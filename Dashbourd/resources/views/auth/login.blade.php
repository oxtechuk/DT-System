@extends('shared.base', ['title' => 'Login — تسجيل الدخول'])

@section('body_attribute')
 class="bg-default-900 min-h-screen w-screen flex justify-center items-center p-4" style="background: radial-gradient(circle at 10% 20%, rgba(99, 102, 241, 0.2) 0%, transparent 40%), radial-gradient(circle at 90% 80%, rgba(6, 182, 212, 0.15) 0%, transparent 40%), #0f172a;"
@endsection

@section('content')
 <div class="2xl:w-1/4 lg:w-1/3 md:w-1/2 w-full max-w-md">
 <div class="card overflow-hidden rounded-2xl border border-default-100/10 shadow-2xl bg-white/95 backdrop-blur-md">
 <div class="px-8 py-10">
 <a class="flex flex-col items-center justify-center mb-6" href="{{ url('/') }}">
 @if(!empty($settingsLogo))
 <img alt="logo" class="h-10 mb-2" src="{{ asset('storage/' . $settingsLogo) }}"/>
 @else
 <img alt="logo" class="h-8 mb-2" src="{{ asset('images/logo-dark.png') }}"/>
 @endif
 <span class="text-xs font-bold tracking-widest text-primary uppercase">DT-System Workspace</span>
 </a>

 <div class="text-center mb-6">
 <h4 class="text-xl font-bold text-default-900">تسجيل الدخول للنظام</h4>
 <p class="text-xs text-default-500 mt-1">أدخل بيانات حسابك للمتابعة إلى لوحة التحكم</p>
 </div>

 {{-- Validation Errors --}}
 @if ($errors->any())
 <div class="p-3 mb-5 rounded-lg bg-danger/10 border border-danger/20 text-danger text-xs text-center font-medium">
 {{ $errors->first() }}
 </div>
 @endif

 <form method="POST" action="{{ route('login.submit') }}">
 @csrf

 <div class="mb-4">
 <label class="block text-xs font-semibold text-default-700 mb-2" for="LoggingEmailAddress">
 البريد الإلكتروني / Email
 </label>
 <input class="form-input w-full rounded-lg border-default-200 focus:border-primary focus:ring-primary text-sm" 
 id="LoggingEmailAddress" 
 name="email" 
 type="email" 
 placeholder="admin@dt-system.com" 
 value="{{ old('email', 'admin@dt-system.com') }}" 
 required 
 autofocus/>
 </div>

 <div class="mb-4">
 <div class="flex items-center justify-between mb-2">
 <label class="text-xs font-semibold text-default-700" for="loggingPassword">
 كلمة المرور / Password
 </label>
 </div>
 <input class="form-input w-full rounded-lg border-default-200 focus:border-primary focus:ring-primary text-sm" 
 id="loggingPassword" 
 name="password" 
 type="password" 
 placeholder="••••••••" 
 value="password"
 required/>
 </div>

 <div class="flex items-center justify-between mb-6">
 <label class="flex items-center gap-2 cursor-pointer text-xs text-default-600">
 <input class="form-checkbox rounded text-primary focus:ring-primary" 
 name="remember" 
 type="checkbox" 
 checked/>
 <span>تذكرني على هذا الجهاز</span>
 </label>
 </div>

 <div class="mb-4">
 <button type="submit" class="btn w-full text-white bg-primary hover:bg-primary/90 py-2.5 rounded-lg font-semibold shadow-lg shadow-primary/30 transition-all">
 دخول النظام / Sign In
 </button>
 </div>
 </form>

 {{-- Demo Credentials Box --}}
 <div class="mt-6 p-3.5 rounded-xl bg-default-50 border border-default-200/60 text-xs">
 <p class="font-bold text-default-700 mb-1 flex items-center gap-1.5">
 <span class="size-2 rounded-full bg-emerald-500 inline-block"></span>
 بيانات الدخول الافتراضية (Admin):
 </p>
 <div class="text-default-500 font-mono text-[11px] space-y-0.5 mt-1.5">
 <div>Email: <strong class="text-default-800">admin@dt-system.com</strong></div>
 <div>Password: <strong class="text-default-800">password</strong></div>
 </div>
 </div>

 </div>
 </div>

 <div class="text-center mt-6">
 <a href="{{ url('/cashier') }}" target="_blank" class="inline-flex items-center gap-2 text-xs text-default-400 hover:text-white transition-colors bg-white/5 px-3.5 py-1.5 rounded-full border border-white/10">
 <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/></svg>
 <span>الدخول المباشر لشاشة الكاشير السريع (POS)</span>
 </a>
 </div>
 </div>
@endsection
