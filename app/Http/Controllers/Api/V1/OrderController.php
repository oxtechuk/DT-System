<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Enums\PaymentMethod;
use App\Services\Order\OrderService;
use App\Services\Payment\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        private OrderService   $orderService,
        private PaymentService $paymentService
    ) {}

    /**
     * GET /api/v1/orders/{order}
     */
    public function show(Order $order): JsonResponse
    {
        $this->authorize('orders.view');

        return response()->json(
            $order->load(['customer', 'deal', 'items.product', 'payments'])
        );
    }

    /**
     * POST /api/v1/orders/{order}/items
     */
    public function addItem(Request $request, Order $order): JsonResponse
    {
        $this->authorize('orders.edit');

        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'sometimes|integer|min:1|max:100',
        ]);

        $product  = Product::findOrFail($data['product_id']);
        $quantity = $data['quantity'] ?? 1;

        $item = $this->orderService->addProduct($order, $product, $quantity);

        return response()->json([
            'item'  => $item,
            'order' => $order->fresh(['items', 'payments']),
        ], 201);
    }

    /**
     * DELETE /api/v1/orders/{order}/items/{item}
     */
    public function removeItem(Order $order, OrderItem $item): JsonResponse
    {
        $this->authorize('orders.edit');

        if ($item->order_id !== $order->id) {
            abort(404);
        }

        $this->orderService->removeItem($order, $item);

        return response()->json(['order' => $order->fresh(['items', 'payments'])]);
    }

    /**
     * POST /api/v1/orders/{order}/payments
     */
    public function addPayment(Request $request, Order $order): JsonResponse
    {
        $this->authorize('payments.create');

        $data = $request->validate([
            'method'    => 'required|in:cash,instapay,wallet',
            'amount'    => 'required|numeric|min:0.01',
            'reference' => 'nullable|string|max:100',
            'notes'     => 'nullable|string',
        ]);

        $payment = $this->paymentService->recordPayment(
            $order,
            PaymentMethod::from($data['method']),
            (float) $data['amount'],
            $data['reference'] ?? null,
            $data['notes'] ?? null
        );

        return response()->json([
            'payment' => $payment,
            'order'   => $order->fresh(['items', 'payments']),
        ], 201);
    }

    /**
     * POST /api/v1/orders/{order}/close
     */
    public function close(Order $order): JsonResponse
    {
        $this->authorize('orders.close');

        $order = $this->orderService->close($order);

        return response()->json($order->fresh(['items', 'payments']));
    }
}
