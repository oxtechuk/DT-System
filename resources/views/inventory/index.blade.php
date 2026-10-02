@extends('shared.vertical', ['title' => 'حركة وأرصدة المخزون والخامات — DT-System'])

@section('content')

    {{-- Page Header --}}
    <div class="page-header-container mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="size-2 rounded-full bg-[#4E8F35]"></span>
                <span class="text-xs font-semibold uppercase tracking-wider text-[#4E8F35]">إدارة المستودع والمكونات</span>
            </div>
            <h2 class="text-2xl font-bold text-[#303334] tracking-tight mt-1">
                حركة وأرصدة المخزون والخامات
            </h2>
            <p class="text-xs text-neutral-500 mt-0.5">تتبع كميات الخامات ومكونات الوصفات، مستويات التوريد، وتكاليف المواد الأولية</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('products.index') }}"
                class="px-4 py-2.5 bg-white border border-[#E5E2DC] hover:border-[#4E8F35] text-[#303334] rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-2">
                <svg class="size-4 text-[#4E8F35]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <span>شجرة المنتجات والتكاليف</span>
            </a>

            <button onclick="openModal('addRawMaterialModal')"
                class="px-4 py-2.5 bg-[#4E8F35] hover:bg-[#437c2e] text-white rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-2">
                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>إضافة خامة جديدة</span>
            </button>
        </div>
    </div>

    {{-- Session Feedback --}}
    @if(session('success'))
        <div class="p-3.5 mb-6 rounded-xl bg-[#EBF4E8] border border-[#DCE8D4] text-[#303334] text-xs font-semibold flex items-center gap-2">
            <svg class="size-4 text-[#4E8F35] shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Summary KPIs --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-[#E5E2DC] p-4 flex items-center justify-between shadow-xs">
            <div>
                <p class="text-xs text-neutral-500 font-medium">إجمالي الخامات المسجلة</p>
                <h3 class="text-2xl font-bold text-[#303334] mt-1">{{ $rawMaterials->count() }}</h3>
                <span class="text-[11px] text-[#4E8F35] font-semibold">مكونات تدخل في الوجبات والمشروبات</span>
            </div>
            <div class="size-11 rounded-xl bg-[#EBF4E8] text-[#4E8F35] flex items-center justify-center shrink-0">
                <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-[#E5E2DC] p-4 flex items-center justify-between shadow-xs">
            <div>
                <p class="text-xs text-neutral-500 font-medium">خامات قاربت على النفاد</p>
                <h3 class="text-2xl font-bold text-amber-600 mt-1">{{ $lowStockMaterials->count() }}</h3>
                <span class="text-[11px] text-neutral-500">وصلت لحد الأمان الأدنى</span>
            </div>
            <div class="size-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-[#E5E2DC] p-4 flex items-center justify-between shadow-xs">
            <div>
                <p class="text-xs text-neutral-500 font-medium">قيمة مخزون الخامات التقديرية</p>
                @php
                    $totalRawValue = $rawMaterials->sum(fn($m) => $m->current_stock * $m->unit_cost);
                @endphp
                <h3 class="text-2xl font-bold text-[#303334] mt-1">{{ number_format($totalRawValue, 2) }} <span class="text-xs font-normal">ج.م</span></h3>
                <span class="text-[11px] text-neutral-500">حسب تكلفة الشراء الحالية</span>
            </div>
            <div class="size-11 rounded-xl bg-[#EBF4E8] text-[#4E8F35] flex items-center justify-center shrink-0">
                <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="12" y1="1" x2="12" y2="23"/>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-[#E5E2DC] p-4 flex items-center justify-between shadow-xs">
            <div>
                <p class="text-xs text-neutral-500 font-medium">منتجات جاهزة للبيع</p>
                <h3 class="text-2xl font-bold text-[#303334] mt-1">{{ $products->count() }}</h3>
                <span class="text-[11px] text-neutral-500">مربوطة مع الكاشير والبوفيه</span>
            </div>
            <div class="size-11 rounded-xl bg-[#EBF4E8] text-[#4E8F35] flex items-center justify-center shrink-0">
                <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <path d="M16 10a4 4 0 0 1-8 0"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Low Stock Alert Banner --}}
    @if($lowStockMaterials->count() > 0)
        <div class="bg-amber-50/70 border border-amber-200 rounded-2xl p-4 mb-6">
            <div class="flex items-center gap-3 mb-2">
                <div class="size-8 rounded-lg bg-amber-500 text-white flex items-center justify-center shrink-0">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-amber-900">تنبيه نواقص الخامات بالمستودع</h3>
                    <p class="text-xs text-amber-700">هناك {{ $lowStockMaterials->count() }} خامات اقتربت من النفاد أو أقل من الحد الأدنى:</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-2 mt-2">
                @foreach($lowStockMaterials as $lsm)
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-white border border-amber-200 text-xs font-bold text-amber-900 shadow-xs">
                        <span>{{ $lsm->name }}</span>
                        <span class="bg-amber-100 text-amber-800 px-1.5 py-0.5 rounded text-[11px] font-mono">
                            {{ floatval($lsm->current_stock) }} {{ $lsm->unit_label }} (الحد: {{ floatval($lsm->minimum_stock) }})
                        </span>
                        <button onclick="openAddStockModal({{ $lsm->id }}, '{{ addslashes($lsm->name) }}', '{{ $lsm->unit_label }}')"
                            class="text-[#4E8F35] hover:text-[#3B6E28] text-[11px] font-bold underline">
                            + توريد سريع
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- SECTION 1: Raw Materials Table --}}
    <div class="bg-white rounded-2xl border border-[#E5E2DC] shadow-xs mb-8 overflow-hidden">
        <div class="p-4 border-b border-[#E5E2DC] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-[#F8F7F4]/60">
            <div class="flex items-center gap-2">
                <span class="size-2.5 rounded-full bg-[#4E8F35]"></span>
                <h3 class="font-bold text-[#303334] text-base">أرصدة خامات ومكونات البوفيه (المواد الأولية)</h3>
                <span class="text-xs text-neutral-500">({{ $rawMaterials->count() }} خامة مسجلة)</span>
            </div>
            <div class="flex items-center gap-2">
                <input type="text" id="rawMaterialSearchInput" onkeyup="filterRawMaterials()"
                    placeholder="بحث باسم الخامة أو الوحدة..."
                    class="px-3 py-1.5 text-xs bg-white border border-[#E5E2DC] rounded-xl focus:border-[#4E8F35] focus:outline-none w-56 text-[#303334]">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-right text-xs border-collapse" id="rawMaterialsTable">
                <thead>
                    <tr class="border-b border-[#E5E2DC] bg-[#F8F7F4] text-neutral-500 font-bold">
                        <th class="py-3 px-4">اسم الخامة</th>
                        <th class="py-3 px-4">الوحدة</th>
                        <th class="py-3 px-4">الرصيد بالمستودع</th>
                        <th class="py-3 px-4">تكلفة الوحدة</th>
                        <th class="py-3 px-4">حد الأمان الأدنى</th>
                        <th class="py-3 px-4">إجمالي قيمة الرصيد</th>
                        <th class="py-3 px-4 text-center">إجراءات المستودع</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E2DC]/70">
                    @forelse($rawMaterials as $mat)
                        <tr class="hover:bg-[#F8F7F4]/50 transition-colors">
                            <td class="py-3 px-4">
                                <div class="font-bold text-[#303334] text-sm">{{ $mat->name }}</div>
                                @if($mat->notes)
                                    <div class="text-[11px] text-neutral-400 mt-0.5">{{ Str::limit($mat->notes, 40) }}</div>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-md bg-neutral-100 text-neutral-700 text-[11px] font-semibold">
                                    {{ $mat->unit_label }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono font-bold text-sm {{ $mat->is_low_stock ? 'text-amber-600' : 'text-[#303334]' }}">
                                        {{ floatval($mat->current_stock) }}
                                    </span>
                                    <span class="text-[11px] text-neutral-500">{{ $mat->unit_label }}</span>
                                    @if($mat->is_low_stock)
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            ناقص
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 px-4 font-mono font-bold text-[#303334]">
                                {{ number_format($mat->unit_cost, 2) }} <span class="text-[10px] text-neutral-500 font-normal">ج.م / {{ $mat->unit_label }}</span>
                            </td>
                            <td class="py-3 px-4 font-mono text-neutral-500">
                                {{ floatval($mat->minimum_stock) }} {{ $mat->unit_label }}
                            </td>
                            <td class="py-3 px-4 font-mono font-bold text-[#4E8F35]">
                                {{ number_format($mat->current_stock * $mat->unit_cost, 2) }} <span class="text-[10px] text-neutral-500 font-normal">ج.م</span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button onclick="openAddStockModal({{ $mat->id }}, '{{ addslashes($mat->name) }}', '{{ $mat->unit_label }}')"
                                        title="توريد رصيد جديد"
                                        class="px-2.5 py-1 rounded-lg bg-[#EBF4E8] hover:bg-[#DCE8D4] text-[#3B6E28] font-bold text-xs transition-colors flex items-center gap-1">
                                        <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                        <span>+ توريد</span>
                                    </button>

                                    <button onclick="openEditRawMaterialModal({{ json_encode($mat) }})"
                                        title="تعديل الخامة"
                                        class="p-1.5 rounded-lg border border-[#E5E2DC] hover:border-[#4E8F35] text-neutral-600 hover:text-[#4E8F35] transition-colors">
                                        <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </button>

                                    <button onclick="openDeleteRawMaterialModal({{ $mat->id }}, '{{ addslashes($mat->name) }}')"
                                        title="حذف الخامة"
                                        class="p-1.5 rounded-lg border border-[#E5E2DC] hover:border-rose-300 text-neutral-400 hover:text-rose-600 transition-colors">
                                        <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-neutral-400 text-xs">
                                لا توجد خامات مسجلة حالياً بالمستودع. انقر على "إضافة خامة جديدة" للبدء.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- SECTION 2: Finished Products Table --}}
    <div class="bg-white rounded-2xl border border-[#E5E2DC] shadow-xs overflow-hidden">
        <div class="p-4 border-b border-[#E5E2DC] flex items-center justify-between bg-[#F8F7F4]/60">
            <div class="flex items-center gap-2">
                <span class="size-2.5 rounded-full bg-[#4E8F35]"></span>
                <h3 class="font-bold text-[#303334] text-base">المنتجات الجاهزة والوجبات المباشرة</h3>
                <span class="text-xs text-neutral-500">({{ $products->count() }} صنف جاهز)</span>
            </div>
            <a href="{{ route('products.index') }}"
                class="text-xs text-[#4E8F35] hover:text-[#3B6E28] font-bold flex items-center gap-1">
                <span>تعديل المكونات والوصفات</span>
                <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-right text-xs border-collapse">
                <thead>
                    <tr class="border-b border-[#E5E2DC] bg-[#F8F7F4] text-neutral-500 font-bold">
                        <th class="py-3 px-4">اسم المنتج</th>
                        <th class="py-3 px-4">القسم</th>
                        <th class="py-3 px-4">سعر البيع للعميل</th>
                        <th class="py-3 px-4">تكلفة مكونات الوصفة</th>
                        <th class="py-3 px-4">هامش الربح</th>
                        <th class="py-3 px-4">الرصيد المتاح</th>
                        <th class="py-3 px-4 text-center">إدارة الصنف</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E2DC]/70">
                    @forelse($products as $prod)
                        <tr class="hover:bg-[#F8F7F4]/50 transition-colors">
                            <td class="py-3 px-4 font-bold text-[#303334]">{{ $prod->name }}</td>
                            <td class="py-3 px-4 text-neutral-500">{{ $prod->category->name ?? 'عام' }}</td>
                            <td class="py-3 px-4 font-mono font-bold text-[#303334]">{{ number_format($prod->price, 2) }} ج.م</td>
                            <td class="py-3 px-4 font-mono font-bold text-neutral-600">
                                @if($prod->cost > 0)
                                    {{ number_format($prod->cost, 2) }} ج.م
                                @else
                                    <span class="text-neutral-400 font-normal">غير محدد</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 font-mono font-bold text-[#4E8F35]">
                                @if($prod->profit_margin > 0)
                                    +{{ number_format($prod->profit_margin, 2) }} ج.م
                                @else
                                    <span class="text-neutral-400 font-normal">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 font-mono font-bold">
                                @if($prod->track_inventory)
                                    <span class="{{ $prod->stock_quantity <= 5 ? 'text-amber-600' : 'text-[#303334]' }}">
                                        {{ $prod->stock_quantity }}
                                    </span>
                                @else
                                    <span class="text-neutral-400 font-normal text-[11px]">يُخصم من الخامات</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('products.index') }}"
                                    class="inline-flex items-center gap-1 text-[11px] font-bold text-[#4E8F35] hover:text-[#3B6E28] bg-[#EBF4E8] px-2.5 py-1 rounded-lg">
                                    <span>مكونات الوصفة</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-neutral-400 text-xs">لا توجد منتجات مسجلة.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL: Add Raw Material --}}
    <div id="addRawMaterialModal" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-xs hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl border border-[#E5E2DC] shadow-xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-150">
            <div class="px-5 py-4 border-b border-[#E5E2DC] flex items-center justify-between bg-[#F8F7F4]">
                <div class="flex items-center gap-2">
                    <span class="size-2 rounded-full bg-[#4E8F35]"></span>
                    <h3 class="font-bold text-[#303334] text-sm">إضافة خامة / مادة خام جديدة</h3>
                </div>
                <button type="button" onclick="closeModal('addRawMaterialModal')" class="text-neutral-400 hover:text-neutral-600">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>
            </div>

            <form action="{{ route('raw-materials.store') }}" method="POST" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-[#303334] mb-1.5">اسم الخامة *</label>
                    <input type="text" name="name" required placeholder="مثال: حبوب بن إسبريسو، حليب مراعي، سكر..."
                        class="w-full px-3 py-2 text-xs bg-[#F8F7F4] border border-[#E5E2DC] rounded-xl focus:border-[#4E8F35] focus:bg-white focus:outline-none text-[#303334]">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-[#303334] mb-1.5">وحدة القياس *</label>
                        <select name="unit" required
                            class="w-full px-3 py-2 text-xs bg-[#F8F7F4] border border-[#E5E2DC] rounded-xl focus:border-[#4E8F35] focus:bg-white focus:outline-none text-[#303334]">
                            <option value="gram">جرام (g)</option>
                            <option value="ml">مليلتر (ml)</option>
                            <option value="piece">قطعة (Piece)</option>
                            <option value="pack">كيس / باكت</option>
                            <option value="kg">كيلوجرام (kg)</option>
                            <option value="liter">لتر (L)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#303334] mb-1.5">تكلفة الوحدة (ج.م) *</label>
                        <input type="number" step="0.0001" name="unit_cost" required placeholder="مثال: 0.60"
                            class="w-full px-3 py-2 text-xs bg-[#F8F7F4] border border-[#E5E2DC] rounded-xl focus:border-[#4E8F35] focus:bg-white focus:outline-none text-[#303334]">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-[#303334] mb-1.5">الرصيد المبدئي الحالي *</label>
                        <input type="number" step="0.01" name="current_stock" required value="0"
                            class="w-full px-3 py-2 text-xs bg-[#F8F7F4] border border-[#E5E2DC] rounded-xl focus:border-[#4E8F35] focus:bg-white focus:outline-none text-[#303334]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#303334] mb-1.5">حد الأمان للتنبيه</label>
                        <input type="number" step="0.01" name="minimum_stock" value="10"
                            class="w-full px-3 py-2 text-xs bg-[#F8F7F4] border border-[#E5E2DC] rounded-xl focus:border-[#4E8F35] focus:bg-white focus:outline-none text-[#303334]">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#303334] mb-1.5">ملاحظات أو مواصفات (اختياري)</label>
                    <textarea name="notes" rows="2" placeholder="أي تفاصيل عن المورد أو طريقة التخزين..."
                        class="w-full px-3 py-2 text-xs bg-[#F8F7F4] border border-[#E5E2DC] rounded-xl focus:border-[#4E8F35] focus:bg-white focus:outline-none text-[#303334]"></textarea>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2 border-t border-[#E5E2DC]">
                    <button type="button" onclick="closeModal('addRawMaterialModal')"
                        class="px-3.5 py-2 text-xs font-bold text-neutral-600 hover:text-neutral-800 bg-neutral-100 rounded-xl transition-colors">
                        إلغاء
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-xs font-bold text-white bg-[#4E8F35] hover:bg-[#437c2e] rounded-xl transition-all shadow-sm">
                        حفظ الخامة
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL: Quick Add Stock (توريد) --}}
    <div id="addStockModal" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-xs hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl border border-[#E5E2DC] shadow-xl w-full max-w-sm overflow-hidden animate-in fade-in zoom-in-95 duration-150">
            <div class="px-5 py-4 border-b border-[#E5E2DC] flex items-center justify-between bg-[#F8F7F4]">
                <div class="flex items-center gap-2">
                    <span class="size-2 rounded-full bg-[#4E8F35]"></span>
                    <h3 class="font-bold text-[#303334] text-sm">توريد رصيد جديد للخامة</h3>
                </div>
                <button type="button" onclick="closeModal('addStockModal')" class="text-neutral-400 hover:text-neutral-600">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="addStockForm" method="POST" class="p-5 space-y-4">
                @csrf
                <div class="p-3 bg-[#EBF4E8] rounded-xl border border-[#DCE8D4] text-xs">
                    <span class="text-neutral-500 font-medium">الخامة الموردة: </span>
                    <span id="stockMaterialName" class="font-bold text-[#3B6E28]"></span>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#303334] mb-1.5">
                        الكمية الموردة (<span id="stockMaterialUnit"></span>) *
                    </label>
                    <input type="number" step="0.01" min="0.01" name="added_quantity" required placeholder="مثال: 500 أو 1000"
                        class="w-full px-3 py-2 text-xs bg-[#F8F7F4] border border-[#E5E2DC] rounded-xl focus:border-[#4E8F35] focus:bg-white focus:outline-none text-[#303334]">
                </div>

                <div class="pt-2 flex items-center justify-end gap-2 border-t border-[#E5E2DC]">
                    <button type="button" onclick="closeModal('addStockModal')"
                        class="px-3.5 py-2 text-xs font-bold text-neutral-600 hover:text-neutral-800 bg-neutral-100 rounded-xl transition-colors">
                        إلغاء
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-xs font-bold text-white bg-[#4E8F35] hover:bg-[#437c2e] rounded-xl transition-all shadow-sm">
                        تأكيد التوريد
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL: Edit Raw Material --}}
    <div id="editRawMaterialModal" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-xs hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl border border-[#E5E2DC] shadow-xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-150">
            <div class="px-5 py-4 border-b border-[#E5E2DC] flex items-center justify-between bg-[#F8F7F4]">
                <div class="flex items-center gap-2">
                    <span class="size-2 rounded-full bg-[#4E8F35]"></span>
                    <h3 class="font-bold text-[#303334] text-sm">تعديل بيانات وتكلفة الخامة</h3>
                </div>
                <button type="button" onclick="closeModal('editRawMaterialModal')" class="text-neutral-400 hover:text-neutral-600">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="editRawMaterialForm" method="POST" class="p-5 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-[#303334] mb-1.5">اسم الخامة *</label>
                    <input type="text" id="editMatName" name="name" required
                        class="w-full px-3 py-2 text-xs bg-[#F8F7F4] border border-[#E5E2DC] rounded-xl focus:border-[#4E8F35] focus:bg-white focus:outline-none text-[#303334]">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-[#303334] mb-1.5">وحدة القياس *</label>
                        <select id="editMatUnit" name="unit" required
                            class="w-full px-3 py-2 text-xs bg-[#F8F7F4] border border-[#E5E2DC] rounded-xl focus:border-[#4E8F35] focus:bg-white focus:outline-none text-[#303334]">
                            <option value="gram">جرام (g)</option>
                            <option value="ml">مليلتر (ml)</option>
                            <option value="piece">قطعة (Piece)</option>
                            <option value="pack">كيس / باكت</option>
                            <option value="kg">كيلوجرام (kg)</option>
                            <option value="liter">لتر (L)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#303334] mb-1.5">تكلفة الوحدة (ج.م) *</label>
                        <input type="number" step="0.0001" id="editMatCost" name="unit_cost" required
                            class="w-full px-3 py-2 text-xs bg-[#F8F7F4] border border-[#E5E2DC] rounded-xl focus:border-[#4E8F35] focus:bg-white focus:outline-none text-[#303334]">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-[#303334] mb-1.5">الرصيد الفعلي بالمستودع *</label>
                        <input type="number" step="0.01" id="editMatStock" name="current_stock" required
                            class="w-full px-3 py-2 text-xs bg-[#F8F7F4] border border-[#E5E2DC] rounded-xl focus:border-[#4E8F35] focus:bg-white focus:outline-none text-[#303334]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#303334] mb-1.5">حد الأمان للتنبيه</label>
                        <input type="number" step="0.01" id="editMatMin" name="minimum_stock"
                            class="w-full px-3 py-2 text-xs bg-[#F8F7F4] border border-[#E5E2DC] rounded-xl focus:border-[#4E8F35] focus:bg-white focus:outline-none text-[#303334]">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#303334] mb-1.5">ملاحظات (اختياري)</label>
                    <textarea id="editMatNotes" name="notes" rows="2"
                        class="w-full px-3 py-2 text-xs bg-[#F8F7F4] border border-[#E5E2DC] rounded-xl focus:border-[#4E8F35] focus:bg-white focus:outline-none text-[#303334]"></textarea>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2 border-t border-[#E5E2DC]">
                    <button type="button" onclick="closeModal('editRawMaterialModal')"
                        class="px-3.5 py-2 text-xs font-bold text-neutral-600 hover:text-neutral-800 bg-neutral-100 rounded-xl transition-colors">
                        إلغاء
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-xs font-bold text-white bg-[#4E8F35] hover:bg-[#437c2e] rounded-xl transition-all shadow-sm">
                        حفظ التعديلات
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL: Delete Raw Material --}}
    <div id="deleteRawMaterialModal" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-xs hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl border border-[#E5E2DC] shadow-xl w-full max-w-sm overflow-hidden animate-in fade-in zoom-in-95 duration-150">
            <div class="px-5 py-4 border-b border-[#E5E2DC] bg-[#F8F7F4] flex items-center justify-between">
                <h3 class="font-bold text-rose-600 text-sm">حذف الخامة من المستودع</h3>
                <button type="button" onclick="closeModal('deleteRawMaterialModal')" class="text-neutral-400 hover:text-neutral-600">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="deleteRawMaterialForm" method="POST" class="p-5 space-y-4">
                @csrf
                @method('DELETE')
                <p class="text-xs text-[#303334]">
                    هل أنت متأكد من رغبتك في حذف الخامة <strong id="deleteRawMaterialName" class="text-rose-600"></strong> نهائياً؟
                </p>
                <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-[11px] text-rose-800">
                    تنبيه: في حال كانت الخامة مرتبطة بوصفات منتجات حالية، قد يتوقف حساب تكاليف تلك المنتجات تلقائياً.
                </div>

                <div class="pt-2 flex items-center justify-end gap-2 border-t border-[#E5E2DC]">
                    <button type="button" onclick="closeModal('deleteRawMaterialModal')"
                        class="px-3.5 py-2 text-xs font-bold text-neutral-600 hover:text-neutral-800 bg-neutral-100 rounded-xl transition-colors">
                        إلغاء
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition-all shadow-sm">
                        تأكيد الحذف
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function openAddStockModal(materialId, materialName, unitLabel) {
        const form = document.getElementById('addStockForm');
        form.action = `/raw-materials/${materialId}/add-stock`;
        document.getElementById('stockMaterialName').textContent = materialName;
        document.getElementById('stockMaterialUnit').textContent = unitLabel;
        openModal('addStockModal');
    }

    function openEditRawMaterialModal(mat) {
        const form = document.getElementById('editRawMaterialForm');
        form.action = `/raw-materials/${mat.id}`;
        document.getElementById('editMatName').value = mat.name;
        document.getElementById('editMatUnit').value = mat.unit;
        document.getElementById('editMatCost').value = mat.unit_cost;
        document.getElementById('editMatStock').value = mat.current_stock;
        document.getElementById('editMatMin').value = mat.minimum_stock;
        document.getElementById('editMatNotes').value = mat.notes || '';
        openModal('editRawMaterialModal');
    }

    function openDeleteRawMaterialModal(materialId, materialName) {
        const form = document.getElementById('deleteRawMaterialForm');
        form.action = `/raw-materials/${materialId}`;
        document.getElementById('deleteRawMaterialName').textContent = materialName;
        openModal('deleteRawMaterialModal');
    }

    function filterRawMaterials() {
        const input = document.getElementById('rawMaterialSearchInput').value.toLowerCase();
        const rows = document.querySelectorAll('#rawMaterialsTable tbody tr');
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(input) ? '' : 'none';
        });
    }

    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            ['addRawMaterialModal', 'addStockModal', 'editRawMaterialModal', 'deleteRawMaterialModal'].forEach(closeModal);
        }
    });
</script>
@endsection
