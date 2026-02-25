<?php

namespace App\Http\Controllers;

use App\Services\BalanceCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $activeColocation = $user->activeColocation();
        
        if ($activeColocation) {
            $recentExpenses = $activeColocation->expenses()
                ->with(['payer', 'category'])
                ->orderBy('date', 'desc')
                ->limit(5)
                ->get();

            $balanceSummary = BalanceCalculator::getUserBalanceSummary($user, $activeColocation);
            
            $formattedExpenses = [];
            foreach ($recentExpenses as $expense) {
                $formattedExpenses[] = [
                    'title' => $expense->title,
                    'amount' => $expense->amount,
                    'date' => $expense->date->format('M d, Y'),
                    'status' => 'Paid',
                    'status_color' => 'green',
                    'icon' => $expense->category->icon ?? 'receipt',
                    'color' => $expense->category->color ?? 'gray'
                ];
            }

            $debts = [];
            foreach ($balanceSummary['individual_balances'] as $debt) {
                $debts[] = [
                    'name' => $debt['to']->name,
                    'amount' => -$debt['amount']
                ];
            }

            $balances = BalanceCalculator::calculateColocationBalances($activeColocation);
            foreach ($balances as $otherUserId => $otherBalance) {
                if ($otherUserId != $user->id) {
                    foreach ($otherBalance['individual_balances'] as $debt) {
                        if ($debt['to']->id == $user->id) {
                            $debts[] = [
                                'name' => $otherBalance['user']->name,
                                'amount' => $debt['amount']
                            ];
                        }
                    }
                }
            }

            $totalBalance = $balanceSummary['total_balance'];
            $totalDebts = $balanceSummary['total_you_owe'];
            $pendingInvitations = 0;
            
            return view('dashboard', compact(
                'activeColocation',
                'totalBalance',
                'totalDebts', 
                'pendingInvitations',
                'recentExpenses',
                'debts'
            ));
        } else {
            return view('dashboard', compact('activeColocation'));
        }
    }
}
