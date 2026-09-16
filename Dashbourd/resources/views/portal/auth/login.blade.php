@extends('portal.layout')

@section('title', 'تسجيل الدخول — DDT WORKING SPACE')

@section('content')
<div class="flex-1 flex flex-col justify-center py-6">

    <!-- Brand Header -->
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center p-2 rounded-2xl bg-white border border-[#E5E2DC] shadow-sm mx-auto mb-3">
            <img src="{{ asset('images/ddt-logo.svg') }}" alt="DDT Working Space" class="h-10 object-contain"/>
        </div>
        <h1 class="text-xl font-black text-[#303334] tracking-tight">تسجيل الدخول لبوابة الأعضاء</h1>
        <p class="text-xs text-[#73777A] mt-1">
            <span class="text-[#4E8F35] font-bold">مستقل.. مش لوحدك</span> • اطلب مشروباتك وتابع رصيد ولائك
        </p>
    </div>

    <!-- Error Alert -->
    @if ($errors->any())
        <div class="mb-4 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs shadow-sm">
            @foreach ($errors->all() as $error)
                <p class="flex items-center gap-1.5">• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- Login Form Card (Solid White) -->
    <div class="solid-card rounded-2xl p-6 shadow-sm">
        <form action="{{ route('portal.login.submit') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-[#303334] mb-1.5">رقم الهاتف (المسجل لدى الكاشير)</label>
                <div class="relative">
                    <input type="tel" name="phone" id="portal_phone" value="{{ old('phone') }}" required dir="ltr" placeholder="01xxxxxxxxx" 
                           class="w-full bg-[#F5F3EE] border border-[#E5E2DC] rounded-xl px-4 py-2.5 text-xs text-[#303334] placeholder-[#73777A] focus:outline-none focus:border-[#4E8F35] transition font-mono text-left">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#303334] mb-1.5">كلمة المرور</label>
                <div class="relative">
                    <input type="password" name="password" id="portal_password" required placeholder="••••••••" 
                           class="w-full bg-[#F5F3EE] border border-[#E5E2DC] rounded-xl px-4 py-2.5 text-xs text-[#303334] placeholder-[#73777A] focus:outline-none focus:border-[#4E8F35] transition">
                </div>
            </div>

            <div class="flex items-center justify-between pt-1">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" value="1" checked class="w-4 h-4 rounded text-[#4E8F35] focus:ring-[#4E8F35] border-[#E5E2DC]">
                    <span class="text-xs text-[#73777A] font-medium">تذكرني على هذا الجهاز</span>
                </label>
            </div>

            <button type="submit" class="w-full mt-2 py-3 px-4 rounded-xl bg-[#4E8F35] hover:bg-[#3F742B] font-bold text-white text-xs shadow-sm transition active:scale-98 flex items-center justify-center gap-2">
                <span>دخول الحساب</span>
                <svg class="w-4 h-4 rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>

        <!-- Demo Accounts Quick Fill -->
        <div class="mt-5 pt-4 border-t border-[#E5E2DC]">
            <div class="text-[10px] text-[#73777A] font-bold uppercase tracking-wider mb-2 text-center">حسابات تجريبية سريعة:</div>
            <div class="grid grid-cols-2 gap-2">
                <button type="button" onclick="fillPortal('01012345678', 'password')" class="p-2 rounded-xl bg-[#F5F3EE] border border-[#E5E2DC] hover:border-[#4E8F35] text-right text-xs transition">
                    <div class="font-bold text-[#303334] text-[11px]">محمد أحمد</div>
                    <div class="text-[10px] text-[#4E8F35] font-mono">01012345678</div>
                </button>
                <button type="button" onclick="fillPortal('01011112222', 'password')" class="p-2 rounded-xl bg-[#F5F3EE] border border-[#E5E2DC] hover:border-[#4E8F35] text-right text-xs transition">
                    <div class="font-bold text-[#303334] text-[11px]">كريم فتحي</div>
                    <div class="text-[10px] text-[#4E8F35] font-mono">01011112222</div>
                </button>
            </div>
        </div>
    </div>

    <!-- Registration Link & Referral Banner -->
    <div class="mt-5 text-center">
        <p class="text-xs text-[#73777A]">
            ليس لديك حساب بعد؟
            <a href="{{ route('portal.register') }}" class="font-bold text-[#4E8F35] hover:underline">
                انضم لمجتمع DDT وسجل الآن
            </a>
        </p>

        <div class="mt-3 p-3 rounded-2xl bg-[#EBF4E8] border border-[#DCE8D4] text-[#4E8F35] text-xs flex items-center justify-center gap-2">
            <img src="{{ asset('images/ddt-flask-icon.svg') }}" class="w-4 h-4" alt="DDT Flask"/>
            <span>سجل واستفد من بطاقة ختم الولاء ومكافآت التوصية الفورية</span>
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
