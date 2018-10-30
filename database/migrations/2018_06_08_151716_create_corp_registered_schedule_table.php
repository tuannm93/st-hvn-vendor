<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCorpRegisteredScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corp_registered_schedule', function (Blueprint $table) {
            $table->integer('corp_id'); //corp id
            $table->integer('genre_id'); //genre id
            $table->integer('category_id'); //category id
            $table->integer('time_finish'); //time to finish (by minutes)
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->string('created_by', 50);
            $table->string('updated_by', 50);

            $table->primary(['corp_id', 'genre_id', 'category_id']);
            $table->index('time_finish');

            $table->foreign('corp_id')->references('id')->on('m_corps')->onDelete('cascade');
            $table->foreign('genre_id')->references('id')->on('m_genres')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('m_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('corp_registered_schedule');
    }
}
