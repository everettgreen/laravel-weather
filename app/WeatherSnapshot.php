<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WeatherSnapshot extends Model
{

    public function request() {
        $weatherService = new WeatherService();
        $this->response = $weatherService->requestByPostcode($this->postcode);
        $this->hydrateFromResponse();
    }

    protected function hydrateFromResponse() {
        $response = json_decode($this->response);
        $this->location = $response->name;
        $this->conditions_group = $response->weather[0]->main;
        $this->conditions_details = $response->weather[0]->description;
        $this->temperature = $response->main->temp;
        $this->pressure = $response->main->pressure;
        $this->humidity = $response->main->humidity;
        $this->wind_speed = $response->wind->speed;
        $this->wind_direction = $response->wind->deg;
        $this->country_code = $response->sys->country;
    }
}
