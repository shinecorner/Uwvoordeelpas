<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('transaction')) {
            Schema::create('transaction', function (Blueprint $table) {
                $table->increments('id');
                $table->string('external_id', '90');
                $table->integer('program_id');
                $table->integer('user_id');
                $table->decimal('amount', 10, 2);
                $table->string('status', 50);
                $table->string('ip', 64);
                $table->dateTime('processed');
                $table->timestamps();
                $table->integer('paid');
                $table->string('affiliate_network');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('transaction');
    }

}
