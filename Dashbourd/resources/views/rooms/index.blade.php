@extends('shared.vertical', ['title' => 'الغرف والمساحات — DT-System'])

@section('content')

    {{-- Page Header --}}
    <div class="page-header-container">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2.5 py-0.5 rounded-full border border-amber-200">المساحات وقاعات العمل</span>
                <span class="text-xs text-default-400 font-mono">Rooms & Workspaces</span>
            </div>
            <h2 class="text-2xl font-extrabold text-default-900 tracking-tight">
                الغرف والمساحات
            </h2>
            <p class="text-xs text-default-400 mt-1">متابعة الغرف، السعة الاستيعابية، وحالة الشغور لكل مساحة</p>
        </div>

        <div class="page-header-actions">
            <a href="{{ url('/cashier') }}" target="_blank"
               class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-emerald-600/25 gap-2">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/>
                </svg>
                <span>بدء جلسة لغرفة بالكاشير</span>
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="dt-grid-3">
        <div class="dt-stat-card">
            <div class="dt-stat-info">
                <span class="dt-stat-label">إجمالي الغرف</span>
                <div class="dt-stat-value">{{ $rooms->count() }}</div>
            </div>
            <div class="dt-stat-icon bg-amber-50 text-amber-600">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M13 4h3a2 2 0 0 1 2 2v14"/><path d="M2 20h20"/><path d="M13 20V4a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v16"/>
                </svg>
            </div>
        </div>

        <div class="dt-stat-card">
            <div class="dt-stat-info">
                <span class="dt-stat-label">الغرف المتاحة حالياً</span>
                <div class="dt-stat-value text-emerald-600">{{ $availableRooms }}</div>
            </div>
            <div class="dt-stat-icon bg-emerald-50 text-emerald-600">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
            </div>
        </div>

        <div class="dt-stat-card">
            <div class="dt-stat-info">
                <span class="dt-stat-label">إجمالي السعة</span>
                <div class="dt-stat-value">{{ $totalCapacity }} <span class="text-xs font-normal text-default-400">فرد</span></div>
            </div>
            <div class="dt-stat-icon bg-purple-50 text-purple-600">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Rooms Grid --}}
    <div class="dt-grid-3">
        @foreach($rooms as $room)
            <div class="dt-card flex flex-col justify-between {{ $room->is_available ? '' : 'border-rose-200 bg-rose-50/20' }}">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $room->is_available ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                            {{ $room->is_available ? 'شاغرة ومتاحة' : 'مشغولة حالياً' }}
                        </span>
                        <span class="text-xs text-default-400 font-mono">{{ $room->code }}</span>
                    </div>

                    <h3 class="text-lg font-bold text-default-900 mb-1">{{ $room->name }}</h3>
                    <p class="text-xs text-default-500 mb-3">{{ $room->description ?? 'قاعة عمل ومساحة مشتركة' }}</p>

                    <div class="flex items-center gap-4 text-xs text-default-600 mb-4 bg-default-50 p-2.5 rounded-xl border border-default-100">
                        <div>السعة: <strong class="text-default-900 font-mono">{{ $room->capacity }}</strong> أفراد</div>
                        <div class="border-s border-default-200 ps-4">
                            التسعير: <strong class="text-primary font-bold">حسب باقة المدة</strong>
                        </div>
                    </div>

                    @if(!$room->is_available && $room->activeDeals->first())
                        @php $deal = $room->activeDeals->first(); @endphp
                        <div class="p-3 bg-rose-50 border border-rose-200/60 rounded-xl mb-3 text-xs">
                            <span class="font-bold text-rose-800 block">مشغولة بواسطة: {{ $deal->customer->name ?? 'عميل' }}</span>
                            <span class="text-[11px] text-rose-600 font-mono">منذ {{ $deal->started_at->format('h:i A') }}</span>
                        </div>
                    @endif
                </div>

                <div class="pt-3 border-t border-default-100 flex items-center justify-between">
                    <a href="{{ url('/cashier') }}" target="_blank"
                       class="text-xs font-bold text-primary hover:underline">
                        إدارة الجلسة في الكاشير ↗
                    </a>
                </div>
            </div>
        @endforeach
    </div>

@endsection
