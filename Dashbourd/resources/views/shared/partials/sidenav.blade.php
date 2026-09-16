<!-- Start Sidebar (Unified DDT Light Brand Architecture) -->
<aside
    class="w-sidenav min-w-sidenav bg-white shadow-sm border-s border-[#E5E2DC] overflow-y-auto hs-overlay fixed inset-y-0 start-0 z-60 hidden -translate-x-full transform transition-all duration-200 hs-overlay-open:translate-x-0 lg:bottom-0 lg:end-auto lg:z-30 lg:block lg:translate-x-0 rtl:translate-x-full rtl:hs-overlay-open:translate-x-0 rtl:lg:translate-x-0 print:hidden [--body-scroll:true] [--overlay-backdrop:true] lg:[--overlay-backdrop:false]"
    id="app-menu">
    <div class="flex flex-col h-full">

        <!-- Sidenav Brand Logo -->
        <div class="sticky top-0 flex h-topbar items-center justify-between px-5 border-b border-[#E5E2DC] bg-white z-10">
            <a href="{{ url('/') }}" class="flex items-center gap-2.5">
                <img alt="DDT Working Space" class="h-8 max-w-[150px] object-contain" src="{{ asset('images/ddt-logo.svg') }}"/>
            </a>
        </div>

        <!-- Sidenav Navigation Menu -->
        <div class="p-3 h-[calc(100%-70px)] flex-grow flex flex-col justify-between" data-simplebar="">
            <div>
                <ul class="admin-menu flex w-full flex-col gap-1">

                    {{-- ── الرئيسية والتشغيل ── --}}
                    <li class="px-3 pt-2 pb-1 text-[11px] font-bold text-[#73777A] uppercase tracking-wider text-start">الرئيسية والتشغيل</li>

                    {{-- لوحة التحكم --}}
                    <li class="menu-item">
                        @php $isActive = request()->is('/') || request()->is('dashboard'); @endphp
                        <a class="group flex items-center gap-x-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ $isActive ? 'bg-[#EBF4E8] text-[#4E8F35] font-bold' : 'text-[#73777A] hover:bg-[#F5F3EE] hover:text-[#303334]' }}"
                           href="{{ url('/') }}">
                            <svg width="18" height="18" class="shrink-0 transition-colors {{ $isActive ? 'text-[#4E8F35]' : 'text-[#73777A] group-hover:text-[#4E8F35]' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/>
                            </svg>
                            <span>لوحة التحكم الرئيسية</span>
                        </a>
                    </li>

                    {{-- الكاشير السريع POS --}}
                    <li class="menu-item">
                        @php $isActive = request()->is('cashier*'); @endphp
                        <a class="group flex items-center gap-x-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ $isActive ? 'bg-[#EBF4E8] text-[#4E8F35] font-bold' : 'text-[#73777A] hover:bg-[#F5F3EE] hover:text-[#303334]' }}"
                           href="{{ url('/cashier') }}" target="_blank">
                            <svg width="18" height="18" class="shrink-0 transition-colors {{ $isActive ? 'text-[#4E8F35]' : 'text-[#73777A] group-hover:text-[#4E8F35]' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/><path d="m9 10 2 2 4-4"/>
                            </svg>
                            <span>شاشة الكاشير السريع</span>
                        </a>
                    </li>

                    {{-- ── إدارة العمليات ── --}}
                    <li class="px-3 pt-3.5 pb-1 text-[11px] font-bold text-[#73777A] uppercase tracking-wider text-start">إدارة المساحة والعملاء</li>

                    {{-- العملاء --}}
                    <li class="menu-item">
                        @php $isActive = request()->is('customers*'); @endphp
                        <a class="group flex items-center gap-x-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ $isActive ? 'bg-[#EBF4E8] text-[#4E8F35] font-bold' : 'text-[#73777A] hover:bg-[#F5F3EE] hover:text-[#303334]' }}"
                           href="{{ url('/customers') }}">
                            <svg width="18" height="18" class="shrink-0 transition-colors {{ $isActive ? 'text-[#4E8F35]' : 'text-[#73777A] group-hover:text-[#4E8F35]' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                            <span>دليل العملاء</span>
                        </a>
                    </li>

                    {{-- الغرف والمساحات --}}
                    <li class="menu-item">
                        @php $isActive = request()->is('rooms*'); @endphp
                        <a class="group flex items-center gap-x-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ $isActive ? 'bg-[#EBF4E8] text-[#4E8F35] font-bold' : 'text-[#73777A] hover:bg-[#F5F3EE] hover:text-[#303334]' }}"
                           href="{{ url('/rooms') }}">
                            <svg width="18" height="18" class="shrink-0 transition-colors {{ $isActive ? 'text-[#4E8F35]' : 'text-[#73777A] group-hover:text-[#4E8F35]' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M13 4h3a2 2 0 0 1 2 2v14"/><path d="M2 20h20"/><path d="M13 20V4a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v16"/><circle cx="9" cy="12" r="1"/>
                            </svg>
                            <span>الغرف والمساحات</span>
                        </a>
                    </li>

                    {{-- الحجوزات --}}
                    <li class="menu-item">
                        @php $isActive = request()->is('bookings*'); @endphp
                        <a class="group flex items-center gap-x-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ $isActive ? 'bg-[#EBF4E8] text-[#4E8F35] font-bold' : 'text-[#73777A] hover:bg-[#F5F3EE] hover:text-[#303334]' }}"
                           href="{{ url('/bookings') }}">
                            <svg width="18" height="18" class="shrink-0 transition-colors {{ $isActive ? 'text-[#4E8F35]' : 'text-[#73777A] group-hover:text-[#4E8F35]' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect width="18" height="18" x="3" y="4" rx="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><path d="m9 16 2 2 4-4"/>
                            </svg>
                            <span>جدول الحجوزات</span>
                        </a>
                    </li>

                    {{-- الجلسات النشطة --}}
                    <li class="menu-item">
                        @php $isActive = request()->is('deals*'); @endphp
                        <a class="group flex items-center gap-x-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ $isActive ? 'bg-[#EBF4E8] text-[#4E8F35] font-bold' : 'text-[#73777A] hover:bg-[#F5F3EE] hover:text-[#303334]' }}"
                           href="{{ url('/deals/active') }}">
                            <svg width="18" height="18" class="shrink-0 transition-colors {{ $isActive ? 'text-[#4E8F35]' : 'text-[#73777A] group-hover:text-[#4E8F35]' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                            </svg>
                            <span>الجلسات النشطة</span>
                        </a>
                    </li>

                    {{-- فعاليات المجتمع والبانرات --}}
                    <li class="menu-item">
                        @php $isActive = request()->is('*events*'); @endphp
                        <a class="group flex items-center gap-x-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ $isActive ? 'bg-[#EBF4E8] text-[#4E8F35] font-bold' : 'text-[#73777A] hover:bg-[#F5F3EE] hover:text-[#303334]' }}"
                           href="{{ route('admin.events.index') }}">
                            <svg width="18" height="18" class="shrink-0 transition-colors {{ $isActive ? 'text-[#4E8F35]' : 'text-[#73777A] group-hover:text-[#4E8F35]' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                            <span>فعاليات المجتمع والبانرات</span>
                        </a>
                    </li>

                    {{-- ── الكافيه والمخزون ── --}}
                    <li class="px-3 pt-3.5 pb-1 text-[11px] font-bold text-[#73777A] uppercase tracking-wider text-start">الكافيه والمخزون</li>

                    {{-- المنتجات والبوفيه --}}
                    <li class="menu-item">
                        @php $isActive = request()->is('products*'); @endphp
                        <a class="group flex items-center gap-x-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ $isActive ? 'bg-[#EBF4E8] text-[#4E8F35] font-bold' : 'text-[#73777A] hover:bg-[#F5F3EE] hover:text-[#303334]' }}"
                           href="{{ url('/products') }}">
                            <svg width="18" height="18" class="shrink-0 transition-colors {{ $isActive ? 'text-[#4E8F35]' : 'text-[#73777A] group-hover:text-[#4E8F35]' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M17 8h1a4 4 0 1 1 0 8h-1"/><path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z"/><line x1="6" x2="6" y1="2" y2="4"/><line x1="10" x2="10" y1="2" y2="4"/><line x1="14" x2="14" y1="2" y2="4"/>
                            </svg>
                            <span>المنتجات والمشروبات</span>
                        </a>
                    </li>

                    {{-- المخزون --}}
                    <li class="menu-item">
                        @php $isActive = request()->is('inventory*'); @endphp
                        <a class="group flex items-center gap-x-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ $isActive ? 'bg-[#EBF4E8] text-[#4E8F35] font-bold' : 'text-[#73777A] hover:bg-[#F5F3EE] hover:text-[#303334]' }}"
                           href="{{ url('/inventory') }}">
                            <svg width="18" height="18" class="shrink-0 transition-colors {{ $isActive ? 'text-[#4E8F35]' : 'text-[#73777A] group-hover:text-[#4E8F35]' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/>
                            </svg>
                            <span>حركة المخزون</span>
                        </a>
                    </li>

                    {{-- ── المالية والتحكم ── --}}
                    <li class="px-3 pt-3.5 pb-1 text-[11px] font-bold text-[#73777A] uppercase tracking-wider text-start">المالية والنظام</li>

                    {{-- المدفوعات والمالية --}}
                    <li class="menu-item">
                        @php $isActive = request()->is('payments*'); @endphp
                        <a class="group flex items-center gap-x-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ $isActive ? 'bg-[#EBF4E8] text-[#4E8F35] font-bold' : 'text-[#73777A] hover:bg-[#F5F3EE] hover:text-[#303334]' }}"
                           href="{{ url('/payments') }}">
                            <svg width="18" height="18" class="shrink-0 transition-colors {{ $isActive ? 'text-[#4E8F35]' : 'text-[#73777A] group-hover:text-[#4E8F35]' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>
                            </svg>
                            <span>المدفوعات والإيراد</span>
                        </a>
                    </li>

                    {{-- الورديات --}}
                    <li class="menu-item">
                        @php $isActive = request()->is('shifts*'); @endphp
                        <a class="group flex items-center gap-x-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ $isActive ? 'bg-[#EBF4E8] text-[#4E8F35] font-bold' : 'text-[#73777A] hover:bg-[#F5F3EE] hover:text-[#303334]' }}"
                           href="{{ url('/shifts/current') }}">
                            <svg width="18" height="18" class="shrink-0 transition-colors {{ $isActive ? 'text-[#4E8F35]' : 'text-[#73777A] group-hover:text-[#4E8F35]' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 8 14"/>
                            </svg>
                            <span>الوردية الحالية</span>
                        </a>
                    </li>

                    {{-- تخصيص الهوية والألوان واللوجو --}}
                    <li class="menu-item">
                        @php $isActive = request()->is('settings*'); @endphp
                        <a class="group flex items-center gap-x-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ $isActive ? 'bg-[#EBF4E8] text-[#4E8F35] font-bold' : 'text-[#73777A] hover:bg-[#F5F3EE] hover:text-[#303334]' }}"
                           href="{{ url('/settings/general') }}">
                            <svg width="18" height="18" class="shrink-0 transition-colors {{ $isActive ? 'text-[#4E8F35]' : 'text-[#73777A] group-hover:text-[#4E8F35]' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="13.5" cy="6.5" r=".5" fill="currentColor"/><circle cx="17.5" cy="10.5" r=".5" fill="currentColor"/><circle cx="8.5" cy="7.5" r=".5" fill="currentColor"/><circle cx="6.5" cy="12.5" r=".5" fill="currentColor"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z"/>
                            </svg>
                            <span>تخصيص اللوجو والألوان</span>
                        </a>
                    </li>

                    {{-- الربط مع HubSpot CRM --}}
                    <li class="menu-item">
                        @php $isActive = request()->is('hubspot*'); @endphp
                        <a class="group flex items-center gap-x-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ $isActive ? 'bg-[#EBF4E8] text-[#4E8F35] font-bold' : 'text-[#73777A] hover:bg-[#F5F3EE] hover:text-[#303334]' }}"
                           href="{{ route('admin.hubspot.index') }}">
                            <svg width="18" height="18" class="shrink-0 transition-colors {{ $isActive ? 'text-[#4E8F35]' : 'text-[#73777A] group-hover:text-[#4E8F35]' }}" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M18.8 7.3c-.6 0-1.1.4-1.3.9l-2.4-.7c.1-.4.1-.7.1-1.1 0-1.8-1.5-3.3-3.3-3.3s-3.3 1.5-3.3 3.3c0 .5.1.9.3 1.3L6.7 9.8c-.3-.2-.7-.3-1.1-.3-1.4 0-2.5 1.1-2.5 2.5s1.1 2.5 2.5 2.5c.5 0 1-.1 1.4-.4l2.1 2.2c-.1.3-.2.6-.2 1 0 1.8 1.5 3.3 3.3 3.3s3.3-1.5 3.3-3.3c0-.4-.1-.8-.2-1.1l2.4-.7c.2.6.8 1 1.4 1 1 0 1.8-.8 1.8-1.8 0-1-.8-1.8-1.8-1.8-.6 0-1.1.4-1.3.9l-2.4-.7c0-.2.1-.5.1-.7 0-.4-.1-.7-.1-1.1l2.4-.7c.2.6.8 1 1.4 1 1 0 1.8-.8 1.8-1.8 0-1-.8-1.8-1.8-1.8zM11.9 4.8c.8 0 1.5.7 1.5 1.5s-.7 1.5-1.5 1.5-1.5-.7-1.5-1.5.7-1.5 1.5-1.5zm.3 13.9c-.8 0-1.5-.7-1.5-1.5s.7-1.5 1.5-1.5 1.5.7 1.5 1.5-.7 1.5-1.5 1.5z"/>
                            </svg>
                            <span>الربط مع HubSpot</span>
                        </a>
                    </li>

                </ul>
            </div>

        </div>
    </div>
</aside>
<!-- End Sidebar -->
