@extends('shared.vertical', ['title' => 'إدارة المنتجات والمشروبات والخامات — DDT WORKING SPACE'])

@section('content')

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div class="mb-4 p-4 rounded-xl bg-[#EBF4E8] border border-[#DCE8D4] text-[#4E8F35] text-xs font-bold flex items-center justify-between shadow-xs animate-fade-in">
            <div class="flex items-center gap-2">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-[#4E8F35] hover:opacity-75">✕</button>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold flex items-center justify-between shadow-xs animate-fade-in">
            <div class="flex items-center gap-2">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-rose-700 hover:opacity-75">✕</button>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs space-y-1 shadow-xs">
            <div class="font-black mb-1">يرجى تصحيح الأخطاء التالية:</div>
            @foreach($errors->all() as $err)
                <div>• {{ $err }}</div>
            @endforeach
        </div>
    @endif

    {{-- Page Header (Unified DDT Architecture) --}}
    <div class="page-header-container">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-bold text-[#4E8F35] bg-[#EBF4E8] px-2.5 py-0.5 rounded-full border border-[#DCE8D4]">الكافيه والمخزون</span>
                <span class="text-xs text-[#73777A] font-mono">Cafe & Products</span>
            </div>
            <h1 class="text-2xl font-extrabold text-[#303334] tracking-tight">
                المنتجات والمشروبات والخامات
            </h1>
            <p class="text-xs text-[#73777A] mt-1">إدارة الأصناف، تحديد أسعار البيع والتكلفة، وربط المكونات بالخامات والمخزون (Recipe BOM)</p>
        </div>

        <div class="page-header-actions">
            {{-- Add New Product Button --}}
            <button type="button" onclick="openAddProductModal()"
                    class="px-4 py-2.5 bg-[#4E8F35] hover:bg-[#3F742B] text-white rounded-xl text-xs font-bold transition-all shadow-xs gap-2 flex items-center cursor-pointer">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/>
                </svg>
                <span>إضافة صنف جديد</span>
            </button>

            {{-- Go to Inventory / Raw Materials --}}
            <a href="{{ route('inventory.index') }}"
               class="px-3.5 py-2.5 bg-[#F5F3EE] hover:bg-[#EBF4E8] text-[#303334] hover:text-[#4E8F35] border border-[#E5E2DC] rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shadow-xs">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>
                </svg>
                <span>أرصدة الخامات والمستودع</span>
            </a>

            {{-- Cashier Link --}}
            <a href="{{ url('/cashier') }}" target="_blank"
               class="px-3.5 py-2.5 bg-[#F5F3EE] hover:bg-[#EBF4E8] text-[#303334] hover:text-[#4E8F35] border border-[#E5E2DC] rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shadow-xs">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/>
                </svg>
                <span>شاشة الكاشير</span>
            </a>
        </div>
    </div>

    {{-- Unified Stat Cards --}}
    <div class="dt-grid-3">
        <div class="dt-stat-card">
            <div class="dt-stat-info">
                <span class="dt-stat-label">إجمالي الأصناف</span>
                <div class="dt-stat-value">{{ $totalProducts }}</div>
            </div>
            <div class="dt-stat-icon">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M17 8h1a4 4 0 1 1 0 8h-1"/><path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z"/><line x1="6" x2="6" y1="2" y2="4"/><line x1="10" x2="10" y1="2" y2="4"/><line x1="14" x2="14" y1="2" y2="4"/>
                </svg>
            </div>
        </div>

        <div class="dt-stat-card">
            <div class="dt-stat-info">
                <span class="dt-stat-label">الخامات المتاحة بالمخزن</span>
                <div class="dt-stat-value text-[#4E8F35]">{{ $rawMaterials->count() }} <span class="text-xs font-normal text-[#73777A]">خامة</span></div>
            </div>
            <div class="dt-stat-icon">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                </svg>
            </div>
        </div>

        <div class="dt-stat-card">
            <div class="dt-stat-info">
                <span class="dt-stat-label">أصناف مربوطة بمكونات</span>
                <div class="dt-stat-value">{{ $products->filter(fn($p) => $p->ingredients->count() > 0)->count() }}</div>
            </div>
            <div class="dt-stat-icon">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Products Table Card --}}
    <div class="bg-white border border-[#E5E2DC] rounded-2xl p-5 shadow-xs">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-[#E5E2DC]">
            <div>
                <h3 class="font-bold text-sm text-[#303334]">جدول الأصناف والأسعار والمكونات</h3>
                <p class="text-xs text-[#73777A]">يمكنك تعديل أي صنف، تحديد تكلفته، أو ربطه بخامات ومكونات لتخصم آلياً من المخزن عند البيع</p>
            </div>
            <span class="text-xs text-[#73777A] font-bold">عرض {{ $products->count() }} من {{ $totalProducts }}</span>
        </div>

        <div class="dt-table-responsive">
            <table class="dt-table">
                <thead>
                    <tr>
                        <th>الصنف / القسم</th>
                        <th>سعر البيع</th>
                        <th>سعر التكلفة</th>
                        <th>صافي الربح</th>
                        <th>الخامات والمكونات (Recipe)</th>
                        <th>الحالة</th>
                        <th class="text-center">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                        @php
                            $cost = $p->cost;
                            $profit = max(0, $p->selling_price - $cost);
                            $hasRecipe = $p->ingredients->count() > 0;
                        @endphp
                        <tr class="hover:bg-[#F8F7F4]/80 transition">
                            <td>
                                <div class="font-extrabold text-[#303334] text-xs mb-0.5">{{ $p->name }}</div>
                                <div class="flex items-center gap-1.5 text-[10px] text-[#73777A]">
                                    <span class="bg-[#F5F3EE] px-1.5 py-0.5 rounded border border-[#E5E2DC]">
                                        {{ $p->category->name ?? 'بوفيه عام' }}
                                    </span>
                                    @if($p->unit)
                                        <span>• {{ $p->unit }}</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Selling Price --}}
                            <td class="font-mono font-black text-xs text-[#303334]">
                                {{ number_format($p->selling_price, 2) }} <span class="text-[10px] font-normal text-[#73777A]">ج.م</span>
                            </td>

                            {{-- Cost Price --}}
                            <td class="font-mono text-xs">
                                @if($cost > 0)
                                    <div class="font-bold text-[#303334] flex items-center gap-1">
                                        <span>{{ number_format($cost, 2) }}</span>
                                        <span class="text-[10px] font-normal text-[#73777A]">ج.م</span>
                                    </div>
                                    <span class="text-[9px] font-semibold {{ $hasRecipe ? 'text-[#4E8F35]' : 'text-[#73777A]' }}">
                                        {{ $hasRecipe ? 'محسوبة من الخامات' : 'تكلفة مباشرة' }}
                                    </span>
                                @else
                                    <span class="text-[#73777A] text-[11px]">— غير محدد</span>
                                @endif
                            </td>

                            {{-- Profit Margin --}}
                            <td class="font-mono font-bold text-xs">
                                @if($cost > 0)
                                    <span class="text-[#4E8F35]">{{ number_format($profit, 2) }} ج.م</span>
                                    <div class="text-[10px] text-[#73777A]">({{ round(($profit / $p->selling_price) * 100) }}%)</div>
                                @else
                                    <span class="text-[#73777A] text-[11px]">—</span>
                                @endif
                            </td>

                            {{-- Recipe / Ingredients --}}
                            <td>
                                @if($hasRecipe)
                                    <div class="flex flex-wrap gap-1 max-w-xs">
                                        @foreach($p->ingredients as $ing)
                                            <span class="inline-flex items-center gap-1 text-[10px] bg-[#F5F3EE] text-[#303334] px-2 py-0.5 rounded-md border border-[#E5E2DC]" title="التكلفة: {{ number_format($ing->calculated_cost, 2) }} ج.م">
                                                <strong>{{ $ing->rawMaterial->name ?? 'خامة' }}</strong>
                                                <span class="text-[#73777A] font-mono">({{ $ing->quantity }} {{ $ing->rawMaterial?->unit_label }})</span>
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <button type="button" onclick="openRecipeModal({{ json_encode($p) }})"
                                            class="text-[11px] font-bold text-[#4E8F35] hover:underline flex items-center gap-1 cursor-pointer">
                                        <span>+ ربط خامات ومكونات</span>
                                    </button>
                                @endif
                            </td>

                            {{-- Active Status --}}
                            <td>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $p->active ? 'bg-[#EBF4E8] text-[#4E8F35] border border-[#DCE8D4]' : 'bg-[#F5F3EE] text-[#73777A] border border-[#E5E2DC]' }}">
                                    {{ $p->active ? 'متاح للطلب' : 'معطل' }}
                                </span>
                            </td>

                            {{-- Actions --}}
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-1">
                                    {{-- Recipe / Ingredients Button --}}
                                    <button type="button" onclick="openRecipeModal({{ json_encode($p) }})"
                                            title="إدارة خامات ومكونات الصنف (Recipe BOM)"
                                            class="p-1.5 rounded-lg bg-[#F5F3EE] hover:bg-[#EBF4E8] text-[#73777A] hover:text-[#4E8F35] transition cursor-pointer">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                                        </svg>
                                    </button>

                                    {{-- Edit Button --}}
                                    <button type="button" onclick="openEditProductModal({{ json_encode($p) }})"
                                            title="تعديل بيانات الصنف والأسعار"
                                            class="p-1.5 rounded-lg bg-[#F5F3EE] hover:bg-[#EBF4E8] text-[#73777A] hover:text-[#4E8F35] transition cursor-pointer">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                    </button>

                                    {{-- Delete Button --}}
                                    <button type="button" onclick="openDeleteProductModal({{ $p->id }}, '{{ addslashes($p->name) }}')"
                                            title="حذف الصنف"
                                            class="p-1.5 rounded-lg bg-[#F5F3EE] hover:bg-rose-50 text-[#73777A] hover:text-rose-600 transition cursor-pointer">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-[#73777A] text-xs">
                                لا توجد أصناف مسجلة حالياً. انقر على «إضافة صنف جديد» للبدء.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 pt-3 border-t border-[#E5E2DC]">
            {{ $products->links() }}
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════ --}}
    {{-- MODAL 1: إضافة صنف جديد (Add Product Modal)              --}}
    {{-- ════════════════════════════════════════════════════════ --}}
    <div id="modal-add-product" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div class="bg-white border border-[#E5E2DC] rounded-3xl max-w-lg w-full p-6 shadow-2xl text-[#303334] animate-scale-up">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-[#E5E2DC]">
                <div>
                    <h3 class="font-black text-base text-[#303334]">إضافة صنف جديد للكافيه</h3>
                    <p class="text-xs text-[#73777A]">تسجيل المشروب أو السناك وسعر البيع والتكلفة</p>
                </div>
                <button type="button" onclick="closeModal('modal-add-product')" class="w-8 h-8 rounded-lg bg-[#F5F3EE] text-[#73777A] hover:text-[#303334] flex items-center justify-center font-bold transition">
                    ✕
                </button>
            </div>

            <form action="{{ route('products.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-[#303334] mb-1.5">اسم الصنف *</label>
                        <input type="text" name="name" required placeholder="مثال: كابتشينو، قهوة تركي، كودريد..."
                               class="w-full bg-[#F5F3EE] border border-[#E5E2DC] text-[#303334] text-xs font-semibold rounded-xl px-3.5 py-2.5 focus:border-[#4E8F35] focus:bg-white focus:ring-2 focus:ring-[#EBF4E8] outline-none transition">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-[#303334] mb-1.5">القسم / التصنيف</label>
                            <select name="category_id" class="w-full bg-[#F5F3EE] border border-[#E5E2DC] text-[#303334] text-xs font-bold rounded-xl px-3.5 py-2.5 focus:border-[#4E8F35] focus:bg-white outline-none">
                                <option value="">-- اختر القسم --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#303334] mb-1.5">وحدة التقديم</label>
                            <input type="text" name="unit" placeholder="مثال: كوب، فنجان، زجاجة..."
                                   class="w-full bg-[#F5F3EE] border border-[#E5E2DC] text-[#303334] text-xs rounded-xl px-3.5 py-2.5 focus:border-[#4E8F35] focus:bg-white outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-[#303334] mb-1.5">سعر البيع (ج.م) *</label>
                            <input type="number" step="0.5" name="selling_price" required min="0" placeholder="مثال: 35.00"
                                   class="w-full bg-[#F5F3EE] border border-[#E5E2DC] text-[#303334] text-xs font-mono font-bold rounded-xl px-3.5 py-2.5 focus:border-[#4E8F35] focus:bg-white outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#303334] mb-1.5">سعر التكلفة التقديري (ج.م)</label>
                            <input type="number" step="0.1" name="purchase_price" min="0" value="0" placeholder="مثال: 12.00"
                                   class="w-full bg-[#F5F3EE] border border-[#E5E2DC] text-[#303334] text-xs font-mono font-bold rounded-xl px-3.5 py-2.5 focus:border-[#4E8F35] focus:bg-white outline-none">
                            <span class="text-[10px] text-[#73777A] mt-1 block">يُحسب آلياً إذا تم ربط الصنف بخامات</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-1">
                        <input type="checkbox" name="active" value="1" id="add-active" checked
                               class="w-4 h-4 rounded text-[#4E8F35] focus:ring-[#4E8F35]">
                        <label for="add-active" class="text-xs font-bold text-[#303334] cursor-pointer">متاح للطلب فوراً في الكاشير وتطبيق الموبايل</label>
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-[#E5E2DC]">
                        <button type="button" onclick="closeModal('modal-add-product')" class="px-4 py-2 bg-[#F5F3EE] hover:bg-[#E5E2DC] text-[#303334] rounded-xl text-xs font-bold transition">
                            إلغاء
                        </button>
                        <button type="submit" class="px-5 py-2 bg-[#4E8F35] hover:bg-[#3F742B] text-white rounded-xl text-xs font-bold transition shadow-xs cursor-pointer">
                            حفظ الصنف
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════ --}}
    {{-- MODAL 2: تعديل الصنف (Edit Product Modal)                 --}}
    {{-- ════════════════════════════════════════════════════════ --}}
    <div id="modal-edit-product" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div class="bg-white border border-[#E5E2DC] rounded-3xl max-w-lg w-full p-6 shadow-2xl text-[#303334] animate-scale-up">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-[#E5E2DC]">
                <div>
                    <h3 class="font-black text-base text-[#303334]">تعديل بيانات الصنف والتكلفة</h3>
                    <p class="text-xs text-[#73777A]">تعديل الاسم، الأسعار، أو حالة النشاط</p>
                </div>
                <button type="button" onclick="closeModal('modal-edit-product')" class="w-8 h-8 rounded-lg bg-[#F5F3EE] text-[#73777A] hover:text-[#303334] flex items-center justify-center font-bold transition">
                    ✕
                </button>
            </div>

            <form id="form-edit-product" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-[#303334] mb-1.5">اسم الصنف *</label>
                        <input type="text" id="edit-prod-name" name="name" required
                               class="w-full bg-[#F5F3EE] border border-[#E5E2DC] text-[#303334] text-xs font-semibold rounded-xl px-3.5 py-2.5 focus:border-[#4E8F35] focus:bg-white focus:ring-2 focus:ring-[#EBF4E8] outline-none transition">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-[#303334] mb-1.5">القسم / التصنيف</label>
                            <select id="edit-prod-category" name="category_id" class="w-full bg-[#F5F3EE] border border-[#E5E2DC] text-[#303334] text-xs font-bold rounded-xl px-3.5 py-2.5 focus:border-[#4E8F35] focus:bg-white outline-none">
                                <option value="">-- اختر القسم --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#303334] mb-1.5">وحدة التقديم</label>
                            <input type="text" id="edit-prod-unit" name="unit"
                                   class="w-full bg-[#F5F3EE] border border-[#E5E2DC] text-[#303334] text-xs rounded-xl px-3.5 py-2.5 focus:border-[#4E8F35] focus:bg-white outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-[#303334] mb-1.5">سعر البيع (ج.م) *</label>
                            <input type="number" step="0.5" id="edit-prod-selling" name="selling_price" required min="0"
                                   class="w-full bg-[#F5F3EE] border border-[#E5E2DC] text-[#303334] text-xs font-mono font-bold rounded-xl px-3.5 py-2.5 focus:border-[#4E8F35] focus:bg-white outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#303334] mb-1.5">سعر التكلفة (ج.م)</label>
                            <input type="number" step="0.1" id="edit-prod-purchase" name="purchase_price" min="0"
                                   class="w-full bg-[#F5F3EE] border border-[#E5E2DC] text-[#303334] text-xs font-mono font-bold rounded-xl px-3.5 py-2.5 focus:border-[#4E8F35] focus:bg-white outline-none">
                            <span class="text-[10px] text-[#73777A] mt-1 block">يمكن تعديلها يدوياً أو احتسابها من المكونات</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-1">
                        <input type="checkbox" name="active" value="1" id="edit-prod-active"
                               class="w-4 h-4 rounded text-[#4E8F35] focus:ring-[#4E8F35]">
                        <label for="edit-prod-active" class="text-xs font-bold text-[#303334] cursor-pointer">متاح للطلب في الكاشير وتطبيق الموبايل</label>
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-[#E5E2DC]">
                        <button type="button" onclick="closeModal('modal-edit-product')" class="px-4 py-2 bg-[#F5F3EE] hover:bg-[#E5E2DC] text-[#303334] rounded-xl text-xs font-bold transition">
                            إلغاء
                        </button>
                        <button type="submit" class="px-5 py-2 bg-[#4E8F35] hover:bg-[#3F742B] text-white rounded-xl text-xs font-bold transition shadow-xs cursor-pointer">
                            حفظ التعديلات
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════ --}}
    {{-- MODAL 3: مكونات وخامات الصنف (Recipe / BOM Modal)        --}}
    {{-- ════════════════════════════════════════════════════════ --}}
    <div id="modal-recipe" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div class="bg-white border border-[#E5E2DC] rounded-3xl max-w-xl w-full p-6 shadow-2xl text-[#303334] animate-scale-up max-h-[90vh] flex flex-col">
            <div class="flex items-center justify-between pb-3 border-b border-[#E5E2DC] shrink-0">
                <div>
                    <h3 class="font-black text-base text-[#303334] flex items-center gap-2">
                        <span>مكونات وخامات:</span>
                        <span id="recipe-product-name" class="text-[#4E8F35]"></span>
                    </h3>
                    <p class="text-xs text-[#73777A]">حدد الخامات المستهلكة لعمل الصنف وسيتم خصمها آلياً من المخزن عند كل بيع</p>
                </div>
                <button type="button" onclick="closeModal('modal-recipe')" class="w-8 h-8 rounded-lg bg-[#F5F3EE] text-[#73777A] hover:text-[#303334] flex items-center justify-center font-bold transition">
                    ✕
                </button>
            </div>

            <form id="form-recipe" action="" method="POST" class="flex-1 overflow-y-auto my-3 pr-1">
                @csrf
                <div class="p-3 bg-[#F8F7F4] rounded-xl border border-[#E5E2DC] mb-4 text-xs">
                    💡 <strong>كيف يعمل نظام الخامات؟</strong> عند بيع هذا الصنف في الكاشير، سيقوم النظام تلقائياً بخصم الكميات المحددة هنا من أرصدة الخامات في المستودع فوراً، وإعادة احتساب تكلفة الصنف الفعلية.
                </div>

                <div class="space-y-3" id="recipe-ingredients-container">
                    {{-- Dynamically populated rows --}}
                </div>

                <div class="mt-4 pt-2">
                    <button type="button" onclick="addRecipeIngredientRow()"
                            class="px-3.5 py-1.5 rounded-xl bg-[#EBF4E8] text-[#4E8F35] hover:bg-[#DCE8D4] text-xs font-bold transition flex items-center gap-1.5 cursor-pointer">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        <span>+ إضافة خامة للمكونات</span>
                    </button>
                </div>

                <div class="mt-4 p-3 bg-[#F5F3EE] rounded-xl border border-[#E5E2DC] flex items-center justify-between text-xs">
                    <span class="font-bold text-[#303334]">التكلفة الإجمالية المحسوبة للخامات:</span>
                    <span id="recipe-total-cost-display" class="font-mono font-black text-sm text-[#4E8F35]">0.00 ج.م</span>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-[#E5E2DC] mt-4">
                    <button type="button" onclick="closeModal('modal-recipe')" class="px-4 py-2 bg-[#F5F3EE] hover:bg-[#E5E2DC] text-[#303334] rounded-xl text-xs font-bold transition">
                        إلغاء
                    </button>
                    <button type="submit" class="px-5 py-2 bg-[#4E8F35] hover:bg-[#3F742B] text-white rounded-xl text-xs font-bold transition shadow-xs cursor-pointer">
                        حفظ المكونات وتحديث التكلفة
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════ --}}
    {{-- MODAL 4: تأكيد حذف الصنف (Delete Product Modal)          --}}
    {{-- ════════════════════════════════════════════════════════ --}}
    <div id="modal-delete-product" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div class="bg-white border border-[#E5E2DC] rounded-3xl max-w-md w-full p-6 shadow-2xl text-[#303334] animate-scale-up">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mb-4">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
            </div>

            <h3 class="font-black text-base text-[#303334] mb-1">هل أنت متأكد من حذف الصنف؟</h3>
            <p class="text-xs text-[#73777A] leading-relaxed mb-4">
                سيتم حذف الصنف <strong id="delete-prod-name-display" class="text-[#303334]"></strong> من قائمة المشروبات والمأكولات.
            </p>

            <form id="form-delete-product" action="" method="POST" class="flex items-center justify-end gap-2.5">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeModal('modal-delete-product')" class="px-4 py-2 bg-[#F5F3EE] hover:bg-[#E5E2DC] text-[#303334] rounded-xl text-xs font-bold transition">
                    إلغاء
                </button>
                <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition shadow-xs cursor-pointer">
                    نعم، احذف الصنف
                </button>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    const availableRawMaterials = @json($rawMaterials);

    function openModal(id) {
        const el = document.getElementById(id);
        if (el) el.classList.remove('hidden');
    }

    function closeModal(id) {
        const el = document.getElementById(id);
        if (el) el.classList.add('hidden');
    }

    function openAddProductModal() {
        openModal('modal-add-product');
    }

    function openEditProductModal(prod) {
        const form = document.getElementById('form-edit-product');
        form.action = `/products/${prod.id}`;

        document.getElementById('edit-prod-name').value = prod.name || '';
        document.getElementById('edit-prod-category').value = prod.category_id || '';
        document.getElementById('edit-prod-unit').value = prod.unit || '';
        document.getElementById('edit-prod-selling').value = prod.selling_price || 0;
        document.getElementById('edit-prod-purchase').value = prod.purchase_price || 0;
        document.getElementById('edit-prod-active').checked = !!prod.active;

        openModal('modal-edit-product');
    }

    function openDeleteProductModal(prodId, prodName) {
        const form = document.getElementById('form-delete-product');
        form.action = `/products/${prodId}`;
        document.getElementById('delete-prod-name-display').textContent = prodName;
        openModal('modal-delete-product');
    }

    let recipeRowIndex = 0;

    function openRecipeModal(prod) {
        const form = document.getElementById('form-recipe');
        form.action = `/products/${prod.id}/ingredients`;
        document.getElementById('recipe-product-name').textContent = prod.name;

        const container = document.getElementById('recipe-ingredients-container');
        container.innerHTML = '';
        recipeRowIndex = 0;

        const currentIngredients = prod.ingredients || [];
        if (currentIngredients.length > 0) {
            currentIngredients.forEach(ing => {
                addRecipeIngredientRow(ing.raw_material_id, ing.quantity);
            });
        } else {
            addRecipeIngredientRow();
        }

        recalculateRecipeTotalCost();
        openModal('modal-recipe');
    }

    function addRecipeIngredientRow(selectedMaterialId = null, quantity = 1) {
        const container = document.getElementById('recipe-ingredients-container');
        const rowIndex = recipeRowIndex++;

        let optionsHtml = '<option value="">-- اختر الخامة --</option>';
        availableRawMaterials.forEach(rm => {
            const isSel = selectedMaterialId == rm.id ? 'selected' : '';
            optionsHtml += `<option value="${rm.id}" data-cost="${rm.unit_cost}" data-unit="${rm.unit}" ${isSel}>${rm.name} (${rm.unit} — تكلفة ${parseFloat(rm.unit_cost).toFixed(2)} ج.م)</option>`;
        });

        const row = document.createElement('div');
        row.className = 'grid grid-cols-12 gap-2.5 items-center p-2.5 rounded-xl bg-[#F8F7F4] border border-[#E5E2DC] recipe-row';
        row.id = `recipe-row-${rowIndex}`;
        row.innerHTML = `
            <div class="col-span-7">
                <select name="ingredients[${rowIndex}][raw_material_id]" required onchange="recalculateRecipeTotalCost()"
                        class="w-full bg-white border border-[#E5E2DC] text-[#303334] text-xs font-bold rounded-lg px-2.5 py-2 outline-none focus:border-[#4E8F35] recipe-mat-select">
                    ${optionsHtml}
                </select>
            </div>
            <div class="col-span-4">
                <input type="number" step="0.1" min="0.01" name="ingredients[${rowIndex}][quantity]" value="${quantity}" required
                       oninput="recalculateRecipeTotalCost()" placeholder="الكمية"
                       class="w-full bg-white border border-[#E5E2DC] text-[#303334] text-xs font-mono font-bold rounded-lg px-2.5 py-2 outline-none focus:border-[#4E8F35] recipe-qty-input">
            </div>
            <div class="col-span-1 text-center">
                <button type="button" onclick="removeRecipeIngredientRow(${rowIndex})"
                        class="text-slate-400 hover:text-rose-600 transition cursor-pointer p-1" title="حذف الخامة">
                    ✕
                </button>
            </div>
        `;
        container.appendChild(row);
        recalculateRecipeTotalCost();
    }

    function removeRecipeIngredientRow(index) {
        const row = document.getElementById(`recipe-row-${index}`);
        if (row) row.remove();
        recalculateRecipeTotalCost();
    }

    function recalculateRecipeTotalCost() {
        let total = 0;
        document.querySelectorAll('.recipe-row').forEach(row => {
            const select = row.querySelector('.recipe-mat-select');
            const qtyInput = row.querySelector('.recipe-qty-input');
            if (select && qtyInput && select.value) {
                const selectedOpt = select.options[select.selectedIndex];
                const unitCost = parseFloat(selectedOpt.dataset.cost) || 0;
                const qty = parseFloat(qtyInput.value) || 0;
                total += (unitCost * qty);
            }
        });
        document.getElementById('recipe-total-cost-display').textContent = `${total.toFixed(2)} ج.م`;
    }

    // Close on escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal('modal-add-product');
            closeModal('modal-edit-product');
            closeModal('modal-recipe');
            closeModal('modal-delete-product');
        }
    });
</script>
@endsection
