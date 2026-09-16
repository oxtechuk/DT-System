@extends('shared.vertical', ['title' => 'دليل العملاء — DT-System'])

@section('content')

 {{-- Page Header --}}
 <div class="page-header-container">
          
            <h1 class="text-2xl font-extrabold text-[#303334] tracking-tight">
                دليل وسجل العملاء
            </h1>
            <p class="text-xs text-[#73777A] mt-1">إدارة بيانات العملاء، أرقام الهواتف، الأرصدة، وسجل الزيارات والجلسات</p>
        </div>

        <div class="page-header-actions">
            <button type="button" onclick="document.getElementById('add-customer-modal').classList.remove('hidden')"
                    class="px-4 py-2.5 bg-[#4E8F35] hover:bg-[#3F742B] text-white rounded-xl text-xs font-bold transition-all shadow-xs gap-2 flex items-center">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/>
                </svg>
                <span>إضافة عميل جديد</span>
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
 <div class="dt-grid-3">
 {{-- Total Customers --}}
 <div class="dt-stat-card">
 <div class="dt-stat-info">
 <span class="dt-stat-label">إجمالي العملاء</span>
 <div class="dt-stat-value">{{ $totalCustomers }}</div>
 </div>
 <div class="dt-stat-icon bg-blue-50 text-blue-600">
 <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
 <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
 </svg>
 </div>
 </div>

 {{-- Active Customers --}}
 <div class="dt-stat-card">
 <div class="dt-stat-info">
 <span class="dt-stat-label">العملاء النشطون</span>
 <div class="dt-stat-value text-emerald-600">{{ $activeCustomers }}</div>
 </div>
 <div class="dt-stat-icon bg-emerald-50 text-emerald-600">
 <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
 <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
 </svg>
 </div>
 </div>

 {{-- Cashier Shortcut --}}
 <div class="dt-stat-card">
 <div class="dt-stat-info">
 <span class="dt-stat-label">شاشة الكاشير السريع</span>
 <a href="{{ url('/cashier') }}" target="_blank" class="text-xs font-bold text-primary hover:underline mt-1 block">
 بدء جلسة لعميل بالكاشير ↗
 </a>
 </div>
 <div class="dt-stat-icon bg-purple-50 text-purple-600">
 <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
 <rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/>
 </svg>
 </div>
 </div>
 </div>

 {{-- Search and Table --}}
 <div class="dt-card">
 <div class="flex flex-col sm:flex-row items-center justify-between gap-3 mb-4">
 <form method="GET" action="{{ route('customers.index') }}" class="w-full sm:w-80">
 <div class="relative">
 <input type="text" name="search" value="{{ $search }}" placeholder="ابحث بالاسم أو رقم الهاتف..."
 class="w-full px-3.5 py-2 pe-10 border border-default-200 rounded-xl text-xs focus:border-primary focus:ring-1 focus:ring-primary outline-none">
 <button type="submit" class="absolute end-3 top-2 text-default-400 hover:text-default-700">
 <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
 <circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/>
 </svg>
 </button>
 </div>
 </form>
 </div>

 <div class="dt-table-responsive">
 <table class="dt-table">
 <thead>
 <tr>
 <th>الاسم</th>
 <th>رقم الهاتف</th>
 <th>البريد الإلكتروني</th>
 <th>عدد الجلسات</th>
 <th>الحالة</th>
 </tr>
 </thead>
 <tbody>
 @forelse($customers as $c)
 <tr>
 <td>
 <div class="font-bold text-default-900">{{ $c->name }}</div>
 </td>
 <td class="font-mono text-default-700 font-semibold" dir="ltr">{{ $c->phone }}</td>
 <td class="text-default-500 font-mono">{{ $c->email ?? '-' }}</td>
 <td class="font-bold font-mono">{{ $c->deals_count }}</td>
 <td>
 <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $c->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-default-100 text-default-600' }}">
 {{ $c->status === 'active' ? 'نشط' : 'معطل' }}
 </span>
 </td>
 </tr>
 @empty
 <tr>
 <td colspan="5" class="py-8 text-center text-default-400 text-xs">لا يوجد عملاء مسجلين حالياً.</td>
 </tr>
 @endforelse
 </tbody>
 </table>
 </div>

 <div class="mt-4">
 {{ $customers->links() }}
 </div>
 </div>

 {{-- Add Customer Modal --}}
 <div id="add-customer-modal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
 <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-default-100">
 <div class="flex items-center justify-between mb-4 pb-3 border-b border-default-100">
 <h3 class="text-base font-bold text-default-900">إضافة عميل جديد</h3>
 <button type="button" onclick="document.getElementById('add-customer-modal').classList.add('hidden')" class="size-8 rounded-lg bg-default-100 hover:bg-default-200 text-default-500 flex items-center justify-center transition-all">
 <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
 </button>
 </div>

 <form method="POST" action="{{ route('customers.store') }}">
 @csrf
 <div class="space-y-4">
 <div>
 <label class="block text-xs font-bold text-default-700 mb-1.5">اسم العميل *</label>
 <input type="text" name="name" required placeholder="مثال: أحمد محمد"
 class="w-full px-3.5 py-2.5 border border-default-200 rounded-xl text-xs focus:border-primary outline-none">
 </div>
 <div>
 <label class="block text-xs font-bold text-default-700 mb-1.5">رقم الهاتف *</label>
 <input type="text" name="phone" required placeholder="مثال: 01012345678" dir="ltr"
 class="w-full px-3.5 py-2.5 border border-default-200 rounded-xl text-xs focus:border-primary outline-none text-start">
 </div>
 <div>
 <label class="block text-xs font-bold text-default-700 mb-1.5">البريد الإلكتروني (اختياري)</label>
 <input type="email" name="email" placeholder="example@domain.com" dir="ltr"
 class="w-full px-3.5 py-2.5 border border-default-200 rounded-xl text-xs focus:border-primary outline-none text-start">
 </div>
 <div>
 <label class="block text-xs font-bold text-default-700 mb-1.5">ملاحظات</label>
 <textarea name="notes" rows="2" placeholder="أي ملاحظات حول تفضيلات العميل..."
 class="w-full px-3.5 py-2.5 border border-default-200 rounded-xl text-xs focus:border-primary outline-none"></textarea>
 </div>
 <div class="flex items-center justify-end gap-2.5 pt-2">
 <button type="button" onclick="document.getElementById('add-customer-modal').classList.add('hidden')"
 class="px-4 py-2.5 bg-default-100 text-default-700 rounded-xl text-xs font-bold">إلغاء</button>
 <button type="submit" class="px-5 py-2.5 bg-primary text-white rounded-xl text-xs font-bold">حفظ العميل</button>
 </div>
 </div>
 </form>
 </div>
 </div>

@endsection
