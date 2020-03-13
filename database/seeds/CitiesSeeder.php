<?php

use Illuminate\Database\Seeder;
use App\City;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new City([
            'code' => 'BSK',
            'name' => 'Брянск',
            'longitude' => 53.2446861,
            'latitude'  => 34.3657761,
            'temperature' => null
        ]))->save();
    }
}
