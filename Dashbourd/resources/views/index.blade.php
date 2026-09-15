@extends('shared.vertical', ['title' => 'لوحة التحكم — DT-System'])

@php
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
    $totalProducts = \App\Models\Product::where('is_active', true)->count();

    $recentDeals = \App\Models\Deal::with(['customer', 'room', 'workspaceType', 'order'])
        ->latest('started_at')
        ->limit(8)
        ->get();

    // 7-day revenue chart data
    $chartDays = [];
    $chartAmounts = [];
    for ($i = 6; $i >= 0; $i--) {
        $d = today()->subDays($i);
        $chartDays[] = $d->format('D (d/m)');
        $amt = \App\Models\Payment::whereDate('paid_at', $d)->where('type', 'income')->sum('amount');
        $chartAmounts[] = (float) $amt;
    }
@endphp

@section('content')

    {{-- Page Header & Breadcrumb --}}
    <div class="page-header-container">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-bold text-primary bg-primary/10 px-2.5 py-0.5 rounded-full">نظام إدارة المساحة</span>
                <span class="text-xs text-default-400 font-mono">DT-SYSTEM Hub</span>
            </div>
            <h2 class="text-2xl font-extrabold text-default-900 tracking-tight">
                لوحة التحكم الرئيسية
            </h2>
            <p class="text-xs text-default-400 mt-1">مرحباً بك! إليك ملخص حي لنشاط مساحة العمل والعمليات المالية اليوم</p>
        </div>

        {{-- Top Action Buttons --}}
        <div class="page-header-actions">
            <a href="{{ url('/cashier') }}" target="_blank"
               class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-emerald-600/25 gap-2">
                <svg width="18" height="18" class="text-white shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/><path d="m9 10 2 2 4-4"/>
                </svg>
                <span>فتح شاشة الكاشير (POS)</span>
            </a>
            <a href="{{ url('/settings/general') }}"
               class="px-3.5 py-2.5 bg-white hover:bg-default-50 border border-default-200 text-default-700 rounded-xl text-xs font-bold transition-all shadow-sm gap-2">
                <svg width="18" height="18" class="text-rose-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="13.5" cy="6.5" r=".5" fill="currentColor"/><circle cx="17.5" cy="10.5" r=".5" fill="currentColor"/><circle cx="8.5" cy="7.5" r=".5" fill="currentColor"/><circle cx="6.5" cy="12.5" r=".5" fill="currentColor"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z"/>
                </svg>
                <span>تخصيص الألوان واللوجو</span>
            </a>
        </div>
    </div>

    {{-- ── 4 KPI STAT CARDS ── --}}
    <div class="dt-grid-4">

        {{-- 1. Today Revenue --}}
        <div class="card border border-default-100 rounded-2xl shadow-sm hover:shadow-md transition-all p-5 bg-white relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-default-400 uppercase tracking-wider block mb-1">إيراد اليوم الإجمالي</span>
                    <h3 class="text-2xl font-black text-default-900">
                        {{ number_format($todayRevenue, 2) }}
                        <span class="text-xs font-bold text-default-500">ج.م</span>
                    </h3>
                </div>
                <div class="size-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-inner">
                    <svg class="size-6 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>
                    </svg>
                </div>
            </div>

            {{-- Breakdown by method --}}
            <div class="mt-4 pt-3 border-t border-default-100 flex items-center justify-between text-[11px] font-semibold text-default-500">
                <span class="flex items-center gap-1.5">
                    <span class="size-2 rounded-full bg-emerald-500 inline-block"></span>
                    كاش: <strong class="text-default-800 font-mono">{{ number_format($cashRevenue) }}</strong>
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="size-2 rounded-full bg-indigo-500 inline-block"></span>
                    إنستاباي: <strong class="text-default-800 font-mono">{{ number_format($instapayRevenue) }}</strong>
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="size-2 rounded-full bg-amber-500 inline-block"></span>
                    محفظة: <strong class="text-default-800 font-mono">{{ number_format($walletRevenue) }}</strong>
                </span>
            </div>
        </div>

        {{-- 2. Active Sessions --}}
        <div class="card border border-default-100 rounded-2xl shadow-sm hover:shadow-md transition-all p-5 bg-white relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-default-400 uppercase tracking-wider block mb-1">الجلسات المفتوحة حالياً</span>
                    <h3 class="text-2xl font-black text-default-900">
                        {{ $activeDealsCount }}
                        <span class="text-xs font-bold text-default-500">جلسة</span>
                    </h3>
                </div>
                <div class="size-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center shadow-inner">
                    <svg class="size-6 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-default-100 flex items-center justify-between text-[11px] font-semibold">
                <span class="text-emerald-600 flex items-center gap-1.5">
                    <span class="size-2 rounded-full bg-emerald-500 inline-block animate-ping"></span>
                    {{ $activeDealsCount > 0 ? 'يوجد رواد بالمساحة حالياً' : 'لا توجد جلسات مفتوحة' }}
                </span>
                <a href="{{ url('/cashier') }}" target="_blank" class="text-primary hover:underline text-[11px] font-bold">
                    فتح الجلسات ←
                </a>
            </div>
        </div>

        {{-- 3. Room Occupancy --}}
        <div class="card border border-default-100 rounded-2xl shadow-sm hover:shadow-md transition-all p-5 bg-white relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-default-400 uppercase tracking-wider block mb-1">إشغال الغرف والمساحات</span>
                    <h3 class="text-2xl font-black text-default-900">
                        {{ $availableRoomsCount }} / {{ $totalRooms }}
                        <span class="text-xs font-bold text-emerald-600">متاحة</span>
                    </h3>
                </div>
                <div class="size-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shadow-inner">
                    <svg class="size-6 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M13 4h3a2 2 0 0 1 2 2v14"/><path d="M2 20h20"/><path d="M13 20V4a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v16"/><circle cx="9" cy="12" r="1"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-default-100 flex items-center justify-between text-[11px] font-semibold text-default-500">
                <span class="text-default-600">مشغولة: <strong class="text-rose-600 font-bold">{{ $occupiedRoomsCount }}</strong></span>
                <span class="text-default-600">شاغرة: <strong class="text-emerald-600 font-bold">{{ $availableRoomsCount }}</strong></span>
                <span class="text-primary font-bold">{{ $totalRooms > 0 ? round(($occupiedRoomsCount / $totalRooms) * 100) : 0 }}% إشغال</span>
            </div>
        </div>

        {{-- 4. Total Customers & Cafe --}}
        <div class="card border border-default-100 rounded-2xl shadow-sm hover:shadow-md transition-all p-5 bg-white relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-default-400 uppercase tracking-wider block mb-1">العملاء والمنتجات</span>
                    <h3 class="text-2xl font-black text-default-900">
                        {{ $totalCustomers }}
                        <span class="text-xs font-bold text-default-500">عميل مسجل</span>
                    </h3>
                </div>
                <div class="size-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-inner">
                    <svg class="size-6 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-default-100 flex items-center justify-between text-[11px] font-semibold text-default-500">
                <span>منتجات الكافيه: <strong class="text-default-800">{{ $totalProducts }}</strong></span>
                <span class="text-emerald-600 font-bold">● نشط وجاهز</span>
            </div>
        </div>

    </div>

    {{-- ── SECTION 2: LIVE ROOMS STATUS & REVENUE CHART ── --}}
    <div class="grid xl:grid-cols-3 gap-6 mb-6">

        {{-- Live Rooms Status Cards (2 cols on xl) --}}
        <div class="xl:col-span-2 card border border-default-100 rounded-2xl shadow-sm bg-white p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h4 class="text-base font-bold text-default-900 flex items-center gap-2">
                        <svg class="size-5 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/>
                        </svg>
                        <span>حالة الغرف والمساحات المباشرة</span>
                    </h4>
                    <p class="text-xs text-default-400 mt-0.5">مراقبة فورية للغرف الشاغرة والمشغولة بالوقت الفعلي</p>
                </div>
                <a href="{{ url('/cashier') }}" target="_blank" class="text-xs font-bold text-primary hover:underline flex items-center gap-1">
                    <span>إدارة الغرف في الكاشير</span>
                    <span>←</span>
                </a>
            </div>

            <div class="grid sm:grid-cols-3 gap-4">
                @forelse($rooms as $room)
                    @php
                        $isOccupied = !$room->is_available;
                        $deal = $room->activeDeals->first();
                    @endphp
                    <div class="p-4 rounded-xl border {{ $isOccupied ? 'border-rose-200 bg-rose-50/40' : 'border-default-200 bg-default-50/50' }} flex flex-col justify-between transition-all hover:shadow-sm">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="size-3 rounded-full" style="background-color: {{ $room->color ?? '#6366f1' }}"></span>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $isOccupied ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700' }}">
                                    {{ $isOccupied ? 'مشغولة' : 'شاغرة ومتاحة' }}
                                </span>
                            </div>
                            <h5 class="text-sm font-bold text-default-800">{{ $room->name }}</h5>
                            <p class="text-xs text-default-400 mt-0.5">السعة: {{ $room->capacity ?? 4 }} أفراد</p>
                        </div>

                        <div class="mt-4 pt-3 border-t {{ $isOccupied ? 'border-rose-200/60' : 'border-default-200' }}">
                            @if($isOccupied && $deal)
                                <p class="text-xs font-semibold text-default-700 truncate flex items-center gap-1.5">
                                    <svg width="13" height="13" class="text-default-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    <span>{{ $deal->customer->full_name ?? 'عميل' }}</span>
                                </p>
                                <p class="text-[10px] text-default-400 font-mono mt-0.5">
                                    منذ: {{ $deal->started_at->format('h:i A') }}
                                </p>
                            @else
                                <a href="{{ url('/cashier') }}" target="_blank"
                                   class="w-full text-center block text-xs font-bold text-primary hover:text-primary/80 py-1 rounded bg-white border border-primary/20 hover:border-primary transition-all">
                                    + حجز جلسة
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-8 text-default-400 text-xs">
                        لا توجد غرف مضافة حالياً.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- 7-Day Revenue Chart (1 col on xl) --}}
        <div class="card border border-default-100 rounded-2xl shadow-sm bg-white p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h4 class="text-base font-bold text-default-900 flex items-center gap-2">
                        <svg class="size-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/>
                        </svg>
                        <span>إيرادات آخر 7 أيام</span>
                    </h4>
                    <p class="text-xs text-default-400 mt-0.5">معدل التحصيل اليومي بالجنيه المصري</p>
                </div>
            </div>

            <div id="revenue-weekly-chart" class="min-h-[260px]"></div>
        </div>

    </div>

    {{-- ── SECTION 3: RECENT SESSIONS TABLE ── --}}
    <div class="card border border-default-100 rounded-2xl shadow-sm bg-white overflow-hidden mb-6">
        <div class="p-5 border-b border-default-100 flex items-center justify-between">
            <div>
                <h4 class="text-base font-bold text-default-900 flex items-center gap-2">
                    <svg class="size-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 14 14"/>
                    </svg>
                    <span>آخر الجلسات والتعاملات الأخيرة</span>
                </h4>
                <p class="text-xs text-default-400 mt-0.5">سجل الجلسات المفتوحة والمنتهية المسجلة في النظام</p>
            </div>
            <a href="{{ url('/cashier') }}" target="_blank"
               class="inline-flex items-center gap-1 text-xs font-bold text-primary hover:underline">
                <span>عرض شاشة الكاشير</span>
                <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" x2="21" y1="14" y2="3"/></svg>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-start text-xs">
                <thead class="bg-default-50 text-default-600 font-bold border-b border-default-100">
                    <tr>
                        <th class="px-5 py-3.5 text-start">رقم الجلسة</th>
                        <th class="px-5 py-3.5 text-start">اسم العميل</th>
                        <th class="px-5 py-3.5 text-start">الغرفة / المساحة</th>
                        <th class="px-5 py-3.5 text-start">وقت البدء</th>
                        <th class="px-5 py-3.5 text-start">الحالة</th>
                        <th class="px-5 py-3.5 text-start">المبلغ الإجمالي</th>
                        <th class="px-5 py-3.5 text-center">إجراء</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-default-100 font-medium">
                    @forelse($recentDeals as $deal)
                        <tr class="hover:bg-default-50/70 transition-colors">
                            <td class="px-5 py-3.5 font-mono font-bold text-primary">
                                #{{ $deal->deal_number }}
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="font-bold text-default-800">{{ $deal->customer->full_name ?? 'عميل' }}</span>
                                <span class="block text-[11px] text-default-400 font-mono">{{ $deal->customer->phone ?? '' }}</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="font-semibold text-default-700">{{ $deal->room->name ?? 'مساحة مشتركة' }}</span>
                                <span class="block text-[10px] text-default-400">{{ $deal->workspaceType->name ?? 'Shared' }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-default-600 font-mono">
                                {{ $deal->started_at->format('h:i A') }}
                                <span class="block text-[10px] text-default-400">{{ $deal->started_at->format('Y-m-d') }}</span>
                            </td>
                            <td class="px-5 py-3.5">
                                @if($deal->status === 'open')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                        <span class="size-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        جلسة مفتوحة
                                    </span>
                                @elseif($deal->status === 'closed')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">
                                        مغلقة / بانتظار الدفع
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                        <span>مدفوعة بالكامل</span>
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 font-bold font-mono text-default-900">
                                {{ number_format($deal->order->total ?? $deal->applied_price ?? 0, 2) }} ج.م
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <a href="{{ url('/cashier') }}" target="_blank"
                                   class="inline-flex items-center gap-1 px-2.5 py-1 bg-default-100 hover:bg-primary hover:text-white rounded-lg text-[11px] font-bold text-default-700 transition-all">
                                    <span>فتح في الكاشير</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-default-400 text-xs">
                                لا توجد جلسات مسجلة حتى الآن. ابدأ أول جلسة من شاشة الكاشير!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Render 7-Day Revenue ApexChart
    const categories = @json($chartDays);
    const seriesData = @json($chartAmounts);

    const options = {
        series: [{
            name: 'الإيراد اليومي (ج.م)',
            data: seriesData
        }],
        chart: {
            type: 'area',
            height: 260,
            toolbar: { show: false },
            fontFamily: 'Cairo, sans-serif'
        },
        colors: ['#10b981'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.45,
                opacityTo: 0.05,
                stops: [20, 100]
            }
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        dataLabels: { enabled: false },
        xaxis: {
            categories: categories,
            labels: {
                style: { fontSize: '11px', colors: '#64748b' }
            }
        },
        yaxis: {
            labels: {
                formatter: function (val) { return val + ' ج.م'; },
                style: { fontSize: '11px', colors: '#64748b' }
            }
        },
        tooltip: {
            y: {
                formatter: function (val) { return val.toFixed(2) + ' ج.م'; }
            }
        }
    };

    const chart = new ApexCharts(document.querySelector("#revenue-weekly-chart"), options);
    chart.render();
});
</script>
@endsection
