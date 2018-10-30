<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMStaffCategoryExclusions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_staff_category_exclusions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('jis_cd', 50); //jis_cd
            $table->integer('genre_id'); //genre_id
            $table->integer('category_id'); //category_id
            $table->string('user_id', 50); //user_id
            $table->timestamp('public_start')->nullable();
            $table->timestamp('public_end')->nullable();
            $table->string('created_by', 50);
            $table->string('updated_by', 50);
            $table->timestamp('create_at');
            $table->timestamp('updated_at');

            $table->index('jis_cd');
            $table->index('genre_id');
            $table->index('category_id');

            $table->foreign('user_id')->references('user_id')->on('m_users')->onDelete('cascade');
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
        Schema::dropIfExists('m_staff_category_exclusions');
    }
}
