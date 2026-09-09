<?php

namespace App\Services\Deal;

use App\Enums\DealStatus;
use App\Enums\OrderItemType;
use App\Enums\OrderStatus;
use App\Models\AuditLog;
use App\Models\Customer;
use App\Models\Deal;
use App\Models\Order;
use App\Models\Room;
use App\Models\WorkspaceType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DealService
{
    public function __construct(
        private DealPricingService $pricingService
    ) {}

    /**
     * Start a new Deal (walk-in or from reception).
     */
    public function start(
        Customer $customer,
        WorkspaceType $workspaceType,
        ?Room $room = null,
        ?Carbon $startedAt = null,
        ?string $notes = null
    ): Deal {
        $deal = DB::transaction(function () use ($customer, $workspaceType, $room, $startedAt, $notes) {
            $deal = Deal::create([
                'deal_number'       => $this->generateDealNumber(),
                'customer_id'       => $customer->id,
                'room_id'           => $room?->id,
                'workspace_type_id' => $workspaceType->id,
                'started_at'        => $startedAt ?? now(),
                'status'            => DealStatus::Open,
                'notes'             => $notes,
                'created_by'        => auth()->id(),
            ]);

            AuditLog::record('deal.started', $deal, null, [
                'customer_id'       => $customer->id,
                'workspace_type_id' => $workspaceType->id,
                'room_id'           => $room?->id,
                'started_at'        => $deal->started_at->toIso8601String(),
            ]);

            return $deal;
        });

        return $deal;
    }

    /**
     * Close a Deal, calculate pricing, create Order with session item.
     */
    public function close(Deal $deal, ?Carbon $endedAt = null): Order
    {
        if (! $deal->isOpen()) {
            throw new \RuntimeException("Deal #{$deal->deal_number} is not open.");
        }

        return DB::transaction(function () use ($deal, $endedAt) {
            // Record end time
            $deal->ended_at = $endedAt ?? now();
            $deal->status   = DealStatus::Closed;
            $deal->closed_by = auth()->id();

            // Run pricing engine
            $pricing = $this->pricingService->calculateForDeal($deal);
            $deal->duration_minutes = $pricing['actual_minutes'];
            $deal->save();

            // Create or update the Order
            $order = $deal->order ?? Order::create([
                'order_number' => $this->generateOrderNumber(),
                'customer_id'  => $deal->customer_id,
                'deal_id'      => $deal->id,
                'status'       => OrderStatus::Open,
                'created_by'   => auth()->id(),
            ]);

            // Add session charge as an order item (with historical pricing snapshot)
            $order->items()->create([
                'item_type'  => OrderItemType::Session,
                'name'       => "Session: {$deal->workspaceType->name} ({$pricing['billable_minutes']} min)",
                'quantity'   => 1,
                'unit_price' => $pricing['session_price'],
                'total'      => $pricing['session_price'],
                'metadata'   => [
                    'pricing_rule_id'  => $pricing['pricing_rule_id'],
                    'actual_minutes'   => $pricing['actual_minutes'],
                    'billable_minutes' => $pricing['billable_minutes'],
                ],
            ]);

            // Recalculate order totals
            $order->load('items', 'payments');
            $order->recalculate();
            $order->save();

            AuditLog::record('deal.closed', $deal, null, [
                'ended_at'         => $deal->ended_at->toIso8601String(),
                'actual_minutes'   => $pricing['actual_minutes'],
                'billable_minutes' => $pricing['billable_minutes'],
                'session_price'    => $pricing['session_price'],
                'order_id'         => $order->id,
            ]);

            return $order;
        });
    }

    /**
     * Adjust deal times (authorized users only — enforced at Policy level).
     */
    public function adjustTime(Deal $deal, ?Carbon $startedAt, ?Carbon $endedAt): Deal
    {
        return DB::transaction(function () use ($deal, $startedAt, $endedAt) {
            $old = [
                'started_at' => $deal->started_at?->toIso8601String(),
                'ended_at'   => $deal->ended_at?->toIso8601String(),
            ];

            if ($startedAt) {
                $deal->started_at = $startedAt;
            }

            if ($endedAt && $deal->isClosed()) {
                $deal->ended_at = $endedAt;
            }

            $deal->save();

            AuditLog::record('deal.time_adjusted', $deal, $old, [
                'started_at' => $deal->started_at?->toIso8601String(),
                'ended_at'   => $deal->ended_at?->toIso8601String(),
            ]);

            return $deal;
        });
    }

    private function generateDealNumber(): string
    {
        $prefix = 'D' . now()->format('Ymd');
        $last   = Deal::where('deal_number', 'like', $prefix . '%')
                      ->orderByDesc('deal_number')
                      ->value('deal_number');

        $sequence = $last ? ((int) substr($last, -4)) + 1 : 1;

        return $prefix . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    private function generateOrderNumber(): string
    {
        $prefix = 'O' . now()->format('Ymd');
        $last   = Order::where('order_number', 'like', $prefix . '%')
                       ->orderByDesc('order_number')
                       ->value('order_number');

        $sequence = $last ? ((int) substr($last, -4)) + 1 : 1;

        return $prefix . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
