<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
 /**
 * Show the unified login form.
 */
 public function showLogin()
 {
 // If staff user is logged in
 if (Auth::guard('web')->check()) {
 $user = Auth::guard('web')->user();
 if ($user->relationLoaded('roles') || method_exists($user, 'roles')) {
 $roles = $user->roles()->pluck('slug')->toArray();
 if (in_array('cashier', $roles) || in_array('reception', $roles)) {
 return redirect()->route('cashier');
 }
 }
 return redirect()->route('root');
 }

 // If customer is logged in
 if (Auth::guard('customer')->check()) {
 return redirect()->route('portal.home');
 }

 return view('auth.login');
 }

 /**
 * Handle unified authentication attempt.
 * Automatically routes Staff/Admin to Dashboard, Cashier to POS, and Customer to Portal.
 */
 public function login(Request $request)
 {
 $request->validate([
 'login' => ['sometimes', 'string'],
 'email' => ['sometimes', 'string'],
 'password' => ['required', 'string'],
 ], [
 'password.required' => 'يرجى إدخال كلمة المرور',
 ]);

 $loginInput = trim($request->input('login') ?: $request->input('email'));
 $password = $request->input('password');
 $remember = $request->boolean('remember');

 if (empty($loginInput)) {
 return back()->withErrors(['login' => 'يرجى إدخال البريد الإلكتروني أو رقم الهاتف'])->onlyInput('login');
 }

 // ── 1. Check Staff / Admin / Cashier in `users` table ──
 $staffUser = User::where('email', $loginInput)->first();

 if ($staffUser && Hash::check($password, $staffUser->password)) {
 Auth::guard('web')->login($staffUser, $remember);
 $request->session()->regenerate();

 // Check roles for redirection
 $roles = $staffUser->roles()->pluck('slug')->toArray();
 if (in_array('cashier', $roles) || in_array('reception', $roles)) {
 return redirect()->intended(route('cashier'));
 }

 return redirect()->intended(route('root'));
 }

 // ── 2. Check Customer in `customers` table (by phone or email) ──
 $customer = Customer::where('phone', $loginInput)
 ->orWhere('email', $loginInput)
 ->first();

 if ($customer && !empty($customer->password) && Hash::check($password, $customer->password)) {
 if ($customer->status === 'blocked') {
 return back()->withErrors([
 'login' => 'الحساب معطل حالياً، يرجى مراجعة إدارة مساحة العمل.'
 ])->onlyInput('login');
 }

 Auth::guard('customer')->login($customer, $remember);
 $request->session()->regenerate();

 return redirect()->intended(route('portal.home'));
 }

 return back()->withErrors([
 'login' => 'بيانات الدخول غير صحيحة، يرجى التأكد من البريد أو رقم الهاتف وكلمة المرور.',
 ])->onlyInput('login');
 }

 /**
 * Log out all guards.
 */
 public function logout(Request $request)
 {
 if (Auth::guard('web')->check()) {
 Auth::guard('web')->logout();
 }

 if (Auth::guard('customer')->check()) {
 Auth::guard('customer')->logout();
 }

 $request->session()->invalidate();
 $request->session()->regenerateToken();

 return redirect()->route('login');
 }
}
