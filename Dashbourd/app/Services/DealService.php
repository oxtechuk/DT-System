<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Deal;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * DealService
 *
 * Handles the full deal lifecycle:
 * 1. Start deal (walk-in or from booking)
 * 2. Add products to deal
 * 3. Close deal (calculate price → update order → ready for payment)
 */
class DealService
{
 public function __construct(
 protected DealPricingService $pricing
 ) {}

 // ──────────────────────────────────────────────────
 // 1. START DEAL
 // ──────────────────────────────────────────────────

 /**
 * Start a new deal for a customer.
 *
 * @param array{
 * customer_id: int,
 * workspace_type_id: int,
 * room_id: ?int,
 * booking_id: ?int,
 * started_at: ?string, // override if allowed
 * notes: ?string,
 * created_by: ?int,
 * } $data
 */
 public function start(array $data): Deal
 {
 return DB::transaction(function () use ($data) {
 $deal = Deal::create([
 'deal_number' => Deal::generateNumber(),
 'customer_id' => $data['customer_id'],
 'workspace_type_id' => $data['workspace_type_id'],
 'room_id' => $data['room_id'] ?? null,
 'booking_id' => $data['booking_id'] ?? null,
 'started_at' => $data['started_at'] ?? now(),
 'status' => 'open',
 'notes' => $data['notes'] ?? null,
 'created_by' => $data['created_by'] ?? auth()->id(),
 ]);

 // Create an empty order for this deal immediately
 Order::create([
 'order_number' => Order::generateNumber(),
 'customer_id' => $deal->customer_id,
 'deal_id' => $deal->id,
 'status' => 'open',
 'created_by' => $deal->created_by,
 ]);

 return $deal->load('customer', 'room', 'workspaceType', 'order');
 });
 }

 // ──────────────────────────────────────────────────
 // 2. ADD PRODUCT TO DEAL
 // ──────────────────────────────────────────────────

 /**
 * Add a product to the active deal's order.
 */
 public function addProduct(Deal $deal, int $productId, int $quantity = 1): OrderItem
 {
 abort_if($deal->status !== 'open', 422, 'Deal is not open.');

 $product = Product::findOrFail($productId);
 $order = $deal->order;

 return DB::transaction(function () use ($order, $product, $quantity) {
 // Check if already in order — increase quantity
 $existing = $order->items()
 ->where('product_id', $product->id)
 ->where('item_type', 'product')
 ->first();

 if ($existing) {
 $existing->increment('quantity', $quantity);
 $existing->update(['total' => $existing->unit_price * $existing->quantity]);
 $item = $existing->fresh();
 } else {
 $item = $order->items()->create([
 'product_id' => $product->id,
 'item_type' => 'product',
 'name' => $product->name, // snapshot
 'quantity' => $quantity,
 'unit_price' => $product->selling_price, // snapshot
 'total' => $product->selling_price * $quantity,
 ]);
 }

 $order->recalculate();

 return $item;
 });
 }

 /**
 * Remove a product item from the deal's order.
 */
 public function removeItem(Deal $deal, int $orderItemId): void
 {
 abort_if($deal->status !== 'open', 422, 'Deal is not open.');

 $item = $deal->order->items()->findOrFail($orderItemId);
 $item->delete();
 $deal->order->recalculate();
 }

 // ──────────────────────────────────────────────────
 // 3. CLOSE DEAL
 // ──────────────────────────────────────────────────

 /**
 * Close the deal:
 * - Record end time
 * - Run pricing engine
 * - Add session charge to the order
 * - Update deal & order status
 */
 public function close(Deal $deal, array $data = []): Deal
 {
 abort_if($deal->status !== 'open', 422, 'Deal is already closed or cancelled.');

 return DB::transaction(function () use ($deal, $data) {
 $endedAt = isset($data['ended_at'])
 ? Carbon::parse($data['ended_at'])
 : now();

 // Run pricing engine
 $pricing = $this->pricing->calculate($deal, $endedAt);

 // Update deal fields
 $deal->update([
 'ended_at' => $endedAt,
 'duration_minutes' => $pricing['actual_minutes'],
 'billable_duration_minutes' => $pricing['billable_minutes'],
 'pricing_rule_id' => $pricing['pricing_rule_id'],
 'applied_price' => $pricing['price'],
 'status' => 'closed',
 'closed_by' => $data['closed_by'] ?? auth()->id(),
 ]);

 // Add session charge to order (if price > 0)
 $order = $deal->order;
 if ($pricing['price'] > 0) {
 // Remove old session item if re-closing
 $order->items()->where('item_type', 'session')->delete();

 $order->items()->create([
 'item_type' => 'session',
 'name' => 'Session Charge — ' . $deal->workspaceType->name,
 'quantity' => 1,
 'unit_price' => $pricing['price'],
 'total' => $pricing['price'],
 ]);
 }

 $order->recalculate();

 return $deal->fresh(['customer', 'room', 'workspaceType', 'order.items']);
 });
 }

 // ──────────────────────────────────────────────────
 // 4. PAY ORDER
 // ──────────────────────────────────────────────────

 /**
 * Register a payment against an order.
 *
 * Supports multiple payments (cash + instapay + wallet split).
 */
 public function pay(Order $order, array $payments): Order
 {
 abort_if($order->status === 'paid', 422, 'Order is already paid.');

 return DB::transaction(function () use ($order, $payments) {
 foreach ($payments as $paymentData) {
 if (empty($paymentData['amount']) || $paymentData['amount'] <= 0) {
 continue;
 }

 Payment::create([
 'payment_number' => Payment::generateNumber(),
 'order_id' => $order->id,
 'customer_id' => $order->customer_id,
 'type' => 'income',
 'method' => $paymentData['method'],
 'amount' => $paymentData['amount'],
 'reference' => $paymentData['reference'] ?? null,
 'paid_at' => now(),
 'received_by' => auth()->id(),
 'notes' => $paymentData['notes'] ?? null,
 ]);
 }

 // Recompute paid_amount from payments table
 $totalPaid = $order->payments()->where('type', 'income')->sum('amount');
 $remaining = max(0, $order->total - $totalPaid);

 $order->update([
 'paid_amount' => $totalPaid,
 'remaining_amount' => $remaining,
 'status' => $remaining <= 0 ? 'paid' : 'partially_paid',
 'closed_at' => $remaining <= 0 ? now() : null,
 'closed_by' => $remaining <= 0 ? auth()->id() : null,
 ]);

 return $order->fresh(['items', 'payments']);
 });
 }
}
