<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/admin')->assertRedirect('/login');
    }

    public function test_customer_cannot_access_admin_area(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_customer_cannot_access_operations_area(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/operations')
            ->assertForbidden();
    }

    public function test_operator_can_access_operations_but_not_admin(): void
    {
        $operator = User::factory()->operator()->create();

        $this->actingAs($operator)->get('/operations')->assertOk();
        $this->actingAs($operator)->get('/admin')->assertForbidden();
    }

    public function test_admin_can_access_admin_and_operations(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/admin')->assertOk();
        $this->actingAs($admin)->get('/operations')->assertOk();
    }

    public function test_role_cannot_be_mass_assigned(): void
    {
        $user = User::create([
            'name' => 'Intruder',
            'email' => 'intruder@example.test',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $this->assertSame(UserRole::Customer, $user->fresh()->role);
    }
}