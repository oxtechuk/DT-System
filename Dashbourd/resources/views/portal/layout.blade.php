<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'DDT WORKING SPACE — أكثر من مكان.. مجتمع بيكبر معاك')</title>
    <meta name="theme-color" content="#FFFFFF">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <link rel="manifest" href="/manifest.json">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts: Cairo -->
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
                        primary: {
                            DEFAULT: '#4E8F35',
                            50: '#F5F9F2',
                            100: '#EBF4E8',
                            200: '#DCE8D4',
                            300: '#A9D68A',
                            400: '#79B84A',
                            500: '#4E8F35',
                            600: '#3F742B',
                            700: '#325C22',
                        },
                        ddt: {
                            green: '#4E8F35',
                            light: '#79B84A',
                            sage: '#DCE8D4',
                            charcoal: '#303334',
                            offwhite: '#F5F3EE',
                            gray: '#73777A',
                            border: '#E5E2DC',
                        }
                    },
                    fontFamily: {
                        sans: ['Cairo', 'sans-serif'],
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
            background-color: #EFECE6;
            color: #303334;
        }
        ::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Pure Light Style Solid Cards */
        .solid-card {
            background-color: #FFFFFF;
            border: 1px solid #E5E2DC;
            box-shadow: 0 1px 3px rgba(48, 51, 52, 0.05);
        }
        .solid-card-elevated {
            background-color: #FFFFFF;
            border: 1px solid #E5E2DC;
            box-shadow: 0 4px 16px rgba(48, 51, 52, 0.06);
        }
        .solid-card-sage {
            background-color: #F7FAF5;
            border: 1px solid #DCE8D4;
        }
        .solid-card-green {
            background: linear-gradient(135deg, #4E8F35 0%, #3F742B 100%);
            color: #FFFFFF;
            border: 1px solid #4E8F35;
        }
        .solid-card-charcoal {
            background-color: #303334;
            color: #FFFFFF;
            border: 1px solid #242627;
        }

        .nav-item-active {
            color: #4E8F35 !important;
            font-weight: 800;
        }
        .nav-item-active svg {
            stroke: #4E8F35 !important;
            stroke-width: 2.2 !important;
        }
    </style>
    @yield('styles')
</head>
<body class="min-h-screen text-[#303334] flex justify-center selection:bg-primary-500 selection:text-white pb-24">

    <!-- Mobile Shell Container (Solid Light Architecture) -->
    <div class="w-full max-w-md min-h-screen flex flex-col bg-[#F5F3EE] shadow-xl relative border-x border-[#E5E2DC]">

        <!-- Top Header Bar (Solid White) -->
        <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-none px-4 py-3 border-b border-[#E5E2DC] flex items-center justify-between shadow-[0_1px_4px_rgba(48,51,52,0.03)]">
            <div class="flex items-center gap-2">
                <a href="{{ route('portal.home') }}" class="flex-shrink-0 flex items-center">
                    <img src="{{ asset('images/ddt-logo.svg') }}" alt="DDT Working Space" class="h-8 object-contain"/>
                </a>
            </div>

            @auth('customer')
                <div class="flex items-center gap-2">
                    <!-- Orders / Receipt Quick Icon -->
                    <a href="{{ route('portal.orders') }}" title="طلباتي وسجل المشتريات"
                        class="w-8 h-8 rounded-full bg-[#F5F3EE] border border-[#E5E2DC] flex items-center justify-center text-[#73777A] hover:text-[#4E8F35] hover:border-primary-300 transition active:scale-95 relative">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        @php
                            $openOrdersCount = \App\Models\Order::where('customer_id', Auth::guard('customer')->id())
                                ->whereIn('fulfillment_status', ['pending', 'preparing'])
                                ->count();
                        @endphp
                        @if($openOrdersCount > 0)
                            <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-[#4E8F35] text-white text-[9px] font-black flex items-center justify-center">
                                {{ $openOrdersCount }}
                            </span>
                        @endif
                    </a>

                    <!-- Loyalty visits badge -->
                    <a href="{{ route('portal.loyalty') }}" title="بطاقة الولاء"
                        class="flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#EBF4E8] border border-[#DCE8D4] text-[#4E8F35] text-xs font-bold transition active:scale-95">
                        <img src="{{ asset('images/ddt-flask-icon.svg') }}" class="w-3.5 h-3.5" alt="Flask"/>
                        <span>{{ Auth::guard('customer')->user()->loyalty_status['current_visits'] }}/5</span>
                    </a>

                    <!-- Profile initials -->
                    <a href="{{ route('portal.profile') }}" title="الملف الشخصي"
                        class="w-8 h-8 rounded-full bg-[#303334] text-white flex items-center justify-center text-xs font-black hover:bg-[#4E8F35] transition">
                        {{ Auth::guard('customer')->user()->initials }}
                    </a>
                </div>
            @else
                <a href="{{ route('portal.login') }}" class="px-3.5 py-1.5 rounded-xl bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs shadow-sm transition">
                    تسجيل الدخول
                </a>
            @endauth
        </header>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mx-4 mt-3 p-3.5 rounded-xl bg-[#EBF4E8] border border-[#DCE8D4] text-[#325C22] text-xs flex items-center gap-2.5 shadow-sm">
                <svg class="w-4 h-4 flex-shrink-0 text-[#4E8F35]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mx-4 mt-3 p-3.5 rounded-xl bg-[#FDF2F2] border border-[#F8D7DA] text-[#9E2A2B] text-xs flex items-center gap-2.5 shadow-sm">
                <svg class="w-4 h-4 flex-shrink-0 text-[#D9534F]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Main Content Body -->
        <main class="flex-1 p-4 flex flex-col gap-4">
            @yield('content')
        </main>

        @auth('customer')
            <!-- Mobile App Bottom Navigation Bar (Solid White Light Mode) -->
            <nav class="fixed bottom-0 left-0 right-0 z-50 flex justify-center pointer-events-none">
                <div class="w-full max-w-md pointer-events-auto bg-white border-t border-[#E5E2DC] px-2 py-2 flex items-center justify-around shadow-[0_-4px_20px_rgba(48,51,52,0.06)]">
                    
                    <!-- 1. الرئيسية -->
                    <a href="{{ route('portal.home') }}" class="flex flex-col items-center gap-1 py-1 px-3 rounded-xl transition text-[#73777A] hover:text-[#303334] {{ request()->routeIs('portal.home') ? 'nav-item-active' : '' }}">
                        <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span class="text-[11px] font-semibold">الرئيسية</span>
                    </a>

                    <!-- 2. المشروبات -->
                    <a href="{{ route('portal.menu') }}" class="flex flex-col items-center gap-1 py-1 px-3 rounded-xl transition text-[#73777A] hover:text-[#303334] {{ request()->routeIs('portal.menu') ? 'nav-item-active' : '' }}">
                        <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3"/>
                        </svg>
                        <span class="text-[11px] font-semibold">المشروبات</span>
                    </a>

                    <!-- 3. زر عائم للطلب السريع -->
                    <a href="{{ route('portal.menu') }}" class="-mt-6 flex flex-col items-center group">
                        <div class="w-12 h-12 rounded-full bg-[#4E8F35] hover:bg-[#3F742B] text-white flex items-center justify-center shadow-md shadow-[#4E8F35]/30 border-[3px] border-white group-hover:scale-105 group-active:scale-95 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <span class="text-[10px] font-bold text-[#4E8F35] mt-1">اطلب الآن</span>
                    </a>

                    <!-- 4. مجتمعي (Community Hub) - User explicit request -->
                    <a href="{{ route('portal.community') }}" class="flex flex-col items-center gap-1 py-1 px-3 rounded-xl transition text-[#73777A] hover:text-[#303334] {{ request()->routeIs('portal.community') ? 'nav-item-active' : '' }} relative">
                        <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span class="text-[11px] font-semibold">مجتمعي</span>
                        <span class="absolute top-1 right-2.5 w-1.5 h-1.5 rounded-full bg-[#4E8F35]"></span>
                    </a>

                    <!-- 5. المكافآت والولاء -->
                    <a href="{{ route('portal.loyalty') }}" class="flex flex-col items-center gap-1 py-1 px-3 rounded-xl transition text-[#73777A] hover:text-[#303334] {{ request()->routeIs('portal.loyalty') ? 'nav-item-active' : '' }}">
                        <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                        </svg>
                        <span class="text-[11px] font-semibold">المكافآت</span>
                    </a>

                </div>
            </nav>
        @endauth

    </div>

    <!-- PWA Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(err => console.log('SW failed', err));
            });
        }
    </script>
    @yield('scripts')
</body>
</html>
