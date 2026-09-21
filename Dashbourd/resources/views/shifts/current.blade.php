@extends('shared.vertical', ['title' => 'الوردية الحالية وتسليم الكاشير — DT-System'])

@section('content')

    {{-- Page Header --}}
    <div class="page-header-container mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                @if($shift)
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="size-2 rounded-full bg-emerald-500 animate-ping"></span>
                        وردية مفتوحة ونشطة
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                        الوردية مغلقة
                    </span>
                @endif
                <span class="text-xs text-[#73777A]">الوقت الحالي: {{ now()->format('h:i A') }}</span>
            </div>
            <h1 class="text-2xl font-extrabold text-[#303334] tracking-tight">
                الوردية الحالية وتسليم الكاشير
            </h1>
            <p class="text-xs text-[#73777A] mt-1">متابعة دقيقة للنقدية والمتحصلات، العملاء غير المحاسبين، وتسليم الوردية</p>
        </div>

        {{-- Action Buttons --}}
        <div class="page-header-actions flex items-center gap-2.5">
            <a href="{{ url('/cashier') }}" target="_blank"
               class="px-4 py-2.5 bg-[#4E8F35] hover:bg-[#3F742B] text-white rounded-xl text-xs font-bold transition-all shadow-xs gap-2 flex items-center cursor-pointer">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/>
                </svg>
                <span>شاشة الكاشير (POS) ↗</span>
            </a>
            @if($shift)
                <button type="button" onclick="document.getElementById('close-shift-modal').classList.remove('hidden')"
                        class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition-all shadow-xs gap-2 flex items-center cursor-pointer">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/>
                    </svg>
                    <span>تقفيل وتسليم الوردية</span>
                </button>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 rounded-xl bg-[#EBF4E8] border border-[#DCE8D4] text-[#4E8F35] text-xs font-bold flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-[#4E8F35] hover:opacity-75">✕</button>
        </div>
    @endif

    @if(session('warning'))
        <div class="mb-4 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-bold flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
                <span>{{ session('warning') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-amber-800 hover:opacity-75">✕</button>
        </div>
    @endif

    @if($shift)
        {{-- Active Shift Banner --}}
        <div class="bg-white rounded-2xl border border-[#E5E2DC] p-5 mb-6 shadow-xs bg-gradient-to-r from-white via-white to-[#FAF9F5]">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="size-14 rounded-2xl bg-orange-500 text-white flex items-center justify-center shadow-md shadow-orange-500/20 shrink-0">
                        <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 8 14"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                <span class="size-2 rounded-full bg-emerald-500 animate-ping"></span>
                                وردية نشطة #{{ $shift->id }}
                            </span>
                            <span class="text-xs text-[#73777A] font-mono">المسؤول: <strong class="text-[#303334]">{{ $shift->user->name ?? 'الكاشير' }}</strong></span>
                        </div>
                        <p class="text-xs text-[#73777A] mt-1">
                            بدأت في: <strong class="font-mono text-[#303334]">{{ $shift->opened_at->format('Y-m-d — h:i A') }}</strong>
                            <span class="text-emerald-700 font-bold">({{ $shift->opened_at->diffForHumans() }})</span>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="bg-[#FAF9F5] px-4 py-3 rounded-xl border border-[#E5E2DC] text-start md:text-end">
                        <span class="text-[11px] font-bold text-[#73777A] block uppercase">النقدية المتوقعة بالدرج</span>
                        <div class="text-2xl font-black text-[#4E8F35] font-mono mt-0.5">
                            {{ number_format($stats['expected_cash'], 2) }}
                            <span class="text-xs font-bold text-[#73777A]">ج.م</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 5 Metric Cards (شامل كرت العملاء غير المحاسبين) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5 mb-6">
            {{-- Opening Cash --}}
            <div class="p-4 rounded-2xl bg-white border border-[#E5E2DC] shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-[#73777A] block">عهدة بداية الوردية</span>
                    <div class="text-xl font-black text-[#303334] font-mono mt-1">
                        {{ number_format($stats['opening_cash'], 2) }} <span class="text-[10px] font-normal text-[#73777A]">ج.م</span>
                    </div>
                </div>
                <div class="size-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/>
                    </svg>
                </div>
            </div>

            {{-- Cash Sales --}}
            <div class="p-4 rounded-2xl bg-white border border-[#E5E2DC] shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-[#73777A] block">مبيعات نقدية (كاش)</span>
                    <div class="text-xl font-black text-[#4E8F35] font-mono mt-1">
                        {{ number_format($stats['cash_sales'], 2) }} <span class="text-[10px] font-normal text-[#73777A]">ج.م</span>
                    </div>
                </div>
                <div class="size-10 rounded-xl bg-[#EBF4E8] text-[#4E8F35] flex items-center justify-center shrink-0">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><path d="M12 18V6"/>
                    </svg>
                </div>
            </div>

            {{-- Electronic Sales --}}
            <div class="p-4 rounded-2xl bg-white border border-[#E5E2DC] shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-[#73777A] block">إلكتروني (إنستاباي/محافظ)</span>
                    <div class="text-xl font-black text-indigo-600 font-mono mt-1">
                        {{ number_format($stats['instapay_sales'] + $stats['wallet_sales'] + $stats['card_sales'], 2) }} <span class="text-[10px] font-normal text-[#73777A]">ج.م</span>
                    </div>
                </div>
                <div class="size-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>
                    </svg>
                </div>
            </div>

            {{-- Total Shift Revenue --}}
            <div class="p-4 rounded-2xl bg-white border border-[#E5E2DC] shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-[#73777A] block">إجمالي تحصيل الوردية</span>
                    <div class="text-xl font-black text-purple-600 font-mono mt-1">
                        {{ number_format($stats['total_sales'], 2) }} <span class="text-[10px] font-normal text-[#73777A]">ج.م</span>
                    </div>
                </div>
                <div class="size-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/>
                    </svg>
                </div>
            </div>

            {{-- العملاء اللسا محسبوش (Metric Card) --}}
            <div class="p-4 rounded-2xl bg-white border border-amber-200 shadow-xs flex items-center justify-between bg-gradient-to-b from-amber-50/30 via-white to-white">
                <div>
                    <span class="text-[11px] font-bold text-amber-800 block">عملاء لم يُحاسبوا بعد</span>
                    <div class="text-xl font-black text-amber-600 font-mono mt-1">
                        {{ $totalUnpaidCount }} <span class="text-xs font-bold text-[#303334]">عميل</span>
                    </div>
                    <div class="text-[10px] text-[#73777A] mt-0.5">
                        {{ $unpaidOpenDeals->count() }} جلسة مفتوحة | {{ $unpaidClosedDeals->count() }} آجل
                    </div>
                </div>
                <div class="size-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- ───────────────────────────────────────────────────────────── --}}
        {{-- كارت إضافي لعرض العملاء اللسا محسبوش (الجلسات والحسابات المعلقة) --}}
        {{-- ───────────────────────────────────────────────────────────── --}}
        <div class="bg-white rounded-2xl border border-[#E5E2DC] p-5 mb-6 shadow-xs" id="unpaid-customers-card">
            {{-- Header --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4 pb-3 border-b border-[#F0EDE6]">
                <div class="flex items-center gap-3">
                    <div class="size-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-200 shrink-0">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-base font-extrabold text-[#303334]">العملاء الذين لم يُحاسبوا بعد</h3>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-mono font-bold bg-amber-100 text-amber-800">
                                {{ $totalUnpaidCount }} عميل معلق
                            </span>
                        </div>
                        <p class="text-xs text-[#73777A] mt-0.5">
                            رواد المساحة المتواجدون حالياً في جلسات مفتوحة وفواتير الآجل المطلوب تصفيتها قبل إقفال الوردية
                        </p>
                    </div>
                </div>

                {{-- Fast Filter Buttons & Search --}}
                <div class="flex items-center gap-2">
                    <div class="relative w-full sm:w-56">
                        <input type="text" id="unpaid-search" onkeyup="filterUnpaidTable()"
                               placeholder="بحث باسم العميل أو الغرفة..."
                               class="w-full px-3 py-2 pe-8 border border-[#E5E2DC] rounded-xl text-xs focus:border-[#4E8F35] outline-none">
                        <div class="absolute end-2.5 top-2 text-[#73777A]">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Unpaid Customers Table --}}
            @if($totalUnpaidCount > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-start text-xs border-collapse" id="table-unpaid-customers">
                        <thead>
                            <tr class="border-b border-[#E5E2DC] text-[#73777A] text-[11px] font-black uppercase">
                                <th class="py-2.5 px-3 text-start">العميل</th>
                                <th class="py-2.5 px-3 text-start">الغرفة / المساحة</th>
                                <th class="py-2.5 px-3 text-start">وقت البدء / الحضور</th>
                                <th class="py-2.5 px-3 text-start">طلبات الكافيه</th>
                                <th class="py-2.5 px-3 text-center">نوع الجلسة والحالة</th>
                                <th class="py-2.5 px-3 text-end">إجراء المحاسبة</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#F0EDE6]">
                            {{-- 1. Active Open Deals --}}
                            @foreach($unpaidOpenDeals as $deal)
                                @php
                                    $custName = $deal->customer ? ($deal->customer->full_name ?: $deal->customer->name) : 'عميل مباشر';
                                    $custInitials = $deal->customer ? $deal->customer->initials : 'ع';
                                    $roomName = $deal->room ? $deal->room->name : 'مساحة مفتوحة';
                                    $cafeOrdersCount = $deal->order ? $deal->order->items->count() : 0;
                                    $cafeTotal = $deal->order ? $deal->order->total : 0;
                                @endphp
                                <tr class="hover:bg-[#FAF9F5] transition-colors unpaid-row"
                                    data-customer="{{ $custName }}" data-room="{{ $roomName }}">
                                    {{-- Customer --}}
                                    <td class="py-3 px-3">
                                        <div class="flex items-center gap-2.5">
                                            <div class="size-8 rounded-full bg-[#EBF4E8] text-[#4E8F35] font-black text-xs flex items-center justify-center shrink-0 border border-[#DCE8D4]">
                                                {{ $custInitials ?: 'ع' }}
                                            </div>
                                            <div>
                                                <div class="font-extrabold text-[#303334] text-xs">{{ $custName }}</div>
                                                @if($deal->customer && $deal->customer->phone)
                                                    <span class="font-mono text-[10px] text-[#73777A]" dir="ltr">{{ $deal->customer->phone }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Room --}}
                                    <td class="py-3 px-3">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-[#F5F3EE] text-[#303334] border border-[#E5E2DC]">
                                            {{ $roomName }}
                                        </span>
                                    </td>

                                    {{-- Started Time & Duration --}}
                                    <td class="py-3 px-3">
                                        <div class="font-mono text-xs font-bold text-[#303334]">
                                            {{ $deal->started_at ? $deal->started_at->format('h:i A') : '—' }}
                                        </div>
                                        <div class="text-[11px] text-[#4E8F35] font-bold">
                                            {{ $deal->started_at ? $deal->started_at->diffForHumans(null, true) : '-' }}
                                        </div>
                                    </td>

                                    {{-- Cafe Orders --}}
                                    <td class="py-3 px-3">
                                        @if($cafeOrdersCount > 0)
                                            <span class="inline-flex items-center gap-1 font-mono font-bold text-xs text-amber-700">
                                                <span>{{ $cafeOrdersCount }} أصناف</span>
                                                <span class="text-[11px]">({{ number_format($cafeTotal, 2) }} ج.م)</span>
                                            </span>
                                        @else
                                            <span class="text-[11px] text-[#73777A]">بدون طلبات</span>
                                        @endif
                                    </td>

                                    {{-- Status Badge --}}
                                    <td class="py-3 px-3 text-center">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="size-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                                            جلسة جارية (مفتوحة)
                                        </span>
                                    </td>

                                    {{-- Action: Cashier Link --}}
                                    <td class="py-3 px-3 text-end">
                                        <a href="{{ url('/cashier?deal_id=' . $deal->id) }}" target="_blank"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#4E8F35] hover:bg-[#3F742B] text-white rounded-lg text-xs font-bold transition-all shadow-xs">
                                            <span>محاسبة بالكاشير</span>
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="5" x2="19" y1="12" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach

                            {{-- 2. Unpaid Closed Deals (Debt / الآجل) --}}
                            @foreach($unpaidClosedDeals as $deal)
                                @php
                                    $custName = $deal->customer ? ($deal->customer->full_name ?: $deal->customer->name) : 'عميل مباشر';
                                    $custInitials = $deal->customer ? $deal->customer->initials : 'ع';
                                    $roomName = $deal->room ? $deal->room->name : 'مساحة عامة';
                                    $remaining = $deal->order ? $deal->order->remaining_amount : 0;
                                @endphp
                                <tr class="hover:bg-rose-50/30 transition-colors unpaid-row bg-rose-50/10"
                                    data-customer="{{ $custName }}" data-room="{{ $roomName }}">
                                    {{-- Customer --}}
                                    <td class="py-3 px-3">
                                        <div class="flex items-center gap-2.5">
                                            <div class="size-8 rounded-full bg-rose-50 text-rose-600 font-black text-xs flex items-center justify-center shrink-0 border border-rose-200">
                                                {{ $custInitials ?: 'ع' }}
                                            </div>
                                            <div>
                                                <div class="font-extrabold text-[#303334] text-xs">{{ $custName }}</div>
                                                @if($deal->customer && $deal->customer->phone)
                                                    <span class="font-mono text-[10px] text-[#73777A]" dir="ltr">{{ $deal->customer->phone }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Room --}}
                                    <td class="py-3 px-3">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-[#F5F3EE] text-[#303334] border border-[#E5E2DC]">
                                            {{ $roomName }}
                                        </span>
                                    </td>

                                    {{-- Started / Ended Time --}}
                                    <td class="py-3 px-3">
                                        <div class="text-[11px] text-[#73777A]">انتهت في:</div>
                                        <div class="font-mono text-xs font-bold text-[#303334]">
                                            {{ $deal->ended_at ? $deal->ended_at->format('Y-m-d h:i A') : '—' }}
                                        </div>
                                    </td>

                                    {{-- Remaining Debt --}}
                                    <td class="py-3 px-3">
                                        <span class="font-mono font-black text-rose-600 text-xs">
                                            {{ number_format($remaining, 2) }} ج.م متبقي
                                        </span>
                                    </td>

                                    {{-- Status Badge --}}
                                    <td class="py-3 px-3 text-center">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            حساب آجل معلق
                                        </span>
                                    </td>

                                    {{-- Action --}}
                                    <td class="py-3 px-3 text-end">
                                        <a href="{{ url('/cashier?deal_id=' . $deal->id) }}" target="_blank"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-bold transition-all shadow-xs">
                                            <span>تحصيل الآجل</span>
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="5" x2="19" y1="12" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-8 text-center bg-[#FAF9F5] rounded-xl border border-dashed border-[#DCD8D0]">
                    <div class="size-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-2 border border-emerald-200">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                    </div>
                    <h4 class="text-xs font-black text-[#303334]">جميع الحسابات والعملاء تم محاسبتهم وتصفيتهم بنجاح</h4>
                    <p class="text-[11px] text-[#73777A] mt-0.5">لا توجد أي جلسات معلقة أو مبالغ آجلة غير محصلة حالياً.</p>
                </div>
            @endif
        </div>

        {{-- ───────────────────────────────────────────────────────────── --}}
        {{-- سجل عمليات ومبيعات الوردية الحالية --}}
        {{-- ───────────────────────────────────────────────────────────── --}}
        <div class="bg-white rounded-2xl border border-[#E5E2DC] p-5 shadow-xs">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-[#F0EDE6]">
                <div>
                    <h3 class="text-base font-bold text-[#303334]">سجل عمليات الوردية الحالية</h3>
                    <p class="text-xs text-[#73777A]">جميع المبالغ المحصلة وفواتير الكاشير خلال هذه الوردية</p>
                </div>
                <span class="text-xs bg-[#F5F3EE] text-[#303334] px-3 py-1 rounded-full font-bold font-mono border border-[#E5E2DC]">
                    {{ $payments->count() }} معاملة محصلة
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-start text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-[#E5E2DC] text-[#73777A] text-[11px] font-black uppercase">
                            <th class="py-2.5 px-3 text-start">رقم المعاملة</th>
                            <th class="py-2.5 px-3 text-start">العميل / الغرفة</th>
                            <th class="py-2.5 px-3 text-start">طريقة الدفع</th>
                            <th class="py-2.5 px-3 text-start">المبلغ</th>
                            <th class="py-2.5 px-3 text-end">الوقت</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F0EDE6]">
                        @forelse($payments as $pay)
                            <tr class="hover:bg-[#FAF9F5] transition-colors">
                                <td class="py-3 px-3 font-mono font-bold text-[#303334]">#PAY-{{ $pay->id }}</td>
                                <td class="py-3 px-3">
                                    <div class="font-bold text-[#303334]">{{ $pay->customer?->name ?? 'عميل مباشر' }}</div>
                                    <span class="text-[10px] text-[#73777A]">{{ $pay->order?->deal?->room?->name ?? 'جلسة عامة' }}</span>
                                </td>
                                <td class="py-3 px-3">
                                    @if($pay->method === 'cash')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">كاش نقدي</span>
                                    @elseif($pay->method === 'instapay')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">إنستاباي</span>
                                    @elseif($pay->method === 'wallet')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">محفظة ذكية</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-default-100 text-default-700">أخرى</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 font-black text-[#303334] font-mono text-sm">
                                    {{ number_format($pay->amount, 2) }} <span class="text-[10px] font-normal text-[#73777A]">ج.م</span>
                                </td>
                                <td class="py-3 px-3 text-end text-[#73777A] font-mono">
                                    {{ $pay->paid_at->format('h:i A') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-[#73777A] text-xs">
                                    لا توجد معاملات مسجلة في هذه الوردية حتى الآن.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Close Shift Modal --}}
        <div id="close-shift-modal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-[#E5E2DC] animate-fade-in">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-[#F0EDE6]">
                    <div>
                        <h3 class="text-base font-bold text-[#303334]">تقفيل وتسليم الوردية</h3>
                        <p class="text-xs text-[#73777A]">مطابقة الكاش الفعلي في الدرج مع الحسابات المسجلة</p>
                    </div>
                    <button type="button" onclick="document.getElementById('close-shift-modal').classList.add('hidden')" class="size-8 rounded-lg bg-[#F5F3EE] hover:bg-[#E5E2DC] text-[#73777A] flex items-center justify-center transition-all cursor-pointer">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                    </button>
                </div>

                @if($totalUnpaidCount > 0)
                    <div class="mb-4 p-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs flex items-start gap-2">
                        <svg width="16" height="16" class="shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
                        <div>
                            <strong>تنبيه قبل الإقفال:</strong> يوجد عدد <strong>{{ $totalUnpaidCount }} عميل</strong> لم يحاسبوا بعد ({{ $unpaidOpenDeals->count() }} جلسة مفتوحة). سيتم ترحيل هذه الجلسات للوردية التالية تلقائياً.
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('shifts.close', $shift->id) }}">
                    @csrf
                    <div class="space-y-4">
                        <div class="p-3.5 rounded-xl bg-[#FAF9F5] border border-[#E5E2DC] flex items-center justify-between">
                            <span class="text-xs font-bold text-[#73777A]">النقدية المتوقعة في الدرج:</span>
                            <span class="text-base font-black text-[#4E8F35] font-mono">{{ number_format($stats['expected_cash'], 2) }} ج.م</span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#303334] mb-1.5">الكاش الفعلي في الدرج (بالعد) *</label>
                            <div class="relative">
                                <input type="number" step="0.5" name="actual_cash" required placeholder="أدخل المبلغ الفعلي بعد عد الدرج"
                                       class="w-full px-3.5 py-2.5 border border-[#E5E2DC] rounded-xl text-sm font-bold font-mono focus:border-[#4E8F35] outline-none">
                                <span class="absolute end-3 top-2.5 text-xs text-[#73777A] font-bold">ج.م</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#303334] mb-1.5">ملاحظات الإغلاق والتسليم</label>
                            <textarea name="closing_notes" rows="2" placeholder="أي ملاحظات حول العجز، الزيادة أو تسليم العهدة للكاشير التالي..."
                                      class="w-full px-3.5 py-2.5 border border-[#E5E2DC] rounded-xl text-xs focus:border-[#4E8F35] outline-none"></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-2.5 pt-2">
                            <button type="button" onclick="document.getElementById('close-shift-modal').classList.add('hidden')"
                                    class="px-4 py-2.5 bg-[#F5F3EE] hover:bg-[#E5E2DC] text-[#303334] rounded-xl text-xs font-bold transition-all cursor-pointer">إلغاء</button>
                            <button type="submit"
                                    class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition-all shadow-xs cursor-pointer">
                                تأكيد التقفيل وتصفية الوردية
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    @else
        {{-- No Active Shift State --}}
        <div class="bg-white rounded-2xl border border-[#E5E2DC] p-10 text-center max-w-md mx-auto my-8 shadow-xs">
            <div class="size-16 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-4 border border-amber-200">
                <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/>
                </svg>
            </div>
            <h3 class="text-lg font-black text-[#303334] mb-1">لا توجد وردية مفتوحة حالياً</h3>
            <p class="text-xs text-[#73777A] mb-6">يرجى فتح وردية جديدة وبدء استلام عهدة الدرج لتسجيل المعاملات المالية.</p>

            <form method="POST" action="{{ route('shifts.open') }}" class="text-start">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-bold text-[#303334] mb-1.5">عهدة بداية الوردية (الكاش المستلم) *</label>
                    <div class="relative">
                        <input type="number" step="0.5" name="opening_cash" value="0" required
                               class="w-full px-3.5 py-2.5 border border-[#E5E2DC] rounded-xl text-sm font-bold font-mono focus:border-[#4E8F35] outline-none">
                        <span class="absolute end-3 top-2.5 text-xs text-[#73777A] font-bold">ج.م</span>
                    </div>
                </div>
                <button type="submit" class="w-full py-3 bg-[#4E8F35] hover:bg-[#3F742B] text-white rounded-xl text-xs font-bold transition-all shadow-xs cursor-pointer">
                    فتح الوردية وبدء العمل
                </button>
            </form>
        </div>
    @endif

@endsection

@section('scripts')
<script>
    function filterUnpaidTable() {
        const query = document.getElementById('unpaid-search').value.trim().toLowerCase();
        const rows = document.querySelectorAll('.unpaid-row');

        rows.forEach(row => {
            const customer = (row.getAttribute('data-customer') || '').toLowerCase();
            const room = (row.getAttribute('data-room') || '').toLowerCase();

            if (!query || customer.includes(query) || room.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endsection
