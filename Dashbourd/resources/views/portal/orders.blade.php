@extends('portal.layout')

@section('title', 'متابعة طلباتي — DDT WORKING SPACE')

@section('content')

    <!-- Header (Solid Light with Action Pill) -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-sm font-extrabold text-[#303334]">متابعة طلبات المشروبات</h1>
            <p class="text-[11px] text-[#738276]">تابع حالة إعداد قهوتك ومشروباتك لحظة بلحظة</p>
        </div>
        <a href="{{ route('portal.menu') }}" class="pill-btn-forest px-4 py-2 text-xs flex items-center gap-1.5 shadow-sm">
            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            <span>طلب جديد</span>
        </a>
    </div>

    <!-- 1. Active / In-progress Orders Section -->
    <div class="mt-1">
        <div class="flex items-center justify-between mb-2 px-1">
            <h2 class="text-xs font-bold text-[#4E8F35] flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-[#4E8F35]"></span>
                <span>الطلبات الجارية ({{ $activeOrders->count() }})</span>
            </h2>
        </div>

        @if($activeOrders->isEmpty())
            <!-- Minimalist Empty State (Strictly matching Reference Screen 3 "No players available") -->
            <div class="solid-card rounded-[28px] p-8 text-center flex flex-col items-center justify-center my-2">
                <!-- Ink-style Minimalist Line Art Vector (Golf Bag style in image, coffee/cup bag in DDT) -->
                <div class="w-28 h-28 my-2 text-[#4E8F35] flex items-center justify-center">
                    <svg class="w-full h-full" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <!-- Intricate ink-line style coffee cup & takeaway bag -->
                        <rect x="26" y="32" width="48" height="52" rx="8"/>
                        <!-- Bag fold lines -->
                        <path d="M26 32l10 -12h28l10 12"/>
                        <path d="M42 20v-5a4 4 0 014-4h8a4 4 0 014 4v5"/>
                        <!-- Ink sketch shading lines -->
                        <line x1="36" y1="44" x2="64" y2="44"/>
                        <line x1="38" y1="52" x2="62" y2="52"/>
                        <circle cx="50" cy="65" r="7"/>
                        <path d="M47 65h6m-3-3v6"/>
                    </svg>
                </div>

                <!-- Title (Matching Screen 3 "No players available") -->
                <h3 class="text-base font-extrabold text-[#303334] mt-2">
                    لا توجد طلبات جارية حالياً
                </h3>

                <!-- Subtitle (Matching Screen 3 description) -->
                <p class="text-xs text-[#738276] mt-1.5 max-w-[280px] leading-relaxed">
                    لم تقم بطلب أي مشروبات بعد. يمكنك تصفح قائمة الكافيه وطلب مشروبك ليصلك فوراً إلى طاولتك.
                </p>

                <!-- Buttons Stack (Matching Screen 3 button layout) -->
                <div class="w-full space-y-2.5 mt-6">
                    <!-- Outline Pill Button: "Update my preferences" -->
                    <a href="{{ route('portal.profile') }}" class="w-full py-3 px-4 pill-btn-outline text-xs font-bold flex items-center justify-center gap-2">
                        <svg class="w-3.5 h-3.5 text-[#4E8F35]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                        </svg>
                        <span>تعديل تفضيلات المشروبات</span>
                    </a>

                    <!-- Solid Forest Green Pill Button: "Try again" -->
                    <a href="{{ route('portal.menu') }}" class="w-full py-3.5 px-4 pill-btn-forest text-xs font-extrabold flex items-center justify-center gap-2 shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span>فتح المنيو والطلب الآن</span>
                    </a>
                </div>
            </div>
        @else
            <!-- Active Orders List (Crisp Solid Cards) -->
            <div class="space-y-3">
                @foreach($activeOrders as $order)
                    <div class="solid-card rounded-[26px] p-5 relative overflow-hidden border-r-4 border-r-[#4E8F35]">
                        <div class="flex items-start justify-between pb-3.5 border-b border-[#E5E2DC]">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-mono font-bold text-[#4E8F35] bg-[#EBF4E8] px-2.5 py-0.5 rounded-full">{{ $order->order_number }}</span>
                                    <span class="text-[11px] text-[#738276]">{{ $order->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs font-bold text-[#303334] mt-1.5">
                                    التسليم إلى: <span class="text-[#4E8F35] font-extrabold">{{ $order->table_or_room_name ?: 'المساحة المشتركة' }}</span>
                                </p>
                            </div>

                            <!-- Status Badge -->
                            @if($order->fulfillment_status === 'pending')
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                    قيد الانتظار
                                </span>
                            @elseif($order->fulfillment_status === 'preparing')
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-[#EBF4E8] text-[#4E8F35] border border-[#DCE8D4]">
                                    جاري التحضير
                                </span>
                            @endif
                        </div>

                        <!-- Items List -->
                        <div class="py-3 space-y-1.5 border-b border-[#E5E2DC]">
                            @foreach($order->items as $it)
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-[#738276]">{{ $it->name }} <strong class="text-[#303334]">× {{ $it->quantity }}</strong></span>
                                    <span class="font-mono font-bold text-[#303334]">{{ number_format($it->total, 2) }} ج.م</span>
                                </div>
                            @endforeach
                        </div>

                        @if(!empty($order->customer_notes))
                            <div class="mt-2.5 p-2.5 rounded-2xl bg-[#F5F3EE] text-[11px] text-[#738276] border border-[#E5E2DC]">
                                <strong class="text-[#303334]">ملاحظاتك:</strong> {{ $order->customer_notes }}
                            </div>
                        @endif

                        <div class="pt-3 flex items-center justify-between text-xs">
                            <span class="text-[#738276] font-bold">المجموع الإجمالي:</span>
                            <span class="text-sm font-black text-[#4E8F35] font-mono">{{ number_format($order->total, 2) }} ج.م</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- 2. Past Orders History -->
    <div class="mt-5">
        <h2 class="text-xs font-bold text-[#738276] mb-2.5 px-1">سجل الطلبات السابقة</h2>
        @if($pastOrders->isEmpty())
            <div class="solid-card rounded-[22px] p-4 text-center text-[#738276] text-xs">
                لا توجد طلبات سابقة مسجلة.
            </div>
        @else
            <div class="space-y-2">
                @foreach($pastOrders as $order)
                    <div class="solid-card rounded-[20px] p-3.5 flex items-center justify-between text-xs">
                        <div>
                            <div class="font-bold text-[#303334]">
                                {{ $order->items->pluck('name')->join('، ') ?: 'طلب مشروبات' }}
                            </div>
                            <div class="text-[10px] text-[#738276] mt-0.5">
                                {{ $order->created_at->format('Y-m-d h:i A') }} • {{ $order->table_or_room_name ?: 'المساحة' }}
                            </div>
                        </div>
                        <div class="text-left">
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $order->fulfillment_status === 'delivered' ? 'bg-[#EBF4E8] text-[#4E8F35]' : 'bg-rose-50 text-rose-700' }}">
                                {{ $order->fulfillment_status === 'delivered' ? 'تم التسليم' : 'ملغي' }}
                            </span>
                            <div class="text-[11px] font-mono font-bold text-[#303334] mt-0.5">
                                {{ number_format($order->total, 2) }} ج.م
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-3">
                {{ $pastOrders->links() }}
            </div>
        @endif
    </div>

@endsection
