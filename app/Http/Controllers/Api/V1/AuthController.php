<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Unified mobile login for Staff (Admin/Cashier) and Customers.
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'login' => ['required_without:email', 'string'],
            'email' => ['required_without:login', 'string'],
            'password' => ['required', 'string'],
            'device' => ['nullable', 'string', 'max:100'],
        ], [
            'login.required_without' => 'يرجى إدخال البريد الإلكتروني أو رقم الهاتف',
            'email.required_without' => 'يرجى إدخال البريد الإلكتروني أو رقم الهاتف',
            'password.required' => 'يرجى إدخال كلمة المرور',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $loginInput = trim($request->input('login') ?: $request->input('email'));
        $password = $request->input('password');
        $deviceName = $request->input('device', 'mobile-app');

        // 1. Check Staff / Admin / Cashier in `users`
        $staffUser = User::with('role')->where('email', $loginInput)->orWhere('phone', $loginInput)->first();

        if ($staffUser && Hash::check($password, $staffUser->password)) {
            if ($staffUser->status !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'الحساب معطل حالياً، يرجى مراجعة إدارة النظام.',
                ], 403);
            }

            $token = $staffUser->createToken($deviceName)->plainTextToken;
            $roles = $staffUser->roles()->pluck('slug')->toArray();
            if ($staffUser->role && ! in_array($staffUser->role->name, $roles)) {
                $roles[] = $staffUser->role->name;
            }

            return response()->json([
                'success' => true,
                'message' => 'تم تسجيل الدخول بنجاح',
                'data' => [
                    'account_type' => 'staff',
                    'token' => $token,
                    'user' => [
                        'id' => $staffUser->id,
                        'name' => $staffUser->name,
                        'email' => $staffUser->email,
                        'phone' => $staffUser->phone,
                        'avatar' => $staffUser->avatar,
                        'initials' => $staffUser->initials(),
                        'role' => $staffUser->role?->name ?? 'Staff',
                        'roles' => $roles,
                    ],
                ],
            ]);
        }

        // 2. Check Customer in `customers`
        $customer = Customer::where('phone', $loginInput)->orWhere('email', $loginInput)->first();

        if ($customer && ! empty($customer->password) && Hash::check($password, $customer->password)) {
            if ($customer->status === 'blocked') {
                return response()->json([
                    'success' => false,
                    'message' => 'حساب العميل معطل حالياً.',
                ], 403);
            }

            $customer->update(['last_active_at' => now()]);
            $token = $customer->createToken($deviceName)->plainTextToken;

            $isTeacher = $customer->isTeacher();
            $teacher = $isTeacher ? $customer->teacher() : null;

            return response()->json([
                'success' => true,
                'message' => 'تم تسجيل الدخول بنجاح',
                'data' => [
                    'account_type' => 'customer',
                    'token' => $token,
                    'customer' => [
                        'id' => $customer->id,
                        'name' => $customer->name,
                        'phone' => $customer->phone,
                        'email' => $customer->email,
                        'referral_code' => $customer->referral_code,
                        'classification' => $customer->classification_label,
                        'is_teacher' => $isTeacher,
                        'teacher_code' => $teacher?->code,
                        'loyalty' => $customer->loyalty_status,
                    ],
                ],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'بيانات الدخول غير صحيحة، يرجى التأكد من البريد أو رقم الهاتف وكلمة المرور.',
        ], 401);
    }

    /**
     * Mobile Customer Registration.
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:20', 'unique:customers,phone'],
            'email' => ['nullable', 'email', 'max:150', 'unique:customers,email'],
            'password' => ['required', 'string', 'min:6'],
            'referral_code' => ['nullable', 'string', 'max:32'],
            'device' => ['nullable', 'string', 'max:100'],
        ], [
            'name.required' => 'يرجى إدخال الاسم بالكامل',
            'phone.required' => 'يرجى إدخال رقم الهاتف',
            'phone.unique' => 'رقم الهاتف مسجل مسبقاً',
            'email.unique' => 'البريد الإلكتروني مسجل مسبقاً',
            'password.required' => 'يرجى إدخال كلمة المرور',
            'password.min' => 'كلمة المرور يجب ألا تقل عن 6 أحرف',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $referredById = null;
        if (! empty($request->referral_code)) {
            $referrer = Customer::where('referral_code', trim($request->referral_code))->first();
            if ($referrer) {
                $referredById = $referrer->id;
            }
        }

        $myReferralCode = Customer::generateUniqueReferralCode($request->name);

        $customer = Customer::create([
            'full_name' => trim($request->name),
            'phone' => trim($request->phone),
            'email' => $request->email ? trim($request->email) : null,
            'password' => $request->password,
            'referral_code' => $myReferralCode,
            'referred_by_id' => $referredById,
            'customer_type' => 'registered',
            'source' => $referredById ? 'referral' : 'mobile_app',
            'status' => 'active',
            'last_active_at' => now(),
        ]);

        $token = $customer->createToken($request->input('device', 'mobile-app'))->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء الحساب بنجاح، مرحباً بك في مساحة عمل DDT!',
            'data' => [
                'account_type' => 'customer',
                'token' => $token,
                'customer' => [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                    'email' => $customer->email,
                    'referral_code' => $customer->referral_code,
                    'classification' => $customer->classification_label,
                    'is_teacher' => false,
                    'loyalty' => $customer->loyalty_status,
                ],
            ],
        ], 201);
    }

    /**
     * Get authenticated user profile.
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user instanceof User) {
            $roles = $user->roles()->pluck('slug')->toArray();

            return response()->json([
                'success' => true,
                'data' => [
                    'account_type' => 'staff',
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'avatar' => $user->avatar,
                        'initials' => $user->initials(),
                        'role' => $user->role?->name ?? 'Staff',
                        'roles' => $roles,
                    ],
                ],
            ]);
        }

        if ($user instanceof Customer) {
            $isTeacher = $user->isTeacher();
            $teacher = $isTeacher ? $user->teacher() : null;

            return response()->json([
                'success' => true,
                'data' => [
                    'account_type' => 'customer',
                    'customer' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'phone' => $user->phone,
                        'email' => $user->email,
                        'referral_code' => $user->referral_code,
                        'classification' => $user->classification_label,
                        'is_teacher' => $isTeacher,
                        'teacher_code' => $teacher?->code,
                        'loyalty' => $user->loyalty_status,
                    ],
                ],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'غير مصرح',
        ], 401);
    }

    /**
     * Revoke current access token.
     */
    public function logout(Request $request): JsonResponse
    {
        if ($request->user() && method_exists($request->user(), 'currentAccessToken')) {
            $request->user()->currentAccessToken()?->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الخروج بنجاح',
        ]);
    }
}
