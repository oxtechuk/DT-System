@extends('shared.vertical')

@section('title', 'لوحة تحكم المدرس')

@section('content')
<div class="container-fluid px-4 py-4" dir="rtl">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h1 class="fw-bold mb-1" style="font-size:1.6rem; color:#1e1b4b;">
                مرحباً، {{ $teacher->user->name }} 👋
            </h1>
            <p class="text-muted mb-0">هنا تجد كل زياراتك وكوميشنك بشكل مباشر</p>
        </div>
        <a href="{{ route('teacher.qr-code') }}"
           class="btn d-flex align-items-center gap-2 fw-semibold"
           style="background:linear-gradient(135deg,#4F46E5,#7C3AED);color:white;border-radius:12px;padding:10px 20px;">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
            </svg>
            عرض QR Code
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="p-3 rounded-3 h-100" style="background:linear-gradient(135deg,#EEF2FF,#E0E7FF);border:1px solid #C7D2FE;">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="rounded-2 d-flex align-items-center justify-content-center" style="width:36px;height:36px;background:#4F46E5;">
                        <svg width="18" height="18" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
                    </div>
                    <span class="text-muted" style="font-size:.8rem;">إجمالي الزيارات</span>
                </div>
                <div class="fw-bold" style="font-size:1.8rem;color:#4F46E5;">{{ number_format($stats['total_visits']) }}</div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="p-3 rounded-3 h-100" style="background:linear-gradient(135deg,#ECFDF5,#D1FAE5);border:1px solid #A7F3D0;">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="rounded-2 d-flex align-items-center justify-content-center" style="width:36px;height:36px;background:#059669;">
                        <svg width="18" height="18" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-muted" style="font-size:.8rem;">كوميشن مدفوع</span>
                </div>
                <div class="fw-bold" style="font-size:1.8rem;color:#059669;">{{ number_format($stats['total_earnings'], 0) }} <small style="font-size:.9rem;">ج</small></div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="p-3 rounded-3 h-100" style="background:linear-gradient(135deg,#FFFBEB,#FEF3C7);border:1px solid #FDE68A;">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="rounded-2 d-flex align-items-center justify-content-center" style="width:36px;height:36px;background:#D97706;">
                        <svg width="18" height="18" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-muted" style="font-size:.8rem;">كوميشن معلّق</span>
                </div>
                <div class="fw-bold" style="font-size:1.8rem;color:#D97706;">{{ number_format($stats['pending_commission'], 0) }} <small style="font-size:.9rem;">ج</small></div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="p-3 rounded-3 h-100" style="background:linear-gradient(135deg,#F5F3FF,#EDE9FE);border:1px solid #DDD6FE;">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="rounded-2 d-flex align-items-center justify-content-center" style="width:36px;height:36px;background:#7C3AED;">
                        <svg width="18" height="18" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <span class="text-muted" style="font-size:.8rem;">نسبة الكوميشن</span>
                </div>
                <div class="fw-bold" style="font-size:1.8rem;color:#7C3AED;">{{ $stats['commission_rate'] }}<small style="font-size:.9rem;">%</small></div>
            </div>
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <a href="{{ route('teacher.visits') }}" class="text-decoration-none">
                <div class="p-3 rounded-3 border d-flex align-items-center gap-3 h-100" style="transition:.2s;" onmouseover="this.style.borderColor='#4F46E5'" onmouseout="this.style.borderColor='#dee2e6'">
                    <div class="rounded-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width:44px;height:44px;background:#EEF2FF;">
                        <svg width="22" height="22" fill="none" stroke="#4F46E5" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <div>
                        <div class="fw-semibold" style="color:#1e1b4b;">سجل الزيارات</div>
                        <small class="text-muted">عرض كل الزيارات</small>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('teacher.commissions') }}" class="text-decoration-none">
                <div class="p-3 rounded-3 border d-flex align-items-center gap-3 h-100" style="transition:.2s;" onmouseover="this.style.borderColor='#059669'" onmouseout="this.style.borderColor='#dee2e6'">
                    <div class="rounded-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width:44px;height:44px;background:#ECFDF5;">
                        <svg width="22" height="22" fill="none" stroke="#059669" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <div class="fw-semibold" style="color:#1e1b4b;">الكوميشن</div>
                        <small class="text-muted">المستحق والمدفوع</small>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('teacher.qr-code') }}" class="text-decoration-none">
                <div class="p-3 rounded-3 border d-flex align-items-center gap-3 h-100" style="transition:.2s;" onmouseover="this.style.borderColor='#7C3AED'" onmouseout="this.style.borderColor='#dee2e6'">
                    <div class="rounded-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width:44px;height:44px;background:#F5F3FF;">
                        <svg width="22" height="22" fill="none" stroke="#7C3AED" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    </div>
                    <div>
                        <div class="fw-semibold" style="color:#1e1b4b;">QR Code الخاص بك</div>
                        <small class="text-muted">عرض وتحميل الكود</small>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- Recent Visits --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between py-3 px-4">
            <h5 class="fw-bold mb-0" style="color:#1e1b4b;">آخر الزيارات</h5>
            <a href="{{ route('teacher.visits') }}" class="btn btn-sm" style="background:#EEF2FF;color:#4F46E5;border-radius:8px;font-weight:600;">
                عرض الكل
            </a>
        </div>
        <div class="card-body p-0">
            @if($recentVisits->isEmpty())
                <div class="text-center py-5">
                    <svg width="60" height="60" fill="none" stroke="#D1D5DB" stroke-width="1.5" viewBox="0 0 24 24" class="mb-3">
                        <path stroke-linecap="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                    </svg>
                    <p class="text-muted">لا توجد زيارات بعد. شارك QR Code الخاص بك!</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background:#F9FAFB;">
                            <tr>
                                <th class="px-4 py-3 text-muted fw-semibold" style="font-size:.82rem;">#</th>
                                <th class="px-4 py-3 text-muted fw-semibold" style="font-size:.82rem;">الاسم</th>
                                <th class="px-4 py-3 text-muted fw-semibold" style="font-size:.82rem;">الهاتف</th>
                                <th class="px-4 py-3 text-muted fw-semibold" style="font-size:.82rem;">التاريخ والوقت</th>
                                <th class="px-4 py-3 text-muted fw-semibold" style="font-size:.82rem;">الكوميشن</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentVisits as $i => $visit)
                            <tr>
                                <td class="px-4">{{ $i + 1 }}</td>
                                <td class="px-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white flex-shrink-0"
                                             style="width:34px;height:34px;background:linear-gradient(135deg,#4F46E5,#7C3AED);font-size:.8rem;">
                                            {{ mb_strtoupper(mb_substr($visit->visitor_name, 0, 1, 'UTF-8'), 'UTF-8') }}
                                        </div>
                                        <span class="fw-semibold">{{ $visit->visitor_name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 text-muted">{{ $visit->visitor_phone ?? '—' }}</td>
                                <td class="px-4">
                                    <div style="font-size:.88rem;">{{ $visit->visited_at->format('Y-m-d') }}</div>
                                    <small class="text-muted">{{ $visit->visited_at->format('h:i A') }}</small>
                                </td>
                                <td class="px-4">
                                    @if($visit->commission)
                                        <span class="badge" style="background:#ECFDF5;color:#059669;border:1px solid #A7F3D0;border-radius:8px;font-size:.82rem;">
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
            @endif
        </div>
    </div>
</div>
@endsection
