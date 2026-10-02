@extends('portal.layout')

@section('title', 'قائمة المشروبات — DDT WORKING SPACE')

@php
    function getCategorySvg($code, $name) {
        $code = strtolower($code ?? '');
        $name = mb_strtolower($name ?? '');
        if (str_contains($code, 'hot') || str_contains($name, 'ساخن') || str_contains($name, 'قهوة') || str_contains($name, 'شاي')) {
            return '<svg class="w-4 h-4 text-[#4E8F35]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3"/></svg>';
        }
        if (str_contains($code, 'cold') || str_contains($name, 'بارد') || str_contains($name, 'عصير') || str_contains($name, 'مياه')) {
            return '<svg class="w-4 h-4 text-[#4E8F35]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>';
        }
        if (str_contains($code, 'snack') || str_contains($name, 'سناك') || str_contains($name, 'أكل') || str_contains($name, 'حلو')) {
            return '<svg class="w-4 h-4 text-[#4E8F35]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>';
        }
        return '<svg class="w-4 h-4 text-[#4E8F35]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="m4.93 4.93 4.24 4.24"/><path d="m14.83 9.17 4.24-4.24"/></svg>';
    }
@endphp

@section('content')

    <!-- Header & Room Location Indicator (Solid White Card) -->
    <div class="solid-card rounded-[24px] p-4.5">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-sm font-extrabold text-[#303334] flex items-center gap-2">
                    <span>قائمة المشروبات — DDT Cafe</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#EBF4E8] text-[#4E8F35]">
                        طلب مباشر
                    </span>
                </h1>
                <p class="text-[11px] text-[#738276] mt-0.5">اطلب مشروبك وسيصلك فوراً إلى مكان جلوسك في DDT</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-[#EBF4E8] text-[#4E8F35] flex items-center justify-center p-2.5 flex-shrink-0">
                <svg class="w-full h-full" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3"/></svg>
            </div>
        </div>

        <!-- Location Selector -->
        <div class="mt-3.5 pt-3 border-t border-[#E5E2DC]">
            <label class="text-[11px] font-bold text-[#303334] mb-1.5 flex items-center justify-between">
                <span class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-[#4E8F35]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    مكان تسليم الطلب:
                </span>
                @if($activeDeal && $activeDeal->room)
                    <span class="text-[#4E8F35] text-[10px] font-bold flex items-center gap-1 bg-[#EBF4E8] px-2.5 py-0.5 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#4E8F35]"></span>
                        مرتبط بجلستك الحالية
                    </span>
                @endif
            </label>
            <div class="flex gap-2">
                <select id="order-room-select" class="flex-1 bg-[#F5F3EE] border border-[#E5E2DC] rounded-full px-3 py-2 text-xs text-[#303334] focus:outline-none focus:border-[#4E8F35] font-bold">
                    @if($activeDeal && $activeDeal->room)
                        <option value="{{ $activeDeal->room->id }}" selected>{{ $activeDeal->room->name }} (مكانك الحالي)</option>
                    @endif
                    @foreach($rooms as $room)
                        @if(!$activeDeal || $activeDeal->room_id != $room->id)
                            <option value="{{ $room->id }}">{{ $room->name }}</option>
                        @endif
                    @endforeach
                    <option value="">مكان آخر (يدوي)</option>
                </select>
                <input type="text" id="order-table-input" placeholder="رقم الطاولة أو المكان" 
                    value="{{ $activeDeal && $activeDeal->room ? $activeDeal->room->name : '' }}"
                    class="w-1/2 bg-[#F5F3EE] border border-[#E5E2DC] rounded-full px-3 py-2 text-xs text-[#303334] placeholder-[#738276] focus:outline-none focus:border-[#4E8F35]">
            </div>
        </div>
    </div>

    <!-- Search, Filter & Categories Navigation Section -->
    <div class="space-y-2.5 sticky top-[57px] z-30 pt-1 pb-2 bg-[#F5F3EE]">
        
        <!-- Search bar & Quick Sort -->
        <div class="flex items-center gap-2">
            <div class="relative flex-1">
                <input type="text" id="menu-search-input" placeholder="ابحث باسم المشروب أو الصنف..." 
                    class="w-full bg-white border border-[#E5E2DC] rounded-full pr-9 pl-8 py-2 text-xs text-[#303334] placeholder-[#738276] focus:outline-none focus:border-[#4E8F35] shadow-xs">
                <svg class="w-4 h-4 text-[#738276] absolute right-3 top-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <button type="button" id="clear-search-btn" onclick="clearSearch()" class="hidden absolute left-2.5 top-2 text-[#738276] hover:text-[#303334] p-0.5 rounded-full transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Sort dropdown -->
            <div class="relative flex-shrink-0">
                <select id="menu-sort-select" onchange="applyFilters()" class="bg-white border border-[#E5E2DC] rounded-full px-3 py-2 text-xs text-[#303334] font-bold focus:outline-none focus:border-[#4E8F35] shadow-xs">
                    <option value="default">الترتيب</option>
                    <option value="price_asc">الأقل سعراً</option>
                    <option value="price_desc">الأعلى سعراً</option>
                    <option value="name_asc">الاسم (أ-ي)</option>
                </select>
            </div>
        </div>

        <!-- Category Pills Navigation (Capsule Pill Row) -->
        <div class="flex gap-1.5 overflow-x-auto pb-1 no-scrollbar text-xs scroll-smooth" id="categories-container">
            <!-- 'All' Tab -->
            <button type="button" onclick="selectCategory('all', this)" 
                data-cat="all"
                class="cat-pill active flex items-center gap-1.5 px-3.5 py-1.5 rounded-full font-bold whitespace-nowrap transition-all shadow-xs bg-[#4E8F35] text-white">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/>
                </svg>
                <span>الكل</span>
                <span class="px-1.5 py-0.2 rounded-full bg-white/20 text-white font-mono text-[10px] font-bold">{{ $allProducts->count() }}</span>
            </button>

            @foreach($categories as $cat)
                @php
                    $catSvg = getCategorySvg($cat->code, $cat->name);
                    $pCount = $cat->products->count();
                @endphp
                <button type="button" onclick="selectCategory('cat-{{ $cat->id }}', this)" 
                    data-cat="cat-{{ $cat->id }}"
                    class="cat-pill flex items-center gap-1.5 px-3.5 py-1.5 rounded-full font-medium whitespace-nowrap transition-all bg-white text-[#738276] border border-[#E5E2DC] hover:text-[#303334]">
                    <span>{!! $catSvg !!}</span>
                    <span>{{ $cat->name }}</span>
                    <span class="cat-count-badge px-1.5 py-0.2 rounded-full bg-[#F5F3EE] text-[#738276] font-mono text-[10px] font-bold">{{ $pCount }}</span>
                </button>
            @endforeach
        </div>

        <!-- Live Filter Counter Bar -->
        <div class="flex items-center justify-between text-[11px] text-[#738276] px-1">
            <div id="filter-status-text">
                عرض <strong class="text-[#4E8F35] font-mono font-bold" id="visible-count">{{ $allProducts->count() }}</strong> صنف متاح
            </div>
            <button type="button" id="reset-filter-btn" onclick="resetAllFilters()" class="hidden text-rose-700 hover:text-rose-800 font-bold transition flex items-center gap-1 text-[10px]">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>إلغاء الفلترة</span>
            </button>
        </div>

    </div>

    <!-- Products List / Grid (Solid Light Cards) -->
    <div class="space-y-2.5 pb-24" id="products-list">
        @forelse($allProducts as $product)
            @php
                $itemSvg = $product->category ? getCategorySvg($product->category->code, $product->category->name) : '';
                $categoryName = $product->category ? $product->category->name : 'أخرى';
                $catId = $product->category_id ? 'cat-' . $product->category_id : 'cat-none';
            @endphp
            <div class="product-card solid-card rounded-[22px] p-3.5 flex items-center justify-between transition-all duration-200 hover:border-[#4E8F35]/50"
                data-id="{{ $product->id }}"
                data-category="{{ $catId }}"
                data-category-name="{{ $categoryName }}"
                data-name="{{ mb_strtolower($product->name) }}"
                data-desc="{{ mb_strtolower($product->description ?? '') }}"
                data-price="{{ (float) $product->selling_price }}"
                id="product-row-{{ $product->id }}">
                
                <div class="flex items-center gap-3 flex-1 min-w-0">
                    <div class="w-11 h-11 rounded-2xl bg-[#F5F3EE] border border-[#E5E2DC] flex items-center justify-center flex-shrink-0 text-[#4E8F35]">
                        {!! $itemSvg !!}
                    </div>
                    <div class="min-w-0 flex-1 pr-1">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <h2 class="text-xs font-bold text-[#303334] truncate">{{ $product->name }}</h2>
                            <button type="button" onclick="quickFilterCategory('{{ $catId }}')" 
                                class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-[#F5F3EE] text-[#738276] hover:text-[#4E8F35] transition">
                                {{ $categoryName }}
                            </button>
                        </div>
                        @if($product->description)
                            <p class="text-[10px] text-[#738276] mt-0.5 line-clamp-1">{{ $product->description }}</p>
                        @endif
                        <div class="text-xs font-black text-[#4E8F35] font-mono mt-1">
                            {{ number_format($product->selling_price, 2) }} <span class="text-[10px] text-[#738276] font-normal">ج.م</span>
                        </div>
                    </div>
                </div>

                <!-- Add / Quantity Controls (Pill Stepper) -->
                <div class="flex items-center gap-2 flex-shrink-0">
                    <div class="flex items-center bg-[#F5F3EE] border border-[#E5E2DC] rounded-full p-0.5" id="stepper-box-{{ $product->id }}">
                        <button type="button" onclick="changeQty({{ $product->id }}, -1, '{{ addslashes($product->name) }}', {{ $product->selling_price }})"
                            class="w-7 h-7 rounded-full bg-white hover:bg-[#E5E2DC] active:scale-90 text-[#303334] font-bold text-xs flex items-center justify-center transition shadow-xs">
                            -
                        </button>
                        <span id="qty-{{ $product->id }}" class="w-7 text-center text-xs font-bold font-mono text-[#303334] select-none">0</span>
                        <button type="button" onclick="changeQty({{ $product->id }}, 1, '{{ addslashes($product->name) }}', {{ $product->selling_price }})"
                            class="w-7 h-7 rounded-full bg-[#4E8F35] hover:bg-[#0F2B1E] active:scale-90 text-white font-bold text-xs flex items-center justify-center transition shadow-xs">
                            +
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-16 text-[#738276] text-xs solid-card rounded-[24px] p-6">
                لا توجد منتجات متاحة حالياً في المنيو.
            </div>
        @endforelse
    </div>

    <!-- Empty Filter State (Hidden by default) -->
    <div id="empty-filter-state" class="hidden text-center py-12 px-4 solid-card rounded-[24px]">
        <h2 class="text-sm font-bold text-[#303334]">لم يتم العثور على أي صنف</h2>
        <p class="text-xs text-[#738276] mt-1 max-w-xs mx-auto">لم نجد أصناف تطابق بحثك أو القسم المحدد. جرّب كلمات أخرى أو قم بإلغاء الفلترة.</p>
        <button type="button" onclick="resetAllFilters()" class="mt-3 px-5 py-2.5 pill-btn-forest text-xs shadow-sm">
            إظهار كافة الأصناف
        </button>
    </div>

    <!-- Floating Bottom Cart Bar (Solid Pill Island) -->
    <div id="floating-cart" class="fixed bottom-20 left-0 right-0 z-40 flex justify-center px-4 transition-all duration-300 transform translate-y-24 opacity-0 pointer-events-none">
        <div class="w-full max-w-sm pointer-events-auto bg-white border border-[#E5E2DC] rounded-full p-2.5 shadow-[0_8px_30px_rgba(20,56,40,0.12)] flex items-center justify-between">
            <div class="flex items-center gap-3 pr-2">
                <div class="w-9 h-9 rounded-full bg-[#4E8F35] text-white flex items-center justify-center font-bold text-xs shadow-sm">
                    <span id="cart-total-count">0</span>
                </div>
                <div>
                    <div class="text-[10px] font-bold text-[#738276]">إجمالي الطلب</div>
                    <div class="text-xs font-black text-[#4E8F35] font-mono" id="cart-total-price">0.00 ج.م</div>
                </div>
            </div>

            <button type="button" onclick="openOrderModal()" class="pill-btn-forest px-4 py-2 text-xs flex items-center gap-1.5 shadow-sm">
                <span>تأكيد الطلب</span>
                <svg class="w-3.5 h-3.5 rotate-180" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </div>
    </div>

    <!-- Order Confirmation Modal (Solid Bottom Sheet with Drag Handle) -->
    <div id="order-modal" class="fixed inset-0 z-50 bg-black/40 hidden flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="w-full max-w-md bg-white rounded-t-[32px] sm:rounded-[32px] border border-[#E5E2DC] p-6 max-h-[90vh] flex flex-col shadow-2xl">
            <!-- Handle -->
            <div class="w-10 h-1.5 rounded-full bg-[#E5E2DC] mx-auto mb-3"></div>

            <div class="flex items-center justify-between pb-3 border-b border-[#E5E2DC]">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#4E8F35]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3"/></svg>
                    <h2 class="text-sm font-bold text-[#303334]">تأكيد طلب المشروبات</h2>
                </div>
                <button type="button" onclick="closeOrderModal()" class="text-[#738276] hover:text-[#303334] p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Selected Items Review List -->
            <div id="modal-items-list" class="my-3 space-y-2 max-h-48 overflow-y-auto no-scrollbar">
                <!-- Populated via JS -->
            </div>

            <!-- Custom Notes per order -->
            <div class="mb-3">
                <label class="block text-[11px] font-bold text-[#303334] mb-1">ملاحظات التحضير (اختياري)</label>
                <input type="text" id="order-notes-input" placeholder="مثلاً: قهوة مظبوط، سكر زيادة، بارد بدون ثلج..."
                    class="w-full bg-[#F5F3EE] border border-[#E5E2DC] rounded-full px-4 py-2 text-xs text-[#303334] placeholder-[#738276] focus:outline-none focus:border-[#4E8F35]">
            </div>

            <!-- Location summary -->
            <div class="p-3 rounded-2xl bg-[#F5F3EE] border border-[#E5E2DC] text-[11px] text-[#303334] flex items-center justify-between mb-4">
                <span class="text-[#738276]">التوصيل إلى:</span>
                <strong class="text-[#4E8F35] font-bold" id="modal-delivery-location">المساحة العامة</strong>
            </div>

            <!-- Total and Submit button -->
            <div class="pt-3 border-t border-[#E5E2DC] flex items-center justify-between">
                <div>
                    <span class="text-[10px] text-[#738276] block">المبلغ الإجمالي</span>
                    <span class="text-base font-black text-[#4E8F35] font-mono" id="modal-total-price">0.00 ج.م</span>
                </div>
                <button type="button" id="submit-order-btn" onclick="submitFinalOrder()" class="pill-btn-forest px-6 py-2.5 text-xs flex items-center gap-2 shadow-sm">
                    <span>إرسال الطلب للكاشير</span>
                </button>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    let cart = {}; // { productId: { name, price, qty } }
    let currentCategory = 'all';

    function changeQty(id, delta, name, price) {
        if (!cart[id]) {
            cart[id] = { name: name, price: price, qty: 0 };
        }
        cart[id].qty += delta;

        const qtySpan = document.getElementById(`qty-${id}`);
        const card = document.getElementById(`product-row-${id}`);

        if (cart[id].qty <= 0) {
            delete cart[id];
            if (qtySpan) qtySpan.textContent = '0';
            if (card) card.classList.remove('border-[#4E8F35]', 'bg-[#EBF4E8]');
        } else {
            if (qtySpan) qtySpan.textContent = cart[id].qty;
            if (card) card.classList.add('border-[#4E8F35]', 'bg-[#EBF4E8]');
        }
        updateCartUI();
    }

    function updateCartUI() {
        let totalCount = 0;
        let totalPrice = 0;
        for (let id in cart) {
            totalCount += cart[id].qty;
            totalPrice += cart[id].qty * cart[id].price;
        }

        const floating = document.getElementById('floating-cart');
        if (totalCount > 0) {
            floating.classList.remove('translate-y-24', 'opacity-0', 'pointer-events-none');
            document.getElementById('cart-total-count').textContent = totalCount;
            document.getElementById('cart-total-price').textContent = totalPrice.toFixed(2) + ' ج.م';
        } else {
            floating.classList.add('translate-y-24', 'opacity-0', 'pointer-events-none');
        }
    }

    function selectCategory(catKey, btn) {
        currentCategory = catKey;

        document.querySelectorAll('.cat-pill').forEach(b => {
            b.classList.remove('active', 'bg-[#4E8F35]', 'text-white', 'shadow-xs');
            b.classList.add('bg-white', 'text-[#738276]', 'border', 'border-[#E5E2DC]');
            
            const badge = b.querySelector('.cat-count-badge');
            if (badge) {
                badge.classList.remove('bg-white/20', 'text-white');
                badge.classList.add('bg-[#F5F3EE]', 'text-[#738276]');
            }
        });

        btn.classList.add('active', 'bg-[#4E8F35]', 'text-white', 'shadow-xs');
        btn.classList.remove('bg-white', 'text-[#738276]', 'border', 'border-[#E5E2DC]');
        
        const activeBadge = btn.querySelector('.cat-count-badge');
        if (activeBadge) {
            activeBadge.classList.add('bg-white/20', 'text-white');
            activeBadge.classList.remove('bg-[#F5F3EE]', 'text-[#738276]');
        }

        btn.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        applyFilters();
    }

    function quickFilterCategory(catKey) {
        const targetBtn = document.querySelector(`.cat-pill[data-cat="${catKey}"]`);
        if (targetBtn) {
            selectCategory(catKey, targetBtn);
        }
    }

    function applyFilters() {
        const searchInput = document.getElementById('menu-search-input');
        const q = searchInput.value.toLowerCase().trim();
        const sortOrder = document.getElementById('menu-sort-select').value;
        const clearBtn = document.getElementById('clear-search-btn');
        const resetBtn = document.getElementById('reset-filter-btn');
        const emptyState = document.getElementById('empty-filter-state');
        const productsContainer = document.getElementById('products-list');

        if (q.length > 0) {
            clearBtn.classList.remove('hidden');
        } else {
            clearBtn.classList.add('hidden');
        }

        if (q.length > 0 || currentCategory !== 'all') {
            resetBtn.classList.remove('hidden');
        } else {
            resetBtn.classList.add('hidden');
        }

        let visibleCount = 0;
        const cards = Array.from(document.querySelectorAll('.product-card'));

        cards.forEach(card => {
            const cardCat = card.dataset.category;
            const name = card.dataset.name || '';
            const desc = card.dataset.desc || '';

            const matchesCategory = (currentCategory === 'all' || cardCat === currentCategory);
            const matchesSearch = (!q || name.includes(q) || desc.includes(q));

            if (matchesCategory && matchesSearch) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });

        if (sortOrder !== 'default') {
            cards.sort((a, b) => {
                if (sortOrder === 'price_asc') {
                    return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
                } else if (sortOrder === 'price_desc') {
                    return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
                } else if (sortOrder === 'name_asc') {
                    return a.dataset.name.localeCompare(b.dataset.name, 'ar');
                }
                return 0;
            });
            cards.forEach(card => productsContainer.appendChild(card));
        }

        document.getElementById('visible-count').textContent = visibleCount;

        if (visibleCount === 0) {
            emptyState.classList.remove('hidden');
        } else {
            emptyState.classList.add('hidden');
        }
    }

    function clearSearch() {
        const searchInput = document.getElementById('menu-search-input');
        searchInput.value = '';
        applyFilters();
        searchInput.focus();
    }

    function resetAllFilters() {
        document.getElementById('menu-search-input').value = '';
        document.getElementById('menu-sort-select').value = 'default';
        const allBtn = document.querySelector('.cat-pill[data-cat="all"]');
        if (allBtn) {
            selectCategory('all', allBtn);
        } else {
            applyFilters();
        }
    }

    document.getElementById('menu-search-input').addEventListener('input', applyFilters);

    function openOrderModal() {
        const list = document.getElementById('modal-items-list');
        list.innerHTML = '';
        let totalPrice = 0;

        for (let id in cart) {
            const item = cart[id];
            const itemTotal = item.qty * item.price;
            totalPrice += itemTotal;

            const row = document.createElement('div');
            row.className = 'p-3 rounded-2xl bg-[#F5F3EE] border border-[#E5E2DC] flex items-center justify-between text-xs';
            row.innerHTML = `
                <div>
                    <div class="font-bold text-[#303334]">${item.name}</div>
                    <div class="text-[10px] text-[#738276] font-mono">${item.price.toFixed(2)} ج.م × ${item.qty}</div>
                </div>
                <span class="font-bold text-[#4E8F35] font-mono">${itemTotal.toFixed(2)} ج.م</span>
            `;
            list.appendChild(row);
        }

        document.getElementById('modal-total-price').textContent = totalPrice.toFixed(2) + ' ج.م';
        
        const roomSelect = document.getElementById('order-room-select');
        const roomText = roomSelect.options[roomSelect.selectedIndex] ? roomSelect.options[roomSelect.selectedIndex].text : '';
        const tableInput = document.getElementById('order-table-input').value.trim();
        document.getElementById('modal-delivery-location').textContent = tableInput || roomText || 'المساحة العامة';

        document.getElementById('order-modal').classList.remove('hidden');
    }

    function closeOrderModal() {
        document.getElementById('order-modal').classList.add('hidden');
    }

    async function submitFinalOrder() {
        const btn = document.getElementById('submit-order-btn');
        btn.disabled = true;
        btn.innerHTML = `<span>جاري الإرسال...</span>`;

        const itemsPayload = [];
        for (let id in cart) {
            itemsPayload.push({
                product_id: id,
                quantity: cart[id].qty
            });
        }

        const roomId = document.getElementById('order-room-select').value;
        const locationName = document.getElementById('order-table-input').value.trim() || 
            (document.getElementById('order-room-select').options[document.getElementById('order-room-select').selectedIndex]?.text || '');
        const notes = document.getElementById('order-notes-input').value.trim();

        try {
            const response = await fetch("{{ route('portal.orders.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    items: itemsPayload,
                    room_id: roomId || null,
                    table_or_room_name: locationName,
                    customer_notes: notes
                })
            });

            const data = await response.json();
            if (data.success) {
                window.location.href = "{{ route('portal.orders') }}";
            } else {
                alert(data.message || 'حدث خطأ أثناء إرسال الطلب');
                btn.disabled = false;
                btn.innerHTML = `<span>إرسال الطلب للكاشير</span>`;
            }
        } catch (e) {
            alert('تعذر الاتصال بالخادم.');
            btn.disabled = false;
            btn.innerHTML = `<span>إرسال الطلب للكاشير</span>`;
        }
    }
</script>
@endsection
