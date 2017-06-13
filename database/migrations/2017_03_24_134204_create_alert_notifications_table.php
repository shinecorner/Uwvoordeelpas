<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlertNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('alert_notifications')) {
            Schema::create('alert_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('content');
            $table->integer('is_on');
            $table->timestamps();
            $table->integer('width');
            $table->integer('height');
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
        Schema::dropIfExists('alert_notifications');
    }
}
