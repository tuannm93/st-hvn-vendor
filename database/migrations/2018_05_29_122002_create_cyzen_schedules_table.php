<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCyzenSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cyzen_schedules', function (Blueprint $table) {
            $table->primary('id');

            $table->string('id', 50); #schedule_id get from api cyzen
            $table->string('group_id', 50); #group_id get from api cyzen
            $table->string('title', 255)->nullable(); #title get from api cyzen
            $table->text('detail')->nullable(); #detail get from api cyzen
            $table->timestamp('start_date'); #start_date get from api cyzen
            $table->timestamp('end_date'); #end_date get from api cyzen
            $table->boolean('is_all_day'); #is_all_day get from api cyzen
            $table->string('spot_id', 50); #spot_id get from api cyzen
            $table->text('address')->nullable(); #address get from api cyzen
            $table->point('location')->nullable();
            /*ST_GeographyFromText('Point(longitude latitude)') of latitude and  longitude get from api cyzen */
            $table->timestamps(); #updated_at, created_at get from api cyzen
            $table->timestamp('crawler_time'); #time get data from api cyzen

            $table->index('start_date');
            $table->index('end_date');
            $table->index('is_all_day');

            //relationship
            $table->foreign('group_id')->references('id')->on('cyzen_groups')->onDelete('cascade');
            $table->foreign('spot_id')->references('id')->on('cyzen_spots')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cyzen_schedules');
    }
}
