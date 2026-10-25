<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Teacher;
use App\Models\TeacherCommission;
use App\Models\TeacherVisit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * TeacherAdminController — Admin management of teachers (owner/admin roles only).
 */
class TeacherAdminController extends Controller
{
    /**
     * List all teachers with stats.
     */
    public function index()
    {
        $teachers = Teacher::with('user')
            ->withCount('visits')
            ->latest()
            ->paginate(20);

        $totalCommissionPending = TeacherCommission::where('status', 'pending')->sum('amount');
        $totalCommissionPaid = TeacherCommission::where('status', 'paid')->sum('amount');
        $totalVisitsCount = TeacherVisit::count();
        $activeTeachersCount = Teacher::where('is_active', true)->count();

        return view('admin.teachers.index', compact(
            'teachers',
            'totalCommissionPending',
            'totalCommissionPaid',
            'totalVisitsCount',
            'activeTeachersCount'
        ));
    }

    /**
     * Show a single teacher's details, visits, and commissions.
     */
    public function show(Teacher $teacher)
    {
        $teacher->load('user');

        $visits = $teacher->visits()->latest('visited_at')->paginate(15);

        $commissions = $teacher->commissions()
            ->with('visit')
            ->latest()
            ->paginate(15);

        return view('admin.teachers.show', compact('teacher', 'visits', 'commissions'));
    }

    /**
     * Store a new teacher (creates a User + Teacher record).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8'],
            'commission_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'bio' => ['nullable', 'string', 'max:500'],
        ]);

        $teacherRole = Role::where('slug', 'teacher')->first();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role_id' => $teacherRole?->id,
            'status' => 'active',
        ]);

        Teacher::create([
            'user_id' => $user->id,
            'commission_rate' => $validated['commission_rate'],
            'bio' => $validated['bio'] ?? null,
            'is_active' => true,
        ]);

        // Ensure teacher also exists as a Customer for the mobile customer portal
        if (! empty($validated['phone'])) {
            $customer = Customer::where('phone', $validated['phone'])->first();
            if (! $customer && ! empty($validated['email'])) {
                $customer = Customer::where('email', $validated['email'])->first();
            }

            if (! $customer) {
                Customer::create([
                    'full_name' => $validated['name'],
                    'phone' => $validated['phone'],
                    'email' => $validated['email'],
                    'password' => $validated['password'],
                    'customer_type' => 'registered',
                    'classification' => 'lecturer',
                    'source' => 'teacher',
                    'status' => 'active',
                    'referral_code' => Customer::generateUniqueReferralCode('TCH_'.substr(preg_replace('/\D/', '', $validated['phone']), -4)),
                ]);
            } else {
                $customer->update([
                    'full_name' => $validated['name'],
                    'email' => $validated['email'],
                    'classification' => 'lecturer',
                ]);
            }
        }

        return redirect()->route('admin.teachers.index')
            ->with('success', 'تم إضافة المدرس وتفعيل حسابه بتطبيق الموبايل بنجاح.');
    }

    /**
     * Update teacher commission rate and details.
     */
    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'commission_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'bio' => ['nullable', 'string', 'max:500'],
            'is_active' => ['boolean'],
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $teacher->user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
        ]);

        $teacher->update([
            'commission_rate' => $validated['commission_rate'],
            'bio' => $validated['bio'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Sync Customer profile if exists
        $customer = $teacher->customer();
        if ($customer) {
            $customer->update([
                'full_name' => $validated['name'],
                'phone' => $validated['phone'] ?? $customer->phone,
                'classification' => 'lecturer',
            ]);
        }

        return redirect()->route('admin.teachers.show', $teacher)
            ->with('success', 'تم تحديث بيانات المدرس بنجاح.');
    }

    /**
     * Delete a teacher and their user account.
     */
    public function destroy(Teacher $teacher)
    {
        $user = $teacher->user;
        $teacher->delete();
        $user->delete();

        return redirect()->route('admin.teachers.index')
            ->with('success', 'تم حذف المدرس بنجاح.');
    }

    /**
     * Mark all pending commissions for a teacher as paid.
     */
    public function pay(Teacher $teacher)
    {
        $count = $teacher->commissions()
            ->where('status', 'pending')
            ->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

        return redirect()->route('admin.teachers.show', $teacher)
            ->with('success', "تم تسجيل دفع {$count} كوميشن بنجاح.");
    }

    /**
     * Global commissions report across all teachers.
     */
    public function commissions()
    {
        $commissions = TeacherCommission::with(['teacher.user', 'visit'])
            ->latest()
            ->paginate(30);

        $summary = [
            'total_pending' => TeacherCommission::where('status', 'pending')->sum('amount'),
            'total_paid' => TeacherCommission::where('status', 'paid')->sum('amount'),
        ];

        return view('admin.teachers.commissions', compact('commissions', 'summary'));
    }

    /**
     * Update the teacher_visit_base_value setting.
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'teacher_visit_base_value' => ['required', 'numeric', 'min:0'],
            'teacher_default_commission_rate' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        Setting::set('teacher_visit_base_value', $validated['teacher_visit_base_value']);
        Setting::set('teacher_default_commission_rate', $validated['teacher_default_commission_rate']);

        return redirect()->back()->with('success', 'تم حفظ إعدادات الكوميشن.');
    }
}
