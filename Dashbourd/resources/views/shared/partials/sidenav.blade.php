<!-- Start Sidebar -->
<aside
    class="w-sidenav min-w-sidenav bg-white shadow-sm border-s border-default-100 overflow-y-auto hs-overlay fixed inset-y-0 start-0 z-60 hidden -translate-x-full transform transition-all duration-200 hs-overlay-open:translate-x-0 lg:bottom-0 lg:end-auto lg:z-30 lg:block lg:translate-x-0 rtl:translate-x-full rtl:hs-overlay-open:translate-x-0 rtl:lg:translate-x-0 print:hidden [--body-scroll:true] [--overlay-backdrop:true] lg:[--overlay-backdrop:false]"
    id="app-menu">
    <div class="flex flex-col h-full">

        <!-- Sidenav Brand Logo -->
        <div class="sticky top-0 flex h-topbar items-center justify-between px-5 border-b border-default-100 bg-white z-10">
            <a href="{{ url('/') }}" class="flex items-center gap-2.5">
                @if(!empty($settingsLogo))
                    <img alt="logo" class="h-8 max-w-[140px] object-contain" src="{{ asset('storage/' . $settingsLogo) }}"/>
                @else
                    <div class="size-9 rounded-xl bg-gradient-to-tr from-primary to-indigo-600 flex items-center justify-center text-white shadow-md shadow-primary/30 shrink-0">
                        <svg width="20" height="20" class="text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/>
                        </svg>
                    </div>
                    <div class="flex flex-col text-start">
                        <span class="font-bold text-sm text-default-900 tracking-tight leading-none">DT-SYSTEM</span>
                        <span class="text-[10px] text-default-400 font-medium mt-0.5">مساحة العمل المتكاملة</span>
                    </div>
                @endif
            </a>
            <span class="text-[10px] font-bold uppercase tracking-wider bg-primary/10 text-primary px-2 py-0.5 rounded-full">v2.0</span>
        </div>

        <!-- Sidenav Navigation Menu -->
        <div class="p-3.5 h-[calc(100%-theme('spacing.topbar'))] flex-grow flex flex-col justify-between" data-simplebar="">
            <div>
                <ul class="admin-menu flex w-full flex-col gap-1">

                    {{-- ── الرئيسية ── --}}
                    <li class="px-3 pt-2 pb-1 text-[11px] font-bold text-default-400 uppercase tracking-wider text-start">الرئيسية والتشغيل</li>

                    {{-- لوحة التحكم --}}
                    <li class="menu-item">
                        <a class="group flex items-center gap-x-3 rounded-xl px-3.5 py-2.5 text-sm font-semibold transition-all {{ request()->is('/') || request()->is('dashboard') ? 'bg-primary text-white shadow-md shadow-primary/25' : 'text-default-700 hover:bg-default-100 hover:text-primary' }}"
                           href="{{ url('/') }}">
                            <svg width="20" height="20" class="shrink-0 {{ request()->is('/') || request()->is('dashboard') ? 'text-white' : 'text-primary' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/>
                            </svg>
                            <span>لوحة التحكم الرئيسية</span>
                        </a>
                    </li>

                    {{-- الكاشير السريع POS --}}
                    <li class="menu-item">
                        <a class="group flex items-center gap-x-3 rounded-xl px-3.5 py-2.5 text-sm font-semibold transition-all {{ request()->is('cashier*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/25' : 'text-default-700 hover:bg-emerald-50 hover:text-emerald-600' }}"
                           href="{{ url('/cashier') }}" target="_blank">
                            <svg width="20" height="20" class="shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/><path d="m9 10 2 2 4-4"/>
                            </svg>
                            <span>شاشة الكاشير السريع</span>
                            <span class="ms-auto text-[10px] bg-emerald-500 text-white px-2 py-0.5 rounded-full font-bold animate-pulse">POS</span>
                        </a>
                    </li>

                    {{-- ── إدارة العمليات ── --}}
                    <li class="px-3 pt-4 pb-1 text-[11px] font-bold text-default-400 uppercase tracking-wider text-start">إدارة المساحة والعملاء</li>

                    {{-- العملاء --}}
                    <li class="menu-item">
                        <a class="group flex items-center gap-x-3 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-default-700 hover:bg-default-100 hover:text-primary transition-all {{ request()->is('customers*') ? 'bg-primary/10 text-primary' : '' }}"
                           href="{{ url('/customers') }}">
                            <svg width="20" height="20" class="shrink-0 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                            <span>دليل العملاء</span>
                        </a>
                    </li>

                    {{-- الغرف والمساحات --}}
                    <li class="menu-item">
                        <a class="group flex items-center gap-x-3 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-default-700 hover:bg-default-100 hover:text-primary transition-all {{ request()->is('rooms*') ? 'bg-primary/10 text-primary' : '' }}"
                           href="{{ url('/rooms') }}">
                            <svg width="20" height="20" class="shrink-0 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M13 4h3a2 2 0 0 1 2 2v14"/><path d="M2 20h20"/><path d="M13 20V4a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v16"/><circle cx="9" cy="12" r="1"/>
                            </svg>
                            <span>الغرف والمساحات</span>
                        </a>
                    </li>

                    {{-- الحجوزات --}}
                    <li class="menu-item">
                        <a class="group flex items-center gap-x-3 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-default-700 hover:bg-default-100 hover:text-primary transition-all {{ request()->is('bookings*') ? 'bg-primary/10 text-primary' : '' }}"
                           href="{{ url('/bookings') }}">
                            <svg width="20" height="20" class="shrink-0 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect width="18" height="18" x="3" y="4" rx="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><path d="m9 16 2 2 4-4"/>
                            </svg>
                            <span>جدول الحجوزات</span>
                        </a>
                    </li>

                    {{-- الجلسات النشطة --}}
                    <li class="menu-item">
                        <a class="group flex items-center gap-x-3 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-default-700 hover:bg-default-100 hover:text-primary transition-all {{ request()->is('deals*') ? 'bg-primary/10 text-primary' : '' }}"
                           href="{{ url('/deals/active') }}">
                            <svg width="20" height="20" class="shrink-0 text-teal-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                            </svg>
                            <span>الجلسات النشطة</span>
                        </a>
                    </li>

                    {{-- ── الكافيه والمخزون ── --}}
                    <li class="px-3 pt-4 pb-1 text-[11px] font-bold text-default-400 uppercase tracking-wider text-start">الكافيه والمخزون</li>

                    {{-- المنتجات والبوفيه --}}
                    <li class="menu-item">
                        <a class="group flex items-center gap-x-3 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-default-700 hover:bg-default-100 hover:text-primary transition-all {{ request()->is('products*') ? 'bg-primary/10 text-primary' : '' }}"
                           href="{{ url('/products') }}">
                            <svg width="20" height="20" class="shrink-0 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M17 8h1a4 4 0 1 1 0 8h-1"/><path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z"/><line x1="6" x2="6" y1="2" y2="4"/><line x1="10" x2="10" y1="2" y2="4"/><line x1="14" x2="14" y1="2" y2="4"/>
                            </svg>
                            <span>المنتجات والمشروبات</span>
                        </a>
                    </li>

                    {{-- المخزون --}}
                    <li class="menu-item">
                        <a class="group flex items-center gap-x-3 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-default-700 hover:bg-default-100 hover:text-primary transition-all {{ request()->is('inventory*') ? 'bg-primary/10 text-primary' : '' }}"
                           href="{{ url('/inventory') }}">
                            <svg width="20" height="20" class="shrink-0 text-purple-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/>
                            </svg>
                            <span>حركة المخزون</span>
                        </a>
                    </li>

                    {{-- ── المالية والتحكم ── --}}
                    <li class="px-3 pt-4 pb-1 text-[11px] font-bold text-default-400 uppercase tracking-wider text-start">المالية والنظام</li>

                    {{-- المدفوعات والمالية --}}
                    <li class="menu-item">
                        <a class="group flex items-center gap-x-3 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-default-700 hover:bg-default-100 hover:text-primary transition-all {{ request()->is('payments*') ? 'bg-primary/10 text-primary' : '' }}"
                           href="{{ url('/payments') }}">
                            <svg width="20" height="20" class="shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>
                            </svg>
                            <span>المدفوعات والإيراد</span>
                        </a>
                    </li>

                    {{-- الورديات --}}
                    <li class="menu-item">
                        <a class="group flex items-center gap-x-3 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-default-700 hover:bg-default-100 hover:text-primary transition-all {{ request()->is('shifts*') ? 'bg-primary/10 text-primary' : '' }}"
                           href="{{ url('/shifts/current') }}">
                            <svg width="20" height="20" class="shrink-0 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 8 14"/>
                            </svg>
                            <span>الوردية الحالية</span>
                        </a>
                    </li>

                    {{-- تخصيص الهوية والألوان واللوجو --}}
                    <li class="menu-item">
                        <a class="group flex items-center gap-x-3 rounded-xl px-3.5 py-2.5 text-sm font-semibold transition-all {{ request()->is('settings*') ? 'bg-primary text-white shadow-md shadow-primary/25' : 'text-default-700 hover:bg-default-100 hover:text-primary' }}"
                           href="{{ url('/settings/general') }}">
                            <svg width="20" height="20" class="shrink-0 text-rose-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="13.5" cy="6.5" r=".5" fill="currentColor"/><circle cx="17.5" cy="10.5" r=".5" fill="currentColor"/><circle cx="8.5" cy="7.5" r=".5" fill="currentColor"/><circle cx="6.5" cy="12.5" r=".5" fill="currentColor"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z"/>
                            </svg>
                            <span>تخصيص اللوجو والألوان</span>
                            <span class="ms-auto text-[10px] bg-rose-50 text-rose-600 border border-rose-200 px-1.5 py-0.5 rounded font-bold">الهوية</span>
                        </a>
                    </li>

                </ul>
            </div>

            {{-- Bottom Admin Card --}}
            <div class="mt-6 pt-4 border-t border-default-100">
                <div class="p-3 rounded-xl bg-default-50 border border-default-100 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="size-8 rounded-lg bg-primary/10 text-primary font-bold flex items-center justify-center text-xs shrink-0">
                            AD
                        </div>
                        <div class="text-start">
                            <p class="text-xs font-bold text-default-800 leading-tight">{{ auth()->user()->name ?? 'مدير النظام' }}</p>
                            <p class="text-[10px] text-emerald-500 font-semibold flex items-center gap-1">
                                <span class="size-1.5 rounded-full bg-emerald-500 inline-block animate-ping"></span>
                                متصل الآن
                            </p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="تسجيل الخروج" class="size-8 rounded-lg text-default-400 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center transition-all">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</aside>
<!-- End Sidebar -->
