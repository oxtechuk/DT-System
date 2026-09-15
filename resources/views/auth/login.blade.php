<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول — نظام إدارة مساحات العمل</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-deep: #070913;
            --card-bg: rgba(15, 23, 42, 0.7);
            --card-border: rgba(255, 255, 255, 0.1);
            --card-glow: rgba(99, 102, 241, 0.15);
            --accent-primary: #6366f1;
            --accent-purple: #8b5cf6;
            --accent-cyan: #06b6d4;
            --accent-emerald: #10b981;
            --text-main: #f8fafc;
            --text-secondary: #94a3b8;
            --input-bg: rgba(2, 6, 23, 0.65);
            --input-border: rgba(255, 255, 255, 0.12);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Cairo', 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background-color: var(--bg-deep);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
            padding: 1.5rem;
        }

        /* Ambient glowing background orbs */
        .ambient-orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.45;
            pointer-events: none;
            z-index: 0;
            animation: float 14s infinite ease-in-out alternate;
        }

        .orb-1 {
            top: -10%;
            right: 15%;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, #6366f1 0%, rgba(99, 102, 241, 0) 70%);
        }

        .orb-2 {
            bottom: -15%;
            left: 10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, #06b6d4 0%, rgba(6, 182, 212, 0) 70%);
            animation-duration: 18s;
        }

        .orb-3 {
            top: 40%;
            left: 45%;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, #a855f7 0%, rgba(168, 85, 247, 0) 70%);
            opacity: 0.25;
            animation-duration: 22s;
        }

        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, -40px) scale(1.08); }
            100% { transform: translate(-25px, 35px) scale(0.95); }
        }

        /* Subtle grid pattern */
        .grid-pattern {
            position: fixed;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.07) 1px, transparent 1px);
            background-size: 32px 32px;
            pointer-events: none;
            z-index: 0;
        }

        /* Login Container */
        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 460px;
            margin: auto;
        }

        .login-card {
            background: var(--card-bg);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid var(--card-border);
            border-radius: 28px;
            padding: 2.75rem 2.5rem;
            box-shadow: 
                0 25px 60px -15px rgba(0, 0, 0, 0.7),
                0 0 40px -10px var(--card-glow),
                inset 0 1px 0 rgba(255, 255, 255, 0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        /* Header / Branding */
        .brand-header {
            text-align: center;
            margin-bottom: 2.25rem;
        }

        .brand-logo-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 64px;
            height: 64px;
            border-radius: 20px;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #06b6d4 100%);
            box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.5);
            margin-bottom: 1.25rem;
            position: relative;
        }

        .brand-logo-wrap::after {
            content: '';
            position: absolute;
            inset: -2px;
            border-radius: 22px;
            background: linear-gradient(135deg, #6366f1, transparent, #06b6d4);
            z-index: -1;
            opacity: 0.7;
        }

        .brand-title {
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            background: linear-gradient(135deg, #ffffff 0%, #cbd5e1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.35rem;
        }

        .brand-subtitle {
            font-size: 0.9rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 1.35rem;
            position: relative;
        }

        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #cbd5e1;
            margin-bottom: 0.5rem;
        }

        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            right: 1.1rem;
            color: #64748b;
            pointer-events: none;
            display: flex;
            align-items: center;
            transition: color 0.2s ease;
        }

        .form-control {
            width: 100%;
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 14px;
            padding: 0.85rem 3rem 0.85rem 1.1rem;
            color: var(--text-main);
            font-size: 0.95rem;
            outline: none;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .form-control::placeholder {
            color: #475569;
        }

        .form-control:focus {
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.18);
            background: rgba(15, 23, 42, 0.8);
        }

        .form-control:focus + .input-icon {
            color: var(--accent-primary);
        }

        .password-toggle {
            position: absolute;
            left: 1.1rem;
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
            transition: color 0.2s;
        }

        .password-toggle:hover {
            color: #cbd5e1;
        }

        /* Form Options: Remember Me & Forgot */
        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.75rem;
            font-size: 0.85rem;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            color: #94a3b8;
            user-select: none;
        }

        .remember-label input[type="checkbox"] {
            appearance: none;
            width: 18px;
            height: 18px;
            border: 1px solid var(--input-border);
            border-radius: 6px;
            background: var(--input-bg);
            cursor: pointer;
            position: relative;
            transition: all 0.2s;
        }

        .remember-label input[type="checkbox"]:checked {
            background: var(--accent-primary);
            border-color: var(--accent-primary);
        }

        .remember-label input[type="checkbox"]:checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #fff;
            font-size: 12px;
            font-weight: bold;
        }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            border: none;
            border-radius: 14px;
            padding: 0.95rem 1.5rem;
            color: #ffffff;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.4);
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            transform: translateY(-2px);
            box-shadow: 0 15px 30px -5px rgba(99, 102, 241, 0.5);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* Demo Accounts Box */
        .demo-box {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .demo-title {
            text-align: center;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            margin-bottom: 0.85rem;
        }

        .demo-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }

        .demo-btn {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 12px;
            padding: 0.65rem 0.75rem;
            color: #cbd5e1;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: center;
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
        }

        .demo-btn span.role-title {
            color: #a5b4fc;
            font-weight: 700;
        }

        .demo-btn span.role-email {
            font-size: 0.7rem;
            color: #64748b;
            direction: ltr;
        }

        .demo-btn:hover {
            background: rgba(99, 102, 241, 0.1);
            border-color: rgba(99, 102, 241, 0.3);
            transform: translateY(-1px);
        }

        /* Alert error */
        .alert-error {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            padding: 0.85rem 1.1rem;
            border-radius: 14px;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            animation: shake 0.4s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-4px); }
            40%, 80% { transform: translateX(4px); }
        }

        .system-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: #6ee7b7;
            padding: 0.25rem 0.65rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #10b981;
            box-shadow: 0 0 8px #10b981;
        }
    </style>
