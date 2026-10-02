<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
 <meta charset="UTF-8"/>
 <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
 <title>شاشة الكاشير ونقاط البيع (POS) — DT-System</title>
 <meta name="description" content="نظام الكاشير ونقاط البيع المتكامل لمساحات العمل"/>
 <meta name="csrf-token" content="{{ csrf_token() }}">

 <!-- Google Fonts: Cairo -->
 <link rel="preconnect" href="https://fonts.googleapis.com">
 <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
 <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>

 <!-- Tailwind CDN -->
 <script src="https://cdn.tailwindcss.com"></script>
 <script>
 tailwind.config = {
 theme: {
 extend: {
 colors: {
 primary: {
 DEFAULT: '#4E8F35',
 hover: '#3F742B',
 light: '#DCE8D4',
 50: '#F5F9F2',
 100: '#E6F2DF',
 200: '#DCE8D4',
 500: '#4E8F35',
 600: '#3F742B',
 700: '#325C22'
 },
 ddt: {
 green: '#4E8F35',
 light: '#79B84A',
 sage: '#DCE8D4',
 charcoal: '#303334',
 gray: '#73777A',
 }
 },
 fontFamily: {
 sans: ['Cairo', 'sans-serif'],
 }
 }
 }
 };
 </script>

 <style>
 * {
 box-sizing: border-box;
 margin: 0;
 padding: 0;
 font-family: 'Cairo', system-ui, -apple-system, sans-serif !important;
 -webkit-font-smoothing: antialiased;
 }

 :root {
 --primary: #4E8F35;
 --primary-hover: #3F742B;
 --primary-light: #DCE8D4;
 }

 body {
 background-color: #f8fafc;
 color: #303334;
 height: 100vh;
 width: 100vw;
 overflow: hidden;
 display: flex;
 flex-direction: column;
 user-select: none;
 }

 /* Solid explicit fallback classes for buttons and active states */
 .btn-primary, .bg-primary {
 background-color: #4E8F35 !important;
 color: #ffffff !important;
 }
 .btn-primary:hover, .hover\:bg-primary:hover {
 background-color: #3F742B !important;
 color: #ffffff !important;
 }
 .text-primary {
 color: #4E8F35 !important;
 }
 .border-primary {
 border-color: #4E8F35 !important;
 }

 /* Custom Scrollbars */
 ::-webkit-scrollbar { width: 6px; height: 6px; }
 ::-webkit-scrollbar-track { background: #f1f5f9; }
 ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 6px; }
 ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

 /* Thermal Receipt Modal Print Styles */
 @media print {
 body * { visibility: hidden !important; }
 #receipt-printable-area, #receipt-printable-area * {
 visibility: visible !important;
 }
 #receipt-printable-area {
 position: fixed;
 left: 0;
 top: 0;
 width: 80mm;
 margin: 0;
 padding: 10px;
 background: white !important;
 color: black !important;
 }
 .no-print { display: none !important; }
 }
 </style>
</head>
<body class="bg-[#f8fafc] text-slate-900 flex flex-col h-screen overflow-hidden">

 {{-- ════════════════════════════════════════════════════════ --}}
 {{-- 1. TOP STATUS BAR (الهيدر العلوي الأبيض العصري) --}}
 {{-- ════════════════════════════════════════════════════════ --}}
 <header class="h-16 bg-white border-b border-slate-200 px-4 md:px-6 flex items-center justify-between gap-3 shrink-0 z-20 shadow-sm">
 {{-- Brand & System Name --}}
 <div class="flex items-center gap-3.5">
 <a href="{{ url('/') }}" class="flex items-center gap-2.5 text-decoration-none group" title="العودة للوحة التحكم">
 <img src="{{ asset('images/ddt-logo.svg') }}" alt="DDT Working Space" class="h-10 object-contain"/>
 </a>

 <div class="h-6 w-px bg-slate-200 mx-2 hidden sm:block"></div>

 {{-- Shift Status Badge --}}
 @if($currentShift)

 @else
 <a href="{{ url('/shifts/current') }}" target="_blank"
 class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-50 border border-amber-300 text-amber-800 text-xs font-bold hover:bg-amber-100 transition-all shadow-xs">
 <svg width="14" height="14" class="text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
 <span>لا توجد وردية مفتوحة</span>
 </a>
 @endif
 </div>

 {{-- Center Quick Search & Clock --}}
 <div class="flex items-center gap-3">
 {{-- Fast Search --}}
 <div class="relative w-56 md:w-80">
 <input type="text" id="pos-global-search" placeholder="بحث سريع عن غرفة، عميل، أو صنف..."
 class="w-full bg-[#F5F3EE] border border-[#E5E2DC] text-[#303334] text-xs font-semibold rounded-xl px-3.5 py-2 pe-9 focus:border-[#4E8F35] focus:bg-white focus:ring-2 focus:ring-[#EBF4E8] outline-none transition-all placeholder:text-[#73777A]">
 <span class="absolute end-3 top-2.5 text-slate-400">
 <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
 <circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/>
 </svg>
 </span>
 </div>

 {{-- Live Clock --}}
 <div class="hidden lg:flex items-center gap-2 font-mono text-xs font-black text-[#303334] bg-[#F5F3EE] px-3.5 py-2 rounded-xl border border-[#E5E2DC]">
 <span class="w-2 h-2 rounded-full bg-[#4E8F35]"></span>
 <span id="pos-live-clock">--:--:--</span>
 </div>
 </div>

 {{-- Top Right Actions --}}
 <div class="flex items-center gap-2.5">
 {{-- + جلسة جديدة --}}
 <button type="button" onclick="openNewSessionModal()"
 class="btn-primary inline-flex items-center gap-2 px-4 py-2 bg-[#4E8F35] hover:bg-[#3F742B] text-white rounded-xl text-xs font-extrabold transition-all shadow-xs cursor-pointer">
 <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
 <line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/>
 </svg>
 <span>+ جلسة جديدة (F2)</span>
 </button>

 {{-- طلبات المشروبات أونلاين --}}
 <button type="button" onclick="document.getElementById('mobile-orders-bar').scrollIntoView({behavior: 'smooth'})"
 class="inline-flex items-center gap-2 px-3 py-2 bg-[#F5F3EE] hover:bg-[#EBF4E8] border border-[#E5E2DC] text-[#303334] rounded-xl text-xs font-bold transition-all shadow-xs cursor-pointer relative">
 <span class="text-base"></span>
 <span class="hidden md:inline font-extrabold">طلبات الموبايل</span>
 <span id="portal-orders-badge" class="px-1.5 py-0.5 rounded-full text-[10px] font-black bg-[#4E8F35] text-white min-w-[20px] text-center {{ $pendingPortalOrders->count() > 0 ? '' : 'hidden' }}">
 {{ $pendingPortalOrders->count() }}
 </span>
 </button>

 {{-- + عميل جديد --}}
 <button type="button" onclick="openAddCustomerModal()"
 class="inline-flex items-center gap-2 px-3.5 py-2 bg-white hover:bg-[#F5F3EE] border border-[#E5E2DC] text-[#303334] rounded-xl text-xs font-bold transition-all shadow-xs cursor-pointer">
 <svg width="16" height="16" class="text-[#4E8F35]" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
 <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/>
 </svg>
 <span class="hidden md:inline">عميل جديد (F3)</span>
 </button>

 {{-- Return to Dashboard --}}
 <a href="{{ url('/') }}" title="العودة للوحة التحكم الرئيسية"
 class="p-2 rounded-xl bg-white hover:bg-[#F5F3EE] border border-[#E5E2DC] text-[#73777A] hover:text-[#303334] transition-all shadow-xs">
 <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
 <rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/>
 </svg>
 </a>
 </div>
 </header>

{{-- ════════════════════════════════════════════════════════ --}}
{{-- 1.5 TOP TOTALS BAR (شريط إجماليات المفتوح والمقفول والمديونيات) --}}
{{-- ════════════════════════════════════════════════════════ --}}
<div class="bg-[#F8F7F4] border-b border-[#E5E2DC] px-4 py-2.5 flex flex-wrap items-center justify-between gap-3 text-xs shrink-0 font-bold z-10">
    <div class="flex items-center gap-3 flex-wrap">
        {{-- Open Sessions Total --}}
        <div class="flex items-center gap-2 px-3 py-1.5 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-900 shadow-2xs">
            <span class="size-2.5 rounded-full bg-emerald-600 animate-pulse"></span>
            <span class="font-extrabold text-[#303334]">إجمالي الجلسات المفتوحة:</span>
            <span class="font-mono font-black text-sm text-emerald-700" id="top-open-sessions-total">{{ number_format($totalOpenSessionsValue, 2) }} <span class="text-[10px] font-bold">ج.م</span></span>
        </div>

        {{-- Closed Sessions Total Today --}}
        <div class="flex items-center gap-2 px-3 py-1.5 bg-blue-50 border border-blue-200 rounded-xl text-blue-900 shadow-2xs">
            <span class="size-2.5 rounded-full bg-blue-600"></span>
            <span class="font-extrabold text-[#303334]">إجمالي المقفولة اليوم:</span>
            <span class="font-mono font-black text-sm text-blue-700" id="top-closed-sessions-total">{{ number_format($totalClosedSessionsValueToday, 2) }} <span class="text-[10px] font-bold">ج.م</span></span>
        </div>

        {{-- Pending Debt Total --}}
        <div class="flex items-center gap-2 px-3 py-1.5 bg-rose-50 border border-rose-200 rounded-xl text-rose-900 shadow-2xs">
            <span class="size-2.5 rounded-full bg-rose-600"></span>
            <span class="font-extrabold text-[#303334]">إجمالي المديونيات المعلقة:</span>
            <span class="font-mono font-black text-sm text-rose-700" id="top-debt-total">{{ number_format($totalUnpaidDebtValue, 2) }} <span class="text-[10px] font-bold">ج.م</span></span>
        </div>
    </div>

    {{-- Controls: Hide Empty Rooms Toggle & Shift Verification --}}
    <div class="flex items-center gap-2">
        <button type="button" onclick="toggleHideEmptyRooms()" id="btn-toggle-empty-rooms"
                class="px-3 py-1.5 rounded-xl bg-white border border-[#E5E2DC] hover:border-[#4E8F35] text-[#303334] text-xs font-bold transition-all shadow-2xs flex items-center gap-1.5 cursor-pointer">
            <span class="size-2.5 rounded-full bg-slate-300" id="indicator-empty-rooms"></span>
            <span id="label-empty-rooms">إخفاء الغرف الخالية</span>
        </button>

        <a href="{{ url('/shifts/current') }}" target="_blank"
           class="px-3 py-1.5 rounded-xl bg-white border border-[#E5E2DC] hover:border-amber-500 text-slate-800 text-xs font-bold transition-all shadow-2xs flex items-center gap-1.5">
            <svg width="14" height="14" class="text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            <span>فحص الوردية والدرج</span>
        </a>
    </div>
</div>

 {{-- ════════════════════════════════════════════════════════ --}}
 {{-- 2. MAIN THREE-PANEL POS WORKSPACE --}}
 {{-- ════════════════════════════════════════════════════════ --}}
 <main class="flex-1 flex overflow-hidden">

 {{-- ── COLUMN 1 (RIGHT): الغرف والمساحات المباشرة ── --}}
 <section class="w-80 bg-white border-s border-[#E5E2DC] flex flex-col shrink-0">
 {{-- Header & Filter Tabs --}}
 <div class="p-3.5 border-b border-[#E5E2DC] bg-[#F8F7F4]">
 <div class="flex items-center justify-between mb-2.5">
 <h3 class="text-xs font-black text-[#303334] flex items-center gap-2">
 <svg width="16" height="16" class="text-[#4E8F35]" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
 <path d="M13 4h3a2 2 0 0 1 2 2v14"/><path d="M2 20h20"/><path d="M13 20V4a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v16"/>
 </svg>
 <span>الغرف والمساحات</span>
 </h3>
 <span id="pos-rooms-summary" class="text-[11px] font-mono text-[#303334] font-extrabold bg-[#F5F3EE] border border-[#E5E2DC] px-2 py-0.5 rounded-md">
 {{ $rooms->where('is_available', false)->count() }} مشغولة / {{ $rooms->count() }}
 </span>
 </div>

 {{-- Mode Switch Tabs: الغرف والمساحات / غير مسددة / تم السداد --}}
 <div class="flex items-center gap-1 p-1 bg-[#F5F3EE] border border-[#E5E2DC] rounded-xl text-xs font-bold mb-2">
 <button type="button" onclick="switchSidebarView('rooms')" id="view-tab-rooms"
 class="sidebar-view-tab flex-1 py-1.5 rounded-lg text-center bg-[#4E8F35] text-white shadow-xs font-extrabold transition-all cursor-pointer">
 الغرف
 </button>
 <button type="button" onclick="switchSidebarView('unpaid')" id="view-tab-unpaid"
 class="sidebar-view-tab flex-1 py-1.5 rounded-lg text-center text-[#73777A] hover:text-[#303334] font-bold transition-all cursor-pointer flex items-center justify-center gap-1">
 <span>غير مسددة</span>
 @if($unpaidClosedDeals->count() > 0)
 <span class="px-1.5 py-0.2 rounded-full text-[10px] font-black bg-rose-500 text-white">
 {{ $unpaidClosedDeals->count() }}
 </span>
 @endif
 </button>
 <button type="button" onclick="switchSidebarView('paid')" id="view-tab-paid"
 class="sidebar-view-tab flex-1 py-1.5 rounded-lg text-center text-[#73777A] hover:text-[#303334] font-bold transition-all cursor-pointer">
 المسددة ({{ $paidDealsToday->count() }})
 </button>
 </div>

 {{-- Filter Tabs (Only shown in Rooms view) --}}
 <div id="rooms-sub-filters" class="flex items-center gap-1 p-1 bg-white border border-[#E5E2DC] rounded-xl text-[11px] font-bold">
 <button type="button" onclick="filterRooms('all')" id="tab-room-all"
 class="room-filter-tab flex-1 py-1 rounded-lg text-center bg-[#4E8F35] text-white shadow-xs font-extrabold transition-all cursor-pointer">
 الكل
 </button>
 <button type="button" onclick="filterRooms('available')" id="tab-room-available"
 class="room-filter-tab flex-1 py-1 rounded-lg text-center text-[#73777A] hover:text-[#303334] font-bold transition-all cursor-pointer">
 متاحة ({{ $rooms->where('is_available', true)->count() }})
 </button>
 <button type="button" onclick="filterRooms('occupied')" id="tab-room-occupied"
 class="room-filter-tab flex-1 py-1 rounded-lg text-center text-[#73777A] hover:text-[#303334] font-bold transition-all cursor-pointer">
 مشغولة ({{ $rooms->where('is_available', false)->count() }})
 </button>
 </div>
 </div>

 {{-- VIEW 1: Rooms List / Grid --}}
 <div class="flex-1 overflow-y-auto p-3 space-y-2.5 sidebar-view-content" id="pos-rooms-list">
 @foreach($rooms as $room)
@php
    $activeDeals = $room->activeDeals;
    $occupancy = $activeDeals->count();
    $capacity = max(1, (int) ($room->capacity ?: 1));
    $isPrivate = $room->is_private_occupied;
    $isFull = $isPrivate || ($occupancy >= $capacity);
    $remaining = max(0, $capacity - $occupancy);
    $cardStatus = $isFull ? 'occupied' : 'available';
    $primaryDeal = $activeDeals->first();
