<!-- Topbar Start -->
<header class="app-header sticky top-0 z-50 p-4 pb-0 bg-default-50/50 backdrop-blur-sm print:hidden">
    <div class="min-h-topbar flex items-center bg-white rounded-xl shadow-sm border border-default-100">
        <div class="px-5 w-full flex items-center justify-between gap-4">

            <!-- Left Controls: Menu Toggle & Title -->
            <div class="flex items-center gap-3">
                <button aria-label="Toggle navigation"
                        class="relative inline-flex items-center justify-center size-9 rounded-lg bg-default-50 border border-default-200 hover:bg-default-100 text-default-700 transition-all"
                        data-hs-overlay="#app-menu">
                    <svg class="size-5 text-default-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/>
                    </svg>
                </button>

                <div class="hidden sm:flex items-center gap-2 text-xs font-semibold text-default-600 bg-default-50 px-3 py-1.5 rounded-lg border border-default-100">
                    <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>النظام متصل ويعمل</span>
                    <span class="text-default-300">|</span>
                    <span id="topbar-live-clock" class="font-mono text-default-700">--:--:--</span>
                </div>
            </div>

            <!-- Right Controls: Quick Actions & Profile -->
            <div class="flex items-center gap-2.5 sm:gap-3">

                <!-- Open Cashier Quick Button -->
                <a href="{{ url('/cashier') }}" target="_blank"
                   class="inline-flex items-center gap-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 text-xs font-bold px-3 py-1.5 rounded-lg transition-all shadow-sm">
                    <svg class="size-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/>
                    </svg>
                    <span>شاشة الكاشير</span>
                    <span class="bg-emerald-600 text-white text-[9px] px-1.5 py-0.2 rounded-full font-extrabold">POS</span>
                </a>

                <!-- Settings Branding Quick Button -->
                <a href="{{ url('/settings/general') }}"
                   class="hidden md:inline-flex items-center gap-1.5 bg-default-50 hover:bg-default-100 text-default-700 border border-default-200 text-xs font-semibold px-3 py-1.5 rounded-lg transition-all">
                    <svg class="size-4 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="13.5" cy="6.5" r=".5" fill="currentColor"/><circle cx="17.5" cy="10.5" r=".5" fill="currentColor"/><circle cx="8.5" cy="7.5" r=".5" fill="currentColor"/><circle cx="6.5" cy="12.5" r=".5" fill="currentColor"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z"/>
                    </svg>
                    <span>الهوية والألوان</span>
                </a>

                <!-- Profile Dropdown -->
                <div class="hs-dropdown relative inline-flex [--placement:bottom-right]">
                    <button class="hs-dropdown-toggle inline-flex items-center gap-2 p-1 pe-2.5 rounded-xl hover:bg-default-100 transition-all border border-default-100" type="button">
                        <div class="size-8 rounded-lg bg-primary/15 text-primary font-bold flex items-center justify-center text-xs">
                            AD
                        </div>
                        <div class="hidden lg:block text-start">
                            <p class="text-xs font-bold text-default-800 leading-none">{{ auth()->user()->name ?? 'Admin' }}</p>
                            <p class="text-[10px] text-default-400 font-medium">مدير النظام</p>
                        </div>
                        <svg class="size-3.5 text-default-400 hidden lg:inline" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>

                    <div class="hs-dropdown-menu duration mt-2 min-w-56 rounded-xl border border-default-100 bg-white p-2 opacity-0 shadow-xl transition-[opacity,margin] hs-dropdown-open:opacity-100 hidden">
                        <div class="px-3 py-2 border-b border-default-100 mb-1">
                            <p class="text-xs font-bold text-default-800">{{ auth()->user()->name ?? 'Admin' }}</p>
                            <p class="text-[11px] text-default-400">{{ auth()->user()->email ?? 'admin@dt-system.com' }}</p>
                        </div>

                        <a class="flex items-center gap-2.5 py-2 px-3 rounded-lg text-xs font-semibold text-default-700 hover:bg-default-100 hover:text-primary transition-all"
                           href="{{ url('/settings/general') }}">
                            <svg class="size-4 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                            <span>إعدادات النظام واللوجو</span>
                        </a>

                        <a class="flex items-center gap-2.5 py-2 px-3 rounded-lg text-xs font-semibold text-default-700 hover:bg-default-100 hover:text-primary transition-all"
                           href="{{ url('/cashier') }}" target="_blank">
                            <svg class="size-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/></svg>
                            <span>فتح الكاشير في نافذة مستقلة</span>
                        </a>

                        <div class="border-t border-default-100 my-1"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2.5 py-2 px-3 rounded-lg text-xs font-semibold text-rose-600 hover:bg-rose-50 transition-all text-start">
                                <svg class="size-4 text-rose-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                                <span>تسجيل الخروج</span>
                            </button>
                        </form>
                    </div>
                </div>

            </div>

        </div>
    </div>
</header>
<!-- Topbar End -->

<script>
    // Live Topbar Clock
    function updateTopbarClock() {
        const el = document.getElementById('topbar-live-clock');
        if (!el) return;
        const now = new Date();
        el.textContent = now.toLocaleTimeString('ar-EG', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true });
    }
    setInterval(updateTopbarClock, 1000);
    updateTopbarClock();
</script>
