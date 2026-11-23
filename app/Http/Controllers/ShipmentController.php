<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\ShipmentRequest;
use App\Models\Carrier;
use App\Models\Location;
use App\Models\Scenario;
use App\Models\Shipment;
use App\Models\User;
use App\Services\ShipmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ShipmentController extends Controller
{
    public function __construct(private ShipmentService $shipments)
    {
    }

    public function index(Request $request)
    {
        Gate::authorize('viewAny', Shipment::class);

        $shipments = Shipment::query()
            ->visibleTo($request->user())
            ->with(['customer', 'carrier', 'origin', 'destination', 'scenario'])
            ->latest()
            ->paginate(10);

        return view('shipments.index', compact('shipments'));
    }

    public function create()
    {
        Gate::authorize('create', Shipment::class);

        return view('shipments.form', $this->formData(new Shipment()));
    }

    public function store(ShipmentRequest $request)
    {
        $shipment = $this->shipments->create($request->validated(), $request->user());

        return redirect()->route('shipments.show', $shipment)->with('status', 'Shipment created.');
    }

    public function show(Shipment $shipment)
    {
        Gate::authorize('view', $shipment);

        $shipment->load(['customer', 'creator', 'carrier', 'scenario', 'origin', 'destination', 'items']);

        return view('shipments.show', compact('shipment'));
    }

    public function edit(Shipment $shipment)
    {
        Gate::authorize('update', $shipment);

        $shipment->load('items');

        return view('shipments.form', $this->formData($shipment));
    }

    public function update(ShipmentRequest $request, Shipment $shipment)
    {
        $this->shipments->update($shipment, $request->validated());

        return redirect()->route('shipments.show', $shipment)->with('status', 'Shipment updated.');
    }

    public function destroy(Shipment $shipment)
    {
        Gate::authorize('delete', $shipment);

        $shipment->delete();

        return redirect()->route('shipments.index')->with('status', 'Shipment deleted.');
    }

    private function formData(Shipment $shipment): array
    {
        return [
            'shipment' => $shipment,
            'customers' => User::where('role', UserRole::Customer->value)->orderBy('name')->get(),
            'carriers' => Carrier::where('is_active', true)->orderBy('name')->get(),
            'scenarios' => Scenario::where('is_active', true)->orderBy('code')->get(),
            'locations' => Location::orderBy('name')->get(),
        ];
    }
}
