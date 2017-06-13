<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('categories')) {
				Schema::create('categories', function (Blueprint $table) {
				$table->increments('id');
				$table->string('slug');
				$table->string('name');
				$table->integer('subcategory_id');
				$table->longText('extra_categories_id');
				$table->timestamps();
				$table->integer('ad_page_id');
				$table->integer('no_show')->nullable();
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
        Schema::dropIfExists('categories');
    }
}
