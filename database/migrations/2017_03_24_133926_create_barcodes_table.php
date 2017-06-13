<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBarcodesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('barcodes')) {
            Schema::create('barcodes', function (Blueprint $table) {
                $table->increments('id');
                $table->string('code');
                $table->integer('company_id');
                $table->integer('is_active');
                $table->timestamps();
                $table->dateTime('expires');
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
        Schema::dropIfExists('barcodes');
    }

}
