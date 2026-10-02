@extends('shared.vertical', ['title' => 'دليل وسجل العملاء — DDT WORKING SPACE'])

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

    @if(isset($errors) && $errors->any())
        <div class="mb-4 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs space-y-1.5 shadow-xs">
            <div class="font-black flex items-center gap-2">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>تعذر حفظ البيانات، يرجى تصحيح الأخطاء التالية:</span>
            </div>
            <ul class="list-disc list-inside pe-2 space-y-1">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="page-header-container mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-[#303334] tracking-tight">
                دليل وسجل العملاء
            </h1>
            <p class="text-xs text-[#73777A] mt-1">إدارة بيانات العملاء، التحقق من الاسم ورقم الموبايل، تصنيف نوع العميل ومصدر الاكتساب</p>
        </div>

        <div class="page-header-actions flex items-center gap-2.5 flex-wrap">
            <button type="button" onclick="openImportModal()"
                    class="px-4 py-2.5 bg-[#111827] hover:bg-[#1F2937] text-white rounded-xl text-xs font-bold transition-all shadow-xs gap-2 flex items-center cursor-pointer border border-[#374151]">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                <span>استيراد من CSV / إكسيل</span>
            </button>

            <button type="button" onclick="openAddCustomerModal()"
                    class="px-4 py-2.5 bg-[#4E8F35] hover:bg-[#3F742B] text-white rounded-xl text-xs font-bold transition-all shadow-xs gap-2 flex items-center cursor-pointer">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                <span>إضافة عميل جديد</span>
            </button>

            <a href="{{ url('/cashier') }}" target="_blank"
               class="px-4 py-2.5 bg-[#F5F3EE] hover:bg-[#EBF4E8] text-[#303334] hover:text-[#4E8F35] border border-[#E5E2DC] hover:border-[#4E8F35]/40 rounded-xl text-xs font-bold transition-all shadow-xs gap-2 flex items-center">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/>
                </svg>
                <span>شاشة الكاشير السريع ↗</span>
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Total Customers --}}
        <div class="p-4 rounded-2xl bg-white border border-[#E5E2DC] shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-[#73777A] block">إجمالي العملاء</span>
                <div class="text-2xl font-black text-[#303334] mt-1">{{ number_format($totalCustomers) }}</div>
            </div>
            <div class="size-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                </svg>
            </div>
        </div>

        {{-- Registered Customers --}}
        <div class="p-4 rounded-2xl bg-white border border-[#E5E2DC] shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-[#73777A] block">العملاء المسجلون (دائم)</span>
                <div class="text-2xl font-black text-indigo-600 mt-1">{{ number_format($registeredCustomers) }}</div>
            </div>
            <div class="size-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M20 21v-2a4 4 0 0 0-3-3.87"/><path d="M4 21v-2a4 4 0 0 1 3-3.87"/><circle cx="12" cy="7" r="4"/>
                </svg>
            </div>
        </div>

        {{-- Guest Customers --}}
        <div class="p-4 rounded-2xl bg-white border border-[#E5E2DC] shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-[#73777A] block">العملاء الزوار (مؤقت)</span>
                <div class="text-2xl font-black text-amber-600 mt-1">{{ number_format($guestCustomers) }}</div>
            </div>
            <div class="size-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                </svg>
            </div>
        </div>

        {{-- Active Customers --}}
        <div class="p-4 rounded-2xl bg-white border border-[#E5E2DC] shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-[#73777A] block">العملاء النشطون</span>
                <div class="text-2xl font-black text-[#4E8F35] mt-1">{{ number_format($activeCustomers) }}</div>
            </div>
            <div class="size-11 rounded-xl bg-[#EBF4E8] text-[#4E8F35] flex items-center justify-center">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Search & Filters --}}
    <div class="bg-white rounded-2xl border border-[#E5E2DC] p-4 mb-6 shadow-xs">
        <form method="GET" action="{{ route('customers.index') }}" class="flex flex-col md:flex-row items-center gap-3">
            {{-- Search Input --}}
            <div class="relative flex-1 w-full">
                <input type="text" name="search" value="{{ $search }}"
                       placeholder="ابحث باسم العميل، رقم الموبايل، أو البريد الإلكتروني..."
                       class="w-full px-3.5 py-2.5 pe-10 border border-[#E5E2DC] rounded-xl text-xs focus:border-[#4E8F35] focus:ring-1 focus:ring-[#4E8F35] outline-none">
                <button type="submit" class="absolute end-3 top-2.5 text-[#73777A] hover:text-[#303334]">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/>
                    </svg>
                </button>
            </div>

            {{-- Customer Type Filter --}}
            <div class="w-full md:w-44">
                <select name="customer_type" onchange="this.form.submit()"
                        class="w-full px-3 py-2.5 border border-[#E5E2DC] rounded-xl text-xs font-bold text-[#303334] focus:border-[#4E8F35] outline-none bg-white">
                    <option value="">جميع أنواع العملاء</option>
                    <option value="registered" {{ ($customerType ?? '') === 'registered' ? 'selected' : '' }}>مسجل (دائم)</option>
                    <option value="guest" {{ ($customerType ?? '') === 'guest' ? 'selected' : '' }}>زائر (مؤقت)</option>
                </select>
            </div>

            {{-- Source Filter --}}
            <div class="w-full md:w-48">
                <select name="source" onchange="this.form.submit()"
                        class="w-full px-3 py-2.5 border border-[#E5E2DC] rounded-xl text-xs font-bold text-[#303334] focus:border-[#4E8F35] outline-none bg-white">
                    <option value="">جميع المصادر</option>
                    <option value="walk-in" {{ ($source ?? '') === 'walk-in' ? 'selected' : '' }}>زيارة مباشرة (استقبال)</option>
                    <option value="mobile" {{ ($source ?? '') === 'mobile' ? 'selected' : '' }}>تواصل هاتفي / واتساب</option>
                    <option value="portal" {{ ($source ?? '') === 'portal' ? 'selected' : '' }}>بوابة الموبايل الذكية</option>
                    <option value="referral" {{ ($source ?? '') === 'referral' ? 'selected' : '' }}>إحالة / ترشيح صديق</option>
                    <option value="social_media" {{ ($source ?? '') === 'social_media' ? 'selected' : '' }}>سوشيال ميديا وإعلانات</option>
                    <option value="hubspot" {{ ($source ?? '') === 'hubspot' ? 'selected' : '' }}>HubSpot CRM</option>
                    <option value="other" {{ ($source ?? '') === 'other' ? 'selected' : '' }}>أخرى</option>
                </select>
            </div>

            {{-- Classification Filter --}}
            <div class="w-full md:w-44">
                <select name="classification" onchange="this.form.submit()"
                        class="w-full px-3 py-2.5 border border-[#E5E2DC] rounded-xl text-xs font-bold text-[#303334] focus:border-[#4E8F35] outline-none bg-white">
                    <option value="">جميع التصنيفات</option>
                    <option value="freelancer" {{ request('classification') === 'freelancer' ? 'selected' : '' }}>فريلانسر / عمل حر</option>
                    <option value="high_school" {{ request('classification') === 'high_school' ? 'selected' : '' }}>طالب ثانوي</option>
                    <option value="university" {{ request('classification') === 'university' ? 'selected' : '' }}>طالب جامعي</option>
                    <option value="lecturer" {{ request('classification') === 'lecturer' ? 'selected' : '' }}>محاضر / مدرب</option>
                    <option value="other" {{ request('classification') === 'other' ? 'selected' : '' }}>أخرى / عام</option>
                </select>
            </div>

            {{-- Submit & Reset --}}
            <div class="flex items-center gap-2 w-full md:w-auto">
                <button type="submit" class="w-full md:w-auto px-4 py-2.5 bg-[#4E8F35] text-white rounded-xl text-xs font-bold hover:bg-[#3F742B] transition-all">
                    تصفية
                </button>
                @if($search || $customerType || $source || request('classification'))
                    <a href="{{ route('customers.index') }}"
                       class="px-3 py-2.5 bg-[#F5F3EE] hover:bg-[#E5E2DC] text-[#73777A] hover:text-[#303334] rounded-xl text-xs font-bold transition-all whitespace-nowrap">
                        إعادة ضبط
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Customers Table Card --}}
    <div class="bg-white rounded-2xl border border-[#E5E2DC] p-5 shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-start text-xs border-collapse">
                <thead>
                    <tr class="border-b border-[#E5E2DC] text-[#73777A] text-[11px] font-black uppercase tracking-wider">
                        <th class="py-3 px-3 text-start">العميل</th>
                        <th class="py-3 px-3 text-start">رقم الموبايل</th>
                        <th class="py-3 px-3 text-start">نوع العميل</th>
                        <th class="py-3 px-3 text-start">التصنيف</th>
                        <th class="py-3 px-3 text-start">مصدر العميل</th>
                        <th class="py-3 px-3 text-start">البريد الإلكتروني</th>
                        <th class="py-3 px-3 text-center">الجلسات</th>
                        <th class="py-3 px-3 text-center">الحالة</th>
                        <th class="py-3 px-3 text-end">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F0EDE6]">
                    @forelse($customers as $c)
                        @php
                            // Label for customer source
                            $sourceMap = [
                                'walk-in' => ['label' => 'زيارة مباشرة', 'bg' => 'bg-slate-100 text-slate-700 border-slate-200'],
                                'reception' => ['label' => 'استقبال / كاشير', 'bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                                'mobile' => ['label' => 'هاتف / واتساب', 'bg' => 'bg-teal-50 text-teal-700 border-teal-200'],
                                'portal' => ['label' => 'بوابة الموبايل', 'bg' => 'bg-purple-50 text-purple-700 border-purple-200'],
                                'referral' => ['label' => 'إحالة وترشيح', 'bg' => 'bg-blue-50 text-blue-700 border-blue-200'],
                                'affiliate' => ['label' => 'إحالة وترشيح', 'bg' => 'bg-blue-50 text-blue-700 border-blue-200'],
                                'social_media' => ['label' => 'سوشيال ميديا', 'bg' => 'bg-pink-50 text-pink-700 border-pink-200'],
                                'hubspot' => ['label' => 'HubSpot CRM', 'bg' => 'bg-orange-50 text-orange-700 border-orange-200'],
                                'other' => ['label' => 'أخرى', 'bg' => 'bg-gray-100 text-gray-700 border-gray-200'],
                            ];
                            $sourceInfo = $sourceMap[$c->source] ?? ['label' => $c->source ?: 'زيارة مباشرة', 'bg' => 'bg-slate-100 text-slate-700 border-slate-200'];
                        @endphp
                        <tr class="hover:bg-[#FAF9F5] transition-colors">
                            {{-- Customer Name & Avatar --}}
                            <td class="py-3 px-3">
                                <div class="flex items-center gap-3">
                                    <div class="size-9 rounded-full bg-[#EBF4E8] text-[#4E8F35] font-black flex items-center justify-center text-xs shrink-0 border border-[#DCE8D4]">
                                        {{ $c->initials ?: 'ع' }}
                                    </div>
                                    <div>
                                        <div class="font-extrabold text-[#303334] text-xs">
                                            {{ $c->full_name ?: $c->name }}
                                        </div>
                                        @if(!empty($c->referral_code))
                                            <span class="inline-flex items-center gap-1 text-[10px] font-mono text-[#73777A] mt-0.5">
                                                <span>كود:</span>
                                                <span class="bg-[#F5F3EE] px-1.5 py-0.5 rounded font-bold text-[#303334]">{{ $c->referral_code }}</span>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Mobile Phone --}}
                            <td class="py-3 px-3">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-xs font-bold text-[#303334]" dir="ltr">{{ $c->phone }}</span>
                                    @php
                                        $cleanPhone = preg_replace('/[^0-9]/', '', $c->phone);
                                        if (str_starts_with($cleanPhone, '01')) {
                                            $waPhone = '2' . $cleanPhone;
                                        } else {
                                            $waPhone = $cleanPhone;
                                        }
                                    @endphp
                                    <a href="https://wa.me/{{ $waPhone }}" target="_blank" title="محادثة واتساب"
                                       class="text-emerald-600 hover:text-emerald-700 p-1 rounded hover:bg-emerald-50 transition">
                                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.275.072.376-.044c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.043.073.043.419-.101.824z"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>

                            {{-- Customer Type Badge --}}
                            <td class="py-3 px-3">
                                @if($c->customer_type === 'guest')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                                        <span>زائر</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-3-3.87"/><path d="M4 21v-2a4 4 0 0 1 3-3.87"/><circle cx="12" cy="7" r="4"/></svg>
                                        <span>مسجل</span>
                                    </span>
                                @endif
                            </td>

                            {{-- Customer Classification Badge --}}
                            <td class="py-3 px-3">
                                @php
                                    $clsBadge = match($c->classification) {
                                        'freelancer' => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'icon' => 'laptop'],
                                        'high_school' => ['bg' => 'bg-amber-50 text-amber-700 border-amber-200', 'icon' => 'school'],
                                        'university' => ['bg' => 'bg-blue-50 text-blue-700 border-blue-200', 'icon' => 'academic-cap'],
                                        'lecturer' => ['bg' => 'bg-purple-50 text-purple-700 border-purple-200', 'icon' => 'presentation'],
                                        default => ['bg' => 'bg-stone-100 text-stone-700 border-stone-200', 'icon' => 'user'],
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $clsBadge['bg'] }}">
                                    <span>{{ $c->classification_label }}</span>
                                </span>
                            </td>

                            {{-- Customer Source Badge --}}
                            <td class="py-3 px-3">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $sourceInfo['bg'] }}">
                                    {{ $sourceInfo['label'] }}
                                </span>
                            </td>

                            {{-- Email --}}
                            <td class="py-3 px-3">
                                <span class="text-xs font-mono text-[#73777A]">{{ $c->email ?: '—' }}</span>
                            </td>

                            {{-- Deals / Sessions Count --}}
                            <td class="py-3 px-3 text-center">
                                <span class="font-mono font-black text-xs px-2 py-0.5 rounded-lg bg-[#F5F3EE] text-[#303334]">
                                    {{ $c->deals_count }}
                                </span>
                            </td>

                            {{-- Status Badge --}}
                            <td class="py-3 px-3 text-center">
                                @if($c->status === 'active')
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        نشط
                                    </span>
                                @elseif($c->status === 'blocked')
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        محظور
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-default-100 text-[#73777A]">
                                        معطل
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="py-3 px-3 text-end">
                                <div class="flex items-center justify-end gap-1.5">
                                    {{-- Edit Button --}}
                                    <button type="button"
                                            onclick="openEditCustomerModal({{ json_encode([
                                                'id' => $c->id,
                                                'full_name' => $c->full_name ?: $c->name,
                                                'phone' => $c->phone,
                                                'email' => $c->email,
                                                'customer_type' => $c->customer_type ?: 'registered',
                                                'classification' => $c->classification ?: 'freelancer',
                                                'source' => $c->source ?: 'walk-in',
                                                'status' => $c->status ?: 'active',
                                                'notes' => $c->notes,
                                            ]) }})"
                                            title="تعديل بيانات العميل"
                                            class="p-1.5 rounded-lg bg-[#F5F3EE] hover:bg-[#E5E2DC] text-[#303334] transition">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                    </button>

                                    {{-- Open in Cashier --}}
                                    <a href="{{ url('/cashier?customer_id=' . $c->id) }}" target="_blank"
                                       title="بدء جلسة أو طلب في الكاشير"
                                       class="p-1.5 rounded-lg bg-[#EBF4E8] hover:bg-[#DCE8D4] text-[#4E8F35] transition">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-[#73777A] text-xs">
                                <div class="max-w-xs mx-auto space-y-2">
                                    <div class="size-12 rounded-full bg-[#F5F3EE] text-[#73777A] flex items-center justify-center mx-auto">
                                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/>
                                        </svg>
                                    </div>
                                    <p class="font-bold text-[#303334]">لا يوجد عملاء مطابقين لخيارات البحث</p>
                                    <p class="text-[11px] text-[#73777A]">يمكنك إضافة عميل جديد أو تغيير معايير البحث والتصفية أعلاه.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-5 border-t border-[#F0EDE6] pt-4">
            {{ $customers->links() }}
        </div>
    </div>

    {{-- ───────────────────────────────────────────────────────────── --}}
    {{-- Add Customer Modal --}}
    {{-- ───────────────────────────────────────────────────────────── --}}
    <div id="modal-add-customer" class="{{ (isset($errors) && $errors->any()) ? '' : 'hidden' }} fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-[#E5E2DC] my-8 animate-fade-in">
            <div class="flex items-center justify-between mb-5 pb-3 border-b border-[#F0EDE6]">
                <div class="flex items-center gap-2.5">
                    <div class="size-9 rounded-xl bg-[#EBF4E8] text-[#4E8F35] flex items-center justify-center">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-[#303334]">إضافة عميل جديد</h3>
                        <p class="text-[11px] text-[#73777A]">تسجيل عميل جديد مع التحقق الكامل من البيانات</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modal-add-customer')" class="size-8 rounded-lg bg-[#F5F3EE] hover:bg-[#E5E2DC] text-[#73777A] flex items-center justify-center transition-all cursor-pointer">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>
            </div>

            <form method="POST" action="{{ route('customers.store') }}" novalidate id="form-add-customer">
                @csrf
                <div class="space-y-4">
                    {{-- Full Name with Validation --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-[#303334]">
                                اسم العميل بالكامل <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[10px] text-[#73777A]">حروف فقط (3 أحرف على الأقل)</span>
                        </div>
                        <div class="relative">
                            <input type="text" name="full_name" id="add-customer-name"
                                   required minlength="3" maxlength="100"
                                   value="{{ old('full_name') }}"
                                   placeholder="مثال: أحمد محمد علي"
                                   class="w-full px-3.5 py-2.5 border @error('full_name') border-rose-500 bg-rose-50/30 @else border-[#E5E2DC] @enderror rounded-xl text-xs focus:border-[#4E8F35] focus:ring-1 focus:ring-[#4E8F35] outline-none">
                        </div>
                        @error('full_name')
                            <p class="text-rose-600 text-[11px] font-bold mt-1 flex items-center gap-1">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    {{-- Mobile Phone with Validation --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-[#303334]">
                                رقم الموبايل <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[10px] text-[#73777A]">مثال: 01012345678 أو +2010...</span>
                        </div>
                        <div class="relative">
                            <input type="tel" name="phone" id="add-customer-phone"
                                   required dir="ltr"
                                   value="{{ old('phone') }}"
                                   placeholder="01012345678"
                                   pattern="^((\+?20|0)?1[0125][0-9]{8}|\+?[1-9][0-9]{7,14})$"
                                   class="w-full px-3.5 py-2.5 border text-start @error('phone') border-rose-500 bg-rose-50/30 @else border-[#E5E2DC] @enderror rounded-xl text-xs font-mono font-bold focus:border-[#4E8F35] focus:ring-1 focus:ring-[#4E8F35] outline-none">
                        </div>
                        @error('phone')
                            <p class="text-rose-600 text-[11px] font-bold mt-1 flex items-center gap-1">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    {{-- Customer Type, Classification & Acquisition Source Grid --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                        {{-- Customer Type --}}
                        <div>
                            <label class="block text-xs font-bold text-[#303334] mb-1.5">
                                نوع الحساب <span class="text-rose-500">*</span>
                            </label>
                            <select name="customer_type" id="add-customer-type" required
                                    class="w-full px-3.5 py-2.5 border @error('customer_type') border-rose-500 @else border-[#E5E2DC] @enderror rounded-xl text-xs font-bold text-[#303334] focus:border-[#4E8F35] outline-none bg-white">
                                <option value="registered" {{ old('customer_type', 'registered') === 'registered' ? 'selected' : '' }}>
                                    مسجل (عضوية دائمة)
                                </option>
                                <option value="guest" {{ old('customer_type') === 'guest' ? 'selected' : '' }}>
                                    زائر (زيارة مؤقتة)
                                </option>
                            </select>
                            @error('customer_type')
                                <p class="text-rose-600 text-[11px] font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Classification (المهنة / الفئة) --}}
                        <div>
                            <label class="block text-xs font-bold text-[#303334] mb-1.5">
                                تصنيف العميل <span class="text-rose-500">*</span>
                            </label>
                            <select name="classification" id="add-customer-classification" required
                                    class="w-full px-3.5 py-2.5 border @error('classification') border-rose-500 @else border-[#E5E2DC] @enderror rounded-xl text-xs font-bold text-[#303334] focus:border-[#4E8F35] outline-none bg-white">
                                <option value="freelancer" {{ old('classification', 'freelancer') === 'freelancer' ? 'selected' : '' }}>فريلانسر / عمل حر</option>
                                <option value="high_school" {{ old('classification') === 'high_school' ? 'selected' : '' }}>طالب ثانوي</option>
                                <option value="university" {{ old('classification') === 'university' ? 'selected' : '' }}>طالب جامعي</option>
                                <option value="lecturer" {{ old('classification') === 'lecturer' ? 'selected' : '' }}>محاضر / مدرب</option>
                                <option value="other" {{ old('classification') === 'other' ? 'selected' : '' }}>أخرى / عام</option>
                            </select>
                            @error('classification')
                                <p class="text-rose-600 text-[11px] font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Source --}}
                        <div>
                            <label class="block text-xs font-bold text-[#303334] mb-1.5">
                                مصدر العميل <span class="text-rose-500">*</span>
                            </label>
                            <select name="source" id="add-customer-source" required
                                    class="w-full px-3.5 py-2.5 border border-[#E5E2DC] rounded-xl text-xs font-bold text-[#303334] focus:border-[#4E8F35] outline-none bg-white">
                                <option value="walk-in" {{ old('source', 'walk-in') === 'walk-in' ? 'selected' : '' }}>زيارة مباشرة (استقبال)</option>
                                <option value="mobile" {{ old('source') === 'mobile' ? 'selected' : '' }}>تواصل هاتفي / واتساب</option>
                                <option value="portal" {{ old('source') === 'portal' ? 'selected' : '' }}>بوابة الموبايل الذكية</option>
                                <option value="referral" {{ old('source') === 'referral' ? 'selected' : '' }}>إحالة / ترشيح صديق</option>
                                <option value="social_media" {{ old('source') === 'social_media' ? 'selected' : '' }}>سوشيال ميديا وإعلانات</option>
                                <option value="hubspot" {{ old('source') === 'hubspot' ? 'selected' : '' }}>HubSpot CRM</option>
                                <option value="other" {{ old('source') === 'other' ? 'selected' : '' }}>أخرى</option>
                            </select>
                            @error('source')
                                <p class="text-rose-600 text-[11px] font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-bold text-[#303334] mb-1.5">
                            البريد الإلكتروني <span class="text-[10px] text-[#73777A] font-normal">(اختياري)</span>
                        </label>
                        <input type="email" name="email" id="add-customer-email"
                               dir="ltr"
                               value="{{ old('email') }}"
                               placeholder="customer@domain.com"
                               class="w-full px-3.5 py-2.5 border @error('email') border-rose-500 @else border-[#E5E2DC] @enderror rounded-xl text-xs text-start focus:border-[#4E8F35] outline-none">
                        @error('email')
                            <p class="text-rose-600 text-[11px] font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label class="block text-xs font-bold text-[#303334] mb-1.5">
                            ملاحظات العميل <span class="text-[10px] text-[#73777A] font-normal">(اختياري)</span>
                        </label>
                        <textarea name="notes" id="add-customer-notes" rows="2"
                                  placeholder="أي تفضيلات خاصة، مشروبات مفضلة، أو ملاحظات للمساحة..."
                                  class="w-full px-3.5 py-2.5 border border-[#E5E2DC] rounded-xl text-xs focus:border-[#4E8F35] outline-none">{{ old('notes') }}</textarea>
                    </div>

                    {{-- Submit & Cancel Buttons --}}
                    <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-[#F0EDE6]">
                        <button type="button" onclick="closeModal('modal-add-customer')"
                                class="px-4 py-2.5 bg-[#F5F3EE] hover:bg-[#E5E2DC] text-[#303334] rounded-xl text-xs font-bold transition cursor-pointer">
                            إلغاء
                        </button>
                        <button type="submit"
                                class="px-5 py-2.5 bg-[#4E8F35] hover:bg-[#3F742B] text-white rounded-xl text-xs font-bold transition shadow-xs cursor-pointer flex items-center gap-1.5">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            <span>حفظ بيانات العميل</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ───────────────────────────────────────────────────────────── --}}
    {{-- Edit Customer Modal --}}
    {{-- ───────────────────────────────────────────────────────────── --}}
    <div id="modal-edit-customer" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-[#E5E2DC] my-8 animate-fade-in">
            <div class="flex items-center justify-between mb-5 pb-3 border-b border-[#F0EDE6]">
                <div class="flex items-center gap-2.5">
                    <div class="size-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-[#303334]">تعديل بيانات العميل</h3>
                        <p class="text-[11px] text-[#73777A]">تعديل الاسم ورقم الموبايل، التصنيف، المصدر والحالة</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modal-edit-customer')" class="size-8 rounded-lg bg-[#F5F3EE] hover:bg-[#E5E2DC] text-[#73777A] flex items-center justify-center transition-all cursor-pointer">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>
            </div>

            <form method="POST" action="" id="form-edit-customer" novalidate>
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    {{-- Full Name --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-[#303334]">
                                اسم العميل بالكامل <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[10px] text-[#73777A]">أحرف فقط (3 أحرف على الأقل)</span>
                        </div>
                        <input type="text" name="full_name" id="edit-customer-name"
                               required minlength="3" maxlength="100"
                               placeholder="مثال: أحمد محمد علي"
                               class="w-full px-3.5 py-2.5 border border-[#E5E2DC] rounded-xl text-xs focus:border-[#4E8F35] outline-none">
                    </div>

                    {{-- Mobile Phone --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-[#303334]">
                                رقم الموبايل <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[10px] text-[#73777A]">مثال: 01012345678 أو +2010...</span>
                        </div>
                        <input type="tel" name="phone" id="edit-customer-phone"
                               required dir="ltr"
                               placeholder="01012345678"
                               pattern="^((\+?20|0)?1[0125][0-9]{8}|\+?[1-9][0-9]{7,14})$"
                               class="w-full px-3.5 py-2.5 border border-[#E5E2DC] rounded-xl text-xs font-mono font-bold text-start focus:border-[#4E8F35] outline-none">
                    </div>

                    {{-- Customer Type, Classification, Source & Status Grid --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        {{-- Customer Type --}}
                        <div>
                            <label class="block text-xs font-bold text-[#303334] mb-1.5">
                                نوع الحساب <span class="text-rose-500">*</span>
                            </label>
                            <select name="customer_type" id="edit-customer-type" required
                                    class="w-full px-3 py-2.5 border border-[#E5E2DC] rounded-xl text-xs font-bold text-[#303334] focus:border-[#4E8F35] outline-none bg-white">
                                <option value="registered">مسجل (دائم)</option>
                                <option value="guest">زائر (مؤقت)</option>
                            </select>
                        </div>

                        {{-- Classification --}}
                        <div>
                            <label class="block text-xs font-bold text-[#303334] mb-1.5">
                                التصنيف <span class="text-rose-500">*</span>
                            </label>
                            <select name="classification" id="edit-customer-classification" required
                                    class="w-full px-3 py-2.5 border border-[#E5E2DC] rounded-xl text-xs font-bold text-[#303334] focus:border-[#4E8F35] outline-none bg-white">
                                <option value="freelancer">فريلانسر / عمل حر</option>
                                <option value="high_school">طالب ثانوي</option>
                                <option value="university">طالب جامعي</option>
                                <option value="lecturer">محاضر / مدرب</option>
                                <option value="other">أخرى / عام</option>
                            </select>
                        </div>

                        {{-- Source --}}
                        <div>
                            <label class="block text-xs font-bold text-[#303334] mb-1.5">
                                مصدر العميل <span class="text-rose-500">*</span>
                            </label>
                            <select name="source" id="edit-customer-source" required
                                    class="w-full px-3 py-2.5 border border-[#E5E2DC] rounded-xl text-xs font-bold text-[#303334] focus:border-[#4E8F35] outline-none bg-white">
                                <option value="walk-in">زيارة مباشرة</option>
                                <option value="mobile">هاتف / واتساب</option>
                                <option value="portal">بوابة الموبايل</option>
                                <option value="referral">إحالة صديق</option>
                                <option value="social_media">سوشيال ميديا</option>
                                <option value="hubspot">HubSpot CRM</option>
                                <option value="other">أخرى</option>
                            </select>
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="block text-xs font-bold text-[#303334] mb-1.5">
                                الحالة <span class="text-rose-500">*</span>
                            </label>
                            <select name="status" id="edit-customer-status" required
                                    class="w-full px-3 py-2.5 border border-[#E5E2DC] rounded-xl text-xs font-bold text-[#303334] focus:border-[#4E8F35] outline-none bg-white">
                                <option value="active">نشط</option>
                                <option value="inactive">معطل</option>
                                <option value="blocked">محظور</option>
                            </select>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-bold text-[#303334] mb-1.5">
                            البريد الإلكتروني <span class="text-[10px] text-[#73777A] font-normal">(اختياري)</span>
                        </label>
                        <input type="email" name="email" id="edit-customer-email"
                               dir="ltr"
                               placeholder="customer@domain.com"
                               class="w-full px-3.5 py-2.5 border border-[#E5E2DC] rounded-xl text-xs text-start focus:border-[#4E8F35] outline-none">
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label class="block text-xs font-bold text-[#303334] mb-1.5">
                            الملاحظات <span class="text-[10px] text-[#73777A] font-normal">(اختياري)</span>
                        </label>
                        <textarea name="notes" id="edit-customer-notes" rows="2"
                                  placeholder="ملاحظات العميل..."
                                  class="w-full px-3.5 py-2.5 border border-[#E5E2DC] rounded-xl text-xs focus:border-[#4E8F35] outline-none"></textarea>
                    </div>

                    {{-- Action buttons --}}
                    <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-[#F0EDE6]">
                        <button type="button" onclick="closeModal('modal-edit-customer')"
                                class="px-4 py-2.5 bg-[#F5F3EE] hover:bg-[#E5E2DC] text-[#303334] rounded-xl text-xs font-bold transition cursor-pointer">
                            إلغاء
                        </button>
                        <button type="submit"
                                class="px-5 py-2.5 bg-[#4E8F35] hover:bg-[#3F742B] text-white rounded-xl text-xs font-bold transition shadow-xs cursor-pointer flex items-center gap-1.5">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            <span>تحديث بيانات العميل</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal: Import Customers CSV --}}
    <div id="modal-import-customers" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs hidden p-4 animate-fade-in">
        <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl border border-[#E5E2DC] relative max-h-[92vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 border-b border-[#F0EDE6] mb-5">
                <div class="flex items-center gap-3">
                    <div class="size-10 rounded-xl bg-[#111827] text-white flex items-center justify-center">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-[#303334]">استيراد العملاء من ملف (CSV / Excel)</h3>
                        <p class="text-[11px] text-[#73777A]">رفع ملف صادرات HubSpot أو Excel واستيراد البيانات تلقائياً</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modal-import-customers')" class="size-8 rounded-lg bg-[#F5F3EE] hover:bg-[#E5E2DC] text-[#73777A] flex items-center justify-center text-sm font-bold transition">✕</button>
            </div>

            <form action="{{ route('customers.import') }}" method="POST" enctype="multipart/form-data" id="form-import-customers">
                @csrf

                <div class="space-y-4">
                    {{-- File Dropzone --}}
                    <div>
                        <label class="text-xs font-bold text-[#303334] block mb-2">اختر ملف الـ CSV / Excel المصدّر:</label>
                        <div id="csv-dropzone"
                             onclick="document.getElementById('csv-file-input').click()"
                             class="border-2 border-dashed border-[#D1D5DB] hover:border-[#4E8F35] bg-[#F8F7F4] hover:bg-[#EBF4E8]/40 rounded-2xl p-6 text-center cursor-pointer transition-all">
                            <input type="file" id="csv-file-input" name="csv_file" accept=".csv,.txt,.tsv" class="hidden" onchange="handleFileSelected(this)">
                            
                            <div id="dropzone-idle" class="space-y-2">
                                <div class="size-12 mx-auto rounded-full bg-white shadow-xs border border-[#E5E2DC] flex items-center justify-center text-[#4E8F35]">
                                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                </div>
                                <p class="text-xs font-bold text-[#303334]">اضغط هنا لاختيار الملف أو اسحبه وأفلته هنا</p>
                                <p class="text-[10px] text-[#73777A]">يدعم ملفات CSV UTF-8 المفصولة بفواصل (أو صادرات HubSpot)</p>
                            </div>

                            <div id="dropzone-selected" class="hidden space-y-2">
                                <div class="size-12 mx-auto rounded-full bg-[#EBF4E8] text-[#4E8F35] flex items-center justify-center font-bold">
                                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <polyline points="20 6 9 17 4 12"/>
                                    </svg>
                                </div>
                                <p id="selected-file-name" class="text-xs font-black text-[#303334] dir-ltr text-center"></p>
                                <p id="selected-file-size" class="text-[10px] text-[#73777A] font-semibold"></p>
                                <span class="inline-block text-[10px] font-bold text-[#4E8F35] bg-[#EBF4E8] px-2.5 py-0.5 rounded-full">جاهز للاستيراد (اضغط لتغيير الملف)</span>
                            </div>
                        </div>
                    </div>

                    {{-- Update Option --}}
                    <div class="p-3 bg-[#F8F7F4] border border-[#E5E2DC] rounded-xl flex items-start gap-3">
                        <input type="checkbox" id="update_existing" name="update_existing" value="1" checked
                               class="mt-1 size-4 text-[#4E8F35] rounded border-[#D1D5DB] focus:ring-[#4E8F35]">
                        <label for="update_existing" class="text-xs cursor-pointer">
                            <span class="font-bold text-[#303334] block">تحديث بيانات العملاء المسجلين مسبقاً</span>
                            <span class="text-[11px] text-[#73777A] block mt-0.5">إذا كان رقم الهاتف أو معرّف HubSpot موجوداً مسبقاً، سيتم دمج البيانات وإضافة الملاحظات وتحديث تاريخ النشاط بدلاً من تكراره.</span>
                        </label>
                    </div>

                    {{-- Supported Columns Info --}}
                    <div class="p-3.5 bg-blue-50/60 border border-blue-100 rounded-xl space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold text-blue-900 flex items-center gap-1.5">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                الأعمدة المدعومة وتنسيق الملف:
                            </span>
                            <a href="{{ route('customers.sample') }}" class="text-[11px] font-bold text-blue-700 hover:text-blue-900 underline flex items-center gap-1">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                <span>تحميل نموذج تجريبي (CSV)</span>
                            </a>
                        </div>
                        <div class="flex flex-wrap gap-1 text-[10px]">
                            <span class="px-1.5 py-0.5 rounded bg-white text-gray-800 border border-gray-200 font-mono">Record ID</span>
                            <span class="px-1.5 py-0.5 rounded bg-white text-gray-800 border border-gray-200 font-mono">First Name</span>
                            <span class="px-1.5 py-0.5 rounded bg-white text-gray-800 border border-gray-200 font-mono">Last Name</span>
                            <span class="px-1.5 py-0.5 rounded bg-white text-gray-800 border border-gray-200 font-mono">Phone Number</span>
                            <span class="px-1.5 py-0.5 rounded bg-white text-gray-800 border border-gray-200 font-mono">Contact owner</span>
                            <span class="px-1.5 py-0.5 rounded bg-white text-gray-800 border border-gray-200 font-mono">Last Activity Date</span>
                            <span class="px-1.5 py-0.5 rounded bg-white text-gray-800 border border-gray-200 font-mono">Lead Status</span>
                            <span class="px-1.5 py-0.5 rounded bg-white text-gray-800 border border-gray-200 font-mono">Source</span>
                            <span class="px-1.5 py-0.5 rounded bg-white text-gray-800 border border-gray-200 font-mono">Associated Deal IDs</span>
                        </div>
                        <p class="text-[10px] text-gray-500">ملاحظة: يتعرف النظام تلقائياً على الأرقام المصرية (010, 011, 012, 015) ويصحح الأرقام المكتوبة بصيغة علمية (Exponential) من Excel.</p>
                    </div>

                    {{-- Action buttons --}}
                    <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-[#F0EDE6]">
                        <button type="button" onclick="closeModal('modal-import-customers')"
                                class="px-4 py-2.5 bg-[#F5F3EE] hover:bg-[#E5E2DC] text-[#303334] rounded-xl text-xs font-bold transition cursor-pointer">
                            إلغاء
                        </button>
                        <button type="submit" id="btn-submit-import"
                                class="px-5 py-2.5 bg-[#4E8F35] hover:bg-[#3F742B] text-white rounded-xl text-xs font-bold transition shadow-xs cursor-pointer flex items-center gap-1.5">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            <span>بدء استيراد البيانات</span>
                        </button>
                    </div>
                </div>
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

    function openAddCustomerModal() {
        openModal('modal-add-customer');
    }

    function openImportModal() {
        openModal('modal-import-customers');
    }

    function handleFileSelected(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            document.getElementById('dropzone-idle').classList.add('hidden');
            document.getElementById('dropzone-selected').classList.remove('hidden');
            document.getElementById('selected-file-name').textContent = file.name;
            document.getElementById('selected-file-size').textContent = (file.size / 1024).toFixed(1) + ' KB';
        }
    }

    function openEditCustomerModal(customer) {
        const form = document.getElementById('form-edit-customer');
        form.action = `/customers/${customer.id}`;

        document.getElementById('edit-customer-name').value = customer.full_name || '';
        document.getElementById('edit-customer-phone').value = customer.phone || '';
        document.getElementById('edit-customer-email').value = customer.email || '';
        document.getElementById('edit-customer-type').value = customer.customer_type || 'registered';
        document.getElementById('edit-customer-classification').value = customer.classification || 'freelancer';
        document.getElementById('edit-customer-source').value = customer.source || 'walk-in';
        document.getElementById('edit-customer-status').value = customer.status || 'active';
        document.getElementById('edit-customer-notes').value = customer.notes || '';

        openModal('modal-edit-customer');
    }

    // Client-side instant validation helpers
    document.addEventListener('DOMContentLoaded', function() {
        const addForm = document.getElementById('form-add-customer');
        const phoneInput = document.getElementById('add-customer-phone');
        const nameInput = document.getElementById('add-customer-name');

        if (addForm) {
            addForm.addEventListener('submit', function(e) {
                let valid = true;

                // Validate Name
                if (nameInput) {
                    const nameVal = nameInput.value.trim();
                    if (nameVal.length < 3) {
                        alert('يرجى إدخال اسم العميل بشكل صحيح (3 أحرف على الأقل).');
                        nameInput.focus();
                        valid = false;
                        e.preventDefault();
                        return;
                    }
                }

                // Validate Phone
                if (phoneInput && valid) {
                    const phoneVal = phoneInput.value.trim();
                    const phoneRegex = /^((\+?20|0)?1[0125][0-9]{8}|\+?[1-9][0-9]{7,14})$/;
                    if (!phoneRegex.test(phoneVal)) {
                        alert('يرجى إدخال رقم موبايل صحيح (مثال: 01012345678 أو +201012345678).');
                        phoneInput.focus();
                        valid = false;
                        e.preventDefault();
                        return;
                    }
                }
            });
        }

        // Import form validation
        const importForm = document.getElementById('form-import-customers');
        if (importForm) {
            importForm.addEventListener('submit', function(e) {
                const fileInput = document.getElementById('csv-file-input');
                if (!fileInput.files || fileInput.files.length === 0) {
                    alert('يرجى اختيار ملف CSV أولاً.');
                    e.preventDefault();
                    return;
                }
                const btn = document.getElementById('btn-submit-import');
                btn.disabled = true;
                btn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span>جاري معالجة واستيراد البيانات...</span>
                `;
            });
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal('modal-add-customer');
            closeModal('modal-edit-customer');
            closeModal('modal-import-customers');
        }
    });
</script>
@endsection
