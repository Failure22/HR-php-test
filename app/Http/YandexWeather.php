<?php
/**
 * Created by PhpStorm.
 * User: Failure
 * Date: 12.03.2020
 * Time: 18:00
 */

namespace App;

use GuzzleHttp\Client;

/**
 * Yandex Weather temperature integration
 */
class YandexWeather
{
    const API_URL = 'https://api.weather.yandex.ru/v1/forecast';

    /** @var Client */
    private $httpClient;

    /** @var string */
    private $key;

    /**
     * @param string|null $token
     */
    public function __construct(string $token = null)
    {
        $this->httpClient = new Client();

        $this->key = is_null($token) ? env('YANDEX_WEATHER_KEY') : $token;
    }

    /**
     * Gets celsius temperature for current city
     *
     * @param City $city
     *
     * @return int
     */
    public function getTemperature(City $city) : int
    {
        return json_decode($this->httpClient->get(static::API_URL . '?' . $this->getRequestString($city),
            [
                'headers' => [
                        'X-Yandex-API-Key' => $this->key
                    ]
            ])
            ->getBody()->getContents(), true)['fact']['temp'];
    }

    /**
     * Forms Yandex Weather query string (without ?)
     *
     * @param City $city
     *
     * @return string
     */
    public function getRequestString(City $city) : string
    {
        return collect([
            'lat' => $city->latitude,
            'lon' => $city->longitude
        ])
        ->map(function($value, $parameter)
            {
                return sprintf('%s=%s', $parameter, $value);
            })
        ->implode('&');
    }
}