<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WeatherForecast\WeatherForeCastService;

class GetWeatherForecast extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:weather_forecast {action?} {type?} {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get weather forecast with action, type and date argument';
    /**
     * @var WeatherForeCastService
     */
    protected $service;

    /**
     * GetWeatherForecast constructor.
     *
     * @param WeatherForeCastService $service
     */
    public function __construct(WeatherForeCastService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * Run service get weather forecast
     */
    public function handle()
    {
        $this->service->main($this->arguments());
    }
}
