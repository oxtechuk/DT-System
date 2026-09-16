@extends('shared.vertical', ['title' => 'Settings — الإعدادات'])

@php
 $settings = $settings ?? \App\Models\Setting::getAllAsArray();
@endphp

@section('styles')
<style>
 .settings-nav-item {
 display: flex;
 align-items: center;
 gap: 10px;
 padding: 10px 12px;
 border-radius: 8px;
 cursor: pointer;
 font-size: 13px;
 font-weight: 500;
 color: #57606a;
 transition: all 0.15s;
 text-decoration: none;
 }
 .settings-nav-item:hover { background: rgb(var(--color-primary) / 0.06); color: rgb(var(--color-primary)); }
 .settings-nav-item.active { background: rgb(var(--color-primary) / 0.1); color: rgb(var(--color-primary)); font-weight: 600; }

 .color-swatch {
 width: 36px;
 height: 36px;
 border-radius: 8px;
 cursor: pointer;
 border: 2px solid transparent;
 transition: all 0.2s;
 flex-shrink: 0;
 }
 .color-swatch.selected { border-color: #333; transform: scale(1.15); box-shadow: 0 0 0 3px rgba(0,0,0,0.1); }

 .logo-upload-zone {
 border: 2px dashed #d0d7de;
 border-radius: 12px;
 padding: 32px 20px;
 text-align: center;
 cursor: pointer;
 transition: all 0.2s;
 background: #f6f8fa;
 }
 .logo-upload-zone:hover { border-color: rgb(var(--color-primary) / 0.6); background: rgb(var(--color-primary) / 0.03); }
 .logo-upload-zone.dragging { border-color: rgb(var(--color-primary)); background: rgb(var(--color-primary) / 0.05); }

 .preview-card {
 border: 1px solid #e5e7eb;
 border-radius: 12px;
 overflow: hidden;
 background: white;
 }
 .preview-sidebar {
 width: 200px;
 background: white;
 border-right: 1px solid #e5e7eb;
 padding: 12px;
 font-size: 11px;
 }
 .preview-sidebar .logo-preview {
 height: 28px;
 display: flex;
 align-items: center;
 justify-content: center;
 margin-bottom: 12px;
 padding-bottom: 10px;
 border-bottom: 1px solid #e5e7eb;
 font-weight: 800;
 font-size: 13px;
 }
 .preview-menu-item {
 display: flex;
 align-items: center;
 gap: 8px;
 padding: 6px 8px;
 border-radius: 6px;
 margin-bottom: 2px;
 font-size: 11px;
 color: #57606a;
 }
 .preview-menu-item.active { background: var(--preview-primary-light); color: var(--preview-primary); font-weight: 600; }
 .preview-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--preview-primary); }
 .preview-topbar {
 background: white;
 border-bottom: 1px solid #e5e7eb;
 padding: 8px 14px;
 display: flex;
 align-items: center;
 justify-content: space-between;
 font-size: 11px;
 }
 .preview-btn {
 background: var(--preview-primary);
 color: white;
 border-radius: 6px;
 padding: 4px 10px;
 font-size: 10px;
 font-weight: 600;
 }
 .preview-card-widget {
 background: white;
 border: 1px solid #e5e7eb;
 border-radius: 8px;
 padding: 10px;
 margin: 4px;
 }
 .preview-card-icon {
 width: 28px;
 height: 28px;
 border-radius: 8px;
 background: var(--preview-primary-light);
 display: flex;
 align-items: center;
 justify-content: center;
 }

 input[type="color"] {
 -webkit-appearance: none;
 width: 40px;
 height: 40px;
 border: none;
 border-radius: 8px;
 cursor: pointer;
 padding: 2px;
 }
 input[type="color"]::-webkit-color-swatch-wrapper { padding: 0; border-radius: 6px; }
 input[type="color"]::-webkit-color-swatch { border-radius: 6px; border: none; }
</style>
@endsection

