<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoutingController extends Controller
{
    /**
     * Display main dashboard root view
     */
    public function root()
    {
        try {
            $today = today();
            $payments = \App\Models\Payment::whereDate('paid_at', $today)->where('type', 'income')->get();
            $todayRevenue = (float) $payments->sum('amount');
            $cashRevenue = (float) $payments->where('method', 'cash')->sum('amount');
            $instapayRevenue = (float) $payments->where('method', 'instapay')->sum('amount');
            $walletRevenue = (float) $payments->where('method', 'wallet')->sum('amount');

            $activeDeals = \App\Models\Deal::open()->with(['customer', 'room', 'workspaceType', 'order.items'])->get();
            $activeDealsCount = $activeDeals->count();

            $rooms = \App\Models\Room::active()->with('activeDeals.customer')->get();
            $totalRooms = $rooms->count();
            $availableRoomsCount = $rooms->filter(fn($r) => $r->is_available)->count();
            $occupiedRoomsCount = $totalRooms - $availableRoomsCount;

            $totalCustomers = \App\Models\Customer::active()->count();
            $totalProducts = \App\Models\Product::active()->count();

            $recentDeals = \App\Models\Deal::with(['customer', 'room', 'workspaceType', 'order'])
                ->latest('started_at')
                ->limit(8)
                ->get();

            // 7-day revenue chart data (Aggregated in 1 query)
            $sevenDaysAgo = today()->subDays(6)->startOfDay();
            $dailyPayments = \App\Models\Payment::where('paid_at', '>=', $sevenDaysAgo)
                ->where('type', 'income')
                ->selectRaw('DATE(paid_at) as p_date, SUM(amount) as total_amt')
                ->groupBy('p_date')
                ->pluck('total_amt', 'p_date')
                ->toArray();

            $chartDays = [];
            $chartAmounts = [];
            for ($i = 6; $i >= 0; $i--) {
                $d = today()->subDays($i);
                $chartDays[] = $d->format('D (d/m)');
                $key = $d->format('Y-m-d');
                $chartAmounts[] = (float) ($dailyPayments[$key] ?? 0);
            }
        } catch (\Throwable $e) {
            \Log::error('Dashboard Root Metric Error: ' . $e->getMessage());
            $todayRevenue = $cashRevenue = $instapayRevenue = $walletRevenue = 0;
            $activeDeals = collect();
            $activeDealsCount = 0;
            $rooms = collect();
            $totalRooms = $availableRoomsCount = $occupiedRoomsCount = 0;
            $totalCustomers = $totalProducts = 0;
            $recentDeals = collect();
            $chartDays = [];
            $chartAmounts = [];
        }

        return view('index', compact(
            'todayRevenue', 'cashRevenue', 'instapayRevenue', 'walletRevenue',
            'activeDeals', 'activeDealsCount', 'rooms', 'totalRooms',
            'availableRoomsCount', 'occupiedRoomsCount', 'totalCustomers',
            'totalProducts', 'recentDeals', 'chartDays', 'chartAmounts'
        ));
    }

    /**
     * Standard 404 Not Found response
     */
    public function notFound()
    {
        return response()->view('pages.404', [], 404);
    }

    /**
     * Redirect referral code to portal registration
     */
    public function referralRedirect(string $code)
    {
        return redirect()->route('portal.register', ['ref' => $code]);
    }

    /**
     * Legacy events redirect
     */
    public function eventsRedirect()
    {
        return redirect()->route('admin.events.index');
    }
}
