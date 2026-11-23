<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Carriers</h2>
            @can('create', \App\Models\Carrier::class)
                <a href="{{ route('carriers.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">New carrier</a>
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
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Code</th>
                            <th class="px-4 py-3">Mode</th>
                            <th class="px-4 py-3">Contact</th>
                            <th class="px-4 py-3">Active</th>
                            <th class="px-4 py-3">Shipments</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($carriers as $carrier)
                            <tr>
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $carrier->name }}</td>
                                <td class="px-4 py-3">{{ $carrier->code }}</td>
                                <td class="px-4 py-3">{{ $carrier->mode->label() }}</td>
                                <td class="px-4 py-3">{{ $carrier->contact_email }}</td>
                                <td class="px-4 py-3">{{ $carrier->is_active ? 'Yes' : 'No' }}</td>
                                <td class="px-4 py-3">{{ $carrier->shipments_count }}</td>
                                <td class="px-4 py-3 text-right space-x-3">
                                    @can('update', $carrier)
                                        <a href="{{ route('carriers.edit', $carrier) }}" class="text-indigo-600 hover:underline">Edit</a>
                                    @endcan
                                    @can('delete', $carrier)
                                        <form method="POST" action="{{ route('carriers.destroy', $carrier) }}" class="inline" onsubmit="return confirm('Delete this carrier?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-4 py-6 text-center text-gray-500">No carriers yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $carriers->links() }}</div>
        </div>
    </div>
</x-app-layout>
