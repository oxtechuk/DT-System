<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\Order;
use App\Services\DealService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function __construct(protected DealService $dealService) {}

    // ──────────────────────────────────────────────────────
    // GET /api/v1/deals
    // List deals with optional filters (customer_id, status, date)
    // ──────────────────────────────────────────────────────
    public function index(Request $request): JsonResponse
    {
        $query = Deal::with([
            'customer:id,full_name,phone',
            'room:id,name,code,color',
            'workspaceType:id,name,code',
            'order:id,deal_id,total,paid_amount,remaining_amount,status',
            'order.items:id,order_id,name,quantity,unit_price,total,item_type',
        ]);

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($customerId = $request->get('customer_id')) {
            $query->where('customer_id', $customerId);
        }

        $deals = $query->latest('started_at')->paginate($request->get('per_page', 20));

        return response()->json([
            'success' => true,
            'data' => $deals->items(),
            'meta' => [
                'current_page' => $deals->currentPage(),
                'last_page' => $deals->lastPage(),
                'total' => $deals->total(),
            ],
        ]);
    }

    // ──────────────────────────────────────────────────────
    // GET /api/v1/deals/active
    // Used by Cashier POS to load all currently open deals.
    // ──────────────────────────────────────────────────────
    public function active(): JsonResponse
    {
        $deals = Deal::open()
            ->with([
                'customer:id,full_name,phone',
                'room:id,name,code,color',
                'workspaceType:id,name,code',
                'order:id,deal_id,total,paid_amount,remaining_amount,status',
                'order.items:id,order_id,name,quantity,unit_price,total,item_type',
            ])
            ->orderBy('started_at')
            ->get();

        return response()->json([
            'data' => $deals->map(fn ($d) => $this->formatDeal($d)),
        ]);
    }

    // ──────────────────────────────────────────────────────
    // POST /api/v1/deals
    // Start a new deal from the Cashier POS.
    // ──────────────────────────────────────────────────────
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'workspace_type_id' => 'required|exists:workspace_types,id',
            'room_id' => 'nullable|exists:rooms,id',
            'booking_id' => 'nullable|exists:bookings,id',
            'started_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $deal = $this->dealService->start($validated);

        return response()->json([
            'message' => 'Deal started successfully.',
            'data' => $this->formatDeal($deal),
        ], 201);
    }

    // ──────────────────────────────────────────────────────
    // GET /api/v1/deals/{deal}
    // Get deal details including order items.
    // ──────────────────────────────────────────────────────
    public function show(Deal $deal): JsonResponse
    {
        $deal->load([
            'customer', 'room', 'workspaceType',
            'order.items', 'order.payments',
        ]);

        return response()->json([
            'data' => $this->formatDeal($deal),
        ]);
    }

    // ──────────────────────────────────────────────────────
    // POST /api/v1/deals/{deal}/items
    // Add a product to the deal from Cashier POS.
    // ──────────────────────────────────────────────────────
    public function addItem(Request $request, Deal $deal): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $item = $this->dealService->addProduct(
            $deal,
            $validated['product_id'],
            $validated['quantity'] ?? 1,
        );

        $deal->order->refresh();

        return response()->json([
            'message' => 'Product added.',
            'item' => $item,
            'order' => $this->formatOrder($deal->order),
        ]);
    }

    // ──────────────────────────────────────────────────────
    // DELETE /api/v1/deals/{deal}/items/{item}
    // Remove an item from the deal's order.
    // ──────────────────────────────────────────────────────
    public function removeItem(Deal $deal, int $itemId): JsonResponse
    {
        $this->dealService->removeItem($deal, $itemId);

        $deal->order->refresh();

        return response()->json([
            'message' => 'Item removed.',
            'order' => $this->formatOrder($deal->order),
        ]);
    }

    // ──────────────────────────────────────────────────────
    // POST /api/v1/deals/{deal}/close
    // Close the deal, run pricing engine, finalize order.
    // ──────────────────────────────────────────────────────
    public function close(Request $request, Deal $deal): JsonResponse
    {
        $validated = $request->validate([
            'ended_at' => 'nullable|date',
        ]);

        $deal = $this->dealService->close($deal, $validated);

        return response()->json([
            'message' => 'Deal closed.',
            'data' => $this->formatDeal($deal),
        ]);
    }

    // ──────────────────────────────────────────────────────
    // POST /api/v1/deals/{deal}/pay
    // Register payment(s) against the deal's order.
    // ──────────────────────────────────────────────────────
    public function pay(Request $request, Deal $deal): JsonResponse
    {
        $request->validate([
            'payments' => 'required|array|min:1',
            'payments.*.method' => 'required|in:cash,instapay,wallet,card,other',
            'payments.*.amount' => 'required|numeric|min:0.01',
            'payments.*.reference' => 'nullable|string',
        ]);

        $order = $this->dealService->pay($deal->order, $request->payments);

        return response()->json([
            'message' => 'Payment recorded.',
            'order' => $this->formatOrder($order),
        ]);
    }

    // ──────────────────────────────────────────────────────
    // FORMATTING HELPERS
    // ──────────────────────────────────────────────────────

    private function formatDeal(Deal $deal): array
    {
        return [
            'id' => $deal->id,
            'deal_number' => $deal->deal_number,
            'status' => $deal->status,
            'customer' => $deal->customer ? [
                'id' => $deal->customer->id,
                'full_name' => $deal->customer->full_name,
                'phone' => $deal->customer->phone,
                'initials' => $deal->customer->initials,
            ] : null,
            'room' => $deal->room ? [
                'id' => $deal->room->id,
                'name' => $deal->room->name,
                'color' => $deal->room->display_color,
            ] : null,
            'workspace_type' => $deal->workspaceType?->name,
            'started_at' => $deal->started_at?->toISOString(),
            'ended_at' => $deal->ended_at?->toISOString(),
            'live_minutes' => $deal->live_duration_minutes,
            'applied_price' => $deal->applied_price,
            'order' => $deal->order ? $this->formatOrder($deal->order) : null,
        ];
    }

    private function formatOrder(Order $order): array
    {
        return [
            'id' => $order->id,
            'order_number' => $order->order_number,
            'status' => $order->status,
            'subtotal' => $order->subtotal,
            'discount' => $order->discount,
            'total' => $order->total,
            'paid_amount' => $order->paid_amount,
            'remaining_amount' => $order->remaining_amount,
            'items' => $order->items?->map(fn ($i) => [
                'id' => $i->id,
                'name' => $i->name,
                'type' => $i->item_type,
                'quantity' => $i->quantity,
                'unit_price' => $i->unit_price,
                'total' => $i->total,
            ]),
            'payments' => $order->payments?->map(fn ($p) => [
                'id' => $p->id,
                'method' => $p->method,
                'amount' => $p->amount,
                'paid_at' => $p->paid_at?->toISOString(),
            ]),
        ];
    }
}
