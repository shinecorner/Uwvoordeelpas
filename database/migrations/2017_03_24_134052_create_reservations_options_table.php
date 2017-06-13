<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationsOptionsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('reservations_options')) {
            Schema::create('reservations_options', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('price');
                $table->integer('price_from');
                $table->integer('total_amount');
                $table->integer('total_reservations');
                $table->integer('company_id');
                $table->string('name');
                $table->text('description');
                $table->time('time_from');
                $table->time('time_to');
                $table->date('date_from');
                $table->date('date_to');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('reservations_options');
    }

}
