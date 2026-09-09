<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\Customer;
use App\Models\Room;
use App\Models\WorkspaceType;
use App\Services\Deal\DealService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function __construct(private DealService $dealService) {}

    /**
     * GET /api/v1/deals
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('deals.view');

        $deals = Deal::with(['customer', 'room', 'workspaceType', 'order'])
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->when($request->customer_id, fn($q, $id) => $q->where('customer_id', $id))
            ->when($request->room_id, fn($q, $id) => $q->where('room_id', $id))
            ->when($request->date, fn($q, $d) => $q->whereDate('started_at', $d))
            ->orderBy('started_at', 'desc')
            ->paginate(20);

        return response()->json($deals);
    }

    /**
     * POST /api/v1/deals
     */
    public function store(Request $request): JsonResponse
    {
        $this->authorize('deals.create');

        $data = $request->validate([
            'customer_id'       => 'required|exists:customers,id',
            'workspace_type_id' => 'required|exists:workspace_types,id',
            'room_id'           => 'nullable|exists:rooms,id',
            'started_at'        => 'nullable|date',
            'notes'             => 'nullable|string',
        ]);

        $customer      = Customer::findOrFail($data['customer_id']);
        $workspaceType = WorkspaceType::findOrFail($data['workspace_type_id']);
        $room          = isset($data['room_id']) ? Room::findOrFail($data['room_id']) : null;
        $startedAt     = isset($data['started_at']) ? Carbon::parse($data['started_at']) : null;

        $deal = $this->dealService->start($customer, $workspaceType, $room, $startedAt, $data['notes'] ?? null);

        return response()->json($deal->load(['customer', 'room', 'workspaceType']), 201);
    }

    /**
     * GET /api/v1/deals/{deal}
     */
    public function show(Deal $deal): JsonResponse
    {
        $this->authorize('deals.view');

        return response()->json(
            $deal->load(['customer', 'room', 'workspaceType', 'order.items', 'order.payments'])
        );
    }

    /**
     * POST /api/v1/deals/{deal}/close
     */
    public function close(Request $request, Deal $deal): JsonResponse
    {
        $this->authorize('deals.close');

        $data = $request->validate([
            'ended_at' => 'nullable|date|after:' . $deal->started_at->toDateTimeString(),
        ]);

        $endedAt = isset($data['ended_at']) ? Carbon::parse($data['ended_at']) : null;
        $order   = $this->dealService->close($deal, $endedAt);

        return response()->json([
            'deal'  => $deal->fresh(['customer', 'room', 'workspaceType']),
            'order' => $order->load('items', 'payments'),
        ]);
    }

    /**
     * PATCH /api/v1/deals/{deal}/time
     */
    public function adjustTime(Request $request, Deal $deal): JsonResponse
    {
        $this->authorize('deals.adjust_time');

        $data = $request->validate([
            'started_at' => 'nullable|date',
            'ended_at'   => 'nullable|date',
        ]);

        $startedAt = isset($data['started_at']) ? Carbon::parse($data['started_at']) : null;
        $endedAt   = isset($data['ended_at'])   ? Carbon::parse($data['ended_at'])   : null;

        $deal = $this->dealService->adjustTime($deal, $startedAt, $endedAt);

        return response()->json($deal->fresh());
    }
}
