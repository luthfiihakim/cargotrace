<?php

namespace App\Models;

use App\Enums\ExceptionType;
use App\Enums\ScenarioKpi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Scenario extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'route_label',
        'delay_threshold_hours',
        'primary_exception_type',
        'kpi',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'delay_threshold_hours' => 'integer',
            'primary_exception_type' => ExceptionType::class,
            'kpi' => ScenarioKpi::class,
            'is_active' => 'boolean',
        ];
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }
}
