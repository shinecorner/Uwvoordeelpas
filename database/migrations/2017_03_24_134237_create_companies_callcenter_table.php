<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesCallcenterTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('companies_callcenter')) {
            Schema::create('companies_callcenter', function (Blueprint $table) {
                $table->increments('id');
                $table->string('slug');
                $table->string('name');
                $table->integer('user_id');
                $table->string('email');
                $table->string('phone');
                $table->string('address');
                $table->string('zipcode');
                $table->string('city');
                $table->string('contact_name');
                $table->string('contact_phone');
                $table->string('contact_email');
                $table->string('contact_role');
                $table->string('financial_iban');
                $table->string('financial_iban_tnv');
                $table->string('financial_email');
                $table->string('kvk');
                $table->string('btw');
                $table->text('regio');
                $table->timestamp('called_at');
                $table->timestamps();
                $table->longText('comment');
                $table->timestamp('callback_at');
                $table->integer('caller_id');
                $table->integer('score');
                $table->longText('preferences');
                $table->longText('price');
                $table->longText('kitchens');
                $table->longText('allergies');
                $table->longText('facilities');
                $table->longText('kids');
                $table->longText('person');
                $table->longText('sustainability');
                $table->longText('discount');
                $table->integer('no_show');
                $table->integer('company_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('companies_callcenter');
    }

}
