<?php

namespace Database\Factories;

use App\Enums\CarrierMode;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarrierFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'code' => strtoupper(fake()->unique()->lexify('???')),
            'mode' => CarrierMode::Sea,
            'contact_email' => fake()->safeEmail(),
            'is_active' => true,
        ];
    }
}
