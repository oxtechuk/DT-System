@extends('shared.vertical')

@section('title', 'QR Code الخاص بي')

@section('content')
<div class="container-fluid px-4 py-4" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            <div class="d-flex align-items-center gap-3 mb-4">
                <a href="{{ route('teacher.dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-2">← رجوع</a>
                <div>
                    <h1 class="fw-bold mb-0" style="font-size:1.5rem;color:#1e1b4b;">QR Code الخاص بك</h1>
                    <p class="text-muted mb-0">شارك هذا الكود مع طلابك لتسجيل حضورهم</p>
                </div>
            </div>

            {{-- QR Card --}}
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header text-center py-4" style="background:linear-gradient(135deg,#4F46E5,#7C3AED);">
                    <h4 class="fw-bold text-white mb-1">{{ $teacher->user->name }}</h4>
                    <small class="text-white opacity-75">مدرس معتمد</small>
                </div>
                <div class="card-body text-center py-5">
                    <div class="d-inline-block p-3 rounded-3 mb-4" style="background:#F9FAFB;border:2px solid #E5E7EB;">
                        <img
                            src="{{ $teacher->qrImageUrl() }}"
                            alt="QR Code"
                            width="220"
                            height="220"
                            style="display:block;"
                            id="qr-img"
                        >
                    </div>

                    <div class="mb-3">
                        <div class="text-muted mb-1" style="font-size:.82rem;">رابط التسجيل</div>
                        <div class="d-flex align-items-center gap-2 justify-content-center">
                            <code class="px-3 py-2 rounded-2" style="background:#F3F4F6;color:#4F46E5;font-size:.85rem;word-break:break-all;" id="scan-url">{{ $teacher->qrUrl() }}</code>
                            <button onclick="copyUrl()" class="btn btn-sm rounded-2 flex-shrink-0" style="background:#EEF2FF;color:#4F46E5;" id="copy-btn" title="نسخ الرابط">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            </button>
                        </div>
                    </div>

                    <div class="mb-4">
                        <span class="badge rounded-pill px-3 py-2" style="background:#EEF2FF;color:#4F46E5;font-size:.85rem;">
                            كود: {{ $teacher->qr_code }}
                        </span>
                    </div>

                    <div class="d-flex gap-2 justify-content-center flex-wrap">
                        <a href="{{ $teacher->qrImageUrl() }}" download="qr-{{ $teacher->qr_code }}.png"
                           class="btn fw-semibold" style="background:linear-gradient(135deg,#4F46E5,#7C3AED);color:white;border-radius:10px;padding:10px 24px;">
                            ⬇️ تحميل QR
                        </a>
                        <button onclick="shareUrl()" class="btn fw-semibold" style="background:#ECFDF5;color:#059669;border:1px solid #A7F3D0;border-radius:10px;padding:10px 24px;">
                            📤 مشاركة الرابط
                        </button>
                    </div>
                </div>
            </div>

            {{-- Instructions --}}
            <div class="card border-0 rounded-4" style="background:#FFFBEB;border:1px solid #FDE68A!important;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3" style="color:#92400E;">📋 كيف يعمل النظام؟</h6>
                    <ol class="mb-0 ps-3" style="color:#78350F;line-height:2;">
                        <li>شارك الرابط أو صورة QR Code مع طلابك</li>
                        <li>الطالب يفتح الرابط على هاتفه ويكتب اسمه ورقم هاتفه</li>
                        <li>يظهر لك تلقائياً في قائمة الزيارات</li>
                        <li>يُحتسب كوميشن تلقائياً عن كل زيارة</li>
                    </ol>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function copyUrl() {
    const url = document.getElementById('scan-url').textContent.trim();
    navigator.clipboard.writeText(url).then(() => {
        const btn = document.getElementById('copy-btn');
        btn.innerHTML = '✅';
        setTimeout(() => {
            btn.innerHTML = '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>';
        }, 2000);
    });
}

function shareUrl() {
    const url = document.getElementById('scan-url').textContent.trim();
    if (navigator.share) {
        navigator.share({ title: 'سجّل حضورك', url: url });
    } else {
        copyUrl();
        alert('تم نسخ الرابط! يمكنك مشاركته الآن.');
    }
}
</script>
@endsection
