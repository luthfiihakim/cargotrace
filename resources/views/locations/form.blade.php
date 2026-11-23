@php($editing = $location->exists)

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $editing ? 'Edit location' : 'New location' }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ $editing ? route('locations.update', $location) : route('locations.store') }}" class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                @csrf
                @if ($editing)
                    @method('PUT')
                @endif

                <div>
                    <x-input-label for="name" value="Name" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $location->name)" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <x-input-label for="code" value="Code" />
                        <x-text-input id="code" name="code" type="text" class="mt-1 block w-full uppercase" :value="old('code', $location->code)" required />
                        <x-input-error :messages="$errors->get('code')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="type" value="Type" />
                        <select id="type" name="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @foreach ($types as $type)
                                <option value="{{ $type->value }}" @selected(old('type', $location->type?->value) === $type->value)>{{ $type->label() }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <x-input-label for="city" value="City" />
                        <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city', $location->city)" required />
                        <x-input-error :messages="$errors->get('city')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="country" value="Country" />
                        <x-text-input id="country" name="country" type="text" class="mt-1 block w-full" :value="old('country', $location->country)" required />
                        <x-input-error :messages="$errors->get('country')" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <x-input-label for="latitude" value="Latitude" />
                        <x-text-input id="latitude" name="latitude" type="number" step="any" class="mt-1 block w-full" :value="old('latitude', $location->latitude)" required />
                        <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="longitude" value="Longitude" />
                        <x-text-input id="longitude" name="longitude" type="number" step="any" class="mt-1 block w-full" :value="old('longitude', $location->longitude)" required />
                        <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <x-primary-button>Save</x-primary-button>
                    <a href="{{ route('locations.index') }}" class="text-sm text-gray-600 hover:underline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
