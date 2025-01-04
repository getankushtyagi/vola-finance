<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('trans_id')->unique();
            $table->unsignedBigInteger('trans_user_id');
            $table->string('trans_plaid_trans_id');
            $table->string('trans_plaid_categories');
            $table->decimal('trans_plaid_amount', 10, 2);
            $table->string('trans_plaid_category_id');
            $table->date('trans_plaid_date');
            $table->string('trans_plaid_name');
            $table->timestamps();

            $table->index('trans_user_id');
            $table->index('trans_plaid_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};