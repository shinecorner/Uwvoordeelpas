<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemporaryAuthTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('temporary_auth')) {
            Schema::create('temporary_auth', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->string('code');
                $table->longText('redirect_to');
                $table->timestamps();
                $table->integer('terms_active');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('temporary_auth');
    }

}
