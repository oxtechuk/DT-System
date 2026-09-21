@extends('shared.vertical', ['title' => 'إدارة الفريق والصلاحيات'])

@section('styles')
<style>
/* ── Avatar ─────────────────────────────────────────────────────────── */
.av-ring { box-shadow: 0 0 0 3px #fff, 0 0 0 4px #4E8F35; }

/* ── Toggle Switch ───────────────────────────────────────────────────── */
.perm-toggle { display: none; }
.perm-toggle + label {
    display: inline-flex; align-items: center; gap: 8px;
    cursor: pointer; user-select: none; font-size: 13px;
    color: #57606a;
}
.perm-toggle + label .track {
    width: 36px; height: 20px; border-radius: 20px;
    background: #e5e7eb; position: relative;
    transition: background 0.2s;
}
.perm-toggle + label .track::after {
    content: ''; position: absolute; top: 2px; left: 2px;
    width: 16px; height: 16px; border-radius: 50%;
    background: #fff; transition: transform 0.2s;
    box-shadow: 0 1px 3px rgba(0,0,0,.2);
}
.perm-toggle:checked + label .track { background: #4E8F35; }
.perm-toggle:checked + label .track::after { transform: translateX(16px); }

/* ── Cards Hover ─────────────────────────────────────────────────────── */
.member-card {
    transition: box-shadow .2s, transform .2s;
    border: 1.5px solid #E5E2DC;
}
.member-card:hover {
    box-shadow: 0 6px 24px rgba(0,0,0,.09);
    transform: translateY(-1px);
}

/* ── Tabs ────────────────────────────────────────────────────────────── */
.tab-btn { transition: all .15s; }
.tab-btn.active {
    background: #4E8F35; color: #fff; box-shadow: 0 2px 8px rgba(78,143,53,.35);
}

/* ── Skeleton Pulse ──────────────────────────────────────────────────── */
@keyframes pulse { 0%,100% { opacity:.4 } 50% { opacity:1 } }
.skeleton { animation: pulse 1.5s ease infinite; background: #e5e7eb; border-radius: 6px; }

/* ── Permission group header ─────────────────────────────────────────── */
.perm-group-hdr {
    font-size: 11px; font-weight: 700; letter-spacing: .06em;
    color: #73777A; text-transform: uppercase; padding: 10px 0 6px;
    border-bottom: 1px solid #E5E2DC; margin-bottom: 8px;
}
</style>
@endsection

@section('content')
<div dir="rtl">

    {{-- ══ Page Header ══════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-[#303334]">الفريق والصلاحيات</h1>
            <p class="text-sm text-[#73777A] mt-0.5">إدارة أعضاء الفريق وتحديد ما يشوفوه</p>
        </div>
        <div class="flex items-center gap-2.5">
            {{-- Tabs --}}
            <div class="flex bg-[#F5F3EE] rounded-xl p-1 border border-[#E5E2DC]">
                <button id="tab-members" class="tab-btn active px-4 py-1.5 rounded-lg text-sm font-bold" onclick="switchTab('members')">
                    الأعضاء
                </button>
                <button id="tab-roles" class="tab-btn px-4 py-1.5 rounded-lg text-sm font-bold text-[#73777A]" onclick="switchTab('roles')">
                    الأدوار والصلاحيات
                </button>
                <button id="tab-notifs" class="tab-btn px-4 py-1.5 rounded-lg text-sm font-bold text-[#73777A]" onclick="switchTab('notifs')">
                    الإشعارات
                </button>
            </div>
            <button onclick="openAddMember()" id="btn-add-member"
                    class="flex items-center gap-2 px-4 py-2 rounded-xl bg-[#4E8F35] hover:bg-[#3F742B] text-white text-sm font-bold transition-all shadow-sm">
                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                إضافة عضو
            </button>
            <button onclick="openAddRole()" id="btn-add-role"
                    class="hidden flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold transition-all shadow-sm">
                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                دور جديد
            </button>
        </div>
    </div>

    {{-- ══ Stats Bar ══════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
        @php
            $totalUsers  = $users->count();
            $activeUsers = $users->where('status','active')->count();
            $totalRoles  = $roles->count();
        @endphp
        <div class="bg-white rounded-2xl border border-[#E5E2DC] p-4 flex items-center gap-3">
            <span class="size-10 rounded-xl bg-[#EBF4E8] text-[#4E8F35] flex items-center justify-center">
                <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </span>
            <div>
                <p class="text-2xl font-black text-[#303334]">{{ $totalUsers }}</p>
                <p class="text-xs text-[#73777A]">إجمالي الأعضاء</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-[#E5E2DC] p-4 flex items-center gap-3">
            <span class="size-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center">
                <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
            </span>
            <div>
                <p class="text-2xl font-black text-[#303334]">{{ $activeUsers }}</p>
                <p class="text-xs text-[#73777A]">عضو نشط</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-[#E5E2DC] p-4 flex items-center gap-3">
            <span class="size-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
            </span>
            <div>
                <p class="text-2xl font-black text-[#303334]">{{ $totalRoles }}</p>
                <p class="text-xs text-[#73777A]">أدوار معرّفة</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-[#E5E2DC] p-4 flex items-center gap-3">
            <span class="size-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
            </span>
            <div>
                <p class="text-2xl font-black text-[#303334]">{{ $users->where('status','inactive')->count() }}</p>
                <p class="text-xs text-[#73777A]">عضو غير نشط</p>
            </div>
        </div>
    </div>

    {{-- ══ Members Tab ════════════════════════════════════════════════ --}}
    <div id="panel-members">
        @if($users->isEmpty())
            <div class="bg-white rounded-2xl border border-[#E5E2DC] p-12 text-center">
                <div class="size-16 rounded-2xl bg-[#EBF4E8] mx-auto flex items-center justify-center mb-4">
                    <svg class="size-8 text-[#4E8F35]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                </div>
                <p class="text-[#303334] font-bold text-base mb-1">لا يوجد أعضاء حتى الآن</p>
                <p class="text-[#73777A] text-sm">ابدأ بإضافة أول عضو في الفريق</p>
            </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4" id="members-grid">
            @foreach($users as $u)
            @php
                $statusColor = $u->status === 'active'
                    ? 'bg-green-100 text-green-700 border-green-200'
                    : 'bg-gray-100 text-gray-500 border-gray-200';
                $uData = htmlspecialchars(json_encode([
                    'id'      => $u->id,
                    'name'    => $u->name,
                    'email'   => $u->email,
                    'phone'   => $u->phone,
                    'role_id' => $u->role_id,
                    'status'  => $u->status,
                    'notes'   => $u->notes,
                ]), ENT_QUOTES, 'UTF-8');
            @endphp
            <div class="member-card bg-white rounded-2xl p-5 relative" id="member-card-{{ $u->id }}"
                 data-user="{!! $uData !!}">

                {{-- Status badge top-left --}}
                <div class="absolute top-4 start-4">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full border text-[10px] font-bold {{ $statusColor }}">
                        <span class="size-1.5 rounded-full {{ $u->status === 'active' ? 'bg-green-500' : 'bg-gray-400' }} me-1"></span>
                        {{ $u->status === 'active' ? 'نشط' : 'غير نشط' }}
                    </span>
                </div>

                {{-- Action menu top-right --}}
                <div class="absolute top-4 end-4 flex gap-1.5">
                    <button onclick="editMember({{ $u->id }})"
                            class="size-7 flex items-center justify-center rounded-lg bg-[#F5F3EE] hover:bg-[#EBF4E8] hover:text-[#4E8F35] text-[#73777A] transition-all"
                            title="تعديل">
                        <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </button>
                    <button onclick="deleteMember({{ $u->id }}, '{{ addslashes($u->name) }}')"
                            class="size-7 flex items-center justify-center rounded-lg bg-[#F5F3EE] hover:bg-red-50 hover:text-red-500 text-[#73777A] transition-all"
                            title="حذف">
                        <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                    </button>
                </div>

                {{-- Avatar + Name --}}
                <div class="flex flex-col items-center text-center pt-4 pb-3">
                    <div class="size-16 rounded-2xl bg-gradient-to-br from-[#4E8F35] to-[#79B84A] flex items-center justify-center text-white font-black text-xl mb-3 shadow-md shadow-[#4E8F35]/20">
                        {{ $u->initials() }}
                    </div>
                    <h3 class="text-[#303334] font-black text-base leading-tight">{{ $u->name }}</h3>
                    <p class="text-[#73777A] text-xs mt-0.5">{{ $u->email }}</p>
                    @if($u->phone)
                    <p class="text-[#B0ADA8] text-xs mt-0.5">{{ $u->phone }}</p>
                    @endif
                </div>

                {{-- Role badge --}}
                <div class="flex justify-center mb-3">
                    @if($u->role)
                        <span class="inline-flex items-center px-3 py-1 rounded-full border text-xs font-bold {{ $u->role->badgeColor() }}">
                            {{ $u->role->name }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full border text-xs font-bold bg-gray-50 text-gray-400 border-gray-200">
                            بدون دور
                        </span>
                    @endif
                </div>

                @if($u->notes)
                <p class="text-[#73777A] text-xs text-center bg-[#FAF9F5] rounded-xl px-3 py-2 border border-[#E5E2DC] line-clamp-2">
                    {{ $u->notes }}
                </p>
                @endif
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- ══ Roles Tab ══════════════════════════════════════════════════ --}}
    <div id="panel-roles" class="hidden">
        @if($roles->isEmpty())
            <div class="bg-white rounded-2xl border border-[#E5E2DC] p-12 text-center">
                <p class="text-[#303334] font-bold">لا يوجد أدوار معرّفة بعد</p>
                <p class="text-[#73777A] text-sm mt-1">أضف أول دور وحدد صلاحياته</p>
            </div>
        @else
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4" id="roles-grid">
            @foreach($roles as $role)
            @php
                $rolePerms = $role->permissions->pluck('name')->toArray();
            @endphp
            <div class="bg-white rounded-2xl border border-[#E5E2DC] p-5" id="role-card-{{ $role->id }}"
                 data-role="{{ json_encode(['id'=>$role->id,'name'=>$role->name,'slug'=>$role->slug,'description'=>$role->description,'permissions'=>$rolePerms]) }}">

                {{-- Role header --}}
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="size-10 rounded-xl bg-[#EBF4E8] flex items-center justify-center text-[#4E8F35]">
                            <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-black text-[#303334] text-sm">{{ $role->name }}</h3>
                            <p class="text-[#B0ADA8] text-xs font-mono">{{ $role->slug }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="text-xs text-[#73777A] font-semibold me-1">{{ $role->users_count }} عضو</span>
                        <button onclick="editRole({{ $role->id }})"
                                class="size-7 flex items-center justify-center rounded-lg bg-[#F5F3EE] hover:bg-blue-50 hover:text-blue-600 text-[#73777A] transition-all" title="تعديل">
                            <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </button>
                        <button onclick="deleteRole({{ $role->id }}, '{{ addslashes($role->name) }}')"
                                class="size-7 flex items-center justify-center rounded-lg bg-[#F5F3EE] hover:bg-red-50 hover:text-red-500 text-[#73777A] transition-all" title="حذف">
                            <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                        </button>
                    </div>
                </div>

                @if($role->description)
                <p class="text-[#73777A] text-xs mb-3 bg-[#FAF9F5] px-3 py-2 rounded-xl border border-[#E5E2DC]">{{ $role->description }}</p>
                @endif

                {{-- Permissions summary --}}
                <div class="flex flex-wrap gap-1.5">
                    @if(empty($rolePerms))
                        <span class="text-xs text-[#B0ADA8] italic">لا توجد صلاحيات</span>
                    @else
                        @foreach(array_slice($rolePerms, 0, 6) as $p)
                        <span class="px-2 py-0.5 rounded-md bg-[#EBF4E8] text-[#4E8F35] text-[10px] font-mono font-bold">{{ $p }}</span>
                        @endforeach
                        @if(count($rolePerms) > 6)
                        <span class="px-2 py-0.5 rounded-md bg-[#F5F3EE] text-[#73777A] text-[10px] font-bold">+{{ count($rolePerms)-6 }} أخرى</span>
                        @endif
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- ══ Notifications Tab ══════════════════════════════════════════ --}}
    <div id="panel-notifs" class="hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            {{-- Send Notification Form --}}
            <div class="bg-white rounded-2xl border border-[#E5E2DC] overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-500 p-4 flex items-center gap-3">
                    <span class="size-8 rounded-xl bg-white/20 flex items-center justify-center">
                        <svg class="size-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    </span>
                    <div>
                        <h3 class="text-white font-black text-sm">إرسال إشعار مجدول</h3>
                        <p class="text-blue-100 text-xs">سيظهر للموظف في الوقت المحدد</p>
                    </div>
                </div>
                <form id="form-notif" class="p-5 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-[#303334] mb-1.5">اختر الموظف(ين) <span class="text-red-500">*</span></label>
                        <div class="border border-[#E5E2DC] rounded-xl p-3 max-h-36 overflow-y-auto space-y-1.5">
                            @foreach($users as $u)
                            <label class="flex items-center gap-2.5 px-2 py-1.5 rounded-lg hover:bg-[#F5F3EE] cursor-pointer transition-all">
                                <input type="checkbox" name="notif_users" value="{{ $u->id }}"
                                       class="size-4 rounded accent-blue-600">
                                <span class="size-7 rounded-lg bg-gradient-to-br from-[#4E8F35] to-[#79B84A] text-white text-[10px] font-black flex items-center justify-center flex-shrink-0">
                                    {{ $u->initials() }}
                                </span>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-[#303334] truncate">{{ $u->name }}</p>
                                    <p class="text-[10px] text-[#B0ADA8]">{{ $u->role?->name ?? 'بدون دور' }}</p>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        <button type="button" onclick="selectAllUsers()" class="text-[10px] text-blue-500 mt-1 hover:underline">تحديد الكل</button>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#303334] mb-1.5">عنوان الإشعار <span class="text-red-500">*</span></label>
                        <input id="notif-title" type="text" placeholder="مثال: تذكير باجتماع الفريق"
                               class="w-full px-3 py-2.5 rounded-xl border border-[#E5E2DC] text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#303334] mb-1.5">نص الإشعار</label>
                        <textarea id="notif-body" rows="2" placeholder="تفاصيل الإشعار (اختياري)..."
                                  class="w-full px-3 py-2.5 rounded-xl border border-[#E5E2DC] text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15 transition-all resize-none"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-[#303334] mb-1.5">الأيقونة</label>
                            <select id="notif-icon" class="w-full px-3 py-2.5 rounded-xl border border-[#E5E2DC] text-sm bg-white focus:outline-none focus:border-blue-500 transition-all">
                                <option value="bell">🔔 تنبيه</option>
                                <option value="task">✅ مهمة</option>
                                <option value="warning">⚠️ تحذير</option>
                                <option value="info">ℹ️ معلومة</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#303334] mb-1.5">اللون</label>
                            <select id="notif-color" class="w-full px-3 py-2.5 rounded-xl border border-[#E5E2DC] text-sm bg-white focus:outline-none focus:border-blue-500 transition-all">
                                <option value="green">🟢 أخضر</option>
                                <option value="blue">🔵 أزرق</option>
                                <option value="amber">🟡 أصفر</option>
                                <option value="red">🔴 أحمر</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#303334] mb-1.5">وقت الإشعار <span class="text-red-500">*</span></label>
                        <input id="notif-at" type="datetime-local"
                               class="w-full px-3 py-2.5 rounded-xl border border-[#E5E2DC] text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15 transition-all">
                    </div>

                    <div id="notif-error" class="hidden px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm"></div>

                    <button type="submit" id="btn-send-notif"
                            class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm transition-all shadow-sm">
                        إرسال الإشعار 🔔
                    </button>
                </form>
            </div>

            {{-- Sent Notifications History --}}
            <div class="bg-white rounded-2xl border border-[#E5E2DC] overflow-hidden">
                <div class="p-4 border-b border-[#E5E2DC] flex items-center justify-between">
                    <h3 class="font-black text-[#303334] text-sm">الإشعارات المُرسلة</h3>
                    <button onclick="loadSentNotifications()"
                            class="text-xs px-3 py-1.5 rounded-lg bg-[#F5F3EE] hover:bg-[#EBF4E8] text-[#73777A] hover:text-[#4E8F35] font-bold transition-all">
                        تحديث ↻
                    </button>
                </div>
                <div id="sent-notifs-list" class="divide-y divide-[#F0EDE8] max-h-[480px] overflow-y-auto">
                    <div class="p-8 text-center text-[#B0ADA8] text-sm">
                        <p>اضغط "تحديث" لعرض الإشعارات</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div><!-- /container -->

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- MODAL: Add / Edit Member                                              --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<div id="modal-member" class="fixed inset-0 z-[9998] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeMemberModal()"></div>
    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg mx-auto overflow-hidden">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-[#4E8F35] to-[#79B84A] p-5 flex items-center justify-between">
            <div>
                <p class="text-white/70 text-xs uppercase tracking-widest mb-0.5">إدارة الفريق</p>
                <h2 id="modal-member-title" class="text-white font-black text-lg">إضافة عضو جديد</h2>
            </div>
            <button onclick="closeMemberModal()" class="size-8 rounded-xl bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-all">
                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="form-member" class="p-5 space-y-4">
            <input type="hidden" id="member-id">

            <div class="grid grid-cols-2 gap-3">
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-[#303334] mb-1.5">الاسم الكامل <span class="text-red-500">*</span></label>
                    <input id="m-name" type="text" placeholder="مثال: أحمد محمد"
                           class="w-full px-3 py-2.5 rounded-xl border border-[#E5E2DC] text-sm text-[#303334] focus:outline-none focus:border-[#4E8F35] focus:ring-2 focus:ring-[#4E8F35]/15 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#303334] mb-1.5">البريد الإلكتروني <span class="text-red-500">*</span></label>
                    <input id="m-email" type="email" placeholder="example@ddt.com"
                           class="w-full px-3 py-2.5 rounded-xl border border-[#E5E2DC] text-sm text-[#303334] focus:outline-none focus:border-[#4E8F35] focus:ring-2 focus:ring-[#4E8F35]/15 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#303334] mb-1.5">رقم الهاتف</label>
                    <input id="m-phone" type="tel" placeholder="05XXXXXXXX"
                           class="w-full px-3 py-2.5 rounded-xl border border-[#E5E2DC] text-sm text-[#303334] focus:outline-none focus:border-[#4E8F35] focus:ring-2 focus:ring-[#4E8F35]/15 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#303334] mb-1.5">الدور الوظيفي</label>
                    <select id="m-role" class="w-full px-3 py-2.5 rounded-xl border border-[#E5E2DC] text-sm text-[#303334] focus:outline-none focus:border-[#4E8F35] focus:ring-2 focus:ring-[#4E8F35]/15 transition-all bg-white">
                        <option value="">— بدون دور —</option>
                        @foreach($roles as $r)
                        <option value="{{ $r->id }}">{{ $r->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#303334] mb-1.5">الحالة</label>
                    <select id="m-status" class="w-full px-3 py-2.5 rounded-xl border border-[#E5E2DC] text-sm text-[#303334] focus:outline-none focus:border-[#4E8F35] focus:ring-2 focus:ring-[#4E8F35]/15 transition-all bg-white">
                        <option value="active">نشط ✓</option>
                        <option value="inactive">غير نشط ✗</option>
                    </select>
                </div>
                <div id="password-field">
                    <label class="block text-xs font-bold text-[#303334] mb-1.5">كلمة المرور <span class="text-red-500" id="pwd-required">*</span></label>
                    <input id="m-password" type="password" placeholder="8 أحرف على الأقل"
                           class="w-full px-3 py-2.5 rounded-xl border border-[#E5E2DC] text-sm text-[#303334] focus:outline-none focus:border-[#4E8F35] focus:ring-2 focus:ring-[#4E8F35]/15 transition-all">
                    <p id="pwd-hint" class="hidden text-[10px] text-[#B0ADA8] mt-1">اتركها فارغة إذا لا تريد تغيير كلمة المرور</p>
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-[#303334] mb-1.5">ملاحظات (اختياري)</label>
                    <textarea id="m-notes" rows="2" placeholder="ملاحظات خاصة بالعضو..."
                              class="w-full px-3 py-2.5 rounded-xl border border-[#E5E2DC] text-sm text-[#303334] focus:outline-none focus:border-[#4E8F35] focus:ring-2 focus:ring-[#4E8F35]/15 transition-all resize-none"></textarea>
                </div>
            </div>

            {{-- Error --}}
            <div id="member-error" class="hidden px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm"></div>

            {{-- Buttons --}}
            <div class="flex gap-2.5 pt-2">
                <button type="submit" id="btn-save-member"
                        class="flex-1 py-3 rounded-xl bg-[#4E8F35] hover:bg-[#3F742B] text-white font-bold text-sm transition-all shadow-sm">
                    حفظ العضو
                </button>
                <button type="button" onclick="closeMemberModal()"
                        class="px-5 py-3 rounded-xl bg-[#F5F3EE] hover:bg-[#EAE7E0] text-[#73777A] font-bold text-sm transition-all">
                    إلغاء
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- MODAL: Add / Edit Role & Permissions                                  --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<div id="modal-role" class="fixed inset-0 z-[9998] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeRoleModal()"></div>
    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl mx-auto overflow-hidden flex flex-col max-h-[90vh]">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-blue-600 to-blue-500 p-5 flex items-center justify-between shrink-0">
            <div>
                <p class="text-white/70 text-xs uppercase tracking-widest mb-0.5">الأدوار والصلاحيات</p>
                <h2 id="modal-role-title" class="text-white font-black text-lg">إنشاء دور جديد</h2>
            </div>
            <button onclick="closeRoleModal()" class="size-8 rounded-xl bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-all">
                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="overflow-y-auto flex-1">
        <form id="form-role" class="p-5 space-y-4">
            <input type="hidden" id="role-id">

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-[#303334] mb-1.5">اسم الدور <span class="text-red-500">*</span></label>
                    <input id="r-name" type="text" placeholder="مثال: مدير الكاشير"
                           class="w-full px-3 py-2.5 rounded-xl border border-[#E5E2DC] text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#303334] mb-1.5">المعرّف (Slug) <span class="text-red-500">*</span></label>
                    <input id="r-slug" type="text" placeholder="cashier-manager"
                           class="w-full px-3 py-2.5 rounded-xl border border-[#E5E2DC] text-sm font-mono focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15 transition-all">
                    <p class="text-[10px] text-[#B0ADA8] mt-1">حروف وأرقام وشرطة فقط، لا يمكن تغييره لاحقاً</p>
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-[#303334] mb-1.5">وصف الدور</label>
                    <input id="r-desc" type="text" placeholder="مثال: يدير الكاشير ويفتح الجلسات"
                           class="w-full px-3 py-2.5 rounded-xl border border-[#E5E2DC] text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15 transition-all">
                </div>
            </div>

            {{-- Permissions Matrix --}}
            <div>
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-black text-[#303334]">الصلاحيات</h3>
                    <div class="flex gap-2">
                        <button type="button" onclick="selectAllPerms(true)"
                                class="text-xs px-3 py-1 rounded-lg bg-[#EBF4E8] text-[#4E8F35] font-bold hover:bg-[#D5EBcC] transition-all">
                            تحديد الكل
                        </button>
                        <button type="button" onclick="selectAllPerms(false)"
                                class="text-xs px-3 py-1 rounded-lg bg-[#F5F3EE] text-[#73777A] font-bold hover:bg-[#EAE7E0] transition-all">
                            إلغاء الكل
                        </button>
                    </div>
                </div>

                <div class="space-y-4" id="perms-matrix">
                    @foreach($allPermissions as $groupName => $perms)
                    <div>
                        <p class="perm-group-hdr">{{ $groupName }}</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach($perms as $pKey => $pLabel)
                            <div class="flex items-center gap-2 px-3 py-2 rounded-xl bg-[#FAF9F5] border border-transparent hover:border-[#E5E2DC] transition-all">
                                <input type="checkbox" class="perm-toggle perm-chk"
                                       id="perm-{{ str_replace('.', '-', $pKey) }}"
                                       name="permissions[]" value="{{ $pKey }}">
                                <label for="perm-{{ str_replace('.', '-', $pKey) }}" class="cursor-pointer flex items-center gap-2 w-full">
                                    <span class="track"></span>
                                    <span class="text-xs font-semibold text-[#303334]">{{ $pLabel }}</span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Error --}}
            <div id="role-error" class="hidden px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm"></div>

            {{-- Buttons --}}
            <div class="flex gap-2.5 pt-2 pb-1">
                <button type="submit" id="btn-save-role"
                        class="flex-1 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm transition-all shadow-sm">
                    حفظ الدور
                </button>
                <button type="button" onclick="closeRoleModal()"
                        class="px-5 py-3 rounded-xl bg-[#F5F3EE] hover:bg-[#EAE7E0] text-[#73777A] font-bold text-sm transition-all">
                    إلغاء
                </button>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
const APP_BASE = "{{ rtrim(url('/'), '/') }}";

// ── Tab Switching ──────────────────────────────────────────────────────────
function switchTab(tab) {
    document.getElementById('panel-members').classList.toggle('hidden', tab !== 'members');
    document.getElementById('panel-roles').classList.toggle('hidden', tab !== 'roles');
    document.getElementById('tab-members').classList.toggle('active', tab === 'members');
    document.getElementById('tab-roles').classList.toggle('active', tab === 'roles');
    document.getElementById('tab-members').classList.toggle('text-[#73777A]', tab !== 'members');
    document.getElementById('tab-roles').classList.toggle('text-[#73777A]', tab !== 'roles');
    document.getElementById('btn-add-member').classList.toggle('hidden', tab !== 'members');
    document.getElementById('btn-add-role').classList.toggle('hidden', tab !== 'roles');
}

// ── Member Modal ───────────────────────────────────────────────────────────
function openAddMember() {
    document.getElementById('modal-member-title').textContent = 'إضافة عضو جديد';
    document.getElementById('member-id').value = '';
    document.getElementById('m-name').value = '';
    document.getElementById('m-email').value = '';
    document.getElementById('m-phone').value = '';
    document.getElementById('m-role').value = '';
    document.getElementById('m-status').value = 'active';
    document.getElementById('m-password').value = '';
    document.getElementById('m-notes').value = '';
    document.getElementById('pwd-required').classList.remove('hidden');
    document.getElementById('pwd-hint').classList.add('hidden');
    document.getElementById('member-error').classList.add('hidden');
    document.getElementById('modal-member').classList.remove('hidden');
}

function editMember(id) {
    const card = document.getElementById('member-card-' + id);
    const data = JSON.parse(card.dataset.user);
    document.getElementById('modal-member-title').textContent = 'تعديل بيانات العضو';
    document.getElementById('member-id').value = data.id;
    document.getElementById('m-name').value = data.name;
    document.getElementById('m-email').value = data.email;
    document.getElementById('m-phone').value = data.phone || '';
    document.getElementById('m-role').value = data.role_id || '';
    document.getElementById('m-status').value = data.status;
    document.getElementById('m-password').value = '';
    document.getElementById('m-notes').value = data.notes || '';
    document.getElementById('pwd-required').classList.add('hidden');
    document.getElementById('pwd-hint').classList.remove('hidden');
    document.getElementById('member-error').classList.add('hidden');
    document.getElementById('modal-member').classList.remove('hidden');
}

function closeMemberModal() {
    document.getElementById('modal-member').classList.add('hidden');
}

// Submit Member Form
document.getElementById('form-member').addEventListener('submit', async (e) => {
    e.preventDefault();
    const id   = document.getElementById('member-id').value;
    const btn  = document.getElementById('btn-save-member');
    const errEl = document.getElementById('member-error');
    errEl.classList.add('hidden');

    const body = {
        name:     document.getElementById('m-name').value.trim(),
        email:    document.getElementById('m-email').value.trim(),
        phone:    document.getElementById('m-phone').value.trim(),
        role_id:  document.getElementById('m-role').value || null,
        status:   document.getElementById('m-status').value,
        notes:    document.getElementById('m-notes').value.trim(),
        password: document.getElementById('m-password').value,
    };

    btn.disabled = true; btn.textContent = '...جاري الحفظ';

    try {
        const url    = id ? `${APP_BASE}/admin/team/users/${id}` : `${APP_BASE}/admin/team/users`;
        const method = id ? 'PUT' : 'POST';
        const res    = await fetch(url, {
            method,
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify(body),
        });
        const data = await res.json();
        if (data.success) {
            closeMemberModal();
            location.reload();
        } else {
            const msgs = data.errors ? Object.values(data.errors).flat().join(' — ') : (data.message || 'حدث خطأ');
            errEl.textContent = msgs;
            errEl.classList.remove('hidden');
        }
    } catch (err) {
        errEl.textContent = 'تعذر الاتصال بالخادم.';
        errEl.classList.remove('hidden');
    } finally {
        btn.disabled = false; btn.textContent = 'حفظ العضو';
    }
});

async function deleteMember(id, name) {
    if (!confirm(`هل تريد حذف العضو «${name}»؟ هذا الإجراء لا يمكن التراجع عنه.`)) return;
    try {
        const res  = await fetch(`${APP_BASE}/admin/team/users/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
        });
        const data = await res.json();
        if (data.success) {
            document.getElementById('member-card-' + id)?.remove();
        } else {
            alert(data.message || 'فشل الحذف.');
        }
    } catch(e) { alert('تعذر الاتصال بالخادم.'); }
}

// ── Role Modal ─────────────────────────────────────────────────────────────
function openAddRole() {
    document.getElementById('modal-role-title').textContent = 'إنشاء دور جديد';
    document.getElementById('role-id').value = '';
    document.getElementById('r-name').value = '';
    document.getElementById('r-slug').value = '';
    document.getElementById('r-desc').value = '';
    document.getElementById('r-slug').disabled = false;
    document.querySelectorAll('.perm-chk').forEach(c => c.checked = false);
    document.getElementById('role-error').classList.add('hidden');
    document.getElementById('modal-role').classList.remove('hidden');
}

function editRole(id) {
    const card = document.getElementById('role-card-' + id);
    const data = JSON.parse(card.dataset.role);
    document.getElementById('modal-role-title').textContent = 'تعديل الدور: ' + data.name;
    document.getElementById('role-id').value = data.id;
    document.getElementById('r-name').value = data.name;
    document.getElementById('r-slug').value = data.slug;
    document.getElementById('r-slug').disabled = true;
    document.getElementById('r-desc').value = data.description || '';
    // Reset then set permissions
    document.querySelectorAll('.perm-chk').forEach(c => {
        c.checked = data.permissions.includes(c.value);
    });
    document.getElementById('role-error').classList.add('hidden');
    document.getElementById('modal-role').classList.remove('hidden');
}

function closeRoleModal() {
    document.getElementById('modal-role').classList.add('hidden');
}

function selectAllPerms(val) {
    document.querySelectorAll('.perm-chk').forEach(c => c.checked = val);
}

// Auto-slug from name
document.getElementById('r-name')?.addEventListener('input', function() {
    if (!document.getElementById('r-slug').disabled) {
        document.getElementById('r-slug').value = this.value
            .toLowerCase()
            .replace(/\s+/g, '-')
            .replace(/[^a-z0-9-]/g, '')
            .slice(0, 60);
    }
});

// Submit Role Form
document.getElementById('form-role').addEventListener('submit', async (e) => {
    e.preventDefault();
    const id   = document.getElementById('role-id').value;
    const btn  = document.getElementById('btn-save-role');
    const errEl = document.getElementById('role-error');
    errEl.classList.add('hidden');

    const permissions = [...document.querySelectorAll('.perm-chk:checked')].map(c => c.value);
    const body = {
        name:        document.getElementById('r-name').value.trim(),
        slug:        document.getElementById('r-slug').value.trim(),
        description: document.getElementById('r-desc').value.trim(),
        permissions,
    };

    btn.disabled = true; btn.textContent = '...جاري الحفظ';

    try {
        const url    = id ? `${APP_BASE}/admin/team/roles/${id}` : `${APP_BASE}/admin/team/roles`;
        const method = id ? 'PUT' : 'POST';
        const res    = await fetch(url, {
            method,
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify(body),
        });
        const data = await res.json();
        if (data.success) {
            closeRoleModal();
            location.reload();
        } else {
            const msgs = data.errors ? Object.values(data.errors).flat().join(' — ') : (data.message || 'حدث خطأ');
            errEl.textContent = msgs;
            errEl.classList.remove('hidden');
        }
    } catch(err) {
        errEl.textContent = 'تعذر الاتصال بالخادم.';
        errEl.classList.remove('hidden');
    } finally {
        btn.disabled = false; btn.textContent = 'حفظ الدور';
    }
});

async function deleteRole(id, name) {
    if (!confirm(`هل تريد حذف الدور «${name}»؟`)) return;
    try {
        const res  = await fetch(`${APP_BASE}/admin/team/roles/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
        });
        const data = await res.json();
        if (data.success) {
            document.getElementById('role-card-' + id)?.remove();
        } else {
            alert(data.message || 'فشل الحذف.');
        }
    } catch(e) { alert('تعذر الاتصال بالخادم.'); }
}

// ── Tab Switching (updated with notifs) ────────────────────────────────────
function switchTab(tab) {
    const panels = ['members', 'roles', 'notifs'];
    panels.forEach(p => {
        document.getElementById('panel-' + p)?.classList.toggle('hidden', p !== tab);
        const btn = document.getElementById('tab-' + p);
        if (btn) {
            btn.classList.toggle('active', p === tab);
            btn.classList.toggle('text-[#73777A]', p !== tab);
        }
    });

    document.getElementById('btn-add-member')?.classList.toggle('hidden', tab !== 'members');
    document.getElementById('btn-add-role')?.classList.toggle('hidden', tab !== 'roles');

    if (tab === 'notifs') loadSentNotifications();
}

// ── Notifications: Select All Users ───────────────────────────────────────
function selectAllUsers() {
    document.querySelectorAll('input[name="notif_users"]').forEach(c => c.checked = true);
}

// ── Send Notification ──────────────────────────────────────────────────────
document.getElementById('form-notif')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn   = document.getElementById('btn-send-notif');
    const errEl = document.getElementById('notif-error');
    errEl.classList.add('hidden');

    const userIds = [...document.querySelectorAll('input[name="notif_users"]:checked')].map(c => +c.value);
    if (userIds.length === 0) {
        errEl.textContent = 'اختر موظفاً على الأقل.';
        errEl.classList.remove('hidden');
        return;
    }

    const title = document.getElementById('notif-title').value.trim();
    if (!title) {
        errEl.textContent = 'عنوان الإشعار مطلوب.';
        errEl.classList.remove('hidden');
        return;
    }

    const scheduledAt = document.getElementById('notif-at').value;
    if (!scheduledAt) {
        errEl.textContent = 'حدد وقت الإشعار.';
        errEl.classList.remove('hidden');
        return;
    }

    btn.disabled = true; btn.textContent = '...جاري الإرسال';

    try {
        const res = await fetch(`${APP_BASE}/admin/notifications`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({
                user_ids:     userIds,
                title:        title,
                body:         document.getElementById('notif-body').value.trim(),
                icon:         document.getElementById('notif-icon').value,
                color:        document.getElementById('notif-color').value,
                scheduled_at: scheduledAt,
            }),
        });
        const data = await res.json();
        if (data.success) {
            // Reset form
            document.getElementById('form-notif').reset();
            document.querySelectorAll('input[name="notif_users"]').forEach(c => c.checked = false);
            showToast(data.message, 'green');
            loadSentNotifications();
        } else {
            const msgs = data.errors ? Object.values(data.errors).flat().join(' — ') : (data.message || 'حدث خطأ');
            errEl.textContent = msgs;
            errEl.classList.remove('hidden');
        }
    } catch(err) {
        errEl.textContent = 'تعذر الاتصال بالخادم.';
        errEl.classList.remove('hidden');
    } finally {
        btn.disabled = false; btn.textContent = 'إرسال الإشعار 🔔';
    }
});

// ── Load Sent Notifications ────────────────────────────────────────────────
async function loadSentNotifications() {
    const list = document.getElementById('sent-notifs-list');
    if (!list) return;
    list.innerHTML = '<div class="p-8 text-center text-[#B0ADA8] text-sm animate-pulse">...جاري التحميل</div>';

    try {
        const res  = await fetch(`${APP_BASE}/admin/notifications/sent`, { headers: { 'Accept': 'application/json' } });
        const data = await res.json();

        if (!data.success || data.notifications.length === 0) {
            list.innerHTML = '<div class="p-8 text-center text-[#B0ADA8] text-sm">لا توجد إشعارات مُرسلة</div>';
            return;
        }

        const iconMap = { bell: '🔔', task: '✅', warning: '⚠️', info: 'ℹ️' };

        list.innerHTML = data.notifications.map(n => `
            <div class="flex items-start gap-3 p-4 hover:bg-[#FAF9F5] transition-all" id="sn-${n.id}">
                <span class="text-xl flex-shrink-0">${iconMap[n.icon] ?? '🔔'}</span>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <p class="text-xs font-bold text-[#303334] leading-tight">${n.title}</p>
                        <div class="flex items-center gap-1 flex-shrink-0">
                            <span class="text-[10px] ${n.is_read ? 'text-green-500' : 'text-amber-500'} font-bold">
                                ${n.is_read ? '✓ مقروء' : '⏳ لم يُقرأ'}
                            </span>
                            <button onclick="deleteSentNotif(${n.id})"
                                    class="size-5 flex items-center justify-center rounded text-[#B0ADA8] hover:text-red-500 hover:bg-red-50 transition-all text-xs">✕</button>
                        </div>
                    </div>
                    ${n.body ? `<p class="text-[11px] text-[#73777A] mt-0.5 line-clamp-1">${n.body}</p>` : ''}
                    <div class="flex items-center gap-3 mt-1">
                        <span class="text-[10px] text-[#B0ADA8]">→ ${n.recipient ?? 'غير محدد'}</span>
                        <span class="text-[10px] text-[#B0ADA8]">📅 ${n.scheduled_at}</span>
                    </div>
                </div>
            </div>
        `).join('');
    } catch(e) {
        list.innerHTML = '<div class="p-6 text-center text-red-400 text-xs">فشل التحميل</div>';
    }
}

async function deleteSentNotif(id) {
    try {
        const res = await fetch(`${APP_BASE}/admin/notifications/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
        });
        const data = await res.json();
        if (data.success) document.getElementById('sn-' + id)?.remove();
    } catch(e) {}
}

// ── Toast Helper ───────────────────────────────────────────────────────────
function showToast(msg, color = 'green') {
    const colors = {
        green: 'bg-green-600', blue: 'bg-blue-600', red: 'bg-red-600', amber: 'bg-amber-500'
    };
    const toast = document.createElement('div');
    toast.className = `fixed bottom-6 start-6 z-[9999] ${colors[color] ?? colors.green} text-white px-5 py-3 rounded-2xl shadow-xl text-sm font-bold transition-all`;
    toast.textContent = msg;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3500);
}
</script>

{{-- ══ Global Staff Notification Popup (appears for logged-in user) ══════ --}}
<div id="staff-notif-popup" class="hidden fixed inset-0 z-[9999] flex items-end justify-end p-6 pointer-events-none">
    <div id="staff-notif-stack" class="flex flex-col gap-3 max-w-sm w-full pointer-events-auto"></div>
</div>

<script>
// ── Global Staff Notification Polling (every 30s) ─────────────────────────
const NOTIF_ICONS = { bell: '🔔', task: '✅', warning: '⚠️', info: 'ℹ️' };
const NOTIF_COLORS = {
    green: { bg: '#EBF4E8', border: '#4E8F35', text: '#2D5C1E' },
    blue:  { bg: '#EFF6FF', border: '#3B82F6', text: '#1D4ED8' },
    amber: { bg: '#FFFBEB', border: '#F59E0B', text: '#92400E' },
    red:   { bg: '#FEF2F2', border: '#EF4444', text: '#991B1B' },
};
const shownNotifIds = new Set();

async function pollStaffNotifications() {
    try {
        const res  = await fetch(`${APP_BASE}/admin/notifications/mine`, { headers: { 'Accept': 'application/json' } });
        const data = await res.json();

        if (!data.success || data.count === 0) return;

        const stack = document.getElementById('staff-notif-stack');
        document.getElementById('staff-notif-popup').classList.remove('hidden');

        data.notifications.forEach(n => {
            if (shownNotifIds.has(n.id)) return;
            shownNotifIds.add(n.id);

            const c = NOTIF_COLORS[n.color] ?? NOTIF_COLORS.green;
            const card = document.createElement('div');
            card.id = `popup-notif-${n.id}`;
            card.style.cssText = `background:${c.bg}; border: 1.5px solid ${c.border}; border-radius: 16px; padding: 14px 16px; box-shadow: 0 8px 32px rgba(0,0,0,.15); animation: slideInUp .3s ease;`;
            card.innerHTML = `
                <div style="display:flex; align-items:flex-start; gap:10px;">
                    <span style="font-size:20px; flex-shrink:0;">${NOTIF_ICONS[n.icon] ?? '🔔'}</span>
                    <div style="flex:1; min-width:0;">
                        <p style="font-weight:800; font-size:13px; color:${c.text}; margin:0 0 2px;">${n.title}</p>
                        ${n.body ? `<p style="font-size:11px; color:#73777A; margin:0 0 6px;">${n.body}</p>` : ''}
                        <p style="font-size:10px; color:#B0ADA8; margin:0;">من: ${n.sender} • ${n.scheduled_at}</p>
                    </div>
                    <button onclick="dismissStaffNotif(${n.id})"
                            style="flex-shrink:0; width:24px; height:24px; border-radius:8px; background:rgba(0,0,0,.08); border:none; cursor:pointer; font-size:12px; color:#73777A; display:flex; align-items:center; justify-content:center;">✕</button>
                </div>`;
            stack.prepend(card);

            // Play beep
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain); gain.connect(ctx.destination);
                osc.frequency.value = 620; gain.gain.value = 0.15;
                osc.start(); osc.stop(ctx.currentTime + 0.18);
            } catch(e) {}
        });
    } catch(e) {}
}

async function dismissStaffNotif(id) {
    try {
        await fetch(`${APP_BASE}/admin/notifications/${id}/read`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
        });
    } catch(e) {}
    document.getElementById(`popup-notif-${id}`)?.remove();
    if (!document.getElementById('staff-notif-stack')?.children.length) {
        document.getElementById('staff-notif-popup')?.classList.add('hidden');
    }
}

// Start polling
pollStaffNotifications();
setInterval(pollStaffNotifications, 30000);
</script>

<style>
@keyframes slideInUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
}
</style>
@endsection
