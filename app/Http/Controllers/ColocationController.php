<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\Category;
use App\Models\DebtTransfer;
use App\Models\User;
use App\Services\BalanceCalculator;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ColocationController extends Controller
{
    public function create(): View
    {
        if (Auth::user()->activeColocation()) {
            return redirect()->route('dashboard')
                ->with('error', 'You can only have one active colocation at a time.');
        }

        return view('colocations.create');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $colocation = Colocation::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'owner_id' => Auth::id(),
            'status' => 'active',
        ]);

        $colocation->addMember(Auth::user(), 'owner');

        $defaultCategories = [
            ['name' => 'Groceries', 'color' => 'orange', 'icon' => 'shopping_cart'],
            ['name' => 'Utilities', 'color' => 'blue', 'icon' => 'bolt'],
            ['name' => 'Internet', 'color' => 'purple', 'icon' => 'wifi'],
            ['name' => 'Cleaning', 'color' => 'gray', 'icon' => 'cleaning_services'],
            ['name' => 'Rent', 'color' => 'green', 'icon' => 'home'],
            ['name' => 'Other', 'color' => 'slate', 'icon' => 'more_horiz'],
        ];

        foreach ($defaultCategories as $category) {
            Category::create([
                'name' => $category['name'],
                'color' => $category['color'],
                'icon' => $category['icon'],
                'colocation_id' => $colocation->id,
            ]);
        }

        return redirect()->route('colocations.show', $colocation)
            ->with('success', 'Colocation created successfully!');
    }
    public function show(Colocation $colocation, Request $request): View
    {
        if (!$colocation->hasActiveMember(Auth::user())) {
            abort(403, 'Unauthorized access to this colocation.');
        }

        $monthFilter = $request->get('month', 'all');
        
        $expensesQuery = $colocation->expenses()->with(['payer', 'category']);
        
        if ($monthFilter !== 'all') {
            $expensesQuery->whereRaw('EXTRACT(MONTH FROM date) = ?', [$monthFilter]);
        }
        
        $expenses = $expensesQuery->orderBy('date', 'desc')->get();

        $members = $colocation->activeMembers()->get();

        $balances = BalanceCalculator::calculateColocationBalances($colocation);
        $settlements = [];
        foreach ($balances as $balance) {
            foreach ($balance['individual_balances'] as $debt) {
                $settlements[] = [
                    'from' => $balance['user'],
                    'to' => $debt['to'],
                    'amount' => $debt['amount'],
                ];
            }
        }
        
        $categoryStats = $colocation->categories()
            ->withSum('expenses', 'amount')
            ->get()
            ->filter(fn ($category) => ($category->expenses_sum_amount ?? 0) > 0)
            ->sortByDesc('expenses_sum_amount')
            ->values();

        $monthlyStats = $colocation->expenses()
            ->selectRaw('EXTRACT(YEAR FROM date) as year, EXTRACT(MONTH FROM date) as month, SUM(amount) as total')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        
        $availableMonths = $colocation->expenses()
            ->selectRaw('DISTINCT EXTRACT(MONTH FROM date) as month')
            ->orderBy('month')
            ->pluck('month')
            ->toArray();

        return view('colocations.show', compact(
            'colocation',
            'expenses',
            'members',
            'monthFilter',
            'availableMonths',
            'settlements',
            'categoryStats',
            'monthlyStats'
        ));
    }
    public function edit(Colocation $colocation): View
    {
        if (!$colocation->isOwnerOf(Auth::user())) {
            abort(403, 'Only owners can edit colocations.');
        }

        return view('colocations.edit', compact('colocation'));
    }
    public function update(Request $request, Colocation $colocation): RedirectResponse
    {
        if (!$colocation->isOwnerOf(Auth::user())) {
            abort(403, 'Only owners can update colocations.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $colocation->update($validated);

        return redirect()->route('colocations.show', $colocation)
            ->with('success', 'Colocation updated successfully!');
    }

    /**
     * Remove the specified colocation from storage.
     */
    public function destroy(Colocation $colocation): RedirectResponse
    {
        if (!$colocation->isOwnerOf(Auth::user())) {
            abort(403, 'Only owners can delete colocations.');
        }

        $this->updateReputationOnCancellation($colocation);
        $this->markAllMembersLeft($colocation);
        $colocation->cancel();

        return redirect()->route('dashboard')
            ->with('success', 'Colocation cancelled successfully!');
    }

    public function removeMember(Colocation $colocation, User $user): RedirectResponse
    {
        if (!$colocation->isOwnerOf(Auth::user())) {
            abort(403, 'Only owners can manage members.');
        }

        if ($colocation->owner_id === $user->id) {
            return back()->with('error', 'Cannot remove the owner from the colocation.');
        }

        $this->transferDebtToOwner($user, $colocation);
        $colocation->removeMember($user);

        $this->updateReputationOnLeave($user, $colocation);

        return back()->with('success', 'Member removed successfully!');
    }

    public function leave(Colocation $colocation): RedirectResponse
    {
        if ($colocation->owner_id === Auth::id()) {
            return back()->with('error', 'Owners cannot leave their own colocation. Cancel it instead.');
        }

        $colocation->removeMember(Auth::user());

        $this->updateReputationOnLeave(Auth::user(), $colocation);

        return redirect()->route('dashboard')
            ->with('success', 'You have left the colocation.');
    }

    private function updateReputationOnLeave(User $user, Colocation $colocation): void
    {
        $balance = $this->calculateUserBalance($user, $colocation);
        
        if ($balance < 0) {
            $user->decrement('reputation');
        } else {
            $user->increment('reputation');
        }
    }

    /**
     * Calculate user balance in a colocation.
     */
    private function calculateUserBalance(User $user, Colocation $colocation): float
    {
        $balances = BalanceCalculator::calculateColocationBalances($colocation);

        return $balances[$user->id]['balance'] ?? 0.0;
    }

    private function transferDebtToOwner(User $member, Colocation $colocation): void
    {
        $balances = BalanceCalculator::calculateColocationBalances($colocation);

        if (!isset($balances[$member->id])) {
            return;
        }

        $memberBalance = $balances[$member->id]['balance'];
        if ($memberBalance >= 0) {
            return;
        }

        foreach ($balances[$member->id]['individual_balances'] as $debt) {
            DebtTransfer::create([
                'colocation_id' => $colocation->id,
                'from_user_id' => $colocation->owner_id,
                'to_user_id' => $debt['to']->id,
                'origin_user_id' => $member->id,
                'amount' => $debt['amount'],
                'reason' => 'member_removed_with_debt',
            ]);
        }
    }

    private function updateReputationOnCancellation(Colocation $colocation): void
    {
        $balances = BalanceCalculator::calculateColocationBalances($colocation);
        $members = $colocation->activeMembers()->get();

        foreach ($members as $member) {
            $balance = $balances[$member->id]['balance'] ?? 0;

            if ($balance < 0) {
                $member->decrement('reputation');
            } else {
                $member->increment('reputation');
            }
        }
    }

    private function markAllMembersLeft(Colocation $colocation): void
    {
        $members = $colocation->activeMembers()->get();

        foreach ($members as $member) {
            $colocation->removeMember($member);
        }
    }
}
