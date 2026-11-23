<?php

namespace Database\Seeders;

use App\Enums\ExceptionType;
use App\Enums\ScenarioKpi;
use App\Models\Scenario;
use Illuminate\Database\Seeder;

class ScenarioSeeder extends Seeder
{
    public function run(): void
    {
        $scenarios = [
            ['A', 'Southeast Asia to Europe', 'Southeast Asia → Europe', 12, ExceptionType::CustomsHold, ScenarioKpi::OnTimeRate],
            ['B', 'Asia to North America', 'Asia → North America', 18, ExceptionType::WeatherDelay, ScenarioKpi::AvgDelayDuration],
            ['C', 'Southeast Asia to Australia', 'Southeast Asia → Australia', 24, ExceptionType::DocumentationIssue, ScenarioKpi::ExceptionResolutionTime],
        ];

        foreach ($scenarios as [$code, $name, $route, $threshold, $exception, $kpi]) {
            Scenario::updateOrCreate(['code' => $code], [
                'name' => $name,
                'route_label' => $route,
                'delay_threshold_hours' => $threshold,
                'primary_exception_type' => $exception,
                'kpi' => $kpi,
                'is_active' => true,
            ]);
        }
    }
}
