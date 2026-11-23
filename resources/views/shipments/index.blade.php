<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Shipments</h2>
            @can('create', \App\Models\Shipment::class)
                <a href="{{ route('shipments.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">New shipment</a>
            @endcan
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-flash />

            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-500">
                        <tr>
                            <th class="px-4 py-3">Shipment</th>
                            <th class="px-4 py-3">Customer</th>
                            <th class="px-4 py-3">Route</th>
                            <th class="px-4 py-3">Carrier</th>
                            <th class="px-4 py-3">Scenario</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Expected arrival</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($shipments as $shipment)
                            <tr>
                                <td class="px-4 py-3 font-medium">
                                    <a href="{{ route('shipments.show', $shipment) }}" class="text-indigo-600 hover:underline">{{ $shipment->shipment_number }}</a>
                                </td>
                                <td class="px-4 py-3">{{ $shipment->customer->name }}</td>
                                <td class="px-4 py-3">{{ $shipment->origin->code }} → {{ $shipment->destination->code }}</td>
                                <td class="px-4 py-3">{{ $shipment->carrier->name }}</td>
                                <td class="px-4 py-3">{{ $shipment->scenario->code }}</td>
                                <td class="px-4 py-3"><x-status-badge :status="$shipment->current_status" /></td>
                                <td class="px-4 py-3">{{ $shipment->expected_arrival_at->format('d M Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-4 py-6 text-center text-gray-500">No shipments found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $shipments->links() }}</div>
        </div>
    </div>
</x-app-layout>
