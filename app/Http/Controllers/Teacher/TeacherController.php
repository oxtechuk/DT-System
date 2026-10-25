<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * TeacherController — Teacher dashboard (auth:web + role:teacher required).
 */
class TeacherController extends Controller
{
    /**
     * Teacher main dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $teacher = $user->teacher;

        if (! $teacher) {
            abort(403, 'لا يوجد ملف مدرس مرتبط بحسابك.');
        }

        $recentVisits = $teacher->visits()
            ->latest('visited_at')
            ->take(10)
            ->get();

        $stats = [
            'total_visits' => $teacher->totalVisits(),
            'total_earnings' => $teacher->totalEarnings(),
            'pending_commission' => $teacher->pendingCommission(),
            'commission_rate' => $teacher->commission_rate,
        ];

        return view('teacher.dashboard', compact('teacher', 'recentVisits', 'stats'));
    }

    /**
     * All visits list with pagination.
     */
    public function visits(Request $request)
    {
        $teacher = Auth::user()->teacher;

        if (! $teacher) {
            abort(403);
        }

        $visits = $teacher->visits()
            ->latest('visited_at')
            ->paginate(20);

        return view('teacher.visits', compact('teacher', 'visits'));
    }

    /**
     * Commissions list.
     */
    public function commissions(Request $request)
    {
        $teacher = Auth::user()->teacher;

        if (! $teacher) {
            abort(403);
        }

        $commissions = $teacher->commissions()
            ->with('visit')
            ->latest()
            ->paginate(20);

        $totalPaid = $teacher->totalEarnings();
        $totalPending = $teacher->pendingCommission();

        return view('teacher.commissions', compact('teacher', 'commissions', 'totalPaid', 'totalPending'));
    }

    /**
     * Show QR code page.
     */
    public function qrCode()
    {
        $teacher = Auth::user()->teacher;

        if (! $teacher) {
            abort(403);
        }

        return view('teacher.qr-code', compact('teacher'));
    }
}
