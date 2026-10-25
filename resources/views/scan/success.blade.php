<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تم التسجيل بنجاح ✅</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #059669 0%, #10B981 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(5,150,105,.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
            padding: 48px 32px;
            animation: bounceIn .6s cubic-bezier(.68,-.55,.265,1.55);
        }

        @keyframes bounceIn {
            from { transform: scale(0.7); opacity: 0; }
            to   { transform: scale(1);   opacity: 1; }
        }

        .check-circle {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #059669, #10B981);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            box-shadow: 0 10px 30px rgba(5,150,105,.3);
        }

        .check-circle svg { width: 50px; height: 50px; }

        h1 {
            font-size: 1.6rem;
            font-weight: 800;
            color: #1e1b4b;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #6B7280;
            font-size: .95rem;
            line-height: 1.7;
            margin-bottom: 28px;
        }

        .teacher-name {
            color: #059669;
            font-weight: 700;
        }

        .time-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #F0FDF4;
            border: 1px solid #BBF7D0;
            border-radius: 20px;
            padding: 8px 20px;
            color: #059669;
            font-weight: 600;
            font-size: .9rem;
            margin-bottom: 28px;
        }

        .btn-back {
            display: inline-block;
            padding: 14px 32px;
            background: #F3F4F6;
            color: #374151;
            border-radius: 14px;
            font-family: 'Cairo', sans-serif;
            font-size: .95rem;
            font-weight: 600;
            text-decoration: none;
            transition: background .2s;
        }

        .btn-back:hover { background: #E5E7EB; }
    </style>
</head>
<body>
<div class="card">
    <div class="check-circle">
        <svg fill="none" stroke="white" stroke-width="3" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
    </div>

    <h1>تم تسجيل حضورك! 🎉</h1>
    <p class="subtitle">
        تم تسجيل زيارتك بنجاح مع<br>
        <span class="teacher-name">{{ $teacher->user->name }}</span>
    </p>

    <div class="time-badge">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 6v6l4 2"/>
        </svg>
        {{ now()->format('h:i A') }} — {{ now()->translatedFormat('l، d F Y') }}
    </div>

    <a href="{{ route('scan.show', $teacher->qr_code) }}" class="btn-back">
        ← العودة
    </a>
</div>
</body>
</html>
