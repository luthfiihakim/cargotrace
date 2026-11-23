<?php

namespace App\Http\Requests;

use App\Enums\CarrierMode;
use App\Models\Carrier;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CarrierRequest extends FormRequest
{
    public function authorize(): bool
    {
        $carrier = $this->route('carrier');

        return $carrier instanceof Carrier
            ? $this->user()->can('update', $carrier)
            : $this->user()->can('create', Carrier::class);
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('code')) {
            $this->merge(['code' => strtoupper(trim((string) $this->input('code')))]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:20', Rule::unique('carriers', 'code')->ignore($this->route('carrier'))],
            'mode' => ['required', Rule::enum(CarrierMode::class)],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
