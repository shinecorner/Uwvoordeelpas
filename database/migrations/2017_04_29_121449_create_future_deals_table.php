<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFutureDealsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('future_deals')) {
            Schema::create('future_deals', function (Blueprint $table) {
                $table->increments('id');                
                $table->integer('deal_id');
                $table->integer('user_id');
                $table->decimal('deal_price', 10, 2);
                $table->decimal('deal_base_price', 10, 2);
                $table->integer('persons');
                $table->integer('persons_reserved');
                $table->integer('persons_remain');
                $table->decimal('user_discount', 10, 2);
                $table->decimal('extra_pay', 10, 2);
                $table->date('purchased_date');
                $table->date('expired_at');
                $table->enum('status', ['pending', 'purchased', 'partially_reserved', 'full_reserved']);
                $table->timestamps();
            });
        }
        if (Schema::hasTable('reservations_options')) {
            Schema::table('reservations_options', function (Blueprint $table) {
                if (!Schema::hasColumn('reservations_options', 'expired_at')) {
                    $table->date('expired_at');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('future_deals');
    }

}
