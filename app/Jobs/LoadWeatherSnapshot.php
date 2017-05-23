<?php

namespace App\Jobs;

use App\WeatherSnapshot;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class LoadWeatherSnapshot implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $weatherSnapshot;

    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @param WeatherSnapshot $snapshot
     * @return void
     */
    public function __construct($snapshot)
    {
        $this->weatherSnapshot = $snapshot;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->weatherSnapshot->request();
        $this->weatherSnapshot->save();
    }

    public function fail($exception = null)
    {
        //could notify via email
    }
}
