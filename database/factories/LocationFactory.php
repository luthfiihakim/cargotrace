<?php

namespace Database\Factories;

use App\Enums\LocationType;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->city().' Port',
            'code' => strtoupper(fake()->unique()->lexify('?????')),
            'type' => LocationType::Port,
            'city' => fake()->city(),
            'country' => fake()->country(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
        ];
    }
}
