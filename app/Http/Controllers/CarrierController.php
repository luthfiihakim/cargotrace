<?php

namespace App\Http\Controllers;

use App\Enums\CarrierMode;
use App\Http\Requests\CarrierRequest;
use App\Models\Carrier;
use Illuminate\Support\Facades\Gate;

class CarrierController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Carrier::class);

        $carriers = Carrier::withCount('shipments')->orderBy('name')->paginate(15);

        return view('carriers.index', compact('carriers'));
    }

    public function create()
    {
        Gate::authorize('create', Carrier::class);

        return view('carriers.form', [
            'carrier' => new Carrier(['is_active' => true]),
            'modes' => CarrierMode::cases(),
        ]);
    }

    public function store(CarrierRequest $request)
    {
        Carrier::create($request->validated());

        return redirect()->route('carriers.index')->with('status', 'Carrier created.');
    }

    public function edit(Carrier $carrier)
    {
        Gate::authorize('update', $carrier);

        return view('carriers.form', [
            'carrier' => $carrier,
            'modes' => CarrierMode::cases(),
        ]);
    }

    public function update(CarrierRequest $request, Carrier $carrier)
    {
        $carrier->update($request->validated());

        return redirect()->route('carriers.index')->with('status', 'Carrier updated.');
    }

    public function destroy(Carrier $carrier)
    {
        Gate::authorize('delete', $carrier);

        if ($carrier->shipments()->exists()) {
            return back()->withErrors(['delete' => 'This carrier is used by shipments and cannot be deleted.']);
        }

        $carrier->delete();

        return redirect()->route('carriers.index')->with('status', 'Carrier deleted.');
    }
}
