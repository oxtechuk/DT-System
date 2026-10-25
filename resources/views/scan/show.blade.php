<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الحضور — {{ $teacher->user->name }}</title>
    <meta name="description" content="سجّل حضورك مع {{ $teacher->user->name }}">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary: #4F46E5;
            --primary-dark: #3730A3;
            --primary-light: #EEF2FF;
            --success: #059669;
            --surface: #ffffff;
            --bg: #F0F4FF;
            --text: #1e1b4b;
            --muted: #6B7280;
            --border: #E0E7FF;
            --radius: 16px;
            --shadow: 0 20px 60px rgba(79,70,229,.15);
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .card {
            background: var(--surface);
            border-radius: 24px;
            box-shadow: var(--shadow);
            width: 100%;
            max-width: 420px;
            overflow: hidden;
            animation: slideUp .5s ease;
        }

        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to   { transform: translateY(0);    opacity: 1; }
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary) 0%, #7C3AED 100%);
            padding: 32px 28px;
            text-align: center;
            color: white;
        }

        .teacher-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255,255,255,.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 800;
            margin: 0 auto 16px;
            border: 3px solid rgba(255,255,255,.4);
        }

        .card-header h1 {
            font-size: 1.4rem;
            font-weight: 800;
            margin-bottom: 4px;
        }

        .card-header p {
            font-size: .9rem;
            opacity: .85;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,.15);
            border-radius: 20px;
            padding: 4px 14px;
            font-size: .8rem;
            margin-top: 10px;
        }

        .card-body { padding: 28px; }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-weight: 600;
            font-size: .9rem;
            color: var(--text);
            margin-bottom: 8px;
        }

        .required-star { color: #EF4444; }

        input[type="text"],
        input[type="tel"] {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid var(--border);
            border-radius: var(--radius);
            font-family: 'Cairo', sans-serif;
            font-size: 1rem;
            color: var(--text);
            background: var(--bg);
            transition: border-color .2s, box-shadow .2s;
            outline: none;
        }

        input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(79,70,229,.1);
            background: white;
        }

        .btn-submit {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--primary) 0%, #7C3AED 100%);
            color: white;
            border: none;
            border-radius: var(--radius);
            font-family: 'Cairo', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            cursor: pointer;
            transition: transform .15s, box-shadow .15s;
            margin-top: 8px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79,70,229,.35);
        }

        .btn-submit:active { transform: translateY(0); }

        .hint {
            text-align: center;
            color: var(--muted);
            font-size: .8rem;
            margin-top: 16px;
            line-height: 1.6;
        }

        .error-msg {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            border-radius: 10px;
            padding: 10px 14px;
            color: #B91C1C;
            font-size: .85rem;
            margin-bottom: 14px;
        }
    </style>
</head>
<body>
<div class="card">
    <div class="card-header">
        <div class="teacher-avatar">
            {{ mb_strtoupper(mb_substr($teacher->user->name, 0, 1, 'UTF-8'), 'UTF-8') }}
        </div>
        <h1>{{ $teacher->user->name }}</h1>
        <p>سجّل حضورك الآن</p>
        <div class="badge">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><circle cx="12" cy="11" r="3"/></svg>
            تسجيل الحضور
        </div>
    </div>

    <div class="card-body">
        @if($errors->any())
            <div class="error-msg">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form id="scan-form" action="{{ route('scan.register', $teacher->qr_code) }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="visitor_name">
                    الاسم الكامل <span class="required-star">*</span>
                </label>
                <input
                    type="text"
                    id="visitor_name"
                    name="visitor_name"
                    placeholder="مثال: أحمد محمد"
                    value="{{ old('visitor_name') }}"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label for="visitor_phone">
                    رقم الهاتف <span style="color: var(--muted); font-weight: 400;">(اختياري)</span>
                </label>
                <input
                    type="tel"
                    id="visitor_phone"
                    name="visitor_phone"
                    placeholder="مثال: 0501234567"
                    value="{{ old('visitor_phone') }}"
                >
            </div>

            <button type="submit" class="btn-submit" id="submit-btn">
                ✅ سجّل حضوري الآن
            </button>
        </form>

        <p class="hint">
            بتسجيل حضورك، أنت توافق على حفظ بياناتك<br>لأغراض الحضور والمتابعة فقط
        </p>
    </div>
</div>

<script>
    document.getElementById('scan-form').addEventListener('submit', function() {
        const btn = document.getElementById('submit-btn');
        btn.textContent = '⏳ جاري التسجيل...';
        btn.disabled = true;
    });
</script>
</body>
</html>
