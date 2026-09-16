<!-- Topbar Start (Quiet Unified DDT Design) -->
<header class="app-header sticky top-0 z-50 p-3 pb-0 bg-[#F8F7F4]/80 backdrop-blur-sm print:hidden">
    <div class="min-h-topbar flex items-center bg-white rounded-xl shadow-xs border border-[#E5E2DC]">
        <div class="px-4 w-full flex items-center justify-between gap-4">

            <!-- Left Controls: Menu Toggle & Title -->
            <div class="flex items-center gap-3">
                <button aria-label="Toggle navigation"
                        class="relative inline-flex items-center justify-center size-9 rounded-xl bg-[#F5F3EE] border border-[#E5E2DC] hover:bg-white text-[#73777A] hover:text-[#303334] transition-all"
                        data-hs-overlay="#app-menu">
                    <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/>
                    </svg>
                </button>

                <div class="hidden sm:flex items-center gap-2 text-xs font-semibold text-[#73777A] bg-[#F5F3EE] px-3 py-1.5 rounded-xl border border-[#E5E2DC]">
                    <span class="size-2 rounded-full bg-[#4E8F35]"></span>
                    <span>النظام متصل ويعمل</span>
                    <span class="text-[#E5E2DC]">|</span>
                    <span id="topbar-live-clock" class="font-mono text-[#303334]">--:--:--</span>
                </div>
            </div>

            <!-- Right Controls: Quick Actions & Profile -->
            <div class="flex items-center gap-2.5 sm:gap-3">

                <!-- Open Cashier Quick Button -->
                <a href="{{ url('/cashier') }}" target="_blank"
                   class="inline-flex items-center gap-1.5 bg-[#F5F3EE] hover:bg-[#EBF4E8] text-[#303334] hover:text-[#4E8F35] border border-[#E5E2DC] hover:border-[#4E8F35]/30 text-xs font-bold px-3 py-1.5 rounded-xl transition-all shadow-xs">
                    <svg class="size-4 text-[#4E8F35]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/>
                    </svg>
                    <span>شاشة الكاشير</span>
                    <span class="bg-[#EBF4E8] text-[#4E8F35] text-[9px] px-1.5 py-0.2 rounded font-extrabold">POS</span>
                </a>



                <!-- Profile Dropdown -->
                <div class="hs-dropdown relative inline-flex [--placement:bottom-right]">
                    <button class="hs-dropdown-toggle inline-flex items-center gap-2 p-1 pe-2.5 rounded-xl hover:bg-[#F5F3EE] transition-all border border-[#E5E2DC]" type="button">
                        <div class="size-8 rounded-xl bg-[#303334] text-white font-bold flex items-center justify-center text-xs">
                            AD
                        </div>
                        <div class="hidden lg:block text-start">
                            <p class="text-xs font-bold text-[#303334] leading-none">{{ auth()->user()->name ?? 'Admin' }}</p>
                            <p class="text-[10px] text-[#73777A] font-medium mt-0.5">مدير النظام</p>
                        </div>
                        <svg class="size-3 text-[#73777A] hidden lg:inline" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>

                    <div class="hs-dropdown-menu duration mt-2 min-w-56 rounded-xl border border-[#E5E2DC] bg-white p-2 opacity-0 shadow-lg transition-[opacity,margin] hs-dropdown-open:opacity-100 hidden">
                        <div class="px-3 py-2 border-b border-[#E5E2DC] mb-1">
                            <p class="text-xs font-bold text-[#303334]">{{ auth()->user()->name ?? 'Admin' }}</p>
                            <p class="text-[11px] text-[#73777A]">{{ auth()->user()->email ?? 'admin@dt-system.com' }}</p>
                        </div>

                        <a class="flex items-center gap-2.5 py-2 px-3 rounded-lg text-xs font-semibold text-[#73777A] hover:bg-[#F5F3EE] hover:text-[#4E8F35] transition-all"
                           href="{{ url('/settings/general') }}">
                            <svg class="size-4 text-[#4E8F35]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                            <span>إعدادات النظام واللوجو</span>
                        </a>

                        <a class="flex items-center gap-2.5 py-2 px-3 rounded-lg text-xs font-semibold text-[#73777A] hover:bg-[#F5F3EE] hover:text-[#4E8F35] transition-all"
                           href="{{ url('/cashier') }}" target="_blank">
                            <svg class="size-4 text-[#4E8F35]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/></svg>
                            <span>فتح الكاشير في نافذة مستقلة</span>
                        </a>

                        <div class="my-1 border-t border-[#E5E2DC]"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2.5 py-2 px-3 rounded-lg text-xs font-semibold text-rose-600 hover:bg-rose-50 transition-all text-start">
                                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
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
