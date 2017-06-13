<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuestsThirdPartyTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('guests_third_party')) {
            Schema::create('guests_third_party', function (Blueprint $table) {
                $table->increments('id');
                $table->string('reservation_number', 500);
                $table->string('network_status');
                $table->string('reservation_status');
                $table->string('name', 500);
                $table->string('email');
                $table->string('phone');
                $table->integer('persons');
                $table->mediumText('comment');
                $table->string('network');
                $table->integer('restaurant_id');
                $table->string('restaurant_name');
                $table->string('restaurant_address');
                $table->string('restaurant_zipcode');
                $table->timestamp('reservation_at');
                $table->timestamps();
                $table->dateTime('reservation_date');
                $table->string('mail_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('guests_third_party');
    }

}
