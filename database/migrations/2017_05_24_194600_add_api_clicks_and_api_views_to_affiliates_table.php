<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApiClicksAndApiViewsToAffiliatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('affiliates', function (Blueprint $table) {
        	
        	if (!Schema::hasColumn('affiliates', 'api_clicks')) {
        		$table->integer('api_clicks')->after('clicks');
        	}
        	
        	if (!Schema::hasColumn('affiliates', 'api_views')) {
        		$table->integer('api_views')->after('api_clicks');
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
        Schema::table('affiliates', function (Blueprint $table) {
         	if (!Schema::hasColumn('affiliates', 'api_clicks')) {
        		$table->dropColumn('api_clicks');
        	}
        	if (!Schema::hasColumn('affiliates', 'api_views')) {
        		$table->dropColumn('api_views');
        	}
        });
    }
}
