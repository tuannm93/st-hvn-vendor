<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCyzenUserGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cyzen_user_groups', function (Blueprint $table) {
            $table->string('user_id', 50); #user_id get from cyzen api
            $table->string('group_id', 50); #group_id get from cyzen api
            $table->boolean('is_group_owner'); #is_group_owner get from cyzen api
            $table->timestamps(); #updated_at, created_at get from api cyzen
            $table->timestamp('crawler_time'); #time get data from api cyzen

            $table->primary(['user_id', 'group_id']);

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
        Schema::dropIfExists('cyzen_user_groups');
    }
}
