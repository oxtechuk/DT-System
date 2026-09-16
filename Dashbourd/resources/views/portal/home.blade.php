@extends('portal.layout')

@section('title', 'الرئيسية — DDT WORKING SPACE')

@section('content')

    <!-- 1. Hero Brand Banner (Solid Light Card with DDT Green Identity) -->
    <div class="solid-card rounded-2xl p-5 relative overflow-hidden">
        <div class="flex items-start justify-between">
            <div class="space-y-1.5 max-w-[80%]">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#EBF4E8] text-[#4E8F35] border border-[#DCE8D4]">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#4E8F35]"></span>
                    مستقل.. مش لوحدك
                </span>
                <h1 class="text-base font-extrabold text-[#303334] leading-snug">
                    أكثر من مكان.. <span class="text-[#4E8F35]">مجتمع بيكبر معاك</span>
                </h1>
                <p class="text-[11px] text-[#73777A] leading-relaxed">
                    مرحباً بك في DDT Working Space، مساحتك الهادئة للإبداع والإنتاجية وتبادل الخبرات.
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-[#F5F3EE] border border-[#E5E2DC] flex items-center justify-center p-2.5 flex-shrink-0">
                <img src="{{ asset('images/ddt-flask-icon.svg') }}" alt="DDT Icon" class="w-full h-full object-contain"/>
            </div>
        </div>

        <!-- 4 Core Brand Pillars (Crisp Vector SVGs - Zero Emojis) -->
        <div class="grid grid-cols-4 gap-2 pt-4 mt-3.5 border-t border-[#E5E2DC] text-center">
            <div class="p-2 rounded-xl bg-[#F5F3EE] border border-[#E5E2DC]/60 flex flex-col items-center">
                <svg class="w-4 h-4 text-[#4E8F35]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <div class="text-[10px] font-bold text-[#303334] mt-1">مجتمع حقيقي</div>
                <div class="text-[8px] text-[#73777A]">Community</div>
            </div>
            <div class="p-2 rounded-xl bg-[#F5F3EE] border border-[#E5E2DC]/60 flex flex-col items-center">
                <svg class="w-4 h-4 text-[#4E8F35]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect width="18" height="12" x="3" y="4" rx="2"/><line x1="2" x2="22" y1="20" y2="20"/>
                </svg>
                <div class="text-[10px] font-bold text-[#303334] mt-1">إنتاجية أعلى</div>
                <div class="text-[8px] text-[#73777A]">Productivity</div>
            </div>
            <div class="p-2 rounded-xl bg-[#F5F3EE] border border-[#E5E2DC]/60 flex flex-col items-center">
                <svg class="w-4 h-4 text-[#4E8F35]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3"/>
                </svg>
                <div class="text-[10px] font-bold text-[#303334] mt-1">أجواء هادئة</div>
                <div class="text-[8px] text-[#73777A]">Better Vibes</div>
            </div>
            <div class="p-2 rounded-xl bg-[#F5F3EE] border border-[#E5E2DC]/60 flex flex-col items-center">
                <svg class="w-4 h-4 text-[#4E8F35]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
                <div class="text-[10px] font-bold text-[#303334] mt-1">فرص أكبر</div>
                <div class="text-[8px] text-[#73777A]">Opportunities</div>
            </div>
        </div>
    </div>

    <!-- 2. Active Session Status (If Checked In) -->
    @if($activeDeal)
        <div class="solid-card rounded-2xl p-4 border-r-4 border-r-[#4E8F35] relative overflow-hidden">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-2.5">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#79B84A] opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-[#4E8F35]"></span>
                    </span>
                    <div>
                        <h2 class="text-xs font-bold text-[#303334] flex items-center gap-1.5">
                            <span>جلستك الحالية نشطة</span>
                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-[#EBF4E8] text-[#4E8F35] font-semibold">بالفرع الآن</span>
                        </h2>
                        <p class="text-xs text-[#73777A] mt-0.5">
                            {{ $activeDeal->room ? $activeDeal->room->name : 'المساحة المشتركة' }} • 
                            <span class="font-mono font-bold text-[#4E8F35]">{{ \Carbon\Carbon::parse($activeDeal->started_at)->format('h:i A') }}</span>
                        </p>
                    </div>
                </div>

                <a href="{{ route('portal.menu') }}" class="px-3.5 py-2 rounded-xl bg-[#4E8F35] hover:bg-[#3F742B] text-white font-bold text-xs shadow-sm transition active:scale-95 flex items-center gap-1">
                    <span>اطلب مشروبك</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                </a>
            </div>

            <div class="mt-3 pt-2.5 border-t border-[#E5E2DC] flex items-center justify-between text-[11px] text-[#73777A]">
                <span>نوع الباقة: <strong class="text-[#303334]">{{ $activeDeal->workspaceType ? $activeDeal->workspaceType->name : 'مكتب عمل' }}</strong></span>
                <span class="text-[#4E8F35] font-semibold flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-[#4E8F35]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>طلبات الكافيه تصل لطاولتك</span>
                </span>
            </div>
        </div>
    @else
        <!-- Guest / Not checked in banner -->
        <div class="solid-card rounded-2xl p-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-[#F5F3EE] border border-[#E5E2DC] flex items-center justify-center text-[#73777A] flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xs font-bold text-[#303334]">لست في جلسة عمل حالياً؟</h2>
                    <p class="text-[11px] text-[#73777A] mt-0.5">سجل دخولك عند الاستقبال بالريسبشن لاحتساب زيارات بطاقة الولاء.</p>
                </div>
            </div>
            <a href="{{ route('portal.menu') }}" class="px-3 py-1.5 rounded-xl bg-[#F5F3EE] hover:bg-[#EBF4E8] text-[#303334] hover:text-[#4E8F35] border border-[#E5E2DC] font-bold text-xs flex-shrink-0 transition">
                تصفح المنيو
            </a>
        </div>
    @endif

    <!-- 3. Community Events Teaser Banner (Connecting to مجتمعي) -->
    @if(isset($upcomingEvents) && $upcomingEvents->isNotEmpty())
        @php $nextEvent = $upcomingEvents->first(); @endphp
        <div class="solid-card-sage rounded-2xl p-4 relative overflow-hidden">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#4E8F35]"></span>
                    <span class="text-[10px] font-bold text-[#4E8F35] uppercase">فعالية قادمة في مجتمع DDT</span>
                </div>
                <a href="{{ route('portal.community') }}" class="text-[11px] font-bold text-[#4E8F35] hover:underline flex items-center gap-0.5">
                    <span>عرض الكل</span>
                    <svg class="w-3 h-3 rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
            </div>

            <div class="flex items-start justify-between gap-3">
                <div class="flex-1 min-w-0">
                    <h3 class="text-xs font-extrabold text-[#303334] leading-snug line-clamp-1">
                        {{ $nextEvent->title }}
                    </h3>
                    <div class="text-[10px] text-[#73777A] mt-1 flex items-center gap-3">
                        <span class="font-bold text-[#4E8F35]">{{ \Carbon\Carbon::parse($nextEvent->event_date)->translatedFormat('d F') }}</span>
                        <span>•</span>
                        <span>{{ $nextEvent->time_text }}</span>
                        @if($nextEvent->speaker_name)
                            <span>•</span>
                            <span>{{ $nextEvent->speaker_name }}</span>
                        @endif
                    </div>
                </div>

                <a href="{{ route('portal.community') }}" class="px-3 py-1.5 rounded-xl bg-[#4E8F35] text-white text-[11px] font-bold flex-shrink-0 shadow-sm hover:bg-[#3F742B] transition">
                    تفاصيل الفعالية
                </a>
            </div>
        </div>
    @endif

    <!-- 4. Loyalty Stamp Card Widget (Solid Light Card) -->
    <div class="solid-card rounded-2xl p-4.5 relative overflow-hidden">
        <div class="flex items-center justify-between mb-3 pb-2 border-b border-[#E5E2DC]">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-[#EBF4E8] text-[#4E8F35] flex items-center justify-center font-bold">
                    <img src="{{ asset('images/ddt-flask-icon.svg') }}" class="w-4 h-4" alt="DDT Flask"/>
                </div>
                <div>
                    <h2 class="text-xs font-bold text-[#303334]">بطاقة ختم ولاء DDT</h2>
                    <p class="text-[10px] text-[#73777A]">5 زيارات + جلسة 3 ساعات فأكثر = السادسة مجاناً</p>
                </div>
            </div>
            <a href="{{ route('portal.loyalty') }}" class="text-[11px] font-bold text-[#4E8F35] hover:text-[#3F742B] flex items-center gap-0.5">
                <span>التفاصيل</span>
                <svg class="w-3 h-3 rotate-180" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
        </div>

        <!-- 5 Stamps Row with DDT Flask Icons -->
        <div class="grid grid-cols-6 gap-2 py-1">
            @for($i = 1; $i <= 5; $i++)
                @php $isCompleted = $loyalty['current_visits'] >= $i; @endphp
                <div class="flex flex-col items-center gap-1">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-300 {{ $isCompleted ? 'bg-[#EBF4E8] border border-[#4E8F35] text-[#4E8F35] shadow-sm' : 'bg-[#F5F3EE] border border-[#E5E2DC] text-[#73777A]' }}">
                        @if($isCompleted)
                            <img src="{{ asset('images/ddt-flask-icon.svg') }}" class="w-5 h-5" alt="Completed Stamp"/>
                        @else
                            <span class="text-xs font-bold font-mono">{{ $i }}</span>
                        @endif
                    </div>
                    <span class="text-[9px] {{ $isCompleted ? 'text-[#4E8F35] font-bold' : 'text-[#73777A]' }}">زيارة {{ $i }}</span>
                </div>
            @endfor

            <!-- 6th Free Reward Stamp -->
            <div class="flex flex-col items-center gap-1">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center border-2 border-dashed transition-all duration-300 {{ $loyalty['reward_ready'] ? 'bg-[#4E8F35] text-white border-[#4E8F35] shadow-md scale-105' : 'bg-[#F5F3EE] border-[#DCE8D4] text-[#4E8F35]' }}">
                    <svg class="w-5 h-5 {{ $loyalty['reward_ready'] ? 'text-white' : 'text-[#4E8F35]' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                    </svg>
                </div>
                <span class="text-[9px] font-bold {{ $loyalty['reward_ready'] ? 'text-[#4E8F35]' : 'text-[#73777A]' }}">هدية مجاناً</span>
            </div>
        </div>

        <!-- 3h Condition Status Pill -->
        <div class="mt-2.5 pt-2.5 border-t border-[#E5E2DC] flex items-center justify-between text-[11px]">
            <span class="text-[#73777A]">شرط قضاء جلسة 3 ساعات أو أكثر:</span>
            @if($loyalty['has_long_session'])
                <span class="text-[#4E8F35] font-bold flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    محقّق بالكامل
                </span>
            @else
                <span class="text-[#D97706] font-medium">بانتظار إتمام 3 ساعات في جلسة</span>
            @endif
        </div>
    </div>

    <!-- 5. Quick Actions Grid (Solid Cards) -->
    <div class="grid grid-cols-2 gap-3">
        <a href="{{ route('portal.menu') }}" class="solid-card rounded-2xl p-4 flex flex-col items-start gap-2 hover:border-[#4E8F35] transition group active:scale-95">
            <div class="w-10 h-10 rounded-xl bg-[#EBF4E8] text-[#4E8F35] flex items-center justify-center border border-[#DCE8D4]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3"/>
                </svg>
            </div>
            <div>
                <h2 class="text-xs font-bold text-[#303334]">منيو المشروبات</h2>
                <p class="text-[10px] text-[#73777A]">قهوة مختصة، شاي، وسناكس</p>
            </div>
        </a>

        <a href="{{ route('portal.community') }}" class="solid-card rounded-2xl p-4 flex flex-col items-start gap-2 hover:border-[#4E8F35] transition group active:scale-95">
            <div class="w-10 h-10 rounded-xl bg-[#F5F3EE] text-[#303334] flex items-center justify-center border border-[#E5E2DC]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-xs font-bold text-[#303334]">مجتمع DDT</h2>
                <p class="text-[10px] text-[#73777A]">ورش العمل والبانرات</p>
            </div>
        </a>
    </div>

    <!-- 6. Affiliate & Community Referral Card (Solid Light Card) -->
    <div class="solid-card rounded-2xl p-4">
        <div class="flex items-center justify-between mb-1.5">
            <div>
                <h2 class="text-xs font-bold text-[#303334]">ادعُ صديقاً لمجتمع DDT واكسب خصم</h2>
                <p class="text-[10px] text-[#73777A]">صديقك يربح خصم {{ $referralDiscount }}{{ $referralType === 'percentage' ? '%' : ' ج.م' }} على أول زيارة وأنت تكسب نقاط ومكافآت.</p>
            </div>
        </div>
        <div class="mt-3 flex items-center gap-2">
            <div class="flex-1 bg-[#F5F3EE] border border-[#E5E2DC] rounded-xl px-3 py-2 flex items-center justify-between text-xs font-mono font-bold text-[#303334]">
                <span>{{ $customer->referral_code }}</span>
                <button type="button" onclick="navigator.clipboard.writeText('{{ $customer->referral_code }}'); alert('تم نسخ كود الإحالة بنجاح!');" class="text-[#73777A] hover:text-[#303334] text-[10px] bg-white border border-[#E5E2DC] px-2 py-0.5 rounded transition">نسخ</button>
            </div>
            <a href="https://wa.me/?text={{ urlencode('أنا في مساحة العمل DDT Working Space! سجل بكود الخصم بتاعي واستمتع بخصم '. $referralDiscount. ($referralType === 'percentage'? '%': ' ج.م'). ' على أول زيارة: '. url('/ref/'. $customer->referral_code)) }}" target="_blank" class="px-3.5 py-2 rounded-xl bg-[#4E8F35] hover:bg-[#3F742B] text-white font-bold text-xs flex items-center gap-1.5 shadow-sm transition">
                <span>واتساب</span>
            </a>
        </div>
    </div>

    <!-- 7. Recent Orders Snippet -->
    @if($recentOrders->isNotEmpty())
        <div>
            <div class="flex items-center justify-between mb-2 px-1">
                <h2 class="text-xs font-bold text-[#303334]">آخر طلباتك</h2>
                <a href="{{ route('portal.orders') }}" class="text-[10px] font-bold text-[#4E8F35]">عرض الكل</a>
            </div>
            <div class="space-y-2">
                @foreach($recentOrders as $ord)
                    <div class="solid-card rounded-xl p-3 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-[#F5F3EE] text-[#4E8F35] flex items-center justify-center font-bold border border-[#E5E2DC]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3"/>
                                </svg>
                            </div>
                            <div>
                                <div class="font-bold text-[#303334]">
                                    {{ $ord->items->pluck('name')->join('، ') ?: 'طلب مشروبات' }}
                                </div>
                                <div class="text-[10px] text-[#73777A]">
                                    {{ $ord->created_at->diffForHumans() }} • {{ $ord->table_or_room_name ?: 'المساحة' }}
                                </div>
                            </div>
                        </div>
                        <div class="text-left">
                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold @if($ord->fulfillment_status === 'pending') bg-amber-50 text-amber-700 border border-amber-200 @elseif($ord->fulfillment_status === 'preparing') bg-[#EBF4E8] text-[#4E8F35] border border-[#DCE8D4] @elseif($ord->fulfillment_status === 'delivered') bg-emerald-50 text-emerald-700 border border-emerald-200 @else bg-rose-50 text-rose-700 @endif">
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

@endsection
