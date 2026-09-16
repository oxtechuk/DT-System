@extends('portal.layout')

@section('title', 'مجتمعي — فعاليات وبانرات DDT WORKING SPACE')

@section('content')

    <!-- 1. Community Editorial Hero Card -->
    <div class="solid-card rounded-2xl p-5 relative overflow-hidden">
        <div class="flex items-start justify-between">
            <div class="space-y-1.5 max-w-[80%]">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#EBF4E8] text-[#4E8F35] border border-[#DCE8D4]">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#4E8F35]"></span>
                    مستقل.. مش لوحدك
                </span>
                <h1 class="text-base font-extrabold text-[#303334] leading-snug">
                    مجتمع DDT.. <span class="text-[#4E8F35]">بيكبر معاك</span>
                </h1>
                <p class="text-[11px] text-[#73777A] leading-relaxed">
                    فعاليات، ورش عمل تخصصية، ولقاءات ملهمة تجمع رواد الأعمال والمستقلين في مساحة DDT.
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-[#F5F3EE] border border-[#E5E2DC] flex items-center justify-center p-2 flex-shrink-0">
                <img src="{{ asset('images/ddt-flask-icon.svg') }}" alt="DDT Icon" class="w-full h-full object-contain"/>
            </div>
        </div>

        <!-- Community Highlights -->
        <div class="grid grid-cols-3 gap-2 mt-4 pt-3.5 border-t border-[#E5E2DC] text-center">
            <div class="p-2 rounded-xl bg-[#F5F3EE] border border-[#E5E2DC]/60">
                <div class="text-[10px] font-bold text-[#303334]">لقاءات دورية</div>
                <div class="text-[9px] text-[#73777A] mt-0.5">تبادل خبرات</div>
            </div>
            <div class="p-2 rounded-xl bg-[#F5F3EE] border border-[#E5E2DC]/60">
                <div class="text-[10px] font-bold text-[#303334]">ورش متقدمة</div>
                <div class="text-[9px] text-[#73777A] mt-0.5">تطوير مهارات</div>
            </div>
            <div class="p-2 rounded-xl bg-[#F5F3EE] border border-[#E5E2DC]/60">
                <div class="text-[10px] font-bold text-[#303334]">استشارات</div>
                <div class="text-[9px] text-[#73777A] mt-0.5">توجيه رواد الأعمال</div>
            </div>
        </div>
    </div>

    <!-- 2. Featured Event Banner (البانر الرئيسي للفعاليات) -->
    @if($featuredEvents->isNotEmpty())
        @php $featured = $featuredEvents->first(); @endphp
        <div>
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <span class="w-1 h-3.5 rounded-full bg-[#4E8F35]"></span>
                    <h2 class="text-xs font-bold text-[#303334]">فعالية مميزة</h2>
                </div>
                <span class="text-[10px] font-semibold text-[#73777A]">احجز مقعدك مبكراً</span>
            </div>

            <!-- Featured Banner Card -->
            <div class="rounded-2xl p-5 relative overflow-hidden transition shadow-sm {{ $featured->banner_theme === 'charcoal' ? 'solid-card-charcoal' : ($featured->banner_theme === 'sage' ? 'solid-card-sage text-[#303334]' : 'solid-card-green') }}">
                <div class="flex items-center justify-between mb-2.5">
                    <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full {{ $featured->banner_theme === 'green' ? 'bg-white/20 text-white border border-white/30' : ($featured->banner_theme === 'charcoal' ? 'bg-white/10 text-slate-200 border border-white/20' : 'bg-[#EBF4E8] text-[#4E8F35] border border-[#DCE8D4]') }}">
                        {{ $featured->category }}
                    </span>
                    @if($featured->badge_text)
                        <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full {{ $featured->banner_theme === 'green' ? 'bg-white text-[#4E8F35]' : ($featured->banner_theme === 'charcoal' ? 'bg-[#79B84A] text-white' : 'bg-[#4E8F35] text-white') }}">
                            {{ $featured->badge_text }}
                        </span>
                    @endif
                </div>

                <h3 class="text-sm font-extrabold mb-2 leading-relaxed {{ $featured->banner_theme === 'sage' ? 'text-[#303334]' : 'text-white' }}">
                    {{ $featured->title }}
                </h3>

                @if($featured->description)
                    <p class="text-[11px] mb-3.5 leading-relaxed {{ $featured->banner_theme === 'sage' ? 'text-[#73777A]' : 'text-white/85' }}">
                        {{ $featured->description }}
                    </p>
                @endif

                <!-- Meta Details -->
                <div class="grid grid-cols-2 gap-2 text-[11px] mb-4 {{ $featured->banner_theme === 'sage' ? 'text-[#303334]' : 'text-white/90' }}">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect width="18" height="18" x="3" y="4" rx="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/>
                        </svg>
                        <span class="font-bold">{{ \Carbon\Carbon::parse($featured->event_date)->translatedFormat('l, d F') }}</span>
                    </div>

                    <div class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                        </svg>
                        <span>{{ $featured->time_text }}</span>
                    </div>

                    @if($featured->speaker_name)
                        <div class="flex items-center gap-1.5 col-span-2">
                            <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>{{ $featured->speaker_name }} • <strong class="font-normal opacity-75">{{ $featured->speaker_title }}</strong></span>
                        </div>
                    @endif

                    <div class="flex items-center gap-1.5 col-span-2">
                        <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><circle cx="12" cy="11" r="2"/>
                        </svg>
                        <span>{{ $featured->location }}</span>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="pt-3 border-t {{ $featured->banner_theme === 'sage' ? 'border-[#DCE8D4]' : 'border-white/20' }} flex items-center justify-between">
                    <div class="text-xs font-black">
                        @if($featured->price == 0)
                            <span class="{{ $featured->banner_theme === 'sage' ? 'text-[#4E8F35]' : 'text-white' }}">الدخول مجاني</span>
                        @else
                            <span class="{{ $featured->banner_theme === 'sage' ? 'text-[#303334]' : 'text-white' }}">{{ number_format($featured->price, 0) }} ج.م</span>
                        @endif
                    </div>

                    @php
                        $waText = urlencode("مرحباً، أرغب في تأكيد حجز مقعد في فعالية: " . $featured->title);
                        $regUrl = $featured->registration_url ?: "https://wa.me/201000000000?text={$waText}";
                    @endphp
                    <a href="{{ $regUrl }}" target="_blank"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition active:scale-95 flex items-center gap-1.5 shadow-sm {{ $featured->banner_theme === 'sage' ? 'bg-[#4E8F35] text-white hover:bg-[#3F742B]' : 'bg-white text-[#303334] hover:bg-slate-100' }}">
                        <span>حجز مقعد الآن</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- 3. Upcoming Events Stream -->
    <div>
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <span class="w-1 h-3.5 rounded-full bg-[#4E8F35]"></span>
                <h2 class="text-xs font-bold text-[#303334]">جدول الفعاليات وورش العمل</h2>
            </div>
            <span class="text-[10px] text-[#73777A]">{{ $upcomingEvents->count() }} فعاليات</span>
        </div>

        <!-- Filter Buttons (Client-side tabs) -->
        <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar pb-1 mb-3" id="categoryFilters">
            <button type="button" onclick="filterEvents('all')" class="event-filter-btn px-3 py-1.5 rounded-xl text-[11px] font-bold bg-[#4E8F35] text-white transition whitespace-nowrap active-filter" data-cat="all">
                الكل
            </button>
            <button type="button" onclick="filterEvents('ورشة عمل')" class="event-filter-btn px-3 py-1.5 rounded-xl text-[11px] font-medium bg-white border border-[#E5E2DC] text-[#73777A] hover:text-[#303334] transition whitespace-nowrap" data-cat="ورشة عمل">
                ورش عمل
            </button>
            <button type="button" onclick="filterEvents('ملتقى')" class="event-filter-btn px-3 py-1.5 rounded-xl text-[11px] font-medium bg-white border border-[#E5E2DC] text-[#73777A] hover:text-[#303334] transition whitespace-nowrap" data-cat="ملتقى">
                ملتقيات
            </button>
            <button type="button" onclick="filterEvents('ندوة')" class="event-filter-btn px-3 py-1.5 rounded-xl text-[11px] font-medium bg-white border border-[#E5E2DC] text-[#73777A] hover:text-[#303334] transition whitespace-nowrap" data-cat="ندوة">
                ندوات
            </button>
            <button type="button" onclick="filterEvents('استشارات')" class="event-filter-btn px-3 py-1.5 rounded-xl text-[11px] font-medium bg-white border border-[#E5E2DC] text-[#73777A] hover:text-[#303334] transition whitespace-nowrap" data-cat="استشارات">
                استشارات
            </button>
        </div>

        <!-- Events List -->
        <div class="space-y-3" id="eventsContainer">
            @forelse($upcomingEvents as $event)
                <div class="solid-card rounded-2xl p-4 transition hover:border-[#4E8F35]/40 event-item" data-category="{{ $event->category }}">
                    <div class="flex items-start gap-3">
                        <!-- Date Box -->
                        <div class="w-14 h-14 rounded-xl bg-[#F7FAF5] border border-[#DCE8D4] flex flex-col items-center justify-center text-center flex-shrink-0">
                            <span class="text-sm font-black text-[#4E8F35] leading-none">{{ \Carbon\Carbon::parse($event->event_date)->format('d') }}</span>
                            <span class="text-[10px] font-bold text-[#73777A] mt-0.5 leading-none">{{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('M') }}</span>
                        </div>

                        <!-- Info -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-1.5 mb-1 flex-wrap">
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-[#F5F3EE] text-[#73777A] border border-[#E5E2DC]">
                                    {{ $event->category }}
                                </span>
                                @if($event->badge_text)
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-[#EBF4E8] text-[#4E8F35]">
                                        {{ $event->badge_text }}
                                    </span>
                                @endif
                            </div>

                            <h3 class="text-xs font-bold text-[#303334] leading-snug">
                                {{ $event->title }}
                            </h3>

                            @if($event->speaker_name)
                                <div class="text-[11px] text-[#73777A] mt-1 flex items-center gap-1">
                                    <svg class="w-3 h-3 text-[#73777A]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <span>{{ $event->speaker_name }}</span>
                                </div>
                            @endif

                            <div class="text-[10px] text-[#73777A] mt-1 flex items-center gap-3">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                                    </svg>
                                    <span>{{ $event->time_text }}</span>
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><circle cx="12" cy="11" r="2"/>
                                    </svg>
                                    <span class="truncate">{{ $event->location }}</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Action Bar -->
                    <div class="mt-3 pt-2.5 border-t border-[#E5E2DC] flex items-center justify-between">
                        <span class="text-xs font-bold text-[#4E8F35]">
                            {{ $event->price == 0 ? 'دخول مجاني' : number_format($event->price, 0) . ' ج.م' }}
                        </span>

                        @php
                            $waEventText = urlencode("مرحباً، أرغب في التسجيل بفعالية: " . $event->title);
                            $linkUrl = $event->registration_url ?: "https://wa.me/201000000000?text={$waEventText}";
                        @endphp
                        <a href="{{ $linkUrl }}" target="_blank"
                            class="px-3 py-1.5 rounded-xl bg-[#F5F3EE] hover:bg-[#EBF4E8] text-[#303334] hover:text-[#4E8F35] border border-[#E5E2DC] text-[11px] font-bold transition active:scale-95 flex items-center gap-1">
                            <span>تفاصيل وحجز</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="solid-card rounded-2xl p-6 text-center text-[#73777A]">
                    <svg class="w-8 h-8 mx-auto mb-2 text-[#73777A]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <rect width="18" height="18" x="3" y="4" rx="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/>
                    </svg>
                    <p class="text-xs font-bold text-[#303334]">لا توجد فعاليات مجدولة حالياً</p>
                    <p class="text-[11px] text-[#73777A] mt-1">تابعنا باستمرار لتكون أول الحاضرين في اللقاءات القادمة.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- 4. Community Host Callout -->
    <div class="solid-card rounded-2xl p-4 border-dashed border-[#4E8F35]/40 bg-[#F7FAF5]">
        <div class="flex items-start gap-3">
            <div class="w-9 h-9 rounded-xl bg-[#EBF4E8] border border-[#DCE8D4] flex items-center justify-center text-[#4E8F35] flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 100-6 3 3 0 000 6z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-xs font-bold text-[#303334]">هل ترغب في تنظيم ورشة عمل أو تقديم محاضرة؟</h3>
                <p class="text-[11px] text-[#73777A] mt-0.5 leading-relaxed">
                    مساحة DDT ترحب بالمدربين والخبراء لمشاركة المعرفة مع مجتمعنا. تواصل معنا لتنسيق فعاليتك.
                </p>
                <a href="https://wa.me/201000000000?text=أرغب+في+اقتراح+ورشة+عمل+أو+فعالية+في+DDT" target="_blank"
                    class="inline-flex items-center gap-1 mt-2 text-[11px] font-bold text-[#4E8F35] hover:underline">
                    <span>تواصل مع منسق المجتمع عبر واتساب</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    function filterEvents(category) {
        const items = document.querySelectorAll('.event-item');
        const buttons = document.querySelectorAll('.event-filter-btn');

        buttons.forEach(btn => {
            if (btn.getAttribute('data-cat') === category) {
                btn.classList.remove('bg-white', 'border', 'border-[#E5E2DC]', 'text-[#73777A]');
                btn.classList.add('bg-[#4E8F35]', 'text-white');
            } else {
                btn.classList.remove('bg-[#4E8F35]', 'text-white');
                btn.classList.add('bg-white', 'border', 'border-[#E5E2DC]', 'text-[#73777A]');
            }
        });

        items.forEach(item => {
            if (category === 'all' || item.getAttribute('data-category') === category) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }
</script>
@endsection
