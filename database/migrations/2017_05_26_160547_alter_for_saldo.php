<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterForSaldo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if(!Schema::hasColumn('users', 'admin_saldo')){
                $table->decimal('admin_saldo', 10, 2)->after('new_email_code');
            }
            if(!Schema::hasColumn('users', 'paid_saldo')){
                $table->decimal('paid_saldo', 10, 2)->after('admin_saldo');
            }
            if(!Schema::hasColumn('users', 'transaction_saldo')){
                $table->decimal('transaction_saldo', 10, 2)->after('paid_saldo');
            }
        });
        Schema::table('transactions', function (Blueprint $table) {
            if(!Schema::hasColumn('transactions', 'used_amount')){
                $table->decimal('used_amount', 10, 2)->after('amount');
            }
            if(!Schema::hasColumn('transactions', 'remain_amount')){
                $table->decimal('remain_amount', 10, 2)->after('used_amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
