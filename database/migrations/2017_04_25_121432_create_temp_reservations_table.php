<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('temp_reservations')) {
            Schema::create('temp_reservations', function (Blueprint $table) {
                $table->increments('id');
                $table->date('date');
                $table->time('time');
                $table->string('status');
                $table->integer('user_id');
                $table->integer('reservation_id');
                $table->integer('company_id');
                $table->integer('persons');
                $table->string('name');
                $table->string('email');
                $table->string('phone');
                $table->text('preferences');
                $table->text('allergies');
                $table->text('comment');
                $table->decimal('saldo', 10, 2);
                $table->decimal('rest_pay', 10, 2);
                $table->integer('newsletter_company');
                $table->integer('is_cancelled');
                $table->integer('user_is_paid_back');
                $table->integer('restaurant_is_paid');
                $table->string('source');
                $table->timestamps();
                $table->integer('table_nr');
                $table->string('custom_res_id', 800);
                $table->integer('option_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('temp_reservations');
    }
}
