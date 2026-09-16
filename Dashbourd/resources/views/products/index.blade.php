@extends('shared.vertical', ['title' => 'المنتجات والمشروبات — DT-System'])

@section('content')

 {{-- Page Header --}}
 <div class="page-header-container">
 <div>
 <div class="flex items-center gap-2 mb-1">
 <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2.5 py-0.5 rounded-full border border-amber-200">الكافيه والمخزون</span>
 <span class="text-xs text-default-400 font-mono">Cafe & Products</span>
 </div>
 <h2 class="text-2xl font-extrabold text-default-900 tracking-tight">
 المنتجات والمشروبات
 </h2>
 <p class="text-xs text-default-400 mt-1">قائمة الأصناف والمشروبات والوجبات الخفيفة وأسعار البيع والتكلفة</p>
 </div>

 <div class="page-header-actions">
 <button type="button" onclick="document.getElementById('add-product-modal').classList.remove('hidden')"
 class="px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-amber-600/25 gap-2">
 <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
 <line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/>
 </svg>
 <span>إضافة صنف جديد</span>
 </button>
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

 {{-- Stats Cards --}}
 <div class="dt-grid-2">
 <div class="dt-stat-card">
 <div class="dt-stat-info">
 <span class="dt-stat-label">إجمالي الأصناف</span>
 <div class="dt-stat-value">{{ $totalProducts }}</div>
 </div>
 <div class="dt-stat-icon bg-amber-50 text-amber-600">
 <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
 <path d="M17 8h1a4 4 0 1 1 0 8h-1"/><path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z"/><line x1="6" x2="6" y1="2" y2="4"/><line x1="10" x2="10" y1="2" y2="4"/>
 </svg>
 </div>
 </div>

 <div class="dt-stat-card">
 <div class="dt-stat-info">
 <span class="dt-stat-label">الأصناف منخفضة المخزون</span>
 <div class="dt-stat-value text-rose-600">{{ $lowStockCount }}</div>
 </div>
 <div class="dt-stat-icon bg-rose-50 text-rose-600">
 <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
 <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" x2="12" y1="9" y2="13"/>
 </svg>
 </div>
 </div>
 </div>

 {{-- Products Table --}}
 <div class="dt-card">
 <div class="dt-table-responsive">
 <table class="dt-table">
 <thead>
 <tr>
 <th>اسم الصنف</th>
 <th>سعر البيع</th>
 <th>سعر التكلفة</th>
 <th>الكمية بالمخزن</th>
 <th>الحالة</th>
 </tr>
 </thead>
 <tbody>
 @forelse($products as $p)
 <tr>
 <td>
 <div class="font-bold text-default-900">{{ $p->name }}</div>
 </td>
 <td class="font-mono font-bold text-default-900">{{ number_format($p->price, 2) }} ج.م</td>
 <td class="font-mono text-default-500">{{ $p->cost ? number_format($p->cost, 2) . ' ج.م' : '-' }}</td>
 <td class="font-mono font-bold {{ $p->stock_quantity <= 5 ? 'text-rose-600' : 'text-default-700' }}">
 {{ $p->stock_quantity ?? 'غير محدد' }}
 </td>
 <td>
 <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $p->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-default-100 text-default-600' }}">
 {{ $p->is_active ? 'متاح للطلب' : 'غير متاح' }}
 </span>
 </td>
 </tr>
 @empty
 <tr>
 <td colspan="5" class="py-8 text-center text-default-400 text-xs">لا توجد أصناف مضافة حالياً.</td>
 </tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">
 {{ $products->links() }}
 </div>
 </div>

 {{-- Add Product Modal --}}
 <div id="add-product-modal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
 <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-default-100">
 <div class="flex items-center justify-between mb-4 pb-3 border-b border-default-100">
 <h3 class="text-base font-bold text-default-900">إضافة صنف جديد</h3>
 <button type="button" onclick="document.getElementById('add-product-modal').classList.add('hidden')" class="size-8 rounded-lg bg-default-100 hover:bg-default-200 text-default-500 flex items-center justify-center transition-all">
 <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
 </button>
 </div>

 <form method="POST" action="{{ route('products.store') }}">
 @csrf
 <div class="space-y-4">
 <div>
 <label class="block text-xs font-bold text-default-700 mb-1.5">اسم الصنف / المشروب *</label>
 <input type="text" name="name" required placeholder="مثال: قهوة تركي مظبوط"
 class="w-full px-3.5 py-2.5 border border-default-200 rounded-xl text-xs focus:border-primary outline-none">
 </div>
 <div class="grid grid-cols-2 gap-3">
 <div>
 <label class="block text-xs font-bold text-default-700 mb-1.5">سعر البيع *</label>
 <input type="number" step="0.5" name="price" required placeholder="مثال: 30"
 class="w-full px-3.5 py-2.5 border border-default-200 rounded-xl text-xs font-mono focus:border-primary outline-none">
 </div>
 <div>
 <label class="block text-xs font-bold text-default-700 mb-1.5">سعر التكلفة</label>
 <input type="number" step="0.5" name="cost" placeholder="مثال: 12"
 class="w-full px-3.5 py-2.5 border border-default-200 rounded-xl text-xs font-mono focus:border-primary outline-none">
 </div>
 </div>
 <div>
 <label class="block text-xs font-bold text-default-700 mb-1.5">الكمية بالمخزن (الرصيد الافتتاحي)</label>
 <input type="number" name="stock_quantity" value="20"
 class="w-full px-3.5 py-2.5 border border-default-200 rounded-xl text-xs font-mono focus:border-primary outline-none">
 </div>

 <div class="flex items-center justify-end gap-2.5 pt-2">
 <button type="button" onclick="document.getElementById('add-product-modal').classList.add('hidden')"
 class="px-4 py-2.5 bg-default-100 text-default-700 rounded-xl text-xs font-bold">إلغاء</button>
 <button type="submit" class="px-5 py-2.5 bg-amber-600 text-white rounded-xl text-xs font-bold">حفظ الصنف</button>
 </div>
 </div>
 </form>
 </div>
 </div>

@endsection
