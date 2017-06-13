<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLtmTranslationsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('ltm_translations')) {
            Schema::create('ltm_translations', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('status');
                $table->string('locale');
                $table->string('group');
                $table->string('key');
                $table->text('value');
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
        Schema::dropIfExists('ltm_translations');
    }

}
