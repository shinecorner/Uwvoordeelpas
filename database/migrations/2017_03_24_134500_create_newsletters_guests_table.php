<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewslettersGuestsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('newsletters_guests')) {
            Schema::create('newsletters_guests', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->integer('company_id');
                $table->integer('newsletter_id');
                $table->timestamps();
                $table->integer('no_show');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('newsletters_guests');
    }

}
