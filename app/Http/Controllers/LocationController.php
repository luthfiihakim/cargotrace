<?php

namespace App\Http\Controllers;

use App\Enums\LocationType;
use App\Http\Requests\LocationRequest;
use App\Models\Location;
use Illuminate\Support\Facades\Gate;

class LocationController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Location::class);

        $locations = Location::orderBy('name')->paginate(15);

        return view('locations.index', compact('locations'));
    }

    public function create()
    {
        Gate::authorize('create', Location::class);

        return view('locations.form', [
            'location' => new Location(),
            'types' => LocationType::cases(),
        ]);
    }

    public function store(LocationRequest $request)
    {
        Location::create($request->validated());

        return redirect()->route('locations.index')->with('status', 'Location created.');
    }

    public function edit(Location $location)
    {
        Gate::authorize('update', $location);

        return view('locations.form', [
            'location' => $location,
            'types' => LocationType::cases(),
        ]);
    }

    public function update(LocationRequest $request, Location $location)
    {
        $location->update($request->validated());

        return redirect()->route('locations.index')->with('status', 'Location updated.');
    }

    public function destroy(Location $location)
    {
        Gate::authorize('delete', $location);

        if ($location->isUsed()) {
            return back()->withErrors(['delete' => 'This location is used by shipments and cannot be deleted.']);
        }

        $location->delete();

        return redirect()->route('locations.index')->with('status', 'Location deleted.');
    }
}
