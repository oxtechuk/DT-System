@extends('shared.vertical')

@section('title', 'كوميشن المدرس')

@section('content')
<div class="container-fluid px-4 py-4" dir="rtl">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('teacher.dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-2">← رجوع</a>
        <div>
            <h1 class="fw-bold mb-0" style="font-size:1.5rem;color:#1e1b4b;">الكوميشن</h1>
            <p class="text-muted mb-0">تفاصيل كوميشنك المستحق والمدفوع</p>
        </div>
    </div>

    {{-- Summary --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="p-4 rounded-3" style="background:linear-gradient(135deg,#ECFDF5,#D1FAE5);border:1px solid #A7F3D0;">
                <div class="text-muted mb-1" style="font-size:.85rem;">إجمالي المدفوع</div>
                <div class="fw-bold" style="font-size:2rem;color:#059669;">
                    {{ number_format($totalPaid, 2) }} <small style="font-size:1rem;">ج</small>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="p-4 rounded-3" style="background:linear-gradient(135deg,#FFFBEB,#FEF3C7);border:1px solid #FDE68A;">
                <div class="text-muted mb-1" style="font-size:.85rem;">معلّق (لم يُدفع بعد)</div>
                <div class="fw-bold" style="font-size:2rem;color:#D97706;">
                    {{ number_format($totalPending, 2) }} <small style="font-size:1rem;">ج</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            @if($commissions->isEmpty())
                <div class="text-center py-5">
                    <p class="text-muted">لا يوجد كوميشن مسجّل بعد</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background:#F9FAFB;">
                            <tr>
                                <th class="px-4 py-3 text-muted fw-semibold" style="font-size:.82rem;">الزيارة</th>
                                <th class="px-4 py-3 text-muted fw-semibold" style="font-size:.82rem;">الزائر</th>
                                <th class="px-4 py-3 text-muted fw-semibold" style="font-size:.82rem;">قيمة الزيارة</th>
                                <th class="px-4 py-3 text-muted fw-semibold" style="font-size:.82rem;">النسبة</th>
                                <th class="px-4 py-3 text-muted fw-semibold" style="font-size:.82rem;">المبلغ</th>
                                <th class="px-4 py-3 text-muted fw-semibold" style="font-size:.82rem;">الحالة</th>
                                <th class="px-4 py-3 text-muted fw-semibold" style="font-size:.82rem;">تاريخ الدفع</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($commissions as $commission)
                            <tr>
                                <td class="px-4 text-muted">#{{ $commission->id }}</td>
                                <td class="px-4 fw-semibold">{{ $commission->visit?->visitor_name ?? '—' }}</td>
                                <td class="px-4 text-muted">{{ number_format($commission->visit_base_value_snapshot, 2) }} ج</td>
                                <td class="px-4 text-muted">{{ $commission->commission_rate_snapshot }}%</td>
                                <td class="px-4">
                                    <span class="fw-bold" style="color:#059669;">{{ number_format($commission->amount, 2) }} ج</span>
                                </td>
                                <td class="px-4">
                                    <span class="badge px-3 py-2 {{ $commission->status_badge_class }}" style="border-radius:8px;font-size:.8rem;">
                                        {{ $commission->status_label }}
                                    </span>
                                </td>
                                <td class="px-4 text-muted">
                                    {{ $commission->paid_at ? $commission->paid_at->format('Y-m-d') : '—' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-3">
                    {{ $commissions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
