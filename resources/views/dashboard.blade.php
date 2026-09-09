<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم — نظام إدارة مساحة العمل</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #0b0f19;
            --bg-card: rgba(18, 24, 38, 0.85);
            --border-glow: rgba(99, 102, 241, 0.25);
            --accent-primary: #6366f1;
            --accent-cyan: #06b6d4;
            --accent-emerald: #10b981;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Cairo', 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(99, 102, 241, 0.15) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(6, 182, 212, 0.12) 0%, transparent 40%);
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem 2.5rem;
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .brand-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 50%, #ec4899 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(99, 102, 241, 0.4);
        }

        .brand-text h1 {
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            background: linear-gradient(to left, #fff, #cbd5e1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .brand-text p {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .user-nav {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .badge-role {
            background: rgba(99, 102, 241, 0.15);
            border: 1px solid rgba(99, 102, 241, 0.3);
            color: #a5b4fc;
            padding: 0.3rem 0.8rem;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .btn-logout {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.25);
            color: #fca5a5;
            padding: 0.5rem 1.1rem;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }

        .btn-logout:hover {
            background: rgba(239, 68, 68, 0.2);
            border-color: #ef4444;
            transform: translateY(-1px);
        }

        .container {
            max-width: 1200px;
            margin: 2.5rem auto;
            padding: 0 1.5rem;
            width: 100%;
            flex: 1;
        }

        .welcome-card {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(15, 23, 42, 0.9));
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 2.25rem;
            margin-bottom: 2rem;
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5);
            position: relative;
            overflow: hidden;
        }

        .welcome-card::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 250px;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(99, 102, 241, 0.08));
            pointer-events: none;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 1.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .stat-card:hover {
            border-color: var(--accent-primary);
            transform: translateY(-3px);
            box-shadow: 0 12px 24px -8px rgba(99, 102, 241, 0.25);
        }

        .stat-label {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            color: #fff;
        }

        .api-info {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 16px;
            padding: 1.75rem;
        }

        .code-pill {
            display: inline-block;
            background: rgba(0, 0, 0, 0.4);
            padding: 0.3rem 0.6rem;
            border-radius: 6px;
            font-family: monospace;
            color: #38bdf8;
            font-size: 0.9rem;
            direction: ltr;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="brand">
            <div class="brand-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                    <line x1="3" y1="9" x2="21" y2="9"/>
                    <line x1="9" y1="21" x2="9" y2="9"/>
                </svg>
            </div>
            <div class="brand-text">
                <h1>Workspace Hub</h1>
                <p>نظام إدارة مساحات العمل المتكامل</p>
            </div>
        </div>

        <div class="user-nav">
            <span class="badge-role">{{ auth()->user()->roles->first()?->name ?? 'مستخدم' }}</span>
            <span style="font-weight: 600;">{{ auth()->user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn-logout">تسجيل الخروج</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <div class="welcome-card">
            <h2 style="font-size: 1.75rem; font-weight: 800; margin-bottom: 0.5rem;">مرحباً بك مجدداً، {{ auth()->user()->name }} 👋</h2>
            <p style="color: var(--text-muted); font-size: 0.95rem;">تم تسجيل الدخول بنجاح إلى واجهة التحكم. يمكنك الوصول لجميع خدمات النظام ومسارات الـ REST API من هنا.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">إجمالي العملاء المسجلين</div>
                <div class="stat-value">{{ \App\Models\Customer::count() }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-label">الغرف والمساحات المتاحة</div>
                <div class="stat-value">{{ \App\Models\Room::active()->count() }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-label">الجلسات المفتوحة حالياً</div>
                <div class="stat-value" style="color: #34d399;">{{ \App\Models\Deal::open()->count() }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-label">المنتجات في الكافيه</div>
                <div class="stat-value">{{ \App\Models\Product::active()->count() }}</div>
            </div>
        </div>

        <div class="api-info">
            <h3 style="font-size: 1.15rem; font-weight: 700; margin-bottom: 0.75rem;">📡 واجهة الـ API النشطة</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.7;">
                جميع نقاط النهاية للـ API تعمل بإصدار <span class="code-pill">/api/v1/</span> وتدعم المصادقة عبر Sanctum Bearer Tokens.
                يمكنك استخدام الـ Endpoints التالية للربط مع تطبيقات الاستقبال وأجهزة الكاشير:
            </p>
            <div style="margin-top: 1rem; display: flex; gap: 0.75rem; flex-wrap: wrap;">
                <span class="code-pill">POST /api/v1/deals</span>
                <span class="code-pill">POST /api/v1/deals/{id}/close</span>
                <span class="code-pill">POST /api/v1/orders/{id}/payments</span>
                <span class="code-pill">GET /api/v1/customers</span>
            </div>
        </div>
    </div>
</body>
</html>
