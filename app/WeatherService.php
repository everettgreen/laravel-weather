<?php


namespace app;


use GuzzleHttp\Exception\RequestException;

class WeatherService
{

    protected $apiEndpoint = 'http://api.openweathermap.org/data/2.5/';
    protected $country_code = 'US';
    protected $postcode;

    public function requestByPostcode($postcode) {
        $this->postcode = $postcode;
        return $this->requestSnapshot();
    }

    public function requestSnapshot() {
        return $this->loadApiResponse();
    }

    protected function loadApiResponse() {
        $client = new \GuzzleHttp\Client(['base_uri' => $this->apiEndpoint]);
        $result = $client->get('weather', ['query' => $this->getQueryParams()]);
        if (!$this->resultIsValid($result)) {
            throw new Exception('Invalid response from OpenWeatherMap');
        }
        return $result->getBody()->getContents();
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

}