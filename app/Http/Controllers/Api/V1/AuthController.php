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
