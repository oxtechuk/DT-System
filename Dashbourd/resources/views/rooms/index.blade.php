@extends('shared.vertical', ['title' => 'إدارة الغرف والمساحات — DDT WORKING SPACE'])

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
          
            <h1 class="text-2xl font-extrabold text-[#303334] tracking-tight">
                الغرف والمساحات
            </h1>
            <p class="text-xs text-[#73777A] mt-1">إدارة وتسمية الغرف، ضبط السعة الاستيعابية، والتعديل والحذف</p>
        </div>

        <div class="page-header-actions">
            {{-- Add New Room Button --}}
            <button type="button" onclick="openAddRoomModal()"
                    class="px-4 py-2.5 bg-[#4E8F35] hover:bg-[#3F742B] text-white rounded-xl text-xs font-bold transition-all shadow-xs gap-2 flex items-center cursor-pointer">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                <span>إضافة غرفة جديدة</span>
            </button>

            {{-- Open Cashier Quick Link --}}
            <a href="{{ url('/cashier') }}" target="_blank"
               class="px-4 py-2.5 bg-[#F5F3EE] hover:bg-[#EBF4E8] text-[#303334] hover:text-[#4E8F35] border border-[#E5E2DC] hover:border-[#4E8F35]/40 rounded-xl text-xs font-bold transition-all shadow-xs gap-2 flex items-center">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
                <span class="dt-stat-label">إجمالي الغرف</span>
                <div class="dt-stat-value">{{ $rooms->count() }}</div>
            </div>
            <div class="dt-stat-icon">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M13 4h3a2 2 0 0 1 2 2v14"/><path d="M2 20h20"/><path d="M13 20V4a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v16"/>
                </svg>
            </div>
        </div>

        <div class="dt-stat-card">
            <div class="dt-stat-info">
                <span class="dt-stat-label">الغرف المتاحة حالياً</span>
                <div class="dt-stat-value text-[#4E8F35]">{{ $availableRooms }}</div>
            </div>
            <div class="dt-stat-icon">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
            </div>
        </div>

        <div class="dt-stat-card">
            <div class="dt-stat-info">
                <span class="dt-stat-label">إجمالي السعة</span>
                <div class="dt-stat-value">{{ $totalCapacity }} <span class="text-xs font-normal text-[#73777A]">فرد</span></div>
            </div>
            <div class="dt-stat-icon">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Unified Rooms Grid --}}
    <div class="dt-grid-3">
        @forelse($rooms as $room)
            @php
                $isAvailable = $room->is_available;
                $deal = $room->activeDeals->first();
                $isMaintenance = $room->status === 'maintenance';
                $isInactive = $room->status === 'inactive';
            @endphp
            <div class="bg-white border border-[#E5E2DC] hover:border-[#4E8F35]/60 rounded-2xl p-5 shadow-xs transition-all flex flex-col justify-between group">
                <div>
                    {{-- Card Header: Status & Code & Actions --}}
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            @if($isMaintenance)
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    تحت الصيانة
                                </span>
                            @elseif($isInactive)
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                    معطلة
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $isAvailable ? 'bg-[#EBF4E8] text-[#4E8F35] border border-[#DCE8D4]' : 'bg-[#F5F3EE] text-[#303334] border border-[#E5E2DC]' }}">
                                    {{ $isAvailable ? 'شاغرة ومتاحة' : 'مشغولة حالياً' }}
                                </span>
                            @endif
                            <span class="text-xs text-[#73777A] font-mono font-bold">{{ $room->code }}</span>
                        </div>

                        {{-- Quick Controls Menu --}}
                        <div class="flex items-center gap-1">
                            <button type="button"
                                    onclick="openEditRoomModal({{ json_encode($room) }})"
                                    title="تعديل بيانات وتسمية الغرفة"
                                    class="p-1.5 rounded-lg bg-[#F5F3EE] hover:bg-[#EBF4E8] text-[#73777A] hover:text-[#4E8F35] transition-all cursor-pointer">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </button>

                            <button type="button"
                                    onclick="openDeleteRoomModal({{ $room->id }}, '{{ addslashes($room->name) }}', {{ $deal ? 'true' : 'false' }})"
                                    title="حذف الغرفة"
                                    class="p-1.5 rounded-lg bg-[#F5F3EE] hover:bg-rose-50 text-[#73777A] hover:text-rose-600 transition-all cursor-pointer">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Room Name & Description --}}
                    <h2 class="text-base font-extrabold text-[#303334] mb-1 flex items-center justify-between">
                        <span>{{ $room->name }}</span>
                    </h2>
                    <p class="text-xs text-[#73777A] mb-3.5 leading-relaxed">{{ $room->description ?: 'قاعة عمل ومساحة مشتركة هادئة ومجهزة' }}</p>

                    {{-- Room Specs Box --}}
                    <div class="flex items-center gap-4 text-xs text-[#73777A] mb-3.5 bg-[#F8F7F4] p-2.5 rounded-xl border border-[#E5E2DC]">
                        <div>السعة: <strong class="text-[#303334] font-mono font-bold">{{ $room->capacity }}</strong> أفراد</div>
                        <div class="border-s border-[#E5E2DC] ps-4">
                            الحالة: <strong class="text-[#4E8F35] font-bold">{{ $room->status === 'active' ? 'نشطة' : ($room->status === 'maintenance' ? 'صيانة' : 'معطلة') }}</strong>
                        </div>
                    </div>

                    {{-- Status details inside card --}}
                    @if(!$isAvailable && $deal)
                        <div class="p-3 bg-[#F8F7F4] border border-[#E5E2DC] rounded-xl mb-3 text-xs">
                            <div class="font-bold text-[#303334] flex items-center justify-between">
                                <span>مشغولة بواسطة: {{ $deal->customer->name ?? 'عميل' }}</span>
                            </div>
                            <div class="text-[11px] text-[#73777A] font-mono mt-1 flex items-center justify-between">
                                <span>منذ {{ $deal->started_at->format('h:i A') }}</span>
                                <span class="text-[#4E8F35] font-bold">جلسة نشطة</span>
                            </div>
                        </div>
                    @elseif($isMaintenance)
                        <div class="p-3 bg-amber-50/70 border border-amber-200 rounded-xl mb-3 text-xs flex items-center justify-between">
                            <span class="text-amber-800 font-medium">الغرفة خارج الخدمة حالياً لأعمال الصيانة</span>
                            <span class="text-[10px] text-amber-700 font-bold bg-white px-2 py-0.5 rounded-md border border-amber-200">صيانة</span>
                        </div>
                    @else
                        <div class="p-3 bg-[#F7FAF5] border border-[#DCE8D4] rounded-xl mb-3 text-xs flex items-center justify-between">
                            <span class="text-[#4E8F35] font-medium">جاهزة لاستقبال جلسات العمل</span>
                            <span class="text-[10px] text-[#4E8F35] font-bold bg-white px-2 py-0.5 rounded-md border border-[#DCE8D4]">متاحة</span>
                        </div>
                    @endif
                </div>

                {{-- Action Footer --}}
                <div class="pt-3 border-t border-[#E5E2DC] flex items-center justify-between">
                    <button type="button" onclick="openEditRoomModal({{ json_encode($room) }})"
                            class="text-xs font-bold text-[#73777A] hover:text-[#4E8F35] transition flex items-center gap-1 cursor-pointer">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                        <span>تعديل وتسمية</span>
                    </button>

                    <a href="{{ url('/cashier') }}" target="_blank"
                       class="text-xs font-bold text-[#4E8F35] hover:text-[#3F742B] transition flex items-center gap-1">
                        <span>إدارة بالكاشير</span>
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full p-12 bg-white rounded-2xl border border-[#E5E2DC] text-center">
                <div class="w-16 h-16 rounded-2xl bg-[#F5F3EE] text-[#73777A] mx-auto flex items-center justify-center mb-3">
                    <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M13 4h3a2 2 0 0 1 2 2v14"/><path d="M2 20h20"/><path d="M13 20V4a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v16"/>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-[#303334] mb-1">لا توجد غرف مسجلة حالياً</h3>
                <p class="text-xs text-[#73777A] mb-4">ابدأ بإضافة أول غرفة أو قاعة عمل في مساحتك الآن</p>
                <button type="button" onclick="openAddRoomModal()" class="px-4 py-2 bg-[#4E8F35] text-white rounded-xl text-xs font-bold hover:bg-[#3F742B] transition shadow-xs">
                    + إضافة غرفة جديدة
                </button>
            </div>
        @endforelse
    </div>

    {{-- ════════════════════════════════════════════════════════ --}}
    {{-- MODAL 1: إضافة غرفة جديدة (Add Room Modal)               --}}
    {{-- ════════════════════════════════════════════════════════ --}}
    <div id="modal-add-room" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div class="bg-white border border-[#E5E2DC] rounded-3xl max-w-lg w-full p-6 shadow-2xl text-[#303334] animate-scale-up">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-[#E5E2DC]">
                <div>
                    <h3 class="font-black text-base text-[#303334]">إضافة غرفة / مساحة جديدة</h3>
                    <p class="text-xs text-[#73777A]">تعريف قاعة جديدة وتحديد سعتها الاستيعابية وحالتها</p>
                </div>
                <button type="button" onclick="closeModal('modal-add-room')" class="w-8 h-8 rounded-lg bg-[#F5F3EE] text-[#73777A] hover:text-[#303334] flex items-center justify-center font-bold transition">
                    ✕
                </button>
            </div>

            <form action="{{ route('rooms.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    {{-- Room Name --}}
                    <div>
                        <label class="block text-xs font-bold text-[#303334] mb-1.5">اسم الغرفة / القاعة *</label>
                        <input type="text" name="name" required placeholder="مثال: Room A أو قاعة الاجتماعات الكبرى"
                               class="w-full bg-[#F5F3EE] border border-[#E5E2DC] text-[#303334] text-xs font-semibold rounded-xl px-3.5 py-2.5 focus:border-[#4E8F35] focus:bg-white focus:ring-2 focus:ring-[#EBF4E8] outline-none transition">
                    </div>

                    {{-- Code & Capacity --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-[#303334] mb-1.5">الكود التعريفي (اختياري)</label>
                            <input type="text" name="code" placeholder="مثال: RA أو M1"
                                   class="w-full bg-[#F5F3EE] border border-[#E5E2DC] text-[#303334] text-xs font-mono font-semibold rounded-xl px-3.5 py-2.5 focus:border-[#4E8F35] focus:bg-white outline-none transition">
                            <span class="text-[10px] text-[#73777A] mt-1 block">يُنشأ تلقائياً إذا تُرك فارغاً</span>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#303334] mb-1.5">السعة الاستيعابية (أفراد) *</label>
                            <input type="number" name="capacity" required min="1" value="4"
                                   class="w-full bg-[#F5F3EE] border border-[#E5E2DC] text-[#303334] text-xs font-mono font-bold rounded-xl px-3.5 py-2.5 focus:border-[#4E8F35] focus:bg-white outline-none transition">
                        </div>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-xs font-bold text-[#303334] mb-1.5">حالة الغرفة التشغيلية *</label>
                        <select name="status" required class="w-full bg-[#F5F3EE] border border-[#E5E2DC] text-[#303334] text-xs font-bold rounded-xl px-3.5 py-2.5 focus:border-[#4E8F35] focus:bg-white outline-none">
                            <option value="active" selected>نشطة ومتاحة للاستخدام</option>
                            <option value="maintenance">تحت الصيانة المؤقتة</option>
                            <option value="inactive">غير نشطة (معطلة)</option>
                        </select>
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-xs font-bold text-[#303334] mb-1.5">الوصف والتجهيزات</label>
                        <textarea name="description" rows="3" placeholder="مثال: شاشة عرض 55 بوصة، تكييف، وايت بورد، إضاءة هادئة..."
                                  class="w-full bg-[#F5F3EE] border border-[#E5E2DC] text-[#303334] text-xs rounded-xl px-3.5 py-2.5 focus:border-[#4E8F35] focus:bg-white outline-none transition"></textarea>
                    </div>

                    {{-- Submit & Cancel --}}
                    <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-[#E5E2DC]">
                        <button type="button" onclick="closeModal('modal-add-room')" class="px-4 py-2 bg-[#F5F3EE] hover:bg-[#E5E2DC] text-[#303334] rounded-xl text-xs font-bold transition">
                            إلغاء
                        </button>
                        <button type="submit" class="px-5 py-2 bg-[#4E8F35] hover:bg-[#3F742B] text-white rounded-xl text-xs font-bold transition shadow-xs cursor-pointer">
                            حفظ الغرفة
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════ --}}
    {{-- MODAL 2: تعديل وتسمية الغرفة (Edit Room Modal)          --}}
    {{-- ════════════════════════════════════════════════════════ --}}
    <div id="modal-edit-room" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div class="bg-white border border-[#E5E2DC] rounded-3xl max-w-lg w-full p-6 shadow-2xl text-[#303334] animate-scale-up">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-[#E5E2DC]">
                <div>
                    <h3 class="font-black text-base text-[#303334]">تعديل وتسمية الغرفة</h3>
                    <p class="text-xs text-[#73777A]">تغيير الاسم، السعة الاستيعابية، الكود أو الحالة</p>
                </div>
                <button type="button" onclick="closeModal('modal-edit-room')" class="w-8 h-8 rounded-lg bg-[#F5F3EE] text-[#73777A] hover:text-[#303334] flex items-center justify-center font-bold transition">
                    ✕
                </button>
            </div>

            <form id="form-edit-room" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    {{-- Room Name --}}
                    <div>
                        <label class="block text-xs font-bold text-[#303334] mb-1.5">اسم الغرفة / القاعة *</label>
                        <input type="text" id="edit-room-name" name="name" required
                               class="w-full bg-[#F5F3EE] border border-[#E5E2DC] text-[#303334] text-xs font-semibold rounded-xl px-3.5 py-2.5 focus:border-[#4E8F35] focus:bg-white focus:ring-2 focus:ring-[#EBF4E8] outline-none transition">
                    </div>

                    {{-- Code & Capacity --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-[#303334] mb-1.5">الكود التعريفي</label>
                            <input type="text" id="edit-room-code" name="code"
                                   class="w-full bg-[#F5F3EE] border border-[#E5E2DC] text-[#303334] text-xs font-mono font-semibold rounded-xl px-3.5 py-2.5 focus:border-[#4E8F35] focus:bg-white outline-none transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#303334] mb-1.5">السعة الاستيعابية (أفراد) *</label>
                            <input type="number" id="edit-room-capacity" name="capacity" required min="1"
                                   class="w-full bg-[#F5F3EE] border border-[#E5E2DC] text-[#303334] text-xs font-mono font-bold rounded-xl px-3.5 py-2.5 focus:border-[#4E8F35] focus:bg-white outline-none transition">
                        </div>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-xs font-bold text-[#303334] mb-1.5">حالة الغرفة التشغيلية *</label>
                        <select id="edit-room-status" name="status" required class="w-full bg-[#F5F3EE] border border-[#E5E2DC] text-[#303334] text-xs font-bold rounded-xl px-3.5 py-2.5 focus:border-[#4E8F35] focus:bg-white outline-none">
                            <option value="active">نشطة ومتاحة للاستخدام</option>
                            <option value="maintenance">تحت الصيانة المؤقتة</option>
                            <option value="inactive">غير نشطة (معطلة)</option>
                        </select>
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-xs font-bold text-[#303334] mb-1.5">الوصف والتجهيزات</label>
                        <textarea id="edit-room-description" name="description" rows="3"
                                  class="w-full bg-[#F5F3EE] border border-[#E5E2DC] text-[#303334] text-xs rounded-xl px-3.5 py-2.5 focus:border-[#4E8F35] focus:bg-white outline-none transition"></textarea>
                    </div>

                    {{-- Submit & Cancel --}}
                    <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-[#E5E2DC]">
                        <button type="button" onclick="closeModal('modal-edit-room')" class="px-4 py-2 bg-[#F5F3EE] hover:bg-[#E5E2DC] text-[#303334] rounded-xl text-xs font-bold transition">
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
    {{-- MODAL 3: تأكيد حذف الغرفة (Delete Confirmation Modal)    --}}
    {{-- ════════════════════════════════════════════════════════ --}}
    <div id="modal-delete-room" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div class="bg-white border border-[#E5E2DC] rounded-3xl max-w-md w-full p-6 shadow-2xl text-[#303334] animate-scale-up">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mb-4">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
            </div>

            <h3 class="font-black text-base text-[#303334] mb-1">هل أنت متأكد من حذف الغرفة؟</h3>
            <p class="text-xs text-[#73777A] leading-relaxed mb-4">
                سيتم حذف غرفة <strong id="delete-room-name-display" class="text-[#303334]"></strong> بشكل نهائي من النظام. لا يمكن التراجع عن هذه الخطوة.
            </p>

            <div id="delete-room-warning" class="hidden mb-4 p-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs">
                ⚠️ <strong>تنبيه:</strong> توجد جلسة نشطة حالياً في هذه الغرفة. لن تتمكن من حذفها حتى يتم إنهاء الحساب من الكاشير.
            </div>

            <form id="form-delete-room" action="" method="POST" class="flex items-center justify-end gap-2.5">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeModal('modal-delete-room')" class="px-4 py-2 bg-[#F5F3EE] hover:bg-[#E5E2DC] text-[#303334] rounded-xl text-xs font-bold transition">
                    إلغاء
                </button>
                <button type="submit" id="btn-confirm-delete" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition shadow-xs cursor-pointer">
                    نعم، احذف الغرفة
                </button>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    function openModal(id) {
        const el = document.getElementById(id);
        if (el) el.classList.remove('hidden');
    }

    function closeModal(id) {
        const el = document.getElementById(id);
        if (el) el.classList.add('hidden');
    }

    function openAddRoomModal() {
        openModal('modal-add-room');
    }

    function openEditRoomModal(room) {
        const form = document.getElementById('form-edit-room');
        form.action = `/rooms/${room.id}`;

        document.getElementById('edit-room-name').value = room.name || '';
        document.getElementById('edit-room-code').value = room.code || '';
        document.getElementById('edit-room-capacity').value = room.capacity || 1;
        document.getElementById('edit-room-status').value = room.status || 'active';
        document.getElementById('edit-room-description').value = room.description || '';

        openModal('modal-edit-room');
    }

    function openDeleteRoomModal(roomId, roomName, hasActiveDeal) {
        const form = document.getElementById('form-delete-room');
        form.action = `/rooms/${roomId}`;

        document.getElementById('delete-room-name-display').textContent = roomName;
        const warning = document.getElementById('delete-room-warning');
        const submitBtn = document.getElementById('btn-confirm-delete');

        if (hasActiveDeal) {
            warning.classList.remove('hidden');
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            warning.classList.add('hidden');
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }

        openModal('modal-delete-room');
    }

    // Close modals on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal('modal-add-room');
            closeModal('modal-edit-room');
            closeModal('modal-delete-room');
        }
    });
</script>
@endsection
