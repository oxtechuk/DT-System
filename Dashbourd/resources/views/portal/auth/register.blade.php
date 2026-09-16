@extends('portal.layout')

@section('title', 'إنشاء حساب جديد — DDT WORKING SPACE')

@section('content')
<div class="flex-1 flex flex-col justify-center py-4">

    <!-- Brand Header -->
    <div class="text-center mb-5">
        <div class="inline-flex items-center justify-center p-2 rounded-2xl bg-white border border-[#E5E2DC] shadow-sm mx-auto mb-3">
            <img src="{{ asset('images/ddt-logo.svg') }}" alt="DDT Working Space" class="h-10 object-contain"/>
        </div>
        <h1 class="text-xl font-black text-[#303334] tracking-tight">انضم لمجتمع DDT</h1>
        <p class="text-xs text-[#73777A] mt-1">
            <span class="text-[#4E8F35] font-bold">أكثر من مكان.. مجتمع بيكبر معاك</span> • بطاقة الولاء والطلبات المباشرة
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

    <!-- Registration Form Card (Solid White) -->
    <div class="solid-card rounded-2xl p-5 shadow-sm">
        <form action="{{ route('portal.register.submit') }}" method="POST" class="space-y-3">
            @csrf

            <div>
                <label class="block text-xs font-bold text-[#303334] mb-1">الاسم بالكامل *</label>
                <input type="text" name="full_name" value="{{ old('full_name') }}" required placeholder="مثال: يوسف أحمد"
                       class="w-full bg-[#F5F3EE] border border-[#E5E2DC] rounded-xl px-3.5 py-2 text-xs text-[#303334] placeholder-[#73777A] focus:outline-none focus:border-[#4E8F35] transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-[#303334] mb-1">رقم الهاتف (لتسجيل الدخول) *</label>
                <input type="tel" name="phone" value="{{ old('phone') }}" required dir="ltr" placeholder="01xxxxxxxxx"
                       class="w-full bg-[#F5F3EE] border border-[#E5E2DC] rounded-xl px-3.5 py-2 text-xs text-[#303334] placeholder-[#73777A] focus:outline-none focus:border-[#4E8F35] transition font-mono text-left">
            </div>

            <div>
                <label class="block text-xs font-bold text-[#303334] mb-1">البريد الإلكتروني (اختياري)</label>
                <input type="email" name="email" value="{{ old('email') }}" dir="ltr" placeholder="member@example.com"
                       class="w-full bg-[#F5F3EE] border border-[#E5E2DC] rounded-xl px-3.5 py-2 text-xs text-[#303334] placeholder-[#73777A] focus:outline-none focus:border-[#4E8F35] transition text-left font-mono">
            </div>

            <div class="grid grid-cols-2 gap-2.5">
                <div>
                    <label class="block text-xs font-bold text-[#303334] mb-1">كلمة المرور *</label>
                    <input type="password" name="password" required placeholder="••••••••"
                           class="w-full bg-[#F5F3EE] border border-[#E5E2DC] rounded-xl px-3.5 py-2 text-xs text-[#303334] placeholder-[#73777A] focus:outline-none focus:border-[#4E8F35] transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#303334] mb-1">تأكيد المرور *</label>
                    <input type="password" name="password_confirmation" required placeholder="••••••••"
                           class="w-full bg-[#F5F3EE] border border-[#E5E2DC] rounded-xl px-3.5 py-2 text-xs text-[#303334] placeholder-[#73777A] focus:outline-none focus:border-[#4E8F35] transition">
                </div>
            </div>

            <!-- Referral Code Field -->
            <div class="pt-1">
                <label class="block text-xs font-bold text-[#303334] mb-1 flex items-center justify-between">
                    <span>كود الإحالة / دعوة صديق (اختياري)</span>
                    <span class="text-[10px] text-[#4E8F35] font-bold">خصم فوري للأعضاء الجدد</span>
                </label>
                <div class="relative">
                    <input type="text" name="referral_code" value="{{ old('referral_code', $initialReferralCode) }}" placeholder="مثال: DDT100"
                           class="w-full bg-[#F5F3EE] border {{ !empty($initialReferralCode) ? 'border-[#4E8F35] bg-[#EBF4E8]' : 'border-[#E5E2DC]' }} rounded-xl px-3.5 py-2 text-xs text-[#303334] placeholder-[#73777A] focus:outline-none focus:border-[#4E8F35] transition uppercase font-mono tracking-wider">
                </div>
                @if(!empty($initialReferralCode))
                    <p class="text-[11px] text-[#4E8F35] font-bold mt-1 flex items-center gap-1">
                        <span>✓ تم تطبيق كود دعوة صديقك تلقائياً</span>
                    </p>
                @endif
            </div>

            <button type="submit" class="w-full mt-2 py-3 px-4 rounded-xl bg-[#4E8F35] hover:bg-[#3F742B] font-bold text-white text-xs shadow-sm transition active:scale-98 flex items-center justify-center gap-2">
                <span>تأكيد التسجيل والانضمام للمجتمع</span>
                <svg class="w-4 h-4 rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>
    </div>

    <div class="mt-4 text-center">
        <p class="text-xs text-[#73777A]">
            لديك حساب بالفعل؟
            <a href="{{ route('portal.login') }}" class="font-bold text-[#4E8F35] hover:underline">
                تسجيل الدخول
            </a>
        </p>
    </div>
</div>
@endsection
