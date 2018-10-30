<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('m_users', 'remember_token')) {
            DB::statement('ALTER TABLE m_users ADD COLUMN remember_token CHARACTER VARYING(64) DEFAULT NULL;');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('m_users', 'remember_token')) {
            DB::statement('ALTER TABLE m_users DROP COLUMN remember_token;');
        }
    }
}
