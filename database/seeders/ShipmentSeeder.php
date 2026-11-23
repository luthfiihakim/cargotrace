<?php

namespace Database\Seeders;

use App\Models\Carrier;
use App\Models\Location;
use App\Models\Scenario;
use App\Models\Shipment;
use App\Models\User;
use App\Services\ShipmentService;
use Illuminate\Database\Seeder;

class ShipmentSeeder extends Seeder
{
    public function run(): void
    {
        if (Shipment::query()->exists()) {
            return;
        }

        $operator = User::where('email', 'operator@cargotrace.test')->firstOrFail();
        $service = app(ShipmentService::class);

        $rows = [
            ['A', 'customer1@cargotrace.test', 'BSF', 'IDJKT', 'NLRTM', 'Furniture', 12500, 48.5, 2, 32, [['Teak dining sets', 120, 6000, '940360'], ['Rattan chairs', 300, 4500, '940153']]],
            ['A', 'customer2@cargotrace.test', 'PML', 'SGSIN', 'DEHAM', 'Electronics', 4200, 22, 3, 30, [['LED monitors', 800, 3000, '852859'], ['Keyboards', 1500, 1200, '847160']]],
            ['A', 'customer1@cargotrace.test', 'TAL', 'MYPKG', 'GBFXT', 'Palm oil derivatives', 20000, 25, 5, 35, [['Refined palm oil drums', 100, 20000, '151190']]],
            ['B', 'customer2@cargotrace.test', 'PML', 'CNSHA', 'USLAX', 'Machinery parts', 9800, 40, 1, 21, [['CNC spare parts', 500, 9800, '848390']]],
            ['B', 'customer1@cargotrace.test', 'PML', 'KRPUS', 'USLAX', 'Industrial robots', 3500, 18, 2, 19, [['Robot arms', 12, 3500, '847950']]],
            ['B', 'customer2@cargotrace.test', 'BSF', 'IDSUB', 'USLAX', 'Coffee beans', 18000, 33, 4, 34, [['Arabica coffee bags', 300, 18000, '090111']]],
            ['C', 'customer1@cargotrace.test', 'ABS', 'SGSIN', 'AUSYD', 'Consumer goods', 7600, 30, 2, 14, [['Kitchenware sets', 2000, 7600, '732393']]],
            ['C', 'customer2@cargotrace.test', 'BSF', 'IDJKT', 'AUMEL', 'Textiles', 5400, 26, 3, 17, [['Cotton garments', 5000, 5400, '620520']]],
            ['C', 'customer1@cargotrace.test', 'ABS', 'MYPKG', 'AUBNE', 'Rubber products', 11000, 36, 1, 15, [['Rubber gaskets', 8000, 11000, '401699']]],
        ];

        foreach ($rows as [$scenario, $email, $carrier, $origin, $destination, $cargo, $weight, $volume, $departureDays, $arrivalDays, $items]) {
            $service->create([
                'customer_id' => User::where('email', $email)->value('id'),
                'carrier_id' => Carrier::where('code', $carrier)->value('id'),
                'scenario_id' => Scenario::where('code', $scenario)->value('id'),
                'origin_location_id' => Location::where('code', $origin)->value('id'),
                'destination_location_id' => Location::where('code', $destination)->value('id'),
                'cargo_type' => $cargo,
                'weight_kg' => $weight,
                'volume_cbm' => $volume,
                'expected_departure_at' => now()->addDays($departureDays),
                'expected_arrival_at' => now()->addDays($arrivalDays),
                'items' => collect($items)->map(fn ($item) => [
                    'description' => $item[0],
                    'quantity' => $item[1],
                    'weight_kg' => $item[2],
                    'hs_code' => $item[3],
                ])->all(),
            ], $operator);
        }
    }
}
