<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreferencesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('preferences')) {
            Schema::create('preferences', function (Blueprint $table) {
                $table->increments('id');
                $table->string('slug', 500);
                $table->string('name', 500);
                $table->integer('category_id');
                $table->timestamps();
                $table->integer('no_frontpage');
                $table->integer('clicks');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('preferences');
    }

}
