<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMStaffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_staffs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sp_user_id', 50); //id sharingtech
            $table->integer('corp_id'); //kameiten id
            $table->string('cyzen_user_id', 50)->nullable(); // cyzen user id
            $table->string('staff_role', 50)->nullable(); //role staff
            $table->integer('staff_phone')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->string('created_by', 50);
            $table->string('updated_by', 50);

            $table->index('staff_role');

            $table->foreign('corp_id')->references('id')->on('m_corps')->onDelete('cascade');
            $table->foreign('sp_user_id')->references('user_id')->on('m_users')->onDelete('cascade');
            $table->foreign('cyzen_user_id')->references('id')->on('cyzen_users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_staffs');
    }
}
