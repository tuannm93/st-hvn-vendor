<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCyzenSpotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cyzen_spots', function (Blueprint $table) {
            $table->primary('id');

            $table->string('id', 50); #spot_id get from api cyzen
            $table->integer('spot_code')->nullable(); #spot_code get from api cyzen
            $table->string('spot_name_kana', 255)->nullable(); #spot_name_kana get from api cyzen
            $table->integer('spot_name')->nullable(); #spot_name get from api cyzen
            $table->integer('zip_code')->nullable(); #zip_code get from api cyzen
            $table->string('tel', 20)->nullable(); #tel get from api cyzen
            $table->string('fax', 20)->nullable(); #fax get from api cyzen
            $table->text('address'); #address get from api cyzen
            $table->timestamp('valid_from')->nullable(); #valid_from get from api cyzen
            $table->timestamp('valid_to')->nullable(); #valid_to get from api cyzen
            $table->text('url')->nullable(); #url get from api cyzen
            $table->text('comment')->nullable(); #comment get from api cyzen
            $table->string('create_user_id', 50); #create_user_id get from api cyzen
            /*ST_GeographyFromText('Point(longitude latitude)') of latitude and  longitude get from api cyzen */
            $table->point('spot_location');
            $table->timestamps(); #updated_at, created_at get from api cyzen
            $table->timestamp('crawler_time'); #time get data from api cyzen

            //index
            $table->index('spot_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cyzen_spots');
    }
}
