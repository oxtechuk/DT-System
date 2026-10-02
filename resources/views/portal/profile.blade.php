@extends('portal.layout')

@section('title', 'الملف الشخصي — DDT WORKING SPACE')

@section('content')

    <!-- Top Profile Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-sm font-extrabold text-[#303334]">الملف الشخصي للأعضاء</h1>
            <p class="text-[11px] text-[#738276]">إدارة حسابك وتفضيلاتك في مجتمع DDT</p>
        </div>
        <form action="{{ route('portal.logout') }}" method="POST">
            @csrf
            <button type="submit" class="pill-btn-outline px-3.5 py-1.5 text-xs text-rose-700 hover:bg-rose-50 border-rose-200">
                <span>تسجيل خروج</span>
            </button>
        </form>
    </div>

    <!-- Member Profile Card (Solid White Card with Avatar Badge) -->
    <div class="solid-card rounded-[26px] p-5 relative overflow-hidden">
        <div class="flex items-center gap-3.5">
            <div class="w-14 h-14 rounded-full bg-[#4E8F35] text-white flex items-center justify-center font-extrabold text-lg flex-shrink-0 shadow-sm">
                {{ $customer->initials }}
            </div>
            <div>
                <h2 class="text-sm font-extrabold text-[#303334] flex items-center gap-2">
                    <span>{{ $customer->full_name }}</span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#EBF4E8] text-[#4E8F35]">عضو نشط</span>
                </h2>
                <p class="text-[11px] text-[#738276] mt-0.5 font-mono">
                    {{ $customer->phone }} • كود الإحالة: <strong class="text-[#4E8F35] font-bold">{{ $customer->referral_code }}</strong>
                </p>
            </div>
        </div>

        <div class="mt-4 pt-3.5 border-t border-[#E5E2DC] flex items-center justify-between text-[11px]">
            <span class="text-[#738276]">حالة بطاقة الولاء:</span>
            <span class="text-[#4E8F35] font-bold flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-[#4E8F35]"></span>
                <span>{{ $customer->loyalty_status['current_visits'] }} من 5 زيارات مكتملة</span>
            </span>
        </div>
    </div>

    <!-- Account Details Form (Solid Light Card) -->
    <div class="solid-card rounded-[26px] p-5">
        <h2 class="text-xs font-bold text-[#303334] mb-3.5">تعديل بيانات الحساب</h2>

        @if (isset($errors) && $errors->any())
            <div class="mb-4 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs">
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
                       class="w-full bg-[#F5F3EE] border border-[#E5E2DC] rounded-full px-4 py-2.5 text-xs text-[#303334] focus:outline-none focus:border-[#4E8F35]">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-[#738276] mb-1">رقم الهاتف (مسجل وغير قابل للتعديل)</label>
                <input type="text" value="{{ $customer->phone }}" disabled dir="ltr" 
                       class="w-full bg-[#F5F3EE]/60 border border-[#E5E2DC] rounded-full px-4 py-2.5 text-xs text-[#738276] font-mono text-left cursor-not-allowed">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-[#303334] mb-1">البريد الإلكتروني</label>
                <input type="email" name="email" value="{{ old('email', $customer->email) }}" dir="ltr" 
                       class="w-full bg-[#F5F3EE] border border-[#E5E2DC] rounded-full px-4 py-2.5 text-xs text-[#303334] focus:outline-none focus:border-[#4E8F35] text-left font-mono">
            </div>

            <div class="pt-2 border-t border-[#E5E2DC]">
                <label class="block text-[11px] font-bold text-[#303334] mb-1">تغيير كلمة المرور (اتركه فارغاً إن لم ترغب في التغيير)</label>
                <input type="password" name="password" placeholder="كلمة مرور جديدة" 
                       class="w-full bg-[#F5F3EE] border border-[#E5E2DC] rounded-full px-4 py-2.5 text-xs text-[#303334] focus:outline-none focus:border-[#4E8F35] mb-2">
                <input type="password" name="password_confirmation" placeholder="تأكيد كلمة المرور الجديدة" 
                       class="w-full bg-[#F5F3EE] border border-[#E5E2DC] rounded-full px-4 py-2.5 text-xs text-[#303334] focus:outline-none focus:border-[#4E8F35]">
            </div>

            <button type="submit" class="w-full mt-2 py-3 px-4 pill-btn-forest text-xs font-bold shadow-sm">
                حفظ التعديلات
            </button>
        </form>
    </div>

    <!-- Install PWA Banner -->
    <div class="solid-card rounded-[24px] p-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-[#F5F3EE] text-[#4E8F35] flex items-center justify-center p-2.5 flex-shrink-0 font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-xs font-bold text-[#303334]">تطبيق DDT على هاتفك</h3>
                <p class="text-[10px] text-[#738276]">أضف التطبيق للشاشة الرئيسية لطلب المشروبات وتصفح المجتمع فوراً.</p>
            </div>
        </div>
    </div>

@endsection
