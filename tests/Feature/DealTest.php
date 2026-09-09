<?php

namespace Tests\Feature;

use App\Enums\DealStatus;
use App\Models\Customer;
use App\Models\PricingRule;
use App\Models\Room;
use App\Models\User;
use App\Models\WorkspaceType;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DealTest extends TestCase
{
    use RefreshDatabase;

    private User $receptionUser;
    private Customer $customer;
    private WorkspaceType $workspaceType;
    private Room $room;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->receptionUser = User::factory()->create(['status' => 'active']);
        $this->receptionUser->assignRole('reception');

        $this->customer = Customer::create([
            'full_name'     => 'Test Customer',
            'phone'         => '01011112222',
            'customer_type' => 'registered',
            'status'        => 'active',
        ]);

        $this->workspaceType = WorkspaceType::create([
            'name'         => 'Shared Space',
            'code'         => 'shared',
            'pricing_mode' => 'tiered_hourly',
            'active'       => true,
        ]);

        $this->room = Room::create([
            'name'     => 'Main Hall',
            'code'     => 'HALL-1',
            'capacity' => 20,
            'status'   => 'active',
        ]);

        // Create pricing rules: 30min = 30 EGP, 60min = 50 EGP, 90min = 70 EGP, 120min = 90 EGP
        PricingRule::create([
            'workspace_type_id' => $this->workspaceType->id,
            'duration_minutes'  => 30,
            'price'             => 30.00,
            'effective_from'    => now()->subYear(),
            'active'            => true,
        ]);

        PricingRule::create([
            'workspace_type_id' => $this->workspaceType->id,
            'duration_minutes'  => 60,
            'price'             => 50.00,
            'effective_from'    => now()->subYear(),
            'active'            => true,
        ]);

        PricingRule::create([
            'workspace_type_id' => $this->workspaceType->id,
            'duration_minutes'  => 90,
            'price'             => 70.00,
            'effective_from'    => now()->subYear(),
            'active'            => true,
        ]);
    }

    public function test_can_start_a_deal(): void
    {
        $response = $this->actingAs($this->receptionUser, 'sanctum')
            ->postJson('/api/v1/deals', [
                'customer_id'       => $this->customer->id,
                'workspace_type_id' => $this->workspaceType->id,
                'room_id'           => $this->room->id,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('status', DealStatus::Open->value)
            ->assertJsonPath('customer_id', $this->customer->id);

        $this->assertDatabaseHas('deals', [
            'customer_id' => $this->customer->id,
            'status'      => 'open',
        ]);
    }

    public function test_can_close_a_deal_and_generate_order_with_pricing(): void
    {
        $startedAt = now()->subMinutes(65); // 65 min actual → 60 min billable (1:05 blueprint rule)

        $deal = $this->receptionUser->dealsCreated()->create([
            'deal_number'       => 'D202609040001',
            'customer_id'       => $this->customer->id,
            'workspace_type_id' => $this->workspaceType->id,
            'room_id'           => $this->room->id,
            'started_at'        => $startedAt,
            'status'            => DealStatus::Open,
        ]);

        $response = $this->actingAs($this->receptionUser, 'sanctum')
            ->postJson("/api/v1/deals/{$deal->id}/close", [
                'ended_at' => now()->toDateTimeString(),
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('deal.status', DealStatus::Closed->value)
            ->assertJsonPath('order.status', 'open')
            ->assertJsonPath('order.total', '50.00'); // 60 min = 50 EGP

        $this->assertDatabaseHas('orders', [
            'deal_id' => $deal->id,
            'total'   => 50.00,
        ]);

        $this->assertDatabaseHas('order_items', [
            'item_type'  => 'session',
            'unit_price' => 50.00,
        ]);
    }
}
