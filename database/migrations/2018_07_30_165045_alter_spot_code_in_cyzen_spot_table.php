<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AlterSpotCodeInCyzenSpotTable extends Migration
{
    /**
     * change spot_code to varchar (avoid have same spot code)
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('cyzen_spots', 'spot_code')) {
            DB::statement('ALTER TABLE cyzen_spots ALTER COLUMN "spot_code" TYPE varchar(50);');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('cyzen_spots', 'spot_code')) {
            DB::statement('ALTER TABLE cyzen_spots ALTER COLUMN "spot_code" TYPE int USING (regexp_replace("spot_code", \'[^\d]+\', \'\')::int);');
        }
    }
}
