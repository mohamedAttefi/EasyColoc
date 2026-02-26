<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\Payment;
use App\Models\User;
use App\Services\BalanceCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function store(Request $request, Colocation $colocation): RedirectResponse
    {
        $currentUser = Auth::user();

        if (!$colocation->hasActiveMember($currentUser)) {
            abort(403, 'Unauthorized access to this colocation.');
        }

        $validated = $request->validate([
            'payer_id' => 'required|exists:users,id',
            'receiver_id' => 'required|exists:users,id|different:payer_id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $payer = User::find($validated['payer_id']);
        $receiver = User::find($validated['receiver_id']);

        if (!$payer || !$receiver) {
            return back()->with('error', 'Invalid settlement members.');
        }

        if (!$colocation->hasActiveMember($payer) || !$colocation->hasActiveMember($receiver)) {
            return back()->with('error', 'Settlement members must belong to this colocation.');
        }

        if ($currentUser->id !== $payer->id && !$colocation->isOwnerOf($currentUser)) {
            abort(403, 'Only the debtor or owner can mark this as paid.');
        }

        $balances = BalanceCalculator::calculateColocationBalances($colocation);
        $expectedAmount = 0;

        foreach ($balances[$payer->id]['individual_balances'] ?? [] as $debt) {
            if ($debt['to']->id === $receiver->id) {
                $expectedAmount = $debt['amount'];
                break;
            }
        }

        if ($expectedAmount <= 0) {
            return back()->with('error', 'No outstanding settlement for these members.');
        }

        $amount = (float) $validated['amount'];
        if ($amount - $expectedAmount > 0.01) {
            return back()->with('error', 'Settlement amount exceeds the current debt.');
        }

        Payment::create([
            'amount' => $amount,
            'description' => 'Settlement',
            'payer_id' => $payer->id,
            'receiver_id' => $receiver->id,
            'colocation_id' => $colocation->id,
            'paid_at' => now(),
        ]);

        return back()->with('success', 'Payment marked as paid.');
    }
}
