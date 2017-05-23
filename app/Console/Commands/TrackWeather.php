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
        foreach ($trackedPostcodes as $postcode => $frequencyInMinutes) {
            if ($this->postcodeRequiresNewSnapshot($postcode, $frequencyInMinutes)) {
                $this->queueNewSnapshot($postcode);
            }
        }
        return true;
    }

    protected function postcodeRequiresNewSnapshot($postcode, $frequencyInMinutes) {

        $snapshot = WeatherSnapshot::query()
            ->where('postcode', $postcode)
            ->orderByDesc('created_at')
            ->first(['created_at']);

        if (!$snapshot) {
            return true;
        }

        $minutesSinceLastSnapshot = Carbon::now()->diffInMinutes($snapshot->created_at);

        return $minutesSinceLastSnapshot >= $frequencyInMinutes;

    }

    protected function queueNewSnapshot($postcode) {
        $snapshot = new WeatherSnapshot(['postcode' => $postcode]);
        $snapshot->save();
        dispatch(new LoadWeatherSnapshot($snapshot));
    }
}
