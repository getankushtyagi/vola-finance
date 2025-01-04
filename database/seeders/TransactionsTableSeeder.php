<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionsTableSeeder extends Seeder
{
    public function run()
    {
        // Get the transaction data from the file
        $transactionFile = database_path('data/transaction.php');
        
        if (!file_exists($transactionFile)) {
            $this->command->error('Transaction file not found!');
            return;
        }

        // Include the file and get the $transaction array
        $transaction = require $transactionFile;

        // Prepare the data for insertion
        $formattedTransactions = array_map(function ($trans) {
            return [
                'trans_id' => $trans['trans_id'],
                'trans_user_id' => $trans['trans_user_id'],
                'trans_plaid_trans_id' => $trans['trans_plaid_trans_id'],
                'trans_plaid_categories' => $trans['trans_plaid_categories'],
                'trans_plaid_amount' => $trans['trans_plaid_amount'],
                'trans_plaid_category_id' => $trans['trans_plaid_category_id'],
                'trans_plaid_date' => $trans['trans_plaid_date'],
                'trans_plaid_name' => $trans['trans_plaid_name'],
                'created_at' => now(),
                'updated_at' => now()
            ];
        }, $transaction);

        // Insert the data into the database
        DB::table('transactions')->insert($formattedTransactions);

        $this->command->info('Transactions seeded successfully!');
    }
}