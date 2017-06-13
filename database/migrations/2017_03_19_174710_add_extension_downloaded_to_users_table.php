<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtensionDownloadedToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if(!Schema::hasColumn('users', 'extension_downloaded')){
                $table->integer('extension_downloaded')->default(0)->nullable();
            }            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            if(Schema::hasColumn('users', 'extension_downloaded')){
                $table->dropColumn('extension_downloaded');
            }            
        });
    }
}
