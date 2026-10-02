<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Deal;
use App\Models\Order;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->query('period', 'month'); // month, today, week, 30days, custom
        $classificationFilter = $request->query('classification');

        // Determine date range
        $now = Carbon::now();
        switch ($period) {
            case 'today':
                $startDate = Carbon::today()->startOfDay();
                $endDate = Carbon::today()->endOfDay();
                $prevStartDate = Carbon::yesterday()->startOfDay();
                $prevEndDate = Carbon::yesterday()->endOfDay();
                $periodLabel = 'اليوم ('.$startDate->translatedFormat('d F Y').')';
                break;

            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $prevStartDate = Carbon::now()->subMonth()->startOfMonth();
                $prevEndDate = Carbon::now()->subMonth()->endOfMonth();
                $periodLabel = 'هذا الشهر ('.$startDate->translatedFormat('F Y').')';
                break;

            case '30days':
                $startDate = Carbon::now()->subDays(29)->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                $prevStartDate = Carbon::now()->subDays(59)->startOfDay();
                $prevEndDate = Carbon::now()->subDays(30)->endOfDay();
                $periodLabel = 'آخر 30 يوماً';
                break;

            case 'custom':
                $fromInput = $request->query('from_date');
                $toInput = $request->query('to_date');
                $startDate = $fromInput ? Carbon::parse($fromInput)->startOfDay() : Carbon::now()->subDays(6)->startOfDay();
                $endDate = $toInput ? Carbon::parse($toInput)->endOfDay() : Carbon::now()->endOfDay();
                $diffDays = max(1, $startDate->diffInDays($endDate));
                $prevEndDate = (clone $startDate)->subSecond();
                $prevStartDate = (clone $startDate)->subDays($diffDays);
                $periodLabel = 'فترة مخصصة ('.$startDate->format('Y-m-d').' إلى '.$endDate->format('Y-m-d').')';
                break;

            case 'week':
            default:
                $period = 'week';
                $startDate = Carbon::now()->subDays(6)->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                $prevStartDate = Carbon::now()->subDays(13)->startOfDay();
                $prevEndDate = Carbon::now()->subDays(7)->endOfDay();
                $periodLabel = 'آخر 7 أيام (أسبوع)';
                break;
        }

        // Base Customers Query (respecting optional classification filter)
        $customersQuery = Customer::query();
        if ($classificationFilter) {
            $customersQuery->where('classification', $classificationFilter);
        }

        // ── 1. Real-time App & Portal Customers Metrics ──
        $onlineAppCustomersCount = Customer::where('last_active_at', '>=', Carbon::now()->subMinutes(15))->count();
        $todayAppActiveCount = Customer::whereDate('last_active_at', Carbon::today())->count();
        $recentAppCustomers = Customer::whereNotNull('last_active_at')
            ->orderBy('last_active_at', 'desc')
            ->take(6)
            ->get();

        // ── 2. Customers Overview & Growth ──
        $totalCustomers = Customer::count();
        $newCustomersCount = (clone $customersQuery)->whereBetween('created_at', [$startDate, $endDate])->count();
        $prevNewCustomersCount = (clone $customersQuery)->whereBetween('created_at', [$prevStartDate, $prevEndDate])->count();
        $newCustomersGrowth = $prevNewCustomersCount > 0
            ? round((($newCustomersCount - $prevNewCustomersCount) / $prevNewCustomersCount) * 100, 1)
            : ($newCustomersCount > 0 ? 100 : 0);

        // Classification Breakdown (Overall & in Period)
        $classificationMap = Customer::classifications();
        $classificationStats = [];
        $rawClassification = Customer::select('classification', DB::raw('count(*) as count'))
            ->groupBy('classification')
            ->pluck('count', 'classification')
            ->toArray();

        foreach ($classificationMap as $key => $label) {
            $cnt = $rawClassification[$key] ?? 0;
            $pct = $totalCustomers > 0 ? round(($cnt / $totalCustomers) * 100, 1) : 0;
            $classificationStats[$key] = [
                'key' => $key,
                'label' => $label,
                'count' => $cnt,
                'percentage' => $pct,
            ];
        }

        // Daily Registrations Chart for Selected Period (Aggregated in 1 Single Query)
        $dailyCounts = Customer::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as reg_date, COUNT(*) as total')
            ->groupBy('reg_date')
            ->pluck('total', 'reg_date')
            ->toArray();

        $registrationChart = ['labels' => [], 'data' => []];
        $currentCursor = (clone $startDate)->startOfDay();
        while ($currentCursor->lte($endDate)) {
            $dateKey = $currentCursor->format('Y-m-d');
            $registrationChart['labels'][] = $currentCursor->translatedFormat('D d M');
            $registrationChart['data'][] = (int) ($dailyCounts[$dateKey] ?? 0);
            $currentCursor->addDay();
        }

        // ── 3. Customer Frequency & Retention Analytics ──
        // Count how many visits each customer had
        $visitsPerCustomer = Deal::select('customer_id', DB::raw('count(*) as total_visits'))
            ->whereNotNull('customer_id')
            ->groupBy('customer_id')
            ->pluck('total_visits', 'customer_id');

        $totalCustomersWithVisits = $visitsPerCustomer->count();
        $singleVisitCustomers = $visitsPerCustomer->filter(fn ($v) => $v == 1)->count();
        $repeatVisitors = $visitsPerCustomer->filter(fn ($v) => $v >= 2)->count();
        $frequentVisitors = $visitsPerCustomer->filter(fn ($v) => $v >= 5)->count();
        $retentionRate = $totalCustomersWithVisits > 0 ? round(($repeatVisitors / $totalCustomersWithVisits) * 100, 1) : 0;
        $avgVisitsPerCustomer = $totalCustomersWithVisits > 0 ? round($visitsPerCustomer->sum() / $totalCustomersWithVisits, 1) : 0;

        // Top Frequent Customers
        $topFrequentCustomers = Customer::withCount(['deals' => function ($q) use ($startDate, $endDate) {
            $q->whereBetween('started_at', [$startDate, $endDate]);
        }])
            ->withSum(['deals' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('started_at', [$startDate, $endDate]);
            }], 'duration_minutes')
            ->withSum(['orders' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            }], 'total')
            ->orderBy('deals_count', 'desc')
            ->take(8)
            ->get();

        // ── 4. Orders & Top Products Analytics ──
        $ordersInPeriod = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled');

        $totalOrdersCount = (clone $ordersInPeriod)->count();
        $totalOrdersRevenue = (float) (clone $ordersInPeriod)->sum('total');
        $portalOrdersCount = (clone $ordersInPeriod)->where('source', 'portal')->count();
        $posOrdersCount = (clone $ordersInPeriod)->where('source', '!=', 'portal')->count();
        $portalOrdersShare = $totalOrdersCount > 0 ? round(($portalOrdersCount / $totalOrdersCount) * 100, 1) : 0;

        // Top 10 Requested Products
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
            ->leftJoin('product_categories', 'products.category_id', '=', 'product_categories.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.status', '!=', 'cancelled')
            ->select(
                'order_items.name',
                DB::raw('SUM(order_items.quantity) as total_qty'),
                DB::raw('SUM(order_items.total) as total_revenue'),
                'product_categories.name as category_name'
            )
            ->groupBy('order_items.name', 'product_categories.name')
            ->orderBy('total_qty', 'desc')
            ->take(10)
            ->get();

        // ── 5. Room Utilization & Capacity Analysis (Aggregated in 1 Query) ──
        $rooms = Room::where('status', 'active')->get();
        $totalSpaceCapacity = $rooms->sum('capacity') ?: 50;

        $roomDealsAgg = Deal::whereBetween('started_at', [$startDate, $endDate])
            ->selectRaw('room_id, COUNT(*) as deals_count, SUM(duration_minutes) as total_minutes')
            ->groupBy('room_id')
            ->get()
            ->keyBy('room_id');

        $periodDays = max(1, $startDate->diffInDays($endDate) + 1);
        $totalAvailableHours = $periodDays * 14;

        $roomStats = [];
        foreach ($rooms as $room) {
            $agg = $roomDealsAgg->get($room->id);
            $dealsCount = $agg ? (int) $agg->deals_count : 0;
            $totalMinutes = $agg ? (int) $agg->total_minutes : 0;
            $totalHours = round($totalMinutes / 60, 1);
            $utilizationRate = $totalAvailableHours > 0 ? min(100, round(($totalHours / $totalAvailableHours) * 100, 1)) : 0;

            $roomStats[] = [
                'id' => $room->id,
                'name' => $room->name,
                'color' => $room->color ?: '#4E8F35',
                'capacity' => $room->capacity ?: 1,
                'deals_count' => $dealsCount,
                'total_hours' => $totalHours,
                'utilization_rate' => $utilizationRate,
            ];
        }

        // Sort rooms by total hours used
        usort($roomStats, fn ($a, $b) => $b['total_hours'] <=> $a['total_hours']);

        // ── 6. Hourly Occupancy & Peak Hours vs Empty Hours Analysis ──
        // Operating hours: 08:00 (8 AM) to 23:00 (11 PM)
        $operatingHours = range(8, 23);
        $hourlyOccupancy = [];
        $dealsInPeriod = Deal::whereBetween('started_at', [$startDate, $endDate])
            ->select('id', 'started_at', 'ended_at', 'duration_minutes', 'room_id')
            ->get();

        $daysInPeriod = max(1, $startDate->diffInDays($endDate) + 1);

        foreach ($operatingHours as $h) {
            $activeCountInThisHour = 0;
            foreach ($dealsInPeriod as $d) {
                $startH = Carbon::parse($d->started_at)->hour;
                $endH = $d->ended_at ? Carbon::parse($d->ended_at)->hour : min(23, $startH + max(1, (int) round(($d->duration_minutes ?: 60) / 60)));
                if ($startH <= $h && $endH >= $h) {
                    $activeCountInThisHour++;
                }
            }

            // Average concurrent customers at this hour per day
            $avgConcurrentAtHour = round($activeCountInThisHour / $daysInPeriod, 1);
            $occupancyRateAtHour = $totalSpaceCapacity > 0 ? min(100, round(($avgConcurrentAtHour / $totalSpaceCapacity) * 100, 1)) : 0;

            $status = 'moderate';
            if ($occupancyRateAtHour >= 60) {
                $status = 'peak'; // ذروة
            } elseif ($occupancyRateAtHour <= 25) {
                $status = 'low'; // هدوء / فاضي
            }

            $formattedHour = sprintf('%02d:00', $h);
            $hourlyOccupancy[] = [
                'hour' => $h,
                'time_label' => $formattedHour.' - '.sprintf('%02d:00', ($h + 1) % 24),
                'short_label' => $h > 12 ? ($h - 12).' م' : ($h == 12 ? '12 م' : $h.' ص'),
                'avg_customers' => $avgConcurrentAtHour,
                'occupancy_rate' => $occupancyRateAtHour,
                'status' => $status,
            ];
        }

        // Find peak and quiet hours
        $sortedByOccupancy = $hourlyOccupancy;
        usort($sortedByOccupancy, fn ($a, $b) => $b['occupancy_rate'] <=> $a['occupancy_rate']);
        $peakHourItem = $sortedByOccupancy[0] ?? null;
        $quietHourItem = end($sortedByOccupancy) ?: null;

        return view('reports.index', [
            'period' => $period,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'periodLabel' => $periodLabel,
            'classificationFilter' => $classificationFilter,

            // Live app activity
            'onlineAppCustomersCount' => $onlineAppCustomersCount,
            'todayAppActiveCount' => $todayAppActiveCount,
            'recentAppCustomers' => $recentAppCustomers,

            // Customer metrics
            'totalCustomers' => $totalCustomers,
            'newCustomersCount' => $newCustomersCount,
            'newCustomersGrowth' => $newCustomersGrowth,
            'classificationStats' => $classificationStats,
            'registrationChart' => $registrationChart,

            // Retention & Frequency
            'totalCustomersWithVisits' => $totalCustomersWithVisits,
            'singleVisitCustomers' => $singleVisitCustomers,
            'repeatVisitors' => $repeatVisitors,
            'frequentVisitors' => $frequentVisitors,
            'retentionRate' => $retentionRate,
            'avgVisitsPerCustomer' => $avgVisitsPerCustomer,
            'topFrequentCustomers' => $topFrequentCustomers,

            // Orders & Products
            'totalOrdersCount' => $totalOrdersCount,
            'totalOrdersRevenue' => $totalOrdersRevenue,
            'portalOrdersCount' => $portalOrdersCount,
            'posOrdersCount' => $posOrdersCount,
            'portalOrdersShare' => $portalOrdersShare,
            'topProducts' => $topProducts,

            // Rooms & Capacity
            'rooms' => $rooms,
            'roomStats' => $roomStats,
            'totalSpaceCapacity' => $totalSpaceCapacity,

            // Hourly analysis
            'hourlyOccupancy' => $hourlyOccupancy,
            'peakHourItem' => $peakHourItem,
            'quietHourItem' => $quietHourItem,
        ]);
    }
}
