<?php

namespace Tests\Feature;

use App\Enums\ShipmentStatus;
use App\Models\Carrier;
use App\Models\Location;
use App\Models\Scenario;
use App\Models\Shipment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShipmentTest extends TestCase
{
    use RefreshDatabase;

    private function payload(array $override = []): array
    {
        return array_merge([
            'customer_id' => User::factory()->create()->id,
            'carrier_id' => Carrier::factory()->create()->id,
            'scenario_id' => Scenario::factory()->create()->id,
            'origin_location_id' => Location::factory()->create()->id,
            'destination_location_id' => Location::factory()->create()->id,
            'cargo_type' => 'Electronics',
            'weight_kg' => 1500.5,
            'volume_cbm' => 12.345,
            'expected_departure_at' => now()->addDays(2)->format('Y-m-d H:i'),
            'expected_arrival_at' => now()->addDays(20)->format('Y-m-d H:i'),
            'items' => [
                ['description' => 'Laptops', 'quantity' => 100, 'weight_kg' => 1500.5, 'hs_code' => '847130'],
            ],
        ], $override);
    }

    public function test_guest_cannot_access_shipments(): void
    {
        $this->get('/shipments')->assertRedirect('/login');
    }

    public function test_customer_sees_only_own_shipments(): void
    {
        $customer = User::factory()->create();
        $own = Shipment::factory()->create(['customer_id' => $customer->id]);
        $other = Shipment::factory()->create();

        $this->actingAs($customer)
            ->get('/shipments')
            ->assertOk()
            ->assertSee($own->shipment_number)
            ->assertDontSee($other->shipment_number);
    }

    public function test_customer_cannot_view_another_customers_shipment(): void
    {
        $customer = User::factory()->create();
        $other = Shipment::factory()->create();

        $this->actingAs($customer)->get(route('shipments.show', $other))->assertForbidden();
    }

    public function test_customer_can_view_own_shipment(): void
    {
        $customer = User::factory()->create();
        $own = Shipment::factory()->create(['customer_id' => $customer->id]);

        $this->actingAs($customer)->get(route('shipments.show', $own))->assertOk();
    }

    public function test_customer_cannot_create_shipment(): void
    {
        $customer = User::factory()->create();

        $this->actingAs($customer)->get('/shipments/create')->assertForbidden();
        $this->actingAs($customer)->post('/shipments', $this->payload())->assertForbidden();
        $this->assertDatabaseCount('shipments', 0);
    }

    public function test_operator_can_create_shipment_with_items(): void
    {
        $operator = User::factory()->operator()->create();

        $this->actingAs($operator)->post('/shipments', $this->payload())->assertRedirect();

        $shipment = Shipment::firstOrFail();

        $this->assertSame(ShipmentStatus::Created, $shipment->current_status);
        $this->assertSame($operator->id, $shipment->created_by);
        $this->assertMatchesRegularExpression('/^CT-\d{8}-[A-Z0-9]{5}$/', $shipment->shipment_number);
        $this->assertTrue($shipment->estimated_arrival_at->equalTo($shipment->expected_arrival_at));
        $this->assertCount(1, $shipment->items);
    }

    public function test_status_and_number_cannot_be_mass_assigned(): void
    {
        $operator = User::factory()->operator()->create();

        $this->actingAs($operator)->post('/shipments', $this->payload([
            'current_status' => 'DELIVERED',
            'shipment_number' => 'HACKED',
            'is_delayed' => true,
        ]))->assertRedirect();

        $shipment = Shipment::firstOrFail();

        $this->assertSame(ShipmentStatus::Created, $shipment->current_status);
        $this->assertNotSame('HACKED', $shipment->shipment_number);
        $this->assertFalse($shipment->is_delayed);
    }

    public function test_destination_must_differ_from_origin(): void
    {
        $operator = User::factory()->operator()->create();
        $location = Location::factory()->create();

        $this->actingAs($operator)->post('/shipments', $this->payload([
            'origin_location_id' => $location->id,
            'destination_location_id' => $location->id,
        ]))->assertSessionHasErrors('destination_location_id');
    }

    public function test_arrival_must_be_after_departure(): void
    {
        $operator = User::factory()->operator()->create();

        $this->actingAs($operator)->post('/shipments', $this->payload([
            'expected_departure_at' => now()->addDays(10)->format('Y-m-d H:i'),
            'expected_arrival_at' => now()->addDays(5)->format('Y-m-d H:i'),
        ]))->assertSessionHasErrors('expected_arrival_at');
    }

    public function test_shipment_requires_at_least_one_item(): void
    {
        $operator = User::factory()->operator()->create();

        $this->actingAs($operator)
            ->post('/shipments', $this->payload(['items' => []]))
            ->assertSessionHasErrors('items');
    }

    public function test_customer_id_must_belong_to_a_customer(): void
    {
        $operator = User::factory()->operator()->create();
        $notCustomer = User::factory()->admin()->create();

        $this->actingAs($operator)
            ->post('/shipments', $this->payload(['customer_id' => $notCustomer->id]))
            ->assertSessionHasErrors('customer_id');
    }

    public function test_operator_can_update_open_shipment_and_replace_items(): void
    {
        $operator = User::factory()->operator()->create();
        $shipment = Shipment::factory()->create();
        $shipment->items()->create(['description' => 'Old item', 'quantity' => 1]);

        $payload = $this->payload([
            'customer_id' => $shipment->customer_id,
            'cargo_type' => 'Machinery',
            'items' => [['description' => 'New item', 'quantity' => 5]],
        ]);

        $this->actingAs($operator)
            ->put(route('shipments.update', $shipment), $payload)
            ->assertRedirect(route('shipments.show', $shipment));

        $shipment->refresh();

        $this->assertSame('Machinery', $shipment->cargo_type);
        $this->assertSame(['New item'], $shipment->items()->pluck('description')->all());
    }

    public function test_operator_cannot_update_terminal_shipment(): void
    {
        $operator = User::factory()->operator()->create();
        $shipment = Shipment::factory()->status(ShipmentStatus::Delivered)->create();

        $this->actingAs($operator)
            ->put(route('shipments.update', $shipment), [])
            ->assertForbidden();
    }

    public function test_admin_can_update_terminal_shipment(): void
    {
        $admin = User::factory()->admin()->create();
        $shipment = Shipment::factory()->status(ShipmentStatus::Delivered)->create();

        $this->actingAs($admin)->get(route('shipments.edit', $shipment))->assertOk();
    }

    public function test_only_admin_can_delete_shipment(): void
    {
        $operator = User::factory()->operator()->create();
        $admin = User::factory()->admin()->create();
        $shipment = Shipment::factory()->create();

        $this->actingAs($operator)->delete(route('shipments.destroy', $shipment))->assertForbidden();
        $this->assertNotSoftDeleted($shipment);

        $this->actingAs($admin)->delete(route('shipments.destroy', $shipment))->assertRedirect('/shipments');
        $this->assertSoftDeleted($shipment);
    }
}
