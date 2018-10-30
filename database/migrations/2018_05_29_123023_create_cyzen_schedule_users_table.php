<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCyzenScheduleUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cyzen_schedule_users', function (Blueprint $table) {
            $table->increments('id'); #id autoincrement
            $table->string('schedule_id', 50); #schedule_id get from table cyzen_schedules
            $table->string('user_id', 50); #user_id get from table cyzen_users
            $table->timestamps(); #updated_at, created_at
            $table->timestamp('crawler_time'); #time get data from api cyzen

            //relationship
            $table->foreign('schedule_id')->references('id')->on('cyzen_schedules')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('cyzen_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cyzen_schedule_users');
    }
}
