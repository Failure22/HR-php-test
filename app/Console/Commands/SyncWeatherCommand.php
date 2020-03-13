<?php

namespace App\Console\Commands;

use App\City;
use App\YandexWeather;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class SyncWeatherCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncs city weather info';

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
        /** @var Collection|City[] $cities */
        $cities = City::all();

        $this->info('Getting temperature data for ' . $cities->count() . ' cities...');
        $i = 1;

        foreach ($cities as $city)
        {
            $this->line($i . '/' . $cities->count() . ' - ' . $city->name . '...');

            try {
                $temperature = (new YandexWeather())->getTemperature($city);

                $city->temperature = $temperature;
                $city->save();

                $this->line($temperature . ' °C');
            } catch (\Exception $ex)
            {
                $this->error('Something went wrong');
            }

            $i++;
        }

        return $this->info('Finished');
    }
}
