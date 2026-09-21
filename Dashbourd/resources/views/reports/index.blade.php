@extends('shared.vertical', ['title' => 'التقارير والإحصائيات الشاملة — DDT Working Space'])

@section('content')

    {{-- Page Header --}}
    <div class="page-header-container mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <span class="p-2 rounded-xl bg-[#EBF4E8] text-[#4E8F35] border border-[#DCE8D4]">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
                    </svg>
                </span>
                <div>
                    <h1 class="text-2xl font-black text-[#303334] tracking-tight">
                        التقارير والإحصائيات الشاملة
                    </h1>
                    <p class="text-xs text-[#73777A] mt-0.5">
                        تحليل تفصيلي لأداء مساحة العمل: العملاء الجدد، تردد الزيارات، التصنيفات، أوقات الذروة والقوة الاستيعابية
                    </p>
                </div>
            </div>
        </div>

        <div class="page-header-actions flex items-center gap-2.5">
            <button type="button" onclick="window.print()"
                    class="px-3.5 py-2.5 bg-white hover:bg-[#F5F3EE] border border-[#E5E2DC] text-[#303334] rounded-xl text-xs font-bold transition shadow-xs flex items-center gap-2 cursor-pointer print:hidden">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/>
                </svg>
                <span>طباعة التقرير</span>
            </button>

            <a href="{{ route('reports.index', ['period' => $period, 'classification' => $classificationFilter]) }}"
               class="px-3.5 py-2.5 bg-[#4E8F35] hover:bg-[#3F742B] text-white rounded-xl text-xs font-bold transition shadow-xs flex items-center gap-2 cursor-pointer print:hidden">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>
                </svg>
                <span>تحديث البيانات</span>
            </a>
        </div>
    </div>

    {{-- ── TOP FILTER BAR (مدة / تصنيف / مخصص) ── --}}
    <div class="bg-white rounded-2xl border border-[#E5E2DC] p-4 mb-6 shadow-xs print:hidden">
        <form method="GET" action="{{ route('reports.index') }}" class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
            
            {{-- Quick Period Tabs --}}
            <div class="flex items-center gap-1.5 p-1 bg-[#F5F3EE] rounded-xl border border-[#E5E2DC]/60 overflow-x-auto max-w-full">
                <a href="{{ route('reports.index', ['period' => 'today', 'classification' => $classificationFilter]) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $period === 'today' ? 'bg-[#4E8F35] text-white shadow-xs' : 'text-[#73777A] hover:text-[#303334]' }}">
                    اليوم
                </a>
                <a href="{{ route('reports.index', ['period' => 'week', 'classification' => $classificationFilter]) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $period === 'week' ? 'bg-[#4E8F35] text-white shadow-xs' : 'text-[#73777A] hover:text-[#303334]' }}">
                    آخر 7 أيام
                </a>
                <a href="{{ route('reports.index', ['period' => 'month', 'classification' => $classificationFilter]) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $period === 'month' ? 'bg-[#4E8F35] text-white shadow-xs' : 'text-[#73777A] hover:text-[#303334]' }}">
                    هذا الشهر
                </a>
                <a href="{{ route('reports.index', ['period' => '30days', 'classification' => $classificationFilter]) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $period === '30days' ? 'bg-[#4E8F35] text-white shadow-xs' : 'text-[#73777A] hover:text-[#303334]' }}">
                    آخر 30 يوم
                </a>
            </div>

            {{-- Custom Range & Classification Filters --}}
            <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                <input type="hidden" name="period" value="custom">

                {{-- From Date --}}
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-[#73777A]">من:</span>
                    <input type="date" name="from_date" value="{{ request('from_date', $startDate->format('Y-m-d')) }}"
                           class="px-3 py-1.5 border border-[#E5E2DC] rounded-xl text-xs font-bold text-[#303334] focus:border-[#4E8F35] outline-none bg-white">
                </div>

                {{-- To Date --}}
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-[#73777A]">إلى:</span>
                    <input type="date" name="to_date" value="{{ request('to_date', $endDate->format('Y-m-d')) }}"
                           class="px-3 py-1.5 border border-[#E5E2DC] rounded-xl text-xs font-bold text-[#303334] focus:border-[#4E8F35] outline-none bg-white">
                </div>

                {{-- Classification Filter --}}
                <div class="min-w-[170px]">
                    <select name="classification" onchange="this.form.submit()"
                            class="w-full px-3 py-1.5 border border-[#E5E2DC] rounded-xl text-xs font-bold text-[#303334] focus:border-[#4E8F35] outline-none bg-white">
                        <option value="">جميع التصنيفات</option>
                        <option value="freelancer" {{ $classificationFilter === 'freelancer' ? 'selected' : '' }}>فريلانسر / عمل حر</option>
                        <option value="high_school" {{ $classificationFilter === 'high_school' ? 'selected' : '' }}>طالب ثانوي</option>
                        <option value="university" {{ $classificationFilter === 'university' ? 'selected' : '' }}>طالب جامعي</option>
                        <option value="lecturer" {{ $classificationFilter === 'lecturer' ? 'selected' : '' }}>محاضر / مدرب</option>
                        <option value="other" {{ $classificationFilter === 'other' ? 'selected' : '' }}>أخرى / عام</option>
                    </select>
                </div>

                <button type="submit" class="px-4 py-2 bg-[#303334] hover:bg-black text-white rounded-xl text-xs font-bold transition shadow-xs">
                    تطبيق النطاق
                </button>

                @if($period === 'custom' || $classificationFilter)
                    <a href="{{ route('reports.index') }}" class="px-3 py-2 bg-[#F5F3EE] hover:bg-[#E5E2DC] text-[#73777A] rounded-xl text-xs font-bold transition">
                        إعادة ضبط
                    </a>
                @endif
            </div>

        </form>

        {{-- Active Period Label Badge --}}
        <div class="mt-3 pt-3 border-t border-[#F0EDE6] flex flex-wrap items-center justify-between text-xs text-[#73777A]">
            <div class="flex items-center gap-2">
                <span class="font-bold text-[#303334]">النطاق الزمني المفعل:</span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-[#EBF4E8] text-[#4E8F35] font-black border border-[#DCE8D4]">
                    <span class="size-1.5 rounded-full bg-[#4E8F35] animate-pulse"></span>
                    <span>{{ $periodLabel }}</span>
                </span>
                @if($classificationFilter)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 font-bold border border-blue-200">
                        تصنيف: {{ \App\Models\Customer::classifications()[$classificationFilter] ?? $classificationFilter }}
                    </span>
                @endif
            </div>
            <div>
                <span class="text-[11px] text-[#73777A]">القوة الاستيعابية الكلية للمكان: <strong class="text-[#303334]">{{ $totalSpaceCapacity }} مقعد</strong></span>
            </div>
        </div>
    </div>

    {{-- ── 5 MAIN KPI STAT CARDS ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-3.5 mb-6">

        {{-- 1. Online App Customers (LIVE) --}}
        <div class="bg-white rounded-2xl border border-[#E5E2DC] p-4 shadow-xs hover:shadow-md transition-all relative overflow-hidden flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-[#73777A] flex items-center gap-1.5">
                        <span class="relative flex size-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full size-2.5 bg-emerald-500"></span>
                        </span>
                        <span class="whitespace-nowrap">فاتحين التطبيق الآن</span>
                    </span>
                    <div class="size-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100 shrink-0">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect width="14" height="20" x="5" y="2" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/>
                        </svg>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-3xl font-black text-[#303334]">{{ $onlineAppCustomersCount }}</h3>
                    <span class="text-xs font-bold text-[#73777A]">عميل نشط</span>
                </div>
            </div>
            <div class="text-[11px] text-[#73777A] mt-3 flex items-center justify-between border-t border-[#F0EDE6] pt-2">
                <span class="truncate">نشاط اليوم:</span>
                <strong class="text-emerald-700 font-extrabold whitespace-nowrap">{{ $todayAppActiveCount }} مستخدم</strong>
            </div>
        </div>

        {{-- 2. New Customers in Period --}}
        <div class="bg-white rounded-2xl border border-[#E5E2DC] p-4 shadow-xs hover:shadow-md transition-all relative overflow-hidden flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-[#73777A] whitespace-nowrap">العملاء الجدد</span>
                    <div class="size-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100 shrink-0">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/>
                        </svg>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-3xl font-black text-[#303334]">{{ $newCustomersCount }}</h3>
                    <span class="text-xs font-bold text-[#73777A]">من {{ $totalCustomers }} عميل</span>
                </div>
            </div>
            <div class="mt-3 flex items-center justify-between border-t border-[#F0EDE6] pt-2 text-[11px]">
                <span class="truncate text-[#73777A]">مقارنة بالسابق:</span>
                @if($newCustomersGrowth >= 0)
                    <span class="text-emerald-600 font-bold flex items-center gap-0.5 whitespace-nowrap">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></svg>
                        +{{ $newCustomersGrowth }}%
                    </span>
                @else
                    <span class="text-rose-600 font-bold flex items-center gap-0.5 whitespace-nowrap">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                        {{ $newCustomersGrowth }}%
                    </span>
                @endif
            </div>
        </div>

        {{-- 3. Customer Retention / Frequency --}}
        <div class="bg-white rounded-2xl border border-[#E5E2DC] p-4 shadow-xs hover:shadow-md transition-all relative overflow-hidden flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-[#73777A] whitespace-nowrap">تردد العملاء</span>
                    <div class="size-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center border border-purple-100 shrink-0">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>
                        </svg>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-3xl font-black text-[#303334]">{{ $retentionRate }}%</h3>
                    <span class="text-xs font-bold text-purple-700 whitespace-nowrap">تكرار الزيارة</span>
                </div>
            </div>
            <div class="text-[11px] text-[#73777A] mt-3 flex items-center justify-between border-t border-[#F0EDE6] pt-2">
                <span class="truncate">متوسط الزيارات:</span>
                <strong class="text-[#303334] font-extrabold whitespace-nowrap">{{ $avgVisitsPerCustomer }} زيارة/عميل</strong>
            </div>
        </div>

        {{-- 4. Orders & Cafe Sales --}}
        <div class="bg-white rounded-2xl border border-[#E5E2DC] p-4 shadow-xs hover:shadow-md transition-all relative overflow-hidden flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-[#73777A] whitespace-nowrap">الطلبات والمبيعات</span>
                    <div class="size-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100 shrink-0">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                        </svg>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-3xl font-black text-[#303334]">{{ $totalOrdersCount }}</h3>
                    <span class="text-xs font-bold text-[#73777A]">طلب</span>
                </div>
            </div>
            <div class="text-[11px] text-[#73777A] mt-3 flex items-center justify-between border-t border-[#F0EDE6] pt-2">
                <span class="truncate">إجمالي الإيراد:</span>
                <strong class="text-[#4E8F35] font-extrabold whitespace-nowrap">{{ number_format($totalOrdersRevenue, 0) }} ج.م</strong>
            </div>
        </div>

        {{-- 5. Peak Occupancy Status --}}
        <div class="bg-white rounded-2xl border border-[#E5E2DC] p-4 shadow-xs hover:shadow-md transition-all relative overflow-hidden flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-[#73777A] whitespace-nowrap">أعلى ساعة ذروة</span>
                    <div class="size-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-100 shrink-0">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-2xl font-black text-[#303334] whitespace-nowrap">
                        {{ $peakHourItem ? $peakHourItem['short_label'] : '—' }}
                    </h3>
                    <span class="text-xs font-bold text-rose-600 whitespace-nowrap">
                        {{ $peakHourItem ? $peakHourItem['occupancy_rate'] . '%' : '—' }}
                    </span>
                </div>
            </div>
            <div class="text-[11px] text-[#73777A] mt-3 flex items-center justify-between border-t border-[#F0EDE6] pt-2">
                <span class="truncate">أهدى وقت:</span>
                <strong class="text-emerald-600 font-extrabold whitespace-nowrap">{{ $quietHourItem ? $quietHourItem['short_label'] : '—' }}</strong>
            </div>
        </div>

    </div>

    {{-- ── SECTION 1: HOURLY OCCUPANCY & PEAK/EMPTY ANALYSIS (التحليل بالساعة والقوة الاستيعابية) ── --}}
    <div class="bg-white rounded-2xl border border-[#E5E2DC] p-5 mb-6 shadow-xs">
        <div class="flex flex-col md:flex-row md:items-center justify-between pb-4 border-b border-[#F0EDE6] gap-3">
            <div>
                <div class="flex items-center gap-2">
                    <span class="p-1.5 rounded-lg bg-rose-50 text-rose-600 border border-rose-100">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </span>
                    <h2 class="text-base font-black text-[#303334]">
                        تحليل الساعات وأوقات الذروة والقوة الاستيعابية
                    </h2>
                </div>
                <p class="text-xs text-[#73777A] mt-1">
                    كثافة حركة العملاء ومعدل إشغال المكان بالساعة مقارنة بالطاقة الاستيعابية القصوى ({{ $totalSpaceCapacity }} مقعد) لتحديد متى يكون المكان ممتلئاً أو فارغاً
                </p>
            </div>

            {{-- Legend --}}
            <div class="flex items-center gap-3 text-xs font-bold">
                <span class="flex items-center gap-1.5">
                    <span class="size-3 rounded bg-rose-500"></span>
                    <span class="text-[#303334]">وقت ذروة (&ge; 60%)</span>
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="size-3 rounded bg-amber-400"></span>
                    <span class="text-[#303334]">إشغال معتدل</span>
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="size-3 rounded bg-[#4E8F35]"></span>
                    <span class="text-[#303334]">وقت هادئ / فاضي (&le; 25%)</span>
                </span>
            </div>
        </div>

        {{-- ApexChart Container for Hourly Occupancy --}}
        <div class="my-4">
            <div id="hourly-occupancy-chart" class="w-full h-72"></div>
        </div>

        {{-- Hourly Heatmap Grid (All 16 Hours Clearly Visible & Responsive) --}}
        <div class="pt-3 border-t border-[#F0EDE6] overflow-x-auto pb-2">
            <div class="grid grid-cols-8 lg:grid-cols-16 gap-2 min-w-[780px] lg:min-w-0">
                @foreach($hourlyOccupancy as $ho)
                    @php
                        $cardColor = match($ho['status']) {
                            'peak' => 'bg-rose-50 border-rose-200 text-rose-800',
                            'low' => 'bg-emerald-50 border-emerald-200 text-emerald-800',
                            default => 'bg-amber-50 border-amber-200 text-amber-800',
                        };
                        $badgeBg = match($ho['status']) {
                            'peak' => 'bg-rose-500 text-white',
                            'low' => 'bg-[#4E8F35] text-white',
                            default => 'bg-amber-500 text-white',
                        };
                    @endphp
                    <div class="p-2 rounded-xl border {{ $cardColor }} flex flex-col items-center justify-between text-center transition hover:shadow-xs">
                        <span class="text-[10px] font-black text-[#73777A] whitespace-nowrap">{{ $ho['short_label'] }}</span>
                        <div class="my-1 font-black text-sm text-[#303334]">
                            {{ $ho['occupancy_rate'] }}%
                        </div>
                        <span class="text-[9px] px-1.5 py-0.5 rounded-full font-bold {{ $badgeBg }} whitespace-nowrap">
                            @if($ho['status'] === 'peak')
                                ذروة
                            @elseif($ho['status'] === 'low')
                                فاضي
                            @else
                                معتدل
                            @endif
                        </span>
                        <span class="text-[10px] text-[#73777A] mt-1 font-bold whitespace-nowrap">{{ $ho['avg_customers'] }} عميل</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Operational Business Insights --}}
        <div class="mt-4 p-4 rounded-xl bg-[#FAF9F5] border border-[#E5E2DC] flex flex-col md:flex-row items-start md:items-center justify-between gap-3">
            <div class="flex items-start gap-3">
                <span class="p-2 rounded-lg bg-[#EBF4E8] text-[#4E8F35] shrink-0">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
                </span>
                <div>
                    <h4 class="text-xs font-black text-[#303334]">توصيات تشغيلية ذكية لإدارة المساحة:</h4>
                    <p class="text-xs text-[#73777A] mt-0.5">
                        @if($peakHourItem)
                            أعلى وقت ذروة وضغط هو الساعة <strong>{{ $peakHourItem['short_label'] }}</strong> بنسبة إشغال <strong>{{ $peakHourItem['occupancy_rate'] }}%</strong>. يُنصح بتجهيز كادر البوفيه والاستقبال وتأكيد الحجوزات المسبقة.
                        @endif
                        @if($quietHourItem)
                            بينما أوقات الهدوء تتركز حول <strong>{{ $quietHourItem['short_label'] }}</strong> (نسبة إشغال {{ $quietHourItem['occupancy_rate'] }}%)؛ تمثل فرصة ممتازة لطرح عروض "ساعات الصباح المخفضة" أو فعاليات تدريبية.
                        @endif
                    </p>
                </div>
            </div>
            <div class="shrink-0 text-xs font-bold text-[#4E8F35] bg-white px-3 py-2 rounded-xl border border-[#E5E2DC]">
                نسبة استيعاب المكان الإجمالية: {{ $totalSpaceCapacity }} مقعد
            </div>
        </div>
    </div>

    {{-- ── SECTION 2: CUSTOMER CLASSIFICATION & FREQUENCY (تصنيف وتردد العملاء) ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

        {{-- Customer Classification Breakdown (التصنيف) --}}
        <div class="bg-white rounded-2xl border border-[#E5E2DC] p-5 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-[#F0EDE6] mb-3">
                    <div class="flex items-center gap-2">
                        <span class="p-1.5 rounded-lg bg-blue-50 text-blue-600 border border-blue-100">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                        </span>
                        <h2 class="text-sm font-black text-[#303334]">
                            توزيع العملاء حسب التصنيف
                        </h2>
                    </div>
                    <span class="text-[11px] font-bold text-[#73777A]">الإجمالي: {{ $totalCustomers }}</span>
                </div>

                {{-- Classification Donut Chart Container --}}
                <div id="classification-donut-chart" class="w-full h-56"></div>

                {{-- Classification Details List --}}
                <div class="space-y-2.5 mt-3 pt-3 border-t border-[#F0EDE6]">
                    @foreach($classificationStats as $cls)
                        @php
                            $iconColor = match($cls['key']) {
                                'freelancer' => 'bg-emerald-500',
                                'high_school' => 'bg-amber-500',
                                'university' => 'bg-blue-500',
                                'lecturer' => 'bg-purple-500',
                                default => 'bg-slate-400',
                            };
                        @endphp
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2">
                                <span class="size-2.5 rounded-full {{ $iconColor }}"></span>
                                <span class="font-bold text-[#303334]">{{ $cls['label'] }}</span>
                            </div>
                            <div class="flex items-center gap-2 font-mono">
                                <span class="font-bold text-[#303334]">{{ $cls['count'] }} عميل</span>
                                <span class="text-[11px] text-[#73777A] font-bold">({{ $cls['percentage'] }}%)</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-[#F0EDE6] text-center">
                <a href="{{ route('customers.index') }}" class="text-xs font-bold text-[#4E8F35] hover:underline flex items-center justify-center gap-1">
                    <span>فتح دليل العملاء وتعديل التصنيفات</span>
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                </a>
            </div>
        </div>

        {{-- Customer Frequency & Repeat Visits (تردد العملاء) --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-[#E5E2DC] p-5 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-[#F0EDE6] mb-3">
                    <div class="flex items-center gap-2">
                        <span class="p-1.5 rounded-lg bg-purple-50 text-purple-600 border border-purple-100">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>
                            </svg>
                        </span>
                        <div>
                            <h2 class="text-sm font-black text-[#303334]">
                                تحليل تردد وتكرار الزيارات (Customer Retention)
                            </h2>
                            <p class="text-[11px] text-[#73777A]">نسبة العملاء الذين يعودون للمساحة بعد الزيارة الأولى</p>
                        </div>
                    </div>
                </div>

                {{-- 3 Retention Metric Mini-Cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4">
                    <div class="p-3.5 rounded-xl bg-purple-50/60 border border-purple-200">
                        <span class="text-[11px] font-bold text-purple-800 block mb-1">عملاء متكررو الزيارة (2+ زيارات)</span>
                        <div class="flex items-baseline gap-1.5">
                            <span class="text-2xl font-black text-purple-900">{{ $repeatVisitors }}</span>
                            <span class="text-xs text-purple-700 font-bold">({{ $retentionRate }}%)</span>
                        </div>
                    </div>

                    <div class="p-3.5 rounded-xl bg-stone-50 border border-stone-200">
                        <span class="text-[11px] font-bold text-stone-700 block mb-1">زوار لمرة واحدة فقط</span>
                        <div class="flex items-baseline gap-1.5">
                            <span class="text-2xl font-black text-stone-800">{{ $singleVisitCustomers }}</span>
                            <span class="text-xs text-stone-600 font-bold">({{ $totalCustomersWithVisits > 0 ? round(($singleVisitCustomers / $totalCustomersWithVisits) * 100, 1) : 0 }}%)</span>
                        </div>
                    </div>

                    <div class="p-3.5 rounded-xl bg-emerald-50/60 border border-emerald-200">
                        <span class="text-[11px] font-bold text-emerald-800 block mb-1">عملاء دائمون (5+ زيارات)</span>
                        <div class="flex items-baseline gap-1.5">
                            <span class="text-2xl font-black text-emerald-900">{{ $frequentVisitors }}</span>
                            <span class="text-xs text-emerald-700 font-bold">ولاء عالي</span>
                        </div>
                    </div>
                </div>

                {{-- Top Frequent Customers Table --}}
                <h3 class="text-xs font-black text-[#303334] mb-2 flex items-center gap-1.5">
                    <span>أكثر العملاء تردداً وزيارة للمساحة</span>
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-start text-xs border-collapse">
                        <thead>
                            <tr class="border-b border-[#E5E2DC] text-[#73777A] text-[10px] font-black uppercase">
                                <th class="py-2 px-2 text-start">العميل</th>
                                <th class="py-2 px-2 text-start">التصنيف</th>
                                <th class="py-2 px-2 text-center">عدد الجلسات</th>
                                <th class="py-2 px-2 text-center">إجمالي الساعات</th>
                                <th class="py-2 px-2 text-end">إجمالي الصرف</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#F0EDE6]">
                            @forelse($topFrequentCustomers as $fc)
                                <tr class="hover:bg-[#FAF9F5] transition">
                                    <td class="py-2.5 px-2 font-bold text-[#303334]">
                                        <div class="flex items-center gap-2">
                                            <div class="size-7 rounded-full bg-[#EBF4E8] text-[#4E8F35] font-black flex items-center justify-center text-[10px] border border-[#DCE8D4]">
                                                {{ $fc->initials ?: 'ع' }}
                                            </div>
                                            <div>
                                                <div>{{ $fc->full_name ?: $fc->name }}</div>
                                                <div class="text-[10px] text-[#73777A] font-mono" dir="ltr">{{ $fc->phone }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-2.5 px-2">
                                        <span class="text-[10px] px-2 py-0.5 rounded-full font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                            {{ $fc->classification_label }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-2 text-center font-black text-[#4E8F35]">
                                        {{ $fc->deals_count }} زيارة
                                    </td>
                                    <td class="py-2.5 px-2 text-center font-mono font-bold text-[#303334]">
                                        {{ round(($fc->deals_sum_duration_minutes ?? 0) / 60, 1) }} ساعة
                                    </td>
                                    <td class="py-2.5 px-2 text-end font-bold text-[#303334]">
                                        {{ number_format((float) ($fc->orders_sum_total ?? 0), 0) }} ج.م
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-4 text-center text-xs text-[#73777A]">لا توجد زيارات مسجلة في هذه الفترة</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    {{-- ── SECTION 3: ROOMS UTILIZATION & TOP PRODUCTS (الغرف والمنتجات) ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

        {{-- Most Used Rooms (أكثر الغرف استخداماً) --}}
        <div class="bg-white rounded-2xl border border-[#E5E2DC] p-5 shadow-xs">
            <div class="flex items-center justify-between pb-3 border-b border-[#F0EDE6] mb-3">
                <div class="flex items-center gap-2">
                    <span class="p-1.5 rounded-lg bg-emerald-50 text-[#4E8F35] border border-emerald-100">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M13 4h3a2 2 0 0 1 2 2v14"/><path d="M2 20h20"/><path d="M13 20V4a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v16"/>
                        </svg>
                    </span>
                    <h2 class="text-sm font-black text-[#303334]">
                        أكثر الغرف والمساحات استخداماً
                    </h2>
                </div>
                <span class="text-[11px] font-bold text-[#73777A]">{{ count($roomStats) }} غرف مسجلة</span>
            </div>

            <div class="space-y-3.5">
                @forelse($roomStats as $rs)
                    <div class="p-3 rounded-xl border border-[#E5E2DC] hover:border-[#4E8F35]/40 transition">
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="flex items-center gap-2">
                                <span class="size-3 rounded-full" style="background-color: {{ $rs['color'] }}"></span>
                                <span class="font-extrabold text-xs text-[#303334]">{{ $rs['name'] }}</span>
                                <span class="text-[10px] text-[#73777A] font-bold bg-[#F5F3EE] px-1.5 py-0.5 rounded">سعة {{ $rs['capacity'] }} أفراد</span>
                            </div>
                            <div class="text-xs font-black text-[#4E8F35]">
                                {{ $rs['total_hours'] }} ساعة <span class="text-[#73777A] font-normal">({{ $rs['deals_count'] }} جلسة)</span>
                            </div>
                        </div>

                        {{-- Progress Bar for Room Utilization --}}
                        <div class="w-full bg-[#F5F3EE] rounded-full h-2 overflow-hidden flex">
                            <div class="h-full rounded-full transition-all duration-500"
                                 style="width: {{ min(100, max(5, $rs['utilization_rate'])) }}%; background-color: {{ $rs['color'] }};">
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-1 text-[10px] text-[#73777A]">
                            <span>معدل التشغيل: {{ $rs['utilization_rate'] }}%</span>
                            <span>الطاقة الاستيعابية: {{ $rs['capacity'] }} مقعد</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-xs text-[#73777A]">لا توجد بيانات غرف حالياً</div>
                @endforelse
            </div>
        </div>

        {{-- Top Requested Products (أكثر المنتجات طلباً) --}}
        <div class="bg-white rounded-2xl border border-[#E5E2DC] p-5 shadow-xs">
            <div class="flex items-center justify-between pb-3 border-b border-[#F0EDE6] mb-3">
                <div class="flex items-center gap-2">
                    <span class="p-1.5 rounded-lg bg-amber-50 text-amber-600 border border-amber-100">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M17 8h1a4 4 0 1 1 0 8h-1"/><path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z"/><line x1="6" y1="2" x2="6" y2="4"/>
                        </svg>
                    </span>
                    <h2 class="text-sm font-black text-[#303334]">
                        أكثر المنتجات والمشروبات طلباً
                    </h2>
                </div>
                <div class="text-[11px] font-bold text-[#73777A]">
                    <span>طلبات البورتال: {{ $portalOrdersShare }}%</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-start text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-[#E5E2DC] text-[#73777A] text-[10px] font-black uppercase">
                            <th class="py-2 px-2 text-start">المنتج</th>
                            <th class="py-2 px-2 text-start">القسم</th>
                            <th class="py-2 px-2 text-center">الكمية المباعة</th>
                            <th class="py-2 px-2 text-end">إجمالي الإيراد</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F0EDE6]">
                        @forelse($topProducts as $idx => $prod)
                            <tr class="hover:bg-[#FAF9F5] transition">
                                <td class="py-2.5 px-2 font-bold text-[#303334]">
                                    <div class="flex items-center gap-2">
                                        <span class="size-5 rounded-md bg-[#F5F3EE] text-[#73777A] font-black flex items-center justify-center text-[10px]">
                                            #{{ $idx + 1 }}
                                        </span>
                                        <span>{{ $prod->name }}</span>
                                    </div>
                                </td>
                                <td class="py-2.5 px-2 text-[11px] text-[#73777A]">
                                    {{ $prod->category_name ?: 'عام' }}
                                </td>
                                <td class="py-2.5 px-2 text-center font-black text-[#4E8F35]">
                                    {{ $prod->total_qty }} قطعة
                                </td>
                                <td class="py-2.5 px-2 text-end font-bold text-[#303334]">
                                    {{ number_format((float) $prod->total_revenue, 0) }} ج.م
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-xs text-[#73777A]">لا توجد طلبات مسجلة في هذه الفترة</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- ── SECTION 4: LIVE APP SESSIONS & RECENT APP ACTIVITY ── --}}
    <div class="bg-white rounded-2xl border border-[#E5E2DC] p-5 mb-6 shadow-xs">
        <div class="flex items-center justify-between pb-3 border-b border-[#F0EDE6] mb-3">
            <div class="flex items-center gap-2">
                <span class="p-1.5 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-100">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect width="14" height="20" x="5" y="2" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/>
                    </svg>
                </span>
                <div>
                    <h2 class="text-sm font-black text-[#303334]">
                        نشاط عملاء تطبيق DDT الذكي (Customer Portal)
                    </h2>
                    <p class="text-[11px] text-[#73777A]">قائمة العملاء المتصلين ومستخدمي التطبيق مؤخراً وتصنيفاتهم</p>
                </div>
            </div>
            <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200 flex items-center gap-1.5">
                <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>{{ $onlineAppCustomersCount }} متصل الآن</span>
            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
            @forelse($recentAppCustomers as $rc)
                @php
                    $isOnline = $rc->is_app_online;
                @endphp
                <div class="p-3 rounded-xl border {{ $isOnline ? 'border-emerald-300 bg-emerald-50/20' : 'border-[#E5E2DC] bg-white' }} flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <div class="size-8 rounded-full bg-[#EBF4E8] text-[#4E8F35] font-black flex items-center justify-center text-xs border border-[#DCE8D4]">
                                {{ $rc->initials ?: 'ع' }}
                            </div>
                            @if($isOnline)
                                <span class="size-2.5 rounded-full bg-emerald-500 ring-2 ring-white shadow-xs" title="متصل الآن"></span>
                            @else
                                <span class="size-2 rounded-full bg-slate-300" title="غير متصل"></span>
                            @endif
                        </div>
                        <h4 class="font-black text-xs text-[#303334] truncate">{{ $rc->full_name ?: $rc->name }}</h4>
                        <div class="text-[10px] text-[#73777A] font-mono mt-0.5" dir="ltr">{{ $rc->phone }}</div>
                        <div class="mt-1.5">
                            <span class="text-[10px] px-2 py-0.5 rounded-full font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                {{ $rc->classification_label }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-2 pt-2 border-t border-[#F0EDE6] text-[10px] text-[#73777A]">
                        {{ $rc->last_active_at ? $rc->last_active_at->diffForHumans() : '—' }}
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-6 text-xs text-[#73777A]">
                    لم يتم تسجيل دخول عملاء عبر التطبيق حتى الآن.
                </div>
            @endforelse
        </div>
    </div>

@endsection

@section('scripts')
<script>
window.addEventListener('load', function() {
    function initCharts() {
        if (typeof ApexCharts === 'undefined') {
            setTimeout(initCharts, 100);
            return;
        }

        // ── 1. Hourly Occupancy Chart (ApexCharts) ──
        const hourlyData = @json($hourlyOccupancy);
        const hourlyLabels = hourlyData.map(d => d.short_label);
        const hourlyRates = hourlyData.map(d => d.occupancy_rate);
        const hourlyCounts = hourlyData.map(d => d.avg_customers);

        const hourlyContainer = document.querySelector('#hourly-occupancy-chart');
        if (hourlyContainer) {
            const hourlyOptions = {
                chart: {
                    type: 'area',
                    height: 280,
                    fontFamily: 'Cairo, sans-serif',
                    toolbar: { show: false },
                    zoom: { enabled: false }
                },
                series: [
                    {
                        name: 'نسبة الإشغال (%)',
                        data: hourlyRates
                    },
                    {
                        name: 'متوسط عدد العملاء',
                        data: hourlyCounts
                    }
                ],
                colors: ['#EF4444', '#4E8F35'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.45,
                        opacityTo: 0.05,
                        stops: [20, 100]
                    }
                },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: [3, 2] },
                xaxis: {
                    categories: hourlyLabels,
                    labels: {
                        style: { colors: '#73777A', fontSize: '11px', fontWeight: 600 }
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: [
                    {
                        title: { text: 'نسبة الإشغال (%)', style: { color: '#EF4444', fontWeight: 700 } },
                        min: 0,
                        max: 100,
                        labels: {
                            formatter: val => Math.round(val) + '%',
                            style: { colors: '#73777A', fontWeight: 600 }
                        }
                    },
                    {
                        opposite: true,
                        title: { text: 'متوسط العملاء', style: { color: '#4E8F35', fontWeight: 700 } },
                        labels: {
                            formatter: val => val,
                            style: { colors: '#73777A', fontWeight: 600 }
                        }
                    }
                ],
                tooltip: {
                    shared: true,
                    intersect: false,
                    y: {
                        formatter: (val, opts) => opts.seriesIndex === 0 ? val + '%' : val + ' عميل'
                    }
                },
                grid: {
                    borderColor: '#F0EDE6',
                    strokeDashArray: 4
                }
            };

            const hourlyChart = new ApexCharts(hourlyContainer, hourlyOptions);
            hourlyChart.render();
        }

        // ── 2. Classification Donut Chart (ApexCharts) ──
        const clsStats = @json($classificationStats);
        const clsLabels = Object.values(clsStats).map(c => c.label);
        const clsSeries = Object.values(clsStats).map(c => c.count);

        const donutContainer = document.querySelector('#classification-donut-chart');
        if (donutContainer) {
            const hasData = clsSeries.some(v => v > 0);
            const donutOptions = {
                chart: {
                    type: 'donut',
                    height: 220,
                    fontFamily: 'Cairo, sans-serif'
                },
                series: hasData ? clsSeries : [1],
                labels: hasData ? clsLabels : ['لا توجد بيانات'],
                colors: hasData ? ['#10B981', '#F59E0B', '#3B82F6', '#8B5CF6', '#9CA3AF'] : ['#E5E2DC'],
                legend: { show: false },
                dataLabels: { enabled: false },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '68%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'إجمالي العملاء',
                                    color: '#73777A',
                                    fontSize: '11px',
                                    fontWeight: 700,
                                    formatter: () => '{{ $totalCustomers }}'
                                }
                            }
                        }
                    }
                }
            };

            const donutChart = new ApexCharts(donutContainer, donutOptions);
            donutChart.render();
        }
    }

    initCharts();
});
</script>
@endsection
