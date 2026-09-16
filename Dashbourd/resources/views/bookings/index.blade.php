@extends('shared.vertical', ['title' => 'جدول الحجوزات — DT-System'])

@section('content')

 {{-- Page Header --}}
 <div class="page-header-container">
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-bold text-[#4E8F35] bg-[#EBF4E8] px-2.5 py-0.5 rounded-full border border-[#DCE8D4]">إدارة المواعيد</span>
                <span class="text-xs text-[#73777A] font-mono">Bookings Schedule</span>
            </div>
            <h1 class="text-2xl font-extrabold text-[#303334] tracking-tight">
                جدول ومواعيد الحجوزات
            </h1>
            <p class="text-xs text-[#73777A] mt-1">حجوزات القاعات والمساحات المشتركة والمواعيد المؤكدة</p>
        </div>

        <div class="page-header-actions">
            <button type="button" onclick="document.getElementById('add-booking-modal').classList.remove('hidden')"
                    class="px-4 py-2.5 bg-[#4E8F35] hover:bg-[#3F742B] text-white rounded-xl text-xs font-bold transition-all shadow-xs gap-2 flex items-center">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect width="18" height="18" x="3" y="4" rx="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/>
                </svg>
                <span>تسجيل حجز جديد</span>
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

 {{-- Bookings Table --}}
 <div class="dt-card">
 <div class="dt-table-responsive">
 <table class="dt-table">
 <thead>
 <tr>
 <th>العميل</th>
 <th>الغرفة / المساحة</th>
 <th>من</th>
 <th>إلى</th>
 <th>عدد الحضور</th>
 <th>الحالة</th>
 </tr>
 </thead>
 <tbody>
 @forelse($bookings as $b)
 <tr>
 <td class="font-bold text-default-900">{{ $b->customer->name ?? '-' }}</td>
 <td class="font-semibold text-default-700">{{ $b->room->name ?? '-' }}</td>
 <td class="font-mono text-default-600">{{ $b->start_time }}</td>
 <td class="font-mono text-default-600">{{ $b->end_time }}</td>
 <td class="font-mono font-bold">{{ $b->attendees_count ?? 1 }}</td>
 <td>
 <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
 {{ $b->status === 'confirmed' ? 'مؤكد' : $b->status }}
 </span>
 </td>
 </tr>
 @empty
 <tr>
 <td colspan="6" class="py-8 text-center text-default-400 text-xs">
 لا توجد حجوزات مسجلة حالياً.
 </td>
 </tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">
 {{ $bookings->links() }}
 </div>
 </div>

 {{-- Add Booking Modal --}}
 <div id="add-booking-modal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
 <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-default-100">
 <div class="flex items-center justify-between mb-4 pb-3 border-b border-default-100">
 <h3 class="text-base font-bold text-default-900">تسجيل حجز جديد</h3>
 <button type="button" onclick="document.getElementById('add-booking-modal').classList.add('hidden')" class="size-8 rounded-lg bg-default-100 hover:bg-default-200 text-default-500 flex items-center justify-center transition-all">
 <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
 </button>
 </div>

 <form method="POST" action="{{ route('bookings.store') }}">
 @csrf
 <div class="space-y-4">
 <div>
 <label class="block text-xs font-bold text-default-700 mb-1.5">العميل *</label>
 <select name="customer_id" required class="w-full px-3.5 py-2.5 border border-default-200 rounded-xl text-xs focus:border-primary outline-none">
 <option value="">اختر العميل...</option>
 @foreach($customers as $c)
 <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->phone }})</option>
 @endforeach
 </select>
 </div>

 <div>
 <label class="block text-xs font-bold text-default-700 mb-1.5">الغرفة *</label>
 <select name="room_id" required class="w-full px-3.5 py-2.5 border border-default-200 rounded-xl text-xs focus:border-primary outline-none">
 <option value="">اختر الغرفة...</option>
 @foreach($rooms as $r)
 <option value="{{ $r->id }}">{{ $r->name }} (سعة {{ $r->capacity }})</option>
 @endforeach
 </select>
 </div>

 <div class="grid grid-cols-2 gap-3">
 <div>
 <label class="block text-xs font-bold text-default-700 mb-1.5">من تاريخ / وقت *</label>
 <input type="datetime-local" name="start_time" required class="w-full px-2.5 py-2 border border-default-200 rounded-xl text-xs focus:border-primary outline-none">
 </div>
 <div>
 <label class="block text-xs font-bold text-default-700 mb-1.5">إلى تاريخ / وقت *</label>
 <input type="datetime-local" name="end_time" required class="w-full px-2.5 py-2 border border-default-200 rounded-xl text-xs focus:border-primary outline-none">
 </div>
 </div>

 <div>
 <label class="block text-xs font-bold text-default-700 mb-1.5">ملاحظات الحجز</label>
 <textarea name="notes" rows="2" placeholder="أي طلبات خاصة للحجز..." class="w-full px-3.5 py-2.5 border border-default-200 rounded-xl text-xs focus:border-primary outline-none"></textarea>
 </div>

 <div class="flex items-center justify-end gap-2.5 pt-2">
 <button type="button" onclick="document.getElementById('add-booking-modal').classList.add('hidden')"
 class="px-4 py-2.5 bg-default-100 text-default-700 rounded-xl text-xs font-bold">إلغاء</button>
 <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-bold">تأكيد الحجز</button>
 </div>
 </div>
 </form>
 </div>
 </div>

@endsection
