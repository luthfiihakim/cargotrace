<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $shipment->shipment_number }}</h2>
                <x-status-badge :status="$shipment->current_status" />
            </div>
            <div class="flex items-center gap-4 text-sm">
                @can('update', $shipment)
                    <a href="{{ route('shipments.edit', $shipment) }}" class="text-indigo-600 hover:underline">Edit</a>
                @endcan
                @can('delete', $shipment)
                    <form method="POST" action="{{ route('shipments.destroy', $shipment) }}" onsubmit="return confirm('Delete this shipment?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                @endcan
                <a href="{{ route('shipments.index') }}" class="text-gray-600 hover:underline">Back</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-flash />

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <dl class="grid grid-cols-1 gap-x-6 gap-y-4 text-sm sm:grid-cols-3">
                    <div>
                        <dt class="text-gray-500">Customer</dt>
                        <dd class="font-medium text-gray-900">{{ $shipment->customer->name }}</dd>
                        <dd class="text-gray-500">{{ $shipment->customer->company_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Carrier</dt>
                        <dd class="font-medium text-gray-900">{{ $shipment->carrier->name }}</dd>
                        <dd class="text-gray-500">{{ $shipment->carrier->mode->label() }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Scenario</dt>
                        <dd class="font-medium text-gray-900">{{ $shipment->scenario->code }} · {{ $shipment->scenario->name }}</dd>
                        <dd class="text-gray-500">Delay threshold {{ $shipment->scenario->delay_threshold_hours }} h</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Origin</dt>
                        <dd class="font-medium text-gray-900">{{ $shipment->origin->name }}</dd>
                        <dd class="text-gray-500">{{ $shipment->origin->city }}, {{ $shipment->origin->country }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Destination</dt>
                        <dd class="font-medium text-gray-900">{{ $shipment->destination->name }}</dd>
                        <dd class="text-gray-500">{{ $shipment->destination->city }}, {{ $shipment->destination->country }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Cargo</dt>
                        <dd class="font-medium text-gray-900">{{ $shipment->cargo_type }}</dd>
                        <dd class="text-gray-500">{{ $shipment->weight_kg }} kg · {{ $shipment->volume_cbm }} CBM</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Expected departure</dt>
                        <dd class="font-medium text-gray-900">{{ $shipment->expected_departure_at->format('d M Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Expected arrival</dt>
                        <dd class="font-medium text-gray-900">{{ $shipment->expected_arrival_at->format('d M Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Estimated arrival</dt>
                        <dd class="font-medium text-gray-900">{{ $shipment->estimated_arrival_at->format('d M Y H:i') }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <div class="px-6 py-4 font-semibold text-gray-800">Items</div>
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-500">
                        <tr>
                            <th class="px-4 py-3">Description</th>
                            <th class="px-4 py-3">Quantity</th>
                            <th class="px-4 py-3">Weight (kg)</th>
                            <th class="px-4 py-3">HS code</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($shipment->items as $item)
                            <tr>
                                <td class="px-4 py-3">{{ $item->description }}</td>
                                <td class="px-4 py-3">{{ $item->quantity }}</td>
                                <td class="px-4 py-3">{{ $item->weight_kg }}</td>
                                <td class="px-4 py-3">{{ $item->hs_code }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
