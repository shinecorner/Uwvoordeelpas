<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBarcodesUsersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('barcode_users')) {
            Schema::create('barcode_users', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('is_active');
                $table->string('code');
                $table->integer('user_id');
                $table->integer('company_id');
                $table->timestamps();
                $table->integer('barcode_id');
                $table->date('expire_date');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('barcode_users');
    }

}
