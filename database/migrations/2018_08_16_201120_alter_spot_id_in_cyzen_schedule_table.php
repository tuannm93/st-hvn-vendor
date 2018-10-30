<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AlterSpotIdInCyzenScheduleTable extends Migration
{
    /** @var string $foreignKey */
    public $foreignKey = 'cyzen_schedules_spot_id_foreign';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('cyzen_schedules', 'spot_id')) {
            if ($this->checkExistForeignKey()) {
                DB::statement("ALTER TABLE cyzen_schedules DROP CONSTRAINT $this->foreignKey;");
                DB::statement("ALTER TABLE cyzen_schedules ALTER COLUMN spot_id DROP NOT NULL;");
            }
        }
    }

    /**
     * @return bool
     */
    private function checkExistForeignKey()
    {
        $isHasKey = DB::select("SELECT COUNT(1) FROM pg_constraint WHERE conname='$this->foreignKey';");
        return (bool)$isHasKey[0]->count;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('cyzen_schedules', 'spot_id')) {
            if (!$this->checkExistForeignKey()) {
                try {
                    DB::statement("ALTER TABLE cyzen_schedules
                                    ADD CONSTRAINT $this->foreignKey
                                     FOREIGN KEY (id)
                                      REFERENCES cyzen_spots(id)
                                      on delete cascade;");
                    DB::statement("ALTER TABLE cyzen_schedules ALTER COLUMN spot_id SET NOT NULL;");
                } catch (Exception $ex) {
                    dump('HAVE ERROR CHECK LOG at function: ' . __FUNCTION__ . ' in ' . __CLASS__);
                    Log::error($ex);
                }
            }
        }
    }
}
