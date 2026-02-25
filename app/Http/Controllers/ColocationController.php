<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\Category;
use App\Models\User;
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
    public function store(Request $request): RedirectResponse
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
    public function show(Colocation $colocation): View
    {
        if (!$colocation->hasActiveMember(Auth::user())) {
            abort(403, 'Unauthorized access to this colocation.');
        }

        $expenses = $colocation->expenses()
            ->with(['payer', 'category'])
            ->orderBy('date', 'desc')
            ->get();

        $members = $colocation->activeMembers()->get();

        return view('colocations.show', compact('colocation', 'expenses', 'members'));
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
        // Only owners can delete
        if (!$colocation->isOwnerOf(Auth::user())) {
            abort(403, 'Only owners can delete colocations.');
        }

        $colocation->cancel();

        return redirect()->route('dashboard')
            ->with('success', 'Colocation cancelled successfully!');
    }

    /**
     * Remove a member from the colocation.
     */
    public function removeMember(Colocation $colocation, User $user): RedirectResponse
    {
        // Only owners can manage members
        if (!$colocation->isOwnerOf(Auth::user())) {
            abort(403, 'Only owners can manage members.');
        }

        if ($colocation->owner_id === $user->id) {
            return back()->with('error', 'Cannot remove the owner from the colocation.');
        }

        $colocation->removeMember($user);

        // Update reputation based on debts
        $this->updateReputationOnLeave($user, $colocation);

        return back()->with('success', 'Member removed successfully!');
    }

    /**
     * Leave the colocation.
     */
    public function leave(Colocation $colocation): RedirectResponse
    {
        if ($colocation->owner_id === Auth::id()) {
            return back()->with('error', 'Owners cannot leave their own colocation. Cancel it instead.');
        }

        $colocation->removeMember(Auth::user());

        // Update reputation based on debts
        $this->updateReputationOnLeave(Auth::user(), $colocation);

        return redirect()->route('dashboard')
            ->with('success', 'You have left the colocation.');
    }

    /**
     * Update user reputation when leaving a colocation.
     */
    private function updateReputationOnLeave(User $user, Colocation $colocation): void
    {
        $balance = $this->calculateUserBalance($user, $colocation);
        
        if ($balance < 0) {
            // User has debts, decrease reputation
            $user->decrement('reputation');
        } else {
            // User has no debts, increase reputation
            $user->increment('reputation');
        }
    }

    /**
     * Calculate user balance in a colocation.
     */
    private function calculateUserBalance(User $user, Colocation $colocation): float
    {
        $totalPaid = $colocation->expenses()
            ->where('payer_id', $user->id)
            ->sum('amount');

        $totalExpenses = $colocation->expenses()->sum('amount');
        $memberCount = $colocation->activeMembers()->count();
        $userShare = $memberCount > 0 ? $totalExpenses / $memberCount : 0;

        return $totalPaid - $userShare;
    }
}
