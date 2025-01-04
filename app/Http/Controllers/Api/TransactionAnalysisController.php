<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionAnalysisController extends Controller
{
    public function getClosingBalances(Request $request)
    {
        $userId = $request->user_id;
        $endDate = Carbon::now();
        $startDate = $endDate->copy()->subDays(90);
        
        $dailyBalances = [];
        $initialBalance = 5.00;
        
        for ($date = $startDate; $date <= $endDate; $date = $date->copy()->addDay()) {
            $transactions = Transaction::where('trans_user_id', $userId)
                ->where('trans_plaid_date', '<=', $date)
                ->orderBy('trans_plaid_date', 'asc')
                ->get();
            
            $balance = $initialBalance;
            foreach ($transactions as $transaction) {
                $balance += $transaction->trans_plaid_amount; // Will subtract if amount is negative
            }
            
            $dailyBalances[$date->format('Y-m-d')] = $balance;
        }
        
        // Calculate averages
        $allDaysAvg = array_sum($dailyBalances) / count($dailyBalances);
        
        $first30Days = array_slice($dailyBalances, 0, 30);
        $last30Days = array_slice($dailyBalances, -30);
        
        $first30Avg = array_sum($first30Days) / count($first30Days);
        $last30Avg = array_sum($last30Days) / count($last30Days);
        
        return response()->json([
            'daily_balances' => $dailyBalances,
            'ninety_day_average' => round($allDaysAvg, 2),
            'first_30_days_average' => round($first30Avg, 2),
            'last_30_days_average' => round($last30Avg, 2)
        ]);
    }

    public function getTransactionAnalysis(Request $request)
    {
        $userId = $request->user_id;
        $startDate = Carbon::now()->subDays(30);
        
        // Last 30 days income except category 18020004
        $income = Transaction::where('trans_user_id', $userId)
            ->where('trans_plaid_date', '>=', $startDate)
            ->where('trans_plaid_amount', '>', 0)
            ->where('trans_plaid_category_id', '!=', '18020004')
            ->sum('trans_plaid_amount');
        
        // Debit transaction count in 30 days (positive amounts are debits)
        $debitCount = Transaction::where('trans_user_id', $userId)
            ->where('trans_plaid_date', '>=', $startDate)
            ->where('trans_plaid_amount', '>', 0)
            ->count();
        
        // Sum of debit transactions on weekends
        $weekendDebits = Transaction::where('trans_user_id', $userId)
            ->where('trans_plaid_amount', '>', 0)
            ->whereRaw('DAYOFWEEK(trans_plaid_date) IN (6,7,1)') // Friday, Saturday, Sunday
            ->sum('trans_plaid_amount');
        
        // Sum of income > 15
        $highIncome = Transaction::where('trans_user_id', $userId)
            ->where('trans_plaid_amount', '>', 15)
            ->sum('trans_plaid_amount');
        
        return response()->json([
            'last_30_days_income' => round($income, 2),
            'debit_transaction_count' => $debitCount,
            'weekend_debit_sum' => round($weekendDebits, 2),
            'high_income_sum' => round($highIncome, 2)
        ]);
    }
}