<?php

namespace Database\Seeders;

use App\Enums\CarrierMode;
use App\Models\Carrier;
use Illuminate\Database\Seeder;

class CarrierSeeder extends Seeder
{
    public function run(): void
    {
        $carriers = [
            ['Bahari Sea Freight', 'BSF', CarrierMode::Sea, 'ops@bahari-sea.test'],
            ['Pacific Meridian Lines', 'PML', CarrierMode::Sea, 'ops@pacific-meridian.test'],
            ['Austral Bridge Shipping', 'ABS', CarrierMode::Sea, 'ops@austral-bridge.test'],
            ['Trans Archipelago Logistics', 'TAL', CarrierMode::Sea, 'ops@trans-archipelago.test'],
            ['Cakrawala Air Cargo', 'CAC', CarrierMode::Air, 'ops@cakrawala-air.test'],
        ];

        foreach ($carriers as [$name, $code, $mode, $email]) {
            Carrier::updateOrCreate(['code' => $code], [
                'name' => $name,
                'mode' => $mode,
                'contact_email' => $email,
                'is_active' => true,
            ]);
        }
    }
}
