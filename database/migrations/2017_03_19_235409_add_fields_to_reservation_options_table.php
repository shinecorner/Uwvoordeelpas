<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToReservationOptionsTable extends Migration
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
            if(!Schema::hasColumn('reservations_options', 'price_from')){
                $table->decimal('price_from', 5, 2)->unsigned()->nullable();
            }
            if(!Schema::hasColumn('reservations_options', 'price')){
                $table->decimal('price', 5, 2)->unsigned()->nullable();
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
        Schema::table('reservations_options', function (Blueprint $table) {
            //
            if(Schema::hasColumn('reservations_options', 'price_from')){
                $table->dropColumn('price_from');
            }
            if(Schema::hasColumn('reservations_options', 'price')){
                $table->dropColumn('price');
            }            
        });
    }
}
