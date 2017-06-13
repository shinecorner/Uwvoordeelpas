<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlertNotificationsGroupsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('alert_notifications_groups')) {
            Schema::create('alert_notifications_groups', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('url');
                $table->longText('notification_ids');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('alert_notifications_groups');
    }

}
