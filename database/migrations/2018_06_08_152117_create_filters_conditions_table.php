<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFiltersConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filters_conditions', function (Blueprint $table) {
            $table->integer('filter_id'); //id from filters
            $table->string('condition_cd', 50); // condition_cd include: fee, schedule, distance
            $table->integer('order'); // order of condition_cd
            //save with string ex: 15% or 10km or 15000 円
            $table->text('limit')->nullable(); //value of condition_cd
            $table->string('created_by', 50);
            $table->string('updated_by', 50);
            $table->timestamp('create_at');
            $table->timestamp('updated_at');

            $table->primary(['filter_id', 'condition_cd']);

            $table->foreign('filter_id')->references('id')->on('filters')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('filters_conditions');
    }
}
