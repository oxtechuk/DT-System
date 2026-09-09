<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    private User $receptionUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->receptionUser = User::factory()->create(['status' => 'active']);
        $this->receptionUser->assignRole('reception');
    }

    public function test_can_list_customers(): void
    {
        Customer::create([
            'full_name'     => 'Ahmed Ali',
            'phone'         => '01012345678',
            'customer_type' => 'registered',
            'status'        => 'active',
        ]);

        $response = $this->actingAs($this->receptionUser, 'sanctum')
            ->getJson('/api/v1/customers');

        $response->assertStatus(200)
            ->assertJsonPath('data.0.full_name', 'Ahmed Ali');
    }

    public function test_can_create_customer(): void
    {
        $response = $this->actingAs($this->receptionUser, 'sanctum')
            ->postJson('/api/v1/customers', [
                'full_name'     => 'Mahmoud Hassan',
                'phone'         => '01122334455',
                'email'         => 'mahmoud@example.com',
                'customer_type' => 'registered',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('full_name', 'Mahmoud Hassan')
            ->assertJsonPath('status', 'active');

        $this->assertDatabaseHas('customers', [
            'phone' => '01122334455',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'customer.created',
        ]);
    }

    public function test_cannot_create_customer_with_duplicate_phone(): void
    {
        Customer::create([
            'full_name'     => 'Existing User',
            'phone'         => '01122334455',
            'customer_type' => 'registered',
            'status'        => 'active',
        ]);

        $response = $this->actingAs($this->receptionUser, 'sanctum')
            ->postJson('/api/v1/customers', [
                'full_name'     => 'Mahmoud Hassan',
                'phone'         => '01122334455',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['phone']);
    }

    public function test_can_update_customer(): void
    {
        $customer = Customer::create([
            'full_name'     => 'Ahmed Ali',
            'phone'         => '01012345678',
            'customer_type' => 'registered',
            'status'        => 'active',
        ]);

        $response = $this->actingAs($this->receptionUser, 'sanctum')
            ->putJson("/api/v1/customers/{$customer->id}", [
                'full_name' => 'Ahmed Ali Updated',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('full_name', 'Ahmed Ali Updated');

        $this->assertDatabaseHas('customers', [
            'id'        => $customer->id,
            'full_name' => 'Ahmed Ali Updated',
        ]);
    }
}
