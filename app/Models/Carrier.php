<?php

namespace App\Models;

use App\Enums\CarrierMode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Carrier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'mode',
        'contact_email',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'mode' => CarrierMode::class,
            'is_active' => 'boolean',
        ];
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }
}
