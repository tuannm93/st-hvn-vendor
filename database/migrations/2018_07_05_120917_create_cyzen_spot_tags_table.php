<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCyzenSpotTagsTable extends Migration
{
    /**
     * Get spot tags for create cyzen spot
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cyzen_spot_tags', function (Blueprint $table) {
            $table->string('spot_tag_id', 50);
            $table->string('group_id', 50);
            $table->string('spot_tag_name', 50);
            $table->timestamps();
            $table->timestamp('crawler_time');

            $table->primary(['spot_tag_id', 'group_id']);

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
        Schema::dropIfExists('cyzen_spot_tags');
    }
}
