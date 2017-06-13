<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('invoices')) {
            Schema::create('invoices', function (Blueprint $table) {
                $table->increments('id');
                $table->text('invoice_number');
                $table->date('date');
                $table->integer('week');
                $table->tinyInteger('paid');
                $table->string('invoicenumber');
                $table->integer('company_id');
                $table->string('filename');
                $table->string('hash');
                $table->decimal('total_saldo', 10, 2);
                $table->integer('total_persons');
                $table->date('start_date');
                $table->date('end_date');
                $table->string('type');
                $table->integer('period');
                $table->longText('products');
                $table->timestamps();
                $table->dateTime('next_invoice_at');
                $table->integer('reminder');
                $table->decimal('total_price', 10, 2);
                $table->integer('is_debit');
                $table->string('debit_card');
                $table->string('payment_method');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('invoices');
    }

}