</head>
<body>
    <div class="ambient-orb orb-1"></div>
    <div class="ambient-orb orb-2"></div>
    <div class="ambient-orb orb-3"></div>
    <div class="grid-pattern"></div>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="brand-header">
                <div class="system-badge">
                    <span class="badge-dot"></span>
                    <span>نظام التشغيل نشط v1.0</span>
                </div>

                <div>
                    <div class="brand-logo-wrap">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <line x1="3" y1="9" x2="21" y2="9"/>
                            <line x1="9" y1="21" x2="9" y2="9"/>
                        </svg>
                    </div>
                </div>

                <h1 class="brand-title">Workspace Hub</h1>
                <p class="brand-subtitle">تسجيل الدخول لبوابة الإدارة والاستقبال</p>
            </div>

            {{-- Quick Access & Credentials --}}
            <div style="background: rgba(99, 102, 241, 0.1); border: 1px solid rgba(99, 102, 241, 0.3); border-radius: 14px; padding: 1rem; margin-bottom: 1.5rem; font-size: 0.85rem;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="font-weight: 700; color: #a5b4fc;">🔑 بيانات الدخول:</span>
                    <span style="font-size: 0.75rem; background: rgba(16, 185, 129, 0.2); color: #6ee7b7; padding: 0.15rem 0.5rem; border-radius: 9999px;">Admin</span>
                </div>
                <div style="color: #cbd5e1; font-family: monospace; font-size: 0.8rem; line-height: 1.6;">
                    <div>البريد: <strong>admin@workspace.local</strong></div>
                    <div>كلمة المرور: <strong>password</strong></div>
                </div>
                <div style="margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px solid rgba(255, 255, 255, 0.08);">
                    <a href="http://127.0.0.1:8080/" target="_blank" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; padding: 0.55rem; border-radius: 10px; font-weight: 700; text-decoration: none; font-size: 0.8rem; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);">
                        <span>💻 فتح النظام المطور الجديد (Dashbourd & POS)</span>
                        <span>←</span>
                    </a>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert-error">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <div>
                        {{ $errors->first() }}
                    </div>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" id="loginForm">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">البريد الإلكتروني</label>
                    <div class="input-wrap">
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-control" 
                            placeholder="admin@workspace.local" 
                            value="{{ old('email') }}" 
                            required 
                            autofocus
                        >
                        <span class="input-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">كلمة المرور</label>
                    <div class="input-wrap">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-control" 
                            placeholder="••••••••" 
                            required
                        >
                        <span class="input-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </span>
                        <button type="button" class="password-toggle" id="togglePassword" title="إظهار / إخفاء كلمة المرور">
                            <svg id="eyeIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="form-options">
                    <label class="remember-label">
                        <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                        <span>تذكرني على هذا الجهاز</span>
                    </label>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn">
                    <span>تسجيل الدخول</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="5" y1="12" x2="19" y2="12"/>
                        <polyline points="12 5 19 12 12 19"/>
                    </svg>
                </button>
            </form>

            <div class="demo-box">
                <div class="demo-title">حسابات تجريبية سريعة (Demo Accounts)</div>
                <div class="demo-buttons">
                    <button type="button" class="demo-btn" onclick="fillCredentials('admin@workspace.local', 'password')">
                        <span class="role-title">👑 الإدارة (Owner)</span>
                        <span class="role-email">admin@workspace.local</span>
                    </button>
                    <button type="button" class="demo-btn" onclick="fillCredentials('reception@workspace.local', 'password')">
                        <span class="role-title">💼 الاستقبال (Reception)</span>
                        <span class="role-email">reception@workspace.local</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Password toggle
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', function () {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            
            if (isPassword) {
                eyeIcon.innerHTML = `
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                    <line x1="1" y1="1" x2="23" y2="23"/>
                `;
            } else {
                eyeIcon.innerHTML = `
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                `;
            }
        });

        // Quick fill demo accounts
        function fillCredentials(email, password) {
            const emailInput = document.getElementById('email');
            const passInput = document.getElementById('password');
            
            emailInput.value = email;
            passInput.value = password;

            // Highlight animation
            emailInput.style.borderColor = '#6366f1';
            passInput.style.borderColor = '#6366f1';
            setTimeout(() => {
                emailInput.style.borderColor = '';
                passInput.style.borderColor = '';
            }, 600);
        }

        // Form submit animation
        document.getElementById('loginForm').addEventListener('submit', function () {
            const btn = document.getElementById('submitBtn');
            btn.style.opacity = '0.75';
            btn.innerHTML = `<span>جارٍ تسجيل الدخول...</span>`;
        });
    </script>
</body>
</html>
