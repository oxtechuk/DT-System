@extends('shared.vertical')

@section('title', 'سجل الزيارات')

@section('content')
<div class="container-fluid px-4 py-4" dir="rtl">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('teacher.dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-2">← رجوع</a>
        <div>
            <h1 class="fw-bold mb-0" style="font-size:1.5rem;color:#1e1b4b;">سجل الزيارات</h1>
            <p class="text-muted mb-0">جميع الطلاب الذين سجّلوا حضورهم عبر QR Code</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            @if($visits->isEmpty())
                <div class="text-center py-5">
                    <svg width="70" height="70" fill="none" stroke="#D1D5DB" stroke-width="1.5" viewBox="0 0 24 24" class="mb-3">
                        <path stroke-linecap="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <h6 class="text-muted">لا توجد زيارات بعد</h6>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background:#F9FAFB;">
                            <tr>
                                <th class="px-4 py-3 text-muted fw-semibold" style="font-size:.82rem;">#</th>
                                <th class="px-4 py-3 text-muted fw-semibold" style="font-size:.82rem;">الاسم</th>
                                <th class="px-4 py-3 text-muted fw-semibold" style="font-size:.82rem;">رقم الهاتف</th>
                                <th class="px-4 py-3 text-muted fw-semibold" style="font-size:.82rem;">التاريخ</th>
                                <th class="px-4 py-3 text-muted fw-semibold" style="font-size:.82rem;">الوقت</th>
                                <th class="px-4 py-3 text-muted fw-semibold" style="font-size:.82rem;">الكوميشن</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($visits as $visit)
                            <tr>
                                <td class="px-4 text-muted">{{ $visit->id }}</td>
                                <td class="px-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white flex-shrink-0"
                                             style="width:36px;height:36px;background:linear-gradient(135deg,#4F46E5,#7C3AED);font-size:.85rem;">
                                            {{ mb_strtoupper(mb_substr($visit->visitor_name, 0, 1, 'UTF-8'), 'UTF-8') }}
                                        </div>
                                        <span class="fw-semibold">{{ $visit->visitor_name }}</span>
                                    </div>
                                </td>
                                <td class="px-4">
                                    @if($visit->visitor_phone)
                                        <a href="tel:{{ $visit->visitor_phone }}" class="text-decoration-none text-muted">
                                            {{ $visit->visitor_phone }}
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="px-4">{{ $visit->visited_at->format('Y-m-d') }}</td>
                                <td class="px-4 text-muted">{{ $visit->visited_at->format('h:i A') }}</td>
                                <td class="px-4">
                                    @if($visit->commission)
                                        <span class="badge" style="background:#ECFDF5;color:#059669;border:1px solid #A7F3D0;border-radius:8px;">
                                            {{ number_format($visit->commission->amount, 2) }} ج
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-3">
                    {{ $visits->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
