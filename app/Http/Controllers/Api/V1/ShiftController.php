<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\Shift;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShiftController extends Controller
{
    /**
     * Get current open shift and its live statistics.
     */
    public function current(): JsonResponse
    {
        $currentShift = Shift::where('status', 'open')
            ->with('user:id,name,email')
            ->latest('opened_at')
            ->first();

        if (! $currentShift) {
            return response()->json([
                'success' => true,
                'data' => [
                    'has_open_shift' => false,
                    'shift' => null,
                    'stats' => null,
                ],
            ]);
        }

        $payments = Payment::where('paid_at', '>=', $currentShift->opened_at)->get();
        $cashSales = (float) $payments->where('method', 'cash')->sum('amount');
        $instapaySales = (float) $payments->where('method', 'instapay')->sum('amount');
        $walletSales = (float) $payments->where('method', 'wallet')->sum('amount');
        $cardSales = (float) $payments->where('method', 'card')->sum('amount');
        $totalSales = (float) $payments->sum('amount');

        $expenses = (float) Expense::where('created_at', '>=', $currentShift->opened_at)->sum('amount');
        $openingCash = (float) $currentShift->opening_cash;
        $expectedCash = $openingCash + $cashSales - $expenses;

        $unpaidOpenDealsCount = Deal::open()->count();

        return response()->json([
            'success' => true,
            'data' => [
                'has_open_shift' => true,
                'shift' => [
                    'id' => $currentShift->id,
                    'opened_at' => $currentShift->opened_at,
                    'opened_by' => $currentShift->user?->name,
                    'opening_cash' => $openingCash,
                ],
                'stats' => [
                    'opening_cash' => $openingCash,
                    'cash_sales' => $cashSales,
                    'instapay_sales' => $instapaySales,
                    'wallet_sales' => $walletSales,
                    'card_sales' => $cardSales,
                    'total_sales' => $totalSales,
                    'expenses' => $expenses,
                    'expected_cash' => $expectedCash,
                    'transactions_count' => $payments->count(),
                    'unpaid_open_deals' => $unpaidOpenDealsCount,
                ],
            ],
        ]);
    }

    /**
     * Open a new shift.
     */
    public function open(Request $request): JsonResponse
    {
        $existing = Shift::where('status', 'open')->first();
        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'يوجد وردية مفتوحة بالفعل حالياً.',
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'opening_cash' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $shift = Shift::create([
            'user_id' => $request->user()?->id ?? 1,
            'opening_cash' => $request->input('opening_cash'),
            'opened_at' => now(),
            'status' => 'open',
            'notes' => $request->input('notes'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم فتح الوردية بنجاح',
            'data' => $shift,
        ], 201);
    }

    /**
     * Close the specified shift.
     */
    public function close(Request $request, Shift $shift): JsonResponse
    {
        if ($shift->status !== 'open') {
            return response()->json([
                'success' => false,
                'message' => 'هذه الوردية مغلقة بالفعل.',
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'closing_cash' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $payments = Payment::where('paid_at', '>=', $shift->opened_at)->get();
        $cashSales = (float) $payments->where('method', 'cash')->sum('amount');
        $expenses = (float) Expense::where('created_at', '>=', $shift->opened_at)->sum('amount');
        $expectedCash = (float) $shift->opening_cash + $cashSales - $expenses;
        $actualCash = (float) $request->input('closing_cash');
        $difference = $actualCash - $expectedCash;

        $shift->update([
            'closing_cash' => $actualCash,
            'expected_cash' => $expectedCash,
            'difference' => $difference,
            'closed_at' => now(),
            'status' => 'closed',
            'notes' => $request->input('notes') ?? $shift->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إغلاق الوردية وحساب العجز/الزيادة بنجاح',
            'data' => [
                'shift' => $shift,
                'expected_cash' => $expectedCash,
                'actual_cash' => $actualCash,
                'difference' => $difference,
            ],
        ]);
    }
}
