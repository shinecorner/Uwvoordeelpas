<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncassosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('incassos')) {
            Schema::create('incassos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('paid');
            $table->string('invoicenumber');
            $table->longText('xml');
            $table->integer('no_of_invoices');
            $table->string('type');
            $table->decimal('amount', 10, 2);
            $table->timestamps();
        });
        }
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('incassos');
    }
}
