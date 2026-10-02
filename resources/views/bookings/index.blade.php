@extends('shared.vertical', ['title' => 'تقويم وجدول حجوزات القاعات والاشتراكات — DT-System'])

@section('content')

    {{-- ── 1. Page Header ── --}}
    <div class="page-header-container mb-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="size-2 rounded-full bg-[#4E8F35]"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-[#4E8F35]">تقويم ومواعيد المساحات والقاعات</span>
            </div>
            <h2 class="text-2xl font-black text-[#303334] tracking-tight mt-1">
                جدول وتقويم الحجوزات والاشتراكات
            </h2>
            <p class="text-xs text-neutral-500 mt-0.5">متابعة تفاعلية للاشتراكات الأسبوعية والشهرية، وحجوزات القاعات بالساعة، وتسجيل الحضور الفوري</p>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap">
            <a href="{{ route('rooms.index') }}"
                class="px-3.5 py-2 bg-white border border-[#E5E2DC] hover:border-[#4E8F35] text-[#303334] rounded-xl text-xs font-bold transition-all shadow-2xs flex items-center gap-2">
                <svg class="size-4 text-[#4E8F35]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                    <line x1="3" y1="9" x2="21" y2="9"/>
                    <line x1="9" y1="21" x2="9" y2="9"/>
                </svg>
                <span>القاعات والمساحات</span>
            </a>

            <button type="button" onclick="openNewBookingModal()"
                class="px-4 py-2 bg-[#4E8F35] hover:bg-[#3F742B] text-white rounded-xl text-xs font-black transition-all shadow-xs flex items-center gap-2 cursor-pointer">
                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>+ تسجيل حجز / اشتراك جديد</span>
            </button>
        </div>
    </div>

    {{-- ── 2. Feedback Alerts ── --}}
    @if(session('success'))
        <div class="p-3.5 mb-5 rounded-2xl bg-[#EBF4E8] border border-[#DCE8D4] text-[#303334] text-xs font-bold flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <svg class="size-4 text-[#4E8F35] shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-neutral-400 hover:text-neutral-700">
                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    {{-- ── 3. Quick Summary Stats ── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 mb-5">
        {{-- Stat 1: Today's Bookings --}}
        <div class="p-3.5 rounded-2xl bg-white border border-[#E5E2DC] flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-[#73777A] block">حجوزات اليوم</span>
                <span class="text-xl font-black text-[#303334] font-mono mt-0.5 block">{{ $todayBookingsCount }}</span>
                <span class="text-[10px] text-[#4E8F35] font-semibold mt-0.5 block">جلسات وقاعات اليوم</span>
            </div>
            <div class="size-10 rounded-xl bg-[#EBF4E8] text-[#4E8F35] flex items-center justify-center font-bold">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect width="18" height="18" x="3" y="4" rx="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/>
                </svg>
            </div>
        </div>

        {{-- Stat 2: Active Subscriptions --}}
        <div class="p-3.5 rounded-2xl bg-white border border-[#E5E2DC] flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-[#73777A] block">اشتراكات سارية (أسبوعي/شهري)</span>
                <span class="text-xl font-black text-blue-600 font-mono mt-0.5 block">{{ $activeSubscriptionsCount }}</span>
                <span class="text-[10px] text-blue-600 font-semibold mt-0.5 block">عضويات ومكاتب ممتدة</span>
            </div>
            <div class="size-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
        </div>

        {{-- Stat 3: Upcoming Bookings --}}
        <div class="p-3.5 rounded-2xl bg-white border border-[#E5E2DC] flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-[#73777A] block">حجوزات قادمة مؤكدة</span>
                <span class="text-xl font-black text-amber-600 font-mono mt-0.5 block">{{ $upcomingRoomsBookingsCount }}</span>
                <span class="text-[10px] text-amber-600 font-semibold mt-0.5 block">مواعيد مسجلة مسبقاً</span>
            </div>
            <div class="size-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
            </div>
        </div>

        {{-- Stat 4: Rooms & Spaces Count --}}
        <div class="p-3.5 rounded-2xl bg-white border border-[#E5E2DC] flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-[#73777A] block">القاعات المتاحة</span>
                <span class="text-xl font-black text-slate-800 font-mono mt-0.5 block">{{ $rooms->count() }}</span>
                <span class="text-[10px] text-slate-500 font-semibold mt-0.5 block">مساحات وقاعات خاصة</span>
            </div>
            <div class="size-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- ── 4. Main Calendar Container & Controls ── --}}
    <div class="bg-white rounded-3xl border border-[#E5E2DC] p-4 sm:p-5 shadow-xs mb-8">

        {{-- Top Control Bar: View Switcher, Navigation, Type Legends, Table Toggle --}}
        <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4 pb-4 mb-4 border-b border-[#E5E2DC]">
            
            {{-- Navigation & Period Title --}}
            <div class="flex items-center gap-3 flex-wrap">
                <div class="flex items-center bg-[#F5F3EE] p-1 rounded-xl border border-[#E5E2DC]">
                    <button type="button" onclick="calendarPrev()" title="السابق" class="size-8 rounded-lg hover:bg-white text-slate-700 flex items-center justify-center transition-all cursor-pointer">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
                    </button>
                    <button type="button" onclick="calendarToday()" class="px-3 py-1.5 rounded-lg hover:bg-white text-xs font-extrabold text-slate-800 transition-all cursor-pointer">
                        اليوم
                    </button>
                    <button type="button" onclick="calendarNext()" title="التالي" class="size-8 rounded-lg hover:bg-white text-slate-700 flex items-center justify-center transition-all cursor-pointer">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
                    </button>
                </div>

                <h3 id="calendar-period-title" class="text-base sm:text-lg font-black text-slate-900 min-w-40 font-mono">
                    {{ now()->translatedFormat('F Y') }}
                </h3>
            </div>

            {{-- Legend Indicators (3 Distinct Colors) --}}
            <div class="hidden xl:flex items-center gap-2.5 text-[11px] font-bold p-1.5 rounded-xl bg-[#F5F3EE] border border-[#E5E2DC]" dir="rtl">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white border border-[#E5E2DC] shadow-2xs">
                    <span class="size-3 rounded-full bg-blue-600 shrink-0"></span>
                    <span class="text-slate-800 font-extrabold">باقة مستمرة وسارية</span>
                </span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white border border-[#E5E2DC] shadow-2xs">
                    <span class="size-3 rounded-full bg-purple-600 shrink-0"></span>
                    <span class="text-slate-800 font-extrabold">باقات واشتراكات</span>
                </span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white border border-[#E5E2DC] shadow-2xs">
                    <span class="size-3 rounded-full bg-[#4E8F35] shrink-0"></span>
                    <span class="text-slate-800 font-extrabold">حجز مرة واحدة بالساعة</span>
                </span>
            </div>

            {{-- View Switchers (Month / Week / Day / Table View) --}}
            <div class="flex items-center gap-1.5 bg-[#F5F3EE] p-1 rounded-xl border border-[#E5E2DC] self-start lg:self-auto">
                <button type="button" onclick="changeCalendarView('dayGridMonth', this)" class="cal-view-btn px-3 py-1.5 rounded-lg text-xs font-bold transition-all bg-white text-[#4E8F35] shadow-xs cursor-pointer">
                    شهر
                </button>
                <button type="button" onclick="changeCalendarView('timeGridWeek', this)" class="cal-view-btn px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-slate-600 hover:text-slate-900 cursor-pointer">
                    أسبوع
                </button>
                <button type="button" onclick="changeCalendarView('timeGridDay', this)" class="cal-view-btn px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-slate-600 hover:text-slate-900 cursor-pointer">
                    يوم
                </button>
                <button type="button" onclick="toggleViewMode('table')" id="btn-toggle-table" class="cal-view-btn px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-slate-600 hover:text-slate-900 flex items-center gap-1 cursor-pointer">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="8" x2="21" y1="6" y2="6"/><line x1="8" x2="21" y1="12" y2="12"/><line x1="8" x2="21" y1="18" y2="18"/><line x1="3" x2="3.01" y1="6" y2="6"/><line x1="3" x2="3.01" y1="12" y2="12"/><line x1="3" x2="3.01" y1="18" y2="18"/></svg>
                    <span>الجدول</span>
                </button>
            </div>
        </div>

        {{-- Filters Bar: Room Filter, Type Filter, Status Filter, Search --}}
        <div class="flex flex-wrap items-center justify-between gap-3 mb-5 p-3 rounded-2xl bg-[#F8F7F4] border border-[#E5E2DC]">
            <div class="flex flex-wrap items-center gap-2.5 flex-1">
                {{-- Room Filter --}}
                <div class="w-44">
                    <select id="filter-room" onchange="applyCalendarFilters()" class="w-full bg-white border border-[#E5E2DC] text-slate-800 rounded-xl px-2.5 py-1.5 text-xs font-bold focus:border-[#4E8F35] outline-none">
                        <option value="">كل القاعات والمساحات</option>
                        @foreach($rooms as $r)
                        <option value="{{ $r->id }}" {{ $roomId == $r->id ? 'selected' : '' }}>{{ $r->name }} ({{ $r->type_label }})</option>
                        @endforeach
                    </select>
                </div>

                {{-- Type Filter --}}
                <div class="w-44">
                    <select id="filter-type" onchange="applyCalendarFilters()" class="w-full bg-white border border-[#E5E2DC] text-slate-800 rounded-xl px-2.5 py-1.5 text-xs font-bold focus:border-[#4E8F35] outline-none">
                        <option value="">جميع أنواع الحجوزات</option>
                        <option value="subscription" {{ $type == 'subscription' ? 'selected' : '' }}>اشتراك أسبوعي / شهري</option>
                        <option value="private_room" {{ $type == 'private_room' ? 'selected' : '' }}>حجز قاعة خاصة بالساعة</option>
                        <option value="shared_desk" {{ $type == 'shared_desk' ? 'selected' : '' }}>حجز مساحة مشتركة بالساعة</option>
                    </select>
                </div>

                {{-- Status Filter --}}
                <div class="w-36">
                    <select id="filter-status" onchange="applyCalendarFilters()" class="w-full bg-white border border-[#E5E2DC] text-slate-800 rounded-xl px-2.5 py-1.5 text-xs font-bold focus:border-[#4E8F35] outline-none">
                        <option value="">جميع الحالات</option>
                        <option value="confirmed" {{ $status == 'confirmed' ? 'selected' : '' }}>مؤكد</option>
                        <option value="checked_in" {{ $status == 'checked_in' ? 'selected' : '' }}>تم تسجيل الحضور</option>
                        <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                        <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                    </select>
                </div>
            </div>

            {{-- Live Search Filter --}}
            <div class="relative w-64">
                <input type="text" id="filter-search" value="{{ $search ?? '' }}" oninput="applyCalendarFiltersDebounced()" placeholder="ابحث باسم العميل أو رقم الحجز..."
                    class="w-full bg-white border border-[#E5E2DC] text-slate-800 rounded-xl px-3 py-1.5 pe-8 text-xs focus:border-[#4E8F35] outline-none">
                <span class="absolute end-2.5 top-2 text-slate-400 pointer-events-none">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/></svg>
                </span>
            </div>
        </div>

        {{-- ── 5. CALENDAR VIEW CONTAINER ── --}}
        <div id="calendar-view-container" class="w-full overflow-hidden">
            <div id="fullcalendar-booking" class="min-h-[650px] font-sans"></div>
        </div>

        {{-- ── 6. TABLE VIEW CONTAINER (Hidden by default, toggleable) ── --}}
        <div id="table-view-container" class="hidden">
            <div class="overflow-x-auto rounded-2xl border border-[#E5E2DC]">
                <table class="w-full text-right text-xs">
                    <thead>
                        <tr class="bg-[#F8F7F4] text-slate-600 font-bold border-b border-[#E5E2DC]">
                            <th class="p-3">رقم الحجز</th>
                            <th class="p-3">العميل</th>
                            <th class="p-3">القاعة / المساحة</th>
                            <th class="p-3">نوع الحجز والمدة</th>
                            <th class="p-3">تاريخ ووقت البدء</th>
                            <th class="p-3">تاريخ الانتهاء</th>
                            <th class="p-3 text-center">الحالة</th>
                            <th class="p-3 text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100" id="table-bookings-body">
                        @forelse($bookings as $b)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="p-3 font-mono font-black text-slate-800">{{ $b->booking_number }}</td>
                            <td class="p-3">
                                <div class="font-bold text-slate-900">{{ $b->customer->full_name ?? 'عميل مباشر' }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $b->customer->phone ?? '' }}</div>
                            </td>
                            <td class="p-3">
                                <span class="font-bold text-slate-800">{{ $b->room->name ?? 'مساحة عامة' }}</span>
                                <span class="text-[10px] text-slate-500 block">{{ $b->room->type_label ?? '' }}</span>
                            </td>
                            <td class="p-3">
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="size-2 rounded-full" style="background-color: {{ $b->calendar_color }}"></span>
                                    <span class="font-bold text-slate-800">{{ $b->booking_type_label }}</span>
                                </span>
                                <span class="text-[10px] text-slate-500 block font-medium">{{ $b->duration_formatted }}</span>
                            </td>
                            <td class="p-3 font-mono text-slate-700 font-semibold" dir="ltr">{{ $b->start_at ? $b->start_at->format('Y-m-d h:i A') : '' }}</td>
                            <td class="p-3 font-mono text-slate-700 font-semibold" dir="ltr">{{ $b->end_at ? $b->end_at->format('Y-m-d h:i A') : '' }}</td>
                            <td class="p-3 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $b->status_badge_class }}">
                                    {{ $b->status_label }}
                                </span>
                            </td>
                            <td class="p-3 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    @if(in_array($b->status, ['confirmed', 'pending']))
                                    <form method="POST" action="{{ route('bookings.check-in', $b) }}">
                                        @csrf
                                        <button type="submit" title="تسجيل حضور وتسكين جلسة" class="p-1.5 rounded-lg bg-[#EBF4E8] text-[#4E8F35] hover:bg-[#4E8F35] hover:text-white transition-all font-bold">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                                        </button>
                                    </form>
                                    @endif
                                    <button type="button" onclick="showBookingDetailsById({{ $b->id }})" title="عرض التفاصيل" class="p-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all font-bold">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-400">لا توجد حجوزات مسجلة مطابقة للبحث</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($bookings->hasPages())
            <div class="mt-4">
                {{ $bookings->links() }}
            </div>
            @endif
        </div>

    </div>

    {{-- ── 7. MODAL: تفاصيل الحجز (Booking Details Modal) ── --}}
    <div id="modal-booking-details" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white border border-[#E5E2DC] rounded-3xl max-w-lg w-full p-6 shadow-2xl text-slate-800">
            <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <span id="dtl-color-indicator" class="size-3 rounded-full bg-[#4E8F35]"></span>
                    <div>
                        <h3 class="font-extrabold text-base text-slate-900" id="dtl-booking-number">حجز #BK-0000</h3>
                        <p class="text-[11px] text-slate-400 font-medium" id="dtl-type-label">حجز قاعة خاصة</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modal-booking-details')" class="size-8 rounded-lg bg-slate-100 text-slate-500 hover:text-slate-900 flex items-center justify-center font-bold transition-all">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="space-y-3.5 text-xs">
                {{-- Customer Info Card --}}
                <div class="p-3.5 rounded-2xl bg-[#F8F7F4] border border-[#E5E2DC] flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="size-10 rounded-xl bg-[#4E8F35] text-white font-black text-sm flex items-center justify-center" id="dtl-cust-avatar">
                            ع
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm" id="dtl-cust-name">اسم العميل</h4>
                            <p class="text-slate-500 font-mono text-[11px] mt-0.5" id="dtl-cust-phone" dir="ltr">01000000000</p>
                        </div>
                    </div>
                    <span id="dtl-status-badge" class="px-3 py-1 rounded-full text-[11px] font-bold bg-[#EBF4E8] text-[#3B6E28]">مؤكد</span>
                </div>

                {{-- Room and Timing Info --}}
                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200">
                        <span class="text-[10px] text-slate-400 font-bold block mb-1">المكان والقاعة</span>
                        <p class="font-bold text-slate-900" id="dtl-room-name">قاعة A</p>
                        <span class="text-[10px] text-slate-500" id="dtl-room-type">غرفة خاصة</span>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200">
                        <span class="text-[10px] text-slate-400 font-bold block mb-1">المدة المحجوزة</span>
                        <p class="font-bold text-[#4E8F35]" id="dtl-duration">ساعتان</p>
                        <span class="text-[10px] text-slate-500" id="dtl-workspace-type">مساحة عمل</span>
                    </div>
                </div>

                {{-- Dates Details --}}
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 space-y-1.5">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 font-semibold">تاريخ ووقت البدء:</span>
                        <span class="font-mono font-bold text-slate-800" id="dtl-start-time" dir="ltr">2026-09-21 10:00 AM</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 font-semibold">تاريخ ووقت الانتهاء:</span>
                        <span class="font-mono font-bold text-slate-800" id="dtl-end-time" dir="ltr">2026-09-21 12:00 PM</span>
                    </div>
                </div>

                {{-- Notes --}}
                <div id="dtl-notes-container" class="p-3 rounded-xl bg-amber-50/70 border border-amber-200 text-amber-900">
                    <span class="font-bold text-[11px] block mb-0.5">ملاحظات الحجز:</span>
                    <p id="dtl-notes" class="text-xs"></p>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-between gap-2.5 pt-4 mt-4 border-t border-slate-100">
                <form id="form-dtl-checkin" method="POST" action="" class="flex-1">
                    @csrf
                    <button type="submit" id="btn-dtl-checkin" class="w-full px-4 py-2.5 bg-[#4E8F35] hover:bg-[#3F742B] text-white rounded-xl text-xs font-black transition-all shadow-xs flex items-center justify-center gap-1.5 cursor-pointer">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        <span>تسجيل حضور وبدء الجلسة</span>
                    </button>
                </form>

                <form id="form-dtl-delete" method="POST" action="" onsubmit="return confirm('هل أنت متأكد من حذف أو إلغاء هذا الحجز؟');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3.5 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl text-xs font-bold transition-all border border-rose-200 cursor-pointer">
                        إلغاء الحجز
                    </button>
                </form>

                <button type="button" onclick="closeModal('modal-booking-details')" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all cursor-pointer">
                    إغلاق
                </button>
            </div>
        </div>
    </div>

    {{-- ── 8. MODAL: تسجيل حجز أو اشتراك جديد (Add / Edit Booking Modal) ── --}}
    <div id="modal-add-booking" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white border border-[#E5E2DC] rounded-3xl max-w-lg w-full p-6 shadow-2xl text-slate-800">
            <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                <div>
                    <h3 class="font-extrabold text-base text-slate-900" id="ab-modal-title">تسجيل حجز أو اشتراك جديد</h3>
                    <p class="text-xs text-slate-400">حجز قاعة بالساعة أو اشتراك مدة أسبوعي وشهري</p>
                </div>
                <button type="button" onclick="closeModal('modal-add-booking')" class="size-8 rounded-lg bg-slate-100 text-slate-500 hover:text-slate-900 flex items-center justify-center font-bold transition-all">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>
            </div>

            <form action="{{ route('bookings.store') }}" method="POST" id="form-add-booking">
                @csrf
                <div class="space-y-3.5 text-xs">

                    {{-- 1. Customer Selection with Quick Search --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">العميل <span class="text-rose-500">*</span></label>
                        <select name="customer_id" id="ab-customer-id" required class="w-full bg-slate-50 border border-[#E5E2DC] text-slate-800 rounded-xl px-3.5 py-2.5 text-xs focus:border-[#4E8F35] focus:bg-white outline-none">
                            <option value="">اختر العميل من القائمة...</option>
                            @foreach($customers as $c)
                            <option value="{{ $c->id }}">{{ $c->full_name }} ({{ $c->phone }})</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 2. Booking Type / Duration Mode --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">نوع ونظام الحجز <span class="text-rose-500">*</span></label>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="flex flex-col items-center justify-center cursor-pointer p-2.5 rounded-xl border border-slate-200 hover:border-[#4E8F35] hover:bg-[#EBF4E8] transition text-center has-[:checked]:border-[#4E8F35] has-[:checked]:bg-[#EBF4E8] has-[:checked]:text-[#4E8F35]">
                                <input type="radio" name="duration_mode" value="hourly" checked onchange="onDurationModeChange('hourly')" class="sr-only">
                                <span class="font-extrabold text-xs">حجز بالساعة</span>
                                <span class="text-[10px] text-slate-400">قاعة أو مكتب</span>
                            </label>
                            <label class="flex flex-col items-center justify-center cursor-pointer p-2.5 rounded-xl border border-slate-200 hover:border-blue-600 hover:bg-blue-50 transition text-center has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 has-[:checked]:text-blue-600">
                                <input type="radio" name="duration_mode" value="weekly" onchange="onDurationModeChange('weekly')" class="sr-only">
                                <span class="font-extrabold text-xs">اشتراك أسبوعي</span>
                                <span class="text-[10px] text-slate-400">7 أيام متواصلة</span>
                            </label>
                            <label class="flex flex-col items-center justify-center cursor-pointer p-2.5 rounded-xl border border-slate-200 hover:border-blue-600 hover:bg-blue-50 transition text-center has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 has-[:checked]:text-blue-600">
                                <input type="radio" name="duration_mode" value="monthly" onchange="onDurationModeChange('monthly')" class="sr-only">
                                <span class="font-extrabold text-xs">اشتراك شهري</span>
                                <span class="text-[10px] text-slate-400">30 يوم</span>
                            </label>
                        </div>
                    </div>

                    {{-- 3. Room Selection --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">القاعة أو المساحة</label>
                        <select name="room_id" id="ab-room-id" class="w-full bg-slate-50 border border-[#E5E2DC] text-slate-800 rounded-xl px-3.5 py-2.5 text-xs focus:border-[#4E8F35] focus:bg-white outline-none">
                            <option value="">مساحة عامة / بدون تحديد غرفة</option>
                            @foreach($rooms as $r)
                            <option value="{{ $r->id }}">{{ $r->name }} ({{ $r->type_label }}) - سعة {{ $r->capacity }} فرد</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 4. Date & Time Inputs --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">تاريخ ووقت البدء <span class="text-rose-500">*</span></label>
                            <input type="datetime-local" name="start_at" id="ab-start-at" required onchange="calculateEndAtFromMode()"
                                class="w-full bg-slate-50 border border-[#E5E2DC] text-slate-800 rounded-xl px-3 py-2 text-xs focus:border-[#4E8F35] focus:bg-white outline-none font-mono">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">تاريخ ووقت الانتهاء <span class="text-rose-500">*</span></label>
                            <input type="datetime-local" name="end_at" id="ab-end-at" required
                                class="w-full bg-slate-50 border border-[#E5E2DC] text-slate-800 rounded-xl px-3 py-2 text-xs focus:border-[#4E8F35] focus:bg-white outline-none font-mono">
                        </div>
                    </div>

                    {{-- 5. Notes --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">ملاحظات وتفاصيل إضافية</label>
                        <textarea name="notes" id="ab-notes" rows="2" placeholder="مثال: تجهيز بروجكتور، عدد الحضور المتوقع..."
                            class="w-full bg-slate-50 border border-[#E5E2DC] text-slate-800 rounded-xl px-3.5 py-2 text-xs focus:border-[#4E8F35] focus:bg-white outline-none resize-none"></textarea>
                    </div>

                    {{-- Modal Submit Actions --}}
                    <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                        <button type="button" onclick="closeModal('modal-add-booking')" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all">
                            إلغاء
                        </button>
                        <button type="submit" class="px-6 py-2.5 bg-[#4E8F35] hover:bg-[#3F742B] text-white rounded-xl text-xs font-black shadow-xs flex items-center gap-1.5 transition-all cursor-pointer">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                            <span>حفظ وتأكيد الحجز</span>
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
{{-- Load FullCalendar 6.x Bundle with Locale support --}}
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

<script>
    // ── Global State & Data ──
    const rawCalendarEvents = @json($calendarEvents);
    let currentFilteredEvents = [...rawCalendarEvents];
    let calendarInstance = null;
    let currentViewMode = 'calendar'; // 'calendar' or 'table'
    let currentDurationMode = 'hourly';

    // ── Helper Modal Open/Close ──
    function openModal(id) {
        const el = document.getElementById(id);
        if (el) el.classList.remove('hidden');
    }
    function closeModal(id) {
        const el = document.getElementById(id);
        if (el) el.classList.add('hidden');
    }

    // ── Initialize FullCalendar on DOMContentLoaded ──
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('fullcalendar-booking');
        if (!calendarEl) return;

        calendarInstance = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            direction: 'rtl',
            locale: 'ar',
            firstDay: 6, // Saturday in Egypt / Arab countries
            headerToolbar: false, // Managed by our custom top bar
            events: currentFilteredEvents,
            eventDisplay: 'block', // Force full solid block badges instead of tiny dots
            displayEventTime: true,
            displayEventEnd: false,
            editable: false,
            selectable: true,
            selectMirror: true,
            dayMaxEvents: 4,
            height: 'auto',
            slotMinTime: '08:00:00',
            slotMaxTime: '24:00:00',
            slotDuration: '01:00:00',
            expandRows: true,
            nowIndicator: true,
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                meridiem: 'short',
                hour12: true
            },

            // ── Custom Event Content Renderer (Solid, Crisp Badges) ──
            eventContent: function (arg) {
                const props = arg.event.extendedProps || {};
                const timeStr = arg.timeText || '';
                const titleStr = arg.event.title || '';
                const isMulti = props.is_multi_day || arg.event.allDay;

                return {
                    html: `
                        <div class="w-full flex items-center gap-1.5 px-2 py-1 text-white font-bold text-[11px] leading-tight overflow-hidden rounded-md shadow-2xs">
                            ${timeStr ? `<span class="font-mono text-[10px] opacity-90 shrink-0 font-extrabold bg-black/15 px-1 rounded">${timeStr}</span>` : ''}
                            <span class="truncate flex-1 font-bold">${titleStr}</span>
                            ${isMulti ? `<span class="text-[9px] bg-white/20 px-1 rounded font-extrabold shrink-0">اشتراك</span>` : ''}
                        </div>
                    `
                };
            },

            // ── Event Click Handler ──
            eventClick: function (info) {
                info.jsEvent.preventDefault();
                showBookingDetails(info.event.extendedProps);
            },

            // ── Slot Select Handler (Direct Click to Book) ──
            select: function (info) {
                openNewBookingModalWithDates(info.startStr, info.endStr, info.allDay);
            },

            // ── Dates Set (Update Period Title in Header) ──
            datesSet: function (dateInfo) {
                updateCalendarTitle(dateInfo.view.title);
            },

            // ── Custom Tooltip ──
            eventDidMount: function(info) {
                const props = info.event.extendedProps;
                info.el.setAttribute('title', `${props.customer_name} - ${props.room_name} (${props.duration_formatted || ''})`);
            }
        });

        calendarInstance.render();
    });

    // ── Custom Toolbar Controls ──
    function calendarPrev() {
        if (calendarInstance) calendarInstance.prev();
    }
    function calendarNext() {
        if (calendarInstance) calendarInstance.next();
    }
    function calendarToday() {
        if (calendarInstance) calendarInstance.today();
    }

    function changeCalendarView(viewName, btn) {
        toggleViewMode('calendar');
        if (calendarInstance) {
            calendarInstance.changeView(viewName);
        }
        document.querySelectorAll('.cal-view-btn').forEach(b => {
            b.classList.remove('bg-white', 'text-[#4E8F35]', 'shadow-xs');
            b.classList.add('text-slate-600');
        });
        if (btn) {
            btn.classList.add('bg-white', 'text-[#4E8F35]', 'shadow-xs');
            btn.classList.remove('text-slate-600');
        }
    }

    function updateCalendarTitle(title) {
        const titleEl = document.getElementById('calendar-period-title');
        if (titleEl) titleEl.textContent = title;
    }

    function toggleViewMode(mode) {
        currentViewMode = mode;
        const calContainer = document.getElementById('calendar-view-container');
        const tblContainer = document.getElementById('table-view-container');
        const tblBtn = document.getElementById('btn-toggle-table');

        if (mode === 'table') {
            calContainer.classList.add('hidden');
            tblContainer.classList.remove('hidden');
            if (tblBtn) {
                tblBtn.classList.add('bg-white', 'text-[#4E8F35]', 'shadow-xs');
                tblBtn.classList.remove('text-slate-600');
            }
        } else {
            calContainer.classList.remove('hidden');
            tblContainer.classList.add('hidden');
            if (calendarInstance) {
                setTimeout(() => calendarInstance.updateSize(), 50);
            }
        }
    }

    // ── Filtering Logic ──
    let filterDebounceTimeout = null;
    function applyCalendarFiltersDebounced() {
        clearTimeout(filterDebounceTimeout);
        filterDebounceTimeout = setTimeout(applyCalendarFilters, 250);
    }

    function applyCalendarFilters() {
        const roomVal = document.getElementById('filter-room')?.value || '';
        const typeVal = document.getElementById('filter-type')?.value || '';
        const statusVal = document.getElementById('filter-status')?.value || '';
        const searchVal = (document.getElementById('filter-search')?.value || '').trim().toLowerCase();

        currentFilteredEvents = rawCalendarEvents.filter(ev => {
            const p = ev.extendedProps || {};
            // Room Filter
            if (roomVal && String(p.room_id) !== String(roomVal)) return false;
            // Status Filter
            if (statusVal && p.status !== statusVal) return false;
            // Type Filter
            if (typeVal === 'subscription' && !p.is_multi_day && !p.booking_type_label?.includes('اشتراك')) return false;
            if (typeVal === 'private_room' && (p.is_multi_day || !p.booking_type_label?.includes('خاصة'))) return false;
            if (typeVal === 'shared_desk' && (p.is_multi_day || !p.booking_type_label?.includes('مشتركة'))) return false;
            // Search Filter
            if (searchVal) {
                const searchStr = `${p.booking_number} ${p.customer_name} ${p.customer_phone} ${p.room_name}`.toLowerCase();
                if (!searchStr.includes(searchVal)) return false;
            }
            return true;
        });

        if (calendarInstance) {
            calendarInstance.removeAllEvents();
            calendarInstance.addEventSource(currentFilteredEvents);
        }
    }

    // ── Booking Details Modal Logic ──
    function showBookingDetails(props) {
        if (!props) return;

        document.getElementById('dtl-booking-number').textContent = `حجز #${props.booking_number}`;
        document.getElementById('dtl-type-label').textContent = props.booking_type_label || 'حجز مساحة';
        document.getElementById('dtl-cust-name').textContent = props.customer_name || 'عميل مباشر';
        document.getElementById('dtl-cust-phone').textContent = props.customer_phone || '--';
        document.getElementById('dtl-cust-avatar').textContent = (props.customer_name ? props.customer_name.charAt(0) : 'ع');
        document.getElementById('dtl-room-name').textContent = props.room_name || 'مساحة عامة';
        document.getElementById('dtl-room-type').textContent = props.room_type || '';
        document.getElementById('dtl-duration').textContent = props.duration_formatted || `${props.duration_hours || 1} ساعة`;
        document.getElementById('dtl-workspace-type').textContent = props.workspace_type_name || '';
        document.getElementById('dtl-start-time').textContent = props.start_formatted || '';
        document.getElementById('dtl-end-time').textContent = props.end_formatted || '';

        // Status Badge
        const badge = document.getElementById('dtl-status-badge');
        badge.textContent = props.status_label || 'مؤكد';

        // Color Indicator
        const colorInd = document.getElementById('dtl-color-indicator');
        if (colorInd) {
            if (props.is_multi_day) colorInd.className = 'size-3 rounded-full bg-blue-600';
            else if (props.status === 'checked_in') colorInd.className = 'size-3 rounded-full bg-emerald-600';
            else colorInd.className = 'size-3 rounded-full bg-[#4E8F35]';
        }

        // Notes
        const notesContainer = document.getElementById('dtl-notes-container');
        if (props.notes && props.notes.trim()) {
            document.getElementById('dtl-notes').textContent = props.notes;
            notesContainer.classList.remove('hidden');
        } else {
            notesContainer.classList.add('hidden');
        }

        // Actions
        const checkinForm = document.getElementById('form-dtl-checkin');
        const deleteForm = document.getElementById('form-dtl-delete');
        const checkinBtn = document.getElementById('btn-dtl-checkin');

        checkinForm.action = `/bookings/${props.id}/check-in`;
        deleteForm.action = `/bookings/${props.id}`;

        if (props.can_checkin) {
            checkinBtn.classList.remove('hidden');
        } else {
            checkinBtn.classList.add('hidden');
        }

        openModal('modal-booking-details');
    }

    function showBookingDetailsById(bookingId) {
        const found = rawCalendarEvents.find(ev => String(ev.extendedProps?.id) === String(bookingId));
        if (found) {
            showBookingDetails(found.extendedProps);
        }
    }

    // ── Add New Booking Modal Logic ──
    function openNewBookingModal() {
        const now = new Date();
        const startStr = formatDateTimeLocal(now);
        now.setHours(now.getHours() + 2);
        const endStr = formatDateTimeLocal(now);

        openNewBookingModalWithDates(startStr, endStr, false);
    }

    function openNewBookingModalWithDates(startStr, endStr, allDay) {
        const form = document.getElementById('form-add-booking');
        if (form) form.reset();

        const startInput = document.getElementById('ab-start-at');
        const endInput = document.getElementById('ab-end-at');

        let sDate = new Date(startStr);
        if (isNaN(sDate.getTime())) sDate = new Date();

        // If clicked in month view without time, default to 10:00 AM
        if (allDay) {
            sDate.setHours(10, 0, 0, 0);
        }

        let eDate = new Date(sDate);
        eDate.setHours(eDate.getHours() + 2);

        if (startInput) startInput.value = formatDateTimeLocal(sDate);
        if (endInput) endInput.value = formatDateTimeLocal(eDate);

        // Reset radio to hourly
        const hourlyRadio = document.querySelector('input[name="duration_mode"][value="hourly"]');
        if (hourlyRadio) hourlyRadio.checked = true;
        onDurationModeChange('hourly');

        openModal('modal-add-booking');
    }

    function onDurationModeChange(mode) {
        currentDurationMode = mode;
        calculateEndAtFromMode();
    }

    function calculateEndAtFromMode() {
        const startInput = document.getElementById('ab-start-at');
        const endInput = document.getElementById('ab-end-at');
        if (!startInput || !endInput || !startInput.value) return;

        const sDate = new Date(startInput.value);
        if (isNaN(sDate.getTime())) return;

        let eDate = new Date(sDate);
        if (currentDurationMode === 'weekly') {
            eDate.setDate(eDate.getDate() + 7);
            endInput.value = formatDateTimeLocal(eDate);
            endInput.readOnly = true;
        } else if (currentDurationMode === 'monthly') {
            eDate.setDate(eDate.getDate() + 30);
            endInput.value = formatDateTimeLocal(eDate);
            endInput.readOnly = true;
        } else {
            endInput.readOnly = false;
        }
    }

    function formatDateTimeLocal(d) {
        const pad = n => String(n).padStart(2, '0');
        const y = d.getFullYear();
        const m = pad(d.getMonth() + 1);
        const day = pad(d.getDate());
        const h = pad(d.getHours());
        const min = pad(d.getMinutes());
        return `${y}-${m}-${day}T${h}:${min}`;
    }
</script>

<style>
/* ── FullCalendar DDT Custom Styling (Solid, Flat, Modern) ── */
.fc {
    --fc-border-color: #E5E2DC;
    --fc-today-bg-color: #F5F9F2;
    --fc-neutral-bg-color: #F8F7F4;
    --fc-event-border-color: transparent;
    font-size: 11.5px;
}

.fc-theme-standard td, .fc-theme-standard th {
    border-color: #E5E2DC !important;
}

.fc-col-header-cell {
    background-color: #F8F7F4;
    padding: 10px 0 !important;
    font-weight: 800;
    color: #303334;
    text-transform: uppercase;
}

.fc-daygrid-day-number {
    font-weight: 800;
    color: #303334;
    padding: 6px 8px !important;
    font-family: ui-monospace, monospace;
}

.fc-day-today .fc-daygrid-day-number {
    background-color: #4E8F35;
    color: #ffffff !important;
    border-radius: 8px;
    display: inline-block;
}

/* Event Styling */
.fc-daygrid-event-dot {
    display: none !important;
}
.fc-daygrid-event {
    margin-top: 2px !important;
    margin-bottom: 2px !important;
    border-radius: 6px !important;
}
.fc-event {
    border: none !important;
    cursor: pointer;
    transition: transform 0.1s ease, filter 0.1s ease;
}
.fc-event-main {
    padding: 0 !important;
}
.fc-event:hover {
    filter: brightness(1.1);
    transform: translateY(-1px);
}

.fc-timegrid-slot {
    height: 38px !important;
}
.fc-timegrid-slot-label {
    font-family: ui-monospace, monospace;
    font-weight: 600;
    color: #73777A;
}

.fc-timegrid-now-indicator-line {
    border-color: #4E8F35 !important;
    border-width: 2px !important;
}
.fc-timegrid-now-indicator-arrow {
    border-color: #4E8F35 !important;
}
</style>
@endsection
