<aside
    class="w-sidenav min-w-sidenav overflow-y-auto hs-overlay fixed inset-y-0 start-0 z-[99999] hidden -translate-x-full transform transition-all duration-300 hs-overlay-open:translate-x-0 lg:bottom-0 lg:end-auto lg:z-[99999] lg:block lg:translate-x-0 rtl:translate-x-full rtl:hs-overlay-open:translate-x-0 rtl:lg:translate-x-0 print:hidden [--body-scroll:true] [--overlay-backdrop:true] lg:[--overlay-backdrop:false]"
    id="app-menu"
    style="background-color: #111827; border-inline-end: 1px solid #1F2937; z-index: 99999 !important;">
    <div class="flex flex-col h-full">

        <!-- Brand Logo Header -->
        <div class="sticky top-0 z-10 flex h-topbar items-center justify-between px-3 bg-[#111827] border-b border-[#1F2937]">
            <a href="{{ url('/') }}" class="flex items-center gap-2 overflow-hidden">
                <!-- Full logo -->
                <div class="sidebar-logo-full flex items-center gap-2">
                    <div class="size-7 rounded-md flex items-center justify-center text-white font-black text-[11px] bg-[#4E8F35] flex-shrink-0">
                        DDT
                    </div>
                    <div class="menu-label-block">
                        <p class="text-white font-bold text-xs leading-none">DDT System</p>
                        <p class="text-[10px] text-gray-400 font-medium leading-none mt-1">Working Space</p>
                    </div>
                </div>
                <!-- Mini icon when collapsed -->
                <div class="sidebar-logo-icon hidden size-7 rounded-md items-center justify-center text-white font-black text-[11px] bg-[#4E8F35]">
                    DDT
                </div>
            </a>

            <!-- Pin Toggle -->
            <button id="btn-pin-sidebar" type="button"
                    title="تثبيت أو طي القائمة"
                    class="hidden lg:inline-flex items-center justify-center size-7 rounded-md transition-colors menu-label-block hover:bg-[#1F2937] text-gray-400 hover:text-white">
                <svg id="icon-pin-unpinned" class="size-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 17.929H6c-1.105 0-2-.895-2-2V5c0-1.105.895-2 2-2h12c1.105 0 2 .895 2 2v10c0 1.105-.895 2-2 2h-2M12 12v9m-3-3 3 3 3-3"/>
                </svg>
                <svg id="icon-pin-pinned" class="size-3.5 hidden text-[#4E8F35]" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M16 12V4h1V2H7v2h1v8l-2 2v2h5.2v6h1.6v-6H18v-2l-2-2z"/>
                </svg>
            </button>
        </div>

        <!-- Navigation Menu -->
        <div class="p-2 flex-grow flex flex-col justify-between overflow-y-auto" data-simplebar="">
            <ul class="flex w-full flex-col gap-1" id="sidebar-tree-root">

                {{-- ══ 1. العمليات والكاشير ══ --}}
                <li class="tree-group-item">
                    <button type="button" onclick="toggleTreeGroup('tree-ops', this)"
                            class="tree-group-btn w-full flex items-center justify-between px-2 py-1.5 rounded-lg transition-colors select-none text-gray-300 hover:bg-[#1F2937] hover:text-white">
                        <div class="flex items-center gap-2">
                            <span class="size-6 rounded-md flex items-center justify-center flex-shrink-0 bg-[#1F2937] text-gray-300">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/>
                                </svg>
                            </span>
                            <span class="menu-label text-[11px] font-bold">العمليات والكاشير</span>
                        </div>
                        <span class="tree-arrow-wrapper menu-label flex items-center justify-center size-4 rounded text-gray-400">
                            <svg class="tree-arrow size-3 transition-transform duration-200"
                                 fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <polyline points="6 9 12 15 18 9"/>
                            </svg>
                        </span>
                    </button>

                    <div id="tree-ops" class="tree-children hidden">
                        <div class="tree-sub-list">
                            @php $isSubActive = request()->is('/') || request()->is('dashboard'); @endphp
                            <a href="{{ url('/') }}" class="nav-sub-link {{ $isSubActive ? 'nav-sub-active' : '' }}">
                                <span class="flex items-center gap-1.5">
                                    <span class="sub-dot"></span>
                                    <span>لوحة التحكم</span>
                                </span>
                            </a>

                            @php $isSubActive = request()->is('cashier*'); @endphp
                            <a href="{{ url('/cashier') }}" target="_blank" class="nav-sub-link {{ $isSubActive ? 'nav-sub-active' : '' }}">
                                <span class="flex items-center gap-1.5">
                                    <span class="sub-dot"></span>
                                    <span>شاشة الكاشير</span>
                                </span>
                                <span class="text-[8px] px-1 py-0.2 rounded font-bold bg-[#14532D] text-[#4ADE80] border border-[#166534]">POS</span>
                            </a>

                            @php $isSubActive = request()->is('deals*'); @endphp
                            <a href="{{ url('/deals/active') }}" class="nav-sub-link {{ $isSubActive ? 'nav-sub-active' : '' }}">
                                <span class="flex items-center gap-1.5">
                                    <span class="sub-dot"></span>
                                    <span>الجلسات النشطة</span>
                                </span>
                            </a>

                            @php $isSubActive = request()->is('bookings*'); @endphp
                            <a href="{{ url('/bookings') }}" class="nav-sub-link {{ $isSubActive ? 'nav-sub-active' : '' }}">
                                <span class="flex items-center gap-1.5">
                                    <span class="sub-dot"></span>
                                    <span>جدول الحجوزات</span>
                                </span>
                            </a>

                            @php $isSubActive = request()->is('rooms*'); @endphp
                            <a href="{{ url('/rooms') }}" class="nav-sub-link {{ $isSubActive ? 'nav-sub-active' : '' }}">
                                <span class="flex items-center gap-1.5">
                                    <span class="sub-dot"></span>
                                    <span>الغرف والمساحات</span>
                                </span>
                            </a>
                        </div>
                    </div>
                </li>

                {{-- ══ 2. العملاء وتطبيق الهاتف ══ --}}
                <li class="tree-group-item">
                    <button type="button" onclick="toggleTreeGroup('tree-crm', this)"
                            class="tree-group-btn w-full flex items-center justify-between px-2 py-1.5 rounded-lg transition-colors select-none text-gray-300 hover:bg-[#1F2937] hover:text-white">
                        <div class="flex items-center gap-2">
                            <span class="size-6 rounded-md flex items-center justify-center flex-shrink-0 bg-[#1F2937] text-gray-300">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                </svg>
                            </span>
                            <span class="menu-label text-[11px] font-bold">العملاء وتطبيق الهاتف</span>
                        </div>
                        <span class="tree-arrow-wrapper menu-label flex items-center justify-center size-4 rounded text-gray-400">
                            <svg class="tree-arrow size-3 transition-transform duration-200"
                                 fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <polyline points="6 9 12 15 18 9"/>
                            </svg>
                        </span>
                    </button>

                    <div id="tree-crm" class="tree-children hidden">
                        <div class="tree-sub-list">
                            @php $isSubActive = request()->is('customers*'); @endphp
                            <a href="{{ url('/customers') }}" class="nav-sub-link {{ $isSubActive ? 'nav-sub-active' : '' }}">
                                <span class="flex items-center gap-1.5">
                                    <span class="sub-dot"></span>
                                    <span>سجل وتصنيف العملاء</span>
                                </span>
                            </a>

                            <a href="{{ url('/app') }}" target="_blank" class="nav-sub-link">
                                <span class="flex items-center gap-1.5">
                                    <span class="sub-dot"></span>
                                    <span>تطبيق الهاتف للجوال</span>
                                </span>
                                <span class="text-[8px] px-1 py-0.2 rounded font-bold bg-[#1E3A8A] text-[#93C5FD] border border-[#1D4ED8]">/app</span>
                            </a>

                            @php $isSubActive = request()->is('*events*'); @endphp
                            <a href="{{ route('admin.events.index') }}" class="nav-sub-link {{ $isSubActive ? 'nav-sub-active' : '' }}">
                                <span class="flex items-center gap-1.5">
                                    <span class="sub-dot"></span>
                                    <span>فعاليات المجتمع والورش</span>
                                </span>
                            </a>
                        </div>
                    </div>
                </li>

                {{-- ══ 3. الكافيه والمخزون ══ --}}
                <li class="tree-group-item">
                    <button type="button" onclick="toggleTreeGroup('tree-cafe', this)"
                            class="tree-group-btn w-full flex items-center justify-between px-2 py-1.5 rounded-lg transition-colors select-none text-gray-300 hover:bg-[#1F2937] hover:text-white">
                        <div class="flex items-center gap-2">
                            <span class="size-6 rounded-md flex items-center justify-center flex-shrink-0 bg-[#1F2937] text-gray-300">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M17 8h1a4 4 0 1 1 0 8h-1"/><path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z"/><line x1="6" x2="6" y1="2" y2="4"/><line x1="10" x2="10" y1="2" y2="4"/><line x1="14" x2="14" y1="2" y2="4"/>
                                </svg>
                            </span>
                            <span class="menu-label text-[11px] font-bold">الكافيه والمخزون</span>
                        </div>
                        <span class="tree-arrow-wrapper menu-label flex items-center justify-center size-4 rounded text-gray-400">
                            <svg class="tree-arrow size-3 transition-transform duration-200"
                                 fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <polyline points="6 9 12 15 18 9"/>
                            </svg>
                        </span>
                    </button>

                    <div id="tree-cafe" class="tree-children hidden">
                        <div class="tree-sub-list">
                            @php $isSubActive = request()->is('products*'); @endphp
                            <a href="{{ url('/products') }}" class="nav-sub-link {{ $isSubActive ? 'nav-sub-active' : '' }}">
                                <span class="flex items-center gap-1.5">
                                    <span class="sub-dot"></span>
                                    <span>قائمة المشروبات والمنتجات</span>
                                </span>
                            </a>

                            @php $isSubActive = request()->is('inventory*'); @endphp
                            <a href="{{ url('/inventory') }}" class="nav-sub-link {{ $isSubActive ? 'nav-sub-active' : '' }}">
                                <span class="flex items-center gap-1.5">
                                    <span class="sub-dot"></span>
                                    <span>حركة وجرد المخزون</span>
                                </span>
                            </a>
                        </div>
                    </div>
                </li>

                {{-- ══ 4. المالية والورديات ══ --}}
                <li class="tree-group-item">
                    <button type="button" onclick="toggleTreeGroup('tree-fin', this)"
                            class="tree-group-btn w-full flex items-center justify-between px-2 py-1.5 rounded-lg transition-colors select-none text-gray-300 hover:bg-[#1F2937] hover:text-white">
                        <div class="flex items-center gap-2">
                            <span class="size-6 rounded-md flex items-center justify-center flex-shrink-0 bg-[#1F2937] text-gray-300">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>
                                </svg>
                            </span>
                            <span class="menu-label text-[11px] font-bold">المالية والورديات</span>
                        </div>
                        <span class="tree-arrow-wrapper menu-label flex items-center justify-center size-4 rounded text-gray-400">
                            <svg class="tree-arrow size-3 transition-transform duration-200"
                                 fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <polyline points="6 9 12 15 18 9"/>
                            </svg>
                        </span>
                    </button>

                    <div id="tree-fin" class="tree-children hidden">
                        <div class="tree-sub-list">
                            @php $isSubActive = request()->is('shifts*'); @endphp
                            <a href="{{ url('/shifts/current') }}" class="nav-sub-link {{ $isSubActive ? 'nav-sub-active' : '' }}">
                                <span class="flex items-center gap-1.5">
                                    <span class="sub-dot"></span>
                                    <span>الوردية الحالية والخزينة</span>
                                </span>
                            </a>

                            @php $isSubActive = request()->is('payments*'); @endphp
                            <a href="{{ url('/payments') }}" class="nav-sub-link {{ $isSubActive ? 'nav-sub-active' : '' }}">
                                <span class="flex items-center gap-1.5">
                                    <span class="sub-dot"></span>
                                    <span>سجل المدفوعات والإيراد</span>
                                </span>
                            </a>
                        </div>
                    </div>
                </li>

                {{-- ══ 5. التقارير والتحليلات ══ --}}
                <li class="tree-group-item">
                    <button type="button" onclick="toggleTreeGroup('tree-reports', this)"
                            class="tree-group-btn w-full flex items-center justify-between px-2 py-1.5 rounded-lg transition-colors select-none text-gray-300 hover:bg-[#1F2937] hover:text-white">
                        <div class="flex items-center gap-2">
                            <span class="size-6 rounded-md flex items-center justify-center flex-shrink-0 bg-[#1F2937] text-gray-300">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
                                </svg>
                            </span>
                            <span class="menu-label text-[11px] font-bold">التقارير والتحليلات</span>
                        </div>
                        <span class="tree-arrow-wrapper menu-label flex items-center justify-center size-4 rounded text-gray-400">
                            <svg class="tree-arrow size-3 transition-transform duration-200"
                                 fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <polyline points="6 9 12 15 18 9"/>
                            </svg>
                        </span>
                    </button>

                    <div id="tree-reports" class="tree-children hidden">
                        <div class="tree-sub-list">
                            @php $isSubActive = request()->is('reports*'); @endphp
                            <a href="{{ route('reports.index') }}" class="nav-sub-link {{ $isSubActive ? 'nav-sub-active' : '' }}">
                                <span class="flex items-center gap-1.5">
                                    <span class="sub-dot"></span>
                                    <span>التقارير وساعات الذروة</span>
                                </span>
                            </a>
                        </div>
                    </div>
                </li>

                {{-- ══ Divider ══ --}}
                <li class="py-0.5"><div class="mx-2 border-t border-[#1F2937]"></div></li>

                {{-- ══ 6. الإعدادات والربط ══ --}}
                <li class="tree-group-item">
                    <button type="button" onclick="toggleTreeGroup('tree-settings', this)"
                            class="tree-group-btn w-full flex items-center justify-between px-2 py-1.5 rounded-lg transition-colors select-none text-gray-300 hover:bg-[#1F2937] hover:text-white">
                        <div class="flex items-center gap-2">
                            <span class="size-6 rounded-md flex items-center justify-center flex-shrink-0 bg-[#1F2937] text-gray-300">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                                </svg>
                            </span>
                            <span class="menu-label text-[11px] font-bold">الإعدادات والربط</span>
                        </div>
                        <span class="tree-arrow-wrapper menu-label flex items-center justify-center size-4 rounded text-gray-400">
                            <svg class="tree-arrow size-3 transition-transform duration-200"
                                 fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <polyline points="6 9 12 15 18 9"/>
                            </svg>
                        </span>
                    </button>

                    <div id="tree-settings" class="tree-children hidden">
                        <div class="tree-sub-list">
                            @php $isSubActive = request()->is('settings*'); @endphp
                            <a href="{{ url('/settings/general') }}" class="nav-sub-link {{ $isSubActive ? 'nav-sub-active' : '' }}">
                                <span class="flex items-center gap-1.5">
                                    <span class="sub-dot"></span>
                                    <span>تخصيص اللوجو والألوان</span>
                                </span>
                            </a>

                            @php $isSubActive = request()->is('hubspot*'); @endphp
                            <a href="{{ route('admin.hubspot.index') }}" class="nav-sub-link {{ $isSubActive ? 'nav-sub-active' : '' }}">
                                <span class="flex items-center gap-1.5">
                                    <span class="sub-dot"></span>
                                    <span>الربط مع HubSpot CRM</span>
                                </span>
                            </a>

                            @php $isSubActive = request()->is('admin/team*'); @endphp
                            <a href="{{ route('admin.team.index') }}" class="nav-sub-link {{ $isSubActive ? 'nav-sub-active' : '' }}">
                                <span class="flex items-center gap-1.5">
                                    <span class="sub-dot"></span>
                                    <span>الفريق والصلاحيات</span>
                                </span>
                                <span class="text-[8px] px-1 py-0.2 rounded font-bold bg-[#1E3A8A] text-[#93C5FD] border border-[#1D4ED8]">NEW</span>
                            </a>
                        </div>
                    </div>
                </li>

            </ul>
        </div>

        <!-- Sidenav Footer -->
        <div class="px-3 py-2.5 flex items-center gap-2 menu-label-block border-t border-[#1F2937]">
            <span class="size-2 rounded-full flex-shrink-0 bg-[#22C55E]"></span>
            <span class="text-[10px] font-medium text-gray-400">قاعدة البيانات: <strong class="text-gray-300 font-bold">MySQL</strong></span>
        </div>

    </div>
