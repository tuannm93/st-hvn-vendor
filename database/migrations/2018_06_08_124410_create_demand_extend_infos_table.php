<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDemandExtendInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demand_extend_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('demand_id'); #demand_id from tables demand_infos
            /*ST_GeographyFromText('Point(longitude latitude)') of latitude and longitude get from address customer*/
            $table->point('location_demand');
            $table->timestamp('contact_time_from')->nullable(); #contact deadline time
            $table->timestamp('contact_time_to')->nullable(); #contact deadline time
            $table->timestamp('est_start_work')->nullable(); #estimate time work
            $table->timestamp('est_end_work')->nullable(); #estimate time work

            $table->index('demand_id');
            $table->index('est_start_work');
            $table->index('est_end_work');

            $table->foreign('demand_id')->references('id')->on('demand_infos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demand_extend_infos');
    }
}
