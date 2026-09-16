@extends('shared.vertical', ['title' => 'تقرير أداء الصفقات والمبيعات ومزامنة HubSpot — DT Space'])

@section('content')
<div class="space-y-6">

 <!-- Header & Print / Back Actions -->
 <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-default-150 shadow-sm print:hidden">
 <div class="flex items-center gap-3.5">
 <div class="size-12 rounded-2xl bg-primary/10 border border-primary/20 text-primary flex items-center justify-center text-2xl shadow-sm shrink-0">
 
 </div>
 <div>
 <h1 class="text-xl font-black text-default-900 leading-tight">التقرير التحليلي لربط HubSpot والمبيعات</h1>
 <p class="text-xs text-default-500 mt-1">مؤشرات الصفقات المفتوحة والمغلقة، حركة العملاء، وتوزيع الإيرادات المتزامنة.</p>
 </div>
 </div>

 <div class="flex items-center gap-2">
 <button type="button" onclick="window.print()" class="px-4 py-2.5 rounded-xl bg-default-100 hover:bg-default-200 text-default-800 font-bold text-xs flex items-center gap-2 transition">
 <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
 <span>طباعة التقرير</span>
 </button>
 <a href="{{ route('admin.hubspot.index') }}" class="px-4 py-2.5 rounded-xl bg-primary hover:bg-primary/90 text-white font-bold text-xs flex items-center gap-2 transition shadow-md shadow-primary/25">
 <svg class="size-4 rotate-180" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
 <span>العودة لإعدادات HubSpot</span>
 </a>
 </div>
 </div>

 <!-- KPI Summary Grid -->
 <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
 
 <!-- Total Pipeline Value -->
 <div class="bg-white p-5 rounded-2xl border border-default-150 shadow-sm">
 <div class="flex items-center justify-between">
 <span class="text-xs font-bold text-default-500">قيمة الصفقات المتزامنة في HubSpot</span>
 <span class="size-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center"><svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></span>
 </div>
 <div class="text-2xl font-black text-emerald-600 font-mono mt-2">
 {{ number_format($analytics['deals']['synced_val'], 2) }} <span class="text-xs text-default-400 font-sans">ج.م</span>
 </div>
 <div class="mt-2 text-[11px] text-default-500 font-medium">
 من إجمالي مبيعات الصفقات: <strong class="text-default-800 font-mono">{{ number_format($analytics['deals']['total_val'], 2) }} ج.م</strong>
 </div>
 </div>

 <!-- Total Customers Base -->
 <div class="bg-white p-5 rounded-2xl border border-default-150 shadow-sm">
 <div class="flex items-center justify-between">
 <span class="text-xs font-bold text-default-500">قاعدة العملاء المتزامنة</span>
 <span class="size-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center"><svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></span>
 </div>
 <div class="text-2xl font-black text-blue-600 font-mono mt-2">
 {{ $analytics['customers']['synced'] }} <span class="text-xs text-default-400 font-sans">عميل في HubSpot</span>
 </div>
 <div class="mt-2 text-[11px] text-default-500 font-medium">
 نسبة المزامنة: <strong class="text-blue-600 font-mono font-bold">{{ $analytics['customers']['percentage'] }}%</strong> من إجمالي {{ $analytics['customers']['total'] }} عميل
 </div>
 </div>

 <!-- Deals Completion -->
 <div class="bg-white p-5 rounded-2xl border border-default-150 shadow-sm">
 <div class="flex items-center justify-between">
 <span class="text-xs font-bold text-default-500">حجم الصفقات والحجوزات</span>
 <span class="size-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center"><svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect width="20" height="14" x="2" y="7" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg></span>
 </div>
 <div class="text-2xl font-black text-purple-600 font-mono mt-2">
 {{ $analytics['deals']['synced'] }} <span class="text-xs text-default-400 font-sans">صفقة مسجلة</span>
 </div>
 <div class="mt-2 text-[11px] text-default-500 font-medium">
 نسبة التغطية في مسار المبيعات: <strong class="text-purple-600 font-mono font-bold">{{ $analytics['deals']['percentage'] }}%</strong>
 </div>
 </div>

 </div>

 <!-- Deals Stages & Customer Sources Grid -->
 <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

 <!-- Deals by Status (Pipeline Distribution) -->
 <div class="bg-white rounded-2xl border border-default-150 shadow-sm p-6 space-y-4">
 <div class="flex items-center justify-between pb-3 border-b border-default-100">
 <div class="flex items-center gap-2">
 <svg class="size-5 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
 <h3 class="text-sm font-black text-default-900">توزيع الصفقات حسب الحالة (Deal Stages)</h3>
 </div>
 </div>

 <div class="space-y-3">
 @forelse($dealsByStatus as $stage)
 @php
 $stageLabel = match($stage->status) {
 'open' => 'جلسات نشطة جارية (In Progress / Open)',
 'closed' => 'جلسات مكتملة ومغلقة (Closed Won)',
 'cancelled' => 'جلسات ملغاة (Closed Lost)',
 default => $stage->status,
 };
 $stageColor = match($stage->status) {
 'open' => 'bg-amber-500',
 'closed' => 'bg-emerald-500',
 'cancelled' => 'bg-rose-500',
 default => 'bg-default-400',
 };
 @endphp
 <div class="p-3.5 rounded-xl bg-default-50 border border-default-100 flex items-center justify-between">
 <div class="flex items-center gap-3">
 <span class="size-3 rounded-full {{ $stageColor }}"></span>
 <div>
 <h4 class="text-xs font-bold text-default-900">{{ $stageLabel }}</h4>
 <span class="text-[11px] text-default-500 font-mono">{{ $stage->count }} صفقة مسجلة</span>
 </div>
 </div>
 <div class="text-end">
 <span class="text-xs font-black text-default-900 font-mono">{{ number_format($stage->total_amount, 2) }} ج.م</span>
 </div>
 </div>
 @empty
 <div class="text-center py-8 text-default-400 text-xs">لا توجد صفقات مسجلة حالياً.</div>
 @endforelse
 </div>
 </div>

 <!-- Customer Sources Distribution -->
 <div class="bg-white rounded-2xl border border-default-150 shadow-sm p-6 space-y-4">
 <div class="flex items-center justify-between pb-3 border-b border-default-100">
 <div class="flex items-center gap-2">
 <svg class="size-5 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
 <h3 class="text-sm font-black text-default-900">قنوات اكتساب العملاء (Acquisition Sources)</h3>
 </div>
 </div>

 <div class="space-y-3">
 @forelse($customersBySource as $src)
 @php
 $srcLabel = match($src->source) {
 'portal' => 'بوابة الموبايل الذكية (Mobile Portal)',
 'reception' => 'الاستقبال والكاشير (POS Reception)',
 'affiliate' => 'نظام الإحالة والتوصية (Affiliate & Referral)',
 'hubspot' => 'مستورد من HubSpot CRM',
 default => $src->source ?: 'مباشر (Direct)',
 };
 @endphp
 <div class="p-3.5 rounded-xl bg-default-50 border border-default-100 flex items-center justify-between">
 <div>
 <h4 class="text-xs font-bold text-default-900">{{ $srcLabel }}</h4>
 </div>
 <div class="flex items-center gap-2">
 <span class="px-2.5 py-0.5 rounded-full text-xs font-mono font-bold bg-primary/10 text-primary">
 {{ $src->count }} عميل
 </span>
 </div>
 </div>
 @empty
 <div class="text-center py-8 text-default-400 text-xs">لا توجد بيانات كافية.</div>
 @endforelse
 </div>
 </div>

 </div>

 <!-- Top Selling / Synced Products -->
 <div class="bg-white rounded-2xl border border-default-150 shadow-sm p-6 space-y-4">
 <div class="flex items-center justify-between pb-3 border-b border-default-100">
 <div class="flex items-center gap-2">
 <svg class="size-5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3"/></svg>
 <div>
 <h3 class="text-sm font-black text-default-900">أكثر المنتجات طلباً والمزامنة مع كتالوج HubSpot</h3>
 <p class="text-xs text-default-500">الأصناف الأكثر طلباً ومربوطة ببنود الصفقات (Line Items)</p>
 </div>
 </div>
 </div>

 <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
 @forelse($topProducts as $prod)
 <div class="p-3.5 rounded-xl bg-default-50 border border-default-100 flex flex-col justify-between">
 <div>
 <div class="flex items-center justify-between mb-1.5">
 <span class="text-[10px] font-bold text-primary font-mono">{{ $prod->sku ?: 'SKU-' . $prod->id }}</span>
 @if($prod->hubspot_product_id)
 <span class="text-[10px] text-emerald-600 font-bold bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">متزامن</span>
 @else
 <span class="text-[10px] text-default-400 font-bold bg-default-100 px-1.5 py-0.5 rounded">غير متزامن</span>
 @endif
 </div>
 <h4 class="text-xs font-bold text-default-900 truncate">{{ $prod->name }}</h4>
 <div class="text-sm font-black text-emerald-600 font-mono mt-1">{{ number_format($prod->selling_price, 2) }} ج.م</div>
 </div>
 <div class="mt-3 pt-2 border-t border-default-200/60 text-[11px] text-default-500 flex justify-between">
 <span>إجمالي مرات الطلب:</span>
 <strong class="text-default-800 font-mono">{{ $prod->order_items_count }}</strong>
 </div>
 </div>
 @empty
 <div class="col-span-4 text-center py-8 text-default-400 text-xs">لا توجد مبيعات منتجات حتى الآن.</div>
 @endforelse
 </div>
 </div>

</div>
@endsection
