<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('content_blocks')) {
            Schema::create('content_blocks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category');
            $table->string('slug');
            $table->string('name');
            $table->longText('content');
            $table->timestamps();
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
        Schema::dropIfExists('content_blocks');
    }
}
