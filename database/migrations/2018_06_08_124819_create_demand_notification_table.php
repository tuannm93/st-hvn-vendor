<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDemandNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demand_notification', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('demand_id');
            $table->string('user_id', 50); //user id sharing tech
            $table->string('spot_id', 50)->nullable(); //spot_id get from cyzen api
            $table->string('group_id', 50)->nullable(); //group_id cyzen
            $table->string('cyzen_user_id', 50)->nullable(); //user_id from cyzen
            $table->timestamp('call_time_from');
            $table->timestamp('call_time_to');
            $table->timestamp('draft_start_time');
            $table->timestamp('draft_end_time');
            $table->integer('status'); //status to manager push notification
            $table->integer('commission_id'); // commission id from commission_infos

            $table->index('demand_id');
            $table->index('status');
            $table->index('spot_id');
            $table->index('group_id');

            $table->foreign('commission_id')->references('id')->on('commission_infos')->onDelete('cascade');
            $table->foreign('demand_id')->references('id')->on('demand_infos')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('m_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demand_notification');
    }
}
