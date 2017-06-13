<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyReservationsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('company_reservations')) {
            Schema::create('company_reservations', function (Blueprint $table) {
                $table->increments('id');
                $table->date('date');
                $table->time('start_time');
                $table->time('end_time');
                $table->integer('per_time');
                $table->text('available_persons');
                $table->text('available_deals');
                $table->integer('company_id');
                $table->integer('is_locked');
                $table->integer('max_persons');
                $table->timestamps();
                $table->text('locked_times');
                $table->integer('closed_before_time');
                $table->integer('cancel_before_time');
                $table->integer('update_before_time');
                $table->integer('is_manual');
                $table->integer('reminder_before_date');
                $table->integer('extra_reservations');
                $table->time('closed_at_time');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('company_reservations');
    }

}
