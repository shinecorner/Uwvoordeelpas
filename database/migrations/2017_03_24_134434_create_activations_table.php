<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivationsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('activations')) {
            Schema::create('activations', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->string('code');
                $table->tinyInteger('completed');
                $table->timestamp('completed_at');
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
        Schema::dropIfExists('activations');
    }

}
