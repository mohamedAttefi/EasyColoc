<?php

namespace App\Services;

use App\Models\Colocation;
use App\Models\User;

class BalanceCalculator
{
    public static function calculateColocationBalances(Colocation $colocation): array
    {
        $members = $colocation->activeMembers()->get();
        $balances = [];

        foreach ($members as $member) {
            $balances[$member->id] = [
                'user' => $member,
                'paid' => 0,
                'owes' => 0,
                'balance' => 0,
                'individual_balances' => []
            ];
        }

        $expenses = $colocation->expenses()->get();
        $totalExpenses = $expenses->sum('amount');

        $memberCount = count($members);
        $sharePerPerson = $memberCount > 0 ? $totalExpenses / $memberCount : 0;

        foreach ($members as $member) {
            $memberPaid = 0;
            foreach ($expenses as $expense) {
                if ($expense->payer_id == $member->id) {
                    $memberPaid += $expense->amount;
                }
            }
            
            $memberOwes = $sharePerPerson;
            $memberBalance = $memberPaid - $memberOwes;

            $balances[$member->id]['paid'] = $memberPaid;
            $balances[$member->id]['owes'] = $memberOwes;
            $balances[$member->id]['balance'] = $memberBalance;
        }

        $debtTransfers = $colocation->debtTransfers()->get();
        foreach ($debtTransfers as $transfer) {
            if (!isset($balances[$transfer->from_user_id], $balances[$transfer->to_user_id])) {
                continue;
            }

            $balances[$transfer->from_user_id]['balance'] -= $transfer->amount;
            $balances[$transfer->to_user_id]['balance'] += $transfer->amount;
        }

        $payments = $colocation->payments()->whereNotNull('paid_at')->get();
        foreach ($payments as $payment) {
            if (!isset($balances[$payment->payer_id], $balances[$payment->receiver_id])) {
                continue;
            }

            $balances[$payment->payer_id]['balance'] += $payment->amount;
            $balances[$payment->receiver_id]['balance'] -= $payment->amount;
        }

        $individualBalances = self::calculateIndividualBalances($balances);
        foreach ($balances as $userId => $balance) {
            $balances[$userId]['individual_balances'] = $individualBalances[$userId] ?? [];
        }

        return $balances;
    }

    private static function calculateIndividualBalances(array $balances): array
    {
        $debtors = [];
        $creditors = [];

        foreach ($balances as $userId => $balance) {
            if ($balance['balance'] < 0) {
                $debtors[$userId] = $balance;
            } elseif ($balance['balance'] > 0) {
                $creditors[$userId] = $balance;
            }
        }

        $individualBalances = [];
        foreach ($balances as $userId => $balance) {
            $individualBalances[$userId] = [];
        }

        foreach ($debtors as $debtorId => $debtor) {
            $remainingDebt = abs($debtor['balance']);

            foreach ($creditors as $creditorId => $creditor) {
                if ($remainingDebt <= 0) {
                    break;
                }

                $amountToPay = min($remainingDebt, $creditor['balance']);

                if ($amountToPay <= 0) {
                    continue;
                }

                $individualBalances[$debtorId][] = [
                    'to' => $creditor['user'],
                    'amount' => $amountToPay,
                ];

                $remainingDebt -= $amountToPay;
                $creditors[$creditorId]['balance'] -= $amountToPay;
            }
        }

        return $individualBalances;
    }


    public static function getUserBalanceSummary(User $user, Colocation $colocation): array
    {
        $balances = self::calculateColocationBalances($colocation);
        
        if (!isset($balances[$user->id])) {
            return [
                'total_balance' => 0,
                'total_owed_to_you' => 0,
                'total_you_owe' => 0,
                'individual_balances' => []
            ];
        }

        $userBalance = $balances[$user->id];
        $totalOwedToYou = 0;
        $totalYouOwe = 0;

        foreach ($userBalance['individual_balances'] as $debt) {
            $totalYouOwe += $debt['amount'];
        }

        foreach ($balances as $otherUserId => $otherBalance) {
            if ($otherUserId == $user->id) {
                continue;
            }

            foreach ($otherBalance['individual_balances'] as $debt) {
                if ($debt['to']->id == $user->id) {
                    $totalOwedToYou += $debt['amount'];
                }
            }
        }

        return [
            'total_balance' => $userBalance['balance'],
            'total_owed_to_you' => $totalOwedToYou,
            'total_you_owe' => $totalYouOwe,
            'individual_balances' => $userBalance['individual_balances']
        ];
    }
}
