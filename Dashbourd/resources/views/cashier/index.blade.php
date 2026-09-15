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
                            DEFAULT: '#4f46e5',
                            hover: '#4338ca',
                            light: '#eef2ff',
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca'
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
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: #eef2ff;
        }

        body {
            background-color: #f8fafc;
            color: #0f172a;
            height: 100vh;
            width: 100vw;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            user-select: none;
        }

        /* Solid explicit fallback classes for buttons and active states */
        .btn-primary, .bg-primary {
            background-color: #4f46e5 !important;
            color: #ffffff !important;
        }
        .btn-primary:hover, .hover\:bg-primary:hover {
            background-color: #4338ca !important;
            color: #ffffff !important;
        }
        .text-primary {
            color: #4f46e5 !important;
        }
        .border-primary {
            border-color: #4f46e5 !important;
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
    {{-- 1. TOP STATUS BAR (الهيدر العلوي الأبيض العصري)          --}}
    {{-- ════════════════════════════════════════════════════════ --}}
    <header class="h-16 bg-white border-b border-slate-200 px-4 md:px-6 flex items-center justify-between gap-3 shrink-0 z-20 shadow-sm">
        {{-- Brand & System Name --}}
        <div class="flex items-center gap-3.5">
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 text-decoration-none group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-indigo-500 flex items-center justify-center text-white font-black shadow-md shadow-indigo-500/30 group-hover:scale-105 transition-transform">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/>
                    </svg>
                </div>
                <div class="flex flex-col text-start leading-none">
                    <span class="font-black text-base text-slate-900 tracking-tight">DT-SYSTEM</span>
                    <span class="text-[11px] text-slate-500 font-bold mt-1">كاشير ونقاط بيع مساحة العمل</span>
                </div>
            </a>

            <div class="h-6 w-px bg-slate-200 mx-2 hidden sm:block"></div>

            {{-- Shift Status Badge --}}
            @if($currentShift)
                <a href="{{ url('/shifts/current') }}" target="_blank" title="عرض تفاصيل الوردية والدرج"
                   class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-300 text-emerald-800 text-xs font-bold hover:bg-emerald-100 transition-all shadow-xs">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>وردية مفتوحة #{{ $currentShift->id }}</span>
                    <span class="text-emerald-700 font-bold text-[11px]">({{ $currentShift->user->name ?? 'Admin' }})</span>
                </a>
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
                       class="w-full bg-slate-100/80 border border-slate-200 text-slate-900 text-xs font-semibold rounded-xl px-3.5 py-2 pe-9 focus:border-indigo-600 focus:bg-white focus:ring-2 focus:ring-indigo-100 outline-none transition-all placeholder:text-slate-400">
                <span class="absolute end-3 top-2.5 text-slate-400">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/>
                    </svg>
                </span>
            </div>

            {{-- Live Clock --}}
            <div class="hidden lg:flex items-center gap-2 font-mono text-xs font-black text-slate-700 bg-slate-100/80 px-3.5 py-2 rounded-xl border border-slate-200">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                <span id="pos-live-clock">--:--:--</span>
            </div>
        </div>

        {{-- Top Right Actions --}}
        <div class="flex items-center gap-2.5">
            {{-- + جلسة جديدة --}}
            <button type="button" onclick="openNewSessionModal()"
                    class="btn-primary inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-extrabold transition-all shadow-md shadow-indigo-500/25 cursor-pointer">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/>
                </svg>
                <span>+ جلسة جديدة (F2)</span>
            </button>

            {{-- + عميل جديد --}}
            <button type="button" onclick="openAddCustomerModal()"
                    class="inline-flex items-center gap-2 px-3.5 py-2 bg-white hover:bg-slate-50 border border-slate-300 text-slate-800 rounded-xl text-xs font-bold transition-all shadow-xs cursor-pointer">
                <svg width="16" height="16" class="text-indigo-600" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/>
                </svg>
                <span class="hidden md:inline">عميل جديد (F3)</span>
            </button>

            {{-- Return to Dashboard --}}
            <a href="{{ url('/') }}" title="العودة للوحة التحكم الرئيسية"
               class="p-2 rounded-xl bg-white hover:bg-slate-50 border border-slate-300 text-slate-600 hover:text-slate-900 transition-all shadow-xs">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/>
                </svg>
            </a>
        </div>
    </header>

    {{-- ════════════════════════════════════════════════════════ --}}
    {{-- 2. MAIN THREE-PANEL POS WORKSPACE                        --}}
    {{-- ════════════════════════════════════════════════════════ --}}
    <main class="flex-1 flex overflow-hidden">

        {{-- ── COLUMN 1 (RIGHT): الغرف والمساحات المباشرة ── --}}
        <section class="w-80 bg-white border-s border-slate-200 flex flex-col shrink-0">
            {{-- Header & Filter Tabs --}}
            <div class="p-3.5 border-b border-slate-200 bg-slate-50/70">
                <div class="flex items-center justify-between mb-2.5">
                    <h3 class="text-xs font-black text-slate-900 flex items-center gap-2">
                        <svg width="16" height="16" class="text-indigo-600" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M13 4h3a2 2 0 0 1 2 2v14"/><path d="M2 20h20"/><path d="M13 20V4a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v16"/>
                        </svg>
                        <span>الغرف والمساحات</span>
                    </h3>
                    <span id="pos-rooms-summary" class="text-[11px] font-mono text-slate-600 font-extrabold bg-slate-200/60 px-2 py-0.5 rounded-md">
                        {{ $rooms->where('is_available', false)->count() }} مشغولة / {{ $rooms->count() }}
                    </span>
                </div>

                {{-- Filter Tabs --}}
                <div class="flex items-center gap-1 p-1 bg-slate-200/60 rounded-xl text-xs font-bold">
                    <button type="button" onclick="filterRooms('all')" id="tab-room-all"
                            class="room-filter-tab flex-1 py-1.5 rounded-lg text-center bg-indigo-600 text-white shadow-xs font-extrabold transition-all cursor-pointer">
                        الكل
                    </button>
                    <button type="button" onclick="filterRooms('available')" id="tab-room-available"
                            class="room-filter-tab flex-1 py-1.5 rounded-lg text-center text-slate-600 hover:text-slate-900 font-bold transition-all cursor-pointer">
                        متاحة
                    </button>
                    <button type="button" onclick="filterRooms('occupied')" id="tab-room-occupied"
                            class="room-filter-tab flex-1 py-1.5 rounded-lg text-center text-slate-600 hover:text-slate-900 font-bold transition-all cursor-pointer">
                        مشغولة
                    </button>
                </div>
            </div>

            {{-- Rooms List / Grid --}}
            <div class="flex-1 overflow-y-auto p-3 space-y-2.5" id="pos-rooms-list">
                @foreach($rooms as $room)
                    @php
                        $activeDeal = $room->activeDeals->first();
                    @endphp
                    <div class="room-card p-3.5 rounded-2xl border transition-all cursor-pointer relative overflow-hidden {{ $activeDeal ? 'bg-amber-50/60 border-amber-300 hover:border-amber-500 shadow-sm' : 'bg-white border-slate-200 hover:border-indigo-400 hover:shadow-md' }}"
                         data-room-id="{{ $room->id }}"
                         data-room-name="{{ $room->name }}"
                         data-room-capacity="{{ $room->capacity }}"
                         data-status="{{ $activeDeal ? 'occupied' : 'available' }}"
                         data-deal-id="{{ $activeDeal ? $activeDeal->id : '' }}"
                         onclick="handleRoomClick({{ $room->id }}, '{{ $activeDeal ? 'occupied' : 'available' }}', {{ $activeDeal ? $activeDeal->id : 'null' }})">

                        {{-- Card Header --}}
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full {{ $activeDeal ? 'bg-amber-500 animate-pulse' : 'bg-emerald-500' }}"></span>
                                <span class="font-extrabold text-xs text-slate-900">{{ $room->name }}</span>
                            </div>
                            <span class="text-[11px] font-black px-2.5 py-0.5 rounded-full {{ $activeDeal ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                                {{ $activeDeal ? 'مشغولة' : 'متاحة' }}
                            </span>
                        </div>

                        {{-- If Occupied: Customer & Time --}}
                        @if($activeDeal)
                            <div class="mt-2 pt-2 border-t border-amber-200/80 text-xs space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-500 font-medium">العميل:</span>
                                    <strong class="text-slate-900 font-extrabold">{{ $activeDeal->customer->name ?? 'عميل مباشر' }}</strong>
                                </div>
                                <div class="flex items-center justify-between font-mono">
                                    <span class="text-slate-500 font-medium font-sans">المدة:</span>
                                    <span class="text-rose-700 font-black active-deal-timer bg-rose-50 px-2 py-0.5 rounded border border-rose-200 text-[11px]" data-started="{{ $activeDeal->started_at->toISOString() }}">00:00:00</span>
                                </div>
                                <div class="flex items-center justify-between font-mono">
                                    <span class="text-slate-500 font-medium font-sans">الطلبات:</span>
                                    <span class="text-indigo-700 font-extrabold">{{ $activeDeal->order ? $activeDeal->order->items->count() : 0 }} أصناف</span>
                                </div>
                            </div>
                        @else
                            {{-- If Available: Capacity & Quick Start Button --}}
                            <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                                <span>سعة {{ $room->capacity }} أفراد</span>
                                <span class="text-indigo-600 font-extrabold hover:underline flex items-center gap-1">
                                    <span>+ حجز جلسة</span>
                                </span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>

        {{-- ── COLUMN 2 (CENTER): قائمة البوفيه والمشروبات (Cafe & Products) ── --}}
        <section class="flex-1 bg-[#f8fafc] flex flex-col overflow-hidden border-s border-slate-200">
            {{-- Toolbar: Category Filters & Search --}}
            <div class="p-3.5 bg-white border-b border-slate-200 flex flex-wrap items-center justify-between gap-3 shrink-0 shadow-2xs">
                {{-- Categories Tabs --}}
                <div class="flex items-center gap-1.5 overflow-x-auto text-xs font-bold" id="product-category-tabs">
                    <button type="button" onclick="filterProductsByCode('all', this)"
                            class="prod-cat-tab px-3.5 py-1.5 rounded-xl bg-indigo-600 text-white font-extrabold shadow-sm transition-all cursor-pointer">
                        الكل
                    </button>
                    <button type="button" onclick="filterProductsByCode('hot_drinks', this)"
                            class="prod-cat-tab px-3.5 py-1.5 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 font-bold transition-all cursor-pointer flex items-center gap-1.5">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 8h1a4 4 0 1 1 0 8h-1"/><path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z"/><line x1="6" x2="6" y1="2" y2="4"/><line x1="10" x2="10" y1="2" y2="4"/><line x1="14" x2="14" y1="2" y2="4"/></svg>
                        <span>مشروبات ساخنة</span>
                    </button>
                    <button type="button" onclick="filterProductsByCode('cold_drinks', this)"
                            class="prod-cat-tab px-3.5 py-1.5 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 font-bold transition-all cursor-pointer flex items-center gap-1.5">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 22h8"/><path d="M12 11v11"/><path d="m19 3-2 8H7L5 3Z"/></svg>
                        <span>مشروبات باردة</span>
                    </button>
                    <button type="button" onclick="filterProductsByCode('snacks', this)"
                            class="prod-cat-tab px-3.5 py-1.5 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 font-bold transition-all cursor-pointer flex items-center gap-1.5">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><line x1="3" x2="21" y1="6" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                        <span>سناكس ومأكولات</span>
                    </button>
                    <button type="button" onclick="filterProductsByCode('services', this)"
                            class="prod-cat-tab px-3.5 py-1.5 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 font-bold transition-all cursor-pointer flex items-center gap-1.5">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                        <span>طباعة وخدمات</span>
                    </button>
                </div>

                {{-- Product Search --}}
                <div class="relative w-52">
                    <input type="text" id="pos-product-search" placeholder="ابحث عن صنف في الكافيه..."
                           class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-xs rounded-xl px-3 py-1.5 pe-8 focus:border-indigo-600 focus:bg-white outline-none placeholder:text-slate-400">
                    <span class="absolute end-2.5 top-2 text-slate-400">
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
                        <div class="product-item-card p-3.5 rounded-2xl bg-white hover:bg-slate-50 border border-slate-200 hover:border-indigo-400 transition-all flex flex-col justify-between cursor-pointer group select-none shadow-xs hover:shadow-md transform hover:-translate-y-0.5"
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
                                <h4 class="font-extrabold text-xs text-slate-900 group-hover:text-indigo-600 transition-colors leading-snug line-clamp-2">
                                    {{ $prod->name }}
                                </h4>
                                <span class="text-[10px] text-slate-400 font-medium mt-0.5 block">
                                    {{ $prod->category->name ?? 'بوفيه' }}
                                </span>
                            </div>

                            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-xs font-black text-emerald-700 font-mono">
                                    {{ number_format($prod->price, 2) }} <span class="text-[10px] font-normal text-slate-400">ج.م</span>
                                </span>
                                <span class="w-7 h-7 rounded-lg bg-indigo-50 group-hover:bg-indigo-600 text-indigo-600 group-hover:text-white flex items-center justify-center text-sm font-black transition-all shadow-2xs">
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
        <section class="w-96 bg-white border-s border-slate-200 flex flex-col shrink-0 shadow-sm">

            {{-- 1. No Session Selected Placeholder --}}
            <div id="checkout-placeholder" class="flex-1 flex flex-col items-center justify-center p-8 text-center text-slate-500">
                <div class="w-20 h-20 rounded-3xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 mb-4 shadow-sm">
                    <svg width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>
                    </svg>
                </div>
                <h4 class="text-base font-black text-slate-900 mb-1.5">لم يتم تحديد جلسة</h4>
                <p class="text-xs text-slate-500 leading-relaxed mb-6 max-w-xs font-medium">
                    اختر أي غرفة مشغولة من القائمة اليمنى لعرض ومتابعة الحساب، أو انقر على الزر أدناه لبدء حساب جديد.
                </p>
                <button type="button" onclick="openNewSessionModal()"
                        class="btn-primary inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-black transition-all shadow-md shadow-indigo-500/30 cursor-pointer">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/>
                    </svg>
                    <span>+ بدء جلسة جديدة الآن (F2)</span>
                </button>
            </div>

            {{-- 2. Active Session Checkout Panel --}}
            <div id="checkout-panel" class="hidden flex-1 flex flex-col overflow-hidden">
                {{-- Session Header Banner --}}
                <div class="p-4 bg-gradient-to-r from-indigo-50/70 via-white to-slate-50 border-b border-slate-200 flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-2xl bg-indigo-600 text-white font-black text-base flex items-center justify-center shadow-sm shrink-0" id="co-customer-avatar">
                            ع
                        </div>
                        <div>
                            <h4 class="font-black text-sm text-slate-900 leading-tight" id="co-customer-name">أحمد محمد</h4>
                            <div class="flex items-center gap-2 text-xs text-slate-500 mt-1">
                                <span class="text-indigo-600 font-extrabold bg-indigo-50 px-2 py-0.5 rounded-md border border-indigo-100" id="co-room-name">قاعة A</span>
                                <span>•</span>
                                <span class="font-mono text-rose-700 font-black bg-rose-50 px-2 py-0.5 rounded border border-rose-200" id="co-timer-display">00:00:00</span>
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
                        <span class="font-black text-2xl text-emerald-600 font-mono" id="co-total-amount">
                            0.00 <span class="text-xs font-normal text-slate-500">ج.م</span>
                        </span>
                    </div>
                </div>

                {{-- Payment Methods & Fast Change --}}
                <div class="p-4 bg-white border-t border-slate-200 shrink-0 space-y-3">
                    {{-- Payment Method Buttons --}}
                    <div class="grid grid-cols-3 gap-2 text-center text-xs font-extrabold">
                        <button type="button" onclick="selectPaymentMethod('cash')" id="btn-pay-cash"
                                class="pay-method-btn py-2.5 rounded-xl border-2 border-emerald-500 bg-emerald-50 text-emerald-800 transition-all cursor-pointer flex items-center justify-center gap-1.5">
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
                        <div class="flex items-center justify-between text-xs px-3 py-2 bg-emerald-50 rounded-xl border border-emerald-300">
                            <span class="text-emerald-800 font-bold">الباقي للعميل:</span>
                            <span class="font-black font-mono text-emerald-800" id="co-change-amount">0.00 ج.م</span>
                        </div>
                    </div>

                    {{-- Big Close & Pay Button --}}
                    <button type="button" onclick="executeCheckoutAndPay()" id="btn-submit-checkout"
                            class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-sm rounded-xl transition-all shadow-lg shadow-emerald-600/30 flex items-center justify-center gap-2 cursor-pointer">
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
    {{-- 3. MODALS (النوافذ المنبثقة بالستايل الفاتح النظيف)     --}}
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
                            <button type="button" onclick="openAddCustomerModal()" class="text-xs font-bold text-indigo-600 hover:underline">+ عميل جديد</button>
                        </div>
                        <select id="ns-customer-id" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3.5 py-2.5 text-xs focus:border-indigo-600 focus:bg-white outline-none">
                            <option value="">-- اختر العميل من الدليل --</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->phone }})</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Room Selection --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">الغرفة أو المساحة *</label>
                        <select id="ns-room-id" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3.5 py-2.5 text-xs focus:border-indigo-600 focus:bg-white outline-none">
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
                        <select id="ns-workspace-type-id" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3.5 py-2.5 text-xs focus:border-indigo-600 focus:bg-white outline-none">
                            @foreach($workspaceTypes as $wt)
                                <option value="{{ $wt->id }}">{{ $wt->name }} (تسعير تصاعدي بالمدة)</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">ملاحظات إضافية</label>
                        <input type="text" id="ns-notes" placeholder="مثال: يفضل الجلوس بجوار النافذة..."
                               class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3.5 py-2 text-xs focus:border-indigo-600 focus:bg-white outline-none">
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-2 border-t border-slate-100">
                        <button type="button" onclick="closeModal('modal-new-session')" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all">إلغاء</button>
                        <button type="submit" class="btn-primary px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-black transition-all shadow-md shadow-indigo-500/25">
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
                               class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3.5 py-2.5 text-xs focus:border-indigo-600 focus:bg-white outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">رقم الهاتف *</label>
                        <input type="text" id="qc-phone" required placeholder="مثال: 01012345678" dir="ltr"
                               class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3.5 py-2.5 text-xs focus:border-indigo-600 focus:bg-white outline-none text-start">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">البريد الإلكتروني (اختياري)</label>
                        <input type="email" id="qc-email" placeholder="client@example.com" dir="ltr"
                               class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3.5 py-2 text-xs focus:border-indigo-600 focus:bg-white outline-none text-start">
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-2 border-t border-slate-100">
                        <button type="button" onclick="closeModal('modal-add-customer')" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold">إلغاء</button>
                        <button type="submit" class="btn-primary px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-black shadow-md shadow-indigo-500/25">
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
                <button type="button" onclick="window.print()" class="btn-primary px-4 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold flex items-center gap-1 shadow-md shadow-indigo-500/25">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                    <span>طباعة الفاتورة</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════ --}}
    {{-- 4. JAVASCRIPT STATE ENGINE & WORKFLOW                    --}}
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
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                        <div>
                            <div class="font-extrabold text-slate-900">حساب مدة الجلسة</div>
                            <div class="text-[10px] text-slate-400 font-medium">${activeDeal.workspace_type || 'مكتب مشترك'}</div>
                        </div>
                    </div>
                    <div class="font-black text-indigo-700 font-mono text-xs" id="item-time-price">-- ج.م</div>
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
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
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
                t.classList.remove('bg-indigo-600', 'text-white', 'shadow-xs', 'font-extrabold');
                t.classList.add('text-slate-600', 'font-bold');
            });
            const activeTab = document.getElementById(`tab-room-${filter}`);
            if (activeTab) {
                activeTab.classList.remove('text-slate-600', 'font-bold');
                activeTab.classList.add('bg-indigo-600', 'text-white', 'shadow-xs', 'font-extrabold');
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
                t.classList.remove('bg-indigo-600', 'text-white', 'font-extrabold', 'shadow-sm');
                t.classList.add('bg-slate-100', 'text-slate-700', 'font-bold');
            });
            if (tabBtn) {
                tabBtn.classList.remove('bg-slate-100', 'text-slate-700', 'font-bold');
                tabBtn.classList.add('bg-indigo-600', 'text-white', 'font-extrabold', 'shadow-sm');
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
    </script>
</body>
</html>
