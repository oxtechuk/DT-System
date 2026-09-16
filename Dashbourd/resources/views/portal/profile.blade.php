@extends('portal.layout')

@section('title', 'الملف الشخصي — DDT WORKING SPACE')

@section('content')

    <!-- Top Profile Header (Solid Light) -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-sm font-extrabold text-[#303334]">الملف الشخصي للأعضاء</h1>
            <p class="text-[11px] text-[#73777A]">إدارة حسابك في مجتمع DDT Working Space</p>
        </div>
        <form action="{{ route('portal.logout') }}" method="POST">
            @csrf
            <button type="submit" class="px-3 py-1.5 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 text-xs font-bold transition flex items-center gap-1.5 border border-rose-200">
                <span>تسجيل خروج</span>
            </button>
        </form>
    </div>

    <!-- Member Card Badge (Solid Light Card) -->
    <div class="solid-card rounded-2xl p-4.5 relative overflow-hidden">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-[#F5F3EE] border border-[#E5E2DC] flex items-center justify-center p-2 text-[#303334] font-black text-lg flex-shrink-0">
                <img src="{{ asset('images/ddt-flask-icon.svg') }}" class="w-7 h-7 object-contain" alt="DDT Member"/>
            </div>
            <div>
                <h2 class="text-sm font-bold text-[#303334] flex items-center gap-2">
                    <span>{{ $customer->full_name }}</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#EBF4E8] text-[#4E8F35]">عضو DDT</span>
                </h2>
                <p class="text-[11px] text-[#73777A] mt-0.5 font-mono">
                    {{ $customer->phone }} • كود الإحالة: <strong class="text-[#4E8F35] font-bold">{{ $customer->referral_code }}</strong>
                </p>
            </div>
        </div>

        <div class="mt-3 pt-3 border-t border-[#E5E2DC] flex items-center justify-between text-[11px]">
            <span class="text-[#73777A]">مستوى الولاء:</span>
            <span class="text-[#4E8F35] font-bold flex items-center gap-1">
                <span>{{ $customer->loyalty_status['current_visits'] }} من 5 زيارات</span>
                <span>●</span>
            </span>
        </div>
    </div>

    <!-- Account Details Form (Solid Light Card) -->
    <div class="solid-card rounded-2xl p-5">
        <h2 class="text-xs font-bold text-[#303334] mb-3">تعديل بيانات الحساب</h2>

        @if (isset($errors) && $errors->any())
            <div class="mb-4 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs">
                @foreach ($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('portal.profile.update') }}" method="POST" class="space-y-3.5">
            @csrf

            <div>
                <label class="block text-[11px] font-bold text-[#303334] mb-1">الاسم بالكامل</label>
                <input type="text" name="full_name" value="{{ old('full_name', $customer->full_name) }}" required 
                       class="w-full bg-[#F5F3EE] border border-[#E5E2DC] rounded-xl px-3.5 py-2 text-xs text-[#303334] focus:outline-none focus:border-[#4E8F35]">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-[#73777A] mb-1">رقم الهاتف (مسجل وغير قابل للتعديل)</label>
                <input type="text" value="{{ $customer->phone }}" disabled dir="ltr" 
                       class="w-full bg-[#F5F3EE]/60 border border-[#E5E2DC] rounded-xl px-3.5 py-2 text-xs text-[#73777A] font-mono text-left cursor-not-allowed">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-[#303334] mb-1">البريد الإلكتروني</label>
                <input type="email" name="email" value="{{ old('email', $customer->email) }}" dir="ltr" 
                       class="w-full bg-[#F5F3EE] border border-[#E5E2DC] rounded-xl px-3.5 py-2 text-xs text-[#303334] focus:outline-none focus:border-[#4E8F35] text-left font-mono">
            </div>

            <div class="pt-2 border-t border-[#E5E2DC]">
                <label class="block text-[11px] font-bold text-[#303334] mb-1">تغيير كلمة المرور (اتركه فارغاً إن لم ترغب في التغيير)</label>
                <input type="password" name="password" placeholder="كلمة مرور جديدة" 
                       class="w-full bg-[#F5F3EE] border border-[#E5E2DC] rounded-xl px-3.5 py-2 text-xs text-[#303334] focus:outline-none focus:border-[#4E8F35] mb-2">
                <input type="password" name="password_confirmation" placeholder="تأكيد كلمة المرور الجديدة" 
                       class="w-full bg-[#F5F3EE] border border-[#E5E2DC] rounded-xl px-3.5 py-2 text-xs text-[#303334] focus:outline-none focus:border-[#4E8F35]">
            </div>

            <button type="submit" class="w-full mt-2 py-2.5 px-4 rounded-xl bg-[#4E8F35] hover:bg-[#3F742B] text-white font-bold text-xs shadow-sm transition active:scale-98">
                حفظ التعديلات
            </button>
        </form>
    </div>

    <!-- Install PWA App Button Banner -->
    <div class="solid-card rounded-2xl p-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-[#F5F3EE] border border-[#E5E2DC] text-[#4E8F35] flex items-center justify-center p-2 flex-shrink-0">
                <img src="{{ asset('images/ddt-flask-icon.svg') }}" class="w-full h-full object-contain" alt="DDT"/>
            </div>
            <div>
                <h2 class="text-xs font-bold text-[#303334]">تطبيق DDT على شاشتك الرئيسية</h2>
                <p class="text-[10px] text-[#73777A]">يمكنك إضافة التطبيق لهاتفك لفتح المنيو والفعاليات بسرعة وبدون متصفح.</p>
            </div>
        </div>
    </div>

@endsection
