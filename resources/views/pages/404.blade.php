@extends('shared.base', ['title' => '404 — الصفحة غير موجودة'])

@section('body_attribute')
    class="bg-[#F8F7F4] min-h-screen flex items-center justify-center p-4 font-sans text-right" dir="rtl"
@endsection

@section('content')
    <div class="max-w-md w-full bg-white rounded-3xl border border-[#E5E2DC] p-8 text-center shadow-lg">
        {{-- DDT Logo --}}
        <a href="{{ url('/') }}" class="inline-flex justify-center mb-6">
            <img alt="DDT Working Space" class="h-9 object-contain" src="{{ asset('images/ddt-logo.svg') }}"/>
        </a>

        {{-- 404 Visual Indicator --}}
        <div class="my-6">
            <div class="inline-flex items-center justify-center size-20 rounded-2xl bg-rose-50 text-rose-500 border border-rose-100 mb-2">
                <svg width="36" height="36" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
            </div>
            <h1 class="text-5xl font-black text-[#303334] tracking-tight">404</h1>
        </div>

        <h2 class="text-lg font-black text-[#303334] mb-2">
            الصفحة المطلوبة غير موجودة
        </h2>
        <p class="text-xs text-[#73777A] leading-relaxed mb-8">
            عذراً، الرابط الذي تحاول الوصول إليه غير متاح أو تم نقله. يرجى التحقق من الرابط أو العودة إلى لوحة التحكم الرئيسية.
        </p>

        {{-- Navigation Actions --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ url('/') }}"
               class="w-full sm:w-auto px-6 py-3 bg-[#4E8F35] hover:bg-[#3F742B] text-white rounded-xl text-xs font-bold transition-all shadow-xs flex items-center justify-center gap-2">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/>
                </svg>
                <span>العودة للوحة التحكم</span>
            </a>

            <button type="button" onclick="history.back()"
                    class="w-full sm:w-auto px-5 py-3 bg-[#F5F3EE] hover:bg-[#E5E2DC] text-[#303334] rounded-xl text-xs font-bold transition">
                الصفحة السابقة
            </button>
        </div>
    </div>
@endsection
