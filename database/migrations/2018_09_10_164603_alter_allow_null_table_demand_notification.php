<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAllowNullTableDemandNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('demand_notification', 'demand_id')) {
            DB::statement("ALTER TABLE demand_notification DROP CONSTRAINT demand_notification_commission_id_foreign;");
            DB::statement("ALTER TABLE demand_notification DROP CONSTRAINT demand_notification_demand_id_foreign;");
            DB::statement("ALTER TABLE demand_notification ALTER COLUMN demand_id DROP NOT NULL;");
            DB::statement("ALTER TABLE demand_notification ALTER COLUMN commission_id DROP NOT NULL;");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('demand_notification', 'demand_id')) {
            DB::statement("ALTER TABLE demand_notification
                                    ADD CONSTRAINT demand_notification_commission_id_foreign
                                     FOREIGN KEY (id)
                                      REFERENCES commission_infos(id)
                                      on delete cascade;");
            DB::statement("ALTER TABLE demand_notification
                                    ADD CONSTRAINT demand_notification_demand_id_foreign
                                     FOREIGN KEY (id)
                                      REFERENCES demand_infos(id)
                                      on delete cascade;");
            DB::statement("ALTER TABLE demand_notification ALTER COLUMN demand_id SET NOT NULL;");
            DB::statement("ALTER TABLE demand_notification ALTER COLUMN commission_id SET NOT NULL;");
        }
    }
}
