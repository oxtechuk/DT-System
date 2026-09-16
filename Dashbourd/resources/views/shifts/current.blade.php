@extends('shared.vertical', ['title' => 'الوردية الحالية — DT-System'])

@section('content')

 {{-- Page Header --}}
 <div class="page-header-container">
 <div>
 <div class="flex items-center gap-2 mb-1">
 <span class="text-xs font-bold text-orange-600 bg-orange-50 px-2.5 py-0.5 rounded-full border border-orange-200">المالية والدرج</span>
 <span class="text-xs text-default-400 font-mono">Shift Management</span>
 </div>
 <h2 class="text-2xl font-extrabold text-default-900 tracking-tight">
 الوردية الحالية وتسليم الكاشير
 </h2>
 <p class="text-xs text-default-400 mt-1">متابعة دقيقة لحظة بلحظة للدرج النقدي، المبيعات المحصلة، وإقفال الوردية</p>
 </div>

 {{-- Action Buttons --}}
 <div class="page-header-actions">
 <a href="{{ url('/cashier') }}" target="_blank"
 class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-emerald-600/25 gap-2">
 <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
 <rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/>
 </svg>
 <span>شاشة الكاشير (POS)</span>
 </a>
 @if($shift)
 <button type="button" onclick="document.getElementById('close-shift-modal').classList.remove('hidden')"
 class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-rose-600/25 gap-2">
 <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
 <circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/>
 </svg>
 <span>تقفيل وتسليم الوردية</span>
 </button>
 @endif
 </div>
 </div>

 @if(session('success'))
 <div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center gap-2">
 <svg width="18" height="18" class="text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
 <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
 </svg>
 {{ session('success') }}
 </div>
 @endif

 @if(session('warning'))
 <div class="mb-5 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-bold flex items-center gap-2">
 <svg width="18" height="18" class="text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
 <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/>
 </svg>
 {{ session('warning') }}
 </div>
 @endif

 @if($shift)
 {{-- Active Shift Banner --}}
 <div class="dt-card bg-gradient-to-r from-orange-50/50 via-white to-amber-50/30 border-orange-200/80">
 <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
 <div class="flex items-center gap-4">
 <div class="size-14 rounded-2xl bg-orange-500 text-white flex items-center justify-center shadow-md shadow-orange-500/25 shrink-0">
 <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
 <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 8 14"/>
 </svg>
 </div>
 <div>
 <div class="flex items-center gap-2">
 <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
 <span class="size-2 rounded-full bg-emerald-500 animate-ping"></span>
 وردية مفتوحة ونشطة
 </span>
 <span class="text-xs text-default-400 font-mono">رقم الوردية #{{ $shift->id }}</span>
 </div>
 <h3 class="text-base font-bold text-default-900 mt-1">
 المسؤول الحالي: <span class="text-primary">{{ $shift->user->name ?? 'مدير النظام' }}</span>
 </h3>
 <p class="text-xs text-default-500 mt-0.5">
 بدأت في: <strong class="font-mono text-default-800">{{ $shift->opened_at->format('Y-m-d — h:i A') }}</strong>
 ({{ $shift->opened_at->diffForHumans() }})
 </p>
 </div>
 </div>

 <div class="flex items-center gap-3 self-end md:self-auto">
 <div class="text-start md:text-end">
 <span class="text-[11px] font-bold text-default-400 block uppercase">النقدية المتوقعة بالدرج</span>
 <span class="text-2xl font-black text-emerald-600 font-mono">
 {{ number_format($stats['expected_cash'], 2) }}
 <span class="text-xs font-bold text-default-500">ج.م</span>
 </span>
 </div>
 </div>
 </div>
 </div>

 {{-- 4 Metric Cards --}}
 <div class="dt-grid-4">
 {{-- Opening Cash --}}
 <div class="dt-stat-card">
 <div class="dt-stat-info">
 <span class="dt-stat-label">عهدة بداية الوردية</span>
 <div class="dt-stat-value">{{ number_format($stats['opening_cash'], 2) }} <span class="text-xs font-normal text-default-400">ج.م</span></div>
 </div>
 <div class="dt-stat-icon bg-blue-50 text-blue-600">
 <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
 <rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/>
 </svg>
 </div>
 </div>

 {{-- Cash Sales --}}
 <div class="dt-stat-card">
 <div class="dt-stat-info">
 <span class="dt-stat-label">مبيعات نقدية (كاش)</span>
 <div class="dt-stat-value text-emerald-600">{{ number_format($stats['cash_sales'], 2) }} <span class="text-xs font-normal text-default-400">ج.م</span></div>
 </div>
 <div class="dt-stat-icon bg-emerald-50 text-emerald-600">
 <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
 <circle cx="12" cy="12" r="10"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><path d="M12 18V6"/>
 </svg>
 </div>
 </div>

 {{-- Electronic Sales --}}
 <div class="dt-stat-card">
 <div class="dt-stat-info">
 <span class="dt-stat-label">إلكتروني (إنستاباي/محافظ)</span>
 <div class="dt-stat-value text-indigo-600">{{ number_format($stats['instapay_sales'] + $stats['wallet_sales'] + $stats['card_sales'], 2) }} <span class="text-xs font-normal text-default-400">ج.م</span></div>
 </div>
 <div class="dt-stat-icon bg-indigo-50 text-indigo-600">
 <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
 <rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>
 </svg>
 </div>
 </div>

 {{-- Total Shift Revenue --}}
 <div class="dt-stat-card">
 <div class="dt-stat-info">
 <span class="dt-stat-label">إجمالي تحصيل الوردية</span>
 <div class="dt-stat-value">{{ number_format($stats['total_sales'], 2) }} <span class="text-xs font-normal text-default-400">ج.م</span></div>
 </div>
 <div class="dt-stat-icon bg-purple-50 text-purple-600">
 <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
 <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/>
 </svg>
 </div>
 </div>
 </div>

 {{-- Shift Transactions Table --}}
 <div class="dt-card">
 <div class="flex items-center justify-between mb-4">
 <div>
 <h3 class="text-base font-bold text-default-900">سجل عمليات الوردية الحالية</h3>
 <p class="text-xs text-default-400">جميع المبالغ المحصلة وفواتير الكاشير خلال الوردية</p>
 </div>
 <span class="text-xs bg-default-100 text-default-700 px-3 py-1 rounded-full font-bold font-mono">{{ $payments->count() }} معاملة</span>
 </div>

 <div class="dt-table-responsive">
 <table class="dt-table">
 <thead>
 <tr>
 <th>رقم المعاملة</th>
 <th>العميل / الغرفة</th>
 <th>طريقة الدفع</th>
 <th>المبلغ</th>
 <th>الوقت</th>
 </tr>
 </thead>
 <tbody>
 @forelse($payments as $pay)
 <tr>
 <td class="font-mono font-bold text-default-800">#PAY-{{ $pay->id }}</td>
 <td>
 <div class="font-bold text-default-900">{{ $pay->customer?->name ?? 'عميل مباشر' }}</div>
 <span class="text-[10px] text-default-400">{{ $pay->order?->deal?->room?->name ?? 'جلسة عامة' }}</span>
 </td>
 <td>
 @if($pay->method === 'cash')
 <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">كاش نقدي</span>
 @elseif($pay->method === 'instapay')
 <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">إنستاباي</span>
 @elseif($pay->method === 'wallet')
 <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">محفظة ذكية</span>
 @else
 <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-default-100 text-default-700">أخرى</span>
 @endif
 </td>
 <td class="font-black text-default-900 font-mono text-sm">
 {{ number_format($pay->amount, 2) }} <span class="text-[10px] font-normal text-default-400">ج.م</span>
 </td>
 <td class="text-default-400 font-mono">
 {{ $pay->paid_at->format('h:i A') }}
 </td>
 </tr>
 @empty
 <tr>
 <td colspan="5" class="py-8 text-center text-default-400 text-xs">
 لا توجد معاملات مسجلة في هذه الوردية حتى الآن.
 </td>
 </tr>
 @endforelse
 </tbody>
 </table>
 </div>
 </div>

 {{-- Close Shift Modal --}}
 <div id="close-shift-modal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
 <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-default-100">
 <div class="flex items-center justify-between mb-4 pb-3 border-b border-default-100">
 <div>
 <h3 class="text-base font-bold text-default-900">تقفيل وتسليم الوردية</h3>
 <p class="text-xs text-default-400">مطابقة الكاش الفعلي في الدرج مع الحسابات المسجلة</p>
 </div>
 <button type="button" onclick="document.getElementById('close-shift-modal').classList.add('hidden')" class="size-8 rounded-lg bg-default-100 hover:bg-default-200 text-default-500 flex items-center justify-center transition-all">
 <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
 </button>
 </div>

 <form method="POST" action="{{ route('shifts.close', $shift->id) }}">
 @csrf
 <div class="space-y-4">
 <div class="p-3.5 rounded-xl bg-default-50 border border-default-100 flex items-center justify-between">
 <span class="text-xs font-bold text-default-600">النقدية المتوقعة في الدرج:</span>
 <span class="text-base font-black text-emerald-600 font-mono">{{ number_format($stats['expected_cash'], 2) }} ج.م</span>
 </div>

 <div>
 <label class="block text-xs font-bold text-default-700 mb-1.5">الكاش الفعلي في الدرج (بالعد) *</label>
 <div class="relative">
 <input type="number" step="0.5" name="actual_cash" required placeholder="أدخل المبلغ الفعلي بعد عد الدرج"
 class="w-full px-3.5 py-2.5 border border-default-200 rounded-xl text-sm font-bold font-mono focus:border-primary outline-none">
 <span class="absolute end-3 top-2.5 text-xs text-default-400 font-bold">ج.م</span>
 </div>
 </div>

 <div>
 <label class="block text-xs font-bold text-default-700 mb-1.5">ملاحظات الإغلاق والتسليم</label>
 <textarea name="closing_notes" rows="2" placeholder="أي ملاحظات حول العجز، الزيادة أو تسليم العهدة للكاشير التالي..."
 class="w-full px-3.5 py-2.5 border border-default-200 rounded-xl text-xs focus:border-primary outline-none"></textarea>
 </div>

 <div class="flex items-center justify-end gap-2.5 pt-2">
 <button type="button" onclick="document.getElementById('close-shift-modal').classList.add('hidden')"
 class="px-4 py-2.5 bg-default-100 hover:bg-default-200 text-default-700 rounded-xl text-xs font-bold transition-all">إلغاء</button>
 <button type="submit"
 class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-rose-600/25">
 تأكيد التقفيل وتصفية الوردية
 </button>
 </div>
 </div>
 </form>
 </div>
 </div>

 @else
 {{-- No Active Shift State --}}
 <div class="dt-card p-10 bg-white text-center max-w-md mx-auto my-8">
 <div class="size-16 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-4">
 <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
 <circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/>
 </svg>
 </div>
 <h3 class="text-lg font-black text-default-900 mb-1">لا توجد وردية مفتوحة حالياً</h3>
 <p class="text-xs text-default-400 mb-6">يرجى فتح وردية جديدة وبدء استلام عهدة الدرج لتسجيل المعاملات المالية.</p>

 <form method="POST" action="{{ route('shifts.open') }}" class="text-start">
 @csrf
 <div class="mb-4">
 <label class="block text-xs font-bold text-default-700 mb-1.5">عهدة بداية الوردية (الكاش المستلم) *</label>
 <div class="relative">
 <input type="number" step="0.5" name="opening_cash" value="0" required
 class="w-full px-3.5 py-2.5 border border-default-200 rounded-xl text-sm font-bold font-mono focus:border-primary outline-none">
 <span class="absolute end-3 top-2.5 text-xs text-default-400 font-bold">ج.م</span>
 </div>
 </div>
 <button type="submit" class="w-full py-3 bg-primary hover:bg-primary/90 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-primary/25">
 فتح الوردية وبدء العمل
 </button>
 </form>
 </div>
 @endif

@endsection
