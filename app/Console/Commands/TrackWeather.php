<?php

namespace App\Console\Commands;

use App\Jobs\LoadWeatherSnapshot;
use App\WeatherSnapshot;
use Carbon\Carbon;
use Illuminate\Console\Command;

class TrackWeather extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trackweather';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Queues any scheduled weather tracking according to config/weather.php';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $trackedPostcodes = config('weather.tracked');
        $currentTime = Carbon::now();
        foreach ($trackedPostcodes as $postcode => $frequencyInMinutes) {
            $lastCreatedAt = \App\WeatherSnapshot::query()
                ->where('postcode', $postcode)
                ->orderByDesc('created_at')
                ->value('created_at');
            if ($currentTime->diffInMinutes($lastCreatedAt) >= $frequencyInMinutes) {
                $snapshot = new WeatherSnapshot(['postcode' => $postcode]);
                dispatch(new LoadWeatherSnapshot($snapshot));
            }
        }
        return true;
    }
}
