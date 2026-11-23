<?php

namespace Database\Factories;

use App\Enums\ShipmentStatus;
use App\Models\Carrier;
use App\Models\Location;
use App\Models\Scenario;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ShipmentFactory extends Factory
{
    public function definition(): array
    {
        $arrival = now()->addDays(20)->startOfMinute();

        return [
            'shipment_number' => 'CT-'.now()->format('Ymd').'-'.strtoupper(Str::random(5)),
            'customer_id' => User::factory(),
            'carrier_id' => Carrier::factory(),
            'scenario_id' => Scenario::factory(),
            'origin_location_id' => Location::factory(),
            'destination_location_id' => Location::factory(),
            'created_by' => User::factory()->operator(),
            'cargo_type' => fake()->randomElement(['Electronics', 'Furniture', 'Textiles', 'Machinery']),
            'weight_kg' => fake()->randomFloat(2, 100, 20000),
            'volume_cbm' => fake()->randomFloat(3, 1, 80),
            'expected_departure_at' => now()->addDays(2)->startOfMinute(),
            'expected_arrival_at' => $arrival,
            'estimated_arrival_at' => $arrival,
            'current_status' => ShipmentStatus::Created,
        ];
    }

    public function status(ShipmentStatus $status): static
    {
        return $this->state(fn (array $attributes) => [
            'current_status' => $status,
        ]);
    }
}
