<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\Payment;
use App\Models\Room;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    /**
     * GET /api/v1/dashboard
     * Returns all dashboard KPI data in a single request.
     */
    public function index(): JsonResponse
    {
        $today = today();

        // Today's payments by method
        $payments = Payment::whereDate('paid_at', $today)
            ->where('type', 'income')
            ->selectRaw('method, SUM(amount) as total')
            ->groupBy('method')
            ->pluck('total', 'method');

        $todayRevenue = $payments->sum();

        // Active deals
        $activeDeals = Deal::open()->count();
        $activeShared = Deal::open()
            ->whereHas('workspaceType', fn($q) => $q->where('code', 'shared'))
            ->count();
        $activePrivate = Deal::open()
            ->whereHas('workspaceType', fn($q) => $q->where('code', 'private'))
            ->count();

        // Rooms quick summary
        $rooms = Room::active()->with('activeDeals')->get();
        $availableRooms = $rooms->filter(fn($r) => $r->is_available)->count();

        // Recent sessions (last 10)
        $recentDeals = Deal::with(['customer:id,full_name', 'room:id,name', 'workspaceType:id,name', 'order:id,deal_id,total,status'])
            ->latest('started_at')
            ->limit(10)
            ->get(['id', 'deal_number', 'customer_id', 'room_id', 'workspace_type_id', 'started_at', 'ended_at', 'status', 'applied_price']);

        // Weekly revenue (last 7 days)
        $weeklyRevenue = collect(range(6, 0))->map(function ($daysAgo) {
            $date = today()->subDays($daysAgo);
            $payments = Payment::whereDate('paid_at', $date)
                ->where('type', 'income')
                ->selectRaw('method, SUM(amount) as total')
                ->groupBy('method')
                ->pluck('total', 'method');

            return [
                'date'     => $date->format('D'),
                'cash'     => (float) ($payments['cash'] ?? 0),
                'instapay' => (float) ($payments['instapay'] ?? 0),
                'wallet'   => (float) ($payments['wallet'] ?? 0),
                'total'    => (float) $payments->sum(),
            ];
        });

        return response()->json([
            'revenue' => [
                'today'    => (float) $todayRevenue,
                'cash'     => (float) ($payments['cash'] ?? 0),
                'instapay' => (float) ($payments['instapay'] ?? 0),
                'wallet'   => (float) ($payments['wallet'] ?? 0),
            ],
            'sessions' => [
                'active'  => $activeDeals,
                'shared'  => $activeShared,
                'private' => $activePrivate,
            ],
            'rooms' => [
                'total'     => $rooms->count(),
                'available' => $availableRooms,
                'occupied'  => $rooms->count() - $availableRooms,
            ],
            'recent_deals'   => $recentDeals,
            'weekly_revenue' => $weeklyRevenue,
        ]);
    }
}
