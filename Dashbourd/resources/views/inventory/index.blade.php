@extends('shared.vertical', ['title' => 'حركة المخزون — DT-System'])

@section('content')

 {{-- Page Header --}}
 <div class="page-header-container">
 <div>
 <div class="flex items-center gap-2 mb-1">
 <span class="text-xs font-bold text-purple-600 bg-purple-50 px-2.5 py-0.5 rounded-full border border-purple-200">إدارة المستودع</span>
 <span class="text-xs text-default-400 font-mono">Stock Inventory</span>
 </div>
 <h2 class="text-2xl font-extrabold text-default-900 tracking-tight">
 حركة وأرصدة المخزون
 </h2>
 <p class="text-xs text-default-400 mt-1">تتبع مستويات المخزون، تنبيهات النواقص، والأصناف التي قاربت على النفاد</p>
 </div>

 <div class="page-header-actions">
 <a href="{{ route('products.index') }}"
 class="px-4 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-primary/25 gap-2">
 <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
 <line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/>
 </svg>
 <span>إدارة الأصناف والمنتجات</span>
 </a>
 </div>
 </div>

 @if($lowStock->count() > 0)
 <div class="dt-card border-rose-200 bg-rose-50/40 mb-6">
 <div class="flex items-center gap-3 mb-2">
 <div class="size-8 rounded-lg bg-rose-500 text-white flex items-center justify-center shrink-0">
 <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
 <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" x2="12" y1="9" y2="13"/>
 </svg>
 </div>
 <div>
 <h3 class="text-sm font-bold text-rose-900">تنبيه نواقص المخزون</h3>
 <p class="text-xs text-rose-700">هناك {{ $lowStock->count() }} أصناف اقتربت من النفاد (أقل من 5 قطع)</p>
 </div>
 </div>
 <div class="flex flex-wrap gap-2 mt-3">
 @foreach($lowStock as $ls)
 <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-white border border-rose-200 text-xs font-bold text-rose-800 shadow-sm">
 {{ $ls->name }}
 <span class="bg-rose-100 text-rose-700 px-1.5 py-0.5 rounded font-mono">{{ $ls->stock_quantity }} فقط</span>
 </span>
 @endforeach
 </div>
 </div>
 @endif

 {{-- Inventory Table --}}
 <div class="dt-card">
 <div class="dt-table-responsive">
 <table class="dt-table">
 <thead>
 <tr>
 <th>اسم الصنف</th>
 <th>الكمية الحالية بالمستودع</th>
 <th>سعر البيع</th>
 <th>حالة المخزون</th>
 </tr>
 </thead>
 <tbody>
 @forelse($products as $prod)
 <tr>
 <td class="font-bold text-default-900">{{ $prod->name }}</td>
 <td class="font-mono font-bold text-sm {{ $prod->stock_quantity <= 5 ? 'text-rose-600' : 'text-emerald-600' }}">
 {{ $prod->stock_quantity }}
 </td>
 <td class="font-mono font-bold text-default-800">{{ number_format($prod->price, 2) }} ج.م</td>
 <td>
 @if($prod->stock_quantity <= 0)
 <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">نفد المخزون</span>
 @elseif($prod->stock_quantity <= 5)
 <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">منخفض جداً</span>
 @else
 <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">متوفر</span>
 @endif
 </td>
 </tr>
 @empty
 <tr>
 <td colspan="4" class="py-8 text-center text-default-400 text-xs">لا توجد أصناف خاضعة لإدارة المخزون.</td>
 </tr>
 @endforelse
 </tbody>
 </table>
 </div>
 </div>

@endsection
