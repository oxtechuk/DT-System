<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Teacher;
use App\Models\TeacherVisit;
use Illuminate\Http\Request;

/**
 * QrScanController — Public controller (no auth required).
 * Handles QR code scanning and visitor registration for teachers.
 */
class QrScanController extends Controller
{
    /**
     * Show the scan landing page for a given teacher QR code.
     */
    public function show(string $qrCode)
    {
        $teacher = Teacher::with('user')
            ->where('qr_code', $qrCode)
            ->where('is_active', true)
            ->firstOrFail();

        return view('scan.show', compact('teacher'));
    }

    /**
     * Register a new visitor for the teacher.
     */
    public function register(Request $request, string $qrCode)
    {
        $teacher = Teacher::where('qr_code', $qrCode)
            ->where('is_active', true)
            ->firstOrFail();

        $validated = $request->validate([
            'visitor_name' => ['required', 'string', 'max:100'],
            'visitor_phone' => ['nullable', 'string', 'max:20'],
        ]);

        // Try to match an existing customer by phone
        $customer = null;
        if (! empty($validated['visitor_phone'])) {
            $customer = Customer::where('phone', $validated['visitor_phone'])->first();
        }

        TeacherVisit::create([
            'teacher_id' => $teacher->id,
            'customer_id' => $customer?->id,
            'visitor_name' => $validated['visitor_name'],
            'visitor_phone' => $validated['visitor_phone'] ?? null,
            'visited_at' => now(),
        ]);

        return redirect()->route('scan.success', $qrCode);
    }

    /**
     * Show the success confirmation page.
     */
    public function success(string $qrCode)
    {
        $teacher = Teacher::with('user')
            ->where('qr_code', $qrCode)
            ->where('is_active', true)
            ->firstOrFail();

        return view('scan.success', compact('teacher'));
    }
}
