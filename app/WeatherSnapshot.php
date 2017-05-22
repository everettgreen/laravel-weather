<?php

namespace App;

use Illuminate\Database\Eloquent\Model;;

class WeatherSnapshot extends Model
{

    protected $apiEndpoint = 'http://api.openweathermap.org/data/2.5/';

    public function loadByPostcode($postcode) {
        $this->postcode = $postcode;
        $this->country_code = 'US';
        $this->loadSnapshot();
    }

    public function loadSnapshot() {
        $this->loadApiResponse();
        $this->hydrateFromResponse();
        $this->save();
    }

    protected function loadApiResponse() {
        $client = new \GuzzleHttp\Client(['base_uri' => $this->apiEndpoint]);
        $result = $client->get('weather', ['query' => $this->getQueryParams()]);
        if (!$this->resultIsValid($result)) {
            throw new Exception('Invalid response from OpenWeatherMap');
        }
        $this->response = $result->getBody()->getContents();
    }

    protected function getQueryParams() {
        return [
            'units' => 'imperial',
            'zip' => $this->postcode . ',' . $this->country_code,
            'appid' => config('services.openweathermap.key')
        ];
    }

    protected function resultIsValid($result) {
        return $result->getStatusCode() == 200;
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
