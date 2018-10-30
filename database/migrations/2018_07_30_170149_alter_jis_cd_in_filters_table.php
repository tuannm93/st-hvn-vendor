<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AlterJisCdInFiltersTable extends Migration
{
    /**
     * Change jis_cd to varchar
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('filters', 'jis_cd')) {
            DB::statement('ALTER TABLE filters ALTER COLUMN "jis_cd" TYPE varchar(50);');
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
            DB::statement('ALTER TABLE filters ALTER COLUMN "jis_cd" TYPE int USING (regexp_replace("jis_cd", \'[^\d]+\', \'\')::int);');
        }
    }
}
