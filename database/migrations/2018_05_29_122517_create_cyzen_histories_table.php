<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCyzenHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cyzen_histories', function (Blueprint $table) {
            $table->string('id', 50); #history_id get from api cyzen
            $table->string('user_id', 50); #user_id get from api cyzen
            $table->string('group_id', 50); #group_id get from api cyzen
            $table->text('history_comment')->nullable(); #history_comment get from api cyzen
            $table->string('status_id', 50); #status_id get from api cyzen
            $table->string('address', 255); #address get from api cyzen
            $table->integer('history_accuracy'); #history_accuracy get from api cyzen
            /*ST_GeographyFromText('Point(longitude latitude)') of latitude and  longitude get from api cyzen */
            $table->point('history_location');
            $table->timestamps(); #updated_at, created_at get from api cyzen
            $table->timestamp('crawler_time'); #time get data from api cyzen

            $table->primary('id');

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
        Schema::dropIfExists('cyzen_histories');
    }
}
