<?php

namespace Tests\Feature;

use App\Enums\DealStatus;
use App\Enums\OrderStatus;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderAndPaymentTest extends TestCase
{
    use RefreshDatabase;

    private User $receptionUser;
    private Customer $customer;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->receptionUser = User::factory()->create(['status' => 'active']);
        $this->receptionUser->assignRole('reception');

        $this->customer = Customer::create([
            'full_name'     => 'Sarah Ibrahim',
            'phone'         => '01234567890',
            'customer_type' => 'registered',
            'status'        => 'active',
        ]);

        $category = ProductCategory::create([
            'name'   => 'Hot Drinks',
            'code'   => 'hot_drinks',
            'active' => true,
        ]);

        $this->product = Product::create([
            'category_id'     => $category->id,
            'name'            => 'Cappuccino',
            'selling_price'   => 35.00,
            'track_inventory' => false,
            'active'          => true,
        ]);
    }

    public function test_can_add_and_remove_product_from_order(): void
    {
        $order = Order::create([
            'order_number' => 'O202609040001',
            'customer_id'  => $this->customer->id,
            'status'       => OrderStatus::Open,
            'created_by'   => $this->receptionUser->id,
        ]);

        // Add Product (quantity 2 -> 70 EGP)
        $response = $this->actingAs($this->receptionUser, 'sanctum')
            ->postJson("/api/v1/orders/{$order->id}/items", [
                'product_id' => $this->product->id,
                'quantity'   => 2,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('order.subtotal', '70.00')
            ->assertJsonPath('order.total', '70.00')
            ->assertJsonPath('order.remaining_amount', '70.00');

        $itemId = $response->json('item.id');

        // Remove Product
        $deleteResponse = $this->actingAs($this->receptionUser, 'sanctum')
            ->deleteJson("/api/v1/orders/{$order->id}/items/{$itemId}");

        $deleteResponse->assertStatus(200)
            ->assertJsonPath('order.total', '0.00')
            ->assertJsonPath('order.remaining_amount', '0.00');
    }

    public function test_multi_payment_split_and_order_closure(): void
    {
        $order = Order::create([
            'order_number'     => 'O202609040002',
            'customer_id'      => $this->customer->id,
            'status'           => OrderStatus::Open,
            'subtotal'         => 100.00,
            'total'            => 100.00,
            'paid_amount'      => 0.00,
            'remaining_amount' => 100.00,
            'created_by'       => $this->receptionUser->id,
        ]);

        $order->items()->create([
            'item_type'  => 'custom',
            'name'       => 'Service Fee',
            'quantity'   => 1,
            'unit_price' => 100.00,
            'total'      => 100.00,
        ]);

        // 1. Pay 60 EGP via Cash
        $pay1 = $this->actingAs($this->receptionUser, 'sanctum')
            ->postJson("/api/v1/orders/{$order->id}/payments", [
                'method' => 'cash',
                'amount' => 60.00,
            ]);

        $pay1->assertStatus(201)
            ->assertJsonPath('order.paid_amount', '60.00')
            ->assertJsonPath('order.remaining_amount', '40.00')
            ->assertJsonPath('order.status', 'partially_paid');

        // 2. Attempt to close before fully paid -> should fail
        $closeFail = $this->actingAs($this->receptionUser, 'sanctum')
            ->postJson("/api/v1/orders/{$order->id}/close");

        $closeFail->assertStatus(500); // Exception thrown: unpaid balance

        // 3. Pay remaining 40 EGP via InstaPay
        $pay2 = $this->actingAs($this->receptionUser, 'sanctum')
            ->postJson("/api/v1/orders/{$order->id}/payments", [
                'method'    => 'instapay',
                'amount'    => 40.00,
                'reference' => 'INSTA123456',
            ]);

        $pay2->assertStatus(201)
            ->assertJsonPath('order.paid_amount', '100.00')
            ->assertJsonPath('order.remaining_amount', '0.00');

        // 4. Close order -> should succeed
        $closeSuccess = $this->actingAs($this->receptionUser, 'sanctum')
            ->postJson("/api/v1/orders/{$order->id}/close");

        $closeSuccess->assertStatus(200)
            ->assertJsonPath('status', 'paid');
    }
}
