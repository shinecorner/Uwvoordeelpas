<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyServicesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('company_services')) {
            Schema::create('company_services', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->text('content');
                $table->integer('tax');
                $table->decimal('price', 5, 2);
                $table->integer('company_id');
                $table->integer('period');
                $table->date('start_date');
                $table->date('end_date');
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
        Schema::dropIfExists('company_services');
    }

}
