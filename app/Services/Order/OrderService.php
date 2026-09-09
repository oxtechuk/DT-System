<?php

namespace App\Services\Order;

use App\Enums\OrderItemType;
use App\Models\AuditLog;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * Add a product to an open order.
     * Captures price snapshot at time of sale.
     */
    public function addProduct(Order $order, Product $product, int $quantity = 1): OrderItem
    {
        if (! $order->isOpen()) {
            throw new \RuntimeException("Order #{$order->order_number} is not open.");
        }

        return DB::transaction(function () use ($order, $product, $quantity) {
            $unitPrice = $product->selling_price;

            $item = $order->items()->create([
                'product_id' => $product->id,
                'item_type'  => OrderItemType::Product,
                'name'       => $product->name,              // snapshot
                'quantity'   => $quantity,
                'unit_price' => $unitPrice,                  // snapshot — historical!
                'total'      => $unitPrice * $quantity,
                'metadata'   => ['product_category' => $product->category?->name],
            ]);

            $order->load('items', 'payments');
            $order->recalculate();
            $order->save();

            AuditLog::record('order.item_added', $order, null, [
                'product_id' => $product->id,
                'name'       => $product->name,
                'quantity'   => $quantity,
                'unit_price' => $unitPrice,
            ]);

            return $item;
        });
    }

    /**
     * Remove an item from an open order.
     */
    public function removeItem(Order $order, OrderItem $item): void
    {
        if (! $order->isOpen()) {
            throw new \RuntimeException("Order #{$order->order_number} is not open.");
        }

        DB::transaction(function () use ($order, $item) {
            AuditLog::record('order.item_removed', $order, [
                'item_id'    => $item->id,
                'name'       => $item->name,
                'unit_price' => $item->unit_price,
                'quantity'   => $item->quantity,
            ], null);

            $item->delete();

            $order->load('items', 'payments');
            $order->recalculate();
            $order->save();
        });
    }

    /**
     * Close an order (mark as completed).
     */
    public function close(Order $order): Order
    {
        if ($order->remaining_amount > 0) {
            throw new \RuntimeException(
                "Order #{$order->order_number} has unpaid balance of {$order->remaining_amount}."
            );
        }

        return DB::transaction(function () use ($order) {
            $order->status    = \App\Enums\OrderStatus::Paid;
            $order->closed_by = auth()->id();
            $order->closed_at = now();
            $order->save();

            AuditLog::record('order.closed', $order, null, [
                'total'      => $order->total,
                'paid_amount' => $order->paid_amount,
            ]);

            return $order;
        });
    }
}
