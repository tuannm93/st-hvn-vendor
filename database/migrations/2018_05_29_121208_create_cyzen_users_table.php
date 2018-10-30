<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCyzenUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cyzen_users', function (Blueprint $table) {
            $table->primary('id');

            $table->string('id', 50); #user_id get from api cyzen
            $table->string('user_login_id', 50); #user_login_id get from api cyzen
            $table->string('user_code', 100); #user_code get from api cyzen
            $table->string('user_name', 255); #user_name get from api cyzen
            $table->string('app_version', 50)->nullable(); #app_version get from api cyzen
            $table->string('device', 50)->nullable(); #device get from api cyzen
            $table->string('os_version', 50)->nullable(); #os_version get from api cyzen
            $table->timestamps(); #updated_at, created_at get from api cyzen
            $table->timestamp('crawler_time'); #time get data from api cyzen
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cyzen_users');
    }
}
