@extends('portal.layout')

@section('title', 'تسجيل الدخول — DDT WORKING SPACE')

@section('content')
<div class="flex-1 flex flex-col justify-center py-6">

    <!-- Brand Header -->
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-3xl bg-[#4E8F35] border-2 border-white/20 shadow-lg mx-auto mb-3">
            <span class="text-2xl font-black tracking-widest text-[#EBF4E8]">DDT</span>
        </div>
        <h1 class="text-xl font-black text-[#303334] tracking-tight">تسجيل الدخول لبوابة الأعضاء</h1>
        <p class="text-xs text-[#738276] mt-1 font-medium">
            <span class="text-[#4E8F35] font-bold">مستقل.. مش لوحدك</span> • اطلب مشروباتك وتابع مجتمعك
        </p>
    </div>

    <!-- Error Alert -->
    @if ($errors->any())
        <div class="mb-4 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs shadow-xs">
            @foreach ($errors->all() as $error)
                <p class="flex items-center gap-1.5">• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- Login Form Card -->
    <div class="solid-card rounded-[28px] p-6 shadow-sm border border-[#E5E2DC]">
        <form action="{{ route('portal.login.submit') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-[#303334] mb-1.5">رقم الهاتف (المسجل في DDT)</label>
                <input type="tel" name="phone" id="portal_phone" value="{{ old('phone') }}" required dir="ltr" placeholder="01xxxxxxxxx" 
                       class="w-full bg-[#F5F3EE] border border-[#E5E2DC] rounded-full px-4 py-2.5 text-xs text-[#303334] placeholder-[#738276] focus:outline-none focus:border-[#4E8F35] font-mono text-left">
            </div>

            <div>
                <label class="block text-xs font-bold text-[#303334] mb-1.5">كلمة المرور</label>
                <input type="password" name="password" id="portal_password" required placeholder="••••••••" 
                       class="w-full bg-[#F5F3EE] border border-[#E5E2DC] rounded-full px-4 py-2.5 text-xs text-[#303334] placeholder-[#738276] focus:outline-none focus:border-[#4E8F35]">
            </div>

            <div class="flex items-center justify-between pt-1">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" value="1" checked class="w-4 h-4 rounded text-[#4E8F35] focus:ring-[#4E8F35] border-[#E5E2DC]">
                    <span class="text-xs text-[#303334] font-bold">تذكرني لمدة 15 يوماً على هذا الجهاز</span>
                </label>
            </div>

            <button type="submit" class="w-full mt-2 py-3.5 px-4 pill-btn-forest text-xs flex items-center justify-center gap-2 shadow-sm font-black">
                <span>دخول الحساب</span>
                <svg class="w-4 h-4 rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>

        <!-- Demo Accounts Quick Fill -->
        <div class="mt-5 pt-4 border-t border-[#E5E2DC]">
            <div class="text-[10px] text-[#738276] font-bold uppercase tracking-wider mb-2 text-center">حسابات تجريبية سريعة:</div>
            <div class="grid grid-cols-2 gap-2">
                <button type="button" onclick="fillPortal('01012345678', 'password')" class="p-2.5 rounded-2xl bg-[#F5F3EE] border border-[#E5E2DC] hover:border-[#4E8F35] text-right text-xs transition active:scale-95">
                    <div class="font-bold text-[#303334] text-[11px]">محمد أحمد</div>
                    <div class="text-[10px] text-[#4E8F35] font-mono font-bold">01012345678</div>
                </button>
                <button type="button" onclick="fillPortal('01011112222', 'password')" class="p-2.5 rounded-2xl bg-[#F5F3EE] border border-[#E5E2DC] hover:border-[#4E8F35] text-right text-xs transition active:scale-95">
                    <div class="font-bold text-[#303334] text-[11px]">كريم فتحي</div>
                    <div class="text-[10px] text-[#4E8F35] font-mono font-bold">01011112222</div>
                </button>
            </div>
        </div>
    </div>

    <!-- Registration Link -->
    <div class="mt-5 text-center">
        <p class="text-xs text-[#738276]">
            ليس لديك حساب بعد؟
            <a href="{{ route('portal.register') }}" class="font-bold text-[#4E8F35] hover:underline">
                انضم لمجتمع DDT وسجل الآن
            </a>
        </p>

        <div class="mt-3 p-3.5 rounded-2xl bg-[#EBF4E8] border border-[#DCE8D4] text-[#4E8F35] text-xs flex items-center justify-center gap-2">
            <svg class="w-4 h-4 text-[#4E8F35]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
            </svg>
            <span class="font-bold">سجل واستفد من بطاقة ختم الولاء ومكافآت التوصية الفورية</span>
        </div>
    </div>
</div>

<script>
    function fillPortal(phone, pass) {
        document.getElementById('portal_phone').value = phone;
        document.getElementById('portal_password').value = pass;
    }
</script>
@endsection
