<?php

namespace App\Services;

use App\Enums\ShipmentStatus;
use App\Models\Shipment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ShipmentService
{
    public function create(array $data, User $actor): Shipment
    {
        return DB::transaction(function () use ($data, $actor) {
            $items = $data['items'];
            unset($data['items']);

            $shipment = new Shipment($data);
            $shipment->shipment_number = $this->generateNumber();
            $shipment->created_by = $actor->id;
            $shipment->current_status = ShipmentStatus::Created;
            $shipment->estimated_arrival_at = $data['expected_arrival_at'];
            $shipment->save();

            $shipment->items()->createMany($items);

            return $shipment;
        });
    }

    public function update(Shipment $shipment, array $data): Shipment
    {
        return DB::transaction(function () use ($shipment, $data) {
            $items = $data['items'];
            unset($data['items']);

            $shipment->update($data);
            $shipment->items()->delete();
            $shipment->items()->createMany($items);

            return $shipment;
        });
    }

    private function generateNumber(): string
    {
        do {
            $number = 'CT-'.now()->format('Ymd').'-'.strtoupper(Str::random(5));
        } while (Shipment::withTrashed()->where('shipment_number', $number)->exists());

        return $number;
    }
}
