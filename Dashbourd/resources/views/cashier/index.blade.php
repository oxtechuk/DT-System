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
 <button type="button" onclick="openModal('modal-portal-orders')"
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
 $activeDeal = $room->activeDeals->first();
 @endphp
 <div class="room-card p-3.5 rounded-2xl border transition-all cursor-pointer relative overflow-hidden bg-white border-[#E5E2DC] hover:border-[#4E8F35]/70 shadow-xs"
 data-room-id="{{ $room->id }}"
 data-room-name="{{ $room->name }}"
 data-room-capacity="{{ $room->capacity }}"
 data-status="{{ $activeDeal ? 'occupied' : 'available' }}"
 data-deal-id="{{ $activeDeal ? $activeDeal->id : '' }}"
 onclick="handleRoomClick({{ $room->id }}, '{{ $activeDeal ? 'occupied' : 'available' }}', {{ $activeDeal ? $activeDeal->id : 'null' }})">

 {{-- Card Header --}}
 <div class="flex items-center justify-between mb-1.5">
 <div class="flex items-center gap-2">
 <span class="w-2.5 h-2.5 rounded-full {{ $activeDeal ? 'bg-[#73777A]' : 'bg-[#4E8F35]' }}"></span>
 <span class="font-extrabold text-xs text-[#303334]">{{ $room->name }}</span>
 </div>
 <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full {{ $activeDeal ? 'bg-[#F5F3EE] text-[#303334] border border-[#E5E2DC]' : 'bg-[#EBF4E8] text-[#4E8F35] border border-[#DCE8D4]' }}">
 {{ $activeDeal ? 'مشغولة' : 'متاحة' }}
 </span>
 </div>

 {{-- If Occupied: Customer & Time --}}
 @if($activeDeal)
 <div class="mt-2 pt-2 border-t border-[#E5E2DC] text-xs space-y-1.5">
 <div class="flex items-center justify-between">
 <span class="text-[#73777A] font-medium">العميل:</span>
 <div class="flex items-center gap-1">
 <strong class="text-[#303334] font-extrabold">{{ $activeDeal->customer->name ?? 'عميل مباشر' }}</strong>
 @if($activeDeal->customer_id)
 <button type="button" onclick="event.stopPropagation(); openCustomerProfile({{ $activeDeal->customer_id }})"
 title="عرض كارت وسجل العميل"
 class="p-0.5 rounded bg-[#EBF4E8] text-[#4E8F35] hover:bg-[#DCE8D4] text-[10px]">
 <svg class="size-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
 </button>
 @endif
 </div>
 </div>
 <div class="flex items-center justify-between font-mono">
 <span class="text-[#73777A] font-medium font-sans">المدة:</span>
 <span class="text-[#303334] font-black active-deal-timer bg-[#F5F3EE] px-2 py-0.5 rounded border border-[#E5E2DC] text-[11px]" data-started="{{ $activeDeal->started_at->toISOString() }}">00:00:00</span>
 </div>
 <div class="flex items-center justify-between font-mono">
 <span class="text-[#73777A] font-medium font-sans">الطلبات:</span>
 <span class="text-[#4E8F35] font-extrabold">{{ $activeDeal->order ? $activeDeal->order->items->count() : 0 }} أصناف</span>
 </div>
 </div>
 @else
 {{-- If Available: Capacity & Quick Start Button --}}
 <div class="mt-2 pt-2 border-t border-[#E5E2DC] flex items-center justify-between text-xs text-[#73777A] font-medium">
 <span>سعة {{ $room->capacity }} أفراد</span>
 <span class="text-[#4E8F35] font-extrabold hover:underline flex items-center gap-1">
 <span>+ حجز جلسة</span>
 </span>
 </div>
 @endif
 </div>
 @endforeach
 </div>
 </section>

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
 <span>إنهاء الجلسة والتحصيل والطباعة (F9)</span>
 </button>
 </div>
 </div>

 </section>

 </main>

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
 <select id="ns-customer-id" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3.5 py-2.5 text-xs focus:border-[#4E8F35] focus:bg-white outline-none">
 <option value="">-- اختر العميل من الدليل --</option>
 @foreach($customers as $c)
 <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->phone }})</option>
 @endforeach
 </select>
 </div>

 {{-- Room Selection --}}
 <div>
 <label class="block text-xs font-bold text-slate-700 mb-1.5">الغرفة أو المساحة *</label>
 <select id="ns-room-id" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3.5 py-2.5 text-xs focus:border-[#4E8F35] focus:bg-white outline-none">
 <option value="">-- اختر الغرفة --</option>
 @foreach($rooms as $r)
 <option value="{{ $r->id }}" {{ !$r->is_available ? 'disabled' : '' }}>
 {{ $r->name }} (سعة {{ $r->capacity }} أفراد) {{ !$r->is_available ? '— [مشغولة حالياً]' : '' }}
 </option>
 @endforeach
 </select>
 </div>

 {{-- Workspace Type Selection --}}
 <div>
 <label class="block text-xs font-bold text-slate-700 mb-1.5">باقة ونوع المساحة *</label>
 <select id="ns-workspace-type-id" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3.5 py-2.5 text-xs focus:border-[#4E8F35] focus:bg-white outline-none">
 @foreach($workspaceTypes as $wt)
 <option value="{{ $wt->id }}">{{ $wt->name }} (تسعير تصاعدي بالمدة)</option>
 @endforeach
 </select>
 </div>

 <div>
 <label class="block text-xs font-bold text-slate-700 mb-1.5">ملاحظات إضافية</label>
 <input type="text" id="ns-notes" placeholder="مثال: يفضل الجلوس بجوار النافذة..."
 class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3.5 py-2 text-xs focus:border-[#4E8F35] focus:bg-white outline-none">
 </div>

 <div class="flex items-center justify-end gap-2.5 pt-2 border-t border-slate-100">
 <button type="button" onclick="closeModal('modal-new-session')" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all">إلغاء</button>
 <button type="submit" class="btn-primary px-6 py-2.5 bg-[#4E8F35] hover:bg-[#3F742B] text-white rounded-xl text-xs font-black transition-all shadow-xs">
 بدء الجلسة فوراً
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
 <input type="text" id="qc-phone" required placeholder="مثال: 01012345678" dir="ltr"
 class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3.5 py-2.5 text-xs focus:border-[#4E8F35] focus:bg-white outline-none text-start">
 </div>
 <div>
 <label class="block text-xs font-bold text-slate-700 mb-1.5">البريد الإلكتروني (اختياري)</label>
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
 {{-- MODAL: PORTAL DRINKS ORDERS --}}
 <div id="modal-portal-orders" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
 <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full p-6 border border-slate-200 animate-scale-up max-h-[85vh] flex flex-col">
 <div class="flex items-center justify-between pb-3 border-b border-slate-200">
 <div class="flex items-center gap-2.5">
 <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-lg">
 
 </div>
 <div>
 <h3 class="font-black text-slate-900 text-base">طلبات المشروبات الواردة من الموبايل</h3>
 <p class="text-xs text-slate-500 font-bold">متابعة وتجهيز وتوصيل طلبات العملاء المباشرة</p>
 </div>
 </div>
 <button type="button" onclick="closeModal('modal-portal-orders')" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-xl hover:bg-slate-100">
 <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
 </button>
 </div>

 <div id="portal-orders-list" class="my-4 space-y-3 flex-1 overflow-y-auto pr-1">
 @forelse($pendingPortalOrders as $ord)
 <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 hover:bg-white transition shadow-xs flex flex-col gap-2.5" id="cashier-order-{{ $ord->id }}">
 <div class="flex items-start justify-between">
 <div>
 <span class="font-mono text-xs font-black text-[#4E8F35] bg-[#EBF4E8] px-2 py-0.5 rounded">{{ $ord->order_number }}</span>
 <span class="font-extrabold text-slate-900 text-sm mr-2">{{ $ord->customer ? $ord->customer->full_name : 'عميل' }}</span>
 <span class="text-xs text-slate-500 font-bold font-mono">({{ $ord->customer ? $ord->customer->phone : '' }})</span>
 </div>
 <span class="px-2.5 py-1 rounded-full text-xs font-black {{ $ord->fulfillment_status === 'preparing' ? 'bg-[#EBF4E8] text-[#4E8F35]' : 'bg-[#F5F3EE] text-[#303334]' }}">
 {{ $ord->fulfillment_status === 'preparing' ? 'جاري التحضير' : 'جديد ⏳' }}
 </span>
 </div>

 <div class="text-xs text-slate-700 flex items-center gap-2">
 <span class="font-bold text-slate-500">المكان / الغرفة:</span>
 <span class="font-extrabold text-slate-900 bg-slate-200 px-2 py-0.5 rounded">{{ $ord->table_or_room_name ?: 'المساحة العامة' }}</span>
 <span class="text-slate-400">• {{ $ord->created_at->diffForHumans() }}</span>
 </div>

 <div class="p-2.5 rounded-lg bg-white border border-slate-200 space-y-1">
 @foreach($ord->items as $it)
 <div class="flex justify-between text-xs">
 <span class="font-bold text-slate-800">{{ $it->name }} × {{ $it->quantity }}</span>
 <span class="font-mono font-bold text-slate-600">{{ number_format($it->total, 2) }} ج.م</span>
 </div>
 @endforeach
 @if(!empty($ord->customer_notes))
 <div class="text-[11px] text-amber-800 pt-1 border-t border-slate-100">
 <strong>ملاحظات العميل:</strong> {{ $ord->customer_notes }}
 </div>
 @endif
 </div>

 <div class="flex items-center justify-between pt-1">
 <div class="text-xs font-bold text-slate-800">
 الإجمالي: <span class="font-mono font-black text-[#4E8F35] text-sm">{{ number_format($ord->total, 2) }} ج.م</span>
 </div>
 <div class="flex items-center gap-2">
 @if($ord->fulfillment_status !== 'preparing')
 <button type="button" onclick="updateOrderStatus({{ $ord->id }}, 'preparing')"
 class="px-3 py-1.5 rounded-xl bg-[#EBF4E8] hover:bg-[#DCE8D4] text-[#4E8F35] font-bold text-xs border border-[#DCE8D4] cursor-pointer">
 تحضير 
 </button>
 @endif
 <button type="button" onclick="updateOrderStatus({{ $ord->id }}, 'delivered')"
 class="px-3.5 py-1.5 rounded-xl bg-[#4E8F35] hover:bg-[#3F742B] text-white font-bold text-xs shadow-xs cursor-pointer">
 تم التسليم للغرفة 
 </button>
 <button type="button" onclick="updateOrderStatus({{ $ord->id }}, 'cancelled')"
 class="px-2.5 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold text-xs cursor-pointer">
 إلغاء
 </button>
 </div>
 </div>
 </div>
 @empty
 <div id="no-portal-orders-msg" class="text-center py-10 text-slate-400 text-xs">
 لا توجد طلبات جديدة واردة من الموبايل حالياً.
 </div>
 @endforelse
 </div>
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
        <button type="button" onclick="openModal('modal-portal-orders'); dismissLiveToast();"
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

 function openNewSessionModal(roomId = null) {
 if (roomId) {
 document.getElementById('ns-room-id').value = roomId;
 }
 openModal('modal-new-session');
 }
 function openAddCustomerModal() { openModal('modal-add-customer'); }

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
 fetch(`/api/v1/deals/${dealId}`)
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

 fetch(`/api/v1/deals/${activeDeal.id}/items`, {
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

 fetch(`/api/v1/deals/${activeDeal.id}/items/${itemId}`, {
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

 fetch('/api/v1/deals', {
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
 const email = document.getElementById('qc-email').value;

 fetch('/api/v1/customers', {
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
 })
 })
 .then(async res => {
 const data = await res.json();
 if (!res.ok) throw new Error(data.message || 'حدث خطأ أثناء حفظ العميل.');
 return data;
 })
 .then(data => {
 closeModal('modal-add-customer');
 if (data.data) {
 const select = document.getElementById('ns-customer-id');
 const opt = document.createElement('option');
 opt.value = data.data.id;
 opt.textContent = `${data.data.name} (${data.data.phone})`;
 opt.selected = true;
 select.appendChild(opt);
 }
 alert('تم تسجيل العميل بنجاح.');
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

 fetch(`/api/v1/deals/${activeDeal.id}/close`, {
 method: 'POST',
 headers: {
 'Content-Type': 'application/json',
 'Accept': 'application/json',
 'X-CSRF-TOKEN': CSRF_TOKEN,
 }
 })
 .then(res => res.json())
 .then(closeRes => {
 const totalAmt = parseFloat(document.getElementById('co-total-amount').textContent) || 0;
 return fetch(`/api/v1/deals/${activeDeal.id}/pay`, {
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
 })
 .then(res => res.json())
 .then(payRes => {
 showPrintableReceipt();
 })
 .catch(err => {
 console.error(err);
 alert('حدث خطأ أثناء إتمام عملية التحصيل.');
 btn.disabled = false;
 btn.innerHTML = 'إنهاء الجلسة والتحصيل والطباعة (F9)';
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
 closeModal('modal-receipt');
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
 const list = document.getElementById('portal-orders-list');
 if (!list) return;

 if (orders.length === 0) {
 list.innerHTML = `<div class="text-center py-10 text-slate-400 text-xs">لا توجد طلبات جديدة واردة من الموبايل حالياً.</div>`;
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
 <strong>ملاحظات العميل:</strong> ${ord.customer_notes}
 </div>
 ` : '';

 const card = document.createElement('div');
 card.className = 'p-4 rounded-xl border border-slate-200 bg-slate-50 hover:bg-white transition shadow-xs flex flex-col gap-2.5';
 card.id = `cashier-order-${ord.id}`;
 card.innerHTML = `
 <div class="flex items-start justify-between">
 <div>
 <span class="font-mono text-xs font-black text-[#4E8F35] bg-[#EBF4E8] px-2 py-0.5 rounded">${ord.order_number}</span>
 <span class="font-extrabold text-slate-900 text-sm mr-2">${ord.customer ? ord.customer.full_name : 'عميل'}</span>
 <span class="text-xs text-slate-500 font-bold font-mono">(${ord.customer ? ord.customer.phone : ''})</span>
 </div>
 <span class="px-2.5 py-1 rounded-full text-xs font-black ${isPrep ? 'bg-[#EBF4E8] text-[#4E8F35]' : 'bg-[#F5F3EE] text-[#303334]'}">
 ${isPrep ? 'جاري التحضير' : 'جديد ⏳'}
 </span>
 </div>

 <div class="text-xs text-slate-700 flex items-center gap-2">
 <span class="font-bold text-slate-500">المكان / الغرفة:</span>
 <span class="font-extrabold text-slate-900 bg-slate-200 px-2 py-0.5 rounded">${ord.table_or_room_name || 'المساحة العامة'}</span>
 </div>

 <div class="p-2.5 rounded-lg bg-white border border-slate-200 space-y-1">
 ${itemsHtml}
 ${notesHtml}
 </div>

 <div class="flex items-center justify-between pt-1">
 <div class="text-xs font-bold text-slate-800">
 الإجمالي: <span class="font-mono font-black text-[#4E8F35] text-sm">${parseFloat(ord.total).toFixed(2)} ج.م</span>
 </div>
 <div class="flex items-center gap-2">
 ${!isPrep ? `
 <button type="button" onclick="updateOrderStatus(${ord.id}, 'preparing')"
 class="px-3 py-1.5 rounded-xl bg-[#EBF4E8] hover:bg-[#DCE8D4] text-[#4E8F35] font-bold text-xs border border-[#DCE8D4] cursor-pointer">
 تحضير 
 </button>
 ` : ''}
 <button type="button" onclick="updateOrderStatus(${ord.id}, 'delivered')"
 class="px-3.5 py-1.5 rounded-xl bg-[#4E8F35] hover:bg-[#3F742B] text-white font-bold text-xs shadow-xs cursor-pointer">
 تم التسليم للغرفة 
 </button>
 <button type="button" onclick="updateOrderStatus(${ord.id}, 'cancelled')"
 class="px-2.5 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold text-xs cursor-pointer">
 إلغاء
 </button>
 </div>
 </div>
 `;
 list.appendChild(card);
 });
 }

 function updateOrderStatus(orderId, status) {
 fetch(`/cashier/orders/${orderId}/fulfillment`, {
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

 // Start polling every 8 seconds
 setInterval(pollPortalOrders, 15000);
 </script>
</body>
</html>
