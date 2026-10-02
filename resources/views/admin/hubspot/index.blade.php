@extends('shared.vertical', ['title' => 'الربط والتكامل مع HubSpot CRM — DT Space'])

@section('content')
<div class="space-y-6">

 <!-- Top Header Bar -->
 <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-default-150 shadow-sm">
 <div class="flex items-center gap-3.5">
 <div class="size-12 rounded-2xl bg-orange-500/10 border border-orange-500/20 text-orange-600 flex items-center justify-center text-2xl shadow-sm shrink-0">
 <svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor">
 <path d="M18.8 7.3c-.6 0-1.1.4-1.3.9l-2.4-.7c.1-.4.1-.7.1-1.1 0-1.8-1.5-3.3-3.3-3.3s-3.3 1.5-3.3 3.3c0 .5.1.9.3 1.3L6.7 9.8c-.3-.2-.7-.3-1.1-.3-1.4 0-2.5 1.1-2.5 2.5s1.1 2.5 2.5 2.5c.5 0 1-.1 1.4-.4l2.1 2.2c-.1.3-.2.6-.2 1 0 1.8 1.5 3.3 3.3 3.3s3.3-1.5 3.3-3.3c0-.4-.1-.8-.2-1.1l2.4-.7c.2.6.8 1 1.4 1 1 0 1.8-.8 1.8-1.8 0-1-.8-1.8-1.8-1.8-.6 0-1.1.4-1.3.9l-2.4-.7c0-.2.1-.5.1-.7 0-.4-.1-.7-.1-1.1l2.4-.7c.2.6.8 1 1.4 1 1 0 1.8-.8 1.8-1.8 0-1-.8-1.8-1.8-1.8zM11.9 4.8c.8 0 1.5.7 1.5 1.5s-.7 1.5-1.5 1.5-1.5-.7-1.5-1.5.7-1.5 1.5-1.5zm.3 13.9c-.8 0-1.5-.7-1.5-1.5s.7-1.5 1.5-1.5 1.5.7 1.5 1.5-.7 1.5-1.5 1.5z"/>
 </svg>
 </div>
 <div>
 <div class="flex items-center gap-2.5">
 <h1 class="text-xl font-black text-default-900 leading-tight">الربط المتكامل مع HubSpot CRM</h1>
 @if($analytics['is_enabled'])
 <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-600 border border-emerald-500/20">
 <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
 الربط مفعل ونشط
 </span>
 @else
 <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-500/10 text-amber-600 border border-amber-500/20">
 <span class="size-2 rounded-full bg-amber-500"></span>
 غير مفعل أو بانتظار الإعداد
 </span>
 @endif
 </div>
 <p class="text-xs text-default-500 mt-1">مزامنة العملاء، الحجوزات (Deals)، والمنتجات لحظياً مع حسابك في HubSpot وإنشاء تقارير الأداء.</p>
 </div>
 </div>

 <div class="flex items-center gap-2">
 <a href="{{ route('admin.hubspot.report') }}" class="px-4 py-2.5 rounded-xl bg-default-100 hover:bg-default-200 text-default-700 font-bold text-xs flex items-center gap-2 transition">
 <svg class="size-4 text-default-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
 <span>التقرير التحليلي المالي</span>
 </a>
 <button type="button" onclick="triggerTestConnection()" id="test-connection-btn" class="px-4 py-2.5 rounded-xl bg-orange-50 hover:bg-orange-100 text-orange-600 border border-orange-200 font-bold text-xs flex items-center gap-2 transition active:scale-95 shadow-sm">
 <svg class="size-4 animate-spin hidden" id="test-spinner" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
 <span id="test-btn-text">اختبار الاتصال</span>
 </button>
 </div>
 </div>

 <!-- Alert / Flash Messages -->
 @if(session('success'))
 <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center gap-2.5 shadow-sm">
 <svg class="size-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
 <span>{{ session('success') }}</span>
 </div>
 @endif
 @if(session('warning'))
 <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-bold flex items-center gap-2.5 shadow-sm">
 <svg class="size-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
 <span>{{ session('warning') }}</span>
 </div>
 @endif
 @if(session('error'))
 <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold flex items-center gap-2.5 shadow-sm">
 <svg class="size-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
 <span>{{ session('error') }}</span>
 </div>
 @endif

 <!-- Ajax Connection Result Banner (Hidden by default) -->
 <div id="ajax-connection-banner" class="hidden p-4 rounded-xl text-xs font-bold flex items-center justify-between shadow-sm transition">
 <span id="ajax-connection-text"></span>
 <button type="button" onclick="document.getElementById('ajax-connection-banner').classList.add('hidden')" class="text-default-500 hover:text-default-900 font-bold text-sm"></button>
 </div>

 <!-- KPI Metric Cards Grid -->
 <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
 
 <!-- 1. Contacts -->
 <div class="bg-white p-5 rounded-2xl border border-default-150 shadow-sm relative overflow-hidden">
 <div class="flex items-center justify-between">
 <div>
 <p class="text-xs font-bold text-default-500">العملاء (Contacts)</p>
 <h3 class="text-2xl font-black text-default-900 mt-1">
 {{ $analytics['customers']['synced'] }} <span class="text-xs font-semibold text-default-400">من أصل {{ $analytics['customers']['total'] }}</span>
 </h3>
 </div>
 <div class="size-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg font-bold">
 
 </div>
 </div>
 <!-- Progress Bar -->
 <div class="mt-3.5">
 <div class="flex justify-between text-[11px] font-bold text-default-500 mb-1">
 <span>نسبة المزامنة</span>
 <span class="text-blue-600 font-mono">{{ $analytics['customers']['percentage'] }}%</span>
 </div>
 <div class="w-full bg-default-100 h-2 rounded-full overflow-hidden">
 <div class="bg-blue-600 h-2 rounded-full transition-all" style="width: {{ $analytics['customers']['percentage'] }}%"></div>
 </div>
 </div>
 </div>

 <!-- 2. Deals -->
 <div class="bg-white p-5 rounded-2xl border border-default-150 shadow-sm relative overflow-hidden">
 <div class="flex items-center justify-between">
 <div>
 <p class="text-xs font-bold text-default-500">الجلسات والحجوزات (Deals)</p>
 <h3 class="text-2xl font-black text-default-900 mt-1">
 {{ $analytics['deals']['synced'] }} <span class="text-xs font-semibold text-default-400">من أصل {{ $analytics['deals']['total'] }}</span>
 </h3>
 </div>
 <div class="size-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg font-bold">
 
 </div>
 </div>
 <!-- Total Value -->
 <div class="mt-3.5 flex items-center justify-between text-[11px] font-bold">
 <span class="text-default-500">إجمالي قيمة الصفقات:</span>
 <span class="text-emerald-600 font-mono font-black">{{ number_format($analytics['deals']['total_val'], 2) }} ج.م</span>
 </div>
 </div>

 <!-- 3. Products -->
 <div class="bg-white p-5 rounded-2xl border border-default-150 shadow-sm relative overflow-hidden">
 <div class="flex items-center justify-between">
 <div>
 <p class="text-xs font-bold text-default-500">المنتجات وقائمة الأسعار</p>
 <h3 class="text-2xl font-black text-default-900 mt-1">
 {{ $analytics['products']['synced'] }} <span class="text-xs font-semibold text-default-400">من أصل {{ $analytics['products']['total'] }}</span>
 </h3>
 </div>
 <div class="size-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg font-bold">
 
 </div>
 </div>
 <!-- Progress Bar -->
 <div class="mt-3.5">
 <div class="flex justify-between text-[11px] font-bold text-default-500 mb-1">
 <span>نسبة المزامنة</span>
 <span class="text-amber-600 font-mono">{{ $analytics['products']['percentage'] }}%</span>
 </div>
 <div class="w-full bg-default-100 h-2 rounded-full overflow-hidden">
 <div class="bg-amber-500 h-2 rounded-full transition-all" style="width: {{ $analytics['products']['percentage'] }}%"></div>
 </div>
 </div>
 </div>

 <!-- 4. Sync Health -->
 <div class="bg-white p-5 rounded-2xl border border-default-150 shadow-sm relative overflow-hidden">
 <div class="flex items-center justify-between">
 <div>
 <p class="text-xs font-bold text-default-500">صحة ومعدل المزامنة</p>
 <h3 class="text-2xl font-black text-default-900 mt-1">
 {{ $analytics['logs']['success_rate'] }}% <span class="text-xs font-semibold text-default-400">نجاح العمليات</span>
 </h3>
 </div>
 <div class="size-11 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg font-bold">
 
 </div>
 </div>
 <div class="mt-3.5 flex items-center justify-between text-[11px] text-default-500 font-bold">
 <span>إجمالي السجلات: <strong class="text-default-800">{{ $analytics['logs']['total'] }}</strong></span>
 <span>آخر مزامنة: <strong class="text-default-800">{{ $analytics['last_sync_time'] ? $analytics['last_sync_time']->diffForHumans() : 'لا يوجد' }}</strong></span>
 </div>
 </div>

 </div>

 <!-- Quick Manual Sync Toolbar -->
 <div class="bg-gradient-to-r from-orange-500/10 via-amber-500/10 to-orange-500/5 p-4 rounded-2xl border border-orange-500/20 flex flex-wrap items-center justify-between gap-3">
 <div class="flex items-center gap-2.5">
 <svg class="size-5 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
 <div>
 <h4 class="text-xs font-black text-default-900">إجراءات المزامنة الفورية السريعة (Instant Sync)</h4>
 <p class="text-[11px] text-default-500">يمكنك دفع البيانات من النظام إلى حسابك في HubSpot أو استيراد العملاء الجدد بضغطة زر واحدة</p>
 </div>
 </div>

 <div class="flex flex-wrap items-center gap-2">
 <!-- Sync Customers -->
 <form method="POST" action="{{ route('admin.hubspot.sync') }}" class="inline">
 @csrf
 <input type="hidden" name="type" value="customers">
 <button type="submit" class="px-3 py-2 rounded-xl bg-white hover:bg-blue-50 text-blue-600 border border-blue-200 text-xs font-bold shadow-sm transition active:scale-95 flex items-center gap-1.5">
 <span>مزامنة العملاء</span>
 </button>
 </form>

 <!-- Sync Deals -->
 <form method="POST" action="{{ route('admin.hubspot.sync') }}" class="inline">
 @csrf
 <input type="hidden" name="type" value="deals">
 <button type="submit" class="px-3 py-2 rounded-xl bg-white hover:bg-emerald-50 text-emerald-600 border border-emerald-200 text-xs font-bold shadow-sm transition active:scale-95 flex items-center gap-1.5">
 <span>مزامنة الصفقات</span>
 </button>
 </form>

 <!-- Sync Products -->
 <form method="POST" action="{{ route('admin.hubspot.sync') }}" class="inline">
 @csrf
 <input type="hidden" name="type" value="products">
 <button type="submit" class="px-3 py-2 rounded-xl bg-white hover:bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold shadow-sm transition active:scale-95 flex items-center gap-1.5">
 <span>مزامنة المنيو</span>
 </button>
 </form>

 <!-- Pull / Import from HubSpot -->
 <form method="POST" action="{{ route('admin.hubspot.pull') }}" class="inline">
 @csrf
 <button type="submit" class="px-3 py-2 rounded-xl bg-white hover:bg-purple-50 text-purple-600 border border-purple-200 text-xs font-bold shadow-sm transition active:scale-95 flex items-center gap-1.5">
 <span>سحب من HubSpot</span>
 </button>
 </form>

 <!-- Sync All -->
 <form method="POST" action="{{ route('admin.hubspot.sync') }}" class="inline">
 @csrf
 <input type="hidden" name="type" value="all">
 <button type="submit" class="px-4 py-2 rounded-xl bg-orange-600 hover:bg-orange-700 text-white text-xs font-extrabold shadow-md shadow-orange-600/30 transition active:scale-95 flex items-center gap-1.5">
 <span>مزامنة شاملة للكل</span>
 </button>
 </form>
 </div>
 </div>

 <!-- Main Content Grid: Settings on Left/Right & Instructions -->
 <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

 <!-- Column 1 & 2: HubSpot Settings Form -->
 <div class="lg:col-span-2 bg-white rounded-2xl border border-default-150 shadow-sm p-6 space-y-6">
 <div class="flex items-center justify-between pb-4 border-b border-default-100">
 <div class="flex items-center gap-2">
 <svg class="size-5 text-default-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
 <div>
 <h3 class="text-sm font-black text-default-900">إعدادات الاتصال والمسارات (API Configuration)</h3>
 <p class="text-xs text-default-500">أدخل مفتاح Private App Token وإعدادات مسار المبيعات</p>
 </div>
 </div>
 </div>

 <form method="POST" action="{{ route('admin.hubspot.settings.update') }}" class="space-y-5">
 @csrf

 <!-- Toggles: Enable & Auto-Sync -->
 <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
 <!-- Toggle Enable -->
 <label class="p-3.5 rounded-xl border border-default-200 hover:border-primary/50 flex items-center justify-between cursor-pointer transition {{ $settings['enabled'] ? 'bg-primary/5 border-primary/40' : 'bg-default-50' }}">
 <div class="pr-2">
 <span class="text-xs font-extrabold text-default-900 block">تفعيل الربط مع HubSpot</span>
 <span class="text-[11px] text-default-500">تمكين التبادل التلقائي للبيانات</span>
 </div>
 <input type="checkbox" name="hubspot_enabled" value="1" {{ $settings['enabled'] ? 'checked' : '' }}
 class="size-5 rounded text-primary focus:ring-primary">
 </label>

 <!-- Toggle Auto-Sync -->
 <label class="p-3.5 rounded-xl border border-default-200 hover:border-emerald-500/50 flex items-center justify-between cursor-pointer transition {{ $settings['auto_sync'] ? 'bg-emerald-50/60 border-emerald-300' : 'bg-default-50' }}">
 <div class="pr-2">
 <span class="text-xs font-extrabold text-default-900 block">المزامنة اللحظية التلقائية</span>
 <span class="text-[11px] text-default-500">عند تسجيل عميل أو فتح/إغلاق جلسة</span>
 </div>
 <input type="checkbox" name="hubspot_auto_sync" value="1" {{ $settings['auto_sync'] ? 'checked' : '' }}
 class="size-5 rounded text-emerald-600 focus:ring-emerald-500">
 </label>
 </div>

 <!-- Private App Access Token -->
 <div>
 <div class="flex items-center justify-between mb-1.5">
 <label class="text-xs font-bold text-default-700 flex items-center gap-1.5">
 <span>HubSpot Private App Access Token:</span>
 <span class="text-rose-500">*</span>
 </label>
 <button type="button" onclick="toggleTokenVisibility()" class="text-[11px] text-primary hover:underline font-bold">
 <span id="token-toggle-text">إظهار التوكن</span>
 </button>
 </div>
 <div class="relative">
 <input type="password" id="hubspot-token-input" name="hubspot_access_token" 
 value="{{ $settings['token'] }}"
 placeholder="pat-na1-xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx"
 class="w-full bg-default-50 border border-default-200 rounded-xl px-3.5 py-2.5 text-xs text-default-900 font-mono focus:outline-none focus:border-primary focus:bg-white transition">
 </div>
 <p class="text-[11px] text-default-400 mt-1">يبدأ عادةً بـ <code class="text-primary font-mono">pat-na1-...</code> أو <code class="text-primary font-mono">pat-eu1-...</code></p>
 </div>

 <!-- Pipeline & Stages Grid -->
 <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
 <!-- Pipeline ID -->
 <div>
 <label class="block text-xs font-bold text-default-700 mb-1.5">معرف مسار الصفقات (Pipeline ID):</label>
 <input type="text" name="hubspot_pipeline_id" value="{{ $settings['pipeline_id'] }}" placeholder="default"
 class="w-full bg-default-50 border border-default-200 rounded-xl px-3 py-2 text-xs text-default-900 font-mono focus:outline-none focus:border-primary focus:bg-white transition">
 <span class="text-[10px] text-default-400">اتركه <code class="text-default-700 font-mono">default</code> للمسار الافتراضي</span>
 </div>

 <!-- Open Deal Stage -->
 <div>
 <label class="block text-xs font-bold text-default-700 mb-1.5">مرحلة الجلسة النشطة (Open):</label>
 <input type="text" name="hubspot_stage_open" value="{{ $settings['stage_open'] }}" placeholder="appointmentscheduled"
 class="w-full bg-default-50 border border-default-200 rounded-xl px-3 py-2 text-xs text-default-900 font-mono focus:outline-none focus:border-primary focus:bg-white transition">
 <span class="text-[10px] text-default-400">الافتراضي: <code class="text-default-700 font-mono">appointmentscheduled</code></span>
 </div>

 <!-- Closed Deal Stage -->
 <div>
 <label class="block text-xs font-bold text-default-700 mb-1.5">مرحلة الجلسة المكتملة (Won):</label>
 <input type="text" name="hubspot_stage_closed" value="{{ $settings['stage_closed'] }}" placeholder="closedwon"
 class="w-full bg-default-50 border border-default-200 rounded-xl px-3 py-2 text-xs text-default-900 font-mono focus:outline-none focus:border-primary focus:bg-white transition">
 <span class="text-[10px] text-default-400">الافتراضي: <code class="text-default-700 font-mono">closedwon</code></span>
 </div>
 </div>

 <!-- Submit Button -->
 <div class="pt-4 border-t border-default-100 flex items-center justify-end">
 <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary/90 text-white font-extrabold text-xs shadow-md shadow-primary/30 transition active:scale-95 flex items-center gap-2">
 <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
 <span>حفظ إعدادات HubSpot</span>
 </button>
 </div>
 </form>
 </div>

 <!-- Column 3: Step-by-step Setup Guide -->
 <div class="bg-white rounded-2xl border border-default-150 shadow-sm p-6 space-y-4">
 <div class="flex items-center gap-2 pb-3 border-b border-default-100">
 <svg class="size-5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
 <h4 class="text-xs font-black text-default-900">كيف تستخرج Private App Token من HubSpot؟</h4>
 </div>

 <ol class="space-y-3 text-xs text-default-600 leading-relaxed list-decimal list-inside pr-1">
 <li class="pb-1">
 سجّل الدخول إلى حسابك على <a href="https://app.hubspot.com" target="_blank" class="text-primary font-bold underline">HubSpot CRM</a>.
 </li>
 <li class="pb-1">
 انقر على أيقونة الإعدادات <strong>( Settings)</strong> في الشريط العلوي الأيمن.
 </li>
 <li class="pb-1">
 من القائمة الجانبية اليسرى، افتح:
 <strong class="text-default-900 block mt-1 font-mono text-[11px] bg-default-100 p-1.5 rounded-lg">Integrations ⬅ Private Apps</strong>
 </li>
 <li class="pb-1">
 اضغط على زر <strong>Create a private app</strong> وضع اسماً للتطبيق مثل (DT-System).
 </li>
 <li class="pb-1">
 في تبويب <strong>Scopes</strong>، حدد الصلاحيات التالية:
 <div class="mt-1.5 space-y-1 text-[11px] font-mono text-emerald-700 bg-emerald-50/70 p-2 rounded-lg border border-emerald-200">
 <div> crm.objects.contacts (read & write)</div>
 <div> crm.objects.deals (read & write)</div>
 <div> crm.objects.custom (read & write)</div>
 </div>
 </li>
 <li class="pb-1">
 اضغط <strong>Create app</strong> ثم انسخ الـ <strong>Access Token</strong> والصقه في الحقل المقابل هنا!
 </li>
 </ol>

 <div class="p-3 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 text-[11px] flex items-center gap-2">
 <svg class="size-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
 <span>بيانات التوكن يتم تخزينها واستخدامها بأمان عبر اتصالات HTTPS مشفرة حصرياً.</span>
 </div>
 </div>

 </div>

 <!-- Live Sync Logs Table -->
 <div class="bg-white rounded-2xl border border-default-150 shadow-sm overflow-hidden">
 <div class="p-5 border-b border-default-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
 <div class="flex items-center gap-2.5">
 <svg class="size-5 text-default-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
 <div>
 <h3 class="text-sm font-black text-default-900">سجل عمليات المزامنة اللحظي (Sync Logs)</h3>
 <p class="text-xs text-default-500">سجل بجميع العمليات البرمجية بين المنصة و HubSpot مع تفاصيل النجاح والأخطاء</p>
 </div>
 </div>

 <!-- Filters -->
 <form method="GET" action="{{ route('admin.hubspot.index') }}" class="flex items-center gap-2 text-xs">
 <select name="type" onchange="this.form.submit()" class="bg-default-50 border border-default-200 rounded-xl px-2.5 py-1.5 text-xs text-default-700 font-bold focus:outline-none focus:border-primary">
 <option value="">كافة الكيانات</option>
 <option value="customer" {{ request('type') == 'customer' ? 'selected' : '' }}>عملاء (Contacts)</option>
 <option value="deal" {{ request('type') == 'deal' ? 'selected' : '' }}>صفقات (Deals)</option>
 <option value="product" {{ request('type') == 'product' ? 'selected' : '' }}>منتجات (Products)</option>
 </select>

 <select name="status" onchange="this.form.submit()" class="bg-default-50 border border-default-200 rounded-xl px-2.5 py-1.5 text-xs text-default-700 font-bold focus:outline-none focus:border-primary">
 <option value="">كافة الحالات</option>
 <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>ناجح</option>
 <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>فشل</option>
 </select>
 </form>
 </div>

 <div class="overflow-x-auto">
 <table class="w-full text-start text-xs">
 <thead class="bg-default-50 text-default-600 font-bold border-b border-default-100">
 <tr>
 <th class="py-3 px-4 text-start">الوقت والتاريخ</th>
 <th class="py-3 px-4 text-start">النوع</th>
 <th class="py-3 px-4 text-start">الإجراء</th>
 <th class="py-3 px-4 text-start">المعرف المحلي</th>
 <th class="py-3 px-4 text-start">معرف HubSpot</th>
 <th class="py-3 px-4 text-start">الحالة</th>
 <th class="py-3 px-4 text-start">التفاصيل والأخطاء</th>
 </tr>
 </thead>
 <tbody class="divide-y divide-default-100">
 @forelse($logs as $log)
 <tr class="hover:bg-default-50/60 transition">
 <td class="py-3 px-4 text-default-500 whitespace-nowrap font-mono text-[11px]">
 {{ $log->created_at->format('Y-m-d H:i:s') }}
 </td>
 <td class="py-3 px-4 font-bold text-default-800">
 @switch($log->entity_type)
 @case('customer')
 <span class="text-blue-600 font-bold">عميل</span>
 @break
 @case('deal')
 <span class="text-emerald-600 font-bold">صفقة/حجز</span>
 @break
 @case('product')
 <span class="text-amber-600 font-bold">منتج</span>
 @break
 @default
 <span class="text-default-600 font-bold">عام</span>
 @endswitch
 </td>
 <td class="py-3 px-4 text-default-700 font-mono text-[11px]">
 <span class="px-2 py-0.5 rounded bg-default-100 font-bold uppercase">{{ $log->action }}</span>
 </td>
 <td class="py-3 px-4 text-default-700 font-mono">
 {{ $log->entity_id ? '#' . $log->entity_id : '—' }}
 </td>
 <td class="py-3 px-4 text-primary font-mono font-bold">
 {{ $log->hubspot_id ?: '—' }}
 </td>
 <td class="py-3 px-4">
 {!! $log->status_badge !!}
 </td>
 <td class="py-3 px-4 text-default-600 max-w-xs truncate">
 @if($log->error_message)
 <span class="text-rose-600 font-bold text-[11px]" title="{{ $log->error_message }}">{{ Str::limit($log->error_message, 45) }}</span>
 @elseif($log->response)
 <span class="text-emerald-600 text-[11px] font-mono">رد سليم من HubSpot API</span>
 @else
 <span class="text-default-400">—</span>
 @endif
 </td>
 </tr>
 @empty
 <tr>
 <td colspan="7" class="py-12 text-center text-default-400 text-xs">
 لا توجد سجلات مزامنة مسجلة حتى الآن.
 </td>
 </tr>
 @endforelse
 </tbody>
 </table>
 </div>

 @if($logs->hasPages())
 <div class="p-4 border-t border-default-100">
 {{ $logs->links() }}
 </div>
 @endif
 </div>

