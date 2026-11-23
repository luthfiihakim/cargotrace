@php($editing = $carrier->exists)

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $editing ? 'Edit carrier' : 'New carrier' }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ $editing ? route('carriers.update', $carrier) : route('carriers.store') }}" class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                @csrf
                @if ($editing)
                    @method('PUT')
                @endif

                <div>
                    <x-input-label for="name" value="Name" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $carrier->name)" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="code" value="Code" />
                    <x-text-input id="code" name="code" type="text" class="mt-1 block w-full uppercase" :value="old('code', $carrier->code)" required />
                    <x-input-error :messages="$errors->get('code')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="mode" value="Mode" />
                    <select id="mode" name="mode" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        @foreach ($modes as $mode)
                            <option value="{{ $mode->value }}" @selected(old('mode', $carrier->mode?->value) === $mode->value)>{{ $mode->label() }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('mode')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="contact_email" value="Contact email" />
                    <x-text-input id="contact_email" name="contact_email" type="email" class="mt-1 block w-full" :value="old('contact_email', $carrier->contact_email)" />
                    <x-input-error :messages="$errors->get('contact_email')" class="mt-2" />
                </div>

                <div class="flex items-center gap-2">
                    <input type="hidden" name="is_active" value="0">
                    <input id="is_active" type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" @checked(old('is_active', $carrier->is_active))>
                    <x-input-label for="is_active" value="Active" />
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <x-primary-button>Save</x-primary-button>
                    <a href="{{ route('carriers.index') }}" class="text-sm text-gray-600 hover:underline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
