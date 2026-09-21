@extends('portal.layout')

@section('title', 'فعاليات وورش عمل DDT — مجتمع بيكبر معاك')

@section('content')

    @php
        $bannerImg = \App\Models\Setting::get('app_banner_image');
        $bannerUrl = \App\Models\Setting::get('app_banner_url');
        $bannerSrc = null;
        if (!empty($bannerImg)) {
            $bannerSrc = asset('storage/' . $bannerImg);
        } elseif (!empty($bannerUrl)) {
            $bannerSrc = $bannerUrl;
        }
        $bannerTitle = \App\Models\Setting::get('app_banner_title', 'فعاليات وورش عمل DDT');
        $bannerSubtitle = \App\Models\Setting::get('app_banner_subtitle', 'أكثر من مكان.. مجتمع بيكبر معاك • ورش عمل، لقاءات، وجلسات تشبيك');
        $bannerLink = \App\Models\Setting::get('app_banner_link');
    @endphp

    <!-- 1. Header & Dynamic Banner (Configured from Settings / الإعدادات) -->
    @if($bannerSrc)
        <div class="solid-card rounded-[26px] overflow-hidden border border-[#E5E2DC] shadow-sm relative group">
            @if($bannerLink)
                <a href="{{ $bannerLink }}" target="_blank" class="block relative group-hover:opacity-95 transition">
            @else
                <div class="relative">
            @endif
                <div class="relative h-44 w-full overflow-hidden bg-[#303334]">
                    <img src="{{ $bannerSrc }}" alt="{{ $bannerTitle }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500"/>
                    <div class="absolute inset-0 bg-gradient-to-t from-[#303334] via-[#303334]/50 to-transparent"></div>
                    
                    <!-- Top Badge -->
                    <div class="absolute top-3 right-3 flex items-center gap-1.5">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-[#4E8F35] text-white shadow-md flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-[#79B84A] animate-pulse"></span> DDT COMMUNITY
                        </span>
                    </div>

                    <!-- Bottom Text Info -->
                    <div class="absolute bottom-3 right-3 left-3 text-white">
                        <h1 class="text-base font-black leading-snug drop-shadow-sm">{{ $bannerTitle }}</h1>
                        @if($bannerSubtitle)
                            <p class="text-[11px] text-[#DCE8D4] mt-1 line-clamp-1 font-medium">{{ $bannerSubtitle }}</p>
                        @endif
                    </div>
                </div>
            @if($bannerLink)
                </a>
            @else
                </div>
            @endif

            <!-- Quick Stats & Action Chips -->
            <div class="flex items-center justify-between p-3.5 bg-white border-t border-[#E5E2DC] text-[11px]">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded-full bg-[#F5F3EE] text-[#303334] font-bold border border-[#E5E2DC]">
                        {{ $events->count() }} فعالية متاحة
                    </span>
                    <span class="px-2.5 py-1 rounded-full bg-[#EBF4E8] text-[#4E8F35] font-bold border border-[#DCE8D4]">
                        مجاناً لأعضاء DDT
                    </span>
                </div>
                @if($bannerLink)
                    <a href="{{ $bannerLink }}" target="_blank" class="text-xs font-black text-[#4E8F35] hover:underline flex items-center gap-1">
                        <span>انقر هنا للتفاصيل</span>
                        <svg class="w-3.5 h-3.5 rotate-180" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                @endif
            </div>
        </div>
    @else
        <div class="solid-card rounded-[26px] p-5 relative overflow-hidden border border-[#E5E2DC]">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#4E8F35] animate-pulse"></span>
                        <h1 class="text-base font-black text-[#303334]">{{ $bannerTitle }}</h1>
                    </div>
                    <p class="text-xs text-[#73777A] font-medium">
                        {{ $bannerSubtitle }}
                    </p>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-[#EBF4E8] text-[#4E8F35] flex items-center justify-center font-bold border border-[#DCE8D4] shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect width="18" height="18" x="3" y="4" rx="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/>
                    </svg>
                </div>
            </div>

            <!-- Quick Stats Chips -->
            <div class="flex items-center gap-2 mt-3 pt-3 border-t border-[#E5E2DC] text-[11px] text-[#73777A]">
                <span class="px-2.5 py-1 rounded-full bg-[#F5F3EE] text-[#303334] font-bold border border-[#E5E2DC]">
                    {{ $events->count() }} فعالية متاحة
                </span>
                <span class="px-2.5 py-1 rounded-full bg-[#EBF4E8] text-[#4E8F35] font-bold border border-[#DCE8D4]">
                    مجاناً لأعضاء DDT
                </span>
            </div>
        </div>
    @endif

    <!-- 2. Search & Category Filter Bar (Sticky) -->
    <div class="space-y-2.5 sticky top-[57px] z-30 pt-1 pb-2 bg-[#F5F3EE]">
        <!-- Search Input -->
        <div class="relative">
            <input type="text" id="event-search-input" placeholder="ابحث عن ورشة، عنوان، أو اسم المحاضر..." 
                class="w-full bg-white border border-[#E5E2DC] rounded-full pr-9 pl-9 py-2.5 text-xs text-[#303334] placeholder-[#73777A] focus:outline-none focus:border-[#4E8F35] shadow-xs font-medium">
            <svg class="w-4 h-4 text-[#738276] absolute right-3.5 top-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <button type="button" id="clear-event-search" onclick="clearSearch()" class="hidden absolute left-3 top-2.5 text-[#738276] hover:text-[#303334] p-0.5 rounded-full transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Categories Filter Pills -->
        <div class="flex gap-1.5 overflow-x-auto pb-1 no-scrollbar text-xs scroll-smooth">
            <button type="button" onclick="filterCategory('all', this)" class="cat-chip active px-3.5 py-1.5 rounded-full font-bold whitespace-nowrap transition-all shadow-xs bg-[#4E8F35] text-white">
                الكل ({{ $events->count() }})
            </button>
            <button type="button" onclick="filterCategory('ورشة عمل', this)" class="cat-chip px-3.5 py-1.5 rounded-full font-bold whitespace-nowrap transition-all bg-white text-[#738276] border border-[#E5E2DC]">
                ورش عمل
            </button>
            <button type="button" onclick="filterCategory('ملتقى', this)" class="cat-chip px-3.5 py-1.5 rounded-full font-bold whitespace-nowrap transition-all bg-white text-[#738276] border border-[#E5E2DC]">
                ملتقيات وتشبيك
            </button>
            <button type="button" onclick="filterCategory('ندوة', this)" class="cat-chip px-3.5 py-1.5 rounded-full font-bold whitespace-nowrap transition-all bg-white text-[#738276] border border-[#E5E2DC]">
                ندوات
            </button>
            <button type="button" onclick="filterCategory('استشارات', this)" class="cat-chip px-3.5 py-1.5 rounded-full font-bold whitespace-nowrap transition-all bg-white text-[#738276] border border-[#E5E2DC]">
                استشارات
            </button>
            <button type="button" onclick="filterCategory('فعالية عامة', this)" class="cat-chip px-3.5 py-1.5 rounded-full font-bold whitespace-nowrap transition-all bg-white text-[#738276] border border-[#E5E2DC]">
                فعاليات عامة
            </button>
        </div>
    </div>

    <!-- 3. Events Cards Feed -->
    <div class="space-y-3.5" id="events-feed">
        @forelse($events as $event)
            <div class="event-card solid-card rounded-[26px] overflow-hidden border border-[#E5E2DC] shadow-sm relative group transition duration-200"
                 data-category="{{ $event->category }}"
                 data-title="{{ strtolower($event->title) }}"
                 data-speaker="{{ strtolower($event->speaker_name ?? '') }}"
                 data-desc="{{ strtolower($event->description ?? '') }}">
                
                @if($event->image_url)
                    <!-- Cover Image Banner -->
                    <div class="relative h-44 w-full overflow-hidden bg-[#303334]">
                        <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500"/>
                        <div class="absolute inset-0 bg-gradient-to-t from-[#303334] via-[#303334]/50 to-transparent"></div>
                        
                        <!-- Top Badges -->
                        <div class="absolute top-3 right-3 flex items-center gap-1.5">
                            @if($event->is_featured)
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-amber-400 text-slate-900 shadow-md flex items-center gap-1">
                                    <span>★</span> مميز
                                </span>
                            @endif
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-white/95 backdrop-blur-sm text-[#4E8F35] shadow-sm">
                                {{ $event->category }}
                            </span>
                            @if($event->badge_text)
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-[#EBF4E8] text-[#4E8F35] border border-[#DCE8D4] shadow-sm">
                                    {{ $event->badge_text }}
                                </span>
                            @endif
                        </div>

                        <!-- Bottom Cover Overlay Text -->
                        <div class="absolute bottom-3 right-3 left-3 text-white">
                            <h3 class="text-sm font-black leading-snug drop-shadow-sm">{{ $event->title }}</h3>
                            <p class="text-[11px] text-[#DCE8D4] mt-1 flex items-center gap-2 font-medium">
                                <span>📅 {{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('l, d F') }}</span>
                                <span>⏰ {{ $event->time_text }}</span>
                            </p>
                        </div>
                    </div>
                @else
                    <!-- Fallback Solid Charcoal Header -->
                    <div class="p-5 bg-gradient-to-br from-[#303334] to-[#222425] text-white relative">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-1.5">
                                @if($event->is_featured)
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-400 text-slate-900 flex items-center gap-1">
                                        <span>★</span> مميز
                                    </span>
                                @endif
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-white/20 text-[#DCE8D4]">
                                    {{ $event->category }}
                                </span>
                            </div>
                            @if($event->badge_text)
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#4E8F35] text-white">
                                    {{ $event->badge_text }}
                                </span>
                            @endif
                        </div>

                        <h3 class="text-sm font-black leading-snug">{{ $event->title }}</h3>
                        <p class="text-[11px] text-[#DCE8D4] mt-1 flex items-center gap-2 font-medium">
                            <span>📅 {{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('l, d F') }}</span>
                            <span>⏰ {{ $event->time_text }}</span>
                        </p>
                    </div>
                @endif

                <!-- Card Body & Info -->
                <div class="p-4 bg-white space-y-3">
                    @if($event->speaker_name)
                        <div class="flex items-center gap-2 text-xs text-[#303334]">
                            <div class="w-7 h-7 rounded-full bg-[#EBF4E8] text-[#4E8F35] font-black flex items-center justify-center text-[10px] border border-[#DCE8D4] shrink-0">
                                {{ mb_substr($event->speaker_name, 0, 1) }}
                            </div>
                            <div>
                                <span class="font-bold">{{ $event->speaker_name }}</span>
                                @if($event->speaker_title)
                                    <span class="text-[10px] text-[#738276] block">{{ $event->speaker_title }}</span>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($event->description)
                        <p class="text-[11px] text-[#738276] leading-relaxed line-clamp-2 font-medium">
                            {{ $event->description }}
                        </p>
                    @endif

                    <div class="flex items-center gap-3 text-[10px] text-[#738276] pt-1">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-[#4E8F35]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><circle cx="12" cy="11" r="3"/></svg>
                            {{ $event->location }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-[#4E8F35]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            سعة {{ $event->capacity }} فرد
                        </span>
                    </div>

                    <!-- Action Row -->
                    <div class="flex items-center justify-between pt-3 border-t border-[#E5E2DC]">
                        <div>
                            <span class="text-[10px] text-[#738276] block">رسوم الحضور:</span>
                            <span class="text-xs font-black text-[#4E8F35]">
                                {{ $event->price == 0 ? 'مجاناً للأعضاء' : number_format($event->price, 0) . ' ج.م' }}
                            </span>
                        </div>

                        @php
                            $waText = urlencode("مرحباً، أرغب في تأكيد حجز مقعد في فعالية: " . $event->title);
                            $regUrl = $event->registration_url ?: "https://wa.me/201000000000?text={$waText}";
                        @endphp
                        <a href="{{ $regUrl }}" target="_blank" class="pill-btn-primary px-5 py-2 text-xs flex items-center gap-1.5 shadow-sm active:scale-95">
                            <span>حجز مقعد الآن</span>
                            <svg class="w-3.5 h-3.5 rotate-180" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="solid-card rounded-[26px] p-8 text-center text-xs text-[#738276] border border-[#E5E2DC]">
                لا توجد فعاليات مسجلة حالياً. ترقبوا فعالياتنا القادمة قريباً!
            </div>
        @endforelse
    </div>

    <!-- Empty Search State -->
    <div id="empty-events-state" class="hidden solid-card rounded-[26px] p-8 text-center border border-[#E5E2DC]">
        <div class="w-14 h-14 rounded-2xl bg-[#EBF4E8] text-[#4E8F35] flex items-center justify-center mx-auto mb-3">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
        </div>
        <h3 class="text-sm font-black text-[#303334]">لم يتم العثور على أي فعالية</h3>
        <p class="text-xs text-[#738276] mt-1">جرّب البحث بكلمات أخرى أو اختر قسماً آخر.</p>
        <button type="button" onclick="resetFilters()" class="mt-4 px-4 py-2 pill-btn-primary text-xs shadow-xs">
            إظهار كافة الفعاليات
        </button>
    </div>

@endsection

@section('scripts')
<script>
    let activeCategory = 'all';

    function filterCategory(cat, btn) {
        activeCategory = cat;
        document.querySelectorAll('.cat-chip').forEach(b => {
            b.classList.remove('bg-[#4E8F35]', 'text-white', 'shadow-xs');
            b.classList.add('bg-white', 'text-[#738276]', 'border', 'border-[#E5E2DC]');
        });

        btn.classList.add('bg-[#4E8F35]', 'text-white', 'shadow-xs');
        btn.classList.remove('bg-white', 'text-[#738276]', 'border', 'border-[#E5E2DC]');
        applyEventFilters();
    }

    function applyEventFilters() {
        const query = document.getElementById('event-search-input').value.toLowerCase().trim();
        const clearBtn = document.getElementById('clear-event-search');
        if (query.length > 0) {
            clearBtn.classList.remove('hidden');
        } else {
            clearBtn.classList.add('hidden');
        }

        const cards = document.querySelectorAll('.event-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const cat = card.dataset.category || '';
            const title = card.dataset.title || '';
            const speaker = card.dataset.speaker || '';
            const desc = card.dataset.desc || '';

            const matchCat = (activeCategory === 'all' || cat === activeCategory);
            const matchSearch = (!query || title.includes(query) || speaker.includes(query) || desc.includes(query));

            if (matchCat && matchSearch) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });

        const emptyState = document.getElementById('empty-events-state');
        if (visibleCount === 0 && cards.length > 0) {
            emptyState.classList.remove('hidden');
        } else {
            emptyState.classList.add('hidden');
        }
    }

    function clearSearch() {
        const input = document.getElementById('event-search-input');
        input.value = '';
        applyEventFilters();
        input.focus();
    }

    function resetFilters() {
        document.getElementById('event-search-input').value = '';
        const allBtn = document.querySelector('.cat-chip');
        if (allBtn) filterCategory('all', allBtn);
    }

    document.getElementById('event-search-input').addEventListener('input', applyEventFilters);
</script>
@endsection
