@extends('portal.layout')

@section('title', 'لوحة المدرس والكوميشن — DDT WORKING SPACE')

@section('content')

    <!-- Top Navigation Context -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2.5">
            <a href="{{ route('portal.home') }}" 
               class="w-9 h-9 rounded-full bg-white border border-[#E5E2DC] flex items-center justify-center text-[#303334] hover:bg-[#F5F3EE] transition shadow-xs active:scale-95">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-sm font-extrabold text-[#303334] flex items-center gap-1.5">
                    <span>لوحة المدرس والكوميشن</span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#EBF4E8] text-[#4E8F35] inline-flex items-center gap-1">
                        <svg class="w-3 h-3 text-[#4E8F35]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5"/></svg>
                        <span>مدرس معتمد</span>
                    </span>
                </h1>
                <p class="text-[11px] text-[#73777A]">متابعة أرباحك، حضور طلابك ورابط الباركود</p>
            </div>
        </div>

        <div class="flex items-center gap-1.5">
            <button type="button" onclick="openBoostModal()"
                    class="px-3 py-1.5 rounded-full bg-gradient-to-r from-[#4E8F35] to-[#79B84A] text-white text-xs font-black flex items-center gap-1.5 shadow-xs hover:opacity-95 transition active:scale-95">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                <span>زيادة أرباحك</span>
            </button>

            <button type="button" onclick="openQrModal()"
                    class="px-3 py-1.5 rounded-full bg-white border border-[#E5E2DC] text-[#303334] text-xs font-bold flex items-center gap-1.5 shadow-xs hover:bg-[#F5F3EE] transition active:scale-95">
                <svg class="w-3.5 h-3.5 text-[#4E8F35]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
                <span>الباركود</span>
            </button>
        </div>
    </div>

    <!-- 1. Teacher Hero Profile Card (Solid Charcoal & Primary Green Theme) -->
    <div class="solid-card-charcoal rounded-[28px] p-5 relative overflow-hidden shadow-[0_8px_24px_rgba(48,51,52,0.18)]">
        <div class="flex items-start justify-between">
            <div class="flex items-center gap-3.5">
                <div class="w-13 h-13 rounded-2xl bg-[#4E8F35] border-2 border-white/20 flex items-center justify-center text-white font-black text-lg shadow-sm">
                    {{ mb_substr($teacher->user->name ?? $customer->full_name, 0, 2) }}
                </div>
                <div>
                    <h2 class="text-sm font-extrabold text-white flex items-center gap-1.5">
                        <span>{{ $teacher->user->name ?? $customer->full_name }}</span>
                    </h2>
                    <p class="text-[11px] text-[#DCE8D4] mt-0.5">
                        كود الباركود: <strong class="font-mono text-white tracking-wider">{{ $teacher->qr_code }}</strong>
                    </p>
                </div>
            </div>

            <span class="px-3 py-1 rounded-full bg-[#4E8F35] text-white text-xs font-black shadow-xs border border-white/20">
                {{ $stats['commission_rate'] }}% عمولة
            </span>
        </div>

        <!-- عدادات الأرباح الكلية (يشوف كسب كام وكام) -->
        <div class="grid grid-cols-3 gap-2 mt-4 pt-3.5 border-t border-white/15 text-center">
            <div class="p-2 rounded-xl bg-white/10 border border-white/10">
                <span class="text-[10px] text-[#DCE8D4] block font-bold">مستلم كاش</span>
                <span class="text-xs font-black text-[#79B84A] font-mono mt-0.5 block">{{ number_format($stats['total_earnings'], 2) }}</span>
            </div>
            <div class="p-2 rounded-xl bg-white/10 border border-white/10">
                <span class="text-[10px] text-[#DCE8D4] block font-bold">معلق قيد الصرف</span>
                <span class="text-xs font-black text-amber-400 font-mono mt-0.5 block">{{ number_format($stats['pending_commission'], 2) }}</span>
            </div>
            <div class="p-2 rounded-xl bg-[#4E8F35]/40 border border-[#79B84A]/30">
                <span class="text-[10px] text-white block font-black">إجمالي ما كسبته</span>
                <span class="text-xs font-black text-white font-mono mt-0.5 block">{{ number_format($stats['total_earnings'] + $stats['pending_commission'], 2) }}</span>
            </div>
        </div>

        <div class="mt-3.5 pt-2.5 border-t border-white/10 flex items-center justify-between text-xs">
            <span class="text-[#DCE8D4]">رقم الهاتف: <strong class="font-mono text-white" dir="ltr">{{ $teacher->user->phone ?? $customer->phone }}</strong></span>
            <button type="button" onclick="openBoostModal()" class="text-[#79B84A] hover:text-white font-bold text-[11px] flex items-center gap-1">
                <span>كيف تزيد أرباحك؟</span>
                <span>←</span>
            </button>
        </div>
    </div>

    <!-- 2. Performance Stats Grid (4 Solid Cards) -->
    <div class="grid grid-cols-2 gap-3">
        <!-- Card 1: Total Earnings Paid -->
        <div class="solid-card rounded-[22px] p-4 flex flex-col justify-between">
            <div class="flex items-center justify-between text-[#73777A]">
                <span class="text-[11px] font-bold">الأرباح المستلمة</span>
                <div class="w-7 h-7 rounded-full bg-[#EBF4E8] text-[#4E8F35] flex items-center justify-center font-bold">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-lg font-black text-[#4E8F35] font-mono">{{ number_format($stats['total_earnings'], 2) }}</span>
                <span class="text-[10px] text-[#73777A] font-bold mr-1">ج.م</span>
            </div>
        </div>

        <!-- Card 2: Pending Commission -->
        <div class="solid-card rounded-[22px] p-4 flex flex-col justify-between">
            <div class="flex items-center justify-between text-[#73777A]">
                <span class="text-[11px] font-bold">الكوميشن المعلق</span>
                <div class="w-7 h-7 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-lg font-black text-amber-600 font-mono">{{ number_format($stats['pending_commission'], 2) }}</span>
                <span class="text-[10px] text-[#73777A] font-bold mr-1">ج.م</span>
            </div>
        </div>

        <!-- Card 3: Total Students -->
        <div class="solid-card rounded-[22px] p-4 flex flex-col justify-between">
            <div class="flex items-center justify-between text-[#73777A]">
                <span class="text-[11px] font-bold">إجمالي حضور الطلاب</span>
                <div class="w-7 h-7 rounded-full bg-[#EBF4E8] text-[#4E8F35] flex items-center justify-center font-bold">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-lg font-black text-[#303334] font-mono">{{ $stats['total_students'] }}</span>
                <span class="text-[10px] text-[#73777A] font-bold mr-1">طالب مسجل</span>
            </div>
        </div>

        <!-- Card 4: Month Students -->
        <div class="solid-card rounded-[22px] p-4 flex flex-col justify-between">
            <div class="flex items-center justify-between text-[#73777A]">
                <span class="text-[11px] font-bold">حضور هذا الشهر</span>
                <div class="w-7 h-7 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-lg font-black text-blue-600 font-mono">{{ $stats['month_students'] }}</span>
                <span class="text-[10px] text-[#73777A] font-bold mr-1">طالب هذا الشهر</span>
            </div>
        </div>
    </div>

    <!-- 3. Quick Share & QR Card -->
    <div class="solid-card rounded-[26px] p-4.5 relative overflow-hidden">
        <div class="flex items-center gap-3">
            <div class="w-16 h-16 rounded-2xl bg-white border border-[#E5E2DC] p-1.5 flex-shrink-0 flex items-center justify-center shadow-xs cursor-pointer"
                 onclick="openQrModal()">
                <img src="{{ $teacher->qrImageUrl() }}" alt="QR Code" class="w-full h-full object-contain">
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-xs font-bold text-[#303334]">شارك كود الحضور مع طلابك</h3>
                <p class="text-[11px] text-[#73777A] mt-0.5 leading-relaxed">
                    امسح الباركود لتسجيل حضور الطلاب تلقائياً واحتساب الكوميشن الخاص بك فوراً.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-2 mt-3.5 pt-3 border-t border-[#E5E2DC]">
            @php
                $shareMessage = urlencode("مرحباً بكم يا شباب!\nلتسجيل الحضور وتأكيد جلستكم في DDT WORKING SPACE، امسحوا الرابط ده:\n" . $teacher->qrUrl());
                $whatsappUrl = "https://api.whatsapp.com/send?text={$shareMessage}";
            @endphp
            <a href="{{ $whatsappUrl }}" target="_blank"
               class="flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-full bg-[#25D366] text-white text-xs font-bold shadow-xs hover:bg-[#20ba59] transition active:scale-95">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                </svg>
                <span>مشاركة واتساب</span>
            </a>

            <button type="button" onclick="copyAffiliateLink('{{ $teacher->qrUrl() }}')"
                    class="flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-full bg-[#F5F3EE] border border-[#E5E2DC] text-[#303334] text-xs font-bold hover:bg-[#EBF4E8] hover:text-[#4E8F35] transition active:scale-95">
                <svg class="w-4 h-4 text-[#73777A]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                <span>نسخ الرابط</span>
            </button>
        </div>
    </div>

    <!-- 4. Students Section (أسماء الطلاب والحضور) -->
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <h3 class="text-xs font-extrabold text-[#303334]">سجل حضور الطلاب</h3>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-[#EBF4E8] text-[#4E8F35]">
                    {{ $visits->total() }} طالب
                </span>
            </div>

            <!-- Instant Search Form -->
            <form action="{{ route('portal.teacher') }}" method="GET" class="relative max-w-[170px]">
                <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="بحث بالاسم أو الهاتف..." 
                       class="w-full bg-white border border-[#E5E2DC] rounded-full pl-3 pr-7 py-1.5 text-[11px] text-[#303334] placeholder-[#73777A] focus:outline-none focus:border-[#4E8F35]">
                <button type="submit" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-[#73777A]">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </form>
        </div>

        @if($visits->count() > 0)
            <div class="space-y-2.5">
                @foreach($visits as $visit)
                    @php
                        $displayName = $visit->visitor_name ?: ($visit->customer->full_name ?? 'طالب DDT');
                        $displayPhone = $visit->visitor_phone ?: ($visit->customer->phone ?? null);
                        $commissionAmount = $visit->commission ? $visit->commission->amount : round(100 * ($teacher->commission_rate / 100), 2);
                        $commissionStatus = $visit->commission ? $visit->commission->status : 'pending';
                    @endphp
                    <div class="solid-card rounded-[22px] p-3.5 flex items-center justify-between gap-3 hover:border-[#DCE8D4] transition">
                        <div class="flex items-center gap-3 min-w-0">
                            <!-- Student Initials Avatar -->
                            <div class="w-10 h-10 rounded-full bg-[#F5F3EE] border border-[#E5E2DC] flex items-center justify-center text-xs font-black text-[#4E8F35] flex-shrink-0">
                                {{ mb_substr($displayName, 0, 2) }}
                            </div>

                            <div class="min-w-0">
                                <h4 class="text-xs font-bold text-[#303334] truncate">{{ $displayName }}</h4>
                                <div class="flex items-center gap-2 mt-0.5 text-[10px] text-[#73777A]">
                                    @if($displayPhone)
                                        <span class="font-mono text-[#4E8F35] font-semibold" dir="ltr">{{ $displayPhone }}</span>
                                        <span>•</span>
                                    @endif
                                    <span>{{ $visit->visited_at ? $visit->visited_at->diffForHumans() : $visit->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Commission & Status Badge -->
                        <div class="flex flex-col items-end flex-shrink-0">
                            <span class="text-xs font-black text-[#4E8F35] font-mono">+{{ number_format($commissionAmount, 2) }} ج.م</span>
                            @if($commissionStatus === 'paid')
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-[#EBF4E8] text-[#4E8F35] mt-0.5">
                                    تم السداد
                                </span>
                            @elseif($commissionStatus === 'pending')
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-amber-50 text-amber-700 mt-0.5">
                                    قيد التحصيل
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-gray-100 text-gray-600 mt-0.5">
                                    {{ $commissionStatus }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($visits->hasPages())
                <div class="pt-2">
                    {{ $visits->links() }}
                </div>
            @endif
        @else
            <!-- Empty State for Students -->
            <div class="solid-card rounded-[26px] p-8 text-center">
                <div class="w-14 h-14 rounded-full bg-[#EBF4E8] text-[#4E8F35] flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <h4 class="text-xs font-bold text-[#303334]">لا يوجد حضور طلاب مسجل حتى الآن</h4>
                <p class="text-[11px] text-[#73777A] mt-1 max-w-xs mx-auto">
                    شارك الباركود أو رابط الحضور مع طلابك لتسجيل أول حضور وحساب الكوميشن الخاص بك تلقائياً.
                </p>
                <button type="button" onclick="openQrModal()"
                        class="mt-4 inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-[#4E8F35] text-white text-xs font-bold shadow-xs active:scale-95">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>عرض الباركود ومشاركته</span>
                </button>
            </div>
        @endif
    </div>

    <!-- 5. Recent Commissions Payout Log -->
    @if($commissions->count() > 0)
        <div class="space-y-3 mt-2">
            <h3 class="text-xs font-extrabold text-[#303334]">سجل عمليات الكوميشن الأخيرة</h3>
            <div class="solid-card rounded-[26px] overflow-hidden divide-y divide-[#E5E2DC]">
                @foreach($commissions->take(5) as $comm)
                    <div class="p-3.5 flex items-center justify-between text-xs">
                        <div>
                            <div class="font-bold text-[#303334]">
                                {{ $comm->visit ? ($comm->visit->visitor_name ?: 'زيارة طالب') : 'عمولة حضور' }}
                            </div>
                            <div class="text-[10px] text-[#73777A] mt-0.5">
                                {{ $comm->created_at->format('Y-m-d h:i A') }}
                            </div>
                        </div>
                        <div class="text-left">
                            <span class="font-black text-[#4E8F35] font-mono">+{{ number_format($comm->amount, 2) }} ج.م</span>
                            <div class="mt-0.5">
                                @if($comm->status === 'paid')
                                    <span class="text-[9px] font-bold text-[#4E8F35]">تم الدفع في {{ $comm->paid_at ? \Carbon\Carbon::parse($comm->paid_at)->format('Y-m-d') : '' }}</span>
                                @else
                                    <span class="text-[9px] font-bold text-amber-600">معلق</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- QR Modal for Large Full-Screen Scanning -->
    <div id="qr-modal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs hidden items-center justify-center p-4">
        <div class="bg-white rounded-[32px] p-6 max-w-sm w-full text-center relative border border-[#E5E2DC] shadow-2xl animate-fade-in">
            <button type="button" onclick="closeQrModal()"
                    class="absolute top-4 left-4 w-8 h-8 rounded-full bg-[#F5F3EE] flex items-center justify-center text-[#73777A] hover:text-[#303334]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="w-12 h-12 rounded-2xl bg-[#EBF4E8] text-[#4E8F35] flex items-center justify-center mx-auto mb-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
            </div>

            <h3 class="text-sm font-extrabold text-[#303334]">{{ $teacher->user->name ?? $customer->full_name }}</h3>
            <p class="text-[11px] text-[#73777A] mt-0.5">وجه كاميرا الهاتف نحو الباركود لتسجيل الحضور</p>

            <div class="my-5 p-3 rounded-2xl bg-[#F5F3EE] border border-[#E5E2DC] inline-block shadow-inner">
                <img src="{{ $teacher->qrImageUrl() }}" alt="QR Code" class="w-52 h-52 object-contain mx-auto rounded-xl">
            </div>

            <div class="font-mono text-xs font-bold text-[#4E8F35] tracking-wider mb-4">
                {{ $teacher->qr_code }}
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ $whatsappUrl }}" target="_blank"
                   class="flex-1 py-2.5 px-3 rounded-full bg-[#25D366] text-white text-xs font-bold shadow-xs flex items-center justify-center gap-1.5 active:scale-95">
                    <span>مشاركة واتساب</span>
                </a>
                <button type="button" onclick="copyAffiliateLink('{{ $teacher->qrUrl() }}')"
                        class="flex-1 py-2.5 px-3 rounded-full bg-[#4E8F35] text-white text-xs font-bold shadow-xs flex items-center justify-center gap-1.5 active:scale-95">
                    <span>نسخ الرابط</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Boost Modal in Teacher Portal -->
    <div id="boost-modal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs hidden items-center justify-center p-4">
        <div class="bg-white rounded-[32px] p-6 max-w-sm w-full text-center relative border border-[#E5E2DC] shadow-2xl animate-fade-in">
            <button type="button" onclick="closeBoostModal()"
                    class="absolute top-4 left-4 w-8 h-8 rounded-full bg-[#F5F3EE] flex items-center justify-center text-[#73777A] hover:text-[#303334]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-[#4E8F35] to-[#79B84A] text-white flex items-center justify-center mx-auto mb-2.5 shadow-sm">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>

            <h3 class="text-sm font-black text-[#303334]">خطة زيادة أرباحك مع DDT</h3>
            <p class="text-[11px] text-[#73777A] mt-0.5">
                كل طالب يسجل عبر الباركود يمنحك عمولة فورية بنسبة <strong class="text-[#4E8F35] font-bold">{{ $stats['commission_rate'] }}%</strong>!
            </p>

            <!-- 3 Simple Boost Steps -->
            <div class="my-4 text-right space-y-2.5 bg-[#F5F3EE] p-3.5 rounded-2xl border border-[#E5E2DC] text-xs">
                <div class="flex items-start gap-2">
                    <span class="w-5 h-5 rounded-full bg-[#4E8F35] text-white text-[10px] font-black flex items-center justify-center flex-shrink-0 mt-0.5">1</span>
                    <span class="text-[#303334]"><strong>شارك الرابط بجروب الدفعة:</strong> أرسل رسالة سريعة للطلاب لتأكيد تسجيل حضورهم عند القدوم.</span>
                </div>
                <div class="flex items-start gap-2">
                    <span class="w-5 h-5 rounded-full bg-[#4E8F35] text-white text-[10px] font-black flex items-center justify-center flex-shrink-0 mt-0.5">2</span>
                    <span class="text-[#303334]"><strong>اعرض الباركود مكبراً:</strong> افتح زر "الباركود" واجعله متاحاً للطلاب لمسحه بكاميرا الهاتف.</span>
                </div>
                <div class="flex items-start gap-2">
                    <span class="w-5 h-5 rounded-full bg-[#4E8F35] text-white text-[10px] font-black flex items-center justify-center flex-shrink-0 mt-0.5">3</span>
                    <span class="text-[#303334]"><strong>أرباح فورية ومضمونة:</strong> تضاف أرباح كل طالب إلى رصيدك المعلق فور تسجيله.</span>
                </div>
            </div>

            <div class="space-y-2">
                <a href="{{ $whatsappUrl }}" target="_blank"
                   class="w-full py-2.5 px-4 rounded-full bg-[#25D366] hover:bg-[#20ba59] text-white text-xs font-black shadow-xs flex items-center justify-center gap-2 active:scale-95 transition">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    <span>مشاركة رسالة الدعوة عبر واتساب</span>
                </a>

                <button type="button" onclick="copyAffiliateLink('{{ $teacher->qrUrl() }}')"
                        class="w-full py-2.5 px-4 rounded-full bg-[#F5F3EE] hover:bg-[#EBF4E8] text-[#303334] text-xs font-bold border border-[#E5E2DC] flex items-center justify-center gap-2 active:scale-95 transition">
                    <svg class="w-4 h-4 text-[#73777A]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    <span>نسخ رابط الحضور المباشر</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Quick Toast Notification -->
    <div id="copy-toast" class="fixed bottom-24 left-1/2 -translate-x-1/2 z-50 bg-[#303334] text-white text-xs font-bold px-4 py-2.5 rounded-full shadow-lg transition-all duration-300 opacity-0 pointer-events-none">
        تم نسخ رابط الحضور بنجاح
    </div>

    <script>
        function openBoostModal() {
            const m = document.getElementById('boost-modal');
            if (m) {
                m.classList.remove('hidden');
                m.classList.add('flex');
            }
        }

        function closeBoostModal() {
            const m = document.getElementById('boost-modal');
            if (m) {
                m.classList.add('hidden');
                m.classList.remove('flex');
            }
        }

        function openQrModal() {
            const modal = document.getElementById('qr-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeQrModal() {
            const modal = document.getElementById('qr-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function copyAffiliateLink(url) {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(url).then(showToast);
            } else {
                const tempInput = document.createElement('input');
                tempInput.value = url;
                document.body.appendChild(tempInput);
                tempInput.select();
                document.execCommand('copy');
                document.body.removeChild(tempInput);
                showToast();
            }
        }

        function showToast() {
            const toast = document.getElementById('copy-toast');
            toast.classList.remove('opacity-0', 'pointer-events-none');
            toast.classList.add('opacity-100');
            setTimeout(() => {
                toast.classList.remove('opacity-100');
                toast.classList.add('opacity-0', 'pointer-events-none');
            }, 2500);
        }
    </script>

@endsection
