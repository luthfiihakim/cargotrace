<?php

namespace Database\Seeders;

use App\Enums\LocationType;
use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            ['IDJKT', 'Tanjung Priok Port', 'Jakarta', 'Indonesia', -6.1049, 106.8850],
            ['IDSUB', 'Tanjung Perak Port', 'Surabaya', 'Indonesia', -7.2000, 112.7330],
            ['SGSIN', 'Port of Singapore', 'Singapore', 'Singapore', 1.2644, 103.8222],
            ['MYPKG', 'Port Klang', 'Klang', 'Malaysia', 3.0000, 101.4000],
            ['NLRTM', 'Port of Rotterdam', 'Rotterdam', 'Netherlands', 51.9490, 4.1420],
            ['DEHAM', 'Port of Hamburg', 'Hamburg', 'Germany', 53.5410, 9.9840],
            ['GBFXT', 'Port of Felixstowe', 'Felixstowe', 'United Kingdom', 51.9540, 1.3510],
            ['CNSHA', 'Port of Shanghai', 'Shanghai', 'China', 31.2304, 121.4737],
            ['KRPUS', 'Port of Busan', 'Busan', 'South Korea', 35.1028, 129.0403],
            ['USLAX', 'Port of Los Angeles', 'Los Angeles', 'United States', 33.7290, -118.2620],
            ['AUSYD', 'Port Botany', 'Sydney', 'Australia', -33.9680, 151.2100],
            ['AUMEL', 'Port of Melbourne', 'Melbourne', 'Australia', -37.8240, 144.9120],
            ['AUBNE', 'Port of Brisbane', 'Brisbane', 'Australia', -27.3800, 153.1700],
        ];

        foreach ($locations as [$code, $name, $city, $country, $lat, $lng]) {
            Location::updateOrCreate(['code' => $code], [
                'name' => $name,
                'type' => LocationType::Port,
                'city' => $city,
                'country' => $country,
                'latitude' => $lat,
                'longitude' => $lng,
            ]);
        }
    }
}
