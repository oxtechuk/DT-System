<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PortalAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('portal.home');
        }

        return view('portal.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'nullable|string',
        ], [
            'phone.required' => 'يرجى إدخال رقم الهاتف',
        ]);

        $rawPhone = trim($request->phone);
        $cleanPhone = preg_replace('/[^\d]/', '', $rawPhone);
        $inputPassword = (string) ($request->password !== null && $request->password !== '' ? $request->password : '0000');

        // Look up customer by exact phone, email, or normalized digits
        $customer = Customer::where('phone', $rawPhone)
            ->orWhere('email', $rawPhone)
            ->when(! empty($cleanPhone), function ($q) use ($cleanPhone) {
                $q->orWhere('phone', $cleanPhone);
                if (strlen($cleanPhone) >= 9) {
                    $last9 = substr($cleanPhone, -9);
                    $q->orWhere('phone', 'like', "%{$last9}");
                }
            })
            ->first();

        // If not found in customers directly, check if a User exists with this email or phone
        if (! $customer) {
            $matchedUser = User::where('email', $rawPhone)
                ->orWhere('phone', $rawPhone)
                ->when(! empty($cleanPhone) && strlen($cleanPhone) >= 9, function ($q) use ($cleanPhone) {
                    $q->orWhere('phone', 'like', '%'.substr($cleanPhone, -9));
                })
                ->first();

            if ($matchedUser) {
                $customer = Customer::where('email', $matchedUser->email)
                    ->orWhere('phone', $matchedUser->phone)
                    ->first();
            }
        }

        if ($customer) {
            if ($customer->status === 'blocked') {
                throw ValidationException::withMessages([
                    'phone' => ['الحساب معطل حالياً. يرجى مراجعة إدارة مساحة العمل.'],
                ]);
            }

            // Check password: match hashed password, or match default 0000, or if customer has no password set
            $isValidPassword = false;

            if (empty($customer->password)) {
                // If customer has no password in DB, default password 0000 is valid
                $isValidPassword = true;
                $customer->update(['password' => $inputPassword ?: '0000']);
            } elseif ($inputPassword === '0000') {
                // Universal default 0000 master login
                $isValidPassword = true;
            } elseif (Hash::check($inputPassword, $customer->password)) {
                $isValidPassword = true;
            }

            if (! $isValidPassword) {
                throw ValidationException::withMessages([
                    'phone' => ['كلمة المرور غير صحيحة. كلمة المرور الافتراضية هي 0000'],
                ]);
            }
        } else {
            // Check if this phone belongs to a registered User / Teacher
            $matchedUser = User::with('teacher')->where('phone', $cleanPhone ?: $rawPhone)
                ->when(strlen((string) $cleanPhone) >= 9, function ($q) use ($cleanPhone) {
                    $q->orWhere('phone', 'like', '%'.substr((string) $cleanPhone, -9));
                })->first();

            if ($matchedUser && ! empty($inputPassword) && $inputPassword !== '0000') {
                if (! Hash::check($inputPassword, $matchedUser->password)) {
                    throw ValidationException::withMessages([
                        'phone' => ['كلمة المرور غير صحيحة. كلمة المرور الافتراضية هي 0000 أو كلمة مرور حسابك.'],
                    ]);
                }
            }

            $shortPhone = substr((string) ($cleanPhone ?: $rawPhone), -4);
            $customer = Customer::create([
                'full_name' => $matchedUser?->name ?: ('عضو DDT ('.$shortPhone.')'),
                'phone' => $cleanPhone ?: $rawPhone,
                'email' => $matchedUser?->email,
                'password' => $inputPassword ?: '0000',
                'customer_type' => 'registered',
                'classification' => ($matchedUser && $matchedUser->teacher) ? 'lecturer' : 'other',
                'source' => ($matchedUser && $matchedUser->teacher) ? 'teacher' : 'portal',
                'status' => 'active',
                'referral_code' => Customer::generateUniqueReferralCode('DDT_'.$shortPhone),
            ]);
        }

        // Login with 15-day persistent remember token
        Auth::guard('customer')->login($customer, true);
        $request->session()->regenerate();

        return redirect()->intended(route('portal.home'));
    }

    public function showRegister(Request $request)
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('portal.home');
        }

        $referralCode = $request->query('ref', '');

        return view('portal.auth.register', [
            'initialReferralCode' => $referralCode,
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:150',
            'phone' => 'required|string|max:20|unique:customers,phone',
            'email' => 'nullable|email|max:150|unique:customers,email',
            'password' => 'required|string|min:6|confirmed',
            'referral_code' => 'nullable|string|max:32',
        ], [
            'full_name.required' => 'يرجى إدخال الاسم بالكامل',
            'phone.required' => 'يرجى إدخال رقم الهاتف',
            'phone.unique' => 'رقم الهاتف مسجل مسبقاً',
            'email.unique' => 'البريد الإلكتروني مسجل مسبقاً',
            'password.required' => 'يرجى إدخال كلمة المرور',
            'password.min' => 'كلمة المرور يجب ألا تقل عن 6 أحرف',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
        ]);

        $referredById = null;
        if (! empty($request->referral_code)) {
            $referrer = Customer::where('referral_code', trim($request->referral_code))->first();
            if ($referrer) {
                $referredById = $referrer->id;
            }
        }

        $myReferralCode = Customer::generateUniqueReferralCode($request->full_name);

        $customer = Customer::create([
            'full_name' => trim($request->full_name),
            'phone' => trim($request->phone),
            'email' => $request->email ? trim($request->email) : null,
            'password' => $request->password,
            'referral_code' => $myReferralCode,
            'referred_by_id' => $referredById,
            'customer_type' => 'registered',
            'source' => $referredById ? 'referral' : 'portal',
            'status' => 'active',
        ]);

        Auth::guard('customer')->login($customer, true);

        return redirect()->route('portal.home')->with('success', 'مرحباً بك! تم إنشاء حسابك بنجاح.');
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('portal.login');
    }
}
