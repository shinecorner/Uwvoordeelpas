<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('companies')) {
            Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug');
            $table->string('name');
            $table->integer('user_id');
            $table->integer('caller_id');
            $table->text('description');
            $table->longText('about_us');
            $table->longText('menu');
            $table->longText('details');
            $table->longText('contact');
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
            $table->text('preferences');
            $table->text('price');
            $table->text('kitchens');
            $table->text('allergies');
            $table->text('facilities');
            $table->text('kids');
            $table->text('person');
            $table->text('sustainability');
            $table->text('discount');
            $table->string('kvk');
            $table->string('btw');
            $table->integer('no_show');
            $table->decimal('min_saldo', 10, 2);
            $table->integer('waiter_user_id');
            $table->timestamps();
            $table->text('regio');
            $table->text('days');
            $table->string('website');
            $table->integer('pdf_active');
            $table->string('pdf_name');
            $table->string('pdf_activate_code');
            $table->longText('signature_url');
            $table->string('facebook');
            $table->integer('start_invoice');
            $table->integer('click_registration');
            $table->text('discount_comment');
            $table->integer('clicks');
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
        Schema::dropIfExists('companies');
    }
}
