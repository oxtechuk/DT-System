<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'DDT WORKING SPACE — أكثر من مكان.. مجتمع بيكبر معاك')</title>
    <meta name="theme-color" content="#303334">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts: Cairo (Official Brand Font) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            DEFAULT: '#4E8F35',
                            primary: '#4E8F35',
                            light: '#79B84A',
                            sage: '#DCE8D4',
                            sageLight: '#EBF4E8',
                            charcoal: '#303334',
                            offWhite: '#F5F3EE',
                            gray: '#73777A',
                            border: '#E5E2DC',
                        }
                    },
                    fontFamily: {
                        sans: ['Cairo', 'sans-serif'],
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'scale(0.96)' },
                            '100%': { opacity: '1', transform: 'scale(1)' },
                        },
                        progress: {
                            '0%': { width: '0%' },
                            '100%': { width: '100%' },
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.35s ease-out forwards',
                        'progress': 'progress 0.8s ease-in-out forwards',
                    }
                }
            }
        };
    </script>

    <style>
        * {
            font-family: 'Cairo', system-ui, -apple-system, sans-serif !important;
            -webkit-tap-highlight-color: transparent;
        }
        body {
            background-color: #F5F3EE;
            color: #303334;
        }
        ::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Pure Solid Style Surfaces (Official DDT Brand Guidelines) */
        .solid-card {
            background-color: #FFFFFF;
            border: 1px solid #E5E2DC;
            box-shadow: 0 2px 10px rgba(48, 51, 52, 0.03);
        }
        .solid-card-charcoal {
            background-color: #303334;
            color: #FFFFFF;
            border: 1px solid #303334;
        }
        .solid-card-sage {
            background-color: #EBF4E8;
            border: 1px solid #DCE8D4;
            color: #4E8F35;
        }
        .solid-card-subtle {
            background-color: #F5F3EE;
            border: 1px solid #E5E2DC;
        }

        /* Pill Buttons */
        .pill-btn-forest,
        .pill-btn-primary {
            background-color: #4E8F35;
            color: #FFFFFF;
            border-radius: 9999px;
            font-weight: 800;
            transition: all 0.15s ease;
        }
        .pill-btn-forest:active,
        .pill-btn-primary:active {
            transform: scale(0.97);
            background-color: #3F742B;
        }

        .pill-btn-white {
            background-color: #FFFFFF;
            color: #303334;
            border-radius: 9999px;
            font-weight: 800;
            box-shadow: 0 1px 4px rgba(48, 51, 52, 0.08);
            transition: all 0.15s ease;
        }
        .pill-btn-white:active {
            transform: scale(0.97);
            background-color: #F5F3EE;
        }

        .pill-btn-outline {
            background-color: #FFFFFF;
            color: #303334;
            border: 1px solid #E5E2DC;
            border-radius: 9999px;
            font-weight: 700;
            transition: all 0.15s ease;
        }
        .pill-btn-outline:active {
            transform: scale(0.97);
            background-color: #F5F3EE;
        }
    </style>
    @yield('styles')
    @stack('styles')
