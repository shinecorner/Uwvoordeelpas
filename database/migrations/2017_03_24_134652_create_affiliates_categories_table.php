<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAffiliatesCategoriesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('affiliates_categories')) {
            Schema::create('affiliates_categories', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('affiliate_id');
                $table->integer('category_id');
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
        Schema::dropIfExists('affiliates_categories');
    }

}