@section('content')
 @include('shared.partials.page-title', ['subtitle' => 'Manage your system preferences', 'title' => 'Settings — الإعدادات'])

 <div class="grid xl:grid-cols-4 gap-6">

 {{-- ──────── Settings Sidebar Navigation ──────── --}}
 <div class="xl:col-span-1">
 <div class="card border-0 shadow-sm">
 <div class="card-body p-3">
 <p class="text-[10px] uppercase font-bold tracking-widest text-default-400 px-2 py-2">General / عام</p>
 <nav class="space-y-0.5">
 <a class="settings-nav-item active" href="{{ url('/settings/general') }}">
 <i class="iconify lucide--palette size-4"></i>
 Branding & Colors <span class="text-[10px] text-default-400">الهوية</span>
 </a>
 <a class="settings-nav-item" href="{{ url('/settings/general') }}">
 <i class="iconify lucide--building size-4"></i>
 Business Info <span class="text-[10px] text-default-400">معلومات</span>
 </a>
 </nav>
 <p class="text-[10px] uppercase font-bold tracking-widest text-default-400 px-2 py-2 mt-3">Workspace / المساحة</p>
 <nav class="space-y-0.5">
 <a class="settings-nav-item" href="{{ url('/rooms') }}">
 <i class="iconify lucide--door-open size-4"></i>
 Rooms <span class="text-[10px] text-default-400">إدارة الغرف</span>
 </a>
 <a class="settings-nav-item" href="{{ url('/settings/workspace-types') }}">
 <i class="iconify lucide--layers size-4"></i>
 Workspace Types <span class="text-[10px] text-default-400">الأنواع</span>
 </a>
 <a class="settings-nav-item" href="{{ url('/settings/pricing') }}">
 <i class="iconify lucide--tag size-4"></i>
 Pricing Rules <span class="text-[10px] text-default-400">التسعير</span>
 </a>
 </nav>
 <p class="text-[10px] uppercase font-bold tracking-widest text-default-400 px-2 py-2 mt-3">Finance / المالية</p>
 <nav class="space-y-0.5">
 <a class="settings-nav-item" href="{{ url('/payments') }}">
 <i class="iconify lucide--credit-card size-4"></i>
 Payments <span class="text-[10px] text-default-400">المدفوعات والدفع</span>
 </a>
 <a class="settings-nav-item" href="{{ url('/settings/expense-categories') }}">
 <i class="iconify lucide--folder size-4"></i>
 Expense Categories <span class="text-[10px] text-default-400">المصروفات</span>
 </a>
 </nav>
 <p class="text-[10px] uppercase font-bold tracking-widest text-default-400 px-2 py-2 mt-3">Access / الوصول</p>
 <nav class="space-y-0.5">
 <a class="settings-nav-item" href="{{ url('/settings/users') }}">
 <i class="iconify lucide--users size-4"></i>
 Users & Roles <span class="text-[10px] text-default-400">المستخدمون</span>
 </a>
 </nav>
 </div>
 </div>
 </div>

 {{-- ──────── Main Settings Area ──────── --}}
 <div class="xl:col-span-3 space-y-6">

 {{-- ── Logo Section ── --}}
 <div class="card border-0 shadow-sm">
 <div class="card-body p-6">
 <h5 class="text-sm font-bold text-default-800 mb-1">Logo / الشعار</h5>
 <p class="text-xs text-default-400 mb-5">Upload your brand logo — يظهر في الشريط الجانبي والتوبار</p>

 <div class="grid md:grid-cols-3 gap-5">
 {{-- Main Logo --}}
 <div>
 <label class="block text-xs font-semibold text-default-600 mb-2 uppercase tracking-wide">
 Main Logo <span class="text-default-400">الشعار الرئيسي</span>
 </label>
 <div class="logo-upload-zone" id="logo-main-zone" onclick="document.getElementById('logo-main-input').click()">
 <div id="logo-main-preview">
 @if(!empty($settings['logo_main']))
 <img src="{{ asset('storage/'.$settings['logo_main']) }}" alt="Logo" class="h-12 mx-auto mb-3 object-contain"/>
 @else
 <i class="iconify lucide--image text-default-300 size-10 mx-auto mb-3"></i>
 @endif
 </div>
 <p class="text-xs text-default-500 font-medium">Drop your logo here or <span class="text-primary">browse</span></p>
 <p class="text-[10px] text-default-400 mt-1">PNG, SVG recommended — Max 2MB</p>
 <p class="text-[10px] text-default-400">الشعار الرئيسي المعروض في السايدبار والتوبار</p>
 <input accept="image/*" class="hidden" id="logo-main-input" name="logo_main" type="file"/>
 </div>
 </div>

 {{-- Small Logo / Icon --}}
 <div>
 <label class="block text-xs font-semibold text-default-600 mb-2 uppercase tracking-wide">
 App Icon <span class="text-default-400">أيقونة الموبايل</span>
 </label>
 <div class="logo-upload-zone" id="logo-sm-zone" onclick="document.getElementById('logo-sm-input').click()">
 <div id="logo-sm-preview">
 @if(!empty($settings['logo_sm']))
 <img src="{{ asset('storage/'.$settings['logo_sm']) }}" alt="Logo SM" class="h-12 mx-auto mb-3 object-contain"/>
 @else
 <i class="iconify lucide--image text-default-300 size-10 mx-auto mb-3"></i>
 @endif
 </div>
 <p class="text-xs text-default-500 font-medium">Square icon for mobile / PWA</p>
 <p class="text-[10px] text-default-400 mt-1">PNG, SVG — 512×512 px</p>
 <p class="text-[10px] text-default-400">أيقونة التطبيق والشاشات المصغرة</p>
 <input accept="image/*" class="hidden" id="logo-sm-input" name="logo_sm" type="file"/>
 </div>
 </div>

 {{-- Favicon --}}
 <div>
 <label class="block text-xs font-semibold text-default-600 mb-2 uppercase tracking-wide">
 Favicon <span class="text-default-400">أيقونة المتصفح</span>
 </label>
 <div class="logo-upload-zone" id="logo-fav-zone" onclick="document.getElementById('favicon-input').click()">
 <div id="logo-fav-preview">
 @if(!empty($settings['favicon']))
 <img src="{{ asset('storage/'.$settings['favicon']) }}" alt="Favicon" class="h-10 mx-auto mb-3 object-contain"/>
 @else
 <i class="iconify lucide--globe text-default-300 size-10 mx-auto mb-3"></i>
 @endif
 </div>
 <p class="text-xs text-default-500 font-medium">Browser tab icon (.ico, .png)</p>
 <p class="text-[10px] text-default-400 mt-1">32×32 or 64×64 px</p>
 <p class="text-[10px] text-default-400">تظهر في علامات تبويب المتصفح</p>
 <input accept="image/x-icon,image/png,image/svg+xml" class="hidden" id="favicon-input" name="favicon" type="file"/>
 </div>
 </div>
 </div>
 </div>
 </div>

 {{-- ── Colors Section ── --}}
 <div class="card border-0 shadow-sm">
 <div class="card-body p-6">
 <h5 class="text-sm font-bold text-default-800 mb-1">Primary Color / اللون الأساسي</h5>
 <p class="text-xs text-default-400 mb-5">Choose the main brand color that appears throughout the dashboard — اختر اللون الذي يظهر في كل الداشبورد</p>

 {{-- Preset Color Swatches --}}
 <div class="mb-5">
 <label class="block text-xs font-semibold text-default-600 mb-3 uppercase tracking-wide">Quick Presets / ألوان جاهزة</label>
 <div class="flex flex-wrap gap-3" id="color-swatches">
 <div class="color-swatch selected" data-color="#6366f1" data-name="Indigo (Default)" style="background:#6366f1;" title="Indigo — الأزرق البنفسجي"></div>
 <div class="color-swatch" data-color="#8b5cf6" data-name="Violet" style="background:#8b5cf6;" title="Violet — البنفسجي"></div>
 <div class="color-swatch" data-color="#ec4899" data-name="Pink" style="background:#ec4899;" title="Pink — الوردي"></div>
 <div class="color-swatch" data-color="#ef4444" data-name="Red" style="background:#ef4444;" title="Red — الأحمر"></div>
 <div class="color-swatch" data-color="#f97316" data-name="Orange" style="background:#f97316;" title="Orange — البرتقالي"></div>
 <div class="color-swatch" data-color="#eab308" data-name="Yellow" style="background:#eab308;" title="Yellow — الأصفر"></div>
 <div class="color-swatch" data-color="#22c55e" data-name="Green" style="background:#22c55e;" title="Green — الأخضر"></div>
 <div class="color-swatch" data-color="#14b8a6" data-name="Teal" style="background:#14b8a6;" title="Teal — الفيروزي"></div>
 <div class="color-swatch" data-color="#06b6d4" data-name="Cyan" style="background:#06b6d4;" title="Cyan — السماوي"></div>
 <div class="color-swatch" data-color="#3b82f6" data-name="Blue" style="background:#3b82f6;" title="Blue — الأزرق"></div>
 <div class="color-swatch" data-color="#1e293b" data-name="Dark Slate" style="background:#1e293b;" title="Dark Slate — الرمادي الداكن"></div>
 <div class="color-swatch" data-color="#64748b" data-name="Slate" style="background:#64748b;" title="Slate — الرصاصي"></div>
 </div>
 </div>

 {{-- Custom Color Picker --}}
 <div class="flex items-center gap-4 p-4 bg-default-50 rounded-xl mb-5">
 <div>
 <label class="block text-xs font-semibold text-default-600 mb-1 uppercase tracking-wide">Custom Color / لون مخصص</label>
 <div class="flex items-center gap-3">
 <input type="color" id="custom-color-picker" value="#6366f1" class="rounded-lg border border-default-200"/>
 <div>
 <input type="text" id="custom-color-hex" value="#6366f1"
 class="form-input w-32 text-sm font-mono"
 placeholder="#6366f1" maxlength="7"/>
 <p class="text-[10px] text-default-400 mt-1" id="selected-color-name">Indigo (Default)</p>
 </div>
 </div>
 </div>
 <div class="ms-auto text-right">
 <p class="text-xs text-default-500 mb-1">Preview button</p>
 <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-white text-sm font-semibold" id="color-preview-btn" style="background: #6366f1;">
 <i class="iconify lucide--check size-4"></i>
 Sample Button
 </div>
 </div>
 </div>

 {{-- Live Preview --}}
 <div>
 <label class="block text-xs font-semibold text-default-600 mb-3 uppercase tracking-wide">Live Preview / معاينة حية</label>
 <div class="preview-card">
 <div class="preview-topbar">
 <span class="font-bold text-xs">DT.SYSTEM</span>
 <div class="preview-btn" id="preview-topbar-btn">Open Cashier</div>
 </div>
 <div class="flex" style="min-height: 120px;">
 <div class="preview-sidebar">
 <div class="preview-menu-item active" id="preview-menu-active">
 <div class="preview-dot" id="preview-dot"></div>
 Dashboard
 </div>
 <div class="preview-menu-item">
 <div style="width:8px;height:8px;border-radius:50%;background:#e5e7eb;flex-shrink:0;"></div>
 Customers
 </div>
 <div class="preview-menu-item">
 <div style="width:8px;height:8px;border-radius:50%;background:#e5e7eb;flex-shrink:0;"></div>
 Deals
 </div>
 </div>
 <div class="flex-1 bg-gray-50 p-3">
 <div class="grid grid-cols-2 gap-2">
 <div class="preview-card-widget">
 <div class="flex items-center gap-2">
 <div class="preview-card-icon" id="preview-icon-1">
 <div style="width:10px;height:10px;border-radius:2px;" id="preview-icon-inner-1"></div>
 </div>
 <div>
 <div style="height:6px;width:40px;border-radius:3px;background:#e5e7eb;margin-bottom:4px;"></div>
 <div style="height:10px;width:30px;border-radius:3px;background:#333;"></div>
 </div>
 </div>
 </div>
 <div class="preview-card-widget">
 <div class="flex items-center gap-2">
 <div class="preview-card-icon" id="preview-icon-2">
 <div style="width:10px;height:10px;border-radius:2px;" id="preview-icon-inner-2"></div>
 </div>
 <div>
 <div style="height:6px;width:40px;border-radius:3px;background:#e5e7eb;margin-bottom:4px;"></div>
 <div style="height:10px;width:30px;border-radius:3px;background:#333;"></div>
 </div>
 </div>
 </div>
 </div>
 </div>
 </div>
 </div>
 </div>
 </div>
 </div>

 {{-- ── Business Info ── --}}
 <div class="card border-0 shadow-sm">
 <div class="card-body p-6">
 <h5 class="text-sm font-bold text-default-800 mb-1">Business Info / معلومات المكان</h5>
 <p class="text-xs text-default-400 mb-5">Basic information about your workspace — معلومات أساسية عن مساحتك</p>

 <div class="grid md:grid-cols-2 gap-5">
 <div>
 <label class="block text-xs font-semibold text-default-600 mb-1.5 uppercase tracking-wide">Business Name / اسم المساحة أو المكان</label>
 <input type="text" id="setting-business-name" class="form-input" placeholder="e.g. DT WorkSpace" value="{{ $settings['workspace_name'] ?? 'DT WorkSpace' }}"/>
 </div>
 <div>
 <label class="block text-xs font-semibold text-default-600 mb-1.5 uppercase tracking-wide">Workspace Slogan / الشعار اللفظي أو الوصف</label>
 <input type="text" id="setting-workspace-slogan" class="form-input" placeholder="مثال: مساحة العمل المتكاملة للإبداع والإنتاجية" value="{{ $settings['workspace_slogan'] ?? 'مساحة العمل المتكاملة' }}"/>
 </div>
 <div>
 <label class="block text-xs font-semibold text-default-600 mb-1.5 uppercase tracking-wide">Receipt Footer / نص أسفل فاتورة الكاشير</label>
 <input type="text" id="setting-receipt-footer" class="form-input" placeholder="مثال: شكراً لزيارتكم ونتمنى لكم يوماً سعيداً!" value="{{ $settings['receipt_footer'] ?? 'شكراً لزيارتكم!' }}"/>
 </div>
 <div>
 <label class="block text-xs font-semibold text-default-600 mb-1.5 uppercase tracking-wide">Currency / العملة الافتراضية</label>
 <select id="setting-currency" class="form-input">
 <option value="EGP" {{ ($settings['currency'] ?? 'EGP') === 'EGP' ? 'selected' : '' }}>EGP — جنيه مصري</option>
 <option value="USD" {{ ($settings['currency'] ?? '') === 'USD' ? 'selected' : '' }}>USD — Dollar</option>
 <option value="SAR" {{ ($settings['currency'] ?? '') === 'SAR' ? 'selected' : '' }}>SAR — ريال سعودي</option>
 <option value="AED" {{ ($settings['currency'] ?? '') === 'AED' ? 'selected' : '' }}>AED — درهم إماراتي</option>
 </select>
 </div>
 <div>
 <label class="block text-xs font-semibold text-default-600 mb-1.5 uppercase tracking-wide">Phone / رقم الهاتف الأساسي</label>
 <input type="text" id="setting-phone" class="form-input" placeholder="01xxxxxxxxx" value="{{ $settings['workspace_phone'] ?? '' }}"/>
 </div>
 <div>
 <label class="block text-xs font-semibold text-default-600 mb-1.5 uppercase tracking-wide">WhatsApp / رقم الواتساب المباشر</label>
 <input type="text" id="setting-whatsapp" class="form-input" placeholder="01xxxxxxxxx" value="{{ $settings['workspace_whatsapp'] ?? '' }}"/>
 </div>
 <div class="md:col-span-2">
 <label class="block text-xs font-semibold text-default-600 mb-1.5 uppercase tracking-wide">Email / البريد الإلكتروني الرسمي</label>
 <input type="email" id="setting-email" class="form-input" placeholder="info@example.com" value="{{ $settings['workspace_email'] ?? '' }}"/>
 </div>
 <div class="md:col-span-2">
 <label class="block text-xs font-semibold text-default-600 mb-1.5 uppercase tracking-wide">Address / العنوان الفعلي للمساحة</label>
 <textarea id="setting-address" class="form-input" rows="2" placeholder="العنوان بالتفصيل ليظهر في الفاتورة والبروفايل...">{{ $settings['workspace_address'] ?? '' }}</textarea>
 </div>
 </div>
 </div>
 </div>

 {{-- ── Loyalty & Affiliate Settings ── --}}
 <div class="card border-0 shadow-sm" id="loyalty-affiliate-section">
 <div class="card-body p-6">

 <p class="text-xs text-default-400 mb-5">التحكم في شروط الزيارة السادسة المجانية ونسب خصم الإحالة للعملاء</p>

 <div class="grid md:grid-cols-2 gap-6">
 {{-- Loyalty Visits --}}
 <div class="space-y-4 p-4 rounded-xl bg-slate-50 border border-slate-200">
 <h6 class="text-xs font-bold text-indigo-700 flex items-center gap-1.5">
 <span></span>
 <span>شروط نظام الولاء (Loyalty Visits)</span>
 </h6>

 <div>
 <label class="block text-xs font-semibold text-default-600 mb-1">عدد الزيارات المطلوبة في الدورة</label>
 <input type="number" id="setting-loyalty-visits" 
 value="{{ $settings['loyalty_required_visits'] ?? 5 }}" 
 class="form-input w-full text-xs rounded-lg" min="1" max="50">
 <span class="text-[10px] text-default-400">الافتراضي: 5 زيارات (الزيارة التالية تكون مجانية)</span>
 </div>

 <div>
 <label class="block text-xs font-semibold text-default-600 mb-1">الحد الأدنى لجلسة التأهيل (بالدقائق)</label>
 <input type="number" id="setting-loyalty-min-minutes" 
 value="{{ $settings['loyalty_min_duration_minutes'] ?? 180 }}" 
 class="form-input w-full text-xs rounded-lg" step="15" min="30">
 <span class="text-[10px] text-default-400">الافتراضي: 180 دقيقة (3 ساعات) كشرط وجود جلسة طويلة</span>
 </div>
 </div>

 {{-- Affiliate / Referral --}}
 <div class="space-y-4 p-4 rounded-xl bg-slate-50 border border-slate-200">
 <h6 class="text-xs font-bold text-violet-700 flex items-center gap-1.5">
 <span></span>
 <span>نظام التوصية والإحالة (Affiliate Discount)</span>
 </h6>

 <div>
 <label class="block text-xs font-semibold text-default-600 mb-1">نوع الخصم للعميل الجديد</label>
 <select id="setting-affiliate-discount-type" class="form-select w-full text-xs rounded-lg">
 <option value="percentage" {{ ($settings['affiliate_discount_type'] ?? 'percentage') === 'percentage' ? 'selected' : '' }}>نسبة مئوية (%)</option>
 <option value="fixed" {{ ($settings['affiliate_discount_type'] ?? '') === 'fixed' ? 'selected' : '' }}>مبلغ ثابت (جنيه)</option>
 </select>
 </div>

 <div>
 <label class="block text-xs font-semibold text-default-600 mb-1">قيمة الخصم للعميل الجديد</label>
 <input type="number" id="setting-affiliate-discount-val" 
 value="{{ $settings['affiliate_discount_value'] ?? 20 }}" 
 class="form-input w-full text-xs rounded-lg" min="0">
 <span class="text-[10px] text-default-400">مثال: 20% أو 50 جنيه على الزيارة الأولى</span>
 </div>
 </div>
 </div>
 </div>
 </div>

 {{-- ── Save Button ── --}}
 <div class="flex items-center justify-end gap-3">
 <button class="btn btn-light px-6 py-2.5">Reset / إعادة تعيين</button>
 <button class="btn btn-primary px-8 py-2.5 font-semibold" id="btn-save-settings">
 <i class="iconify lucide--save size-4 me-2"></i>
 Save Settings / حفظ الإعدادات
 </button>
 </div>

 </div><!-- end main settings area -->
 </div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

 const STORED_COLOR = localStorage.getItem('dt_primary_color') || '#6366f1';
 applyColor(STORED_COLOR);
 syncInputs(STORED_COLOR);

 // ── Color Swatches ──
 document.querySelectorAll('.color-swatch').forEach(function(swatch) {
 if (swatch.dataset.color.toLowerCase() === STORED_COLOR.toLowerCase()) {
 swatch.classList.add('selected');
 }
 swatch.addEventListener('click', function() {
 document.querySelectorAll('.color-swatch').forEach(s => s.classList.remove('selected'));
 this.classList.add('selected');
 const color = this.dataset.color;
 const name = this.dataset.name;
 applyColor(color);
 syncInputs(color);
 document.getElementById('selected-color-name').textContent = name;
 });
 });

 // ── Custom Color Picker ──
 document.getElementById('custom-color-picker').addEventListener('input', function() {
 applyColor(this.value);
 document.getElementById('custom-color-hex').value = this.value;
 document.querySelectorAll('.color-swatch').forEach(s => s.classList.remove('selected'));
 });

 document.getElementById('custom-color-hex').addEventListener('input', function() {
 if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
 applyColor(this.value);
 document.getElementById('custom-color-picker').value = this.value;
 document.querySelectorAll('.color-swatch').forEach(s => s.classList.remove('selected'));
 }
 });

 // ── Logo Upload Preview ──
 document.getElementById('logo-main-input').addEventListener('change', function(e) {
 const file = e.target.files[0];
 if (!file) return;
 const reader = new FileReader();
 reader.onload = function(ev) {
 document.getElementById('logo-main-preview').innerHTML =
 '<img src="' + ev.target.result + '" alt="Logo" class="h-12 mx-auto mb-3" style="object-fit:contain;"/>';
 };
 reader.readAsDataURL(file);
 });

 document.getElementById('logo-sm-input').addEventListener('change', function(e) {
 const file = e.target.files[0];
 if (!file) return;
 const reader = new FileReader();
 reader.onload = function(ev) {
 document.getElementById('logo-sm-preview').innerHTML =
 '<img src="' + ev.target.result + '" alt="Logo SM" class="h-10 mx-auto mb-3 object-contain"/>';
 };
 reader.readAsDataURL(file);
 });

 document.getElementById('favicon-input').addEventListener('change', function(e) {
 const file = e.target.files[0];
 if (!file) return;
 const reader = new FileReader();
 reader.onload = function(ev) {
 document.getElementById('logo-fav-preview').innerHTML =
 '<img src="' + ev.target.result + '" alt="Favicon" class="h-10 mx-auto mb-3 object-contain"/>';
 };
 reader.readAsDataURL(file);
 });

 // ── Drag & Drop ──
 ['logo-main-zone', 'logo-sm-zone', 'logo-fav-zone'].forEach(function(id) {
 const zone = document.getElementById(id);
 if (!zone) return;
 zone.addEventListener('dragover', function(e) { e.preventDefault(); zone.classList.add('dragging'); });
 zone.addEventListener('dragleave', function() { zone.classList.remove('dragging'); });
 zone.addEventListener('drop', function(e) {
 e.preventDefault();
 zone.classList.remove('dragging');
 let inputId = 'logo-main-input';
 if (id === 'logo-sm-zone') inputId = 'logo-sm-input';
 if (id === 'logo-fav-zone') inputId = 'favicon-input';
 const input = document.getElementById(inputId);
 input.files = e.dataTransfer.files;
 input.dispatchEvent(new Event('change'));
 });
 });

 // ── Save Settings ──
 document.getElementById('btn-save-settings').addEventListener('click', async function() {
 const btn = this;
 const color = document.getElementById('custom-color-hex').value;
 localStorage.setItem('dt_primary_color', color);

 const formData = new FormData();
 formData.append('primary_color', color);
 
 const bName = document.getElementById('setting-business-name');
 if (bName) formData.append('workspace_name', bName.value);

 const bSlogan = document.getElementById('setting-workspace-slogan');
 if (bSlogan) formData.append('workspace_slogan', bSlogan.value);

 const bFooter = document.getElementById('setting-receipt-footer');
 if (bFooter) formData.append('receipt_footer', bFooter.value);
 
 const bPhone = document.getElementById('setting-phone');
 if (bPhone) formData.append('workspace_phone', bPhone.value);

 const bWhatsapp = document.getElementById('setting-whatsapp');
 if (bWhatsapp) formData.append('workspace_whatsapp', bWhatsapp.value);

 const bEmail = document.getElementById('setting-email');
 if (bEmail) formData.append('workspace_email', bEmail.value);

 const bCurrency = document.getElementById('setting-currency');
 if (bCurrency) formData.append('currency', bCurrency.value);

 const bAddr = document.getElementById('setting-address');
 if (bAddr) formData.append('workspace_address', bAddr.value);

 const loyaltyVisits = document.getElementById('setting-loyalty-visits');
 if (loyaltyVisits) formData.append('loyalty_required_visits', loyaltyVisits.value);

 const loyaltyMinMinutes = document.getElementById('setting-loyalty-min-minutes');
 if (loyaltyMinMinutes) formData.append('loyalty_min_duration_minutes', loyaltyMinMinutes.value);

 const affType = document.getElementById('setting-affiliate-discount-type');
 if (affType) formData.append('affiliate_discount_type', affType.value);

 const affVal = document.getElementById('setting-affiliate-discount-val');
 if (affVal) formData.append('affiliate_discount_value', affVal.value);

 const logoMainInput = document.getElementById('logo-main-input');
 if (logoMainInput && logoMainInput.files[0]) {
 formData.append('logo_main', logoMainInput.files[0]);
 }

 const logoSmInput = document.getElementById('logo-sm-input');
 if (logoSmInput && logoSmInput.files[0]) {
 formData.append('logo_sm', logoSmInput.files[0]);
 }

 const favInput = document.getElementById('favicon-input');
 if (favInput && favInput.files[0]) {
 formData.append('favicon', favInput.files[0]);
 }

 btn.disabled = true;
 btn.innerHTML = '<i class="iconify lucide--loader-2 animate-spin size-4 me-2"></i> Saving... / جارٍ الحفظ';

 try {
 const token = document.querySelector('meta[name="csrf-token"]')?.content;
 const res = await fetch('/api/v1/settings', {
 method: 'POST',
 headers: {
 'X-CSRF-TOKEN': token || '',
 'Accept': 'application/json'
 },
 body: formData
 });
 const data = await res.json();

 btn.innerHTML = '<i class="iconify lucide--check size-4 me-2"></i> Saved! / تم الحفظ بنجاح';
 btn.classList.remove('btn-primary');
 btn.classList.add('btn-success');
 } catch (err) {
 console.error(err);
 btn.innerHTML = '<i class="iconify lucide--check size-4 me-2"></i> Saved Locally!';
 } finally {
 btn.disabled = false;
 setTimeout(() => {
 btn.innerHTML = '<i class="iconify lucide--save size-4 me-2"></i> Save Settings / حفظ الإعدادات';
 btn.classList.remove('btn-success');
 btn.classList.add('btn-primary');
 }, 2500);
 }
 });

 // ── Functions ──
 function hexToRgb(hex) {
 const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
 return result ? parseInt(result[1], 16) + ' ' + parseInt(result[2], 16) + ' ' + parseInt(result[3], 16) : null;
 }

 function adjustColor(hex, amount) {
 let r = parseInt(hex.slice(1, 3), 16);
 let g = parseInt(hex.slice(3, 5), 16);
 let b = parseInt(hex.slice(5, 7), 16);
 r = Math.min(255, Math.max(0, r + amount));
 g = Math.min(255, Math.max(0, g + amount));
 b = Math.min(255, Math.max(0, b + amount));
 return '#' + [r, g, b].map(v => v.toString(16).padStart(2, '0')).join('');
 }

 function applyColor(color) {
 // Apply to CSS variables globally
 const root = document.documentElement;
 const rgb = hexToRgb(color);
 if (rgb) {
 root.style.setProperty('--color-primary', color);
 root.style.setProperty('--preview-primary', color);
 root.style.setProperty('--preview-primary-light', color + '15');
 }

 // Update preview elements
 const previewBtn = document.getElementById('color-preview-btn');
 if (previewBtn) previewBtn.style.background = color;

 const topbarBtn = document.getElementById('preview-topbar-btn');
 if (topbarBtn) topbarBtn.style.background = color;

 const dot = document.getElementById('preview-dot');
 if (dot) dot.style.background = color;

 const activeMenu = document.getElementById('preview-menu-active');
 if (activeMenu) {
 activeMenu.style.background = color + '15';
 activeMenu.style.color = color;
 }

 [1, 2].forEach(function(i) {
 const icon = document.getElementById('preview-icon-' + i);
 if (icon) icon.style.background = color + '20';
 const inner = document.getElementById('preview-icon-inner-' + i);
 if (inner) inner.style.background = color;
 });
 }

 function syncInputs(color) {
 document.getElementById('custom-color-picker').value = color;
 document.getElementById('custom-color-hex').value = color;
 }

 // Apply stored color on every page load (global)
 const storedColor = localStorage.getItem('dt_primary_color');
 if (storedColor) {
 document.documentElement.style.setProperty('--color-primary', storedColor);
 }
});
</script>
@endsection
