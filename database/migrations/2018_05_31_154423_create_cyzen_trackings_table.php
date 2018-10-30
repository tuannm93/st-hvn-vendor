<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCyzenTrackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cyzen_trackings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id', 50); #user_id get from cyzen_api
            $table->string('group_id', 50); #group_id get from cyzen_api
            $table->string('address', 255); #address get from cyzen_api
            $table->integer('tracking_accuracy'); #tracking_accuracy ||  history_accuracy get from api cyzen
            /*ST_GeographyFromText('Point(longitude latitude)') with tracking_latitude, tracking_longitude ||
            history_latitude, history_longitude get from api cyzen*/
            $table->point('tracking_location');
            $table->timestamp('created_at');
            $table->timestamp('crawler_time'); #time get data from api cyzen

            //relationship
            $table->foreign('user_id')->references('id')->on('cyzen_users')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('cyzen_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cyzen_trackings');
    }
}
