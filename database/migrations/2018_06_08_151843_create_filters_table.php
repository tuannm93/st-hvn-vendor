<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filters', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('jis_cd')->nullable(); //jis_cd
            $table->integer('genre_id')->nullable(); //genre_id
            $table->integer('category_id')->nullable(); //category_id
            $table->string('created_by', 50);
            $table->string('updated_by', 50);
            $table->timestamp('create_at');
            $table->timestamp('updated_at');
            $table->boolean('is_all_jis_cd'); // present for all jis_cd
            $table->boolean('is_all_genre'); // present for all genre_id
            $table->boolean('is_all_category'); // present for all category_id

            $table->index('jis_cd');
            $table->index('genre_id');
            $table->index('category_id');

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
        Schema::dropIfExists('filters');
    }
}