</aside>
<!-- End Sidebar -->

<style>
/* ── Clean Solid Sub List & Connecting Line ─────────────────────────────── */
.tree-sub-list {
    display: flex;
    flex-direction: column;
    gap: 1.5px;
    margin-top: 2px;
    margin-bottom: 2px;
    margin-inline-start: 14px;
    padding-inline-start: 10px;
    border-inline-start: 2px solid #1F2937;
}

/* ── Clean Solid Sub Links ──────────────────────────────────────────────── */
.nav-sub-link {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 5px 8px;
    border-radius: 6px;
    font-size: 10.5px;
    font-weight: 500;
    color: #9CA3AF;
    text-decoration: none;
    transition: background-color 0.15s ease, color 0.15s ease;
}
.nav-sub-link .sub-dot {
    width: 4px;
    height: 4px;
    border-radius: 50%;
    background: #4B5563;
    flex-shrink: 0;
}
.nav-sub-link:hover {
    background-color: #1F2937;
    color: #FFFFFF;
}
.nav-sub-link:hover .sub-dot {
    background-color: #9CA3AF;
}

/* Active Sub Link (Solid Card, Crisp & Bright Green) */
.nav-sub-link.nav-sub-active {
    background-color: #4E8F35 !important;
    color: #FFFFFF !important;
    font-weight: 800 !important;
    border-radius: 6px !important;
}
.nav-sub-link.nav-sub-active .sub-dot {
    background-color: #FFFFFF !important;
}

