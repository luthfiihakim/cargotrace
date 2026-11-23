@props(['status'])

@php
    $classes = match ($status->value) {
        'CREATED' => 'bg-gray-100 text-gray-800',
        'BOOKED' => 'bg-blue-100 text-blue-800',
        'PICKED_UP' => 'bg-indigo-100 text-indigo-800',
        'IN_TRANSIT' => 'bg-yellow-100 text-yellow-800',
        'DELIVERED' => 'bg-green-100 text-green-800',
        'CANCELLED' => 'bg-red-100 text-red-800',
    };
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex rounded-full px-2 py-1 text-xs font-semibold '.$classes]) }}>{{ $status->label() }}</span>
