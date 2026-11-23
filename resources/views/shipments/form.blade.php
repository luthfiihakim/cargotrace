@php
    $editing = $shipment->exists;
    $select = 'mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500';
    $initialItems = old('items', $editing
        ? $shipment->items->map(fn ($item) => [
            'description' => $item->description,
            'quantity' => $item->quantity,
            'weight_kg' => $item->weight_kg,
            'hs_code' => $item->hs_code,
        ])->values()->all()
        : [['description' => '', 'quantity' => 1, 'weight_kg' => '', 'hs_code' => '']]);
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $editing ? 'Edit '.$shipment->shipment_number : 'New shipment' }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ $editing ? route('shipments.update', $shipment) : route('shipments.store') }}" class="bg-white shadow-sm sm:rounded-lg p-6 space-y-6">
                @csrf
                @if ($editing)
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div>
                        <x-input-label for="customer_id" value="Customer" />
                        <select id="customer_id" name="customer_id" class="{{ $select }}" required>
                            <option value="">Select customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" @selected((string) old('customer_id', $shipment->customer_id) === (string) $customer->id)>{{ $customer->name }}{{ $customer->company_name ? ' · '.$customer->company_name : '' }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('customer_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="carrier_id" value="Carrier" />
                        <select id="carrier_id" name="carrier_id" class="{{ $select }}" required>
                            <option value="">Select carrier</option>
                            @foreach ($carriers as $carrier)
                                <option value="{{ $carrier->id }}" @selected((string) old('carrier_id', $shipment->carrier_id) === (string) $carrier->id)>{{ $carrier->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('carrier_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="scenario_id" value="Scenario" />
                        <select id="scenario_id" name="scenario_id" class="{{ $select }}" required>
                            <option value="">Select scenario</option>
                            @foreach ($scenarios as $scenario)
                                <option value="{{ $scenario->id }}" @selected((string) old('scenario_id', $shipment->scenario_id) === (string) $scenario->id)>{{ $scenario->code }} · {{ $scenario->route_label }} ({{ $scenario->delay_threshold_hours }} h)</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('scenario_id')" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <x-input-label for="origin_location_id" value="Origin" />
                        <select id="origin_location_id" name="origin_location_id" class="{{ $select }}" required>
                            <option value="">Select origin</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}" @selected((string) old('origin_location_id', $shipment->origin_location_id) === (string) $location->id)>{{ $location->code }} · {{ $location->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('origin_location_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="destination_location_id" value="Destination" />
                        <select id="destination_location_id" name="destination_location_id" class="{{ $select }}" required>
                            <option value="">Select destination</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}" @selected((string) old('destination_location_id', $shipment->destination_location_id) === (string) $location->id)>{{ $location->code }} · {{ $location->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('destination_location_id')" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div>
                        <x-input-label for="cargo_type" value="Cargo type" />
                        <x-text-input id="cargo_type" name="cargo_type" type="text" class="mt-1 block w-full" :value="old('cargo_type', $shipment->cargo_type)" required />
                        <x-input-error :messages="$errors->get('cargo_type')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="weight_kg" value="Weight (kg)" />
                        <x-text-input id="weight_kg" name="weight_kg" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('weight_kg', $shipment->weight_kg)" required />
                        <x-input-error :messages="$errors->get('weight_kg')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="volume_cbm" value="Volume (CBM)" />
                        <x-text-input id="volume_cbm" name="volume_cbm" type="number" step="0.001" min="0" class="mt-1 block w-full" :value="old('volume_cbm', $shipment->volume_cbm)" required />
                        <x-input-error :messages="$errors->get('volume_cbm')" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <x-input-label for="expected_departure_at" value="Expected departure" />
                        <x-text-input id="expected_departure_at" name="expected_departure_at" type="datetime-local" class="mt-1 block w-full" :value="old('expected_departure_at', $shipment->expected_departure_at?->format('Y-m-d\TH:i'))" required />
                        <x-input-error :messages="$errors->get('expected_departure_at')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="expected_arrival_at" value="Expected arrival" />
                        <x-text-input id="expected_arrival_at" name="expected_arrival_at" type="datetime-local" class="mt-1 block w-full" :value="old('expected_arrival_at', $shipment->expected_arrival_at?->format('Y-m-d\TH:i'))" required />
                        <x-input-error :messages="$errors->get('expected_arrival_at')" class="mt-2" />
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-gray-800">Items</h3>
                        <button type="button" id="add-item" class="text-sm text-indigo-600 hover:underline">Add item</button>
                    </div>

                    @foreach ($errors->getMessages() as $key => $messages)
                        @if (str_starts_with($key, 'items'))
                            <p class="mt-2 text-sm text-red-600">{{ $messages[0] }}</p>
                        @endif
                    @endforeach

                    <div id="items" class="mt-3 space-y-3"></div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <x-primary-button>Save</x-primary-button>
                    <a href="{{ $editing ? route('shipments.show', $shipment) : route('shipments.index') }}" class="text-sm text-gray-600 hover:underline">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <template id="item-template">
        <div class="grid grid-cols-12 items-end gap-2">
            <div class="col-span-12 sm:col-span-5">
                <label class="block text-xs font-medium text-gray-600">Description</label>
                <input data-field="description" name="items[__INDEX__][description]" type="text" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">
            </div>
            <div class="col-span-4 sm:col-span-2">
                <label class="block text-xs font-medium text-gray-600">Qty</label>
                <input data-field="quantity" name="items[__INDEX__][quantity]" type="number" min="1" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">
            </div>
            <div class="col-span-4 sm:col-span-2">
                <label class="block text-xs font-medium text-gray-600">Weight (kg)</label>
                <input data-field="weight_kg" name="items[__INDEX__][weight_kg]" type="number" step="0.01" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">
            </div>
            <div class="col-span-4 sm:col-span-2">
                <label class="block text-xs font-medium text-gray-600">HS code</label>
                <input data-field="hs_code" name="items[__INDEX__][hs_code]" type="text" maxlength="20" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">
            </div>
            <div class="col-span-12 sm:col-span-1 text-right">
                <button type="button" data-remove class="text-sm text-red-600 hover:underline">Remove</button>
            </div>
        </div>
    </template>

    <script>
        const container = document.getElementById('items');
        const template = document.getElementById('item-template');
        let index = 0;

        function addRow(item = {}) {
            const wrapper = document.createElement('div');
            wrapper.innerHTML = template.innerHTML.replaceAll('__INDEX__', index++).trim();
            const row = wrapper.firstElementChild;

            ['description', 'quantity', 'weight_kg', 'hs_code'].forEach((field) => {
                row.querySelector('[data-field="' + field + '"]').value = item[field] ?? '';
            });

            row.querySelector('[data-remove]').addEventListener('click', () => {
                if (container.children.length > 1) {
                    row.remove();
                }
            });

            container.appendChild(row);
        }

        @json($initialItems).forEach((item) => addRow(item));

        document.getElementById('add-item').addEventListener('click', () => addRow());
    </script>
</x-app-layout>
