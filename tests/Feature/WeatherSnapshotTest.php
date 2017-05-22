<?php

namespace Tests\Feature;

use App\WeatherSnapshot;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class WeatherSnapshotTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    public function a_user_can_request_knoxville_weather() {
        //This functional test is hitting the API endpoint. Unit test would implement mocks to avoid.
        $snapshot = new WeatherSnapshot();
        $snapshot->loadByPostcode('37917');
        self::assertEquals('Knoxville', $snapshot->location);
    }

}