@endphp
<div class="room-card p-3.5 rounded-2xl border transition-all relative overflow-hidden bg-white border-[#E5E2DC] hover:border-[#4E8F35]/70 shadow-xs"
     data-room-id="{{ $room->id }}"
     data-room-name="{{ $room->name }}"
     data-room-capacity="{{ $capacity }}"
     data-occupancy="{{ $occupancy }}"
     data-is-private="{{ $isPrivate ? '1' : '0' }}"
     data-status="{{ $cardStatus }}"
     data-deal-id="{{ $primaryDeal ? $primaryDeal->id : '' }}">

    {{-- Card Header --}}
    <div class="flex items-center justify-between mb-1.5 cursor-pointer"
         onclick="handleRoomHeaderClick({{ $room->id }}, {{ $isFull ? 'true' : 'false' }}, {{ $isPrivate ? 'true' : 'false' }}, {{ $primaryDeal ? $primaryDeal->id : 'null' }})">
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full {{ $isFull ? 'bg-rose-500' : ($occupancy > 0 ? 'bg-amber-500' : 'bg-[#4E8F35]') }}"></span>
            <span class="font-extrabold text-xs text-[#303334]">{{ $room->name }}</span>
        </div>
        @if($occupancy === 0)
            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-[#EBF4E8] text-[#4E8F35] border border-[#DCE8D4]">
                متاحة (سعة {{ $capacity }})
            </span>
        @elseif($isPrivate)
            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200">
                خاصة مشغولة 🔒
            </span>
        @elseif($isFull)
            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 border border-amber-300">
                مشتركة مكتملة ({{ $occupancy }}/{{ $capacity }})
            </span>
        @else
            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-[#EBF4E8] text-[#4E8F35] border border-[#DCE8D4]">
                مشتركة ({{ $occupancy }}/{{ $capacity }}) متاح {{ $remaining }}
            </span>
        @endif
    </div>

    {{-- Room Content --}}
    @if($occupancy === 0)
        {{-- Completely empty room --}}
        <div class="mt-2 pt-2 border-t border-[#E5E2DC] flex items-center justify-between text-xs text-[#73777A] font-medium">
            <span>سعة {{ $capacity }} أفراد</span>
            <button type="button" onclick="openNewSessionModal({{ $room->id }})"
                    class="text-[#4E8F35] font-extrabold hover:underline flex items-center gap-1 cursor-pointer">
                <span>+ بدء جلسة (F2)</span>
            </button>
        </div>
    @elseif($isPrivate)
        {{-- Private Room Occupied (1 deal locks the room) --}}
        <div class="mt-2 pt-2 border-t border-[#E5E2DC] text-xs space-y-1.5 cursor-pointer hover:bg-slate-50 p-1.5 rounded-xl transition-all"
             onclick="loadDealDetails({{ $primaryDeal->id }})">
            <div class="flex items-center justify-between">
                <span class="text-[#73777A] font-medium">العميل:</span>
                <div class="flex items-center gap-1">
                    <strong class="text-[#303334] font-extrabold">{{ $primaryDeal->customer->name ?? $primaryDeal->customer->full_name ?? 'عميل مباشر' }}</strong>
                    @if($primaryDeal->customer_id)
                    <button type="button" onclick="event.stopPropagation(); openCustomerProfile({{ $primaryDeal->customer_id }})"
                            title="عرض كارت وسجل العميل"
                            class="p-0.5 rounded bg-[#EBF4E8] text-[#4E8F35] hover:bg-[#DCE8D4] text-[10px]">
                        <svg class="size-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </button>
                    @endif
                </div>
            </div>
            <div class="flex items-center justify-between font-mono">
                <span class="text-[#73777A] font-medium font-sans">المدة:</span>
                <span class="text-[#303334] font-black active-deal-timer bg-[#F5F3EE] px-2 py-0.5 rounded border border-[#E5E2DC] text-[11px]" data-started="{{ $primaryDeal->started_at->toISOString() }}">00:00:00</span>
            </div>
            <div class="flex items-center justify-between font-mono">
                <span class="text-[#73777A] font-medium font-sans">الطلبات:</span>
                <span class="text-[#4E8F35] font-extrabold">{{ $primaryDeal->order ? $primaryDeal->order->items->count() : 0 }} أصناف</span>
            </div>
        </div>
    @else
        {{-- Shared Area with 1 or more deals --}}
        <div class="mt-2 pt-2 border-t border-[#E5E2DC] text-xs space-y-2">
            <div class="text-[11px] font-bold text-[#73777A] flex items-center justify-between">
                <span>العملاء بالجلسة ({{ $occupancy }}):</span>
                <span class="font-mono text-[10px] text-[#4E8F35] bg-[#EBF4E8] px-1.5 py-0.2 rounded">متبقي {{ $remaining }}</span>
            </div>
            <div class="space-y-1.5 max-h-36 overflow-y-auto pr-0.5">
                @foreach($activeDeals as $deal)
                <div class="p-2 rounded-xl bg-slate-50 hover:bg-[#EBF4E8] border border-slate-100 hover:border-[#DCE8D4] flex items-center justify-between cursor-pointer transition-all"
                     onclick="event.stopPropagation(); loadDealDetails({{ $deal->id }})"
                     title="اضغط لتحصيل أو إضافة طلبات لهذا العميل">
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="size-6 rounded-lg bg-[#4E8F35] text-white text-[10px] font-black flex items-center justify-center shrink-0">
                            {{ mb_substr($deal->customer->name ?? $deal->customer->full_name ?? 'ع', 0, 1) }}
                        </span>
                        <div class="min-w-0">
                            <p class="font-extrabold text-[11px] text-[#303334] truncate">{{ $deal->customer->name ?? $deal->customer->full_name ?? 'عميل مباشر' }}</p>
                            <p class="font-mono text-[10px] text-[#73777A] active-deal-timer" data-started="{{ $deal->started_at->toISOString() }}">00:00:00</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1">
                        @if($deal->customer_id)
                        <button type="button" onclick="event.stopPropagation(); openCustomerProfile({{ $deal->customer_id }})"
                                class="p-1 rounded text-slate-400 hover:text-[#4E8F35]">
                            <svg class="size-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </button>
                        @endif
                        <span class="text-[10px] font-black text-[#4E8F35] bg-white px-1.5 py-0.5 rounded border border-[#DCE8D4]">
                            حساب
                        </span>
                    </div>
                </div>
                @endforeach
            </div>

            @if($remaining > 0)
            <button type="button" onclick="event.stopPropagation(); openNewSessionModal({{ $room->id }})"
                    class="w-full py-1.5 px-2.5 rounded-xl bg-[#EBF4E8] hover:bg-[#DCE8D4] text-[#4E8F35] text-[11px] font-black flex items-center justify-center gap-1 transition-all cursor-pointer">
                <svg class="size-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 4.5v15m7.5-7.5h-15"/></svg>
                <span>+ إضافة عميل للجلسة المشتركة (متاح {{ $remaining }})</span>
            </button>
            @endif
        </div>
    @endif
</div>
@endforeach
</div>
 </section>


{{-- VIEW 2: Unpaid Closed Sessions (المديونيات المعلقة) --}}
<div class="hidden flex-1 overflow-y-auto p-3 space-y-2.5 sidebar-view-content" id="pos-unpaid-list">
    @forelse($unpaidClosedDeals as $deal)
    <div class="p-3.5 rounded-2xl border border-rose-200 bg-rose-50/50 hover:bg-rose-50 transition-all space-y-2 shadow-2xs">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="size-2.5 rounded-full bg-rose-600"></span>
                <strong class="text-xs font-extrabold text-slate-900">{{ $deal->customer->name ?? $deal->customer->full_name ?? 'عميل مباشر' }}</strong>
            </div>
            <span class="text-[10px] font-mono font-black px-2 py-0.5 rounded-md bg-white border border-rose-200 text-rose-700">
                #{{ $deal->deal_number }}
            </span>
        </div>

        <div class="text-xs text-slate-600 space-y-1 font-medium">
            <div class="flex justify-between">
                <span>المكان/الغرفة:</span>
                <span class="font-bold text-slate-900">{{ $deal->room ? $deal->room->name : 'المساحة العامة' }}</span>
            </div>
            <div class="flex justify-between">
                <span>تاريخ الانتهاء:</span>
                <span class="font-mono text-[11px] text-slate-500">{{ $deal->ended_at ? $deal->ended_at->format('Y-m-d h:i A') : '-' }}</span>
            </div>
            <div class="flex justify-between items-center pt-1 border-t border-rose-200/60">
                <span>المبلغ المتبقي:</span>
                <span class="font-mono font-black text-sm text-rose-700">{{ number_format($deal->order ? $deal->order->remaining_amount : 0, 2) }} ج.م</span>
            </div>
        </div>

        <div class="flex items-center justify-end gap-2 pt-1">
            <button type="button" onclick="openEditSessionModal({{ $deal->id }}, '{{ $deal->started_at->toISOString() }}', '{{ $deal->ended_at ? $deal->ended_at->toISOString() : '' }}', {{ $deal->room_id ?: 'null' }}, {{ $deal->applied_price ?: 0 }})"
                    class="px-2.5 py-1.5 rounded-xl bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 text-[11px] font-bold transition-all cursor-pointer">
                تعديل الجلسة
            </button>
            <button type="button" onclick="openSettleDebtModal({{ $deal->id }}, {{ $deal->order ? $deal->order->remaining_amount : 0 }}, '{{ addslashes($deal->customer->name ?? $deal->customer->full_name ?? 'عميل') }}')"
                    class="px-3.5 py-1.5 rounded-xl bg-[#4E8F35] hover:bg-[#3F742B] text-white text-xs font-black shadow-2xs transition-all cursor-pointer flex items-center gap-1.5">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg>
                <span>تسديد المديونية</span>
            </button>
        </div>
    </div>
    @empty
    <div class="py-12 text-center text-slate-400 text-xs font-medium">
        لا توجد مديونيات معلقة حالياً. ممتاز! 👍
    </div>
    @endforelse
</div>

{{-- VIEW 3: Paid Closed Sessions Today (المسددة اليوم) --}}
<div class="hidden flex-1 overflow-y-auto p-3 space-y-2.5 sidebar-view-content" id="pos-paid-list">
    @forelse($paidDealsToday as $deal)
    <div class="p-3.5 rounded-2xl border border-emerald-200 bg-emerald-50/30 hover:bg-emerald-50/60 transition-all space-y-2 shadow-2xs">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="size-2.5 rounded-full bg-emerald-600"></span>
                <strong class="text-xs font-extrabold text-slate-900">{{ $deal->customer->name ?? $deal->customer->full_name ?? 'عميل مباشر' }}</strong>
            </div>
            <span class="text-[10px] font-mono font-black px-2 py-0.5 rounded-md bg-white border border-emerald-200 text-emerald-700">
                مسدد بالكامل ✓
            </span>
        </div>

        <div class="text-xs text-slate-600 space-y-1 font-medium">
            <div class="flex justify-between">
                <span>الغرفة:</span>
                <span class="font-bold text-slate-900">{{ $deal->room ? $deal->room->name : 'المساحة العامة' }}</span>
            </div>
            <div class="flex justify-between items-center pt-1 border-t border-emerald-200/60">
                <span>الإجمالي:</span>
                <span class="font-mono font-black text-xs text-emerald-700">{{ number_format($deal->order ? $deal->order->total : 0, 2) }} ج.م</span>
            </div>
        </div>

        <div class="flex items-center justify-end gap-2 pt-1">
            <button type="button" onclick="openEditSessionModal({{ $deal->id }}, '{{ $deal->started_at->toISOString() }}', '{{ $deal->ended_at ? $deal->ended_at->toISOString() : '' }}', {{ $deal->room_id ?: 'null' }}, {{ $deal->applied_price ?: 0 }})"
                    class="px-2.5 py-1.5 rounded-xl bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 text-[11px] font-bold transition-all cursor-pointer">
                تعديل الجلسة
            </button>
            <button type="button" onclick="loadDealDetails({{ $deal->id }})"
                    class="px-3 py-1.5 rounded-xl bg-white border border-emerald-300 text-emerald-800 text-[11px] font-extrabold transition-all cursor-pointer">
                عرض الفاتورة
            </button>
        </div>
    </div>
    @empty
    <div class="py-12 text-center text-slate-400 text-xs font-medium">
        لا توجد جلسات مسددة مسجلة اليوم حتى الآن.
    </div>
    @endforelse
</div>

 {{-- ── COLUMN 2 (CENTER): قائمة البوفيه والمشروبات (Cafe & Products) ── --}}
 <section class="flex-1 bg-[#F8F7F4] flex flex-col overflow-hidden border-s border-[#E5E2DC]">
 {{-- Toolbar: Category Filters & Search --}}
 <div class="p-3.5 bg-white border-b border-[#E5E2DC] flex flex-wrap items-center justify-between gap-3 shrink-0 shadow-2xs">
 {{-- Categories Tabs --}}
 <div class="flex items-center gap-1.5 overflow-x-auto text-xs font-bold" id="product-category-tabs">
 <button type="button" onclick="filterProductsByCode('all', this)"
 class="prod-cat-tab px-3.5 py-1.5 rounded-xl bg-[#4E8F35] text-white font-extrabold shadow-xs transition-all cursor-pointer">
 الكل
 </button>
 <button type="button" onclick="filterProductsByCode('hot_drinks', this)"
 class="prod-cat-tab px-3.5 py-1.5 rounded-xl bg-[#F5F3EE] border border-[#E5E2DC] text-[#73777A] hover:text-[#303334] hover:bg-[#EBF4E8] font-bold transition-all cursor-pointer flex items-center gap-1.5">
 <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 8h1a4 4 0 1 1 0 8h-1"/><path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z"/><line x1="6" x2="6" y1="2" y2="4"/><line x1="10" x2="10" y1="2" y2="4"/><line x1="14" x2="14" y1="2" y2="4"/></svg>
 <span>مشروبات ساخنة</span>
 </button>
 <button type="button" onclick="filterProductsByCode('cold_drinks', this)"
 class="prod-cat-tab px-3.5 py-1.5 rounded-xl bg-[#F5F3EE] border border-[#E5E2DC] text-[#73777A] hover:text-[#303334] hover:bg-[#EBF4E8] font-bold transition-all cursor-pointer flex items-center gap-1.5">
 <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 22h8"/><path d="M12 11v11"/><path d="m19 3-2 8H7L5 3Z"/></svg>
 <span>مشروبات باردة</span>
 </button>
 <button type="button" onclick="filterProductsByCode('snacks', this)"
 class="prod-cat-tab px-3.5 py-1.5 rounded-xl bg-[#F5F3EE] border border-[#E5E2DC] text-[#73777A] hover:text-[#303334] hover:bg-[#EBF4E8] font-bold transition-all cursor-pointer flex items-center gap-1.5">
 <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><line x1="3" x2="21" y1="6" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
 <span>سناكس ومأكولات</span>
 </button>
 <button type="button" onclick="filterProductsByCode('services', this)"
 class="prod-cat-tab px-3.5 py-1.5 rounded-xl bg-[#F5F3EE] border border-[#E5E2DC] text-[#73777A] hover:text-[#303334] hover:bg-[#EBF4E8] font-bold transition-all cursor-pointer flex items-center gap-1.5">
 <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
 <span>طباعة وخدمات</span>
 </button>
 </div>

 {{-- Product Search --}}
 <div class="relative w-52">
 <input type="text" id="pos-product-search" placeholder="ابحث عن صنف في الكافيه..."
 class="w-full bg-[#F5F3EE] border border-[#E5E2DC] text-[#303334] text-xs rounded-xl px-3 py-1.5 pe-8 focus:border-[#4E8F35] focus:bg-white outline-none placeholder:text-[#73777A]">
 <span class="absolute end-2.5 top-2 text-[#73777A]">
 <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/></svg>
 </span>
 </div>
 </div>

 {{-- Products Grid --}}
 <div class="flex-1 overflow-y-auto p-4">
 <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3.5" id="pos-products-grid">
 @forelse($products as $prod)
 @php
 $catCode = $prod->category->code ?? 'other';
 $iconBg = 'bg-indigo-50 text-indigo-600';
 if ($catCode === 'cold_drinks') {
 $iconBg = 'bg-cyan-50 text-cyan-600';
 } elseif ($catCode === 'snacks') {
 $iconBg = 'bg-amber-50 text-amber-600';
 } elseif ($catCode === 'services') {
 $iconBg = 'bg-purple-50 text-purple-600';
 }
 @endphp
 <div class="product-item-card p-3.5 rounded-2xl bg-white hover:bg-[#F8F7F4] border border-[#E5E2DC] hover:border-[#4E8F35]/70 transition-all flex flex-col justify-between cursor-pointer group select-none shadow-xs hover:shadow-xs"
 data-product-id="{{ $prod->id }}"
 data-product-name="{{ $prod->name }}"
 data-product-category="{{ $catCode }}"
 data-product-price="{{ $prod->price }}"
 onclick="addProductToActiveSession({{ $prod->id }}, '{{ addslashes($prod->name) }}', {{ $prod->price }})">

 <div>
 <div class="w-10 h-10 rounded-xl {{ $iconBg }} flex items-center justify-center mb-2.5 font-bold shadow-2xs group-hover:scale-110 transition-transform">
 @if($catCode === 'cold_drinks')
 <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 22h8"/><path d="M12 11v11"/><path d="m19 3-2 8H7L5 3Z"/></svg>
 @elseif($catCode === 'snacks')
 <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><line x1="3" x2="21" y1="6" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
 @elseif($catCode === 'services')
 <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
 @else
 <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 8h1a4 4 0 1 1 0 8h-1"/><path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z"/><line x1="6" x2="6" y1="2" y2="4"/><line x1="10" x2="10" y1="2" y2="4"/><line x1="14" x2="14" y1="2" y2="4"/></svg>
 @endif
 </div>
 <h4 class="font-extrabold text-xs text-[#303334] group-hover:text-[#4E8F35] transition-colors leading-snug line-clamp-2">
 {{ $prod->name }}
 </h4>
 <span class="text-[10px] text-[#73777A] font-medium mt-0.5 block">
 {{ $prod->category->name ?? 'بوفيه' }}
 </span>
 </div>

 <div class="mt-3 pt-2.5 border-t border-[#E5E2DC] flex items-center justify-between">
 <span class="text-xs font-black text-[#4E8F35] font-mono">
 {{ number_format($prod->price, 2) }} <span class="text-[10px] font-normal text-[#73777A]">ج.م</span>
 </span>
 <span class="w-7 h-7 rounded-lg bg-[#EBF4E8] group-hover:bg-[#4E8F35] text-[#4E8F35] group-hover:text-white flex items-center justify-center text-sm font-black transition-all shadow-2xs">
 +
 </span>
 </div>
 </div>
 @empty
 <div class="col-span-full py-16 text-center text-slate-400 text-xs">
 لا توجد منتجات مسجلة في الكافيه.
 </div>
 @endforelse
 </div>
 </div>
 </section>

 {{-- ── COLUMN 3 (LEFT): فاتورة الحساب والدفع السريع (Fast Checkout) ── --}}
 <section class="w-96 bg-white border-s border-[#E5E2DC] flex flex-col shrink-0">

 {{-- 1. No Session Selected Placeholder --}}
 <div id="checkout-placeholder" class="flex-1 flex flex-col items-center justify-center p-8 text-center text-slate-500">
 <div class="w-20 h-20 rounded-3xl bg-[#EBF4E8] border border-[#DCE8D4] flex items-center justify-center text-[#4E8F35] mb-4 shadow-xs">
 <svg width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
 <rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>
 </svg>
 </div>
 <h4 class="text-base font-black text-slate-900 mb-1.5">لم يتم تحديد جلسة</h4>
 <p class="text-xs text-slate-500 leading-relaxed mb-6 max-w-xs font-medium">
 اختر أي غرفة مشغولة من القائمة اليمنى لعرض ومتابعة الحساب، أو انقر على الزر أدناه لبدء حساب جديد.
 </p>
 <button type="button" onclick="openNewSessionModal()"
 class="btn-primary inline-flex items-center gap-2 px-6 py-3 bg-[#4E8F35] hover:bg-[#3F742B] text-white rounded-xl text-xs font-black transition-all shadow-xs cursor-pointer">
 <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
 <line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/>
 </svg>
 <span>+ بدء جلسة جديدة الآن (F2)</span>
 </button>
 </div>

 {{-- 2. Active Session Checkout Panel --}}
 <div id="checkout-panel" class="hidden flex-1 flex flex-col overflow-hidden">
 {{-- Session Header Banner --}}
 <div class="p-4 bg-gradient-to-r from-[#EBF4E8]/60 via-white to-[#F8F7F4] border-b border-[#E5E2DC] flex items-center justify-between shrink-0">
 <div class="flex items-center gap-3">
 <div class="w-11 h-11 rounded-2xl bg-[#4E8F35] text-white font-black text-base flex items-center justify-center shadow-xs shrink-0" id="co-customer-avatar">
 ع
 </div>
 <div>
 <h4 class="font-black text-sm text-slate-900 leading-tight" id="co-customer-name">أحمد محمد</h4>
 <div class="flex items-center gap-2 text-xs text-slate-500 mt-1">
 <span class="text-[#4E8F35] font-extrabold bg-[#EBF4E8] px-2 py-0.5 rounded-md border border-[#DCE8D4]" id="co-room-name">قاعة A</span>
 <span>•</span>
 <span class="font-mono text-[#303334] font-black bg-[#F5F3EE] px-2 py-0.5 rounded border border-[#E5E2DC]" id="co-timer-display">00:00:00</span>
 </div>
 </div>
 </div>

 <button type="button" onclick="deselectSession()" title="إلغاء التحديد" class="w-8 h-8 rounded-xl bg-slate-100 text-slate-500 hover:text-slate-900 hover:bg-slate-200 flex items-center justify-center transition-all">
 <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
 </button>
 </div>

 {{-- Itemized Order List --}}
 <div class="flex-1 overflow-y-auto p-3.5 space-y-2.5" id="co-order-items-list">
 {{-- Dynamically filled via JS --}}
 </div>

 {{-- Summary & Totals --}}
 <div class="p-4 bg-slate-50 border-t border-slate-200 shrink-0 space-y-2.5">
 <div class="flex items-center justify-between text-xs text-slate-600 font-semibold">
 <span>حساب الوقت والمساحة:</span>
 <span class="font-mono font-extrabold text-slate-900" id="co-time-subtotal">0.00 ج.م</span>
 </div>
 <div class="flex items-center justify-between text-xs text-slate-600 font-semibold">
 <span>مشروبات وطلبات البوفيه:</span>
 <span class="font-mono font-extrabold text-slate-900" id="co-products-subtotal">0.00 ج.م</span>
 </div>
 <div class="flex items-center justify-between text-xs text-slate-600 font-semibold">
 <span>الخصم (ج.م):</span>
 <input type="number" id="co-discount-input" value="0" min="0" onchange="calculateTotal()"
 class="w-24 bg-white border border-slate-300 text-end px-2.5 py-1 rounded-lg text-xs text-rose-600 font-mono font-black outline-none focus:border-rose-400">
 </div>

 <div class="pt-3 border-t border-slate-200 flex items-center justify-between">
 <span class="font-black text-sm text-slate-900">المبلغ المطلوب سداده:</span>
 <span class="font-black text-2xl text-[#4E8F35] font-mono" id="co-total-amount">
 0.00 <span class="text-xs font-normal text-slate-500">ج.م</span>
                        </span>
                    </div>
                </div>

                {{-- Payment Methods & Fast Change --}}
                <div class="p-4 bg-white border-t border-slate-200 shrink-0 space-y-3">
                    {{-- Payment Method Buttons --}}
                    <div class="grid grid-cols-3 gap-2 text-center text-xs font-extrabold">
                        <button type="button" onclick="selectPaymentMethod('cash')" id="btn-pay-cash"
                                class="pay-method-btn py-2.5 rounded-xl border-2 border-[#4E8F35] bg-[#EBF4E8] text-[#4E8F35] transition-all cursor-pointer flex items-center justify-center gap-1.5">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/></svg>
                            <span>كاش نقدي</span>
                        </button>
                        <button type="button" onclick="selectPaymentMethod('instapay')" id="btn-pay-instapay"
                                class="pay-method-btn py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-600 hover:text-slate-900 transition-all cursor-pointer flex items-center justify-center gap-1.5">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect width="14" height="20" x="5" y="2" rx="2" ry="2"/><line x1="12" x2="12.01" y1="18" y2="18"/></svg>
                            <span>إنستاباي</span>
                        </button>
                        <button type="button" onclick="selectPaymentMethod('wallet')" id="btn-pay-wallet"
                                class="pay-method-btn py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-600 hover:text-slate-900 transition-all cursor-pointer flex items-center justify-center gap-1.5">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/><path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/></svg>
                            <span>محفظة</span>
                        </button>
                    </div>

                    {{-- Fast Change Calculator (كاش فقط) --}}
                    <div id="cash-change-box" class="space-y-2">
                        <div class="flex items-center gap-2 text-xs">
                            <span class="text-slate-500 text-[11px] font-bold">المستلم:</span>
                            <div class="grid grid-cols-4 gap-1.5 flex-1">
                                <button type="button" onclick="setReceivedCash(50)" class="py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-mono font-bold">50</button>
                                <button type="button" onclick="setReceivedCash(100)" class="py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-mono font-bold">100</button>
                                <button type="button" onclick="setReceivedCash(200)" class="py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-mono font-bold">200</button>
                                <button type="button" onclick="setReceivedCash(500)" class="py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-mono font-bold">500</button>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-xs px-3 py-2 bg-[#EBF4E8] rounded-xl border border-[#DCE8D4]">
                            <span class="text-[#4E8F35] font-bold">الباقي للعميل:</span>
                            <span class="font-black font-mono text-[#4E8F35]" id="co-change-amount">0.00 ج.م</span>
                        </div>
                    </div>

                    {{-- Big Close & Pay Button --}}
                    <button type="button" onclick="executeCheckoutAndPay()" id="btn-submit-checkout"
                            class="w-full py-3.5 bg-[#4E8F35] hover:bg-[#3F742B] text-white font-black text-sm rounded-xl transition-all shadow-xs flex items-center justify-center gap-2 cursor-pointer">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        <span>إنهاء الجلسة والتحصيل (F9)</span>
                    </button>
                </div>
            </div>

        </section>

    </main>

    {{-- ════════════════════════════════════════════════════════ --}}
    {{-- NEW: BOTTOM PANEL (طلبات الموبايل) --}}
    {{-- ════════════════════════════════════════════════════════ --}}
    <footer id="mobile-orders-bar" class="h-44 bg-white border-t border-[#E5E2DC] flex flex-col shrink-0 z-10 shadow-[0_-2px_10px_rgba(0,0,0,0.03)]">
        <div class="px-4 py-2 bg-[#F8F7F4] border-b border-[#E5E2DC] flex items-center justify-between shrink-0">
            <div class="flex items-center gap-2">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                </span>
                <h3 class="font-black text-sm text-[#303334]">طلبات الكافيه الواردة من تطبيق الموبايل (Live)</h3>
                <span id="portal-orders-badge" class="{{ count($pendingPortalOrders) > 0 ? '' : 'hidden' }} px-2 py-0.5 rounded-full text-[11px] font-black bg-[#4E8F35] text-white">
                    {{ count($pendingPortalOrders) }}
                </span>
            </div>
            <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                <span class="hidden sm:inline text-[11px] text-slate-400">تحديث تلقائي وفوري</span>
                <button type="button" onclick="pollPortalOrders()" class="p-1 rounded-lg hover:bg-slate-200 text-slate-600 transition cursor-pointer" title="تحديث يدوي">
                    <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                </button>
            </div>
        </div>
 <div id="portal-orders-bottom-list" class="flex-1 overflow-x-auto p-3 flex gap-3 bg-[#f8fafc] items-start">
 @forelse($pendingPortalOrders as $ord)
 <div class="p-3 rounded-xl border border-[#E5E2DC] bg-white hover:border-[#4E8F35]/50 transition shadow-sm flex flex-col gap-2 min-w-[320px] shrink-0" id="cashier-order-{{ $ord->id }}">
 <div class="flex items-start justify-between">
 <div>
 <span class="font-mono text-xs font-black text-[#4E8F35] bg-[#EBF4E8] px-2 py-0.5 rounded">{{ $ord->order_number }}</span>
 <span class="font-extrabold text-slate-900 text-sm mx-1">{{ $ord->customer ? $ord->customer->full_name : 'عميل' }}</span>
 </div>
 <span class="px-2 py-0.5 rounded-md text-[10px] font-black {{ $ord->fulfillment_status === 'preparing' ? 'bg-[#EBF4E8] text-[#4E8F35]' : 'bg-[#F5F3EE] text-[#303334]' }}">
 {{ $ord->fulfillment_status === 'preparing' ? 'جاري التحضير' : 'جديد ⏳' }}
 </span>
 </div>

 <div class="text-[11px] text-slate-700 flex items-center gap-1.5">
 <span class="font-bold text-slate-500">الغرفة:</span>
 <span class="font-extrabold text-slate-900 bg-slate-100 px-1.5 py-0.5 rounded">{{ $ord->table_or_room_name ?: 'المساحة العامة' }}</span>
 </div>

 <div class="p-2 rounded-lg bg-slate-50 border border-slate-100 space-y-1 overflow-y-auto max-h-16">
 @foreach($ord->items as $it)
 <div class="flex justify-between text-xs">
 <span class="font-bold text-slate-800">{{ $it->name }} × {{ $it->quantity }}</span>
 <span class="font-mono font-bold text-slate-600">{{ number_format($it->total, 2) }} ج.م</span>
 </div>
 @endforeach
 @if(!empty($ord->customer_notes))
 <div class="text-[11px] text-amber-800 pt-1 border-t border-slate-100">
 <strong>ملاحظات:</strong> {{ $ord->customer_notes }}
 </div>
 @endif
 </div>

 <div class="flex items-center justify-between pt-1">
 <div class="text-[11px] font-bold text-slate-800">
 الإجمالي: <span class="font-mono font-black text-[#4E8F35] text-xs">{{ number_format($ord->total, 2) }} ج.م</span>
 </div>
 <div class="flex items-center gap-1.5">
 @if($ord->fulfillment_status !== 'preparing')
 <button type="button" onclick="updateOrderStatus({{ $ord->id }}, 'preparing')"
 class="px-2 py-1 rounded-lg bg-[#EBF4E8] hover:bg-[#DCE8D4] text-[#4E8F35] font-bold text-[10px] cursor-pointer border border-[#DCE8D4]">
 تحضير 
 </button>
 @endif
 <button type="button" onclick="updateOrderStatus({{ $ord->id }}, 'delivered')"
 class="px-2.5 py-1 rounded-lg bg-[#4E8F35] hover:bg-[#3F742B] text-white font-bold text-[10px] cursor-pointer shadow-xs">
 تم التسليم
 </button>
 <button type="button" onclick="updateOrderStatus({{ $ord->id }}, 'cancelled')"
 class="px-2 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold text-[10px] cursor-pointer">
 إلغاء
 </button>
 </div>
 </div>
 </div>
 @empty
 <div class="w-full h-full flex items-center justify-center text-slate-400 text-xs">
 لا توجد طلبات جديدة واردة من الموبايل حالياً.
 </div>
 @endforelse
 </div>
 </footer>

 {{-- ════════════════════════════════════════════════════════ --}}
 {{-- 3. MODALS (النوافذ المنبثقة بالستايل الفاتح النظيف) --}}
 {{-- ════════════════════════════════════════════════════════ --}}

 {{-- Modal 1: جلسة جديدة (New Session) --}}
 <div id="modal-new-session" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl max-w-lg w-full p-6 shadow-2xl text-slate-800">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <div>
                <h3 class="font-extrabold text-base text-slate-900">فتح جلسة عمل جديدة (F2)</h3>
                <p class="text-xs text-slate-400">تسكين عميل في مساحة عمل أو قاعة خاصة</p>
            </div>
        </div>
    </div>
</div>

{{-- Modal: تعديل بيانات الجلسة (Edit Session Modal) --}}
<div id="modal-edit-session" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white border border-[#E5E2DC] rounded-3xl max-w-lg w-full p-6 shadow-2xl text-slate-800">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <div>
                <h3 class="font-black text-base text-slate-900">تعديل بيانات وتوقيت وسعر الجلسة</h3>
                <p class="text-xs text-slate-400">تعديل توقيت البدء والانتهاء، الغرفة، والسعر</p>
            </div>
            <button type="button" onclick="closeModal('modal-edit-session')" class="size-8 rounded-lg bg-slate-100 text-slate-500 hover:text-slate-900 flex items-center justify-center font-bold transition-all">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="form-edit-session" onsubmit="submitEditSession(event)">
            <input type="hidden" id="es-deal-id">
            <div class="space-y-4 text-xs">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">وقت البدء *</label>
                        <input type="datetime-local" id="es-started-at" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-mono font-bold focus:border-[#4E8F35] outline-none">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">وقت الانتهاء</label>
                        <input type="datetime-local" id="es-ended-at" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-mono font-bold focus:border-[#4E8F35] outline-none">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">الغرفة / المساحة</label>
                    <select id="es-room-id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:border-[#4E8F35] outline-none">
                        <option value="">بدون تحديد غرفة (مساحة عامة)</option>
                        @foreach($rooms as $r)
                        <option value="{{ $r->id }}">{{ $r->name }} ({{ $r->type_label }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">السعر المطبق للجلسة (ج.م)</label>
                    <input type="number" step="0.5" id="es-applied-price" placeholder="أدخل السعر اليدوي للجلسة..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-mono font-bold focus:border-[#4E8F35] outline-none">
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                    <button type="button" onclick="cancelCurrentSession()" class="px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl font-bold transition-all border border-rose-200 cursor-pointer">
                        إلغاء الجلسة نهائياً
                    </button>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="closeModal('modal-edit-session')" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition-all cursor-pointer">إلغاء</button>
                        <button type="submit" class="px-5 py-2.5 bg-[#4E8F35] hover:bg-[#3F742B] text-white font-black rounded-xl shadow-xs transition-all cursor-pointer">حفظ التعديلات</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal: تنبيه الزيادة في الدفع (Overpayment Tip vs Debt Credit Prompt) --}}
<div id="modal-overpayment-prompt" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white border border-amber-300 rounded-3xl max-w-md w-full p-6 shadow-2xl text-slate-800">
        <div class="text-center mb-4">
            <div class="size-14 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center mx-auto mb-3 text-2xl font-bold">
                ⚠️
            </div>
            <h3 class="font-black text-lg text-slate-900">المبلغ المدفوع أزيد من المستحق</h3>
            <p class="text-xs text-slate-500 mt-1">المبلغ المطلوب: <strong id="op-required-amount" class="font-mono text-slate-900">0.00</strong> ج.م | المدفوع: <strong id="op-paid-amount" class="font-mono text-emerald-600">0.00</strong> ج.م</p>
            <div class="my-3 p-3 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs font-bold">
                الفارق الزائد هو: <span class="font-mono text-base font-black text-amber-700" id="op-diff-amount">0.00</span> ج.م
            </div>
            <p class="text-xs font-bold text-slate-700 mb-3">يرجى تحديد طريقة التعامل مع المبلغ الزائد:</p>
        </div>

        <div class="space-y-2.5 text-xs font-bold">
            <button type="button" onclick="confirmOverpaymentType('tip')" class="w-full p-3.5 rounded-2xl bg-[#EBF4E8] hover:bg-[#DCE8D4] border border-[#DCE8D4] text-[#303334] flex items-center justify-between transition-all cursor-pointer">
                <div class="flex items-center gap-2.5">
                    <span class="text-lg">🎁</span>
                    <div class="text-right">
                        <span class="font-black block text-sm text-[#4E8F35]">إكرامية / تبس (Tip)</span>
                        <span class="text-[11px] text-slate-500">تسجيل المبلغ الزائد كإكرامية للموظف/المكان</span>
                    </div>
                </div>
                <span class="text-xs font-black text-[#4E8F35] bg-white px-2 py-1 rounded-lg">اختيار</span>
            </button>

            <button type="button" onclick="confirmOverpaymentType('debt_credit')" class="w-full p-3.5 rounded-2xl bg-blue-50 hover:bg-blue-100 border border-blue-200 text-slate-900 flex items-center justify-between transition-all cursor-pointer">
                <div class="flex items-center gap-2.5">
                    <span class="text-lg">💳</span>
                    <div class="text-right">
                        <span class="font-black block text-sm text-blue-700">سداد مديونية / رصيد عميل</span>
                        <span class="text-[11px] text-slate-500">إضافة الزيادة لرصيد العميل لاستخدامها لاحقاً</span>
                    </div>
                </div>
                <span class="text-xs font-black text-blue-700 bg-white px-2 py-1 rounded-lg">اختيار</span>
            </button>
        </div>
    </div>
</div>

{{-- Modal: تسديد المديونية (Settle Debt Modal) --}}
<div id="modal-settle-debt" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white border border-[#E5E2DC] rounded-3xl max-w-md w-full p-6 shadow-2xl text-slate-800">
        <div class="flex items-center justify-between mb-3 pb-3 border-b border-slate-100">
            <div>
                <h3 class="font-black text-base text-slate-900">تسديد المديونية السابقة</h3>
                <p class="text-xs text-slate-400" id="sd-customer-name">العميل: --</p>
            </div>
            <button type="button" onclick="closeModal('modal-settle-debt')" class="size-8 rounded-lg bg-slate-100 text-slate-500 hover:text-slate-900 flex items-center justify-center font-bold transition-all">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="form-settle-debt" onsubmit="submitSettleDebt(event)">
            <input type="hidden" id="sd-deal-id">
            <div class="space-y-3.5 text-xs">
                <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 flex items-center justify-between">
                    <span class="font-bold text-rose-900">إجمالي المديونية المطلوبة:</span>
                    <span class="font-mono font-black text-lg text-rose-700" id="sd-remaining-display">0.00 ج.م</span>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">المبلغ المدفوع الآن (ج.م) *</label>
                    <input type="number" step="0.5" id="sd-paid-amount" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm font-mono font-black focus:border-[#4E8F35] outline-none">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">طريقة الدفع *</label>
                    <select id="sd-payment-method" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-bold focus:border-[#4E8F35] outline-none">
                        <option value="cash">كاش نقدي</option>
                        <option value="instapay">إنستاباي</option>
                        <option value="wallet">محفظة إلكترونية</option>
                        <option value="card">كارت / ميزا / مدى</option>
                    </select>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button" onclick="closeModal('modal-settle-debt')" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition-all">إلغاء</button>
                    <button type="submit" class="px-6 py-2.5 bg-[#4E8F35] hover:bg-[#3F742B] text-white font-black rounded-xl shadow-xs transition-all cursor-pointer">تأكيد وتسديد المديونية</button>
                </div>
            </div>
        </form>
    </div>
</div>
            </div>
            <button type="button" onclick="closeModal('modal-new-session')" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 hover:text-slate-800 flex items-center justify-center font-bold transition-all">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="form-new-session" onsubmit="submitNewSession(event)">
            <div class="space-y-4">
                {{-- Customer Selection --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="text-xs font-bold text-slate-700">العميل *</label>
                        <button type="button" onclick="openAddCustomerModal()" class="text-xs font-bold text-[#4E8F35] hover:underline">+ عميل جديد</button>
                    </div>
                    <div class="relative">
                        <input type="text" id="ns-customer-search" autocomplete="off" placeholder="ابحث باسم العميل أو رقم الهاتف..."
                            oninput="searchCustomers(this.value)"
                            class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3.5 py-2.5 text-xs focus:border-[#4E8F35] focus:bg-white outline-none pr-9">
                        <svg class="absolute top-1/2 -translate-y-1/2 right-3 text-slate-400 pointer-events-none" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        <div id="ns-customer-suggestions" class="hidden absolute top-full left-0 right-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-xl z-50 max-h-48 overflow-y-auto"></div>
                    </div>
                    <input type="hidden" id="ns-customer-id" required>
                    <div id="ns-customer-selected" class="hidden mt-2 flex items-center gap-2 p-2 bg-[#EBF4E8] rounded-xl border border-[#DCE8D4]">
                        <span class="size-7 rounded-lg bg-[#4E8F35] text-white text-xs font-black flex items-center justify-center" id="ns-sel-initials">ع</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-slate-900 truncate" id="ns-sel-name"></p>
                            <p class="text-[10px] text-slate-500 font-mono" id="ns-sel-phone"></p>
                        </div>
                        <button type="button" onclick="clearCustomerSelection()" class="text-slate-400 hover:text-rose-500 p-1">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Room Selection --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">الغرفة / المساحة</label>
                    <select id="ns-room-id" onchange="onRoomSelectedInModal(this)" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3.5 py-2.5 text-xs focus:border-[#4E8F35] focus:bg-white outline-none">
                        <option value="">بدون تحديد غرفة (مساحة عامة)</option>
                        @foreach($rooms as $r)
                        @php
                            $activeCount = $r->activeDeals->count();
                            $remaining = max(0, $r->capacity - $activeCount);
                            $isPrivate = in_array($r->type, ['private', 'meeting']);
                            $isFull = $isPrivate ? ($activeCount > 0) : ($remaining <= 0);
                        @endphp
                        <option value="{{ $r->id }}" data-type="{{ $r->type }}" data-capacity="{{ $r->capacity }}" data-active-count="{{ $activeCount }}" data-remaining="{{ $remaining }}" data-is-private="{{ $isPrivate ? '1' : '0' }}" {{ $isFull ? 'disabled' : '' }}>
                            {{ $r->name }} ({{ $r->type_label }}) - {{ $isPrivate ? ($isFull ? 'محجوزة بالكامل' : 'متاحة للحجز') : "المتبقي: {$remaining} مقعد" }}
                        </option>
                        @endforeach
                    </select>
                    <p id="ns-room-notice" class="hidden text-[11px] text-amber-700 bg-amber-50 border border-amber-200 rounded-lg p-2 mt-1 font-medium"></p>
                </div>

                {{-- Workspace Type Selection --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">باقة ونوع المساحة *</label>
                    <select id="ns-workspace-type-id" required onchange="onWorkspaceTypeSelected(this)" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3.5 py-2.5 text-xs focus:border-[#4E8F35] focus:bg-white outline-none">
                        @foreach($workspaceTypes as $wt)
                        @php
                            $isPriv = in_array($wt->code, ['private', 'meeting']);
                        @endphp
                        <option value="{{ $wt->id }}" data-code="{{ $wt->code }}" data-is-private="{{ $isPriv ? '1' : '0' }}">
                            {{ $wt->name }} ({{ $isPriv ? 'غرفة خاصة Private - حجز كامل الغرفة' : 'مساحة مشتركة Shared - محاسبة بالمدة' }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">ملاحظات إضافية</label>
                    <input type="text" id="ns-notes" placeholder="مثال: يفضل الجلوس بجوار النافذة..."
                        class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3.5 py-2 text-xs focus:border-[#4E8F35] focus:bg-white outline-none">
                </div>

                {{-- Modal Action Buttons --}}
                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                    <button type="button" onclick="closeModal('modal-new-session')" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all">
                        إلغاء
                    </button>
                    <button type="submit" class="btn-primary px-6 py-2.5 bg-[#4E8F35] hover:bg-[#3F742B] text-white rounded-xl text-xs font-black shadow-md flex items-center gap-2 transition-all cursor-pointer">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        بدء الجلسة الآن
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

 {{-- Modal 2: تسجيل عميل جديد (Quick Add Customer) --}}
 <div id="modal-add-customer" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
 <div class="bg-white border border-slate-200 rounded-3xl max-w-md w-full p-6 shadow-2xl text-slate-800">
 <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
 <h3 class="font-extrabold text-base text-slate-900">تسجيل عميل جديد (F3)</h3>
 <button type="button" onclick="closeModal('modal-add-customer')" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 hover:text-slate-800 flex items-center justify-center font-bold transition-all">
 <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
 </button>
 </div>

 <form id="form-add-customer" onsubmit="submitQuickCustomer(event)">
 <div class="space-y-3.5">
 <div>
 <label class="block text-xs font-bold text-slate-700 mb-1.5">اسم العميل *</label>
 <input type="text" id="qc-name" required placeholder="مثال: يوسف أحمد"
 class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3.5 py-2.5 text-xs focus:border-[#4E8F35] focus:bg-white outline-none">
 </div>
 <div>
 <label class="block text-xs font-bold text-slate-700 mb-1.5">رقم الهاتف *</label>
 <input type="text" id="qc-phone" required pattern="^(010|011|012|015)[0-9]{8}$" title="رقم هاتف مصري صحيح" placeholder="مثال: 01012345678" dir="ltr"
 class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3.5 py-2.5 text-xs focus:border-[#4E8F35] focus:bg-white outline-none text-start">
 </div>
 <div>
  <label class="block text-xs font-bold text-slate-700 mb-1.5">التصنيف <span class="text-rose-500">*</span></label>
  <div class="grid grid-cols-3 gap-2">
  @foreach(['طالب','موظف','فريلانسر','صاحب عمل','كاتب / مبدع','أخرى'] as $cl)
  <label class="flex items-center justify-center cursor-pointer p-2 rounded-xl border border-slate-200 hover:border-[#4E8F35] hover:bg-[#EBF4E8] transition text-xs font-bold text-slate-700 has-[:checked]:border-[#4E8F35] has-[:checked]:bg-[#EBF4E8] has-[:checked]:text-[#4E8F35]">
  <input type="radio" name="qc-classification" value="{{ $cl }}" required class="sr-only">
  {{ $cl }}
  </label>
  @endforeach
  </div>
  </div>
  <div>
  <label class="block text-xs font-bold text-slate-700 mb-1.5">عرفنا منين؟ <span class="text-rose-500">*</span></label>
  <div class="grid grid-cols-3 gap-2">
  @foreach(['فيس بوك','تيك توك','جوجل','من صاحب','إعلان','أخرى'] as $src)
  <label class="flex items-center justify-center cursor-pointer p-2 rounded-xl border border-slate-200 hover:border-[#4E8F35] hover:bg-[#EBF4E8] transition text-xs font-bold text-slate-700 has-[:checked]:border-[#4E8F35] has-[:checked]:bg-[#EBF4E8] has-[:checked]:text-[#4E8F35]">
  <input type="radio" name="qc-source" value="{{ $src }}" required class="sr-only">
  {{ $src }}
  </label>
  @endforeach
  </div>
  </div>
  <div>
  <label class="block text-xs font-bold text-slate-700 mb-1.5">البريد الإلكتروني <span class="text-slate-400 font-normal">(اختياري)</span></label>
  <input type="email" id="qc-email" placeholder="client@example.com" dir="ltr"
  class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3.5 py-2 text-xs focus:border-[#4E8F35] focus:bg-white outline-none text-start">
</div>

 <div class="flex items-center justify-end gap-2.5 pt-2 border-t border-slate-100">
 <button type="button" onclick="closeModal('modal-add-customer')" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold">إلغاء</button>
 <button type="submit" class="btn-primary px-5 py-2 bg-[#4E8F35] hover:bg-[#3F742B] text-white rounded-xl text-xs font-black shadow-xs">
 حفظ العميل
 </button>
 </div>
 </div>
 </form>
 </div>
 </div>

 {{-- Modal 3: الفاتورة الحرارية للطباعة (Printable Receipt Modal) --}}
 <div id="modal-receipt" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
 <div class="bg-white text-slate-900 rounded-3xl max-w-sm w-full p-6 shadow-2xl relative border border-slate-200">
 <div id="receipt-printable-area" class="text-center font-sans text-xs space-y-2 leading-tight">
 <div class="border-b border-dashed border-slate-300 pb-3 mb-2">
 <h2 class="font-black text-base text-slate-900">DT-SYSTEM HUB</h2>
 <p class="text-[11px] text-slate-500 mt-0.5">مساحة عمل متكاملة وقاعات دراسية</p>
 <p class="text-[10px] text-slate-400 mt-1 font-mono" id="rec-datetime">2026-09-11 10:30 PM</p>
 </div>

 <div class="text-start space-y-1 text-[11px] border-b border-dashed border-slate-300 pb-2">
 <div class="flex justify-between"><span>العميل:</span><strong id="rec-customer">-</strong></div>
 <div class="flex justify-between"><span>الغرفة:</span><strong id="rec-room">-</strong></div>
 <div class="flex justify-between"><span>المدة المحسوبة:</span><strong id="rec-duration">-</strong></div>
 <div class="flex justify-between"><span>طريقة الدفع:</span><strong id="rec-method">كاش</strong></div>
 </div>

 {{-- Receipt Items --}}
 <div class="text-start py-2 border-b border-dashed border-slate-300 space-y-1 text-[11px]" id="rec-items-list">
 {{-- items --}}
 </div>

 <div class="text-start space-y-1 text-xs pt-1 font-bold">
 <div class="flex justify-between"><span>المجموع الفرعي:</span><span id="rec-subtotal">0 ج.م</span></div>
 <div class="flex justify-between text-rose-600"><span>الخصم:</span><span id="rec-discount">0 ج.م</span></div>
 <div class="flex justify-between text-sm font-black border-t border-slate-300 pt-1 text-slate-900">
 <span>الإجمالي المدفوع:</span>
 <span id="rec-total" class="text-emerald-700">0 ج.م</span>
 </div>
 </div>

 <div class="pt-4 text-center text-[10px] text-slate-400">
 <p>شكراً لزيارتكم DT-SYSTEM نتمنى لكم وقتاً مثمراً!</p>
 <p class="font-mono mt-1">pos.dt-system.com</p>
 </div>
 </div>

 <div class="mt-4 pt-3 border-t border-slate-200 flex items-center justify-end gap-2 no-print">
 <button type="button" onclick="closeModal('modal-receipt')" class="px-3 py-1.5 rounded-xl bg-slate-100 text-slate-600 text-xs font-bold">إلغاء</button>
 <button type="button" onclick="window.print()" class="btn-primary px-4 py-1.5 rounded-xl bg-[#4E8F35] hover:bg-[#3F742B] text-white text-xs font-bold flex items-center gap-1 shadow-xs">
 <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
 <span>طباعة الفاتورة</span>
 </button>
 </div>
 </div>
 

{{-- MODAL: CUSTOMER PROFILE & HISTORY CARD --}}
<div id="modal-customer-profile" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full p-6 border border-slate-200 animate-scale-up max-h-[90vh] flex flex-col">
        {{-- Header --}}
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="size-11 rounded-2xl bg-[#EBF4E8] text-[#4E8F35] font-black text-lg flex items-center justify-center" id="cp-avatar">
                    ع
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="font-extrabold text-base text-slate-900" id="cp-name">--</h3>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#EBF4E8] text-[#3B6E28]" id="cp-status-badge">نشط</span>
                    </div>
                    <div class="flex items-center gap-3 text-xs text-slate-500 font-mono mt-0.5">
                        <span id="cp-phone">--</span>
                        <span id="cp-email" class="font-sans text-slate-400"></span>
                    </div>
                </div>
            </div>
            <button type="button" onclick="closeModal('modal-customer-profile')" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-xl hover:bg-slate-100">
                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Summary Stats & Current Presence --}}
        <div class="grid grid-cols-3 gap-3 my-3">
            <div class="p-3 bg-slate-50 border border-slate-100 rounded-2xl text-center">
                <span class="text-[11px] text-slate-500 font-bold block">إجمالي الإنفاق</span>
                <span class="text-sm font-black text-[#4E8F35] font-mono mt-0.5 block" id="cp-total-spent">0.00 ج.م</span>
            </div>
            <div class="p-3 bg-slate-50 border border-slate-100 rounded-2xl text-center">
                <span class="text-[11px] text-slate-500 font-bold block">عدد الجلسات / الزيارات</span>
                <span class="text-sm font-black text-slate-800 font-mono mt-0.5 block" id="cp-visits-count">0</span>
            </div>
            <div class="p-3 bg-slate-50 border border-slate-100 rounded-2xl text-center">
                <span class="text-[11px] text-slate-500 font-bold block">الجلسة الحالية</span>
                <span class="text-xs font-bold text-slate-700 mt-0.5 block truncate" id="cp-current-presence">غير متواجد حالياً</span>
            </div>
        </div>

        {{-- Notes & Preferences Section --}}
        <div class="p-3 bg-amber-50/50 border border-amber-200/70 rounded-2xl mb-3">
            <div class="flex items-center justify-between mb-1.5">
                <label class="text-xs font-bold text-amber-900 flex items-center gap-1.5">
                    <svg class="size-3.5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    <span>ملاحظات وتفضيلات العميل الخاصة (تظهر للكاشير)</span>
                </label>
                <button type="button" onclick="saveCustomerNotesAction()" id="btn-save-notes"
                    class="px-3 py-1 bg-[#4E8F35] hover:bg-[#3F742B] text-white text-[11px] font-bold rounded-lg transition shadow-2xs">
                    حفظ الملاحظات
                </button>
            </div>
            <textarea id="cp-notes-input" rows="2" placeholder="اكتب هنا تفضيلات العميل، مثلاً: يفضل قهوة مضبوط بدون سكر، عميل VIP، مكان الجلوس المفضل..."
                class="w-full bg-white border border-amber-200 rounded-xl px-3 py-1.5 text-xs text-slate-800 focus:outline-none focus:border-[#4E8F35]"></textarea>
            <span id="cp-notes-feedback" class="text-[10px] text-[#4E8F35] font-bold hidden">تم حفظ الملاحظات بنجاح.</span>
        </div>

        {{-- History Sub-Tabs --}}
        <div class="flex items-center gap-1 p-1 bg-slate-100 rounded-xl text-xs font-bold mb-2.5">
            <button type="button" onclick="switchCustomerTab('orders')" id="cp-tab-orders"
                class="cp-tab flex-1 py-1 rounded-lg text-center bg-[#4E8F35] text-white font-bold transition">
                سجل الطلبات والمشروبات
            </button>
            <button type="button" onclick="switchCustomerTab('deals')" id="cp-tab-deals"
                class="cp-tab flex-1 py-1 rounded-lg text-center text-slate-600 hover:text-slate-900 font-bold transition">
                سجل الجلسات
            </button>
            <button type="button" onclick="switchCustomerTab('bookings')" id="cp-tab-bookings"
                class="cp-tab flex-1 py-1 rounded-lg text-center text-slate-600 hover:text-slate-900 font-bold transition">
                سجل الحجوزات
            </button>
        </div>

        {{-- Tab Contents --}}
        <div class="flex-1 overflow-y-auto space-y-2 pr-1" id="cp-tab-content">
            <div class="py-8 text-center text-slate-400 text-xs" id="cp-loading">جاري تحميل سجل العميل...</div>
        </div>
    </div>
</div>

{{-- LIVE PUSH TOAST NOTIFICATION CONTAINER (طلبات المشروبات المباشرة) --}}
<div id="live-order-toast" class="fixed bottom-6 start-6 z-50 max-w-sm w-full bg-white border border-[#4E8F35] rounded-2xl shadow-2xl p-4 transition-all duration-300 transform translate-y-20 opacity-0 pointer-events-none flex flex-col gap-2">
    <div class="flex items-start justify-between">
        <div class="flex items-center gap-2">
            <span class="size-8 rounded-xl bg-[#EBF4E8] text-[#4E8F35] flex items-center justify-center font-bold text-base">
                🔔
            </span>
            <div>
                <h4 class="font-extrabold text-xs text-slate-900">طلب مشروب جديد من الموبايل!</h4>
                <p class="text-[11px] text-slate-500" id="toast-customer-info">عميل في ميتنج روم</p>
            </div>
        </div>
        <button type="button" onclick="dismissLiveToast()" class="text-slate-400 hover:text-slate-600">
            <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
        </button>
    </div>
    <div class="text-xs font-bold text-[#4E8F35] bg-[#EBF4E8]/60 p-2 rounded-xl" id="toast-items-summary">
        2 قهوة تركي + 1 مياه معدنية
    </div>
    <div class="flex items-center justify-end gap-2 pt-1">
        <button type="button" onclick="dismissLiveToast()" class="px-2.5 py-1 text-[11px] text-slate-500 hover:text-slate-700 font-bold">
            تجاهل
        </button>
        <button type="button" onclick="document.getElementById('mobile-orders-bar').scrollIntoView({behavior: 'smooth'}); dismissLiveToast();"
            class="px-3 py-1 bg-[#4E8F35] hover:bg-[#3F742B] text-white text-[11px] font-bold rounded-lg transition shadow-2xs">
            عرض الطلبات وتجهيزها
        </button>
    </div>
</div>

 {{-- ════════════════════════════════════════════════════════ --}}
 {{-- 4. JAVASCRIPT STATE ENGINE & WORKFLOW --}}
 {{-- ════════════════════════════════════════════════════════ --}}
 <script>
 // ── State Variables ──
 const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
 const APP_URL = "{{ rtrim(url('/'), '/') }}";
 let activeDeal = null;
 let selectedPaymentMethod = 'cash';
 let liveTimerInterval = null;

 // ── Clock ──
 function updateClock() {
 const now = new Date();
 const el = document.getElementById('pos-live-clock');
 if (el) el.textContent = now.toLocaleTimeString('ar-EG');
 }
 setInterval(updateClock, 1000);
 updateClock();

 // ── Live Deals Running Timers ──
 function updateRunningTimers() {
 document.querySelectorAll('.active-deal-timer').forEach(el => {
 const startedStr = el.dataset.started;
 if (!startedStr) return;
 const started = new Date(startedStr);
 const diffMs = Math.max(0, new Date() - started);
 const hrs = Math.floor(diffMs / 3600000);
 const mins = Math.floor((diffMs % 3600000) / 60000);
 const secs = Math.floor((diffMs % 60000) / 1000);
 el.textContent = `${String(hrs).padStart(2,'0')}:${String(mins).padStart(2,'0')}:${String(secs).padStart(2,'0')}`;
 });

 if (activeDeal && activeDeal.started_at) {
 const started = new Date(activeDeal.started_at);
 const diffMs = Math.max(0, new Date() - started);
 const hrs = Math.floor(diffMs / 3600000);
 const mins = Math.floor((diffMs % 3600000) / 60000);
 const secs = Math.floor((diffMs % 60000) / 1000);
 const timerEl = document.getElementById('co-timer-display');
 if (timerEl) timerEl.textContent = `${String(hrs).padStart(2,'0')}:${String(mins).padStart(2,'0')}:${String(secs).padStart(2,'0')}`;
 }
 }
 setInterval(updateRunningTimers, 1000);

 // ── Modal Handlers ──
 function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
 function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

 // ── Customer Autocomplete & Selection in New Session ──
 let customerSearchTimeout = null;

 function searchCustomers(query) {
     clearTimeout(customerSearchTimeout);
     const suggestionsEl = document.getElementById('ns-customer-suggestions');
     if (!suggestionsEl) return;

     query = (query || '').trim();
     if (!query) {
         suggestionsEl.innerHTML = '';
         suggestionsEl.classList.add('hidden');
         return;
     }

     customerSearchTimeout = setTimeout(() => {
         fetch(`${APP_URL}/api/v1/customers?search=${encodeURIComponent(query)}&limit=10`, {
             headers: {
                 'Accept': 'application/json',
                 'X-CSRF-TOKEN': CSRF_TOKEN
             }
         })
         .then(res => res.json())
         .then(json => {
             const customers = json.data || [];
             if (customers.length === 0) {
                 suggestionsEl.innerHTML = `
                     <div class="p-3 text-center text-xs text-slate-500">
                         <p class="mb-1.5 font-medium">لا يوجد عميل مطابق للبحث</p>
                         <button type="button" onclick="openAddCustomerModal()" class="text-[#4E8F35] font-bold hover:underline text-xs inline-flex items-center gap-1">
                             <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                             + إضافة عميل جديد الآن
                         </button>
                     </div>
                 `;
                 suggestionsEl.classList.remove('hidden');
                 return;
             }

             let html = '<div class="divide-y divide-slate-100 py-1">';
             customers.forEach(c => {
                 const name = (c.full_name || c.name || '').replace(/'/g, "\\'");
                 const phone = (c.phone || '').replace(/'/g, "\\'");
                 const initials = (c.initials || (c.full_name ? c.full_name.charAt(0) : 'ع')).replace(/'/g, "\\'");
                 const typeLabel = c.type === 'guest' ? 'زائر' : 'مسجل';
                 
                 html += `
                     <button type="button" 
                         onclick="selectCustomer({ id: ${c.id}, full_name: '${name}', phone: '${phone}', initials: '${initials}' })"
                         class="w-full text-right px-3 py-2 hover:bg-[#EBF4E8] flex items-center justify-between transition-colors group">
                         <div class="flex items-center gap-2.5">
                             <span class="size-7 rounded-lg bg-slate-100 group-hover:bg-[#4E8F35] group-hover:text-white text-slate-700 text-xs font-black flex items-center justify-center transition-colors">${initials}</span>
                             <div>
                                 <p class="text-xs font-bold text-slate-800 group-hover:text-[#4E8F35] transition-colors">${c.full_name || c.name}</p>
                                 <p class="text-[10px] text-slate-400 font-mono" dir="ltr">${c.phone || ''}</p>
                             </div>
                         </div>
                         <span class="text-[10px] px-1.5 py-0.5 rounded bg-slate-100 text-slate-600 font-medium">${typeLabel}</span>
                     </button>
                 `;
             });
             html += '</div>';
             suggestionsEl.innerHTML = html;
             suggestionsEl.classList.remove('hidden');
         })
         .catch(err => {
             console.error('Customer search error:', err);
         });
     }, 200);
 }

 function selectCustomer(customer) {
     const idInput = document.getElementById('ns-customer-id');
     const searchInput = document.getElementById('ns-customer-search');
     const selectedBox = document.getElementById('ns-customer-selected');
     const suggestionsEl = document.getElementById('ns-customer-suggestions');

     if (idInput) idInput.value = customer.id;
     if (searchInput) searchInput.value = '';
     if (suggestionsEl) {
         suggestionsEl.innerHTML = '';
         suggestionsEl.classList.add('hidden');
     }

     const nameEl = document.getElementById('ns-sel-name');
     const phoneEl = document.getElementById('ns-sel-phone');
     const initialsEl = document.getElementById('ns-sel-initials');

     if (nameEl) nameEl.textContent = customer.full_name || customer.name || '';
     if (phoneEl) phoneEl.textContent = customer.phone || '';
     if (initialsEl) initialsEl.textContent = customer.initials || (customer.full_name ? customer.full_name.charAt(0) : 'ع');

     if (selectedBox) selectedBox.classList.remove('hidden');
 }

 function clearCustomerSelection() {
     const idInput = document.getElementById('ns-customer-id');
     const searchInput = document.getElementById('ns-customer-search');
     const selectedBox = document.getElementById('ns-customer-selected');
     const suggestionsEl = document.getElementById('ns-customer-suggestions');

     if (idInput) idInput.value = '';
     if (selectedBox) selectedBox.classList.add('hidden');
     if (suggestionsEl) {
         suggestionsEl.innerHTML = '';
         suggestionsEl.classList.add('hidden');
     }
     if (searchInput) {
         searchInput.value = '';
         searchInput.focus();
     }
 }

 function openNewSessionModal(roomId = null) {
     clearCustomerSelection();
     if (roomId) {
         const roomSelect = document.getElementById('ns-room-id');
         if (roomSelect) {
             roomSelect.value = roomId;
             if (typeof onRoomSelectedInModal === 'function') {
                 onRoomSelectedInModal(roomSelect);
             }
         }
     }
     openModal('modal-new-session');
     setTimeout(() => {
         const s = document.getElementById('ns-customer-search');
         if (s) s.focus();
     }, 100);
 }

 function openAddCustomerModal() {
     const form = document.getElementById('form-add-customer');
     if (form) form.reset();
     document.querySelectorAll('input[name="qc-classification"], input[name="qc-source"]').forEach(r => r.checked = false);
     openModal('modal-add-customer');
     setTimeout(() => {
         const nameInput = document.getElementById('qc-name');
         if (nameInput) nameInput.focus();
     }, 100);
 }

 
 // ── Room Header Click & Shared Area Logic ──
 function handleRoomHeaderClick(roomId, isFull, isPrivate, dealId) {
     if (isPrivate && dealId) {
         loadDealDetails(dealId);
     } else if (!isFull) {
         openNewSessionModal(roomId);
     } else if (dealId) {
         loadDealDetails(dealId);
     }
 }

 function onRoomSelectedInModal(selectEl) {
     const opt = selectEl.options[selectEl.selectedIndex];
     if (!opt || !opt.value) return;
     const activeCount = parseInt(opt.dataset.activeCount || '0', 10);
     const remaining = parseInt(opt.dataset.remaining || '0', 10);
     const notice = document.getElementById('ns-room-notice');
     const wsSelect = document.getElementById('ns-workspace-type-id');

     if (activeCount > 0) {
         // Already has shared deals: force shared type and disable private options
         if (notice) {
             notice.textContent = `ℹ️ هذه الغرفة بها ${activeCount} جلسة مشتركة نشطة حالياً. الحجز متاح كجلسة مشتركة (Shared) حتى اكتمال السعة (متبقي ${remaining} أماكن).`;
             notice.classList.remove('hidden');
         }
         Array.from(wsSelect.options).forEach(o => {
             if (o.dataset.isPrivate === '1') {
                 o.disabled = true;
             } else {
                 o.disabled = false;
                 o.selected = true;
             }
         });
     } else {
         if (notice) notice.classList.add('hidden');
         Array.from(wsSelect.options).forEach(o => o.disabled = false);
     }
 }

 function onWorkspaceTypeSelected(selectEl) {
     const opt = selectEl.options[selectEl.selectedIndex];
     if (!opt) return;
     const isPrivate = opt.dataset.isPrivate === '1';
     const roomSelect = document.getElementById('ns-room-id');
     const notice = document.getElementById('ns-room-notice');

     if (isPrivate) {
         // Disable rooms with active deals
         Array.from(roomSelect.options).forEach(ro => {
             const activeCount = parseInt(ro.dataset.activeCount || '0', 10);
             if (activeCount > 0) {
                 ro.disabled = true;
             }
         });
         // If selected room has active deals, deselect it
         const selectedRoomOpt = roomSelect.options[roomSelect.selectedIndex];
         if (selectedRoomOpt && parseInt(selectedRoomOpt.dataset.activeCount || '0', 10) > 0) {
             roomSelect.value = '';
             if (notice) {
                 notice.textContent = '⚠️ الغرف الخاصة (Private) تتطلب غرفة فارغة بالكامل. تم إلغاء تحديد الغرفة المشغولة.';
                 notice.classList.remove('hidden');
             }
         }
     } else {
         // Re-evaluate based on capacity
         Array.from(roomSelect.options).forEach(ro => {
             const isPrivLocked = ro.dataset.isPrivate === '1';
             const remaining = parseInt(ro.dataset.remaining || '0', 10);
             if (isPrivLocked || remaining <= 0) {
                 ro.disabled = true;
             } else {
                 ro.disabled = false;
             }
         });
     }
 }

 // ── Customer Profile & History Modal ──
 let currentProfileCustomerId = null;
 function openCustomerProfile(customerId) {
     currentProfileCustomerId = customerId;
     openModal('modal-customer-profile');
     document.getElementById('cp-loading').classList.remove('hidden');
     document.getElementById('cp-tab-content').innerHTML = '<div class="py-8 text-center text-slate-400 text-xs" id="cp-loading">جاري تحميل سجل العميل...</div>';

     fetch(`${APP_URL}/cashier/customers/${customerId}/history`)
         .then(res => res.json())
         .then(data => {
             if (!data.success) throw new Error();
             const c = data.customer;
             document.getElementById('cp-name').textContent = c.full_name || c.name || '--';
             document.getElementById('cp-phone').textContent = c.phone || '--';
             document.getElementById('cp-email').textContent = c.email || '';
             document.getElementById('cp-avatar').textContent = (c.full_name || c.name || 'ع').charAt(0);
             document.getElementById('cp-total-spent').textContent = parseFloat(data.total_spent || 0).toFixed(2) + ' ج.م';
             document.getElementById('cp-visits-count').textContent = data.visits_count || 0;
             document.getElementById('cp-notes-input').value = c.notes || '';

             const presEl = document.getElementById('cp-current-presence');
             if (data.current_session) {
                 presEl.textContent = `متواجد في ${data.current_session.room_name || 'المساحة العامة'}`;
                 presEl.className = 'text-xs font-bold text-[#4E8F35] mt-0.5 block truncate';
             } else {
                 presEl.textContent = 'غير متواجد حالياً';
                 presEl.className = 'text-xs font-bold text-slate-500 mt-0.5 block truncate';
             }

             window._customerData = data;
             switchCustomerTab('orders');
         })
         .catch(err => {
             console.error(err);
             document.getElementById('cp-tab-content').innerHTML = '<div class="py-6 text-center text-rose-500 text-xs">تعذر تحميل بيانات العميل.</div>';
         });
 }

 function saveCustomerNotesAction() {
     if (!currentProfileCustomerId) return;
     const notes = document.getElementById('cp-notes-input').value;
     const fb = document.getElementById('cp-notes-feedback');
     fetch(`${APP_URL}/cashier/customers/${currentProfileCustomerId}/notes`, {
         method: 'POST',
         headers: {
             'Content-Type': 'application/json',
             'Accept': 'application/json',
             'X-CSRF-TOKEN': CSRF_TOKEN,
         },
         body: JSON.stringify({ notes: notes })
     })
     .then(r => r.json())
     .then(d => {
         if (fb) {
             fb.classList.remove('hidden');
             setTimeout(() => fb.classList.add('hidden'), 2500);
         }
     })
     .catch(e => console.error(e));
 }

 function switchCustomerTab(tab) {
     document.querySelectorAll('.cp-tab').forEach(b => {
         b.classList.remove('bg-[#4E8F35]', 'text-white');
         b.classList.add('text-slate-600');
     });
     const activeBtn = document.getElementById('cp-tab-' + tab);
     if (activeBtn) {
         activeBtn.classList.remove('text-slate-600');
         activeBtn.classList.add('bg-[#4E8F35]', 'text-white');
     }

     const container = document.getElementById('cp-tab-content');
     const d = window._customerData;
     if (!d) return;

     if (tab === 'orders') {
         if (!d.orders || d.orders.length === 0) {
             container.innerHTML = '<div class="py-6 text-center text-slate-400 text-xs">لا يوجد طلبات سابقة لهذا العميل.</div>';
             return;
         }
         container.innerHTML = d.orders.map(ord => `
             <div class="p-2.5 rounded-xl border border-slate-100 bg-slate-50 flex items-center justify-between text-xs">
                 <div>
                     <span class="font-bold text-slate-800">${ord.order_number}</span>
                     <span class="text-slate-400 font-mono text-[11px] mr-2">${ord.created_at}</span>
                     <p class="text-[11px] text-slate-500 mt-0.5">${ord.items_summary || ''}</p>
                 </div>
                 <span class="font-mono font-black text-[#4E8F35]">${parseFloat(ord.total).toFixed(2)} ج.م</span>
             </div>
         `).join('');
     } else if (tab === 'deals') {
         if (!d.deals || d.deals.length === 0) {
             container.innerHTML = '<div class="py-6 text-center text-slate-400 text-xs">لا يوجد جلسات سابقة.</div>';
             return;
         }
         container.innerHTML = d.deals.map(dl => `
             <div class="p-2.5 rounded-xl border border-slate-100 bg-slate-50 flex items-center justify-between text-xs">
                 <div>
                     <span class="font-bold text-slate-800">${dl.room_name || 'مساحة عامة'}</span>
                     <span class="text-slate-400 font-mono text-[11px] mr-2">${dl.started_at}</span>
                     <p class="text-[11px] text-slate-500 mt-0.5">المدة: ${dl.duration_formatted || '--'}</p>
                 </div>
                 <span class="font-mono font-bold text-slate-700">${dl.status_label || dl.status}</span>
             </div>
         `).join('');
     } else if (tab === 'bookings') {
         if (!d.bookings || d.bookings.length === 0) {
             container.innerHTML = '<div class="py-6 text-center text-slate-400 text-xs">لا توجد حجوزات مسجلة.</div>';
             return;
         }
         container.innerHTML = d.bookings.map(bk => `
             <div class="p-2.5 rounded-xl border border-slate-100 bg-slate-50 flex items-center justify-between text-xs">
                 <div>
                     <span class="font-bold text-slate-800">${bk.room_name || 'قاعة'}</span>
                     <span class="text-slate-400 font-mono text-[11px] mr-2">${bk.date}</span>
                 </div>
                 <span class="font-bold text-xs text-blue-600">${bk.status}</span>
             </div>
         `).join('');
     }
 }

 // ── Room Card Click Handler ──
 function handleRoomClick(roomId, status, dealId) {
 if (status === 'occupied' && dealId) {
 loadDealDetails(dealId);
 } else {
 openNewSessionModal(roomId);
 }
 }

 // ── Load Deal Details into Checkout Panel ──
 function loadDealDetails(dealId) {
 fetch(`${APP_URL}/api/v1/deals/${dealId}`)
 .then(res => res.json())
 .then(data => {
 if (data.data) {
 activeDeal = data.data;
 renderCheckoutPanel();
 }
 })
 .catch(err => {
 console.error('Error loading deal:', err);
 alert('حدث خطأ أثناء تحميل بيانات الجلسة.');
 });
 }

 // ── Render Checkout Panel ──
 function renderCheckoutPanel() {
 if (!activeDeal) {
 document.getElementById('checkout-panel').classList.add('hidden');
 document.getElementById('checkout-placeholder').classList.remove('hidden');
 return;
 }

 document.getElementById('checkout-placeholder').classList.add('hidden');
 document.getElementById('checkout-panel').classList.remove('hidden');

 // Customer Info
 const customerName = activeDeal.customer ? (activeDeal.customer.name || activeDeal.customer.full_name) : 'عميل مباشر';
 document.getElementById('co-customer-name').textContent = customerName;
 document.getElementById('co-customer-avatar').textContent = customerName.charAt(0);
 document.getElementById('co-room-name').textContent = activeDeal.room ? activeDeal.room.name : 'مساحة عمل عامة';

 // Items List
 const itemsContainer = document.getElementById('co-order-items-list');
 itemsContainer.innerHTML = '';

 // Room / Duration item
 const timeItemHtml = `
 <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between text-xs">
 <div class="flex items-center gap-2.5">
 <div class="w-8 h-8 rounded-lg bg-[#EBF4E8] text-[#4E8F35] flex items-center justify-center font-bold">
 <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
 </div>
 <div>
 <div class="font-extrabold text-slate-900">حساب مدة الجلسة</div>
 <div class="text-[10px] text-slate-400 font-medium">${activeDeal.workspace_type || 'مكتب مشترك'}</div>
 </div>
 </div>
 <div class="font-black text-[#4E8F35] font-mono text-xs" id="item-time-price">-- ج.م</div>
 </div>
 `;
 itemsContainer.insertAdjacentHTML('beforeend', timeItemHtml);

 // Products items
 let productsSubtotal = 0;
 const items = (activeDeal.order && activeDeal.order.items) ? activeDeal.order.items : [];

 items.forEach(item => {
 productsSubtotal += parseFloat(item.total || (item.unit_price * item.quantity) || 0);
 const itemEl = document.createElement('div');
 itemEl.className = 'p-3 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between text-xs';
 itemEl.innerHTML = `
 <div class="flex items-center gap-2.5 flex-1">
 <div class="w-8 h-8 rounded-lg bg-[#F5F3EE] text-[#303334] flex items-center justify-center font-bold">
 <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 8h1a4 4 0 1 1 0 8h-1"/><path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z"/><line x1="6" x2="6" y1="2" y2="4"/><line x1="10" x2="10" y1="2" y2="4"/><line x1="14" x2="14" y1="2" y2="4"/></svg>
 </div>
 <div>
 <div class="font-extrabold text-slate-900">${item.name}</div>
 <div class="text-[10px] text-slate-400 font-mono font-medium">${parseFloat(item.unit_price).toFixed(2)} ج.م × ${item.quantity}</div>
 </div>
 </div>
 <div class="flex items-center gap-2.5">
 <span class="font-black text-slate-900 font-mono">${parseFloat(item.total).toFixed(2)} ج.م</span>
 <button type="button" onclick="removeOrderItem(${item.id})" class="text-slate-400 hover:text-rose-600 p-1" title="حذف">
 <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
 </button>
 </div>
 `;
 itemsContainer.appendChild(itemEl);
 });

 // Calculate estimated time price
 calculateEstimatedDurationPrice();
 calculateTotal();
 }

 function deselectSession() {
 activeDeal = null;
 renderCheckoutPanel();
 }

 // ── Calculate Estimated Duration Price from Start time ──
 function calculateEstimatedDurationPrice() {
 if (!activeDeal || !activeDeal.started_at) return;
 const started = new Date(activeDeal.started_at);
 const now = new Date();
 const minutes = Math.max(1, Math.round((now - started) / 60000));

 // Estimate approx based on 30 EGP / hr
 const hours = minutes / 60;
 const estimatedTimePrice = Math.max(25, Math.ceil(hours * 30));
 document.getElementById('item-time-price').textContent = `${estimatedTimePrice.toFixed(2)} ج.م`;
 document.getElementById('co-time-subtotal').textContent = `${estimatedTimePrice.toFixed(2)} ج.م`;
 }

 function calculateTotal() {
 if (!activeDeal) return;
 let timePrice = parseFloat(document.getElementById('co-time-subtotal').textContent) || 0;
 let productsPrice = 0;
 if (activeDeal.order && activeDeal.order.items) {
 productsPrice = activeDeal.order.items.reduce((sum, it) => sum + parseFloat(it.total || 0), 0);
 }
 document.getElementById('co-products-subtotal').textContent = `${productsPrice.toFixed(2)} ج.م`;

 let discount = parseFloat(document.getElementById('co-discount-input').value) || 0;
 let netTotal = Math.max(0, timePrice + productsPrice - discount);

 document.getElementById('co-total-amount').innerHTML = `${netTotal.toFixed(2)} <span class="text-xs font-normal text-slate-400">ج.م</span>`;
 calculateChange(netTotal);
 }

 // ── Cash Change Calculator ──
 let receivedCashAmount = 0;
 function setReceivedCash(amount) {
 receivedCashAmount = amount;
 let total = parseFloat(document.getElementById('co-total-amount').textContent) || 0;
 let change = Math.max(0, receivedCashAmount - total);
 document.getElementById('co-change-amount').textContent = `${change.toFixed(2)} ج.م (من ${amount} ج.م)`;
 }
 function calculateChange(netTotal) {
 if (receivedCashAmount > 0) {
 let change = Math.max(0, receivedCashAmount - netTotal);
 document.getElementById('co-change-amount').textContent = `${change.toFixed(2)} ج.م`;
 }
 }

 // ── Payment Method Toggle ──
 function selectPaymentMethod(method) {
 selectedPaymentMethod = method;
 document.querySelectorAll('.pay-method-btn').forEach(btn => {
 btn.classList.remove('border-2', 'border-emerald-500', 'bg-emerald-50', 'text-emerald-800');
 btn.classList.add('border', 'border-slate-200', 'bg-slate-50', 'text-slate-600');
 });

 const activeBtn = document.getElementById(`btn-pay-${method}`);
 if (activeBtn) {
 activeBtn.classList.remove('border', 'border-slate-200', 'bg-slate-50', 'text-slate-600');
 activeBtn.classList.add('border-2', 'border-emerald-500', 'bg-emerald-50', 'text-emerald-800');
 }

 const changeBox = document.getElementById('cash-change-box');
 if (method === 'cash') {
 changeBox.classList.remove('hidden');
 } else {
 changeBox.classList.add('hidden');
 }
 }

 // ── Add Product To Active Session ──
 function addProductToActiveSession(productId, productName, price) {
 if (!activeDeal) {
 alert('يرجى اختيار جلسة نشطة أولاً من القائمة لإضافة المشروبات والطلبات إليها.');
 return;
 }

 fetch(`${APP_URL}/api/v1/deals/${activeDeal.id}/items`, {
 method: 'POST',
 headers: {
 'Content-Type': 'application/json',
 'Accept': 'application/json',
 'X-CSRF-TOKEN': CSRF_TOKEN,
 },
 body: JSON.stringify({
 product_id: productId,
 quantity: 1,
 })
 })
 .then(async res => {
 const data = await res.json();
 if (!res.ok) throw new Error(data.message || 'تعذر إضافة المنتج.');
 return data;
 })
 .then(data => {
 // Reload active deal
 loadDealDetails(activeDeal.id);
 })
 .catch(err => {
 console.error(err);
 alert(err.message || 'تعذر إضافة المنتج.');
 });
 }

 // ── Remove Item from Deal ──
 function removeOrderItem(itemId) {
 if (!activeDeal || !confirm('هل أنت متأكد من حذف هذا الصنف من الفاتورة؟')) return;

 fetch(`${APP_URL}/api/v1/deals/${activeDeal.id}/items/${itemId}`, {
 method: 'DELETE',
 headers: {
 'Accept': 'application/json',
 'X-CSRF-TOKEN': CSRF_TOKEN,
 }
 })
 .then(res => res.json())
 .then(data => {
 loadDealDetails(activeDeal.id);
 })
 .catch(err => console.error(err));
 }

 // ── Submit New Session (F2) ──
 function submitNewSession(e) {
 e.preventDefault();
 const customerId = document.getElementById('ns-customer-id').value;
 const roomId = document.getElementById('ns-room-id').value;
 const workspaceTypeId = document.getElementById('ns-workspace-type-id').value;
 const notes = document.getElementById('ns-notes').value;

 fetch(`${APP_URL}/api/v1/deals`, {
 method: 'POST',
 headers: {
 'Content-Type': 'application/json',
 'Accept': 'application/json',
 'X-CSRF-TOKEN': CSRF_TOKEN,
 },
 body: JSON.stringify({
 customer_id: customerId,
 room_id: roomId || null,
 workspace_type_id: workspaceTypeId,
 notes: notes,
 })
 })
 .then(async res => {
 const data = await res.json();
 if (!res.ok) {
 throw new Error(data.message || 'تعذر فتح الجلسة.');
 }
 return data;
 })
 .then(data => {
 closeModal('modal-new-session');
 window.location.reload();
 })
 .catch(err => {
 console.error(err);
 alert(err.message || 'تعذر فتح الجلسة.');
 });
 }

 // ── Submit Quick Customer (F3) ──
 function submitQuickCustomer(e) {
     e.preventDefault();
     const name = document.getElementById('qc-name').value;
     const phone = document.getElementById('qc-phone').value;
     const email = document.getElementById('qc-email') ? document.getElementById('qc-email').value : null;
     const notes = document.getElementById('qc-notes') ? document.getElementById('qc-notes').value : null;
     const classificationEl = document.querySelector('input[name="qc-classification"]:checked');
     const sourceEl = document.querySelector('input[name="qc-source"]:checked');
     const classification = classificationEl ? classificationEl.value : null;
     const source = sourceEl ? sourceEl.value : null;

     fetch(`${APP_URL}/api/v1/customers`, {
         method: 'POST',
         headers: {
             'Content-Type': 'application/json',
             'Accept': 'application/json',
             'X-CSRF-TOKEN': CSRF_TOKEN,
         },
         body: JSON.stringify({
             name: name,
             phone: phone,
             email: email || null,
             classification: classification || null,
             source: source || null,
             notes: notes || null,
         })
     })
     .then(async res => {
         const data = await res.json();
         if (!res.ok) {
             const msg = data.message || (data.errors ? Object.values(data.errors).flat().join('\n') : 'حدث خطأ أثناء حفظ العميل.');
             throw new Error(msg);
         }
         return data;
     })
     .then(data => {
         closeModal('modal-add-customer');
         if (data.data) {
             selectCustomer(data.data);
         }
         alert('تم تسجيل العميل واختياره بنجاح.');
     })
     .catch(err => {
         console.error(err);
         alert(err.message || 'حدث خطأ أثناء حفظ العميل.');
     });
 }

 // ── Checkout, Close & Pay (F9) ──
 function executeCheckoutAndPay() {
 if (!activeDeal) return;
 if (!confirm('تأكيد إنهاء الجلسة وتحصيل المبلغ وإقفال الحساب؟')) return;

 const btn = document.getElementById('btn-submit-checkout');
 btn.disabled = true;
 btn.innerHTML = 'جاري التحصيل والإنهاء...';

 fetch(`${APP_URL}/api/v1/deals/${activeDeal.id}/close`, {
 method: 'POST',
 headers: {
 'Content-Type': 'application/json',
 'Accept': 'application/json',
 'X-CSRF-TOKEN': CSRF_TOKEN,
 }
 })
 .then(async res => {
 const data = await res.json();
 if (!res.ok) throw new Error(data.message || 'فشل في إغلاق الجلسة.');

 const totalAmt = parseFloat(document.getElementById('co-total-amount').textContent) || 0;
 
 if (totalAmt > 0) {
 const payRes = await fetch(`${APP_URL}/api/v1/deals/${activeDeal.id}/pay`, {
 method: 'POST',
 headers: {
 'Content-Type': 'application/json',
 'Accept': 'application/json',
 'X-CSRF-TOKEN': CSRF_TOKEN,
 },
 body: JSON.stringify({
 payments: [{
 method: selectedPaymentMethod,
 amount: totalAmt,
 }]
 })
 });
 const payData = await payRes.json();
 if (!payRes.ok) throw new Error(payData.message || 'فشل في تحصيل المبلغ.');
 }

 // Refresh UI
 alert('تم إنهاء الجلسة بنجاح.');
 window.location.reload();
 })
 .catch(err => {
 console.error(err);
 alert(err.message || 'حدث خطأ أثناء إتمام عملية التحصيل.');
 btn.disabled = false;
 btn.innerHTML = 'إنهاء الجلسة والتحصيل (F9)';
 });
 }

 // ── Show Printable Thermal Receipt ──
 function showPrintableReceipt() {
 const customerName = document.getElementById('co-customer-name').textContent;
 const roomName = document.getElementById('co-room-name').textContent;
 const timer = document.getElementById('co-timer-display').textContent;
 const total = document.getElementById('co-total-amount').textContent;

 document.getElementById('rec-customer').textContent = customerName;
 document.getElementById('rec-room').textContent = roomName;
 document.getElementById('rec-duration').textContent = timer;
 document.getElementById('rec-datetime').textContent = new Date().toLocaleString('ar-EG');
 document.getElementById('rec-total').textContent = total;
 document.getElementById('rec-method').textContent = selectedPaymentMethod === 'cash' ? 'كاش نقدي' : selectedPaymentMethod;

 const recList = document.getElementById('rec-items-list');
 recList.innerHTML = '';
 if (activeDeal && activeDeal.order && activeDeal.order.items) {
 activeDeal.order.items.forEach(it => {
 const line = document.createElement('div');
 line.className = 'flex justify-between';
 line.innerHTML = `<span>${it.name} × ${it.quantity}</span><span>${parseFloat(it.total).toFixed(2)} ج.م</span>`;
 recList.appendChild(line);
 });
 }

 openModal('modal-receipt');
 }

 // ── Keyboard Shortcuts (F2, F3, F9, Esc) ──
 window.addEventListener('keydown', function(e) {
 if (e.key === 'F2') {
 e.preventDefault();
 openNewSessionModal();
 } else if (e.key === 'F3') {
 e.preventDefault();
 openAddCustomerModal();
 } else if (e.key === 'F9') {
 e.preventDefault();
 if (activeDeal) executeCheckoutAndPay();
 } else if (e.key === 'Escape') {
 closeModal('modal-new-session');
 closeModal('modal-add-customer');
 }
 });

 // ── Filter Rooms Tabs ──
 function filterRooms(filter) {
 document.querySelectorAll('.room-filter-tab').forEach(t => {
 t.classList.remove('bg-[#4E8F35]', 'text-white', 'shadow-xs', 'font-extrabold');
 t.classList.add('text-slate-600', 'font-bold');
 });
 const activeTab = document.getElementById(`tab-room-${filter}`);
 if (activeTab) {
 activeTab.classList.remove('text-slate-600', 'font-bold');
 activeTab.classList.add('bg-[#4E8F35]', 'text-white', 'shadow-xs', 'font-extrabold');
 }

 document.querySelectorAll('.room-card').forEach(card => {
 const status = card.dataset.status;
 if (filter === 'all' || status === filter) {
 card.classList.remove('hidden');
 } else {
 card.classList.add('hidden');
 }
 });
 }

 // ── Filter Products Tabs by Category Code ──
 function filterProductsByCode(catCode, tabBtn) {
 document.querySelectorAll('.prod-cat-tab').forEach(t => {
 t.classList.remove('bg-[#4E8F35]', 'text-white', 'font-extrabold', 'shadow-xs');
 t.classList.add('bg-slate-100', 'text-slate-700', 'font-bold');
 });
 if (tabBtn) {
 tabBtn.classList.remove('bg-slate-100', 'text-slate-700', 'font-bold');
 tabBtn.classList.add('bg-[#4E8F35]', 'text-white', 'font-extrabold', 'shadow-xs');
 }

 document.querySelectorAll('.product-item-card').forEach(card => {
 const itemCat = card.dataset.productCategory;
 if (catCode === 'all' || itemCat === catCode) {
 card.classList.remove('hidden');
 } else {
 card.classList.add('hidden');
 }
 });
 }

 // ── Live Search Filters ──
 document.getElementById('pos-product-search').addEventListener('input', function(e) {
 const q = e.target.value.toLowerCase().trim();
 document.querySelectorAll('.product-item-card').forEach(card => {
 const name = card.dataset.productName.toLowerCase();
 if (!q || name.includes(q)) {
 card.classList.remove('hidden');
 } else {
 card.classList.add('hidden');
 }
 });
 });

 document.getElementById('pos-global-search').addEventListener('input', function(e) {
 const q = e.target.value.toLowerCase().trim();
 document.querySelectorAll('.room-card').forEach(card => {
 const text = card.textContent.toLowerCase();
 if (!q || text.includes(q)) {
 card.classList.remove('hidden');
 } else {
 card.classList.add('hidden');
 }
 });
 document.querySelectorAll('.product-item-card').forEach(card => {
 const text = card.dataset.productName.toLowerCase();
 if (!q || text.includes(q)) {
 card.classList.remove('hidden');
 } else {
 card.classList.add('hidden');
 }
 });
 });

 // ── Real-time Portal Orders & Audio Notification ──
 let lastOrdersCount = {{ $pendingPortalOrders->count() }};

 function playOrderChime() {
 try {
 const ctx = new (window.AudioContext || window.webkitAudioContext)();
 const now = ctx.currentTime;
 // Tone 1
 const osc1 = ctx.createOscillator();
 const gain1 = ctx.createGain();
 osc1.type = 'sine';
 osc1.frequency.setValueAtTime(587.33, now); // D5
 gain1.gain.setValueAtTime(0.3, now);
 gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.5);
 osc1.connect(gain1);
 gain1.connect(ctx.destination);
 osc1.start(now);
 osc1.stop(now + 0.5);

 // Tone 2
 const osc2 = ctx.createOscillator();
 const gain2 = ctx.createGain();
 osc2.type = 'sine';
 osc2.frequency.setValueAtTime(880, now + 0.2); // A5
 gain2.gain.setValueAtTime(0.3, now + 0.2);
 gain2.gain.exponentialRampToValueAtTime(0.001, now + 0.8);
 osc2.connect(gain2);
 gain2.connect(ctx.destination);
 osc2.start(now + 0.2);
 osc2.stop(now + 0.8);
 } catch (e) {
 console.log('Audio error:', e);
 }
 }

 function pollPortalOrders() {
 fetch("{{ route('cashier.portal-orders') }}")
 .then(r => r.json())
 .then(data => {
 if (data.success) {
 const badge = document.getElementById('portal-orders-badge');
 if (data.count > 0) {
 badge.textContent = data.count;
 badge.classList.remove('hidden');
 if (data.count > lastOrdersCount) {
 playOrderChime();
 }
 } else {
 badge.classList.add('hidden');
 }
 lastOrdersCount = data.count;
 renderPortalOrders(data.orders);
 }
 })
 .catch(err => console.error('Poll error:', err));
 }

 function renderPortalOrders(orders) {
 const list = document.getElementById('portal-orders-bottom-list');
 if (!list) return;

 if (orders.length === 0) {
 list.innerHTML = `<div class="w-full h-full flex items-center justify-center text-slate-400 text-xs">لا توجد طلبات جديدة واردة من الموبايل حالياً.</div>`;
 return;
 }

 list.innerHTML = '';
 orders.forEach(ord => {
 const isPrep = ord.fulfillment_status === 'preparing';
 const itemsHtml = (ord.items || []).map(it => `
 <div class="flex justify-between text-xs">
 <span class="font-bold text-slate-800">${it.name} × ${it.quantity}</span>
 <span class="font-mono font-bold text-slate-600">${parseFloat(it.total).toFixed(2)} ج.م</span>
 </div>
 `).join('');

 const notesHtml = ord.customer_notes ? `
 <div class="text-[11px] text-amber-800 pt-1 border-t border-slate-100">
 <strong>ملاحظات:</strong> ${ord.customer_notes}
 </div>
 ` : '';

 const card = document.createElement('div');
 card.className = 'p-3 rounded-xl border border-[#E5E2DC] bg-white hover:border-[#4E8F35]/50 transition shadow-sm flex flex-col gap-2 min-w-[320px] shrink-0';
 card.id = `cashier-order-${ord.id}`;
 card.innerHTML = `
 <div class="flex items-start justify-between">
 <div>
 <span class="font-mono text-xs font-black text-[#4E8F35] bg-[#EBF4E8] px-2 py-0.5 rounded">${ord.order_number}</span>
 <span class="font-extrabold text-slate-900 text-sm mx-1">${ord.customer ? ord.customer.full_name : 'عميل'}</span>
 </div>
 <span class="px-2 py-0.5 rounded-md text-[10px] font-black ${isPrep ? 'bg-[#EBF4E8] text-[#4E8F35]' : 'bg-[#F5F3EE] text-[#303334]'}">
 ${isPrep ? 'جاري التحضير' : 'جديد ⏳'}
 </span>
 </div>

 <div class="text-[11px] text-slate-700 flex items-center gap-1.5">
 <span class="font-bold text-slate-500">الغرفة:</span>
 <span class="font-extrabold text-slate-900 bg-slate-100 px-1.5 py-0.5 rounded">${ord.table_or_room_name || 'المساحة العامة'}</span>
 </div>

 <div class="p-2 rounded-lg bg-slate-50 border border-slate-100 space-y-1 overflow-y-auto max-h-16">
 ${itemsHtml}
 ${notesHtml}
 </div>

 <div class="flex items-center justify-between pt-1">
 <div class="text-[11px] font-bold text-slate-800">
 الإجمالي: <span class="font-mono font-black text-[#4E8F35] text-xs">${parseFloat(ord.total).toFixed(2)} ج.م</span>
 </div>
 <div class="flex items-center gap-1.5">
 ${!isPrep ? `
 <button type="button" onclick="updateOrderStatus(${ord.id}, 'preparing')"
 class="px-2 py-1 rounded-lg bg-[#EBF4E8] hover:bg-[#DCE8D4] text-[#4E8F35] font-bold text-[10px] cursor-pointer border border-[#DCE8D4]">
 تحضير 
 </button>
 ` : ''}
 <button type="button" onclick="updateOrderStatus(${ord.id}, 'delivered')"
 class="px-2.5 py-1 rounded-lg bg-[#4E8F35] hover:bg-[#3F742B] text-white font-bold text-[10px] cursor-pointer shadow-xs">
 تم التسليم
 </button>
 <button type="button" onclick="updateOrderStatus(${ord.id}, 'cancelled')"
 class="px-2 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold text-[10px] cursor-pointer">
 إلغاء
 </button>
 </div>
 </div>
 `;
 list.appendChild(card);
 });
 }

 function updateOrderStatus(orderId, status) {
 fetch(`${APP_URL}/cashier/orders/${orderId}/fulfillment`, {
 method: 'POST',
 headers: {
 'Content-Type': 'application/json',
 'Accept': 'application/json',
 'X-CSRF-TOKEN': CSRF_TOKEN,
 },
 body: JSON.stringify({ status: status })
 })
 .then(r => r.json())
 .then(data => {
 if (data.success) {
 pollPortalOrders();
 if (activeDeal) {
 loadDealDetails(activeDeal.id);
 }
 } else {
 alert(data.message || 'حدث خطأ أثناء التحديث.');
 }
 })
 .catch(err => {
 console.error(err);
 alert('تعذر الاتصال بالخادم.');
 });
 }

 // Start live polling immediately and every 3 seconds
    pollPortalOrders();
    setInterval(pollPortalOrders, 3000);
 </script>

{{-- ══════════════════════════════════════════════════════════════ --}}
{{-- Cashier Alert System Modal + JS Polling                       --}}
{{-- ══════════════════════════════════════════════════════════════ --}}

{{-- Alert Modal --}}
<div id="cashier-alert-modal"
     class="fixed inset-0 z-[9999] flex items-center justify-center p-4 hidden"
     role="dialog" aria-modal="true" aria-labelledby="alert-modal-title">

    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

    {{-- Modal Card --}}
    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md mx-auto overflow-hidden animate-[slideUp_0.35s_cubic-bezier(0.34,1.56,0.64,1)_both]">

        {{-- Header stripe --}}
        <div class="bg-gradient-to-r from-[#4E8F35] to-[#79B84A] p-5 flex items-start gap-4">
            <div class="size-12 rounded-2xl bg-white/20 flex items-center justify-center shrink-0">
                <svg class="size-6 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-white/80 text-xs font-semibold uppercase tracking-widest mb-0.5">تنبيه الكاشير</p>
                <h2 id="alert-modal-title" class="text-white font-black text-base leading-snug break-words">—</h2>
            </div>
            {{-- Badge: snooze count --}}
            <span id="alert-snooze-badge"
                  class="hidden shrink-0 px-2 py-0.5 rounded-full bg-white/25 text-white text-[10px] font-bold">
                تأجيل ×<span id="alert-snooze-count">0</span>
            </span>
        </div>

        {{-- Body --}}
        <div class="p-5">
            <p id="alert-modal-desc" class="text-[#73777A] text-sm leading-relaxed mb-1 min-h-[1.5rem]"></p>
            <p id="alert-modal-meta" class="text-[#B0ADA8] text-xs"></p>

            {{-- Queue indicator (multiple alerts) --}}
            <div id="alert-queue-bar" class="hidden mt-3 py-2 px-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-700 text-xs font-semibold flex items-center gap-2">
                <svg class="size-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16A8 8 0 0010 2zm1 11H9v-2h2v2zm0-4H9V6h2v4z"/></svg>
                <span id="alert-queue-msg">يوجد تنبيه إضافي في الانتظار</span>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="px-5 pb-5 flex flex-col gap-2.5">
            {{-- Done --}}
            <button id="btn-alert-done" type="button"
                    class="w-full flex items-center justify-center gap-2.5 px-4 py-3.5 rounded-2xl bg-[#4E8F35] hover:bg-[#3F742B] active:scale-[0.98] text-white font-black text-sm transition-all shadow-lg shadow-[#4E8F35]/30">
                <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                </svg>
                تم — إغلاق التنبيه
            </button>

            {{-- Snooze --}}
            <button id="btn-alert-snooze" type="button"
                    class="w-full flex items-center justify-center gap-2.5 px-4 py-3 rounded-2xl bg-[#F5F3EE] hover:bg-[#EAE7E0] active:scale-[0.98] text-[#73777A] hover:text-[#303334] font-bold text-sm transition-all border border-[#E5E2DC]">
                <svg class="size-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
                <span id="snooze-btn-label">تذكير لاحقاً (بعد 5 دقائق)</span>
            </button>
        </div>
    </div>
</div>

{{-- Create Alert Button (floating, for manual scheduling) --}}
<button id="btn-open-create-alert"
        title="إنشاء تنبيه مجدول"
        class="fixed bottom-6 start-6 z-50 size-12 rounded-2xl bg-[#4E8F35] hover:bg-[#3F742B] text-white shadow-lg shadow-[#4E8F35]/40 flex items-center justify-center transition-all hover:scale-105 active:scale-95">
    <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
    </svg>
</button>

{{-- Create Alert Mini-Form (popover above the button) --}}
<div id="create-alert-popover"
     class="hidden fixed bottom-22 start-6 z-50 bg-white rounded-2xl shadow-xl border border-[#E5E2DC] w-72 p-4"
     style="bottom: 5.5rem;">
    <p class="text-[#303334] font-black text-sm mb-3">📅 إنشاء تنبيه للكاشير</p>
    <input id="alert-form-title" type="text" placeholder="عنوان التنبيه..."
           class="w-full px-3 py-2 rounded-xl border border-[#E5E2DC] text-sm text-[#303334] mb-2 focus:outline-none focus:border-[#4E8F35] focus:ring-2 focus:ring-[#4E8F35]/20 transition-all"
           maxlength="255">
    <input id="alert-form-datetime" type="datetime-local"
           class="w-full px-3 py-2 rounded-xl border border-[#E5E2DC] text-sm text-[#303334] mb-3 focus:outline-none focus:border-[#4E8F35] focus:ring-2 focus:ring-[#4E8F35]/20 transition-all">
    <div class="flex gap-2">
        <button id="btn-submit-alert" type="button"
                class="flex-1 py-2 rounded-xl bg-[#4E8F35] hover:bg-[#3F742B] text-white text-xs font-bold transition-all">
            حفظ التنبيه
        </button>
        <button id="btn-cancel-alert" type="button"
                class="flex-1 py-2 rounded-xl bg-[#F5F3EE] hover:bg-[#EAE7E0] text-[#73777A] text-xs font-bold transition-all">
            إلغاء
        </button>
    </div>
</div>

<style>
@keyframes slideUp {
    from { opacity: 0; transform: translateY(30px) scale(0.95); }
    to   { opacity: 1; transform: translateY(0)    scale(1); }
}
</style>

<script>
(function () {
    'use strict';

    const CSRF  = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    const modal = document.getElementById('cashier-alert-modal');

    let alertQueue   = [];
    let currentAlert = null;
    let pollTimer    = null;

    // ── Audio Beep (Web Audio API) ──────────────────────────────────────────
    function playBeep() {
        try {
            const ctx  = new (window.AudioContext || window.webkitAudioContext)();
            const osc  = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.type      = 'sine';
            osc.frequency.setValueAtTime(880, ctx.currentTime);
            gain.gain.setValueAtTime(0.4, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.6);
            osc.start(ctx.currentTime);
            osc.stop(ctx.currentTime + 0.6);
        } catch (e) { /* silent if no audio context */ }
    }

    // ── Show modal for one alert ────────────────────────────────────────────
    function showAlert(alert) {
        currentAlert = alert;

        document.getElementById('alert-modal-title').textContent = alert.title;
        document.getElementById('alert-modal-desc').textContent  = alert.description || '';
        document.getElementById('alert-modal-meta').textContent  =
            alert.scheduled_at ? `مجدول: ${alert.scheduled_at}` : '';
        document.getElementById('snooze-btn-label').textContent  = alert.snooze_label;

        const badge = document.getElementById('alert-snooze-badge');
        const countEl = document.getElementById('alert-snooze-count');
        if (alert.snooze_count > 0) {
            countEl.textContent = alert.snooze_count;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }

        const queueBar = document.getElementById('alert-queue-bar');
        const queueMsg = document.getElementById('alert-queue-msg');
        const remaining = alertQueue.length;
        if (remaining > 0) {
            queueMsg.textContent = `يوجد ${remaining} تنبيه إضافي في الانتظار`;
            queueBar.classList.remove('hidden');
        } else {
            queueBar.classList.add('hidden');
        }

        modal.classList.remove('hidden');
        playBeep();
    }

    function hideModal() {
        modal.classList.add('hidden');
        currentAlert = null;

        // Show next in queue
        if (alertQueue.length > 0) {
            const next = alertQueue.shift();
            setTimeout(() => showAlert(next), 500);
        }
    }

    // ── Poll pending alerts ─────────────────────────────────────────────────
    async function pollAlerts() {
        try {
            const res  = await fetch('{{ route("cashier.alerts.pending") }}', {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
            });
            const data = await res.json();
            if (!data.success || !data.alerts?.length) return;

            // Merge new alerts that are not already shown / queued
            const existingIds = new Set([
                ...(currentAlert ? [currentAlert.id] : []),
                ...alertQueue.map(a => a.id)
            ]);

            const fresh = data.alerts.filter(a => !existingIds.has(a.id));
            if (!fresh.length) return;

            if (modal.classList.contains('hidden') && !currentAlert) {
                const [first, ...rest] = fresh;
                alertQueue.push(...rest);
                showAlert(first);
            } else {
                alertQueue.push(...fresh);
                // Update queue count on visible modal
                const remaining = alertQueue.length;
                const queueBar = document.getElementById('alert-queue-bar');
                const queueMsg = document.getElementById('alert-queue-msg');
                if (remaining > 0) {
                    queueMsg.textContent = `يوجد ${remaining} تنبيه إضافي في الانتظار`;
                    queueBar.classList.remove('hidden');
                }
            }
        } catch (e) {
            console.warn('[AlertPoll] failed:', e);
        }
    }

    // ── Complete (تم) ───────────────────────────────────────────────────────
    document.getElementById('btn-alert-done')?.addEventListener('click', async () => {
        if (!currentAlert) return;
        const id = currentAlert.id;
        try {
            await fetch(`${APP_URL}/cashier/alerts/${id}/complete`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
            });
        } catch(e) { console.warn(e); }
        hideModal();
    });

    // ── Snooze (تذكير لاحقاً) ───────────────────────────────────────────────
    document.getElementById('btn-alert-snooze')?.addEventListener('click', async () => {
        if (!currentAlert) return;
        const id = currentAlert.id;
        try {
            await fetch(`${APP_URL}/cashier/alerts/${id}/snooze`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
            });
        } catch(e) { console.warn(e); }
        hideModal();
    });

    // ── Create Alert Popover ────────────────────────────────────────────────
    const openBtn     = document.getElementById('btn-open-create-alert');
    const popover     = document.getElementById('create-alert-popover');
    const cancelBtn   = document.getElementById('btn-cancel-alert');
    const submitBtn   = document.getElementById('btn-submit-alert');
    const titleInput  = document.getElementById('alert-form-title');
    const dtInput     = document.getElementById('alert-form-datetime');

    // Set default datetime to "now + 10 min"
    function defaultDatetime() {
        const d = new Date(Date.now() + 10 * 60_000);
        d.setSeconds(0, 0);
        return d.toISOString().slice(0, 16);
    }

    openBtn?.addEventListener('click', () => {
        dtInput.value = defaultDatetime();
        popover.classList.toggle('hidden');
    });

    cancelBtn?.addEventListener('click', () => popover.classList.add('hidden'));

    submitBtn?.addEventListener('click', async () => {
        const title = titleInput.value.trim();
        const dt    = dtInput.value;
        if (!title || !dt) { titleInput.focus(); return; }

        submitBtn.disabled = true;
        submitBtn.textContent = '...جاري الحفظ';
        try {
            const res  = await fetch('{{ route("cashier.alerts.create") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ title, scheduled_at: dt })
            });
            const data = await res.json();
            if (data.success) {
                titleInput.value = '';
                popover.classList.add('hidden');
            } else {
                alert(data.message ?? 'فشل إنشاء التنبيه.');
            }
        } catch(e) {
            alert('تعذر الاتصال بالخادم.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'حفظ التنبيه';
        }
    });

    // ── Start polling every 30 seconds ─────────────────────────────────────
    pollAlerts();
    setInterval(pollAlerts, 30_000);
})();
</script>
</body>
</html>
