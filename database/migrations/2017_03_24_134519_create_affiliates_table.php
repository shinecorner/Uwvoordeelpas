<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAffiliatesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('affiliates')) {
            Schema::create('affiliates', function (Blueprint $table) {
                $table->increments('id');
                $table->string('slug');
                $table->string('name');
                $table->string('link');
                $table->timestamps();
                $table->integer('clicks');
                $table->integer('no_show');
                $table->integer('program_id');
                $table->text('tracking_duration');
                $table->string('affiliate_network', 75);
                $table->string('tracking_link', 500);
                $table->text('compensations');
                $table->string('image_extension');
                $table->text('terms');
                $table->string('time_duration_confirmed');
                $table->string('percent_sales');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('affiliates');
    }

}
