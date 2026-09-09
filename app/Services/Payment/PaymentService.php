<?php

namespace App\Services\Payment;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Models\AuditLog;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    /**
     * Record a payment against an order.
     * Supports multiple payments per order (cash + instapay + wallet split).
     */
    public function recordPayment(
        Order $order,
        PaymentMethod $method,
        float $amount,
        ?string $reference = null,
        ?string $notes = null
    ): Payment {
        if ($order->status === OrderStatus::Paid) {
            throw new \RuntimeException("Order #{$order->order_number} is already fully paid.");
        }

        if ($amount <= 0) {
            throw new \RuntimeException("Payment amount must be greater than zero.");
        }

        return DB::transaction(function () use ($order, $method, $amount, $reference, $notes) {
            $payment = Payment::create([
                'payment_number' => $this->generatePaymentNumber(),
                'order_id'       => $order->id,
                'customer_id'    => $order->customer_id,
                'type'           => 'income',
                'method'         => $method,
                'amount'         => $amount,
                'reference'      => $reference,
                'paid_at'        => now(),
                'received_by'    => auth()->id(),
                'notes'          => $notes,
            ]);

            // Recalculate order balance
            $order->load('items', 'payments');
            $order->recalculate();
            $order->save();

            AuditLog::record('payment.created', $order, null, [
                'payment_id'     => $payment->id,
                'payment_number' => $payment->payment_number,
                'method'         => $method->value,
                'amount'         => $amount,
                'order_remaining' => $order->remaining_amount,
            ]);

            return $payment;
        });
    }

    /**
     * Get payment summary by method for a date range (used by shift reconciliation).
     */
    public function summaryByMethod(\Carbon\Carbon $from, \Carbon\Carbon $to): array
    {
        $payments = Payment::where('type', 'income')
            ->inDateRange($from, $to)
            ->selectRaw('method, SUM(amount) as total')
            ->groupBy('method')
            ->pluck('total', 'method')
            ->toArray();

        return [
            PaymentMethod::Cash->value     => (float) ($payments[PaymentMethod::Cash->value] ?? 0),
            PaymentMethod::InstaPay->value => (float) ($payments[PaymentMethod::InstaPay->value] ?? 0),
            PaymentMethod::Wallet->value   => (float) ($payments[PaymentMethod::Wallet->value] ?? 0),
        ];
    }

    private function generatePaymentNumber(): string
    {
        $prefix = 'P' . now()->format('Ymd');
        $last   = Payment::where('payment_number', 'like', $prefix . '%')
                         ->orderByDesc('payment_number')
                         ->value('payment_number');

        $sequence = $last ? ((int) substr($last, -4)) + 1 : 1;

        return $prefix . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
