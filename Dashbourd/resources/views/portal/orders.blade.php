@extends('portal.layout')

@section('title', 'متابعة طلباتي — DDT WORKING SPACE')

@section('content')

    <!-- Header (Solid Light) -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-sm font-extrabold text-[#303334]">متابعة طلبات المشروبات</h1>
            <p class="text-[11px] text-[#73777A]">تابع حالة إعداد مشروباتك لحظة بلحظة</p>
        </div>
        <a href="{{ route('portal.menu') }}" class="px-3.5 py-1.5 rounded-xl bg-[#4E8F35] hover:bg-[#3F742B] text-white font-bold text-xs flex items-center gap-1 shadow-sm transition active:scale-95">
            <span>+ طلب جديد</span>
        </a>
    </div>

    <!-- 1. Active / In-progress Orders -->
    <div>
        <h2 class="text-xs font-bold text-[#4E8F35] mb-2 flex items-center gap-1.5">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#79B84A] opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-[#4E8F35]"></span>
            </span>
            <span>الطلبات الجارية ({{ $activeOrders->count() }})</span>
        </h2>

        @if($activeOrders->isEmpty())
            <div class="solid-card rounded-2xl p-6 text-center text-[#73777A]">
                <div class="w-10 h-10 mx-auto rounded-xl bg-[#F5F3EE] border border-[#E5E2DC] flex items-center justify-center text-[#4E8F35] mb-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3"/>
                    </svg>
                </div>
                <h3 class="text-xs font-bold text-[#303334]">لا توجد طلبات جارية حالياً</h3>
                <p class="text-[11px] text-[#73777A] mt-0.5">اطلب مشروبك وسيقوم باريستا DDT بتحضيره لك فوراً.</p>
                <a href="{{ route('portal.menu') }}" class="mt-3 inline-block px-4 py-2 rounded-xl bg-[#4E8F35] hover:bg-[#3F742B] text-white text-xs font-bold shadow-sm transition">
                    فتح المنيو والطلب
                </a>
            </div>
        @else
            <div class="space-y-3">
                @foreach($activeOrders as $order)
                    <div class="solid-card rounded-2xl p-4 border-r-4 border-r-[#4E8F35]">
                        <div class="flex items-start justify-between pb-3 border-b border-[#E5E2DC]">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-mono font-bold text-[#4E8F35]">{{ $order->order_number }}</span>
                                    <span class="text-[11px] text-[#73777A]">• {{ $order->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs font-bold text-[#303334] mt-1">
                                    التسليم إلى: <span class="text-[#4E8F35]">{{ $order->table_or_room_name ?: 'المساحة العامة' }}</span>
                                </p>
                            </div>

                            <!-- Status Badge -->
                            @if($order->fulfillment_status === 'pending')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    قيد الانتظار
                                </span>
                            @elseif($order->fulfillment_status === 'preparing')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-[#EBF4E8] text-[#4E8F35] border border-[#DCE8D4]">
                                    جاري التحضير
                                </span>
                            @endif
                        </div>

                        <!-- Items List -->
                        <div class="py-2.5 space-y-1.5 border-b border-[#E5E2DC]">
                            @foreach($order->items as $it)
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-[#73777A]">{{ $it->name }} <strong class="text-[#303334]">× {{ $it->quantity }}</strong></span>
                                    <span class="font-mono font-bold text-[#303334]">{{ number_format($it->total, 2) }} ج.م</span>
                                </div>
                            @endforeach
                        </div>

                        @if(!empty($order->customer_notes))
                            <div class="mt-2 p-2 rounded-xl bg-[#F5F3EE] text-[11px] text-[#73777A] border border-[#E5E2DC]">
                                <strong>ملاحظاتك:</strong> {{ $order->customer_notes }}
                            </div>
                        @endif

                        <div class="pt-2.5 flex items-center justify-between text-xs">
                            <span class="text-[#73777A] font-bold">الإجمالي:</span>
                            <span class="text-sm font-black text-[#4E8F35] font-mono">{{ number_format($order->total, 2) }} ج.م</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- 2. Past Orders History -->
    <div class="mt-4">
        <h2 class="text-xs font-bold text-[#73777A] mb-2">سجل الطلبات السابقة</h2>
        @if($pastOrders->isEmpty())
            <div class="solid-card rounded-xl p-4 text-center text-[#73777A] text-xs">
                لا توجد طلبات سابقة.
            </div>
        @else
            <div class="space-y-2">
                @foreach($pastOrders as $order)
                    <div class="solid-card rounded-xl p-3 flex items-center justify-between text-xs">
                        <div>
                            <div class="font-bold text-[#303334]">
                                {{ $order->items->pluck('name')->join('، ') ?: 'طلب مشروبات' }}
                            </div>
                            <div class="text-[10px] text-[#73777A]">
                                {{ $order->created_at->format('Y-m-d h:i A') }} • {{ $order->table_or_room_name ?: 'المساحة' }}
                            </div>
                        </div>
                        <div class="text-left">
                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold {{ $order->fulfillment_status === 'delivered' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
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
