<?php

namespace App\Console;

use App\Console\Commands\AuctionInAdvanceAnnounce;
use App\Console\Commands\CalculateCommission;
use App\Console\Commands\CreateNotCorrespond;
use App\Console\Commands\DemandGuideSendMail;
use App\Console\Commands\Periodically;
use App\Console\Commands\ProgImport;
use App\Console\Commands\ProgImport20;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        AuctionInAdvanceAnnounce::class,
        CreateNotCorrespond::class,
        \App\Console\Commands\AuctionInAdvanceAnnounce::class,
        \App\Console\Commands\CheckFollowDate::class,
        CalculateCommission::class,
        DemandGuideSendMail::class,
        Periodically::class,
        ProgImport::class,
        ProgImport20::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->command('command:prog_import')->cron('05 00 01 * *');
//        $schedule->command('command:prog_import20')->cron('05 00 01 * *');

        // command auction in advance announce
//        $schedule->command('command:auction_in_advance_announce')->everyMinute();
//        $schedule->command('command:create_not_correspond')->cron('00 01 * * *');
//        $schedule->command('command:check_follow_date')->cron('* * * * *');
//        $schedule->command('command:auction_auto_call')->cron('0,20,40 * * * *');
//        $schedule->command('command:periodically')->cron('15 00 * * *');

        // command delete temp file
//        $schedule->command('command:delete_temp_file')->cron('05 00 * * *');
//        $schedule->command('command:weather_forecast I A')->cron('00 06 * * *');
//        $schedule->command('command:weather_forecast FC')->cron('00 10 * * *');

        $schedule->command('command:auction_in_advance_announce')->cron('* * * * *');
        $schedule->command('check_deadline_past_auction')->cron('* * * * *');
        $schedule->command('command:demand_guide_send_mail')->cron('* * * * *');
        $schedule->command('command:check_follow_date')->cron('* * * * *');
        $schedule->command('command:check_refusal_auction')->cron('* * * * *');
        $schedule->command('command:auction_auto_call called')->cron('0,20,40 * * * *');
        $schedule->command('command:calculate_commission')->cron('10 00 * * *');
        $schedule->command('command:periodically')->cron('15 00 * * *');
        $schedule->command('command:create_not_correspond')->cron('00 01 * * *');
        $schedule->command('command:prog_import lock')->cron('05 00 01 * *');
        $schedule->command('command:prog_import20 lock')->cron('05 00 01 * *');
        // command get cyzen data
        $schedule->command('command:cyzen_schedule')->cron('*/10 * * * *');
        $schedule->command('command:cyzen_schedule_one_day')->cron('* * * * *');
        $schedule->command('command:cyzen_create_schedule')->cron('* * * * *');
        $schedule->command('command:cyzen_tracking')->cron('* * * * *');
        $schedule->command('command:cyzen_history')->cron('* * * * *');

        // command run cyzen push notification
//        $schedule->command('command:cyzen_notif_call_time')->cron('* * * * *');
        $schedule->command('command:cyzen_notif_start_work')->cron('* * * * *');
        $schedule->command('command:cyzen_notif_end_work')->cron('* * * * *');
        $schedule->command('command:cyzen_group')->cron('* * * * *');
        $schedule->command('command:cyzen_users')->cron('* * * * *');
        $schedule->command('command:cyzen_spot_tag')->cron('* * * * *');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        include base_path('routes/console.php');
    }
}
