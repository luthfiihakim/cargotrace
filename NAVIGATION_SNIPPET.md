# Edit resources/views/layouts/navigation.blade.php

## Desktop: right after the Dashboard <x-nav-link>

```blade
<x-nav-link :href="route('shipments.index')" :active="request()->routeIs('shipments.*')">
    Shipments
</x-nav-link>
@can('viewAny', \App\Models\Carrier::class)
    <x-nav-link :href="route('carriers.index')" :active="request()->routeIs('carriers.*')">
        Carriers
    </x-nav-link>
    <x-nav-link :href="route('locations.index')" :active="request()->routeIs('locations.*')">
        Locations
    </x-nav-link>
@endcan
```

## Mobile: right after the Dashboard <x-responsive-nav-link>

```blade
<x-responsive-nav-link :href="route('shipments.index')" :active="request()->routeIs('shipments.*')">
    Shipments
</x-responsive-nav-link>
@can('viewAny', \App\Models\Carrier::class)
    <x-responsive-nav-link :href="route('carriers.index')" :active="request()->routeIs('carriers.*')">
        Carriers
    </x-responsive-nav-link>
    <x-responsive-nav-link :href="route('locations.index')" :active="request()->routeIs('locations.*')">
        Locations
    </x-responsive-nav-link>
@endcan
```
