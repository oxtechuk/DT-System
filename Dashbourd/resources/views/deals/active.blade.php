@extends('shared.vertical', ['title' => 'الجلسات النشطة — DT-System'])

@section('content')

 {{-- Page Header --}}
 <div class="page-header-container">
 <div>

 <h1 class="text-2xl font-extrabold text-[#303334] tracking-tight">
 الجلسات المفتوحة والنشطة حالياً
 </h1>
 <p class="text-xs text-[#73777A] mt-1">متابعة وقت الجلسات، الطلبات الإضافية، وسرعة المحاسبة عبر شاشة الكاشير</p>
 </div>

 <div class="page-header-actions">
 <a href="{{ url('/cashier') }}" target="_blank"
 class="px-4 py-2.5 bg-[#4E8F35] hover:bg-[#3F742B] text-white rounded-xl text-xs font-bold transition-all shadow-xs gap-2 flex items-center">
 <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
 <rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/>
 </svg>
 <span>فتح شاشة الكاشير (POS)</span>
 </a>
 </div>
 </div>

 {{-- Active Deals Grid --}}
 @if($deals->count() > 0)
 <div class="dt-grid-3">
 @foreach($deals as $deal)
 <div class="dt-card border-teal-200 bg-gradient-to-b from-teal-50/20 via-white to-white flex flex-col justify-between">
 <div>
 <div class="flex items-center justify-between mb-3">
 <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-teal-100 text-teal-800">
 <span class="size-2 rounded-full bg-teal-500 animate-ping"></span>
 جلسة نشطة
 </span>
 <span class="text-xs text-default-400 font-mono">#DEAL-{{ $deal->id }}</span>
 </div>

 <h3 class="text-lg font-bold text-default-900 mb-1">{{ $deal->customer->name ?? 'عميل غير مسجل' }}</h3>
 <p class="text-xs text-default-500 mb-4">{{ $deal->room->name ?? 'مساحة عمل مفتوحة' }} — {{ $deal->workspaceType->name ?? 'مكتب' }}</p>

 <div class="bg-default-50 p-3 rounded-xl border border-default-100 space-y-2 text-xs mb-4">
 <div class="flex items-center justify-between">
 <span class="text-default-400">وقت البدء:</span>
 <strong class="font-mono text-default-800">{{ $deal->started_at->format('h:i A') }}</strong>
 </div>
 <div class="flex items-center justify-between">
 <span class="text-default-400">المدة المنقضية:</span>
 <strong class="font-mono text-teal-700">{{ $deal->started_at->diffForHumans(null, true) }}</strong>
 </div>
 <div class="flex items-center justify-between">
 <span class="text-default-400">طلبات الكافيه:</span>
 <strong class="font-mono text-default-800">{{ $deal->order ? $deal->order->items->count() : 0 }} أصناف</strong>
 </div>
 </div>
 </div>

 <div class="pt-3 border-t border-default-100 flex items-center justify-between">
 <a href="{{ url('/cashier') }}" target="_blank"
 class="w-full py-2.5 bg-primary hover:bg-primary/90 text-white rounded-xl text-xs font-bold transition-all text-center">
 إدارة الجلسة والمحاسبة بالكاشير ↗
 </a>
 </div>
 </div>
 @endforeach
 </div>
 @else
 <div class="dt-card p-12 bg-white text-center max-w-md mx-auto my-10">
 <div class="size-16 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center mx-auto mb-4">
 <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
 <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
 </svg>
 </div>
 <h3 class="text-lg font-black text-default-900 mb-1">لا توجد جلسات نشطة حالياً</h3>
 <p class="text-xs text-default-400 mb-6">جميع الغرف والمساحات شاغرة. يمكنك فتح جلسة جديدة لأي عميل من خلال شاشة الكاشير.</p>
 <a href="{{ url('/cashier') }}" target="_blank"
 class="inline-block px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-emerald-600/25">
 بدء جلسة جديدة بالكاشير
 </a>
 </div>
 @endif

@endsection
