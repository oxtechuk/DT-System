@extends('shared.vertical')

@section('title', 'تقرير عمولات وأفيليت المدرسين الشامل')

@section('content')
<div class="p-4 md:p-6 space-y-6" dir="rtl">

    {{-- 1. Header Banner --}}
    <div class="bg-white p-5 rounded-3xl border border-[#E5E2DC] shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <a href="{{ route('admin.teachers.index') }}"
               class="size-10 rounded-2xl bg-[#F5F3EE] hover:bg-[#EAE7E0] border border-[#E5E2DC] text-[#303334] flex items-center justify-center font-bold text-sm transition" title="رجوع لإدارة المدرسين">
                ←
            </a>
            <div>
                <div class="flex items-center gap-2 mb-0.5">
                    <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-black bg-[#EBF4E8] text-[#4E8F35] border border-[#DCE8D4]">
                        التقارير المالية
                    </span>
                    <span class="text-xs text-slate-400 font-mono">Affiliate Ledger</span>
                </div>
                <h1 class="text-xl font-black text-[#303334]">تقرير عمولات وأفيليت المدرسين</h1>
                <p class="text-xs text-[#73777A] font-medium">سجل شامل بكافة العمليات والزيارات ونسب الأرباح المستحقة والمدفوعة</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" onclick="window.print()" class="px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 border border-[#E5E2DC] text-[#303334] text-xs font-bold transition flex items-center gap-1.5 shadow-2xs cursor-pointer">
                <span>🖨️ طباعة كشف الحساب</span>
            </button>
            <a href="{{ route('admin.teachers.index') }}" class="px-4 py-2.5 rounded-xl bg-[#4E8F35] hover:bg-[#3F742B] text-white text-xs font-black transition flex items-center gap-1.5 shadow-xs">
                <span>👨‍🏫 إدارة المدرسين</span>
            </a>
        </div>
    </div>

    {{-- 2. Summary KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-4 rounded-3xl bg-white border {{ $summary['total_pending'] > 0 ? 'border-amber-300 ring-2 ring-amber-100' : 'border-[#E5E2DC]' }} shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-[#73777A] block">إجمالي الكوميشن المعلق (مطلوب سداده)</span>
                <div class="text-2xl font-black text-amber-600 font-mono mt-0.5">{{ number_format($summary['total_pending'], 2) }} <span class="text-xs font-bold text-slate-500">ج.م</span></div>
                <span class="text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.2 rounded mt-1 inline-block">
                    مستحق للمدرسين
                </span>
            </div>
            <div class="size-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold">
                ⏳
            </div>
        </div>

        <div class="p-4 rounded-3xl bg-white border border-[#E5E2DC] shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-[#73777A] block">إجمالي الكوميشن المدفوع</span>
                <div class="text-2xl font-black text-[#4E8F35] font-mono mt-0.5">{{ number_format($summary['total_paid'], 2) }} <span class="text-xs font-bold text-slate-500">ج.م</span></div>
                <span class="text-[10px] font-bold text-[#4E8F35] bg-[#EBF4E8] px-2 py-0.2 rounded mt-1 inline-block">
                    تمت تسويته بالكامل
                </span>
            </div>
            <div class="size-12 rounded-2xl bg-[#EBF4E8] text-[#4E8F35] flex items-center justify-center text-xl font-bold">
                ✓
            </div>
        </div>

        <div class="p-4 rounded-3xl bg-white border border-[#E5E2DC] shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-[#73777A] block">إجمالي الحركات والعمليات</span>
                <div class="text-2xl font-black text-[#303334] font-mono mt-0.5">{{ $commissions->total() }} <span class="text-xs font-bold text-slate-400">عملية</span></div>
                <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-0.2 rounded mt-1 inline-block">
                    منذ بداية المنظومة
                </span>
            </div>
            <div class="size-12 rounded-2xl bg-[#F5F3EE] text-[#303334] flex items-center justify-center text-xl font-bold">
                📊
            </div>
        </div>
    </div>

    {{-- 3. Live Filter Bar --}}
    <div class="bg-white p-4 rounded-3xl border border-[#E5E2DC] shadow-2xs flex flex-col md:flex-row items-center justify-between gap-3">
        <div class="relative w-full md:w-96">
            <span class="absolute start-3.5 top-3 text-slate-400">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            </span>
            <input type="text" id="comm-search-input" oninput="filterCommissionsTable()"
                   placeholder="بحث باسم المدرس أو اسم الطالب..."
                   class="w-full bg-[#F5F3EE] border border-[#E5E2DC] rounded-xl ps-10 pe-4 py-2.5 text-xs font-bold text-[#303334] outline-none focus:bg-white focus:border-[#4E8F35] transition">
        </div>

        <div class="flex items-center gap-2 w-full md:w-auto">
            <select id="comm-status-filter" onchange="filterCommissionsTable()"
                    class="bg-[#F5F3EE] border border-[#E5E2DC] rounded-xl px-3 py-2.5 text-xs font-bold text-[#303334] outline-none focus:border-[#4E8F35] cursor-pointer">
                <option value="all">كل الحالات (معلق ومدفوع)</option>
                <option value="pending">الكوميشن المعلق فقط</option>
                <option value="paid">المدفوع فقط</option>
            </select>
        </div>
    </div>

    {{-- 4. Commissions Table --}}
    <div class="bg-white rounded-3xl border border-[#E5E2DC] shadow-2xs overflow-hidden">
        @if($commissions->isEmpty())
            <div class="p-12 text-center text-slate-400 text-xs font-bold">
                لا توجد سجلات كوميشن مسجلة حتى الآن.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-start text-xs border-collapse" id="commissions-table">
                    <thead>
                        <tr class="bg-[#F8F7F4] border-b border-[#E5E2DC] text-[#73777A] font-extrabold">
                            <th class="py-3 px-4 text-start">#</th>
                            <th class="py-3 px-4 text-start">المدرس</th>
                            <th class="py-3 px-4 text-start">الطالب / الزائر</th>
                            <th class="py-3 px-4 text-center">قيمة الزيارة</th>
                            <th class="py-3 px-4 text-center">النسبة</th>
                            <th class="py-3 px-4 text-center">مبلغ الكوميشن</th>
                            <th class="py-3 px-4 text-center">حالة السداد</th>
                            <th class="py-3 px-4 text-center">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E5E2DC]/70">
                        @foreach($commissions as $commission)
                        <tr class="hover:bg-[#F5F3EE]/50 transition comm-row"
                            data-teacher="{{ mb_strtolower($commission->teacher?->user?->name ?? '') }}"
                            data-visitor="{{ mb_strtolower($commission->visit?->visitor_name ?? '') }}"
                            data-status="{{ $commission->status }}">
                            <td class="py-3 px-4 font-mono text-slate-400">
                                #{{ $commission->id }}
                            </td>
                            <td class="py-3 px-4 font-extrabold text-[#303334]">
                                {{ $commission->teacher?->user?->name ?? 'مدرس محذوف' }}
                            </td>
                            <td class="py-3 px-4 font-bold text-slate-700">
                                {{ $commission->visit?->visitor_name ?? 'زائر مباشر' }}
                            </td>
                            <td class="py-3 px-4 text-center font-mono text-slate-600">
                                {{ number_format($commission->visit_base_value_snapshot, 2) }} ج.م
                            </td>
                            <td class="py-3 px-4 text-center font-mono font-bold text-slate-800">
                                {{ $commission->commission_rate_snapshot }}%
                            </td>
                            <td class="py-3 px-4 text-center font-mono font-black text-[#4E8F35] text-sm">
                                {{ number_format($commission->amount, 2) }} ج.م
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($commission->status === 'paid')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-[#EBF4E8] text-[#4E8F35] border border-[#DCE8D4]">
                                        تم السداد
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-50 text-amber-700 border border-amber-200">
                                        معلق
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center font-mono text-slate-500 text-[11px]">
                                {{ $commission->created_at->format('Y-m-d h:i A') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-[#E5E2DC] flex items-center justify-between">
                <span class="text-xs text-slate-500 font-bold">عرض {{ $commissions->count() }} من أصل {{ $commissions->total() }} حركة</span>
                <div>{{ $commissions->links() }}</div>
            </div>
        @endif
    </div>

</div>
@endsection

@section('scripts')
<script>
function filterCommissionsTable() {
    const q = document.getElementById('comm-search-input').value.toLowerCase().trim();
    const status = document.getElementById('comm-status-filter').value;
    const rows = document.querySelectorAll('.comm-row');

    rows.forEach(r => {
        const teacher = r.dataset.teacher || '';
        const visitor = r.dataset.visitor || '';
        const rowStatus = r.dataset.status || '';

        const matchQ = !q || teacher.includes(q) || visitor.includes(q);
        let matchStatus = true;
        if (status === 'pending') matchStatus = rowStatus === 'pending';
        else if (status === 'paid') matchStatus = rowStatus === 'paid';

        r.style.display = (matchQ && matchStatus) ? '' : 'none';
    });
}
</script>
@endsection
