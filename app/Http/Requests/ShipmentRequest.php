<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use App\Models\Shipment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $shipment = $this->route('shipment');

        return $shipment instanceof Shipment
            ? $this->user()->can('update', $shipment)
            : $this->user()->can('create', Shipment::class);
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', Rule::exists('users', 'id')->where('role', UserRole::Customer->value)],
            'carrier_id' => ['required', Rule::exists('carriers', 'id')->where('is_active', true)],
            'scenario_id' => ['required', Rule::exists('scenarios', 'id')->where('is_active', true)],
            'origin_location_id' => ['required', 'exists:locations,id'],
            'destination_location_id' => ['required', 'exists:locations,id', 'different:origin_location_id'],
            'cargo_type' => ['required', 'string', 'max:100'],
            'weight_kg' => ['required', 'numeric', 'gt:0', 'max:9999999999'],
            'volume_cbm' => ['required', 'numeric', 'gt:0', 'max:999999999'],
            'expected_departure_at' => ['required', 'date'],
            'expected_arrival_at' => ['required', 'date', 'after:expected_departure_at'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.description' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.weight_kg' => ['nullable', 'numeric', 'min:0'],
            'items.*.hs_code' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'destination_location_id.different' => 'Destination must be different from origin.',
            'expected_arrival_at.after' => 'Expected arrival must be after expected departure.',
        ];
    }
}
