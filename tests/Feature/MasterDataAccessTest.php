<?php

namespace Tests\Feature;

use App\Models\Carrier;
use App\Models\Location;
use App\Models\Shipment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterDataAccessTest extends TestCase
{
    use RefreshDatabase;

    private function carrierPayload(array $override = []): array
    {
        return array_merge([
            'name' => 'Bahari Sea Freight',
            'code' => 'bsf',
            'mode' => 'sea',
            'contact_email' => 'ops@bahari.test',
            'is_active' => 1,
        ], $override);
    }

    private function locationPayload(array $override = []): array
    {
        return array_merge([
            'name' => 'Tanjung Priok Port',
            'code' => 'idjkt',
            'type' => 'port',
            'city' => 'Jakarta',
            'country' => 'Indonesia',
            'latitude' => -6.1049,
            'longitude' => 106.885,
        ], $override);
    }

    public function test_customer_cannot_view_carriers_or_locations(): void
    {
        $customer = User::factory()->create();

        $this->actingAs($customer)->get('/carriers')->assertForbidden();
        $this->actingAs($customer)->get('/locations')->assertForbidden();
    }

    public function test_operator_can_view_but_not_create_carriers(): void
    {
        $operator = User::factory()->operator()->create();

        $this->actingAs($operator)->get('/carriers')->assertOk();
        $this->actingAs($operator)->get('/carriers/create')->assertForbidden();
        $this->actingAs($operator)->post('/carriers', $this->carrierPayload())->assertForbidden();
        $this->assertDatabaseCount('carriers', 0);
    }

    public function test_admin_can_create_carrier_and_code_is_uppercased(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post('/carriers', $this->carrierPayload())
            ->assertRedirect('/carriers');

        $this->assertDatabaseHas('carriers', ['code' => 'BSF', 'name' => 'Bahari Sea Freight']);
    }

    public function test_carrier_code_must_be_unique(): void
    {
        $admin = User::factory()->admin()->create();
        Carrier::factory()->create(['code' => 'BSF']);

        $this->actingAs($admin)
            ->post('/carriers', $this->carrierPayload())
            ->assertSessionHasErrors('code');
    }

    public function test_carrier_used_by_shipments_cannot_be_deleted(): void
    {
        $admin = User::factory()->admin()->create();
        $shipment = Shipment::factory()->create();

        $this->actingAs($admin)
            ->delete(route('carriers.destroy', $shipment->carrier))
            ->assertSessionHasErrors('delete');

        $this->assertModelExists($shipment->carrier);
    }

    public function test_admin_can_create_location(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post('/locations', $this->locationPayload())
            ->assertRedirect('/locations');

        $this->assertDatabaseHas('locations', ['code' => 'IDJKT']);
    }

    public function test_location_rejects_invalid_coordinates(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post('/locations', $this->locationPayload(['latitude' => 120, 'longitude' => 200]))
            ->assertSessionHasErrors(['latitude', 'longitude']);
    }

    public function test_location_used_by_shipments_cannot_be_deleted(): void
    {
        $admin = User::factory()->admin()->create();
        $shipment = Shipment::factory()->create();

        $this->actingAs($admin)
            ->delete(route('locations.destroy', $shipment->origin))
            ->assertSessionHasErrors('delete');

        $this->assertModelExists(Location::find($shipment->origin_location_id));
    }
}
