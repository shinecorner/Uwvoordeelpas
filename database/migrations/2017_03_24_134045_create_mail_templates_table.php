<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailTemplatesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('mail_templates')) {
            Schema::create('mail_templates', function (Blueprint $table) {
                $table->increments('id');
                $table->string('subject');
                $table->longText('content');
                $table->string('category', 500);
                $table->integer('company_id');
                $table->integer('is_active');
                $table->timestamps();
                $table->text('explanation');
                $table->string('type');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('mail_templates');
    }

}
