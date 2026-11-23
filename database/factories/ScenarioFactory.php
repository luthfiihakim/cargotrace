<?php

namespace Database\Factories;

use App\Enums\ExceptionType;
use App\Enums\ScenarioKpi;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScenarioFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->unique()->lexify('S???')),
            'name' => fake()->words(3, true),
            'route_label' => fake()->city().' to '.fake()->city(),
            'delay_threshold_hours' => 12,
            'primary_exception_type' => ExceptionType::CustomsHold,
            'kpi' => ScenarioKpi::OnTimeRate,
            'is_active' => true,
        ];
    }
}
