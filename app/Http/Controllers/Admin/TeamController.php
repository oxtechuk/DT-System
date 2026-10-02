<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class TeamController extends Controller
{
    // ── All permissions grouped for the UI ─────────────────────────────────
    private static function allPermissions(): array
    {
        return [
            'العمليات والكاشير' => [
                'dashboard.view' => 'عرض لوحة التحكم',
                'cashier.view' => 'دخول شاشة الكاشير (POS)',
                'deals.view' => 'عرض الجلسات النشطة',
                'deals.create' => 'فتح جلسة جديدة',
                'deals.close' => 'إغلاق الجلسة وتسديد',
                'bookings.view' => 'عرض الحجوزات',
                'bookings.create' => 'إنشاء حجز جديد',
                'bookings.edit' => 'تعديل وإلغاء الحجوزات',
                'rooms.view' => 'عرض الغرف والمساحات',
                'rooms.manage' => 'إدارة الغرف (إضافة/تعديل)',
            ],
            'العملاء' => [
                'customers.view' => 'عرض قائمة العملاء',
                'customers.create' => 'إضافة عميل جديد',
                'customers.edit' => 'تعديل بيانات العميل',
                'customers.notes' => 'تعديل ملاحظات العميل',
            ],
            'الكافيه والمخزون' => [
                'products.view' => 'عرض قائمة المنتجات',
                'products.manage' => 'إضافة وتعديل المنتجات',
                'inventory.view' => 'عرض حركة المخزون',
                'inventory.manage' => 'إدارة المخزون والمواد الخام',
            ],
            'المالية والورديات' => [
                'payments.view' => 'عرض سجل المدفوعات',
                'shifts.view' => 'عرض الوردية الحالية',
                'shifts.manage' => 'فتح وإغلاق الورديات',
            ],
            'التقارير' => [
                'reports.view' => 'عرض التقارير والتحليلات',
            ],
            'الإعدادات والفريق' => [
                'settings.view' => 'عرض الإعدادات العامة',
                'settings.manage' => 'تعديل الإعدادات',
                'team.view' => 'عرض قائمة الفريق',
                'team.manage' => 'إدارة الأعضاء والصلاحيات',
                'events.manage' => 'إدارة الفعاليات والورش',
                'hubspot.manage' => 'إدارة ربط HubSpot CRM',
            ],
        ];
    }

    // =========================================================================
    // Team Members
    // =========================================================================

    public function index()
    {
        $users = User::with('role')->latest()->get();
        $roles = Role::withCount('users')->get();

        return view('admin.team.index', [
            'users' => $users,
            'roles' => $roles,
            'allPermissions' => self::allPermissions(),
        ]);
    }

    public function storeUser(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'role_id' => 'nullable|exists:roles,id',
            'password' => ['required', Password::min(8)],
            'status' => 'in:active,inactive',
            'notes' => 'nullable|string|max:500',
        ], [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'password.required' => 'كلمة المرور مطلوبة',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'role_id' => $data['role_id'] ?? null,
            'password' => Hash::make($data['password']),
            'status' => $data['status'] ?? 'active',
            'notes' => $data['notes'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => "تم إضافة العضو {$user->name} بنجاح.",
            'user' => $this->formatUser($user->load('role')),
        ]);
    }

    public function updateUser(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => "required|email|unique:users,email,{$user->id}",
            'phone' => 'nullable|string|max:20',
            'role_id' => 'nullable|exists:roles,id',
            'status' => 'in:active,inactive',
            'notes' => 'nullable|string|max:500',
            'password' => ['nullable', Password::min(8)],
        ]);

        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'role_id' => $data['role_id'] ?? null,
            'status' => $data['status'] ?? 'active',
            'notes' => $data['notes'] ?? null,
        ];

        if (! empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);

        return response()->json([
            'success' => true,
            'message' => "تم تحديث بيانات {$user->name} بنجاح.",
            'user' => $this->formatUser($user->load('role')),
        ]);
    }

    public function destroyUser(User $user)
    {
        $name = $user->name;
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => "تم حذف العضو {$name}.",
        ]);
    }

    // =========================================================================
    // Roles & Permissions
    // =========================================================================

    public function storeRole(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:60',
            'slug' => 'required|string|max:60|unique:roles,slug',
            'description' => 'nullable|string|max:255',
            'permissions' => 'array',
            'permissions.*' => 'string',
        ], [
            'name.required' => 'اسم الدور مطلوب',
            'slug.required' => 'المعرّف الفريد مطلوب',
            'slug.unique' => 'هذا المعرّف مستخدم بالفعل',
        ]);

        $role = Role::create([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'description' => $data['description'] ?? null,
        ]);

        // Sync permissions (create if not exist)
        $this->syncPermissionsToRole($role, $data['permissions'] ?? []);

        return response()->json([
            'success' => true,
            'message' => "تم إنشاء الدور «{$role->name}» بنجاح.",
            'role' => $this->formatRole($role->load('permissions')),
        ]);
    }

    public function updateRole(Request $request, Role $role)
    {
        $data = $request->validate([
            'name' => 'required|string|max:60',
            'description' => 'nullable|string|max:255',
            'permissions' => 'array',
            'permissions.*' => 'string',
        ]);

        $role->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        $this->syncPermissionsToRole($role, $data['permissions'] ?? []);

        return response()->json([
            'success' => true,
            'message' => "تم تحديث الدور «{$role->name}» بنجاح.",
            'role' => $this->formatRole($role->load('permissions')),
        ]);
    }

    public function destroyRole(Role $role)
    {
        if ($role->users()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن حذف هذا الدور لأن هناك أعضاء مرتبطون به. قم بتغيير دورهم أولاً.',
            ], 422);
        }

        $name = $role->name;
        $role->delete();

        return response()->json([
            'success' => true,
            'message' => "تم حذف الدور «{$name}».",
        ]);
    }

    // ── Private Helpers ────────────────────────────────────────────────────

    private function syncPermissionsToRole(Role $role, array $permNames): void
    {
        $ids = [];
        foreach ($permNames as $name) {
            // Extract group from permission name prefix (e.g. "customers" from "customers.view")
            $group = str_contains($name, '.') ? explode('.', $name)[0] : null;
            $perm = Permission::firstOrCreate(
                ['name' => $name],
                ['group' => $group]
            );
            $ids[] = $perm->id;
        }
        $role->permissions()->sync($ids);
    }

    private function formatUser(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'status' => $user->status,
            'notes' => $user->notes,
            'initials' => $user->initials(),
            'role_id' => $user->role_id,
            'role' => $user->role ? [
                'id' => $user->role->id,
                'name' => $user->role->name,
                'slug' => $user->role->slug,
                'badge' => $user->role->badgeColor(),
            ] : null,
        ];
    }

    private function formatRole(Role $role): array
    {
        return [
            'id' => $role->id,
            'name' => $role->name,
            'slug' => $role->slug,
            'description' => $role->description,
            'badge' => $role->badgeColor(),
            'permissions' => $role->permissions->pluck('name')->toArray(),
            'users_count' => $role->users()->count(),
        ];
    }
}
