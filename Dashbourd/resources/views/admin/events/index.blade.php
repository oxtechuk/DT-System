@extends('shared.vertical', ['title' => 'فعاليات المجتمع والبانرات — DDT Working Space'])

@section('content')

    {{-- Page Header (Unified DDT Palette) --}}
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-bold text-[#4E8F35] bg-[#EBF4E8] px-2.5 py-0.5 rounded-full border border-[#DCE8D4]">مجتمع DDT • Community Hub</span>
                <span class="text-xs text-[#73777A] font-mono">Customer App Events & Banners</span>
            </div>
            <h1 class="text-2xl font-extrabold text-[#303334] tracking-tight">
                إدارة فعاليات وبانرات المجتمع
            </h1>
            <p class="text-xs text-[#73777A] mt-1">التحكم في الفعاليات، ورش العمل، والبانرات الترويجية المعروضة بتطبيق العملاء (صفحة مجتمعي)</p>
        </div>

        <div class="flex items-center gap-2.5">
            <button type="button" onclick="document.getElementById('createEventModal').classList.remove('hidden')"
                class="px-4 py-2.5 bg-[#4E8F35] hover:bg-[#3F742B] text-white rounded-xl text-xs font-bold transition-all shadow-xs flex items-center gap-2">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                <span>إضافة فعالية جديدة</span>
            </button>
            <a href="{{ route('portal.community') }}" target="_blank"
                class="px-3.5 py-2.5 bg-[#F5F3EE] hover:bg-[#EBF4E8] text-[#303334] hover:text-[#4E8F35] border border-[#E5E2DC] rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                <span>معاينة في تطبيق العملاء</span>
            </a>
        </div>
    </div>

    {{-- Unified Stat Cards (Single Harmonious Palette - No Glowing Rainbows) --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-4 border border-[#E5E2DC] shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-[#73777A]">إجمالي الفعاليات والبانرات</span>
                <div class="text-2xl font-black text-[#303334] mt-1">{{ $totalEvents }}</div>
            </div>
            <div class="w-11 h-11 rounded-xl bg-[#F5F3EE] text-[#4E8F35] border border-[#E5E2DC] flex items-center justify-center">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect width="18" height="18" x="3" y="4" rx="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/>
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-[#E5E2DC] shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-[#73777A]">فعاليات قادمة</span>
                <div class="text-2xl font-black text-[#303334] mt-1">{{ $upcomingCount }}</div>
            </div>
            <div class="w-11 h-11 rounded-xl bg-[#F5F3EE] text-[#4E8F35] border border-[#E5E2DC] flex items-center justify-center">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-[#E5E2DC] shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-[#73777A]">نشطة في التطبيق حالياً</span>
                <div class="text-2xl font-black text-[#4E8F35] mt-1">{{ $activeCount }}</div>
            </div>
            <div class="w-11 h-11 rounded-xl bg-[#EBF4E8] text-[#4E8F35] border border-[#DCE8D4] flex items-center justify-center">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Events List Table / Card --}}
    <div class="bg-white rounded-2xl border border-[#E5E2DC] shadow-xs overflow-hidden mb-8">
        <div class="p-4 border-b border-[#E5E2DC] flex items-center justify-between">
            <h2 class="font-bold text-xs text-[#303334]">قائمة الفعاليات والبانرات</h2>
            <span class="text-xs text-[#73777A]">تظهر في صفحة «مجتمعي» والبانر الرئيسي بتطبيق العملاء</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-start text-xs">
                <thead class="bg-[#F8F7F4] border-b border-[#E5E2DC] text-[#73777A] font-bold">
                    <tr>
                        <th class="p-3.5 text-start">الفعالية / العنوان</th>
                        <th class="p-3.5 text-start">التصنيف والبادج</th>
                        <th class="p-3.5 text-start">الموعد والتوقيت</th>
                        <th class="p-3.5 text-start">المحاضر / المتحدث</th>
                        <th class="p-3.5 text-start">السعر والرمز</th>
                        <th class="p-3.5 text-center">ظهور بالتطبيق</th>
                        <th class="p-3.5 text-center">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E2DC] text-[#303334]">
                    @forelse($events as $ev)
                        <tr class="hover:bg-[#F8F7F4]/80 transition">
                            <td class="p-3.5">
                                <div class="font-bold text-sm text-[#303334]">{{ $ev->title }}</div>
                                <div class="text-[#73777A] text-[11px] mt-0.5 line-clamp-1">{{ $ev->location }}</div>
                                @if($ev->is_featured)
                                    <span class="inline-block mt-1 text-[10px] font-bold bg-[#F5F3EE] text-[#303334] border border-[#E5E2DC] px-2 py-0.5 rounded-full">
                                        ★ بانر مميز (Featured)
                                    </span>
                                @endif
                            </td>
                            <td class="p-3.5">
                                <div class="flex flex-col gap-1 items-start">
                                    <span class="px-2 py-0.5 rounded-md bg-[#F5F3EE] text-[#303334] border border-[#E5E2DC] font-medium text-[11px]">
                                        {{ $ev->category }}
                                    </span>
                                    @if($ev->badge_text)
                                        <span class="px-2 py-0.5 rounded-md bg-[#EBF4E8] text-[#4E8F35] border border-[#DCE8D4] text-[10px] font-bold">
                                            {{ $ev->badge_text }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="p-3.5">
                                <div class="font-bold text-[#303334]">{{ \Carbon\Carbon::parse($ev->event_date)->translatedFormat('l, d F Y') }}</div>
                                <div class="text-[#73777A] text-[11px] mt-0.5">{{ $ev->time_text }}</div>
                            </td>
                            <td class="p-3.5">
                                <div class="font-semibold text-[#303334]">{{ $ev->speaker_name ?: '—' }}</div>
                                <div class="text-[#73777A] text-[11px]">{{ $ev->speaker_title }}</div>
                            </td>
                            <td class="p-3.5">
                                @if($ev->price == 0)
                                    <span class="font-bold text-[#4E8F35]">مجاناً</span>
                                @else
                                    <span class="font-bold text-[#303334]">{{ number_format($ev->price, 0) }} ج.م</span>
                                @endif
                                <div class="text-[#73777A] text-[10px] mt-0.5">سعة: {{ $ev->capacity }} فرد</div>
                            </td>
                            <td class="p-3.5 text-center">
                                <form action="{{ route('admin.events.toggle', $ev) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" title="تبديل حالة الظهور"
                                        class="px-2.5 py-1 rounded-full text-[11px] font-bold transition {{ $ev->is_active ? 'bg-[#EBF4E8] text-[#4E8F35] border border-[#DCE8D4] hover:bg-[#DCE8D4]' : 'bg-[#F5F3EE] text-[#73777A] border border-[#E5E2DC] hover:bg-white' }}">
                                        {{ $ev->is_active ? '● مفعل' : '○ معطل' }}
                                    </button>
                                </form>
                            </td>
                            <td class="p-3.5 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    {{-- Delete --}}
                                    <form action="{{ route('admin.events.destroy', $ev) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه الفعالية؟');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="حذف" class="p-1.5 rounded-lg text-[#73777A] hover:text-rose-600 hover:bg-rose-50 transition">
                                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-[#73777A]">
                                لا توجد فعاليات مسجلة حالياً. اضغط على زر "إضافة فعالية جديدة" لإضافة أول فعالية.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($events->hasPages())
            <div class="p-4 border-t border-[#E5E2DC]">
                {{ $events->links() }}
            </div>
        @endif
    </div>

    {{-- Create Event Modal --}}
    <div id="createEventModal" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-none flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl w-full max-w-xl max-h-[90vh] overflow-y-auto shadow-xl border border-[#E5E2DC] p-6">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-[#E5E2DC]">
                <h2 class="text-sm font-bold text-[#303334]">إضافة فعالية / ورشة عمل جديدة</h2>
                <button type="button" onclick="document.getElementById('createEventModal').classList.add('hidden')" class="text-[#73777A] hover:text-[#303334]">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form action="{{ route('admin.events.store') }}" method="POST" class="space-y-3.5 text-xs">
                @csrf

                <div>
                    <label class="block font-bold text-[#303334] mb-1">عنوان الفعالية / الورشة *</label>
                    <input type="text" name="title" required placeholder="مثال: Masterclass: تسعير المشاريع للعمل الحر"
                        class="w-full px-3 py-2 rounded-xl bg-[#F5F3EE] border border-[#E5E2DC] focus:outline-none focus:border-[#4E8F35] font-semibold text-[#303334]">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-[#303334] mb-1">التصنيف *</label>
                        <select name="category" required class="w-full px-3 py-2 rounded-xl bg-[#F5F3EE] border border-[#E5E2DC] focus:outline-none focus:border-[#4E8F35] text-[#303334]">
                            <option value="ورشة عمل">ورشة عمل (Workshop)</option>
                            <option value="ملتقى">ملتقى (Meetup)</option>
                            <option value="ندوة">ندوة (Talk / Panel)</option>
                            <option value="استشارات">استشارات (Mentorship)</option>
                            <option value="فعالية عامة">فعالية عامة</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-[#303334] mb-1">نص الشارة (Badge)</label>
                        <input type="text" name="badge_text" placeholder="مثال: مجاناً للأعضاء / مقاعد محدودة"
                            class="w-full px-3 py-2 rounded-xl bg-[#F5F3EE] border border-[#E5E2DC] focus:outline-none focus:border-[#4E8F35] text-[#303334]">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-[#303334] mb-1">تاريخ الفعالية *</label>
                        <input type="date" name="event_date" required value="{{ date('Y-m-d') }}"
                            class="w-full px-3 py-2 rounded-xl bg-[#F5F3EE] border border-[#E5E2DC] focus:outline-none focus:border-[#4E8F35] text-[#303334]">
                    </div>

                    <div>
                        <label class="block font-bold text-[#303334] mb-1">التوقيت *</label>
                        <input type="text" name="time_text" required placeholder="06:30 م - 08:30 م"
                            class="w-full px-3 py-2 rounded-xl bg-[#F5F3EE] border border-[#E5E2DC] focus:outline-none focus:border-[#4E8F35] text-[#303334]">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-[#303334] mb-1">اسم المتحدث / المحاضر</label>
                        <input type="text" name="speaker_name" placeholder="م. عمر الشريف"
                            class="w-full px-3 py-2 rounded-xl bg-[#F5F3EE] border border-[#E5E2DC] focus:outline-none focus:border-[#4E8F35] text-[#303334]">
                    </div>

                    <div>
                        <label class="block font-bold text-[#303334] mb-1">المسمى الوظيفي للمتحدث</label>
                        <input type="text" name="speaker_title" placeholder="Senior Product Designer"
                            class="w-full px-3 py-2 rounded-xl bg-[#F5F3EE] border border-[#E5E2DC] focus:outline-none focus:border-[#4E8F35] text-[#303334]">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-[#303334] mb-1">الموقع داخل المساحة *</label>
                        <input type="text" name="location" required value="القاعة الرئيسية — DDT"
                            class="w-full px-3 py-2 rounded-xl bg-[#F5F3EE] border border-[#E5E2DC] focus:outline-none focus:border-[#4E8F35] text-[#303334]">
                    </div>

                    <div>
                        <label class="block font-bold text-[#303334] mb-1">السعر (0 = مجاناً)</label>
                        <input type="number" name="price" value="0" min="0" step="1"
                            class="w-full px-3 py-2 rounded-xl bg-[#F5F3EE] border border-[#E5E2DC] focus:outline-none focus:border-[#4E8F35] text-[#303334]">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-[#303334] mb-1">ثيم البانر (اللون)</label>
                        <select name="banner_theme" class="w-full px-3 py-2 rounded-xl bg-[#F5F3EE] border border-[#E5E2DC] focus:outline-none focus:border-[#4E8F35] text-[#303334]">
                            <option value="green">أخضر DDT الرسمي (#4E8F35)</option>
                            <option value="charcoal">رمادي فاحم Charcoal (#303334)</option>
                            <option value="sage">أخضر زيتي فاتح Sage (#DCE8D4)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-[#303334] mb-1">السعة الاستيعابية (مقاعد)</label>
                        <input type="number" name="capacity" value="25" min="1"
                            class="w-full px-3 py-2 rounded-xl bg-[#F5F3EE] border border-[#E5E2DC] focus:outline-none focus:border-[#4E8F35] text-[#303334]">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-[#303334] mb-1">رابط التسجيل أو واتساب</label>
                    <input type="url" name="registration_url" placeholder="https://wa.me/201000000000?text=..."
                        class="w-full px-3 py-2 rounded-xl bg-[#F5F3EE] border border-[#E5E2DC] focus:outline-none focus:border-[#4E8F35] text-[#303334]">
                </div>

                <div>
                    <label class="block font-bold text-[#303334] mb-1">نبذة وتفاصيل الفعالية</label>
                    <textarea name="description" rows="3" placeholder="اكتب نبذة مختصرة عن أهداف الفعالية والمستفيدين..."
                        class="w-full px-3 py-2 rounded-xl bg-[#F5F3EE] border border-[#E5E2DC] focus:outline-none focus:border-[#4E8F35] text-[#303334]"></textarea>
                </div>

                <div class="flex items-center gap-6 py-2">
                    <label class="flex items-center gap-2 cursor-pointer font-bold text-[#303334]">
                        <input type="checkbox" name="is_featured" value="1" class="rounded text-[#4E8F35] focus:ring-[#4E8F35] w-4 h-4 border-[#E5E2DC]">
                        <span>بانر رئيسي مميز أعلى التطبيق</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer font-bold text-[#303334]">
                        <input type="checkbox" name="is_active" value="1" checked class="rounded text-[#4E8F35] focus:ring-[#4E8F35] w-4 h-4 border-[#E5E2DC]">
                        <span>تفعيل وظهور فوري بتطبيق العملاء</span>
                    </label>
                </div>

                <div class="flex items-center justify-end gap-2 pt-4 border-t border-[#E5E2DC]">
                    <button type="button" onclick="document.getElementById('createEventModal').classList.add('hidden')"
                        class="px-4 py-2 rounded-xl bg-[#F5F3EE] hover:bg-white text-[#73777A] font-bold border border-[#E5E2DC] transition">
                        إلغاء
                    </button>
                    <button type="submit"
                        class="px-5 py-2 rounded-xl bg-[#4E8F35] hover:bg-[#3F742B] text-white font-bold transition shadow-xs">
                        حفظ ونشر الفعالية
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
