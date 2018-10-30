<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCyzenGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cyzen_groups', function (Blueprint $table) {
            $table->string('id', 50); #group_id get from api cyzen
            $table->string('group_join_id', 50); #group_join_id get from api cyzen
            $table->string('group_code', 255); #group_code get from api cyzen
            $table->string('group_name', 255); #group_name get from api cyzen
            $table->timestamps(); #updated_at, created_at get from api cyzen
            $table->timestamp('crawler_time'); #time get data from api cyzen

            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cyzen_groups');
    }
}
