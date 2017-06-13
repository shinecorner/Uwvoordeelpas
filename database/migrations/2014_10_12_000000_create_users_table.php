<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('system_settings')) {
            Schema::create('system_settings', function (Blueprint $table) {
                $table->increments('id');
                $table->string('key');
                $table->text('value');
            });
        }
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('email');
                $table->string('password');
                $table->string('phone');
                $table->text('permissions');
                $table->timestamp('last_login');
                $table->integer('gender');
                $table->text('city');
                $table->text('preferences');
                $table->text('kitchens');
                $table->text('allergies');
                $table->text('facilities');
                $table->text('kids');
                $table->text('price');
                $table->text('sustainability');
                $table->text('discount');
                $table->string('new_email');
                $table->string('new_email_code');
                $table->decimal('saldo', 10, 2);
                $table->integer('newsletter');
                $table->timestamps();
                $table->timestamp('expired_at');
                $table->integer('default_role_id');
                $table->string('google_id');
                $table->integer('terms_active');
                $table->integer('cashback_popup');
                $table->text('source');
                $table->string('facebook_id');
                $table->integer('from_company_id');
                $table->string('expire_code', '500');
                $table->date('birthday_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('system_settings');
        Schema::dropIfExists('users');
    }

}
