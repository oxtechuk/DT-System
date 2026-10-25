@extends('shared.vertical')

@section('title', 'إدارة المدرسين ونظام الأفيليت')

@section('content')
<div class="p-4 md:p-6 space-y-6" dir="rtl">

    {{-- Toast Notification --}}
    <div id="toast-notification" class="hidden fixed bottom-6 left-6 z-50 flex items-center gap-2.5 px-4 py-3 rounded-2xl bg-[#303334] text-white text-xs font-bold shadow-xl border border-white/10 transition-all duration-300">
        <span class="size-2 rounded-full bg-[#4E8F35]"></span>
        <span id="toast-message">تم نسخ الرابط بنجاح!</span>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-[#EBF4E8] border border-[#DCE8D4] text-[#303334] flex items-center justify-between text-xs font-bold shadow-2xs">
            <div class="flex items-center gap-2.5">
                <span class="size-6 rounded-lg bg-[#4E8F35] text-white flex items-center justify-center text-xs">✓</span>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-slate-400 hover:text-slate-700">✕</button>
        </div>
    @endif

    {{-- 1. Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 rounded-3xl border border-[#E5E2DC] shadow-2xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-lg text-[11px] font-black bg-[#EBF4E8] text-[#4E8F35] border border-[#DCE8D4]">
                    منظومة الأفيليت والزيارات
                </span>
                <span class="text-xs text-slate-400 font-mono">Affiliate & Teachers</span>
            </div>
            <h1 class="text-xl md:text-2xl font-black text-[#303334]">إدارة المدرسين ونظام الأفيليت</h1>
            <p class="text-xs text-[#73777A] font-medium mt-0.5">توليد أكواد الـ QR، ضبط نسب الكوميشن، ومتابعة زيارات وأرباح المدرسين بكل سهولة</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            {{-- Settings Modal Button --}}
            <button type="button" data-bs-toggle="modal" data-bs-target="#settingsModal"
                    class="px-3.5 py-2 rounded-xl bg-[#F5F3EE] hover:bg-[#EAE7E0] border border-[#E5E2DC] text-[#303334] text-xs font-bold transition flex items-center gap-1.5 cursor-pointer">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                <span>إعدادات الكوميشن</span>
            </button>

            {{-- Commissions Report Link --}}
            <a href="{{ route('admin.teachers.commissions') }}"
               class="px-3.5 py-2 rounded-xl bg-white hover:bg-slate-50 border border-[#E5E2DC] text-[#303334] text-xs font-bold transition flex items-center gap-1.5 shadow-2xs">
                <svg width="15" height="15" class="text-[#4E8F35]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                <span>تقرير العمولات الكامل</span>
            </a>

            {{-- Add Teacher Button --}}
            <button type="button" data-bs-toggle="modal" data-bs-target="#addTeacherModal"
                    class="px-4 py-2 rounded-xl bg-[#4E8F35] hover:bg-[#3F742B] text-white text-xs font-extrabold transition flex items-center gap-1.5 shadow-xs cursor-pointer">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 4.5v15m7.5-7.5h-15"/></svg>
                <span>+ إضافة مدرس جديد</span>
            </button>
        </div>
    </div>

    {{-- 2. Stats KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Teachers Count --}}
        <div class="p-4 rounded-3xl bg-white border border-[#E5E2DC] shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-[#73777A] block">إجمالي المدرسين</span>
                <div class="text-2xl font-black text-[#303334] font-mono mt-0.5">{{ $teachers->total() }}</div>
                <span class="text-[10px] font-bold text-[#4E8F35] bg-[#EBF4E8] px-2 py-0.2 rounded-md mt-1 inline-block">
                    {{ $activeTeachersCount ?? $teachers->where('is_active', true)->count() }} مدرس نشط
                </span>
            </div>
            <div class="size-12 rounded-2xl bg-[#EBF4E8] text-[#4E8F35] flex items-center justify-center text-xl font-bold">
                👨‍🏫
            </div>
        </div>

        {{-- Card 2: Pending Commission --}}
        <div class="p-4 rounded-3xl bg-white border {{ $totalCommissionPending > 0 ? 'border-amber-300 ring-2 ring-amber-100' : 'border-[#E5E2DC]' }} shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-[#73777A] block">كوميشن معلق (جاهز للصرف)</span>
                <div class="text-2xl font-black text-amber-600 font-mono mt-0.5">{{ number_format($totalCommissionPending, 2) }} <span class="text-xs font-bold text-slate-500">ج.م</span></div>
                @if($totalCommissionPending > 0)
                    <span class="text-[10px] font-bold text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.2 rounded-md mt-1 inline-block">
                        مستحق للمدرسين الآن
                    </span>
                @else
                    <span class="text-[10px] font-bold text-slate-400 bg-slate-50 px-2 py-0.2 rounded-md mt-1 inline-block">
                        تمت تصفية كافة الحسابات
                    </span>
                @endif
            </div>
            <div class="size-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold">
                ⏳
            </div>
        </div>

        {{-- Card 3: Paid Commission --}}
        <div class="p-4 rounded-3xl bg-white border border-[#E5E2DC] shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-[#73777A] block">إجمالي الكوميشن المصروف</span>
                <div class="text-2xl font-black text-[#4E8F35] font-mono mt-0.5">{{ number_format($totalCommissionPaid, 2) }} <span class="text-xs font-bold text-slate-500">ج.م</span></div>
                <span class="text-[10px] font-bold text-[#4E8F35] bg-[#EBF4E8] px-2 py-0.2 rounded-md mt-1 inline-block">
                    مدفوع بالكامل
                </span>
            </div>
            <div class="size-12 rounded-2xl bg-[#EBF4E8] text-[#4E8F35] flex items-center justify-center text-xl font-bold">
                💰
            </div>
        </div>

        {{-- Card 4: Total Visits --}}
        <div class="p-4 rounded-3xl bg-white border border-[#E5E2DC] shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-[#73777A] block">إجمالي زيارات الطلاب عبر الـ QR</span>
                <div class="text-2xl font-black text-[#303334] font-mono mt-0.5">{{ $totalVisitsCount ?? 0 }}</div>
                <span class="text-[10px] font-bold text-blue-700 bg-blue-50 border border-blue-200 px-2 py-0.2 rounded-md mt-1 inline-block">
                    مسجلة من الأفيليت
                </span>
            </div>
            <div class="size-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-bold">
                📲
            </div>
        </div>
    </div>

    {{-- 3. Search & Live Filter Bar --}}
    <div class="bg-white p-4 rounded-3xl border border-[#E5E2DC] shadow-2xs flex flex-col md:flex-row items-center justify-between gap-3">
        <div class="relative w-full md:w-96">
            <span class="absolute start-3.5 top-3 text-slate-400">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            </span>
            <input type="text" id="teacher-search-input" oninput="filterTeachersTable()"
                   placeholder="بحث بالاسم، رقم الهاتف، أو كود الـ QR..."
                   class="w-full bg-[#F5F3EE] border border-[#E5E2DC] rounded-xl ps-10 pe-4 py-2.5 text-xs font-bold text-[#303334] outline-none focus:bg-white focus:border-[#4E8F35] transition">
        </div>

        <div class="flex items-center gap-2 w-full md:w-auto">
            <select id="teacher-status-filter" onchange="filterTeachersTable()"
                    class="bg-[#F5F3EE] border border-[#E5E2DC] rounded-xl px-3 py-2.5 text-xs font-bold text-[#303334] outline-none focus:border-[#4E8F35] cursor-pointer">
                <option value="all">كل الحالات</option>
                <option value="active">المدرسين النشطين فقط</option>
                <option value="inactive">الموقوفين فقط</option>
                <option value="pending">لديه كوميشن معلق</option>
            </select>

            <button type="button" onclick="resetTeachersFilter()" class="px-3 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition cursor-pointer">
                إعادة ضبط
            </button>
        </div>
    </div>

    {{-- 4. Teachers Table --}}
    <div class="bg-white rounded-3xl border border-[#E5E2DC] shadow-2xs overflow-hidden">
        @if($teachers->isEmpty())
            <div class="p-12 text-center space-y-3">
                <div class="size-16 rounded-full bg-[#EBF4E8] text-[#4E8F35] flex items-center justify-center text-3xl mx-auto">
                    👨‍🏫
                </div>
                <h3 class="text-base font-black text-[#303334]">لا يوجد مدرسين مسجلين بعد</h3>
                <p class="text-xs text-[#73777A] max-w-sm mx-auto font-medium">ابدأ الآن بإضافة أول مدرس لتوليد باركود (QR) خاص به وتحديد نسبة أرباحه للبدء في جلب الطلاب والعملاء لمساحة العمل.</p>
                <button type="button" data-bs-toggle="modal" data-bs-target="#addTeacherModal"
                        class="px-5 py-2.5 rounded-xl bg-[#4E8F35] hover:bg-[#3F742B] text-white text-xs font-extrabold transition shadow-xs inline-flex items-center gap-1.5 cursor-pointer mt-2">
                    + إضافة أول مدرس الآن
                </button>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-start text-xs border-collapse" id="teachers-main-table">
                    <thead>
                        <tr class="bg-[#F8F7F4] border-b border-[#E5E2DC] text-[#73777A] font-extrabold">
                            <th class="py-3 px-4 text-start">المدرس</th>
                            <th class="py-3 px-4 text-start">الباركود / QR Code</th>
                            <th class="py-3 px-4 text-center">نسبة الكوميشن</th>
                            <th class="py-3 px-4 text-center">زيارات الطلاب</th>
                            <th class="py-3 px-4 text-center">الكوميشن المعلق</th>
                            <th class="py-3 px-4 text-center">الحالة</th>
                            <th class="py-3 px-4 text-center">الإجراءات السريعة</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E5E2DC]/70">
                        @foreach($teachers as $teacher)
                        @php
                            $pendingComm = $teacher->pendingCommission();
                        @endphp
                        <tr class="hover:bg-[#F5F3EE]/50 transition teacher-row"
                            data-name="{{ mb_strtolower($teacher->user->name ?? '') }}"
                            data-phone="{{ $teacher->user->phone ?? '' }}"
                            data-email="{{ mb_strtolower($teacher->user->email ?? '') }}"
                            data-qr="{{ strtoupper($teacher->qr_code ?? '') }}"
                            data-status="{{ $teacher->is_active ? 'active' : 'inactive' }}"
                            data-has-pending="{{ $pendingComm > 0 ? 'true' : 'false' }}">
                            
                            {{-- Teacher Info --}}
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="size-9 rounded-xl bg-[#EBF4E8] text-[#4E8F35] flex items-center justify-center font-black text-sm shrink-0 border border-[#DCE8D4]">
                                        {{ mb_substr($teacher->user->name ?? 'م', 0, 1) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.teachers.show', $teacher) }}" class="font-extrabold text-[#303334] hover:text-[#4E8F35] text-xs transition block">
                                            {{ $teacher->user->name }}
                                        </a>
                                        <div class="text-[10px] text-slate-500 font-medium flex items-center gap-2 mt-0.5">
                                            <span>{{ $teacher->user->email }}</span>
                                            @if($teacher->user->phone)
                                                <span>•</span>
                                                <span class="font-mono text-slate-600">{{ $teacher->user->phone }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- QR Code & Quick Copy --}}
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2">
                                    <img src="{{ $teacher->qrImageUrl() }}" width="36" height="36" class="rounded-lg border border-[#E5E2DC] bg-white p-0.5 shrink-0" alt="QR Thumbnail">
                                    <div>
                                        <span class="font-mono font-black text-[#303334] text-[11px] bg-[#F5F3EE] px-2 py-0.5 rounded-md border border-[#E5E2DC] block">
                                            {{ $teacher->qr_code }}
                                        </span>
                                        <button type="button" onclick="copyTeacherLink('{{ $teacher->qrUrl() }}')"
                                                class="text-[10px] font-bold text-[#4E8F35] hover:underline flex items-center gap-1 mt-0.5 cursor-pointer">
                                            <span>📋 نسخ رابط الحجز</span>
                                        </button>
                                    </div>
                                </div>
                            </td>

                            {{-- Commission Rate --}}
                            <td class="py-3.5 px-4 text-center">
                                <span class="font-mono font-black text-xs text-[#303334] bg-[#EBF4E8] text-[#4E8F35] border border-[#DCE8D4] px-2.5 py-1 rounded-lg">
                                    {{ $teacher->commission_rate }}%
                                </span>
                            </td>

                            {{-- Visits Count --}}
                            <td class="py-3.5 px-4 text-center">
                                <span class="font-mono font-bold text-xs text-slate-700 bg-slate-100 px-2.5 py-0.5 rounded-md">
                                    {{ $teacher->visits_count }} طالب
                                </span>
                            </td>

                            {{-- Pending Commission --}}
                            <td class="py-3.5 px-4 text-center">
                                @if($pendingComm > 0)
                                    <div>
                                        <span class="font-mono font-black text-xs text-amber-700 bg-amber-50 border border-amber-200 px-2.5 py-1 rounded-lg inline-block">
                                            {{ number_format($pendingComm, 2) }} ج.م
                                        </span>
                                        <form action="{{ route('admin.teachers.pay', $teacher) }}" method="POST" class="mt-1" onsubmit="return confirm('تأكيد صرف وسداد الكوميشن المعلّق ({{ number_format($pendingComm, 2) }} ج.م) للمدرس؟')">
                                            @csrf
                                            <button type="submit" class="text-[10px] font-bold text-emerald-700 hover:text-emerald-900 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-2 py-0.5 rounded cursor-pointer transition">
                                                💰 صرف الآن
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-[10px] font-bold text-slate-400 font-mono">0.00 ج.م</span>
                                @endif
                            </td>

                            {{-- Status Badge --}}
                            <td class="py-3.5 px-4 text-center">
                                @if($teacher->is_active)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-[#EBF4E8] text-[#4E8F35] border border-[#DCE8D4]">
                                        نشط
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-rose-50 text-rose-600 border border-rose-200">
                                        موقوف
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                    {{-- QR Card Preview & Print Modal Trigger --}}
                                    <button type="button"
                                            onclick="openQrModal('{{ addslashes($teacher->user->name) }}', '{{ $teacher->qr_code }}', '{{ $teacher->qrImageUrl() }}', '{{ $teacher->qrUrl() }}')"
                                            class="px-2 py-1 rounded-lg bg-slate-100 hover:bg-[#EBF4E8] hover:text-[#4E8F35] text-slate-700 border border-slate-200 text-[11px] font-bold transition flex items-center gap-1 cursor-pointer"
                                            title="معاينة وطباعة الـ QR">
                                        <span>📱 QR</span>
                                    </button>

                                    {{-- Details Link --}}
                                    <a href="{{ route('admin.teachers.show', $teacher) }}"
                                       class="px-2 py-1 rounded-lg bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-[11px] font-bold transition flex items-center gap-1 shadow-2xs">
                                        <span>عرض</span>
                                    </a>

                                    {{-- Delete Button --}}
                                    <button type="button" onclick="confirmDelete({{ $teacher->id }})"
                                            class="p-1 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                            title="حذف المدرس">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18m-2 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    </button>
                                    <form id="delete-{{ $teacher->id }}" action="{{ route('admin.teachers.destroy', $teacher) }}" method="POST" class="hidden">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-[#E5E2DC] flex items-center justify-between">
                <span class="text-xs text-slate-500 font-bold">عرض {{ $teachers->count() }} من أصل {{ $teachers->total() }} مدرس</span>
                <div>{{ $teachers->links() }}</div>
            </div>
        @endif
    </div>

</div>

{{-- ════════════════════════════════════════════════════════ --}}
{{-- MODAL 1: إضافة مدرس جديد (مع حاسبة فورية للنسبة) --}}
{{-- ════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="addTeacherModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-3xl shadow-2xl p-6 bg-white text-[#303334]" dir="rtl">
            <div class="flex items-center justify-between pb-3 mb-4 border-b border-[#E5E2DC]">
                <div>
                    <h3 class="text-base font-black text-[#303334] flex items-center gap-2">
                        <span class="size-7 rounded-xl bg-[#EBF4E8] text-[#4E8F35] flex items-center justify-center font-bold text-sm">+</span>
                        <span>إضافة مدرس جديد في المنظومة</span>
                    </h3>
                    <p class="text-xs text-[#73777A]">سيتم توليد كود QR تلقائياً فور الحفظ لربطه بطلابه</p>
                </div>
                <button type="button" class="btn-close text-slate-400 hover:text-slate-700" data-bs-dismiss="modal">✕</button>
            </div>

            <form action="{{ route('admin.teachers.store') }}" method="POST" class="space-y-3.5 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">اسم المدرس <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required placeholder="مثال: أ. محمد محمود (مدرس لغة عربية)"
                           class="w-full bg-[#F5F3EE] border border-[#E5E2DC] rounded-xl px-3.5 py-2.5 text-xs font-bold outline-none focus:bg-white focus:border-[#4E8F35] transition">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">البريد الإلكتروني <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" required placeholder="teacher@example.com"
                               class="w-full bg-[#F5F3EE] border border-[#E5E2DC] rounded-xl px-3.5 py-2.5 text-xs font-bold outline-none focus:bg-white focus:border-[#4E8F35] transition">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">رقم الهاتف</label>
                        <input type="tel" name="phone" placeholder="010XXXXXXXX"
                               class="w-full bg-[#F5F3EE] border border-[#E5E2DC] rounded-xl px-3.5 py-2.5 text-xs font-bold outline-none focus:bg-white focus:border-[#4E8F35] transition">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">كلمة المرور لحساب المدرس <span class="text-rose-500">*</span></label>
                    <input type="password" name="password" required minlength="8" placeholder="•••••••• (8 أحرف على الأقل)"
                           class="w-full bg-[#F5F3EE] border border-[#E5E2DC] rounded-xl px-3.5 py-2.5 text-xs font-bold outline-none focus:bg-white focus:border-[#4E8F35] transition">
                    <span class="text-[10px] text-slate-400 mt-0.5 block">سيستخدمها المدرس للدخول إلى لوحة التحكم الخاصة به لمتابعة زيارات طلابه.</span>
                </div>

                {{-- Commission Rate with Live Calculator --}}
                <div class="p-3.5 rounded-2xl bg-[#F5F3EE] border border-[#E5E2DC] space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="font-bold text-slate-800">نسبة الكوميشن للمدرس (%) <span class="text-rose-500">*</span></label>
                        <span class="text-[10px] text-slate-500 font-bold">الافتراضي: {{ \App\Models\Setting::get('teacher_default_commission_rate', 10) }}%</span>
                    </div>
                    <div class="relative">
                        <input type="number" name="commission_rate" id="add-commission-rate" step="0.5" min="0" max="100" required
                               value="{{ \App\Models\Setting::get('teacher_default_commission_rate', 10) }}"
                               oninput="updateCommissionPreview(this.value)"
                               class="w-full bg-white border border-[#E5E2DC] rounded-xl px-3.5 py-2 text-sm font-mono font-black outline-none focus:border-[#4E8F35]">
                        <span class="absolute end-3 top-2 text-xs text-slate-400 font-black">%</span>
                    </div>

                    {{-- Live Calculation Box --}}
                    <div class="p-2.5 rounded-xl bg-white border border-[#E5E2DC] text-[11px] space-y-1">
                        <div class="flex justify-between items-center text-slate-600 font-medium">
                            <span>قيمة الجلسة الافتراضية:</span>
                            <span class="font-mono font-bold">{{ \App\Models\Setting::get('teacher_visit_base_value', 100) }} ج.م</span>
                        </div>
                        <div class="flex justify-between items-center text-[#4E8F35] font-extrabold border-t border-slate-100 pt-1">
                            <span>نصيب المدرس من كل طالب:</span>
                            <span class="font-mono" id="preview-teacher-share">10.00 ج.م</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-500 text-[10px] pt-0.5">
                            <span>صافي دخل المركز:</span>
                            <span class="font-mono font-bold" id="preview-center-share">90.00 ج.م</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">نبذة أو ملاحظات (اختياري)</label>
                    <textarea name="bio" rows="2" placeholder="المادة، المرحلة الدراسية، أو ملاحظات إدارية..."
                              class="w-full bg-[#F5F3EE] border border-[#E5E2DC] rounded-xl px-3.5 py-2 text-xs font-bold outline-none focus:bg-white focus:border-[#4E8F35] transition"></textarea>
                </div>

                <div class="pt-3 border-t border-[#E5E2DC] flex items-center justify-end gap-2">
                    <button type="button" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold transition cursor-pointer" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#4E8F35] hover:bg-[#3F742B] text-white font-black shadow-xs transition cursor-pointer">
                        تأكيد وإضافة المدرس
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════ --}}
{{-- MODAL 2: إعدادات الكوميشن العامة --}}
{{-- ════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="settingsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 rounded-3xl shadow-2xl p-6 bg-white text-[#303334]" dir="rtl">
            <div class="flex items-center justify-between pb-3 mb-4 border-b border-[#E5E2DC]">
                <h3 class="text-sm font-black text-[#303334]">⚙️ إعدادات الكوميشن العامة</h3>
                <button type="button" class="btn-close text-slate-400 hover:text-slate-700" data-bs-dismiss="modal">✕</button>
            </div>

            <form action="{{ route('admin.teachers.settings') }}" method="POST" class="space-y-3.5 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">قيمة الزيارة الأساسية (ج.م) *</label>
                    <input type="number" name="teacher_visit_base_value" required min="0" step="0.5"
                           value="{{ \App\Models\Setting::get('teacher_visit_base_value', 100) }}"
                           class="w-full bg-[#F5F3EE] border border-[#E5E2DC] rounded-xl px-3.5 py-2.5 text-sm font-mono font-bold outline-none focus:bg-white focus:border-[#4E8F35]">
                    <span class="text-[10px] text-slate-400 mt-1 block">القيمة التي يُحسب عليها الكوميشن عند مسح كود الـ QR.</span>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">النسبة الافتراضية للمدرسين الجدد (%) *</label>
                    <input type="number" name="teacher_default_commission_rate" required min="0" max="100" step="0.5"
                           value="{{ \App\Models\Setting::get('teacher_default_commission_rate', 10) }}"
                           class="w-full bg-[#F5F3EE] border border-[#E5E2DC] rounded-xl px-3.5 py-2.5 text-sm font-mono font-bold outline-none focus:bg-white focus:border-[#4E8F35]">
                </div>

                <div class="pt-3 border-t border-[#E5E2DC] flex items-center justify-end gap-2">
                    <button type="button" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold transition cursor-pointer" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-[#4E8F35] hover:bg-[#3F742B] text-white font-black shadow-xs transition cursor-pointer">
                        حفظ الإعدادات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════ --}}
{{-- MODAL 3: معاينة وطباعة الـ QR السريع --}}
{{-- ════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="qrPreviewModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 rounded-3xl shadow-2xl p-6 bg-white text-[#303334] text-center" dir="rtl">
            <div class="flex items-center justify-between pb-3 mb-3 border-b border-[#E5E2DC]">
                <h3 class="text-sm font-black text-[#303334]" id="modal-qr-teacher-name">كود المدرس</h3>
                <button type="button" class="btn-close text-slate-400 hover:text-slate-700" data-bs-dismiss="modal">✕</button>
            </div>

            <div class="space-y-3">
                <div class="p-3 rounded-2xl bg-[#F8F7F4] border border-[#E5E2DC] inline-block">
                    <img id="modal-qr-image" src="" width="180" height="180" class="rounded-xl mx-auto border border-white shadow-2xs" alt="QR Code">
                </div>

                <div>
                    <span class="text-[11px] font-mono font-black text-[#303334] bg-[#F5F3EE] border border-[#E5E2DC] px-3 py-1 rounded-lg" id="modal-qr-code">
                        TCH-XXXXXXXX
                    </span>
                </div>

                <div class="pt-2 flex flex-col gap-2">
                    <button type="button" onclick="printCurrentQrCard()" class="w-full py-2.5 rounded-xl bg-[#4E8F35] hover:bg-[#3F742B] text-white font-extrabold text-xs transition shadow-xs flex items-center justify-center gap-1.5 cursor-pointer">
                        <span>🖨️ طباعة كرت QR للمدرس</span>
                    </button>

                    <div class="flex items-center gap-2">
                        <a id="modal-qr-download" href="" target="_blank" class="flex-1 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition text-center">
                            ⬇️ حفظ الصورة
                        </a>
                        <button type="button" onclick="copyCurrentModalLink()" class="flex-1 py-2 rounded-xl bg-[#EBF4E8] hover:bg-[#DCE8D4] text-[#4E8F35] font-bold text-xs transition text-center cursor-pointer">
                            📋 نسخ الرابط
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let currentModalData = {
    name: '',
    code: '',
    imgUrl: '',
    linkUrl: ''
};

const baseVisitValue = {{ \App\Models\Setting::get('teacher_visit_base_value', 100) }};

function updateCommissionPreview(rate) {
    const r = parseFloat(rate || 0);
    const teacherShare = (baseVisitValue * (r / 100));
    const centerShare = Math.max(0, baseVisitValue - teacherShare);
    document.getElementById('preview-teacher-share').textContent = `${teacherShare.toFixed(2)} ج.م`;
    document.getElementById('preview-center-share').textContent = `${centerShare.toFixed(2)} ج.م`;
}

function copyTeacherLink(url) {
    navigator.clipboard.writeText(url).then(() => {
        showToast('تم نسخ رابط الحجز الخاص بالمدرس بنجاح!');
    }).catch(err => {
        prompt('انسخ الرابط التالي:', url);
    });
}

function showToast(msg) {
    const toast = document.getElementById('toast-notification');
    document.getElementById('toast-message').textContent = msg;
    toast.classList.remove('hidden');
    setTimeout(() => {
        toast.classList.add('hidden');
    }, 3000);
}

function openQrModal(name, code, imgUrl, linkUrl) {
    currentModalData = { name, code, imgUrl, linkUrl };
    document.getElementById('modal-qr-teacher-name').textContent = name;
    document.getElementById('modal-qr-code').textContent = code;
    document.getElementById('modal-qr-image').src = imgUrl;
    document.getElementById('modal-qr-download').href = imgUrl;

    const modal = new bootstrap.Modal(document.getElementById('qrPreviewModal'));
    modal.show();
}

function copyCurrentModalLink() {
    if (currentModalData.linkUrl) {
        copyTeacherLink(currentModalData.linkUrl);
    }
}

function printCurrentQrCard() {
    const printWindow = window.open('', '_blank', 'width=550,height=650');
    printWindow.document.write(`
        <!DOCTYPE html>
        <html dir="rtl" lang="ar">
        <head>
            <meta charset="UTF-8">
            <title>QR Code - ${currentModalData.name}</title>
            <style>
                body { font-family: 'Segoe UI', Tahoma, Arial, sans-serif; text-align: center; padding: 40px 20px; background: #FAF9F6; margin: 0; }
                .card { border: 2px solid #4E8F35; border-radius: 24px; padding: 35px 25px; display: inline-block; max-width: 380px; width: 100%; background: #ffffff; box-shadow: 0 10px 25px rgba(0,0,0,0.06); }
                .badge { background: #EBF4E8; color: #4E8F35; font-size: 13px; font-weight: bold; padding: 5px 12px; border-radius: 20px; display: inline-block; margin-bottom: 12px; border: 1px solid #DCE8D4; }
                h2 { color: #303334; margin: 0 0 6px 0; font-size: 22px; }
                p { color: #73777A; font-size: 13px; margin: 0 0 20px 0; }
                img { border: 2px solid #F1F5F9; border-radius: 16px; padding: 8px; background: #fff; }
                .code-box { margin-top: 18px; font-family: monospace; font-size: 16px; font-weight: 800; background: #F8FAFC; color: #303334; border: 1px solid #E2E8F0; padding: 8px 16px; border-radius: 10px; display: inline-block; letter-spacing: 1px; }
                .footer { margin-top: 15px; font-size: 11px; color: #94A3B8; }
            </style>
        </head>
        <body>
            <div class="card">
                <span class="badge">كود انتساب معتمد</span>
                <h2>${currentModalData.name}</h2>
                <p>امسح رمز الاستجابة السريعة (QR) لتسجيل حضورك وجلستك بالمعهد</p>
                <img src="${currentModalData.imgUrl}" width="220" height="220" alt="QR Code" />
                <div>
                    <div class="code-box">${currentModalData.code}</div>
                </div>
                <div class="footer">DT-System • نظام إدارة مساحات العمل والمعاهد</div>
            </div>
            <script>
                window.onload = function() {
                    window.print();
                    setTimeout(function() { window.close(); }, 500);
                };
            <\/script>
        </body>
        </html>
    `);
    printWindow.document.close();
}

function filterTeachersTable() {
    const query = document.getElementById('teacher-search-input').value.toLowerCase().trim();
    const status = document.getElementById('teacher-status-filter').value;
    const rows = document.querySelectorAll('.teacher-row');

    rows.forEach(row => {
        const name = row.dataset.name || '';
        const phone = row.dataset.phone || '';
        const email = row.dataset.email || '';
        const qr = row.dataset.qr || '';
        const rowStatus = row.dataset.status || '';
        const hasPending = row.dataset.hasPending === 'true';

        const matchesQuery = !query || name.includes(query) || phone.includes(query) || email.includes(query) || qr.includes(query);
        let matchesStatus = true;
        if (status === 'active') matchesStatus = rowStatus === 'active';
        else if (status === 'inactive') matchesStatus = rowStatus === 'inactive';
        else if (status === 'pending') matchesStatus = hasPending;

        row.style.display = (matchesQuery && matchesStatus) ? '' : 'none';
    });
}

function resetTeachersFilter() {
    document.getElementById('teacher-search-input').value = '';
    document.getElementById('teacher-status-filter').value = 'all';
    filterTeachersTable();
}

function confirmDelete(id) {
    if (confirm('هل أنت متأكد من حذف هذا المدرس؟ سيتم حذف جميع بياناته وزياراته المرتبطة.')) {
        document.getElementById('delete-' + id).submit();
    }
}
</script>
@endsection
