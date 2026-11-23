<?php

namespace App\Enums;

enum ScenarioKpi: string
{
    case OnTimeRate = 'on_time_rate';
    case AvgDelayDuration = 'avg_delay_duration';
    case ExceptionResolutionTime = 'exception_resolution_time';

    public function label(): string
    {
        return match ($this) {
            self::OnTimeRate => 'On-time delivery rate',
            self::AvgDelayDuration => 'Average delay duration',
            self::ExceptionResolutionTime => 'Exception resolution time',
        };
    }
}
