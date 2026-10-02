@extends('shared.vertical', ['title' => 'الجلسات النشطة وإشغال الغرف — DDT WORKING SPACE'])

@section('content')

    {{-- Page Header --}}
    <div class="page-header-container mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="size-2 rounded-full bg-emerald-500 animate-ping"></span>
                    متابعة حية في الوقت الفعلي
                </span>
                <span class="text-xs text-[#73777A]">آخر تحديث: {{ now()->format('h:i A') }}</span>
            </div>
            <h1 class="text-2xl font-extrabold text-[#303334] tracking-tight">
                الجلسات النشطة وإشغال الغرف
            </h1>
            <p class="text-xs text-[#73777A] mt-1">توزيع وإشغال الغرف، كم فرد شغال حالياً، المقاعد المشغولة والمتبقية بالمساحة</p>
        </div>

        <div class="page-header-actions flex items-center gap-2.5">
            <button type="button" onclick="window.location.reload()"
                    class="px-3.5 py-2.5 bg-white hover:bg-[#F5F3EE] text-[#303334] border border-[#E5E2DC] rounded-xl text-xs font-bold transition-all shadow-xs gap-1.5 flex items-center cursor-pointer">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.19"/>
                </svg>
                <span>تحديث</span>
            </button>

            <a href="{{ url('/cashier') }}" target="_blank"
               class="px-4 py-2.5 bg-[#4E8F35] hover:bg-[#3F742B] text-white rounded-xl text-xs font-bold transition-all shadow-xs gap-2 flex items-center cursor-pointer">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/>
                </svg>
                <span>فتح شاشة الكاشير (POS) ↗</span>
            </a>
        </div>
    </div>

    {{-- ───────────────────────────────────────────────────────────── --}}
    {{-- كروت الفروق والإحصائيات العلوية المطورة --}}
    {{-- ───────────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        {{-- الكارت 1: كم فرد شغال حالياً --}}
        <div class="p-4 rounded-2xl bg-white border border-[#E5E2DC] shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-[#73777A] block">الأفراد المتواجدون حالياً</span>
                <div class="flex items-baseline gap-1.5 mt-1">
                    <span class="text-2xl font-black text-[#4E8F35]">{{ $activePeopleCount }}</span>
                    <span class="text-xs font-bold text-[#303334]">فرد شغال</span>
                </div>
                <div class="text-[11px] text-[#73777A] mt-0.5">في {{ $deals->count() }} جلسة مفتوحة ونشطة</div>
            </div>
            <div class="size-12 rounded-xl bg-[#EBF4E8] text-[#4E8F35] flex items-center justify-center shrink-0 border border-[#DCE8D4]">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
        </div>

        {{-- الكارت 2: شكل بياني مشغول X من Y مقعد --}}
        <div class="p-4 rounded-2xl bg-white border border-[#E5E2DC] shadow-xs">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-xs font-bold text-[#73777A]">المقاعد المشغولة</span>
                <span class="text-xs font-black font-mono px-2 py-0.5 rounded-full {{ $occupancyRate > 80 ? 'bg-rose-100 text-rose-700' : ($occupancyRate > 50 ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">
                    {{ $occupancyRate }}%
                </span>
            </div>
            <div class="text-base font-black text-[#303334]">
                مشغول <span class="text-emerald-700 font-mono">{{ $activePeopleCount }}</span> من <span class="font-mono text-[#73777A]">{{ $totalCapacity }}</span> مقعد
            </div>
            {{-- شريط بياني تقدمي --}}
            <div class="w-full bg-[#F0EDE6] h-2.5 rounded-full overflow-hidden mt-2.5">
                <div class="h-full rounded-full transition-all duration-500 {{ $occupancyRate > 80 ? 'bg-rose-500' : ($occupancyRate > 50 ? 'bg-amber-500' : 'bg-[#4E8F35]') }}"
                     style="width: {{ min(100, $occupancyRate) }}%;"></div>
            </div>
            <div class="text-[10px] text-[#73777A] mt-1.5 flex items-center justify-between">
                <span>0 مقعد</span>
                <span>إجمالي السعة: {{ $totalCapacity }} مقعد</span>
            </div>
        </div>

        {{-- الكارت 3: باقي كم مقعد متاح --}}
        <div class="p-4 rounded-2xl bg-white border border-[#E5E2DC] shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-[#73777A] block">المقاعد الشاغرة المتبقية</span>
                <div class="flex items-baseline gap-1.5 mt-1">
                    <span class="text-2xl font-black text-blue-600">{{ $remainingSeats }}</span>
                    <span class="text-xs font-bold text-[#303334]">مقعد شاغر</span>
                </div>
                <div class="text-[11px] text-[#73777A] mt-0.5">جاهزة لاستقبال عملاء جدد الآن</div>
            </div>
            <div class="size-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-100">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M19 9h-4V4H9v5H5v7h14V9z"/><path d="M5 16v5"/><path d="M19 16v5"/>
                </svg>
            </div>
        </div>

        {{-- الكارت 4: حالة وتوزيع الغرف --}}
        <div class="p-4 rounded-2xl bg-white border border-[#E5E2DC] shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-[#73777A] block">حالة الغرف والمساحات</span>
                <div class="flex items-baseline gap-1 mt-1">
                    <span class="text-2xl font-black text-purple-600">{{ $occupiedRoomsCount }}</span>
                    <span class="text-xs font-bold text-[#303334]">مشغولة من أصل {{ $allRooms->count() }}</span>
                </div>
                <div class="text-[11px] text-[#73777A] mt-0.5">
                    <strong class="text-emerald-600">{{ $availableRoomsCount }}</strong> غرف شاغرة ومتاحة بالكامل
                </div>
            </div>
            <div class="size-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0 border border-purple-100">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/>
                </svg>
            </div>
        </div>

    </div>

    {{-- ───────────────────────────────────────────────────────────── --}}
    {{-- شريط التصفية والتبويبات السريعة للغرف --}}
    {{-- ───────────────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-[#E5E2DC] p-4 mb-6 shadow-xs flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        {{-- Tabs --}}
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 md:pb-0">
            <a href="{{ route('deals.active') }}"
               class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap {{ empty($statusFilter) && empty($roomFilter) ? 'bg-[#4E8F35] text-white shadow-xs' : 'bg-[#F5F3EE] text-[#73777A] hover:text-[#303334]' }}">
                جميع الغرف ({{ $allRooms->count() }})
            </a>
            <a href="{{ route('deals.active', ['status' => 'occupied']) }}"
               class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap {{ $statusFilter === 'occupied' ? 'bg-[#4E8F35] text-white shadow-xs' : 'bg-[#F5F3EE] text-[#73777A] hover:text-[#303334]' }}">
                الغرف المشغولة فقط ({{ $occupiedRoomsCount }})
            </a>
            <a href="{{ route('deals.active', ['status' => 'available']) }}"
               class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap {{ $statusFilter === 'available' ? 'bg-[#4E8F35] text-white shadow-xs' : 'bg-[#F5F3EE] text-[#73777A] hover:text-[#303334]' }}">
                الغرف الشاغرة بالكامل ({{ $availableRoomsCount }})
            </a>
        </div>

        {{-- Quick Search by customer name inside rooms --}}
        <div class="relative w-full md:w-72">
            <input type="text" id="live-customer-search" onkeyup="filterCustomerDeals()"
                   placeholder="بحث سريع باسم العميل أو رقم الجلسة..."
                   class="w-full px-3.5 py-2 pe-9 border border-[#E5E2DC] rounded-xl text-xs focus:border-[#4E8F35] outline-none">
            <div class="absolute end-3 top-2 text-[#73777A]">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- ───────────────────────────────────────────────────────────── --}}
    {{-- تقسيم الغرف: عرض كل غرفة ومعدل إشغالها والأفراد الشغالين بها --}}
    {{-- ───────────────────────────────────────────────────────────── --}}
    <div class="space-y-6" id="rooms-container">
        @forelse($rooms as $room)
            @php
                $dealsInRoom = $room->activeDeals;
                $occupiedSeatsInRoom = $dealsInRoom->count();
                $capacityInRoom = $room->capacity ?: 1;
                $remainingInRoom = max(0, $capacityInRoom - $occupiedSeatsInRoom);
                $percentInRoom = min(100, round(($occupiedSeatsInRoom / $capacityInRoom) * 100));

                $isFull = $occupiedSeatsInRoom >= $capacityInRoom;
                $isEmpty = $occupiedSeatsInRoom === 0;

                // Color themes based on occupancy
                if ($isEmpty) {
                    $barColor = 'bg-slate-300';
                    $badgeClass = 'bg-slate-100 text-slate-700 border-slate-200';
                    $statusText = 'شاغرة بالكامل';
                } elseif ($isFull) {
                    $barColor = 'bg-rose-500';
                    $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200';
                    $statusText = 'مكتملة السعة';
                } elseif ($percentInRoom >= 70) {
                    $barColor = 'bg-amber-500';
                    $badgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
                    $statusText = 'إشغال مرتفع';
                } else {
                    $barColor = 'bg-[#4E8F35]';
                    $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                    $statusText = 'بها رواد';
                }
            @endphp

            <div class="bg-white rounded-2xl border border-[#E5E2DC] shadow-xs overflow-hidden room-card"
                 data-room-id="{{ $room->id }}" data-room-name="{{ $room->name }}">

                {{-- Room Header & Occupancy Visual Bar --}}
                <div class="p-5 border-b border-[#F0EDE6] bg-gradient-to-r from-white via-white to-[#FAF9F5]">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                        {{-- Room Title & Identity --}}
                        <div class="flex items-center gap-3.5">
                            <div class="size-11 rounded-xl flex items-center justify-center font-black text-sm text-white shrink-0 shadow-xs"
                                 style="background-color: {{ $room->color ?: '#4E8F35' }};">
                                {{ mb_substr($room->name, 0, 1, 'UTF-8') }}
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h2 class="text-base font-extrabold text-[#303334]">{{ $room->name }}</h2>
                                    @if($room->code)
                                        <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-[#F5F3EE] text-[#73777A] font-bold border border-[#E5E2DC]">
                                            {{ $room->code }}
                                        </span>
                                    @endif
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $badgeClass }}">
                                        @if(!$isEmpty)
                                            <span class="size-1.5 rounded-full {{ $isFull ? 'bg-rose-500' : 'bg-emerald-500' }} animate-pulse"></span>
                                        @endif
                                        <span>{{ $statusText }}</span>
                                    </span>
                                </div>
                                <p class="text-xs text-[#73777A] mt-0.5">
                                    {{ $room->description ?: 'مساحة عمل وغرفة اجتماعات مجهزة' }}
                                </p>
                            </div>
                        </div>

                        {{-- Visual Occupancy Gauge (شكل بياني: مشغول 3 من 10 وباقي كم مقعد) --}}
                        <div class="w-full md:w-80 bg-[#FAF9F5] p-3 rounded-xl border border-[#E5E2DC]">
                            <div class="flex items-center justify-between text-xs mb-1.5">
                                <span class="font-extrabold text-[#303334]">
                                    مشغول <span class="text-[#4E8F35] font-mono font-black text-sm">{{ $occupiedSeatsInRoom }}</span> من <span class="font-mono text-[#73777A]">{{ $capacityInRoom }}</span> مقعد
                                </span>
                                <span class="font-mono font-black text-xs px-2 py-0.5 rounded-full bg-white border border-[#E5E2DC] text-[#303334]">
                                    {{ $percentInRoom }}%
                                </span>
                            </div>

                            {{-- Progress Bar --}}
                            <div class="w-full bg-[#E5E2DC] h-2.5 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-500 {{ $barColor }}"
                                     style="width: {{ $percentInRoom }}%;"></div>
                            </div>

                            {{-- Remaining Seats Info --}}
                            <div class="flex items-center justify-between text-[11px] mt-1.5 pt-1 border-t border-[#EFECE6]">
                                <span class="text-[#73777A]">المقاعد الشاغرة:</span>
                                @if($remainingInRoom > 0)
                                    <span class="font-bold text-blue-600 flex items-center gap-1">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                        <span>باقي {{ $remainingInRoom }} مقاعد متاحة</span>
                                    </span>
                                @else
                                    <span class="font-bold text-rose-600">لا توجد مقاعد شاغرة (ممتلئة)</span>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Room Content: Deals List (كم فرد شغال في هذه الغرفة) --}}
                <div class="p-5">
                    @if($dealsInRoom->count() > 0)
                        <div class="mb-3 flex items-center justify-between">
                            <h3 class="text-xs font-black text-[#303334] flex items-center gap-1.5">
                                <span class="size-2 rounded-full bg-[#4E8F35]"></span>
                                <span>الأفراد الشغالين في الغرفة حالياً ({{ $dealsInRoom->count() }} أفراد):</span>
                            </h3>
                            <span class="text-[11px] text-[#73777A]">يتم احتساب الوقت تلقائياً</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3.5 deal-cards-grid">
                            @foreach($dealsInRoom as $deal)
                                @php
                                    $custName = $deal->customer ? ($deal->customer->full_name ?: $deal->customer->name) : 'عميل غير مسجل';
                                    $custInitials = $deal->customer ? $deal->customer->initials : 'ع';
                                    $phone = $deal->customer ? $deal->customer->phone : null;
                                    $itemsCount = $deal->order ? $deal->order->items->count() : 0;
                                    $orderTotal = $deal->order ? $deal->order->total : 0;
                                @endphp
                                <div class="p-4 rounded-xl bg-white border border-[#E5E2DC] hover:border-[#4E8F35]/60 hover:shadow-sm transition-all deal-card flex flex-col justify-between"
                                     data-customer-name="{{ $custName }}" data-deal-id="{{ $deal->id }}">
                                    <div>
                                        {{-- Header: Customer Info & Deal # --}}
                                        <div class="flex items-start justify-between gap-2 mb-3">
                                            <div class="flex items-center gap-2.5">
                                                <div class="size-9 rounded-full bg-[#EBF4E8] text-[#4E8F35] font-black text-xs flex items-center justify-center shrink-0 border border-[#DCE8D4]">
                                                    {{ $custInitials ?: 'ع' }}
                                                </div>
                                                <div>
                                                    <h4 class="font-extrabold text-xs text-[#303334] leading-tight customer-name-text">
                                                        {{ $custName }}
                                                    </h4>
                                                    @if($phone)
                                                        <span class="text-[10px] font-mono text-[#73777A]" dir="ltr">{{ $phone }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <span class="text-[10px] font-mono font-bold px-1.5 py-0.5 rounded bg-[#F5F3EE] text-[#73777A] border border-[#E5E2DC] shrink-0">
                                                #{{ $deal->id }}
                                            </span>
                                        </div>

                                        {{-- Duration & Timers --}}
                                        <div class="p-2.5 rounded-lg bg-[#FAF9F5] border border-[#F0EDE6] space-y-1.5 text-xs mb-3">
                                            <div class="flex items-center justify-between text-[11px]">
                                                <span class="text-[#73777A]">بدأ في:</span>
                                                <span class="font-mono font-bold text-[#303334]">
                                                    {{ $deal->started_at ? $deal->started_at->format('h:i A') : '—' }}
                                                </span>
                                            </div>
                                            <div class="flex items-center justify-between text-[11px]">
                                                <span class="text-[#73777A]">المدة المنقضية:</span>
                                                <span class="font-bold text-[#4E8F35] flex items-center gap-1 live-duration"
                                                      data-started="{{ $deal->started_at ? $deal->started_at->toIso8601String() : '' }}">
                                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                                    <span>{{ $deal->started_at ? $deal->started_at->diffForHumans(null, true) : 'الآن' }}</span>
                                                </span>
                                            </div>
                                            @if($deal->workspaceType)
                                                <div class="flex items-center justify-between text-[11px]">
                                                    <span class="text-[#73777A]">نوع الباقة:</span>
                                                    <span class="font-bold text-[#303334]">{{ $deal->workspaceType->name }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Cafe Orders Pill --}}
                                        <div class="flex items-center justify-between text-xs px-2 py-1.5 rounded-lg bg-white border border-[#E5E2DC] mb-3">
                                            <span class="text-[#73777A] flex items-center gap-1 text-[11px]">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" x2="6" y1="1" y2="4"/><line x1="10" x2="10" y1="1" y2="4"/><line x1="14" x2="14" y1="1" y2="4"/></svg>
                                                <span>طلبات الكافيه:</span>
                                            </span>
                                            @if($itemsCount > 0)
                                                <span class="font-black text-amber-700 text-[11px]">
                                                    {{ $itemsCount }} أصناف ({{ number_format($orderTotal) }} ج.م)
                                                </span>
                                            @else
                                                <span class="text-[11px] text-[#73777A]">لا توجد طلبات</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Cashier Action Link --}}
                                    <div class="pt-2 border-t border-[#F0EDE6]">
                                        <a href="{{ url('/cashier?deal_id=' . $deal->id) }}" target="_blank"
                                           class="w-full py-2 bg-[#F5F3EE] hover:bg-[#EBF4E8] text-[#303334] hover:text-[#4E8F35] rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-1.5">
                                            <span>إدارة ومحاسبة بالكاشير</span>
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="5" x2="19" y1="12" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        {{-- Empty Room State --}}
                        <div class="p-6 rounded-xl bg-[#FAF9F5] border border-dashed border-[#DCD8D0] text-center">
                            <div class="size-10 rounded-full bg-white text-[#73777A] flex items-center justify-center mx-auto mb-2 border border-[#E5E2DC]">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M19 9h-4V4H9v5H5v7h14V9z"/><path d="M5 16v5"/><path d="M19 16v5"/>
                                </svg>
                            </div>
                            <h4 class="text-xs font-black text-[#303334]">الغرفة شاغرة ومتاحة حالياً</h4>
                            <p class="text-[11px] text-[#73777A] mt-0.5 mb-3">
                                جاهزة لاستقبال حتى {{ $capacityInRoom }} فرد. يمكنك بدء جلسة فورية من شاشة الكاشير.
                            </p>
                            <a href="{{ url('/cashier?room_id=' . $room->id) }}" target="_blank"
                               class="inline-flex items-center gap-1.5 px-4 py-2 bg-white hover:bg-[#EBF4E8] text-[#4E8F35] border border-[#DCE8D4] rounded-xl text-xs font-bold transition shadow-xs">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                                <span>تسكين عميل في الغرفة عبر الكاشير</span>
                            </a>
                        </div>
                    @endif
                </div>

            </div>
        @empty
            <div class="p-12 text-center bg-white rounded-2xl border border-[#E5E2DC]">
                <p class="text-xs font-bold text-[#73777A]">لا توجد غرف مسجلة في هذا القسم.</p>
            </div>
        @endforelse

        {{-- ───────────────────────────────────────────────────────────── --}}
        {{-- قسم الجلسات غير المرتبطة بغرفة (إن وجدت - مساحة مفتوحة) --}}
        {{-- ───────────────────────────────────────────────────────────── --}}
        @if($unassignedDeals && $unassignedDeals->count() > 0)
            <div class="bg-white rounded-2xl border border-[#E5E2DC] shadow-xs overflow-hidden">
                <div class="p-5 border-b border-[#F0EDE6] bg-slate-50 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="size-10 rounded-xl bg-slate-200 text-slate-700 flex items-center justify-center font-black text-xs">
                            #
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-[#303334]">مساحة العمل المفتوحة والطلبات العامة</h3>
                            <p class="text-xs text-[#73777A]">جلسات نشطة بدون تخصيص غرفة معينة</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-white text-slate-700 border border-slate-200">
                        {{ $unassignedDeals->count() }} جلسات نشطة
                    </span>
                </div>

                <div class="p-5 grid grid-cols-1 md:grid-cols-3 gap-3.5">
                    @foreach($unassignedDeals as $deal)
                        <div class="p-4 rounded-xl bg-white border border-[#E5E2DC] flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-extrabold text-xs text-[#303334]">
                                        {{ $deal->customer ? ($deal->customer->full_name ?: $deal->customer->name) : 'عميل مباشر' }}
                                    </h4>
                                    <span class="text-[10px] font-mono text-[#73777A]">#{{ $deal->id }}</span>
                                </div>
                                <div class="text-[11px] text-[#73777A] space-y-1 mb-3">
                                    <div>بدأ: {{ $deal->started_at ? $deal->started_at->format('h:i A') : '-' }}</div>
                                    <div class="text-emerald-700 font-bold">منذ: {{ $deal->started_at ? $deal->started_at->diffForHumans(null, true) : '-' }}</div>
                                </div>
                            </div>
                            <a href="{{ url('/cashier?deal_id=' . $deal->id) }}" target="_blank"
                               class="w-full py-2 bg-[#F5F3EE] hover:bg-[#EBF4E8] text-[#303334] hover:text-[#4E8F35] rounded-lg text-xs font-bold text-center transition">
                                محاسبة بالكاشير ↗
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

@endsection

@section('scripts')
<script>
    // Live Client-side Search for deals across rooms
    function filterCustomerDeals() {
        const query = document.getElementById('live-customer-search').value.trim().toLowerCase();
        const roomCards = document.querySelectorAll('.room-card');

        roomCards.forEach(room => {
            const dealCards = room.querySelectorAll('.deal-card');
            let roomHasMatch = false;

            if (!query) {
                // Show all
                dealCards.forEach(card => card.style.display = '');
                room.style.display = '';
                return;
            }

            dealCards.forEach(card => {
                const name = (card.getAttribute('data-customer-name') || '').toLowerCase();
                const dealId = (card.getAttribute('data-deal-id') || '').toLowerCase();

                if (name.includes(query) || dealId.includes(query)) {
                    card.style.display = '';
                    roomHasMatch = true;
                } else {
                    card.style.display = 'none';
                }
            });

            // Also check room name
            const roomName = (room.getAttribute('data-room-name') || '').toLowerCase();
            if (roomName.includes(query)) {
                roomHasMatch = true;
                dealCards.forEach(card => card.style.display = '');
            }

            if (roomHasMatch) {
                room.style.display = '';
            } else {
                room.style.display = 'none';
            }
        });
    }

    // Auto-update elapsed time every minute
    setInterval(function() {
        document.querySelectorAll('.live-duration').forEach(el => {
            const startedIso = el.getAttribute('data-started');
            if (startedIso) {
                const startedDate = new Date(startedIso);
                const now = new Date();
                const diffMs = now - startedDate;
                const diffMins = Math.floor(diffMs / 60000);

                if (diffMins < 60) {
                    el.querySelector('span').textContent = `${diffMins} دقيقة`;
                } else {
                    const hours = Math.floor(diffMins / 60);
                    const mins = diffMins % 60;
                    el.querySelector('span').textContent = `${hours} ساعة و ${mins} دقيقة`;
                }
            }
        });
    }, 60000);
</script>
@endsection
