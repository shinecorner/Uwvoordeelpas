<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToContentBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('content_blocks', function (Blueprint $table) {
            //
            if(!Schema::hasColumn('content_blocks', 'type')){
                $table->string('type')->nullable();
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
            if(!Schema::hasColumn('content_blocks', 'type')){
                $table->string('type')->nullable();
            }          
        });
    }
}
