@extends('portal.layout')

@section('title', 'الولاء والمكافآت — DDT WORKING SPACE')

@section('content')

    <!-- Header (Solid Light) -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-sm font-extrabold text-[#303334]">بطاقة الولاء ومكافآت DDT</h1>
            <p class="text-[11px] text-[#738276]">برنامج تقدير أعضاء مجتمع مساحة العمل</p>
        </div>
        <div class="w-10 h-10 rounded-full bg-[#EBF4E8] text-[#4E8F35] flex items-center justify-center p-2.5 flex-shrink-0 font-bold">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
            </svg>
        </div>
    </div>

    <!-- 1. Reward Unlocked Banner (If eligible) -->
    @if($loyalty['reward_ready'])
        <div class="p-5 rounded-[26px] bg-[#4E8F35] text-white shadow-sm border border-[#4E8F35]">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-full bg-[#4E8F35] flex items-center justify-center text-white flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xs font-bold text-white">زيارتك القادمة مجانية بالكامل</h2>
                    <p class="text-[11px] text-[#DCE8D4] mt-0.5">لقد أتممت 5 زيارات مع جلسة 3+ ساعات. توجه للاستقبال لتفعيل جلستك المجانية.</p>
                </div>
            </div>
            <div class="mt-3.5 pt-2.5 border-t border-white/15 flex items-center justify-between text-[11px]">
                <span class="text-[#DCE8D4]">مفعّل تلقائياً لدى الكاشير والاستقبال</span>
                <span class="font-bold bg-white text-[#4E8F35] px-3 py-0.5 rounded-full font-mono text-[10px]">100% FREE DAY</span>
            </div>
        </div>
    @endif

    <!-- 2. Hero Digital Stamp Card in Solid Forest Green (Matching Reference Theme) -->
    <div class="solid-card-charcoal rounded-[28px] p-5 relative overflow-hidden shadow-[0_8px_24px_rgba(20,56,40,0.16)]">
        <div class="flex items-center justify-between pb-3.5 border-b border-white/15">
            <div>
                <span class="text-[10px] font-bold text-[#DCE8D4] uppercase tracking-wider block">بطاقة الختم الرقمية</span>
                <h2 class="text-xs font-bold text-white">زيارة مجانية بعد كل 5 زيارات</h2>
            </div>
            <div class="text-left">
                <span class="text-sm font-mono font-black text-white bg-[#4E8F35] px-3 py-1 rounded-full border border-white/15">{{ $loyalty['current_visits'] }}/5</span>
                <span class="text-[10px] text-[#DCE8D4] block mt-1">زيارات مكتملة</span>
            </div>
        </div>

        <!-- Stamps Row (Solid Pill Circular Badges) -->
        <div class="grid grid-cols-6 gap-2 my-4">
            @for($i = 1; $i <= 5; $i++)
                @php $isStamped = $loyalty['current_visits'] >= $i; @endphp
                <div class="flex flex-col items-center gap-1.5">
                    <div class="w-11 h-11 rounded-full flex items-center justify-center transition-all duration-300 {{ $isStamped ? 'bg-white text-[#4E8F35] shadow-sm' : 'bg-[#303334] border border-white/15 text-[#DCE8D4]' }}">
                        @if($isStamped)
                            <svg class="w-5 h-5 text-[#4E8F35]" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        @else
                            <span class="text-xs font-mono font-bold">{{ $i }}</span>
                        @endif
                    </div>
                    <span class="text-[9px] font-semibold {{ $isStamped ? 'text-white' : 'text-[#DCE8D4]/70' }}">زيارة {{ $i }}</span>
                </div>
            @endfor

            <!-- 6th Free Reward Stamp -->
            <div class="flex flex-col items-center gap-1.5">
                <div class="w-11 h-11 rounded-full flex items-center justify-center border-2 border-dashed transition-all duration-300 {{ $loyalty['reward_ready'] ? 'bg-white text-[#4E8F35] shadow-sm' : 'bg-[#303334] border-[#DCE8D4]/50 text-[#DCE8D4]' }}">
                    <svg class="w-5 h-5 {{ $loyalty['reward_ready'] ? 'text-[#4E8F35]' : 'text-[#DCE8D4]' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                    </svg>
                </div>
                <span class="text-[9px] font-bold text-[#DCE8D4]">مجاناً</span>
            </div>
        </div>

        <!-- Progress bar in Sage Mint -->
        <div class="w-full bg-[#303334] rounded-full h-2 overflow-hidden mb-3.5 border border-white/10">
            <div class="bg-white h-2 rounded-full transition-all duration-500" style="width: {{ $loyalty['progress_percent'] }}%"></div>
        </div>

        <!-- Rule Details Box -->
        <div class="space-y-2 pt-3 border-t border-white/15 text-xs text-[#DCE8D4]">
            <div class="flex items-center justify-between">
                <span class="flex items-center gap-1.5">
                    <span class="text-white font-bold">●</span>
                    <span>إجمالي 5 زيارات في الدورة:</span>
                </span>
                <strong class="text-white font-bold font-mono">
                    {{ $loyalty['current_visits'] }} من 5
                </strong>
            </div>

            <div class="flex items-center justify-between">
                <span class="flex items-center gap-1.5">
                    <span class="text-white font-bold">●</span>
                    <span>جلسة واحدة لا تقل عن {{ $loyalty['min_hours'] }} ساعات:</span>
                </span>
                @if($loyalty['has_long_session'])
                    <span class="text-[#DCE8D4] font-bold flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        تم تحقيق الشرط
                    </span>
                @else
                    <span class="text-amber-200 font-medium">بانتظار قضاء 3 ساعات في جلسة</span>
                @endif
            </div>
        </div>
    </div>

    <!-- 3. Community Referral Program (Solid White Card) -->
    <div class="solid-card rounded-[26px] p-5">
        <div class="flex items-center gap-2.5 mb-3">
            <div class="w-9 h-9 rounded-full bg-[#EBF4E8] text-[#4E8F35] flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-xs font-bold text-[#303334]">نظام الإحالة ومجتمع DDT</h2>
                <p class="text-[11px] text-[#738276]">أكثر من مكان.. مجتمع بيكبر معاك</p>
            </div>
        </div>

        <div class="p-3.5 rounded-2xl bg-[#F5F3EE] border border-[#E5E2DC] text-xs text-[#303334] mb-4">
            شارك كودك مع أي صديق لم يزر المساحة من قبل؛ سيحصل فوراً على <strong>خصم {{ $referralDiscount }}{{ $referralType === 'percentage' ? '%' : ' ج.م' }}</strong> عند أول زيارة، وأنت تكسب نقاط ومكافآت مضاعفة.
        </div>

        <!-- Referral Code Box -->
        <div class="space-y-3">
            <div>
                <label class="block text-[11px] font-bold text-[#738276] mb-1">كود الإحالة الخاص بك:</label>
                <div class="flex items-center gap-2">
                    <div class="flex-1 bg-[#F5F3EE] border border-[#E5E2DC] rounded-full px-4 py-2.5 text-center text-sm font-bold font-mono tracking-widest text-[#303334]">
                        {{ $customer->referral_code }}
                    </div>
                    <button type="button" onclick="navigator.clipboard.writeText('{{ $customer->referral_code }}'); alert('تم نسخ كود الإحالة بنجاح!');" class="pill-btn-outline px-4 py-2.5 text-xs shadow-xs">
                        نسخ الكود
                    </button>
                </div>
            </div>

            <!-- WhatsApp Share Button -->
            <a href="https://wa.me/?text={{ urlencode('أنا في مساحة العمل DDT Working Space! سجل بكود الخصم بتاعي واستمتع بخصم '. $referralDiscount. ($referralType === 'percentage'? '%': ' ج.م'). ' على أول زيارة: '. url('/ref/'. $customer->referral_code)) }}" target="_blank" class="w-full py-3 px-4 pill-btn-forest text-xs flex items-center justify-center gap-2 shadow-sm">
                <span>مشاركة عبر واتساب بنقرة واحدة</span>
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
            </a>

            <div class="pt-2 flex items-center justify-between text-xs text-[#738276]">
                <span>عدد الأصدقاء المسجلين بكودك:</span>
                <strong class="text-[#303334] font-mono font-bold">{{ $referralsCount }} صديق</strong>
            </div>
        </div>
    </div>

    <!-- 4. Visits & Sessions Log -->
    <div class="mt-2">
        <h2 class="text-xs font-bold text-[#738276] mb-2 px-1">سجل زياراتك في DDT</h2>
        <div class="space-y-2">
            @forelse($dealsHistory as $deal)
                <div class="solid-card rounded-[20px] p-3.5 flex items-center justify-between text-xs">
                    <div>
                        <div class="font-bold text-[#303334]">
                            {{ $deal->room ? $deal->room->name : 'المساحة العامة' }}
                        </div>
                        <div class="text-[10px] text-[#738276] mt-0.5">
                            {{ \Carbon\Carbon::parse($deal->started_at)->format('Y-m-d') }} • {{ \Carbon\Carbon::parse($deal->started_at)->format('h:i A') }}
                        </div>
                    </div>
                    <div class="text-left">
                        <span class="font-mono font-bold text-[#303334] block">
                            {{ $deal->duration_minutes ?? 0 }} دقيقة ({{ round(($deal->duration_minutes ?? 0) / 60, 1) }} ساعة)
                        </span>
                        @if(($deal->duration_minutes ?? 0) >= $loyalty['min_minutes'])
                            <span class="text-[10px] text-[#4E8F35] font-bold bg-[#EBF4E8] px-2 py-0.5 rounded-full inline-block mt-0.5">جلسة +3 ساعات ✓</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="solid-card rounded-[20px] p-4 text-center text-[#738276] text-xs">
                    لم يتم تسجيل جلسات منتهية بعد.
                </div>
            @endforelse
        </div>
    </div>

@endsection
