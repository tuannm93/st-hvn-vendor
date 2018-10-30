<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWorkStatusToCommissionInfos extends Migration
{
    /**
     * Run the migrations. Add work status for tables commission_infos
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('commission_infos', 'work_status')) {
            Schema::table('commission_infos', function (Blueprint $table) {
                $table->integer('work_status')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('commission_infos', 'work_status')) {
            Schema::table('commission_infos', function (Blueprint $table) {
                $table->dropColumn('work_status');
            });
        }
    }
}
