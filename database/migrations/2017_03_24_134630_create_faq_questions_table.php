<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFaqQuestionsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('faq_questions')) {
            Schema::create('faq_questions', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('category');
                $table->string('title');
                $table->text('answer');
                $table->timestamps();
                $table->integer('clicks');
                $table->integer('subcategory');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('faq_questions');
    }

}
