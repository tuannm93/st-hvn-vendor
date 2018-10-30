<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStaffPhoneInMStaffsTable extends Migration
{
    /**
     * Change staff phone to varchar
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('filters', 'jis_cd')) {
            DB::statement('ALTER TABLE m_staffs ALTER COLUMN "staff_phone" TYPE varchar(12);');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('filters', 'jis_cd')) {
            DB::statement('ALTER TABLE m_staffs ALTER COLUMN "staff_phone" TYPE int USING (regexp_replace("staff_phone", \'[^\d]+\', \'\')::int);');
        }
    }
}
