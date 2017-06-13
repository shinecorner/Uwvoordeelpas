<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPricePerGuestToReservationsOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservations_options', function (Blueprint $table) {
        	if (!Schema::hasColumn('reservations_options', 'price_per_guest')) {
        		$table->decimal('price_per_guest', 10, 2)->after('price');
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
        if (!Schema::hasColumn('reservations_options', 'price_per_guest')) {
        		$table->dropColumn('price_per_guest');
        	}
        });
    }
}
