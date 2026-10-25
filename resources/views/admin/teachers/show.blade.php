@extends('shared.vertical')

@section('title', 'تفاصيل المدرس — ' . $teacher->user->name)

@section('content')
<div class="p-4 md:p-6 space-y-6" dir="rtl">

    {{-- Toast Notification --}}
    <div id="toast-notification" class="hidden fixed bottom-6 left-6 z-50 flex items-center gap-2.5 px-4 py-3 rounded-2xl bg-[#303334] text-white text-xs font-bold shadow-xl border border-white/10 transition-all duration-300">
        <span class="size-2 rounded-full bg-[#4E8F35]"></span>
        <span id="toast-message">تم نسخ الرابط بنجاح!</span>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-[#EBF4E8] border border-[#DCE8D4] text-[#303334] flex items-center justify-between text-xs font-bold shadow-2xs">
            <div class="flex items-center gap-2.5">
                <span class="size-6 rounded-lg bg-[#4E8F35] text-white flex items-center justify-center text-xs">✓</span>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-slate-400 hover:text-slate-700">✕</button>
        </div>
    @endif

    {{-- 1. Header Profile Banner --}}
    <div class="bg-white p-5 rounded-3xl border border-[#E5E2DC] shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <a href="{{ route('admin.teachers.index') }}"
               class="size-10 rounded-2xl bg-[#F5F3EE] hover:bg-[#EAE7E0] border border-[#E5E2DC] text-[#303334] flex items-center justify-center font-bold text-sm transition" title="رجوع لقائمة المدرسين">
                ←
            </a>
            <div class="size-12 rounded-2xl bg-[#EBF4E8] text-[#4E8F35] flex items-center justify-center font-black text-lg border border-[#DCE8D4]">
                {{ mb_substr($teacher->user->name ?? 'م', 0, 1) }}
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl font-black text-[#303334]">{{ $teacher->user->name }}</h1>
                    @if($teacher->is_active)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-[#EBF4E8] text-[#4E8F35] border border-[#DCE8D4]">
                            نشط
                        </span>
                    @else
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-rose-50 text-rose-600 border border-rose-200">
                            موقوف
                        </span>
                    @endif
                </div>
                <div class="text-xs text-slate-500 font-medium flex items-center gap-2 mt-0.5">
                    <span>{{ $teacher->user->email }}</span>
                    @if($teacher->user->phone)
                        <span>•</span>
                        <span class="font-mono text-slate-600">{{ $teacher->user->phone }}</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @if($teacher->pendingCommission() > 0)
                <button type="button" data-bs-toggle="modal" data-bs-target="#payModal"
                        class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black transition flex items-center gap-1.5 shadow-xs cursor-pointer">
                    <span>💰 صرف الكوميشن المعلق ({{ number_format($teacher->pendingCommission(), 2) }} ج.م)</span>
                </button>
            @endif

            <button type="button" data-bs-toggle="modal" data-bs-target="#editModal"
                    class="px-3.5 py-2.5 rounded-xl bg-[#F5F3EE] hover:bg-[#EAE7E0] border border-[#E5E2DC] text-[#303334] text-xs font-bold transition flex items-center gap-1.5 cursor-pointer">
                <span>✏️ تعديل النسبة والبيانات</span>
            </button>
        </div>
    </div>

    {{-- 2. Stats Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="p-4 rounded-3xl bg-white border border-[#E5E2DC] shadow-2xs">
            <span class="text-[11px] font-bold text-[#73777A] block">إجمالي الزيارات</span>
            <div class="text-2xl font-black text-[#303334] font-mono mt-0.5">{{ $teacher->totalVisits() }} <span class="text-xs font-bold text-slate-400">طالب</span></div>
            <span class="text-[10px] text-slate-400 font-medium">سجلوا عبر الـ QR</span>
        </div>

        <div class="p-4 rounded-3xl bg-white border border-[#E5E2DC] shadow-2xs">
            <span class="text-[11px] font-bold text-[#73777A] block">كوميشن مدفوع ومصروف</span>
            <div class="text-2xl font-black text-[#4E8F35] font-mono mt-0.5">{{ number_format($teacher->totalEarnings(), 2) }} <span class="text-xs font-bold text-slate-400">ج.م</span></div>
            <span class="text-[10px] text-[#4E8F35] font-bold">تم سداده بالكامل</span>
        </div>

        <div class="p-4 rounded-3xl bg-white border {{ $teacher->pendingCommission() > 0 ? 'border-amber-300 ring-2 ring-amber-100' : 'border-[#E5E2DC]' }} shadow-2xs">
            <span class="text-[11px] font-bold text-[#73777A] block">كوميشن معلّق (مستحق)</span>
            <div class="text-2xl font-black text-amber-600 font-mono mt-0.5">{{ number_format($teacher->pendingCommission(), 2) }} <span class="text-xs font-bold text-slate-400">ج.م</span></div>
            <span class="text-[10px] text-amber-700 font-bold">جاهز للصرف للمدرس</span>
        </div>

        <div class="p-4 rounded-3xl bg-white border border-[#E5E2DC] shadow-2xs">
            <span class="text-[11px] font-bold text-[#73777A] block">نسبة الكوميشن المعتمدة</span>
            <div class="text-2xl font-black text-[#4E8F35] font-mono mt-0.5">{{ $teacher->commission_rate }}%</div>
            <span class="text-[10px] text-slate-400 font-medium">من قيمة كل زيارة</span>
        </div>
    </div>

    {{-- 3. QR Card Showcase --}}
    <div class="p-6 rounded-3xl bg-white border border-[#E5E2DC] shadow-2xs">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
            <div class="flex flex-col sm:flex-row items-center gap-5 text-center sm:text-start">
                <div class="p-3 bg-[#F8F7F4] border border-[#E5E2DC] rounded-2xl shrink-0">
                    <img src="{{ $teacher->qrImageUrl() }}" width="150" height="150" class="rounded-xl border border-white shadow-2xs" alt="QR Code">
                </div>
                <div class="space-y-2">
                    <div class="flex items-center gap-2 justify-center sm:justify-start">
                        <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-black bg-[#EBF4E8] text-[#4E8F35] border border-[#DCE8D4]">
                            كود المسح الشخصي
                        </span>
                        <span class="font-mono font-black text-sm text-[#303334] bg-[#F5F3EE] px-2.5 py-0.5 rounded-lg border border-[#E5E2DC]">
                            {{ $teacher->qr_code }}
                        </span>
                    </div>
                    <h3 class="text-base font-black text-[#303334]">الباركود الترويجي للمدرس (QR Code)</h3>
                    <p class="text-xs text-[#73777A] max-w-md font-medium">
                        امسح هذا الرمز عبر كاميرا الموبايل لتسجيل حضور الطالب واحتساب الزيارة والكوميشن الخاص بالمدرس تلقائياً.
                    </p>
                    <div class="text-[11px] text-slate-500 font-mono bg-[#F5F3EE] p-2 rounded-xl border border-[#E5E2DC] truncate max-w-md">
                        {{ $teacher->qrUrl() }}
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row lg:flex-col gap-2 w-full lg:w-48 shrink-0">
                <button type="button" onclick="printQrCard()"
                        class="w-full py-2.5 rounded-xl bg-[#4E8F35] hover:bg-[#3F742B] text-white text-xs font-black transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                    <span>🖨️ طباعة كرت QR</span>
                </button>
                <a href="{{ $teacher->qrImageUrl() }}" target="_blank"
                   class="w-full py-2.5 rounded-xl bg-white hover:bg-slate-50 border border-[#E5E2DC] text-[#303334] text-xs font-bold transition flex items-center justify-center gap-1.5 text-center shadow-2xs">
                    <span>⬇️ حفظ صورة الـ QR</span>
                </a>
                <button type="button" onclick="copyTeacherLink('{{ $teacher->qrUrl() }}')"
                        class="w-full py-2.5 rounded-xl bg-[#EBF4E8] hover:bg-[#DCE8D4] text-[#4E8F35] text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer">
                    <span>📋 نسخ رابط الحجز</span>
                </button>
            </div>
        </div>
    </div>

    {{-- 4. Visits & Commissions Tabs --}}
    <div class="bg-white rounded-3xl border border-[#E5E2DC] shadow-2xs overflow-hidden">
        <div class="p-4 border-b border-[#E5E2DC] flex items-center justify-between">
            <h3 class="text-sm font-black text-[#303334] flex items-center gap-2">
                <span>سجل زيارات الطلاب عبر هذا المدرس</span>
                <span class="text-[10px] font-bold text-slate-400">({{ $visits->total() }} زيارة)</span>
            </h3>
        </div>

        @if($visits->isEmpty())
            <div class="p-8 text-center text-slate-400 text-xs font-bold">
                لا توجد زيارات مسجلة لهذا المدرس حتى الآن.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-start text-xs border-collapse">
                    <thead>
                        <tr class="bg-[#F8F7F4] border-b border-[#E5E2DC] text-[#73777A] font-extrabold">
                            <th class="py-3 px-4 text-start">اسم الطالب</th>
                            <th class="py-3 px-4 text-start">رقم الهاتف</th>
                            <th class="py-3 px-4 text-center">تاريخ ووقت الزيارة</th>
                            <th class="py-3 px-4 text-center">الملاحظات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E5E2DC]/70">
                        @foreach($visits as $visit)
                        <tr class="hover:bg-[#F5F3EE]/50 transition">
                            <td class="py-3 px-4 font-extrabold text-[#303334]">
                                {{ $visit->visitor_name }}
                            </td>
                            <td class="py-3 px-4 font-mono text-slate-600">
                                {{ $visit->visitor_phone ?? '—' }}
                            </td>
                            <td class="py-3 px-4 text-center font-mono text-slate-500">
                                {{ $visit->visited_at ? $visit->visited_at->format('Y-m-d h:i A') : $visit->created_at->format('Y-m-d h:i A') }}
                            </td>
                            <td class="py-3 px-4 text-center text-slate-400 text-[11px]">
                                {{ $visit->notes ?? 'حضور مباشر' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3 border-t border-[#E5E2DC]">
                {{ $visits->links() }}
            </div>
        @endif
    </div>

</div>

{{-- ════════════════════════════════════════════════════════ --}}
{{-- MODAL: صرف الكوميشن المعلق --}}
{{-- ════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="payModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 rounded-3xl shadow-2xl p-6 bg-white text-[#303334]" dir="rtl">
            <div class="text-center space-y-3">
                <div class="size-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mx-auto border border-emerald-200">
                    💰
                </div>
                <h3 class="text-base font-black text-[#303334]">تأكيد صرف الكوميشن</h3>
                <p class="text-xs text-slate-500">
                    أنت على وشك صرف وتسجيل سداد جميع مبالغ الكوميشن المعلقة للمدرس:
                    <strong class="text-emerald-700 block text-lg font-mono font-black mt-1">
                        {{ number_format($teacher->pendingCommission(), 2) }} ج.م
                    </strong>
                </p>

                <form action="{{ route('admin.teachers.pay', $teacher) }}" method="POST" class="pt-2 flex items-center gap-2">
                    @csrf
                    <button type="button" class="flex-1 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition cursor-pointer" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="flex-1 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs transition shadow-xs cursor-pointer">
                        تأكيد الصرف
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════ --}}
{{-- MODAL: تعديل بيانات المدرس والنسبة --}}
{{-- ════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-3xl shadow-2xl p-6 bg-white text-[#303334]" dir="rtl">
            <div class="flex items-center justify-between pb-3 mb-4 border-b border-[#E5E2DC]">
                <h3 class="text-base font-black text-[#303334]">تعديل بيانات ونسبة المدرس</h3>
                <button type="button" class="btn-close text-slate-400 hover:text-slate-700" data-bs-dismiss="modal">✕</button>
            </div>

            <form action="{{ route('admin.teachers.update', $teacher) }}" method="POST" class="space-y-3.5 text-xs">
                @csrf
                @method('PUT')

                <div>
                    <label class="block font-bold text-slate-700 mb-1">اسم المدرس *</label>
                    <input type="text" name="name" required value="{{ $teacher->user->name }}"
                           class="w-full bg-[#F5F3EE] border border-[#E5E2DC] rounded-xl px-3.5 py-2.5 text-xs font-bold outline-none focus:bg-white focus:border-[#4E8F35]">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">رقم الهاتف</label>
                        <input type="tel" name="phone" value="{{ $teacher->user->phone }}"
                               class="w-full bg-[#F5F3EE] border border-[#E5E2DC] rounded-xl px-3.5 py-2.5 text-xs font-bold outline-none focus:bg-white focus:border-[#4E8F35]">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">نسبة الكوميشن (%) *</label>
                        <div class="relative">
                            <input type="number" name="commission_rate" step="0.5" min="0" max="100" required
                                   value="{{ $teacher->commission_rate }}"
                                   class="w-full bg-[#F5F3EE] border border-[#E5E2DC] rounded-xl px-3.5 py-2.5 text-xs font-mono font-bold outline-none focus:bg-white focus:border-[#4E8F35]">
                            <span class="absolute end-3 top-2.5 text-xs text-slate-400 font-bold">%</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">نبذة أو ملاحظات</label>
                    <textarea name="bio" rows="2"
                              class="w-full bg-[#F5F3EE] border border-[#E5E2DC] rounded-xl px-3.5 py-2 text-xs font-bold outline-none focus:bg-white focus:border-[#4E8F35]">{{ $teacher->bio }}</textarea>
                </div>

                <div class="pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="is_active" value="1" {{ $teacher->is_active ? 'checked' : '' }}
                               class="size-4 accent-[#4E8F35] rounded">
                        <span class="font-bold text-slate-800">حساب المدرس نشط ويستقبل زيارات</span>
                    </label>
                </div>

                <div class="pt-3 border-t border-[#E5E2DC] flex items-center justify-end gap-2">
                    <button type="button" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold transition cursor-pointer" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#4E8F35] hover:bg-[#3F742B] text-white font-black shadow-xs transition cursor-pointer">
                        حفظ التعديلات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
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

function printQrCard() {
    const printWindow = window.open('', '_blank', 'width=550,height=650');
    printWindow.document.write(`
        <!DOCTYPE html>
        <html dir="rtl" lang="ar">
        <head>
            <meta charset="UTF-8">
            <title>QR Code - {{ addslashes($teacher->user->name) }}</title>
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
                <h2>{{ addslashes($teacher->user->name) }}</h2>
                <p>امسح رمز الاستجابة السريعة (QR) لتسجيل حضورك وجلستك بالمعهد</p>
                <img src="{{ $teacher->qrImageUrl() }}" width="220" height="220" alt="QR Code" />
                <div>
                    <div class="code-box">{{ $teacher->qr_code }}</div>
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
</script>
@endsection
