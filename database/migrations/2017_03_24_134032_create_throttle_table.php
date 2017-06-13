<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThrottleTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('throttle')) {
            Schema::create('throttle', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->string('type');
                $table->string('ip');
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
        Schema::dropIfExists('throttle');
    }

}
