<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'trans_id',
        'trans_user_id',
        'trans_plaid_trans_id',
        'trans_plaid_categories',
        'trans_plaid_amount',
        'trans_plaid_category_id',
        'trans_plaid_date',
        'trans_plaid_name'
    ];

    protected $casts = [
        'trans_plaid_date' => 'date',
        'trans_plaid_amount' => 'float'
    ];

    public function getTransactionType()
    {
        // Negative amount indicates credit (money coming in)
        // Positive amount indicates debit (money going out)
        return $this->trans_plaid_amount < 0 ? 'credit' : 'debit';
    }

    public function getAfterBalance()
    {
        $initialBalance = 5.00; // Initial balance for all users
        
        $previousTransactions = Transaction::where('trans_user_id', $this->trans_user_id)
            ->where('trans_plaid_date', '<=', $this->trans_plaid_date)
            ->orderBy('trans_plaid_date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $balance = $initialBalance;
        
        foreach ($previousTransactions as $transaction) {
            $balance += $transaction->trans_plaid_amount;
            
            if ($transaction->id === $this->id) {
                break;
            }
        }
        
        return round($balance, 2);
    }
}