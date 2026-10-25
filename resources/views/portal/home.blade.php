@extends('portal.layout')

@section('title', 'الرئيسية — DDT WORKING SPACE')

@section('content')

    @if(Auth::guard('customer')->check() && Auth::guard('customer')->user()->isTeacher())
        @php
            $teacherProfile = Auth::guard('customer')->user()->teacher();
            $tPaid = $teacherProfile->totalEarnings();
            $tPending = $teacherProfile->pendingCommission();
            $tTotal = $tPaid + $tPending;
            $tStudents = $teacherProfile->totalVisits();
            $tQrUrl = $teacherProfile->qrUrl();
            $tShareMsg = urlencode("مرحباً بكم يا شباب! 🎓\nلتسجيل الحضور وتأكيد جلستكم في DDT WORKING SPACE، امسحوا الرابط ده:\n" . $tQrUrl);
            $tWhatsappUrl = "https://api.whatsapp.com/send?text={$tShareMsg}";
        @endphp
        <!-- Teacher Earnings & Boost Card (شاشة أرباح المدرس وزرار زيادة أرباحك) -->
        <div class="solid-card rounded-[28px] p-5 bg-gradient-to-br from-white via-white to-[#EBF4E8] border border-[#DCE8D4] shadow-[0_8px_24px_rgba(48,51,52,0.06)] relative overflow-hidden">
            <!-- Background Decorative Pattern -->
            <div class="absolute -left-6 -bottom-6 w-28 h-28 rounded-full bg-[#4E8F35]/5 pointer-events-none"></div>

            <div class="flex items-start justify-between relative z-10">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-[#4E8F35] text-white flex items-center justify-center font-black text-base shadow-sm flex-shrink-0">
                        🎓
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <h2 class="text-sm font-black text-[#303334]">أرباحك كـ مدرس معتمد</h2>
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-black bg-[#4E8F35] text-white">نشط</span>
                        </div>
                        <p class="text-[11px] text-[#73777A] mt-0.5 font-medium">
                            نسبة عمولتك: <strong class="text-[#4E8F35] font-bold">{{ $teacherProfile->commission_rate }}%</strong> على كل طالب
                        </p>
                    </div>
                </div>

                <!-- زرار زيادة أرباحك -->
                <button type="button" onclick="openBoostModal()"
                        class="flex items-center gap-1.5 px-3.5 py-2 rounded-full bg-gradient-to-r from-[#4E8F35] to-[#79B84A] text-white text-xs font-black shadow-[0_3px_12px_rgba(78,143,53,0.3)] hover:opacity-95 active:scale-95 transition">
                    <span>🚀</span>
                    <span>زيادة أرباحك</span>
                </button>
            </div>

            <!-- عدادات الأرباح (يشوف كسب كام وكام) -->
            <div class="grid grid-cols-3 gap-2.5 mt-4 pt-4 border-t border-[#E5E2DC]/80 relative z-10">
                <!-- 1. المستلم فعلياً -->
                <div class="p-2.5 rounded-2xl bg-white border border-[#E5E2DC] flex flex-col justify-between shadow-2xs">
                    <span class="text-[10px] text-[#73777A] font-bold flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#4E8F35]"></span>
                        <span>مستلم كاش</span>
                    </span>
                    <div class="mt-1">
                        <span class="text-sm font-black text-[#4E8F35] font-mono">{{ number_format($tPaid, 2) }}</span>
                        <span class="text-[9px] text-[#73777A] font-bold">ج.م</span>
                    </div>
                </div>

                <!-- 2. المعلق الجاهز للتحصيل -->
                <div class="p-2.5 rounded-2xl bg-white border border-[#E5E2DC] flex flex-col justify-between shadow-2xs">
                    <span class="text-[10px] text-[#73777A] font-bold flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        <span>معلق قيد الصرف</span>
                    </span>
                    <div class="mt-1">
                        <span class="text-sm font-black text-amber-600 font-mono">{{ number_format($tPending, 2) }}</span>
                        <span class="text-[9px] text-[#73777A] font-bold">ج.م</span>
                    </div>
                </div>

                <!-- 3. إجمالي ما حققته -->
                <div class="p-2.5 rounded-2xl bg-[#EBF4E8] border border-[#DCE8D4] flex flex-col justify-between shadow-2xs">
                    <span class="text-[10px] text-[#4E8F35] font-black flex items-center gap-1">
                        <span>💰</span>
                        <span>إجمالي كسبت</span>
                    </span>
                    <div class="mt-1">
                        <span class="text-sm font-black text-[#303334] font-mono">{{ number_format($tTotal, 2) }}</span>
                        <span class="text-[9px] text-[#73777A] font-bold">ج.م</span>
                    </div>
                </div>
            </div>

            <!-- Footer Action: عرض تفاصيل الطلاب -->
            <div class="mt-3.5 pt-2.5 border-t border-[#E5E2DC]/80 flex items-center justify-between text-xs relative z-10">
                <span class="text-[11px] text-[#73777A]">
                    حضر لك حتى الآن: <strong class="text-[#303334] font-bold">{{ $tStudents }} طالب</strong>
                </span>

                <a href="{{ route('portal.teacher') }}" class="text-[#4E8F35] hover:text-[#3F742B] font-black text-xs flex items-center gap-1 group">
                    <span>عرض أسماء الطلاب والتقرير</span>
                    <svg class="w-3.5 h-3.5 transform rotate-180 group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    @endif

    <!-- 1. Hero Card: Active Session or Guest Check-in (Solid Charcoal & Primary Green Theme) -->
    @if($activeDeal)
        <div class="solid-card-charcoal rounded-[28px] p-5 relative overflow-hidden shadow-[0_8px_24px_rgba(48,51,52,0.18)]">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div class="w-12 h-12 rounded-full bg-[#4E8F35] border-2 border-white/20 flex items-center justify-center text-white font-black text-sm">
                            {{ mb_substr($activeDeal->room ? $activeDeal->room->name : 'DDT', 0, 2) }}
                        </div>
                        <span class="absolute -bottom-1 -left-1 px-2 py-0.5 rounded-full bg-white text-[#4E8F35] text-[10px] font-black shadow-sm flex items-center justify-center border border-[#DCE8D4]">
                            {{ \Carbon\Carbon::parse($activeDeal->started_at)->format('H:i') }}
                        </span>
                    </div>

                    <div>
                        <div class="flex items-center gap-1.5">
                            <h3 class="text-sm font-extrabold text-white">
                                {{ $activeDeal->room ? $activeDeal->room->name : 'المساحة العامة' }}
                            </h3>
                            <svg class="w-3.5 h-3.5 text-[#79B84A]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </div>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="text-[11px] font-bold text-[#DCE8D4]">جلستك نشطة بالفرع</span>
                            <span class="flex items-center gap-1 mr-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#79B84A]"></span>
                                <span class="w-1.5 h-1.5 rounded-full bg-[#79B84A]"></span>
                                <span class="w-1.5 h-1.5 rounded-full bg-[#79B84A]"></span>
                                <span class="w-1.5 h-1.5 rounded-full bg-[#79B84A]"></span>
                            </span>
                        </div>
                    </div>
                </div>

                <span class="px-3 py-1 rounded-full bg-white/15 text-[#DCE8D4] text-[11px] font-bold border border-white/10">
                    {{ $activeDeal->workspaceType ? $activeDeal->workspaceType->name : 'مكتب عمل' }}
                </span>
            </div>

            <!-- Brand Slogan Tagline -->
            <div class="mt-4">
                <p class="text-xs font-bold text-[#EBF4E8]">
                    أكثر من مكان.. مجتمع بيكبر معاك • طلبات الكافيه تصل لطاولتك
                </p>
            </div>

            <!-- Chips Row -->
            <div class="flex flex-wrap gap-1.5 mt-3">
                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white/10 text-[#DCE8D4] text-[10px] font-semibold border border-white/10">
                    <svg class="w-3 h-3 text-[#79B84A]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    فايبر عالي السرعة
                </span>
                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white/10 text-[#DCE8D4] text-[10px] font-semibold border border-white/10">
                    <svg class="w-3 h-3 text-[#79B84A]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    جلسة مفتوحة
                </span>
                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white/10 text-[#DCE8D4] text-[10px] font-semibold border border-white/10">
                    <svg class="w-3 h-3 text-[#79B84A]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 100-6 3 3 0 000 6z"/></svg>
                    أجواء ملهمة
                </span>
            </div>

            <!-- Footer Action -->
            <div class="mt-5 pt-3.5 border-t border-white/15 flex items-center justify-between">
                <span class="text-xs text-[#DCE8D4]/90 font-bold">
                    بدأت: {{ \Carbon\Carbon::parse($activeDeal->started_at)->format('h:i A') }}
                </span>

                <a href="{{ route('portal.menu') }}" class="pill-btn-white px-5 py-2 text-xs flex items-center gap-1.5 shadow-sm">
                    <svg class="w-3.5 h-3.5 text-[#4E8F35]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>اطلب مشروبك</span>
                </a>
            </div>
        </div>
    @else
        @php
            $bannerImg = \App\Models\Setting::get('app_banner_image');
            $bannerUrl = \App\Models\Setting::get('app_banner_url');
            $bannerSrc = null;
            if (!empty($bannerImg)) {
                $bannerSrc = asset('storage/' . $bannerImg);
            } elseif (!empty($bannerUrl)) {
                $bannerSrc = $bannerUrl;
            }
            $bannerLink = \App\Models\Setting::get('app_banner_link') ?: route('portal.community');
            $bannerBtnText = \App\Models\Setting::get('app_banner_button_text') ?: 'تصفح الفعاليات';
        @endphp

        <!-- Clean Dynamic Image Banner (Configured from Settings / الإعدادات) -->
        <div class="solid-card rounded-[28px] overflow-hidden border border-[#E5E2DC] shadow-[0_8px_24px_rgba(48,51,52,0.14)] relative group">
            <div class="relative w-full h-48 sm:h-56 overflow-hidden bg-[#303334]">
                @if($bannerSrc)
                    <img src="{{ $bannerSrc }}" alt="DDT Banner" class="w-full h-full object-cover group-hover:scale-105 transition duration-500"/>
                @else
                    <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=1200&auto=format&fit=crop&q=80" alt="DDT Space" class="w-full h-full object-cover group-hover:scale-105 transition duration-500 opacity-90"/>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-[#303334]/85 via-transparent to-black/20"></div>

                <!-- Floating Bottom Actions Bar (Linked to Settings) -->
                <div class="absolute bottom-3.5 right-3.5 left-3.5 flex items-center justify-between">
                    <a href="{{ route('portal.menu') }}" class="pill-btn-white px-4 py-2 text-xs flex items-center gap-1.5 shadow-md hover:bg-white/90 transition active:scale-95">
                        <svg class="w-3.5 h-3.5 text-[#4E8F35]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3"/>
                        </svg>
                        <span>الكافيه</span>
                    </a>

                    <a href="{{ $bannerLink }}" {{ str_starts_with($bannerLink, 'http') ? 'target="_blank"' : '' }} class="pill-btn-primary px-5 py-2 text-xs flex items-center gap-1.5 shadow-md active:scale-95">
                        <span>{{ $bannerBtnText }}</span>
                        <svg class="w-3.5 h-3.5 rotate-180" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- 2. FEATURED EVENT BANNER (البانر المميز بالنجمة ★) -->
    @if($featuredEvent)
        <div class="space-y-2">
            <div class="flex items-center justify-between px-1">
                <div class="flex items-center gap-1.5">
                    <span class="text-amber-500 text-sm">★</span>
                    <h2 class="text-xs font-bold text-[#303334]">الفعالية المميزة في DDT</h2>
                </div>
                <button type="button" onclick="openAllEventsModal()" class="text-[11px] font-bold text-[#4E8F35] hover:underline flex items-center gap-1">
                    <span>تصفح الكل ({{ count($upcomingEvents) }})</span>
                    <svg class="w-3 h-3 rotate-180" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>
            </div>

            <!-- Luxury Featured Event Banner Card -->
            <div class="solid-card rounded-[26px] overflow-hidden border border-[#E5E2DC] shadow-sm relative group">
                @if($featuredEvent->image_url)
                    <!-- Event Cover Image with Dark Charcoal Overlay -->
                    <div class="relative h-44 w-full overflow-hidden bg-[#303334]">
                        <img src="{{ $featuredEvent->image_url }}" alt="{{ $featuredEvent->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500"/>
                        <div class="absolute inset-0 bg-gradient-to-t from-[#303334] via-[#303334]/50 to-transparent"></div>
                        <div class="absolute top-3 right-3 flex items-center gap-2">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-amber-400 text-slate-900 shadow-md flex items-center gap-1">
                                <span>★</span> بانر مميز
                            </span>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-white/95 backdrop-blur-sm text-[#4E8F35]">
                                {{ $featuredEvent->category }}
                            </span>
                        </div>
                        <div class="absolute bottom-3 right-3 left-3 text-white">
                            <h3 class="text-sm font-black leading-snug drop-shadow-sm">{{ $featuredEvent->title }}</h3>
                            <p class="text-[11px] text-[#DCE8D4] mt-0.5 flex items-center gap-2 font-medium">
                                <span>📅 {{ \Carbon\Carbon::parse($featuredEvent->event_date)->translatedFormat('d M') }}</span>
                                <span>⏰ {{ $featuredEvent->time_text }}</span>
                            </p>
                        </div>
                    </div>
                @else
                    <!-- Fallback Solid Charcoal Header when no image is uploaded -->
                    <div class="p-5 bg-gradient-to-br from-[#303334] to-[#222425] text-white relative">
                        <div class="flex items-center justify-between mb-2">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-400 text-slate-900 flex items-center gap-1">
                                <span>★</span> بانر مميز
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-white/20 text-[#DCE8D4]">
                                {{ $featuredEvent->category }}
                            </span>
                        </div>
                        <h3 class="text-sm font-black leading-snug">{{ $featuredEvent->title }}</h3>
                        <p class="text-[11px] text-[#DCE8D4] mt-1 flex items-center gap-2 font-medium">
                            <span>📅 {{ \Carbon\Carbon::parse($featuredEvent->event_date)->translatedFormat('l, d F') }}</span>
                            <span>⏰ {{ $featuredEvent->time_text }}</span>
                        </p>
                    </div>
                @endif

                <!-- Event Details & CTA Body -->
                <div class="p-4 bg-white space-y-3">
                    @if($featuredEvent->description)
                        <p class="text-[11px] text-[#73777A] leading-relaxed line-clamp-2">
                            {{ $featuredEvent->description }}
                        </p>
                    @endif

                    <div class="flex items-center justify-between pt-2 border-t border-[#E5E2DC]">
                        <div>
                            <span class="text-[10px] text-[#73777A] block">رسوم الحضور:</span>
                            <span class="text-xs font-black text-[#4E8F35]">
                                {{ $featuredEvent->price == 0 ? 'مجاناً للأعضاء' : number_format($featuredEvent->price, 0) . ' ج.م' }}
                            </span>
                        </div>

                        @php
                            $waText = urlencode("مرحباً، أرغب في تأكيد حجز مقعد في فعالية: " . $featuredEvent->title);
                            $regUrl = $featuredEvent->registration_url ?: "https://wa.me/201000000000?text={$waText}";
                        @endphp
                        <a href="{{ $regUrl }}" target="_blank" class="pill-btn-primary px-5 py-2 text-xs flex items-center gap-1.5 shadow-sm active:scale-95">
                            <span>حجز مقعد الآن</span>
                            <svg class="w-3.5 h-3.5 rotate-180" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- 3. Loyalty Stamp Card Widget (Official Brand Green & Sage) -->
    <div class="solid-card rounded-[24px] p-5 relative overflow-hidden">
        <div class="flex items-center justify-between mb-3 pb-2.5 border-b border-[#E5E2DC]">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full bg-[#EBF4E8] text-[#4E8F35] flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xs font-bold text-[#303334]">بطاقة ولاء DDT للزيارات</h2>
                    <p class="text-[10px] text-[#73777A]">5 زيارات مكتملة = الجلسة السادسة مجانية بالكامل</p>
                </div>
            </div>
            <a href="{{ route('portal.loyalty') }}" class="text-[11px] font-bold text-[#4E8F35] flex items-center gap-0.5">
                <span>التفاصيل</span>
                <svg class="w-3 h-3 rotate-180" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
        </div>

        <!-- Stamps Row -->
        <div class="grid grid-cols-6 gap-2 py-1">
            @for($i = 1; $i <= 5; $i++)
                @php $isCompleted = $loyalty['current_visits'] >= $i; @endphp
                <div class="flex flex-col items-center gap-1.5">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200 {{ $isCompleted ? 'bg-[#4E8F35] text-white shadow-sm' : 'bg-[#F5F3EE] border border-[#E5E2DC] text-[#73777A]' }}">
                        @if($isCompleted)
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        @else
                            <span class="text-xs font-bold font-mono">{{ $i }}</span>
                        @endif
                    </div>
                    <span class="text-[9px] font-semibold {{ $isCompleted ? 'text-[#4E8F35]' : 'text-[#73777A]' }}">{{ $i }}</span>
                </div>
            @endfor

            <!-- 6th Free Reward Stamp -->
            <div class="flex flex-col items-center gap-1.5">
                <div class="w-10 h-10 rounded-full flex items-center justify-center border-2 border-dashed transition-all duration-200 {{ $loyalty['reward_ready'] ? 'bg-[#4E8F35] text-white border-[#4E8F35] shadow-md scale-105' : 'bg-[#EBF4E8] border-[#DCE8D4] text-[#4E8F35]' }}">
                    <svg class="w-4 h-4 {{ $loyalty['reward_ready'] ? 'text-white' : 'text-[#4E8F35]' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                    </svg>
                </div>
                <span class="text-[9px] font-bold text-[#4E8F35]">مجاناً</span>
            </div>
        </div>
    </div>

    <!-- 4. Quick Actions Row -->
    <div class="grid grid-cols-2 gap-3">
        <a href="{{ route('portal.menu') }}" class="solid-card rounded-[22px] p-4 flex flex-col items-start gap-2 hover:border-[#4E8F35] transition group active:scale-95">
            <div class="w-10 h-10 rounded-full bg-[#EBF4E8] text-[#4E8F35] flex items-center justify-center border border-[#DCE8D4]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3"/>
                </svg>
            </div>
            <div>
                <h3 class="text-xs font-bold text-[#303334]">منيو المشروبات</h3>
                <p class="text-[10px] text-[#73777A]">قهوة وسناكس لطاولتك</p>
            </div>
        </a>

        <a href="{{ route('portal.community') }}" class="solid-card rounded-[22px] p-4 flex flex-col items-start gap-2 hover:border-[#4E8F35] transition group active:scale-95">
            <div class="w-10 h-10 rounded-full bg-[#F5F3EE] text-[#303334] flex items-center justify-center border border-[#E5E2DC]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-xs font-bold text-[#303334]">مجتمع DDT</h3>
                <p class="text-[10px] text-[#73777A]">أكثر من مكان.. بيكبر معاك</p>
            </div>
        </a>
    </div>

    <!-- Rating & Report Problem Actions -->
    <div class="grid grid-cols-2 gap-3 mt-2">
        <button type="button" onclick="openRatingModal()" class="solid-card rounded-[22px] p-3.5 flex items-center gap-2.5 hover:border-[#4E8F35] transition cursor-pointer active:scale-95 bg-amber-50/40 border-amber-200">
            <div class="w-9 h-9 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center text-sm font-bold shrink-0">
                ⭐
            </div>
            <div class="text-right">
                <h4 class="text-xs font-black text-amber-900">تقييم الخدمة</h4>
                <p class="text-[10px] text-amber-700 font-medium">شاركنا انطباعك وملاحظاتك</p>
            </div>
        </button>

        <button type="button" onclick="openProblemModal()" class="solid-card rounded-[22px] p-3.5 flex items-center gap-2.5 hover:border-rose-400 transition cursor-pointer active:scale-95 bg-rose-50/40 border-rose-200">
            <div class="w-9 h-9 rounded-full bg-rose-100 text-rose-700 flex items-center justify-center text-sm font-bold shrink-0">
                ⚠️
            </div>
            <div class="text-right">
                <h4 class="text-xs font-black text-rose-900">إبلاغ عن مشكلة</h4>
                <p class="text-[10px] text-rose-700 font-medium">إخطار فوري للكاشير والإدارة</p>
            </div>
        </button>
    </div>

    <!-- RATING MODAL -->
    <div id="ratingModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs hidden flex items-center justify-center p-4">
        <div class="w-full max-w-sm bg-white rounded-3xl p-6 border border-[#E5E2DC] shadow-2xl space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="font-black text-sm text-slate-900">تقييم الخدمة والتجربة ⭐</h3>
                <button type="button" onclick="closeRatingModal()" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
            </div>

            <form id="form-rating" onsubmit="submitFeedbackForm(event)">
                <div class="space-y-3.5 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5 text-center">اختر التقييم (من 1 إلى 5 نجوم) *</label>
                        <div class="flex items-center justify-center gap-2 text-2xl" id="star-rating-container">
                            <span class="star-icon cursor-pointer opacity-40 hover:opacity-100 transition" onclick="selectStar(1)">⭐</span>
                            <span class="star-icon cursor-pointer opacity-40 hover:opacity-100 transition" onclick="selectStar(2)">⭐</span>
                            <span class="star-icon cursor-pointer opacity-40 hover:opacity-100 transition" onclick="selectStar(3)">⭐</span>
                            <span class="star-icon cursor-pointer opacity-40 hover:opacity-100 transition" onclick="selectStar(4)">⭐</span>
                            <span class="star-icon cursor-pointer opacity-40 hover:opacity-100 transition" onclick="selectStar(5)">⭐</span>
                        </div>
                        <input type="hidden" id="rating-selected-val" value="5" required>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">ملاحظاتك واقتراحاتك</label>
                        <textarea id="rating-comment" rows="3" placeholder="اكتب رأيك حول جودة الخدمة، النظافة، أو سرعة التلبية..." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 outline-none focus:border-[#4E8F35]"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" onclick="closeRatingModal()" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold">إلغاء</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-[#4E8F35] hover:bg-[#3F742B] text-white font-black shadow-xs cursor-pointer">إرسال التقييم</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- REPORT PROBLEM MODAL -->
    <div id="problemModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs hidden flex items-center justify-center p-4">
        <div class="w-full max-w-sm bg-white rounded-3xl p-6 border border-rose-200 shadow-2xl space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-rose-100">
                <div class="flex items-center gap-2">
                    <span class="text-base">🚨</span>
                    <h3 class="font-black text-sm text-rose-900">إبلاغ عن مشكلة للكاشير</h3>
                </div>
                <button type="button" onclick="closeProblemModal()" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
            </div>

            <form id="form-problem" onsubmit="submitReportProblemForm(event)">
                <div class="space-y-3.5 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">تفاصيل المشكلة *</label>
                        <textarea id="problem-comment" rows="4" required placeholder="مثال: الواي فاي فصل، عدم وجود تكييف، الصوت مرتفع، أطلب مساعدة..." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 outline-none focus:border-rose-500"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" onclick="closeProblemModal()" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold">إلغاء</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-black shadow-xs cursor-pointer">إرسال البلاغ الآن</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- 5. Recent Orders Card -->
    @if($recentOrders->isNotEmpty())
        <div>
            <div class="flex items-center justify-between mb-2 px-1">
                <h2 class="text-xs font-bold text-[#303334]">آخر طلباتك</h2>
                <a href="{{ route('portal.orders') }}" class="text-[10px] font-bold text-[#4E8F35]">عرض الكل</a>
            </div>
            <div class="space-y-2">
                @foreach($recentOrders as $ord)
                    <div class="solid-card rounded-[20px] p-3.5 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-full bg-[#F5F3EE] text-[#4E8F35] flex items-center justify-center font-bold border border-[#E5E2DC]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3"/>
                                </svg>
                            </div>
                            <div>
                                <div class="font-bold text-[#303334]">
                                    {{ $ord->items->pluck('name')->join('، ') ?: 'طلب كافيه' }}
                                </div>
                                <div class="text-[10px] text-[#73777A]">
                                    {{ $ord->created_at->diffForHumans() }} • {{ $ord->table_or_room_name ?: 'المساحة' }}
                                </div>
                            </div>
                        </div>
                        <div class="text-left">
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold @if($ord->fulfillment_status === 'pending') bg-amber-50 text-amber-800 border border-amber-200 @elseif($ord->fulfillment_status === 'preparing') bg-[#EBF4E8] text-[#4E8F35] border border-[#DCE8D4] @elseif($ord->fulfillment_status === 'delivered') bg-[#EBF4E8] text-[#4E8F35] border border-[#DCE8D4] @else bg-rose-50 text-rose-700 @endif">
                                @if($ord->fulfillment_status === 'pending') قيد الانتظار @elseif($ord->fulfillment_status === 'preparing') جاري التحضير @elseif($ord->fulfillment_status === 'delivered') تم التسليم @else ملغي @endif
                            </span>
                            <div class="text-[11px] font-mono font-bold text-[#303334] mt-0.5">
                                {{ number_format($ord->total, 2) }} ج.م
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- ALL EVENTS BOTTOM SHEET MODAL -->
    <div id="allEventsModal" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm hidden flex items-end justify-center p-0 sm:p-4">
        <div class="w-full max-w-md bg-white rounded-t-[32px] sm:rounded-[32px] border border-[#E5E2DC] p-6 max-h-[85vh] flex flex-col shadow-2xl animate-fade-in">
            <!-- Sheet Drag Handle -->
            <div class="w-12 h-1.5 rounded-full bg-[#E5E2DC] mx-auto mb-4"></div>

            <div class="flex items-center justify-between pb-3 border-b border-[#E5E2DC]">
                <div class="flex items-center gap-2">
                    <span class="text-amber-500 text-base">★</span>
                    <h2 class="text-sm font-black text-[#303334]">كافة فعاليات وورش عمل DDT</h2>
                </div>
                <button type="button" onclick="closeAllEventsModal()" class="text-[#73777A] hover:text-[#303334] p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Events Scrollable List -->
            <div class="my-4 space-y-3 overflow-y-auto max-h-[60vh] no-scrollbar">
                @forelse($upcomingEvents as $event)
                    <div class="solid-card rounded-[22px] p-4 flex flex-col gap-2.5 relative border border-[#E5E2DC]">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-10 h-10 rounded-2xl bg-[#EBF4E8] text-[#4E8F35] flex flex-col items-center justify-center font-black leading-tight border border-[#DCE8D4]">
                                    <span class="text-[9px] uppercase">{{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('D') }}</span>
                                    <span class="text-xs font-mono">{{ \Carbon\Carbon::parse($event->event_date)->format('d') }}</span>
                                </div>
                                <div>
                                    <h3 class="text-xs font-black text-[#303334]">{{ $event->title }}</h3>
                                    <p class="text-[10px] text-[#73777A] mt-0.5">⏰ {{ $event->time_text }} • 📍 {{ $event->location }}</p>
                                </div>
                            </div>
                            @if($event->is_featured)
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black bg-amber-100 text-amber-900 border border-amber-200">
                                    ★ مميز
                                </span>
                            @endif
                        </div>

                        @if($event->description)
                            <p class="text-[10px] text-[#73777A] leading-relaxed line-clamp-2">
                                {{ $event->description }}
                            </p>
                        @endif

                        <div class="flex items-center justify-between pt-2 border-t border-[#E5E2DC] text-xs">
                            <span class="text-xs font-black text-[#4E8F35]">
                                {{ $event->price == 0 ? 'مجاناً للأعضاء' : number_format($event->price, 0) . ' ج.م' }}
                            </span>
                            @php
                                $waText = urlencode("مرحباً، أرغب في حجز مقعد في فعالية: " . $event->title);
                                $regUrl = $event->registration_url ?: "https://wa.me/201000000000?text={$waText}";
                            @endphp
                            <a href="{{ $regUrl }}" target="_blank" class="px-4 py-1.5 rounded-full bg-[#4E8F35] hover:bg-[#3F742B] text-white font-bold text-[10px] transition active:scale-95 shadow-xs">
                                حجز مقعد
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-xs text-[#73777A]">
                        لا توجد فعاليات قادمة حالياً. ترقبوا فعالياتنا الجديدة قريباً!
                    </div>
                @endforelse
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    function openAllEventsModal() {
        const m = document.getElementById('allEventsModal');
        if (m) m.classList.remove('hidden');
    }

    function closeAllEventsModal() {
        const m = document.getElementById('allEventsModal');
        if (m) m.classList.add('hidden');
    }

    function openRatingModal() {
        const m = document.getElementById('ratingModal');
        if (m) m.classList.remove('hidden');
    }

    function closeRatingModal() {
        const m = document.getElementById('ratingModal');
        if (m) m.classList.add('hidden');
    }

    function selectStar(val) {
        const input = document.getElementById('rating-selected-val');
        if (input) input.value = val;
        const stars = document.querySelectorAll('.star-icon');
        stars.forEach((s, idx) => {
            if (idx < val) {
                s.classList.remove('opacity-40');
                s.classList.add('opacity-100');
            } else {
                s.classList.remove('opacity-100');
                s.classList.add('opacity-40');
            }
        });
    }

    function submitFeedbackForm(e) {
        e.preventDefault();
        const ratingInput = document.getElementById('rating-selected-val');
        const commentInput = document.getElementById('rating-comment');
        const rating = ratingInput ? ratingInput.value : 5;
        const comment = commentInput ? commentInput.value : '';

        fetch("{{ route('portal.feedback') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify({ rating: rating, comment: comment })
        })
        .then(r => r.json())
        .then(data => {
            closeRatingModal();
            alert(data.message || 'تم إرسال تقييمك بنجاح.');
        })
        .catch(err => {
            console.error(err);
            alert('تعذر إرسال التقييم حالياً.');
        });
    }

    function openProblemModal() {
        const m = document.getElementById('problemModal');
        if (m) m.classList.remove('hidden');
    }

    function closeProblemModal() {
        const m = document.getElementById('problemModal');
        if (m) m.classList.add('hidden');
    }

    function submitReportProblemForm(e) {
        e.preventDefault();
        const commentInput = document.getElementById('problem-comment');
        const comment = commentInput ? commentInput.value : '';

        fetch("{{ route('portal.report-problem') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify({ comment: comment })
        })
        .then(r => r.json())
        .then(data => {
            closeProblemModal();
            if (commentInput) commentInput.value = '';
            alert(data.message || 'تم إرسال بلاغ المشكلة للكاشير.');
        })
        .catch(err => {
            console.error(err);
            alert('تعذر إرسال البلاغ حالياً.');
        });
    }

    function openBoostModal() {
        const m = document.getElementById('boostModal');
        if (m) {
            m.classList.remove('hidden');
            m.classList.add('flex');
        }
    }

    function closeBoostModal() {
        const m = document.getElementById('boostModal');
        if (m) {
            m.classList.add('hidden');
            m.classList.remove('flex');
        }
    }

    function copyTeacherLink(url) {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(url).then(showTeacherToast);
        } else {
            const temp = document.createElement('input');
            temp.value = url;
            document.body.appendChild(temp);
            temp.select();
            document.execCommand('copy');
            document.body.removeChild(temp);
            showTeacherToast();
        }
    }

    function showTeacherToast() {
        const toast = document.getElementById('teacher-toast');
        if (toast) {
            toast.classList.remove('opacity-0', 'pointer-events-none');
            toast.classList.add('opacity-100');
            setTimeout(() => {
                toast.classList.remove('opacity-100');
                toast.classList.add('opacity-0', 'pointer-events-none');
            }, 2500);
        }
    }
</script>

@if(Auth::guard('customer')->check() && Auth::guard('customer')->user()->isTeacher())
    @php
        $teacherObj = Auth::guard('customer')->user()->teacher();
        $tUrl = $teacherObj->qrUrl();
        $tMsg = urlencode("مرحباً بكم يا شباب! 🎓\nلتسجيل الحضور وتأكيد جلستكم في DDT WORKING SPACE، امسحوا الرابط ده:\n" . $tUrl);
        $tWhatsapp = "https://api.whatsapp.com/send?text={$tMsg}";
    @endphp
    <!-- Boost Earnings Modal (طرق زيادة أرباحك) -->
    <div id="boostModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs hidden items-center justify-center p-4">
        <div class="bg-white rounded-[32px] p-6 max-w-sm w-full text-center relative border border-[#E5E2DC] shadow-2xl animate-fade-in">
            <button type="button" onclick="closeBoostModal()"
                    class="absolute top-4 left-4 w-8 h-8 rounded-full bg-[#F5F3EE] flex items-center justify-center text-[#73777A] hover:text-[#303334]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-[#4E8F35] to-[#79B84A] text-white flex items-center justify-center mx-auto mb-2.5 text-2xl shadow-sm">
                🚀
            </div>

            <h3 class="text-sm font-black text-[#303334]">خطة زيادة أرباحك مع DDT</h3>
            <p class="text-[11px] text-[#73777A] mt-0.5">
                كل طالب يدخل ويسجل عبر الباركود يمنحك عمولة فورية بنسبة <strong class="text-[#4E8F35]">{{ $teacherObj->commission_rate }}%</strong>!
            </p>

            <!-- 3 Simple Boost Steps -->
            <div class="my-4 text-right space-y-2.5 bg-[#F5F3EE] p-3.5 rounded-2xl border border-[#E5E2DC] text-xs">
                <div class="flex items-start gap-2">
                    <span class="w-5 h-5 rounded-full bg-[#4E8F35] text-white text-[10px] font-black flex items-center justify-center flex-shrink-0 mt-0.5">1</span>
                    <span class="text-[#303334]"><strong>شارك الرابط في جروب الواتساب:</strong> دعوة سريعة لطلابك لتأكيد حضورهم عبر الباركود.</span>
                </div>
                <div class="flex items-start gap-2">
                    <span class="w-5 h-5 rounded-full bg-[#4E8F35] text-white text-[10px] font-black flex items-center justify-center flex-shrink-0 mt-0.5">2</span>
                    <span class="text-[#303334]"><strong>اعرض الباركود في بداية الكورس:</strong> الطلاب يمسحون الباركود بكاميرا الموبايل فور وصولهم.</span>
                </div>
                <div class="flex items-start gap-2">
                    <span class="w-5 h-5 rounded-full bg-[#4E8F35] text-white text-[10px] font-black flex items-center justify-center flex-shrink-0 mt-0.5">3</span>
                    <span class="text-[#303334]"><strong>أرباح مستمرة:</strong> يتم احتساب الكوميشن فورياً وإضافته لحسابك في لوحة المدرس.</span>
                </div>
            </div>

            <div class="space-y-2">
                <a href="{{ $tWhatsapp }}" target="_blank"
                   class="w-full py-2.5 px-4 rounded-full bg-[#25D366] hover:bg-[#20ba59] text-white text-xs font-black shadow-xs flex items-center justify-center gap-2 active:scale-95 transition">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    <span>مشاركة رسالة الدعوة عبر واتساب</span>
                </a>

                <button type="button" onclick="copyTeacherLink('{{ $tUrl }}')"
                        class="w-full py-2.5 px-4 rounded-full bg-[#F5F3EE] hover:bg-[#EBF4E8] text-[#303334] text-xs font-bold border border-[#E5E2DC] flex items-center justify-center gap-2 active:scale-95 transition">
                    <svg class="w-4 h-4 text-[#73777A]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    <span>نسخ رابط الحضور المباشر</span>
                </button>

                <a href="{{ route('portal.teacher') }}"
                   class="w-full py-2 px-4 rounded-full text-[#4E8F35] hover:text-[#3F742B] text-xs font-black block text-center mt-1">
                    عرض لوحة الأرباح والطلاب بالكامل ←
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Toast -->
    <div id="teacher-toast" class="fixed bottom-24 left-1/2 -translate-x-1/2 z-50 bg-[#303334] text-white text-xs font-bold px-4 py-2.5 rounded-full shadow-lg transition-all duration-300 opacity-0 pointer-events-none">
        تم نسخ رابط الحضور بنجاح 📋
    </div>
@endif
@endsection