/* ── Clean Accordion ────────────────────────────────────────────────────── */
.tree-children {
    overflow: hidden;
}

/* ── Sidebar scrollbar ──────────────────────────────────────────────────── */
aside#app-menu::-webkit-scrollbar { width: 4px; }
aside#app-menu::-webkit-scrollbar-track { background: transparent; }
aside#app-menu::-webkit-scrollbar-thumb { background: #1F2937; border-radius: 4px; }

/* ── Collapsed Mode Fixes ───────────────────────────────────────────────── */
html.sidebar-collapsed aside#app-menu {
    width: 68px !important;
    min-width: 68px !important;
}
html.sidebar-collapsed aside#app-menu:hover {
    width: 220px !important;
    min-width: 220px !important;
}
html.sidebar-collapsed aside#app-menu .sidebar-logo-full { display: none !important; }
html.sidebar-collapsed aside#app-menu .sidebar-logo-icon { display: flex !important; }
html.sidebar-collapsed aside#app-menu:hover .sidebar-logo-full { display: flex !important; }
html.sidebar-collapsed aside#app-menu:hover .sidebar-logo-icon { display: none !important; }

html.sidebar-collapsed aside#app-menu .menu-label,
html.sidebar-collapsed aside#app-menu .menu-label-block,
html.sidebar-collapsed aside#app-menu .tree-arrow-wrapper,
html.sidebar-collapsed aside#app-menu .tree-children {
    display: none !important;
}

