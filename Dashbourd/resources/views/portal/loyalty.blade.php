@extends('portal.layout')

@section('title', 'الولاء والمكافآت — DDT WORKING SPACE')

@section('content')

    <!-- Header (Solid Light) -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-sm font-extrabold text-[#303334]">بطاقة الولاء ومكافآت DDT</h1>
            <p class="text-[11px] text-[#73777A]">نظام تقدير أعضاء DDT Working Space المميزين</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-[#F5F3EE] border border-[#E5E2DC] text-[#4E8F35] flex items-center justify-center p-2 flex-shrink-0">
            <img src="{{ asset('images/ddt-flask-icon.svg') }}" class="w-full h-full object-contain" alt="DDT Flask"/>
        </div>
    </div>

    <!-- 1. Reward Unlocked Banner (If eligible) -->
    @if($loyalty['reward_ready'])
        <div class="p-4 rounded-2xl bg-[#4E8F35] text-white shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xs font-bold text-white">زيارتك القادمة مجانية بالكامل</h2>
                    <p class="text-[11px] text-white/90 mt-0.5">لقد أتممت 5 زيارات مع جلسة 3+ ساعات. توجه للكاشير لتفعيل جلستك المجانية.</p>
                </div>
            </div>
            <div class="mt-3 pt-2 border-t border-white/20 flex items-center justify-between text-[11px]">
                <span>مفعّل تلقائياً لدى الكاشير والاستقبال</span>
                <span class="font-bold bg-white text-[#4E8F35] px-2.5 py-0.5 rounded-full font-mono text-[10px]">100% FREE DAY</span>
            </div>
        </div>
    @endif

    <!-- 2. Digital Stamp Card (5 Visits + 3h Session Rule) -->
    <div class="solid-card rounded-2xl p-5 relative overflow-hidden">
        <div class="flex items-center justify-between pb-3 border-b border-[#E5E2DC]">
            <div>
                <span class="text-[10px] font-bold text-[#4E8F35] uppercase tracking-wider block">بطاقة الختم الرقمية</span>
                <h2 class="text-xs font-bold text-[#303334]">زيارة مجانية بعد كل 5 زيارات</h2>
            </div>
            <div class="text-left">
                <span class="text-xs font-mono font-bold text-[#4E8F35]">{{ $loyalty['current_visits'] }}/5</span>
                <span class="text-[10px] text-[#73777A] block">زيارات مكتملة</span>
            </div>
        </div>

        <!-- Stamps Row with DDT Flasks -->
        <div class="grid grid-cols-6 gap-2 my-4">
            @for($i = 1; $i <= 5; $i++)
                @php $isStamped = $loyalty['current_visits'] >= $i; @endphp
                <div class="flex flex-col items-center gap-1.5">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center transition-all duration-300 {{ $isStamped ? 'bg-[#EBF4E8] border border-[#4E8F35] text-[#4E8F35] shadow-xs' : 'bg-[#F5F3EE] border border-[#E5E2DC] text-[#73777A]' }}">
                        @if($isStamped)
                            <img src="{{ asset('images/ddt-flask-icon.svg') }}" class="w-6 h-6" alt="Stamped"/>
                        @else
                            <span class="text-xs font-mono font-bold">{{ $i }}</span>
                        @endif
                    </div>
                    <span class="text-[9px] {{ $isStamped ? 'text-[#4E8F35] font-bold' : 'text-[#73777A]' }}">زيارة {{ $i }}</span>
                </div>
            @endfor

            <!-- 6th Free Reward Stamp -->
            <div class="flex flex-col items-center gap-1.5">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center border-2 border-dashed transition-all duration-300 {{ $loyalty['reward_ready'] ? 'bg-[#4E8F35] text-white border-[#4E8F35] shadow-sm' : 'bg-[#F5F3EE] border-[#DCE8D4] text-[#4E8F35]' }}">
                    <svg class="w-5 h-5 {{ $loyalty['reward_ready'] ? 'text-white' : 'text-[#4E8F35]' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                    </svg>
                </div>
                <span class="text-[9px] font-bold {{ $loyalty['reward_ready'] ? 'text-[#4E8F35]' : 'text-[#73777A]' }}">مجاناً</span>
            </div>
        </div>

        <!-- Progress bar in DDT green -->
        <div class="w-full bg-[#F5F3EE] rounded-full h-2 overflow-hidden mb-3 border border-[#E5E2DC]/50">
            <div class="bg-[#4E8F35] h-2 rounded-full transition-all duration-500" style="width: {{ $loyalty['progress_percent'] }}%"></div>
        </div>

        <!-- Rule Details Box -->
        <div class="space-y-2 pt-2.5 border-t border-[#E5E2DC] text-xs text-[#303334]">
            <div class="flex items-center justify-between">
                <span class="flex items-center gap-1.5 text-[#73777A]">
                    <span class="text-[#4E8F35] font-bold">●</span>
                    <span>إجمالي 5 زيارات في الدورة:</span>
                </span>
                <strong class="{{ $loyalty['current_visits'] >= 5 ? 'text-[#4E8F35]' : 'text-[#303334]' }}">
                    {{ $loyalty['current_visits'] }} من 5
                </strong>
            </div>

            <div class="flex items-center justify-between">
                <span class="flex items-center gap-1.5 text-[#73777A]">
                    <span class="text-[#4E8F35] font-bold">●</span>
                    <span>جلسة واحدة لا تقل عن {{ $loyalty['min_hours'] }} ساعات:</span>
                </span>
                @if($loyalty['has_long_session'])
                    <span class="text-[#4E8F35] font-bold flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        تم تحقيق الشرط
                    </span>
                @else
                    <span class="text-[#D97706] font-medium">بانتظار قضاء 3 ساعات في جلسة</span>
                @endif
            </div>
        </div>
    </div>

    <!-- 3. Community Referral Program (Solid Light Card) -->
    <div class="solid-card rounded-2xl p-5">
        <div class="flex items-center gap-2.5 mb-3">
            <div class="w-9 h-9 rounded-xl bg-[#EBF4E8] text-[#4E8F35] flex items-center justify-center border border-[#DCE8D4]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-xs font-bold text-[#303334]">نظام التوصية ومجتمع DDT</h2>
                <p class="text-[11px] text-[#73777A]">أكثر من مكان.. مجتمع بيكبر معاك</p>
            </div>
        </div>

        <div class="p-3 rounded-xl bg-[#F7FAF5] border border-[#DCE8D4] text-xs text-[#303334] mb-4">
            شارك كودك مع أي صديق لم يزر المساحة من قبل؛ سيحصل فوراً على <strong>خصم {{ $referralDiscount }}{{ $referralType === 'percentage' ? '%' : ' ج.م' }}</strong> عند أول زيارة، وأنت تكسب نقاط ومكافآت مضاعفة.
        </div>

        <!-- Referral Code Box -->
        <div class="space-y-3">
            <div>
                <label class="block text-[11px] font-bold text-[#73777A] mb-1">كود الإحالة الخاص بك:</label>
                <div class="flex items-center gap-2">
                    <div class="flex-1 bg-[#F5F3EE] border border-[#E5E2DC] rounded-xl px-4 py-2.5 text-center text-sm font-bold font-mono tracking-widest text-[#303334]">
                        {{ $customer->referral_code }}
                    </div>
                    <button type="button" onclick="navigator.clipboard.writeText('{{ $customer->referral_code }}'); alert('تم نسخ كود الإحالة بنجاح!');" class="px-4 py-2.5 rounded-xl bg-white hover:bg-[#F5F3EE] text-[#303334] font-bold text-xs border border-[#E5E2DC] transition active:scale-95 shadow-xs">
                        نسخ الكود
                    </button>
                </div>
            </div>

            <!-- WhatsApp 1-Click Share Button -->
            <a href="https://wa.me/?text={{ urlencode('أنا في مساحة العمل DDT Working Space! سجل بكود الخصم بتاعي واستمتع بخصم '. $referralDiscount. ($referralType === 'percentage'? '%': ' ج.م'). ' على أول زيارة: '. url('/ref/'. $customer->referral_code)) }}" target="_blank" class="w-full py-2.5 px-4 rounded-xl bg-[#4E8F35] hover:bg-[#3F742B] font-bold text-white text-xs shadow-sm flex items-center justify-center gap-2 active:scale-95 transition">
                <span>مشاركة عبر واتساب بنقرة واحدة</span>
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
            </a>

            <div class="pt-2 flex items-center justify-between text-xs text-[#73777A]">
                <span>عدد الأصدقاء المسجلين بكودك:</span>
                <strong class="text-[#303334] font-mono font-bold">{{ $referralsCount }} صديق</strong>
            </div>
        </div>
    </div>

    <!-- 4. Visits & Sessions Log -->
    <div>
        <h2 class="text-xs font-bold text-[#73777A] mb-2">سجل زياراتك في DDT</h2>
        <div class="space-y-2">
            @forelse($dealsHistory as $deal)
                <div class="solid-card rounded-xl p-3 flex items-center justify-between text-xs">
                    <div>
                        <div class="font-bold text-[#303334]">
                            {{ $deal->room ? $deal->room->name : 'المساحة العامة' }}
                        </div>
                        <div class="text-[10px] text-[#73777A]">
                            {{ \Carbon\Carbon::parse($deal->started_at)->format('Y-m-d') }} • {{ \Carbon\Carbon::parse($deal->started_at)->format('h:i A') }}
                        </div>
                    </div>
                    <div class="text-left">
                        <span class="font-mono font-bold text-[#303334] block">
                            {{ $deal->duration_minutes ?? 0 }} دقيقة ({{ round(($deal->duration_minutes ?? 0) / 60, 1) }} ساعة)
                        </span>
                        @if(($deal->duration_minutes ?? 0) >= $loyalty['min_minutes'])
                            <span class="text-[10px] text-[#4E8F35] font-bold">جلسة +3 ساعات ✓</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="solid-card rounded-xl p-4 text-center text-[#73777A] text-xs">
                    لم يتم تسجيل جلسات منتهية بعد.
                </div>
            @endforelse
        </div>
    </div>

@endsection