</div>
@endsection

@section('scripts')
<script>
 function toggleTokenVisibility() {
 const input = document.getElementById('hubspot-token-input');
 const text = document.getElementById('token-toggle-text');
 if (input.type === 'password') {
 input.type = 'text';
 text.textContent = 'إخفاء التوكن';
 } else {
 input.type = 'password';
 text.textContent = 'إظهار التوكن';
 }
 }

 async function triggerTestConnection() {
 const btn = document.getElementById('test-connection-btn');
 const spinner = document.getElementById('test-spinner');
 const text = document.getElementById('test-btn-text');
 const banner = document.getElementById('ajax-connection-banner');
 const bannerText = document.getElementById('ajax-connection-text');

 btn.disabled = true;
 spinner.classList.remove('hidden');
 text.textContent = 'جاري الفحص...';

 try {
 const response = await fetch("{{ route('admin.hubspot.test') }}", {
 method: "POST",
 headers: {
 "Content-Type": "application/json",
 "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || "{{ csrf_token() }}",
 "Accept": "application/json"
 }
 });

 const data = await response.json();
 banner.classList.remove('hidden');
 if (data.success) {
 banner.className = 'p-4 rounded-xl text-xs font-bold flex items-center justify-between shadow-sm bg-emerald-50 border border-emerald-200 text-emerald-800';
 bannerText.innerHTML = `<span> ${data.message}</span>`;
 } else {
 banner.className = 'p-4 rounded-xl text-xs font-bold flex items-center justify-between shadow-sm bg-rose-50 border border-rose-200 text-rose-800';
 bannerText.innerHTML = `<span> ${data.message}</span>`;
 }
 } catch (e) {
 banner.classList.remove('hidden');
 banner.className = 'p-4 rounded-xl text-xs font-bold flex items-center justify-between shadow-sm bg-rose-50 border border-rose-200 text-rose-800';
 bannerText.innerHTML = `<span> فشل الاتصال بالخادم المحلي أو حدث انقطاع في الشبكة.</span>`;
 } finally {
 btn.disabled = false;
 spinner.classList.add('hidden');
 text.textContent = 'اختبار الاتصال';
 }
 }
</script>
@endsection
