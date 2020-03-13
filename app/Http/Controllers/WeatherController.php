<?php

namespace App\Http\Controllers;

use App\City;
use App\Http\Requests\CityCodeRequest;

class WeatherController extends Controller
{
    /**
     * @param CityCodeRequest $request
     * @param string $cityCode
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(CityCodeRequest $request, string $cityCode)
    {
        /**
         * @var City $city
         */

        $city = City::where('code', '=', $cityCode)->first();

        return view('weather', $city->getAttributes());
    }
}
