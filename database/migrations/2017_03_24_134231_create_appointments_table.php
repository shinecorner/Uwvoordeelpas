<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('appointments')) {
            Schema::create('appointments', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('send_mail');
                $table->integer('send_reminder');
                ;
                $table->longText('comment');
                $table->dateTime('appointment_at');
                $table->timestamps();
                $table->integer('company_id');
                $table->integer('caller_id');
                $table->dateTime('last_reminder_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('appointments');
    }

}