html.sidebar-collapsed aside#app-menu:hover .menu-label {
    display: inline-block !important;
}
html.sidebar-collapsed aside#app-menu:hover .menu-label-block {
    display: block !important;
}
html.sidebar-collapsed aside#app-menu:hover .tree-arrow-wrapper {
    display: flex !important;
}
html.sidebar-collapsed aside#app-menu:hover .tree-children:not(.tree-manually-closed) {
    display: block !important;
}

html.sidebar-collapsed .page-content {
    margin-inline-start: 68px !important;
    transition: margin-inline-start 0.3s ease;
}
@media (max-width: 1023px) {
    html.sidebar-collapsed .page-content { margin-inline-start: 0 !important; }
}
</style>

<script>
function toggleTreeGroup(groupId, btn) {
    const container = document.getElementById(groupId);
    if (!container) return;
    const isHidden = container.classList.contains('hidden');
    const arrow = btn.querySelector('.tree-arrow');
    if (isHidden) {
        container.classList.remove('hidden', 'tree-manually-closed');
        arrow?.classList.add('rotate-180');
    } else {
        container.classList.add('hidden', 'tree-manually-closed');
        arrow?.classList.remove('rotate-180');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const pinBtn       = document.getElementById('btn-pin-sidebar');
    const iconUnpinned = document.getElementById('icon-pin-unpinned');
    const iconPinned   = document.getElementById('icon-pin-pinned');

    if (localStorage.getItem('dt_sidebar_pinned') === 'true') {
        document.documentElement.classList.add('sidebar-collapsed');
        iconUnpinned?.classList.add('hidden');
        iconPinned?.classList.remove('hidden');
    }

    pinBtn?.addEventListener('click', function () {
        const collapsed = document.documentElement.classList.toggle('sidebar-collapsed');
        localStorage.setItem('dt_sidebar_pinned', collapsed ? 'true' : 'false');
        if (collapsed) {
            iconUnpinned?.classList.add('hidden');
            iconPinned?.classList.remove('hidden');
        } else {
            iconUnpinned?.classList.remove('hidden');
            iconPinned?.classList.add('hidden');
        }
    });
});
</script>
