<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShortDescriptionToReservationsOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservations_options', function (Blueprint $table) {
            //
            if(!Schema::hasColumn('reservations_options', 'short_description')){
                $table->text('short_description')->nullable();
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
        Schema::table('content_blocks', function (Blueprint $table) {
            //
            if(!Schema::hasColumn('reservations_options', 'short_description')){
                $table->text('short_description')->nullable();
            }          
        });
    }
}