</head>
<body class="min-h-screen text-[#303334] flex justify-center selection:bg-[#4E8F35] selection:text-white pb-32">

    <!-- Luxury DDT Splash Screen Overlay (Shown ONLY once on first open) -->
    <div id="app-splash-screen" class="fixed inset-0 z-[100] bg-[#303334] flex flex-col items-center justify-center p-6 text-white select-none transition-opacity duration-500 ease-out">
        <script>
            if (sessionStorage.getItem('ddt_splash_seen')) {
                document.getElementById('app-splash-screen').style.display = 'none';
            }
        </script>
        <div class="flex flex-col items-center gap-5 text-center animate-fade-in">
            <!-- Pulsing Logo Emblem -->
            <div class="relative flex items-center justify-center">
                <div class="absolute -inset-4 rounded-full bg-[#4E8F35]/30 animate-ping opacity-60"></div>
                <div class="w-24 h-24 rounded-3xl bg-gradient-to-tr from-[#222425] to-[#303334] border-2 border-white/20 shadow-2xl flex items-center justify-center relative">
                    <span class="text-3xl font-black tracking-widest text-[#79B84A]">DDT</span>
                </div>
            </div>
            <div class="space-y-1 mt-2">
                <h1 class="text-xl font-black tracking-wide text-white">DDT WORKING SPACE</h1>
                <p class="text-xs text-[#DCE8D4] font-bold">أكثر من مكان.. مجتمع بيكبر معاك</p>
                <p class="text-[10px] text-[#73777A] tracking-wider uppercase">PEOPLE • IDEAS • GROW • TOGETHER</p>
            </div>
            <!-- Progress Bar -->
            <div class="w-32 h-1.5 bg-white/10 rounded-full overflow-hidden mt-3 border border-white/10">
                <div class="h-full bg-gradient-to-r from-[#4E8F35] to-[#79B84A] rounded-full animate-progress"></div>
            </div>
        </div>
    </div>

    <!-- Mobile Shell Container -->
    <div class="w-full max-w-md min-h-screen flex flex-col bg-[#F5F3EE] relative border-x border-[#E5E2DC]">

        <!-- Top Header & Location Context Bar -->
        <header class="sticky top-0 z-40 bg-[#F5F3EE]/95 backdrop-blur-md px-4 py-3 border-b border-[#E5E2DC] flex items-center justify-between">
            <!-- Left/Start: Location & Edit Context -->
            <div class="flex items-center gap-2">
                <!-- Quick Drink Order Action Button -->
                <a href="{{ route('portal.menu') }}" 
                   title="اطلب مشروبك الآن ليصلك إلى مكان جلوسك"
                   class="flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#4E8F35] hover:bg-[#3F742B] text-white text-xs font-black shadow-[0_2px_10px_rgba(78,143,53,0.25)] transition duration-150 active:scale-95 group">
                    <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3"/>
                        </svg>
                    </div>
                    <span>طلب مشروب سريع</span>
                </a>
            </div>

            <!-- Right/End: Status Counter Badge + Saved/Orders Icon + Avatar -->
            <div class="flex items-center gap-2">
                @auth('customer')
                    <!-- Status / Visits Counter Pill -->
                    <a href="{{ route('portal.loyalty') }}" title="زيارات الولاء"
                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-[#EBF4E8] border border-[#DCE8D4] text-[#4E8F35] text-xs font-black transition active:scale-95 shadow-xs">
                        <span class="w-2 h-2 rounded-full bg-[#4E8F35]"></span>
                        <span>{{ Auth::guard('customer')->user()->loyalty_status['current_visits'] ?? 0 }}/5</span>
                    </a>

                   
                    @php
                        $favSetting = \App\Models\Setting::get('favicon');
                        $logoSmSetting = \App\Models\Setting::get('logo_sm');
                        $logoMainSetting = \App\Models\Setting::get('logo_main');
                        $topbarFavicon = $favSetting ?: ($logoSmSetting ?: $logoMainSetting);
                    @endphp

                    <!-- Favicon / Profile Logo Button (Configured from Settings) -->
                    <a href="{{ route('portal.profile') }}" title="الملف الشخصي"
                        class="w-9 h-9 rounded-full bg-white border border-[#E5E2DC] flex items-center justify-center overflow-hidden hover:border-[#4E8F35] transition shadow-xs active:scale-95">
                        @if($topbarFavicon)
                            <img src="{{ asset('storage/'.$topbarFavicon) }}" alt="Favicon" class="w-full h-full object-contain p-1"/>
                        @else
                            <div class="w-full h-full bg-[#303334] text-white flex items-center justify-center text-xs font-black">
                                {{ Auth::guard('customer')->user()->initials }}
                            </div>
                        @endif
                    </a>
                @else
                    @php
                        $favSetting = \App\Models\Setting::get('favicon') ?: \App\Models\Setting::get('logo_sm');
                    @endphp
                    @if($favSetting)
                        <a href="{{ route('portal.login') }}" class="w-9 h-9 rounded-full bg-white border border-[#E5E2DC] flex items-center justify-center overflow-hidden shadow-xs">
                            <img src="{{ asset('storage/'.$favSetting) }}" alt="Logo" class="w-full h-full object-contain p-1"/>
                        </a>
                    @else
                        <a href="{{ route('portal.login') }}" class="px-4 py-2 rounded-full bg-[#4E8F35] hover:bg-[#3F742B] text-white font-bold text-xs shadow-sm transition active:scale-95">
                            تسجيل الدخول
                        </a>
                    @endif
                @endauth
            </div>
        </header>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mx-4 mt-3 p-3.5 rounded-2xl bg-[#EBF4E8] border border-[#DCE8D4] text-[#4E8F35] text-xs flex items-center gap-2.5 shadow-xs">
                <svg class="w-4.5 h-4.5 flex-shrink-0 text-[#4E8F35]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mx-4 mt-3 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs flex items-center gap-2.5 shadow-xs">
                <svg class="w-4.5 h-4.5 flex-shrink-0 text-rose-700" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span class="font-bold">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Main Content Body -->
        <main class="flex-1 p-4 flex flex-col gap-4">
            @yield('content')
        </main>

        @auth('customer')
            <!-- Floating Solid Pill Navigation Island (Official DDT Brand Theme) -->
            <div class="fixed bottom-4 left-0 right-0 z-50 flex justify-center pointer-events-none px-3">
                <nav class="w-full max-w-md pointer-events-auto bg-white/95 backdrop-blur-md border border-[#E5E2DC] shadow-[0_10px_35px_rgba(48,51,52,0.12)] rounded-3xl p-2 flex items-center justify-between gap-1">
                    
                    <!-- 1. الرئيسية -->
                    <a href="{{ route('portal.home') }}" 
                       class="flex-1 flex flex-col items-center justify-center py-2 px-1 rounded-2xl transition-all duration-200 active:scale-95 {{ request()->routeIs('portal.home') ? 'bg-[#EBF4E8] text-[#4E8F35]' : 'text-[#73777A] hover:text-[#303334]' }}">
                        <svg class="w-6 h-6 mb-1 {{ request()->routeIs('portal.home') ? 'stroke-[#4E8F35]' : 'stroke-current' }}" fill="none" stroke-width="{{ request()->routeIs('portal.home') ? '2.5' : '2' }}" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span class="text-xs font-black tracking-tight">الرئيسية</span>
                    </a>

                    <!-- 2. الكافيه -->
                    <a href="{{ route('portal.menu') }}" 
                       class="flex-1 flex flex-col items-center justify-center py-2 px-1 rounded-2xl transition-all duration-200 active:scale-95 {{ request()->routeIs('portal.menu') ? 'bg-[#EBF4E8] text-[#4E8F35]' : 'text-[#73777A] hover:text-[#303334]' }}">
                        <svg class="w-6 h-6 mb-1 {{ request()->routeIs('portal.menu') ? 'stroke-[#4E8F35]' : 'stroke-current' }}" fill="none" stroke-width="{{ request()->routeIs('portal.menu') ? '2.5' : '2' }}" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3"/>
                        </svg>
                        <span class="text-xs font-black tracking-tight">الكافيه</span>
                    </a>

                    <!-- 3. الفعاليات -->
                    <a href="{{ route('portal.community') }}" 
                       class="flex-1 flex flex-col items-center justify-center py-2 px-1 rounded-2xl transition-all duration-200 active:scale-95 {{ request()->routeIs('portal.community') ? 'bg-[#EBF4E8] text-[#4E8F35]' : 'text-[#73777A] hover:text-[#303334]' }}">
                        <svg class="w-6 h-6 mb-1 {{ request()->routeIs('portal.community') ? 'stroke-[#4E8F35]' : 'stroke-current' }}" fill="none" stroke-width="{{ request()->routeIs('portal.community') ? '2.5' : '2' }}" viewBox="0 0 24 24">
                            <rect width="18" height="18" x="3" y="4" rx="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/>
                        </svg>
                        <span class="text-xs font-black tracking-tight">الفعاليات</span>
                    </a>

                    <!-- 4. طلباتي -->
                    <a href="{{ route('portal.orders') }}" 
                       class="flex-1 flex flex-col items-center justify-center py-2 px-1 rounded-2xl transition-all duration-200 active:scale-95 relative {{ request()->routeIs('portal.orders') ? 'bg-[#EBF4E8] text-[#4E8F35]' : 'text-[#73777A] hover:text-[#303334]' }}">
                        <svg class="w-6 h-6 mb-1 {{ request()->routeIs('portal.orders') ? 'stroke-[#4E8F35]' : 'stroke-current' }}" fill="none" stroke-width="{{ request()->routeIs('portal.orders') ? '2.5' : '2' }}" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        <span class="text-xs font-black tracking-tight">طلباتي</span>
                        @php
                            $openOrdersCount = \App\Models\Order::where('customer_id', Auth::guard('customer')->id())
                                ->whereIn('fulfillment_status', ['pending', 'preparing'])
                                ->count();
                        @endphp
                        @if($openOrdersCount > 0)
                            <span class="absolute top-1.5 right-3 w-2.5 h-2.5 rounded-full bg-[#4E8F35] ring-2 ring-white animate-pulse"></span>
                        @endif
                    </a>

                    <!-- 5. الولاء -->
                    <a href="{{ route('portal.loyalty') }}" 
                       class="flex-1 flex flex-col items-center justify-center py-2 px-1 rounded-2xl transition-all duration-200 active:scale-95 {{ request()->routeIs('portal.loyalty') ? 'bg-[#EBF4E8] text-[#4E8F35]' : 'text-[#73777A] hover:text-[#303334]' }}">
                        <svg class="w-6 h-6 mb-1 {{ request()->routeIs('portal.loyalty') ? 'stroke-[#4E8F35]' : 'stroke-current' }}" fill="none" stroke-width="{{ request()->routeIs('portal.loyalty') ? '2.5' : '2' }}" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                        </svg>
                        <span class="text-xs font-black tracking-tight">الولاء</span>
                    </a>

                </nav>
            </div>
        @endauth

    </div>

    <!-- PWA Service Worker Registration -->
    <script>
        // Global Error Boundary for Customer Portal (Prevents script crashes from disabling navigation)
        window.addEventListener('error', function(event) {
            console.warn('[Portal Safe Error Boundary] Caught page script error:', event.message);
        });
        window.addEventListener('unhandledrejection', function(event) {
            console.warn('[Portal Safe Error Boundary] Caught async rejection:', event.reason);
        });

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register("{{ asset('sw.js') }}").catch(err => console.log('SW failed', err));
            });
        }

        // Luxury Splash Screen Dissolve Controller (First Visit Only)
        document.addEventListener('DOMContentLoaded', () => {
            const splash = document.getElementById('app-splash-screen');
            if (splash && splash.style.display !== 'none') {
                setTimeout(() => {
                    splash.classList.add('opacity-0', 'pointer-events-none');
                    sessionStorage.setItem('ddt_splash_seen', 'true');
                    setTimeout(() => splash.remove(), 500);
                }, 550);
            } else if (splash) {
                splash.remove();
            }
        });
    </script>
    @yield('scripts')
    @stack('scripts')
</body>
</html>
