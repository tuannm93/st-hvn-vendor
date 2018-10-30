<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCyzenStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cyzen_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('group_id', 50);
            $table->string('status_id', 50);
            $table->string('status_name', 50);
            $table->timestamp('crawler_time');
            $table->timestamps();

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
        Schema::dropIfExists('cyzen_statuses');
    }
}
