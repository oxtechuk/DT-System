@extends('shared.vertical', ['title' => 'المدفوعات والإيراد — DT-System'])

@section('content')

 {{-- Page Header --}}
 <div class="page-header-container">
 <div>

 <h2 class="text-2xl font-extrabold text-default-900 tracking-tight">
 سجل المدفوعات والإيرادات
 </h2>
 <p class="text-xs text-default-400 mt-1">سجل تفصيلي لجميع عمليات السداد والتحصيل بمختلف الطرق (كاش، إنستاباي، محفظة)</p>
 </div>

 <div class="page-header-actions">
 <a href="{{ route('shifts.current') }}"
 class="px-4 py-2.5 bg-orange-600 hover:bg-orange-700 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-orange-600/25 gap-2">
 <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
 <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 8 14"/>
 </svg>
 <span>عرض الوردية الحالية والدرج</span>
 </a>
 </div>
 </div>

 {{-- Stats Cards (4 Columns) --}}
 <div class="dt-grid-4">
 <div class="dt-stat-card">
 <div class="dt-stat-info">
 <span class="dt-stat-label">إجمالي التحصيل</span>
 <div class="dt-stat-value">{{ number_format($totalPaid, 2) }} <span class="text-xs font-normal text-default-400">ج.م</span></div>
 </div>
 <div class="dt-stat-icon bg-purple-50 text-purple-600">
 <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
 <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/>
 </svg>
 </div>
 </div>

 <div class="dt-stat-card">
 <div class="dt-stat-info">
 <span class="dt-stat-label">تحصيل كاش</span>
 <div class="dt-stat-value text-emerald-600">{{ number_format($cashTotal, 2) }} <span class="text-xs font-normal text-default-400">ج.م</span></div>
 </div>
 <div class="dt-stat-icon bg-emerald-50 text-emerald-600">
 <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
 <rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/>
 </svg>
 </div>
 </div>

 <div class="dt-stat-card">
 <div class="dt-stat-info">
 <span class="dt-stat-label">تحصيل إنستاباي</span>
 <div class="dt-stat-value text-indigo-600">{{ number_format($instapayTotal, 2) }} <span class="text-xs font-normal text-default-400">ج.م</span></div>
 </div>
 <div class="dt-stat-icon bg-indigo-50 text-indigo-600">
 <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
 <rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>
 </svg>
 </div>
 </div>

 <div class="dt-stat-card">
 <div class="dt-stat-info">
 <span class="dt-stat-label">تحصيل المحافظ</span>
 <div class="dt-stat-value text-amber-600">{{ number_format($walletTotal, 2) }} <span class="text-xs font-normal text-default-400">ج.م</span></div>
 </div>
 <div class="dt-stat-icon bg-amber-50 text-amber-600">
 <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
 <path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/>
 </svg>
 </div>
 </div>
 </div>

 {{-- Payments Table --}}
 <div class="dt-card">
 <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
 <div class="flex items-center gap-2">
 <a href="{{ route('payments.index') }}"
 class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ !$method ? 'bg-primary text-white shadow-sm' : 'bg-default-100 text-default-600 hover:bg-default-200' }}">
 الكل
 </a>
 <a href="{{ route('payments.index', ['method' => 'cash']) }}"
 class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $method === 'cash' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-default-100 text-default-600 hover:bg-default-200' }}">
 كاش
 </a>
 <a href="{{ route('payments.index', ['method' => 'instapay']) }}"
 class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $method === 'instapay' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-default-100 text-default-600 hover:bg-default-200' }}">
 إنستاباي
 </a>
 <a href="{{ route('payments.index', ['method' => 'wallet']) }}"
 class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $method === 'wallet' ? 'bg-amber-600 text-white shadow-sm' : 'bg-default-100 text-default-600 hover:bg-default-200' }}">
 محافظ ذكية
 </a>
 </div>
 </div>

 <div class="dt-table-responsive">
 <table class="dt-table">
 <thead>
 <tr>
 <th>رقم الإيصال</th>
 <th>العميل</th>
 <th>المساحة / الغرفة</th>
 <th>طريقة الدفع</th>
 <th>المبلغ</th>
 <th>تاريخ التحصيل</th>
 </tr>
 </thead>
 <tbody>
 @forelse($payments as $pay)
 <tr>
 <td class="font-mono font-bold text-default-800">#PAY-{{ $pay->id }}</td>
 <td class="font-bold text-default-900">{{ $pay->deal?->customer?->name ?? 'عميل مباشر' }}</td>
 <td class="text-default-500">{{ $pay->deal?->room?->name ?? 'مساحة عمل عامة' }}</td>
 <td>
 @if($pay->method === 'cash')
 <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">كاش نقدي</span>
 @elseif($pay->method === 'instapay')
 <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">إنستاباي</span>
 @elseif($pay->method === 'wallet')
 <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">محفظة</span>
 @else
 <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-default-100 text-default-700">{{ $pay->method }}</span>
 @endif
 </td>
 <td class="font-mono font-black text-default-900 text-sm">
 {{ number_format($pay->amount, 2) }} <span class="text-[10px] font-normal text-default-400">ج.م</span>
 </td>
 <td class="text-default-400 font-mono">{{ $pay->paid_at->format('Y-m-d — h:i A') }}</td>
 </tr>
 @empty
 <tr>
 <td colspan="6" class="py-8 text-center text-default-400 text-xs">لا توجد سجلات مدفوعات مسجلة.</td>
 </tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">
 {{ $payments->links() }}
 </div>
 </div>

@endsection
