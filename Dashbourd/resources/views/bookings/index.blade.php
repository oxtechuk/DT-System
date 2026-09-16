@extends('shared.vertical', ['title' => 'جدول ومواعيد حجوزات القاعات — DT-System'])

@section('content')

    {{-- Page Header --}}
    <div class="page-header-container mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="size-2 rounded-full bg-[#4E8F35]"></span>
                <span class="text-xs font-semibold uppercase tracking-wider text-[#4E8F35]">حجوزات المساحات والقاعات</span>
            </div>
            <h2 class="text-2xl font-bold text-[#303334] tracking-tight mt-1">
                جدول ومواعيد الحجوزات
            </h2>
            <p class="text-xs text-neutral-500 mt-0.5">تنظيم ومتابعة حجوزات القاعات وغرف الاجتماعات، تأكيد المواعيد وتسجيل الحضور</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('rooms.index') }}"
                class="px-4 py-2.5 bg-white border border-[#E5E2DC] hover:border-[#4E8F35] text-[#303334] rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-2">
                <svg class="size-4 text-[#4E8F35]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                    <line x1="3" y1="9" x2="21" y2="9"/>
                    <line x1="9" y1="21" x2="9" y2="9"/>
                </svg>
                <span>إدارة القاعات والمساحات</span>
            </a>

            <button onclick="openModal('addBookingModal')"
                class="px-4 py-2.5 bg-[#4E8F35] hover:bg-[#437c2e] text-white rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-2">
                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>تسجيل حجز قاعة جديد</span>
            </button>
        </div>
    </div>

    {{-- Feedback Alerts --}}
    @if(session('success'))
        <div class="p-3.5 mb-6 rounded-xl bg-[#EBF4E8] border border-[#DCE8D4] text-[#303334] text-xs font-semibold flex items-center gap-2">
            <svg class="size-4 text-[#4E8F35] shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Room Fast Navigation & Availability Cards --}}
    <div class="mb-6">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold text-[#303334] flex items-center gap-1.5">
                <svg class="size-3.5 text-[#4E8F35]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect width="18" height="18" x="3" y="4" rx="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/>
                </svg>
                توزيع حجوزات القاعات اليوم ({{ $todayBookingsCount }} حجز):
            </span>
            @if($roomId)
                <a href="{{ route('bookings.index') }}" class="text-xs text-[#4E8F35] hover:underline font-bold">
                    عرض جميع القاعات &larr;
                </a>
            @endif
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            {{-- All Rooms Card --}}
            <a href="{{ route('bookings.index') }}"
               class="p-3 rounded-2xl border transition-all text-center flex flex-col justify-between {{ empty($roomId) ? 'bg-[#EBF4E8] border-[#4E8F35] shadow-xs' : 'bg-white border-[#E5E2DC] hover:border-neutral-400' }}">
                <div>
                    <span class="text-xs font-bold {{ empty($roomId) ? 'text-[#3B6E28]' : 'text-[#303334]' }}">كل القاعات</span>
                    <p class="text-[11px] text-neutral-500 mt-0.5">{{ $rooms->count() }} مساحات</p>
                </div>
                <div class="mt-2 font-mono font-bold text-sm {{ empty($roomId) ? 'text-[#4E8F35]' : 'text-neutral-700' }}">
                    {{ $todayBookingsCount }} اليوم
                </div>
            </a>

            {{-- Specific Room Cards --}}
            @foreach($rooms as $room)
                @php
                    $isSelected = ($roomId == $room->id);
                    $roomBookingsToday = $activeRoomsBookings[$room->id] ?? 0;
                @endphp
                <a href="{{ route('bookings.index', ['room_id' => $room->id]) }}"
                   class="p-3 rounded-2xl border transition-all text-center flex flex-col justify-between {{ $isSelected ? 'bg-[#EBF4E8] border-[#4E8F35] shadow-xs' : 'bg-white border-[#E5E2DC] hover:border-[#4E8F35]' }}">
                    <div>
                        <span class="text-xs font-bold truncate block {{ $isSelected ? 'text-[#3B6E28]' : 'text-[#303334]' }}">{{ $room->name }}</span>
                        <span class="text-[10px] text-neutral-400">سعة {{ $room->capacity }} أفراد</span>
                    </div>
                    <div class="mt-2 flex items-center justify-center gap-1.5">
                        <span class="size-1.5 rounded-full {{ $roomBookingsToday > 0 ? 'bg-[#4E8F35]' : 'bg-neutral-300' }}"></span>
                        <span class="text-[11px] font-mono font-semibold {{ $roomBookingsToday > 0 ? 'text-[#4E8F35]' : 'text-neutral-500' }}">
                            {{ $roomBookingsToday }} حجز
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-2xl border border-[#E5E2DC] p-4 mb-6 shadow-xs">
        <form method="GET" action="{{ route('bookings.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
            {{-- Filter by Room --}}
            <div>
                <label class="block text-xs font-bold text-[#303334] mb-1.5">القاعة / الغرفة</label>
                <select name="room_id" class="w-full px-3 py-2 text-xs bg-[#F8F7F4] border border-[#E5E2DC] rounded-xl focus:border-[#4E8F35] focus:bg-white focus:outline-none text-[#303334]">
                    <option value="">جميع القاعات والغرف</option>
                    @foreach($rooms as $r)
                        <option value="{{ $r->id }}" {{ $roomId == $r->id ? 'selected' : '' }}>
                            {{ $r->name }} (سعة {{ $r->capacity }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter by Status --}}
            <div>
                <label class="block text-xs font-bold text-[#303334] mb-1.5">حالة الحجز</label>
                <select name="status" class="w-full px-3 py-2 text-xs bg-[#F8F7F4] border border-[#E5E2DC] rounded-xl focus:border-[#4E8F35] focus:bg-white focus:outline-none text-[#303334]">
                    <option value="">جميع الحالات</option>
                    <option value="confirmed" {{ $status == 'confirmed' ? 'selected' : '' }}>مؤكد</option>
                    <option value="checked_in" {{ $status == 'checked_in' ? 'selected' : '' }}>تم الحضور (جلسة نشطة)</option>
                    <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                    <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>مكتمل</option>
                    <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                </select>
            </div>

            {{-- Filter by Date --}}
            <div>
                <label class="block text-xs font-bold text-[#303334] mb-1.5">تاريخ الحجز</label>
                <input type="date" name="date" value="{{ $date }}"
                    class="w-full px-3 py-2 text-xs bg-[#F8F7F4] border border-[#E5E2DC] rounded-xl focus:border-[#4E8F35] focus:bg-white focus:outline-none text-[#303334]">
            </div>

            {{-- Search input --}}
            <div>
                <label class="block text-xs font-bold text-[#303334] mb-1.5">بحث بالعميل أو رقم الحجز</label>
                <input type="text" name="search" value="{{ $search }}" placeholder="اسم العميل أو الهاتف..."
                    class="w-full px-3 py-2 text-xs bg-[#F8F7F4] border border-[#E5E2DC] rounded-xl focus:border-[#4E8F35] focus:bg-white focus:outline-none text-[#303334]">
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-2">
                <button type="submit"
                    class="flex-1 py-2 px-4 bg-[#4E8F35] hover:bg-[#437c2e] text-white text-xs font-bold rounded-xl transition-colors text-center shadow-xs">
                    تطبيق الفلتر
                </button>
                @if($roomId || $status || $date || $search)
                    <a href="{{ route('bookings.index') }}"
                        class="py-2 px-3 bg-neutral-100 hover:bg-neutral-200 text-neutral-600 text-xs font-bold rounded-xl transition-colors">
                        إعادة ضبط
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Bookings Table --}}
    <div class="bg-white rounded-2xl border border-[#E5E2DC] shadow-xs overflow-hidden">
        <div class="p-4 border-b border-[#E5E2DC] flex items-center justify-between bg-[#F8F7F4]/60">
            <div class="flex items-center gap-2">
                <span class="size-2.5 rounded-full bg-[#4E8F35]"></span>
                <h3 class="font-bold text-[#303334] text-base">قائمة مواعيد الحجوزات</h3>
                <span class="text-xs text-neutral-500">({{ $bookings->total() }} حجز مسجل)</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-right text-xs border-collapse">
                <thead>
                    <tr class="border-b border-[#E5E2DC] bg-[#F8F7F4] text-neutral-500 font-bold">
                        <th class="py-3 px-4">رقم الحجز</th>
                        <th class="py-3 px-4">العميل</th>
                        <th class="py-3 px-4">الغرفة / القاعة</th>
                        <th class="py-3 px-4">موعد الحجز والمدة</th>
                        <th class="py-3 px-4">الحالة</th>
                        <th class="py-3 px-4">ملاحظات</th>
                        <th class="py-3 px-4 text-center">إجراءات الحجز</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E2DC]/70">
                    @forelse($bookings as $b)
                        <tr class="hover:bg-[#F8F7F4]/50 transition-colors">
                            <td class="py-3 px-4 font-mono font-bold text-[#303334]">
                                {{ $b->booking_number }}
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-bold text-[#303334] text-sm">{{ $b->customer->name ?? '-' }}</div>
                                @if($b->customer?->phone)
                                    <div class="text-[11px] font-mono text-neutral-400 dir-ltr text-right mt-0.5">{{ $b->customer->phone }}</div>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-bold text-[#303334] flex items-center gap-1.5">
                                    <span class="size-2 rounded-full bg-[#4E8F35]"></span>
                                    <span>{{ $b->room->name ?? 'غير محددة' }}</span>
                                </div>
                                @if($b->room)
                                    <span class="text-[10px] text-neutral-400">سعة {{ $b->room->capacity }} أفراد</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-mono text-[#303334] font-semibold">
                                    {{ $b->start_at ? $b->start_at->format('Y-m-d — h:i A') : '-' }}
                                </div>
                                <div class="text-[11px] text-neutral-500 flex items-center gap-1 mt-0.5">
                                    <span>إلى: {{ $b->end_at ? $b->end_at->format('h:i A') : '-' }}</span>
                                    @if($b->duration_hours > 0)
                                        <span class="px-1.5 py-0.2 rounded bg-neutral-100 text-neutral-700 font-bold">({{ $b->duration_hours }} ساعة)</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $b->status_badge_class }}">
                                    {{ $b->status_label }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-neutral-500 max-w-xs truncate">
                                {{ $b->notes ?: '-' }}
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center justify-center gap-1.5">
                                    {{-- Check-in Button if confirmed or pending --}}
                                    @if(in_array($b->status, ['confirmed', 'pending']))
                                        <form method="POST" action="{{ route('bookings.check-in', $b) }}">
                                            @csrf
                                            <button type="submit"
                                                title="تسجيل حضور وبدء جلسة فورية في القاعة"
                                                class="px-2.5 py-1 rounded-lg bg-[#EBF4E8] hover:bg-[#DCE8D4] text-[#3B6E28] font-bold text-xs transition-colors flex items-center gap-1 shadow-2xs">
                                                <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                </svg>
                                                <span>تسجيل حضور</span>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Edit Button --}}
                                    <button onclick="openEditBookingModal({{ json_encode($b) }})"
                                        title="تعديل موعد الحجز"
                                        class="p-1.5 rounded-lg border border-[#E5E2DC] hover:border-[#4E8F35] text-neutral-600 hover:text-[#4E8F35] transition-colors">
                                        <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </button>

                                    {{-- Delete Button --}}
                                    <button onclick="openDeleteBookingModal({{ $b->id }}, '{{ $b->booking_number }}', '{{ addslashes($b->customer?->name ?? 'العميل') }}')"
                                        title="إلغاء / حذف الحجز"
                                        class="p-1.5 rounded-lg border border-[#E5E2DC] hover:border-rose-300 text-neutral-400 hover:text-rose-600 transition-colors">
                                        <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-neutral-400 text-xs">
                                لا توجد حجوزات مسجلة مطابقة للبحث أو الفلتر المحدد.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
            <div class="p-4 border-t border-[#E5E2DC]">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>

    {{-- MODAL: Add Booking --}}
    <div id="addBookingModal" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-xs hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl border border-[#E5E2DC] shadow-xl w-full max-w-lg overflow-hidden animate-in fade-in zoom-in-95 duration-150">
            <div class="px-5 py-4 border-b border-[#E5E2DC] flex items-center justify-between bg-[#F8F7F4]">
                <div class="flex items-center gap-2">
                    <span class="size-2 rounded-full bg-[#4E8F35]"></span>
                    <h3 class="font-bold text-[#303334] text-sm">تسجيل حجز قاعة / مساحة جديد</h3>
                </div>
                <button type="button" onclick="closeModal('addBookingModal')" class="text-neutral-400 hover:text-neutral-600">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>
            </div>

            <form action="{{ route('bookings.store') }}" method="POST" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-[#303334] mb-1.5">العميل صاحب الحجز *</label>
                    <select name="customer_id" required
                        class="w-full px-3 py-2 text-xs bg-[#F8F7F4] border border-[#E5E2DC] rounded-xl focus:border-[#4E8F35] focus:bg-white focus:outline-none text-[#303334]">
                        <option value="">اختر العميل...</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->phone }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-[#303334] mb-1.5">القاعة / الغرفة *</label>
                        <select name="room_id" required
                            class="w-full px-3 py-2 text-xs bg-[#F8F7F4] border border-[#E5E2DC] rounded-xl focus:border-[#4E8F35] focus:bg-white focus:outline-none text-[#303334]">
                            <option value="">اختر القاعة...</option>
                            @foreach($rooms as $r)
                                <option value="{{ $r->id }}">
                                    {{ $r->name }} (سعة {{ $r->capacity }} أفراد)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#303334] mb-1.5">نوع المساحة / التسعير</label>
                        <select name="workspace_type_id"
                            class="w-full px-3 py-2 text-xs bg-[#F8F7F4] border border-[#E5E2DC] rounded-xl focus:border-[#4E8F35] focus:bg-white focus:outline-none text-[#303334]">
                            @foreach($workspaceTypes as $wt)
                                <option value="{{ $wt->id }}">{{ $wt->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-[#303334] mb-1.5">من تاريخ ووقت *</label>
                        <input type="datetime-local" name="start_at" required
                            class="w-full px-3 py-2 text-xs bg-[#F8F7F4] border border-[#E5E2DC] rounded-xl focus:border-[#4E8F35] focus:bg-white focus:outline-none text-[#303334]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#303334] mb-1.5">إلى تاريخ ووقت *</label>
                        <input type="datetime-local" name="end_at" required
                            class="w-full px-3 py-2 text-xs bg-[#F8F7F4] border border-[#E5E2DC] rounded-xl focus:border-[#4E8F35] focus:bg-white focus:outline-none text-[#303334]">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#303334] mb-1.5">ملاحظات وطلبات خاصة (اختياري)</label>
                    <textarea name="notes" rows="2" placeholder="عدد الحضور المتوقع، شاشة عرض، تجهيز ضيافة..."
                        class="w-full px-3 py-2 text-xs bg-[#F8F7F4] border border-[#E5E2DC] rounded-xl focus:border-[#4E8F35] focus:bg-white focus:outline-none text-[#303334]"></textarea>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2 border-t border-[#E5E2DC]">
                    <button type="button" onclick="closeModal('addBookingModal')"
                        class="px-3.5 py-2 text-xs font-bold text-neutral-600 hover:text-neutral-800 bg-neutral-100 rounded-xl transition-colors">
                        إلغاء
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-xs font-bold text-white bg-[#4E8F35] hover:bg-[#437c2e] rounded-xl transition-all shadow-sm">
                        تأكيد وتسجيل الحجز
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL: Edit Booking --}}
    <div id="editBookingModal" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-xs hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl border border-[#E5E2DC] shadow-xl w-full max-w-lg overflow-hidden animate-in fade-in zoom-in-95 duration-150">
            <div class="px-5 py-4 border-b border-[#E5E2DC] flex items-center justify-between bg-[#F8F7F4]">
                <div class="flex items-center gap-2">
                    <span class="size-2 rounded-full bg-[#4E8F35]"></span>
                    <h3 class="font-bold text-[#303334] text-sm">تعديل بيانات الحجز</h3>
                </div>
                <button type="button" onclick="closeModal('editBookingModal')" class="text-neutral-400 hover:text-neutral-600">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="editBookingForm" method="POST" class="p-5 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-[#303334] mb-1.5">العميل *</label>
                    <select id="editCustomerId" name="customer_id" required
                        class="w-full px-3 py-2 text-xs bg-[#F8F7F4] border border-[#E5E2DC] rounded-xl focus:border-[#4E8F35] focus:bg-white focus:outline-none text-[#303334]">
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->phone }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-[#303334] mb-1.5">القاعة / الغرفة *</label>
                        <select id="editRoomId" name="room_id" required
                            class="w-full px-3 py-2 text-xs bg-[#F8F7F4] border border-[#E5E2DC] rounded-xl focus:border-[#4E8F35] focus:bg-white focus:outline-none text-[#303334]">
                            @foreach($rooms as $r)
                                <option value="{{ $r->id }}">
                                    {{ $r->name }} (سعة {{ $r->capacity }} أفراد)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#303334] mb-1.5">حالة الحجز *</label>
                        <select id="editStatus" name="status" required
                            class="w-full px-3 py-2 text-xs bg-[#F8F7F4] border border-[#E5E2DC] rounded-xl focus:border-[#4E8F35] focus:bg-white focus:outline-none text-[#303334]">
                            <option value="confirmed">مؤكد</option>
                            <option value="checked_in">تم تسجيل الدخول (جلسة نشطة)</option>
                            <option value="pending">قيد الانتظار</option>
                            <option value="completed">مكتمل</option>
                            <option value="cancelled">ملغي</option>
                            <option value="no_show">لم يحضر</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-[#303334] mb-1.5">من تاريخ ووقت *</label>
                        <input type="datetime-local" id="editStartAt" name="start_at" required
                            class="w-full px-3 py-2 text-xs bg-[#F8F7F4] border border-[#E5E2DC] rounded-xl focus:border-[#4E8F35] focus:bg-white focus:outline-none text-[#303334]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#303334] mb-1.5">إلى تاريخ ووقت *</label>
                        <input type="datetime-local" id="editEndAt" name="end_at" required
                            class="w-full px-3 py-2 text-xs bg-[#F8F7F4] border border-[#E5E2DC] rounded-xl focus:border-[#4E8F35] focus:bg-white focus:outline-none text-[#303334]">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#303334] mb-1.5">ملاحظات الحجز</label>
                    <textarea id="editNotes" name="notes" rows="2"
                        class="w-full px-3 py-2 text-xs bg-[#F8F7F4] border border-[#E5E2DC] rounded-xl focus:border-[#4E8F35] focus:bg-white focus:outline-none text-[#303334]"></textarea>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2 border-t border-[#E5E2DC]">
                    <button type="button" onclick="closeModal('editBookingModal')"
                        class="px-3.5 py-2 text-xs font-bold text-neutral-600 hover:text-neutral-800 bg-neutral-100 rounded-xl transition-colors">
                        إلغاء
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-xs font-bold text-white bg-[#4E8F35] hover:bg-[#437c2e] rounded-xl transition-all shadow-sm">
                        تحديث الحجز
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL: Delete Booking --}}
    <div id="deleteBookingModal" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-xs hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl border border-[#E5E2DC] shadow-xl w-full max-w-sm overflow-hidden animate-in fade-in zoom-in-95 duration-150">
            <div class="px-5 py-4 border-b border-[#E5E2DC] bg-[#F8F7F4] flex items-center justify-between">
                <h3 class="font-bold text-rose-600 text-sm">إلغاء / حذف الحجز</h3>
                <button type="button" onclick="closeModal('deleteBookingModal')" class="text-neutral-400 hover:text-neutral-600">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="deleteBookingForm" method="POST" class="p-5 space-y-4">
                @csrf
                @method('DELETE')
                <p class="text-xs text-[#303334]">
                    هل أنت متأكد من حذف الحجز رقم <strong id="deleteBookingNumber" class="text-rose-600"></strong> للعميل <strong id="deleteCustomerName"></strong>؟
                </p>

                <div class="pt-2 flex items-center justify-end gap-2 border-t border-[#E5E2DC]">
                    <button type="button" onclick="closeModal('deleteBookingModal')"
                        class="px-3.5 py-2 text-xs font-bold text-neutral-600 hover:text-neutral-800 bg-neutral-100 rounded-xl transition-colors">
                        إلغاء
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition-all shadow-sm">
                        تأكيد الحذف
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function formatForInput(dtStr) {
        if (!dtStr) return '';
        const d = new Date(dtStr);
        if (isNaN(d.getTime())) return '';
        const pad = (n) => String(n).padStart(2, '0');
        return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
    }

    function openEditBookingModal(booking) {
        const form = document.getElementById('editBookingForm');
        form.action = `/bookings/${booking.id}`;
        document.getElementById('editCustomerId').value = booking.customer_id;
        document.getElementById('editRoomId').value = booking.room_id;
        document.getElementById('editStatus').value = booking.status;
        document.getElementById('editStartAt').value = formatForInput(booking.start_at);
        document.getElementById('editEndAt').value = formatForInput(booking.end_at);
        document.getElementById('editNotes').value = booking.notes || '';
        openModal('editBookingModal');
    }

    function openDeleteBookingModal(id, number, customerName) {
        const form = document.getElementById('deleteBookingForm');
        form.action = `/bookings/${id}`;
        document.getElementById('deleteBookingNumber').textContent = number;
        document.getElementById('deleteCustomerName').textContent = customerName;
        openModal('deleteBookingModal');
    }

    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            ['addBookingModal', 'editBookingModal', 'deleteBookingModal'].forEach(closeModal);
        }
    });
</script>
@endsection
