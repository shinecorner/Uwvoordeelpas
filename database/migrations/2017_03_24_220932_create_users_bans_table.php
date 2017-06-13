<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersBansTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('users_bans')) {
            Schema::create('users_bans', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->text('reason');
                $table->date('expired_date');
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
        Schema::dropIfExists('users_bans');
    }

}
