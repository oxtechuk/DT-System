<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Payment;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{
    public function current()
    {
        $user = Auth::user() ?? \App\Models\User::first();

        // Find current open shift or latest shift
        $currentShift = Shift::where('status', 'open')
            ->latest('opened_at')
            ->first();

        if (!$currentShift) {
            // Auto open or display no-open-shift state
            $recentShifts = Shift::latest('opened_at')->limit(10)->get();
            return view('shifts.current', [
                'shift' => null,
                'recentShifts' => $recentShifts,
                'stats' => null,
                'payments' => collect(),
            ]);
        }

        // Calculate live sales during this shift
        $payments = Payment::where('paid_at', '>=', $currentShift->opened_at)
            ->with(['deal.customer', 'deal.room', 'order'])
            ->latest('paid_at')
            ->get();

        $cashSales = (float) $payments->where('method', 'cash')->sum('amount');
        $instapaySales = (float) $payments->where('method', 'instapay')->sum('amount');
        $walletSales = (float) $payments->where('method', 'wallet')->sum('amount');
        $cardSales = (float) $payments->where('method', 'card')->sum('amount');
        $totalSales = (float) $payments->sum('amount');

        // Petty cash expenses during shift
        $expenses = Expense::where('created_at', '>=', $currentShift->opened_at)->sum('amount') ?? 0;

        $openingCash = (float) $currentShift->opening_cash;
        $expectedCash = $openingCash + $cashSales - $expenses;

        $stats = [
            'opening_cash' => $openingCash,
            'cash_sales' => $cashSales,
            'instapay_sales' => $instapaySales,
            'wallet_sales' => $walletSales,
            'card_sales' => $cardSales,
            'total_sales' => $totalSales,
            'expenses' => (float) $expenses,
            'expected_cash' => $expectedCash,
            'transactions_count' => $payments->count(),
        ];

        $recentShifts = Shift::where('status', 'closed')->latest('closed_at')->limit(5)->get();

        return view('shifts.current', [
            'shift' => $currentShift,
            'stats' => $stats,
            'payments' => $payments,
            'recentShifts' => $recentShifts,
        ]);
    }

    public function open(Request $request)
    {
        $request->validate([
            'opening_cash' => 'required|numeric|min:0',
        ]);

        $user = Auth::user() ?? \App\Models\User::first();

        // Check if there is already an open shift
        $existing = Shift::where('status', 'open')->first();
        if ($existing) {
            return redirect()->back()->with('warning', 'توجد وردية مفتوحة بالفعل.');
        }

        Shift::create([
            'user_id' => $user->id,
            'opened_at' => now(),
            'opening_cash' => (float) $request->input('opening_cash', 0),
            'status' => 'open',
        ]);

        return redirect()->back()->with('success', 'تم فتح الوردية بنجاح.');
    }

    public function close(Request $request, Shift $shift)
    {
        $request->validate([
            'actual_cash' => 'required|numeric|min:0',
            'closing_notes' => 'nullable|string|max:1000',
        ]);

        // Calculate totals
        $payments = Payment::where('paid_at', '>=', $shift->opened_at)->get();
        $cashSales = (float) $payments->where('method', 'cash')->sum('amount');
        $instapaySales = (float) $payments->where('method', 'instapay')->sum('amount');
        $walletSales = (float) $payments->where('method', 'wallet')->sum('amount');
        $totalSales = (float) $payments->sum('amount');
        $expenses = (float) (Expense::where('created_at', '>=', $shift->opened_at)->sum('amount') ?? 0);

        $expectedCash = (float) $shift->opening_cash + $cashSales - $expenses;
        $actualCash = (float) $request->input('actual_cash');
        $difference = $actualCash - $expectedCash;

        $shift->update([
            'closed_at' => now(),
            'status' => 'closed',
            'expected_cash' => $expectedCash,
            'actual_cash' => $actualCash,
            'cash_difference' => $difference,
            'total_cash' => $cashSales,
            'total_instapay' => $instapaySales,
            'total_wallet' => $walletSales,
            'total_expenses' => $expenses,
            'total_revenue' => $totalSales,
            'closing_notes' => $request->input('closing_notes'),
        ]);

        return redirect()->back()->with('success', 'تم إغلاق الوردية وترحيل الحسابات بنجاح.');
    }
}
