<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\CashierAlertLog;
use App\Models\CashierAlertSchedule;
use App\Models\Customer;
use App\Models\Deal;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Room;
use App\Models\Shift;
use App\Models\WorkspaceType;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CashierController extends Controller
{
    public function index()
    {
        $rooms = Room::with(['activeDeals.customer', 'activeDeals.workspaceType', 'activeDeals.order.items.product'])->get();
        $products = Product::active()->with('category')->orderBy('name')->get();
        $customers = Customer::where('status', 'active')->orderBy('full_name')->get();
        $workspaceTypes = WorkspaceType::where('active', true)->with('pricingRules')->get();

        $currentShift = Shift::where('status', 'open')->latest('opened_at')->first();
        $activeDeals = Deal::open()->with(['customer', 'room', 'workspaceType', 'order.items.product'])->get();
        $activeCustomerIds = $activeDeals->pluck('customer_id')->filter()->values()->toArray();

        // ── Unpaid Closed Sessions (مطلوبة / آجل) ──
        $unpaidClosedDeals = Deal::where('status', 'closed')
            ->whereHas('order', function ($q) {
                $q->where('remaining_amount', '>', 0);
            })
            ->with(['customer', 'room', 'workspaceType', 'order.items.product'])
            ->latest('ended_at')
            ->limit(50)
            ->get();

        // ── Paid Closed Sessions Today (تم السداد اليوم) ──
        $paidDealsToday = Deal::where('status', 'closed')
            ->whereDate('ended_at', today())
            ->whereHas('order', function ($q) {
                $q->where('remaining_amount', '<=', 0);
            })
            ->with(['customer', 'room', 'workspaceType', 'order.items.product'])
            ->latest('ended_at')
            ->limit(50)
            ->get();

        $todayCashTotal = Payment::whereDate('paid_at', today())->where('method', 'cash')->where('type', 'income')->sum('amount');

        $pendingPortalOrders = Order::where('source', 'portal')
            ->whereIn('fulfillment_status', ['pending', 'preparing'])
            ->with(['customer', 'items.product', 'deal.room'])
            ->latest()
            ->get();

        // ── Computed Totals for Dashboard Cards ──
        $totalOpenSessionsValue = (float) $activeDeals->sum(fn ($d) => $d->order ? $d->order->total : 0);
        $totalClosedSessionsValueToday = (float) Deal::where('status', 'closed')
            ->whereDate('ended_at', today())
            ->get()
            ->sum(fn ($d) => $d->order ? $d->order->total : 0);
        $totalUnpaidDebtValue = (float) $unpaidClosedDeals->sum(fn ($d) => $d->order ? $d->order->remaining_amount : 0);

        return view('cashier.index', [
            'rooms' => $rooms,
            'products' => $products,
            'customers' => $customers,
            'activeCustomerIds' => $activeCustomerIds,
            'workspaceTypes' => $workspaceTypes,
            'currentShift' => $currentShift,
            'activeDeals' => $activeDeals,
            'unpaidClosedDeals' => $unpaidClosedDeals,
            'paidDealsToday' => $paidDealsToday,
            'todayCashTotal' => (float) $todayCashTotal,
            'pendingPortalOrders' => $pendingPortalOrders,
            'totalOpenSessionsValue' => $totalOpenSessionsValue,
            'totalClosedSessionsValueToday' => $totalClosedSessionsValueToday,
            'totalUnpaidDebtValue' => $totalUnpaidDebtValue,
        ]);
    }

    /**
     * Full Edit Session (Time, Price, Room) for Open or Closed Sessions
     */
    public function updateSession(Request $request, Deal $deal)
    {
        $validated = $request->validate([
            'started_at' => 'required|date',
            'ended_at' => 'nullable|date|after_or_equal:started_at',
            'room_id' => 'nullable|exists:rooms,id',
            'applied_price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        $startedAt = Carbon::parse($validated['started_at']);
        $endedAt = ! empty($validated['ended_at']) ? Carbon::parse($validated['ended_at']) : $deal->ended_at;

        $durationMins = $endedAt ? $startedAt->diffInMinutes($endedAt) : null;

        $deal->update([
            'started_at' => $startedAt,
            'ended_at' => $endedAt,
            'duration_minutes' => $durationMins,
            'room_id' => $validated['room_id'] ?? $deal->room_id,
            'notes' => $validated['notes'] ?? $deal->notes,
        ]);

        if (array_key_exists('applied_price', $validated) && $validated['applied_price'] !== null && $deal->order) {
            $price = (float) $validated['applied_price'];
            $deal->update(['applied_price' => $price]);

            // Update session item in order
            $sessionItem = $deal->order->items()->where('item_type', 'session')->first();
            if ($sessionItem) {
                $sessionItem->update([
                    'unit_price' => $price,
                    'total' => $price,
                ]);
            } elseif ($price > 0) {
                $deal->order->items()->create([
                    'item_type' => 'session',
                    'name' => 'جلسة — '.($deal->workspaceType?->name ?? 'مساحة عمل'),
                    'quantity' => 1,
                    'unit_price' => $price,
                    'total' => $price,
                ]);
            }
            $deal->order->recalculate();
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تعديل بيانات الجلسة بنجاح.',
                'deal' => $deal->fresh(['room', 'order.items']),
            ]);
        }

        return redirect()->back()->with('success', 'تم تعديل بيانات الجلسة بنجاح.');
    }

    /**
     * Cancel an active open or closed session
     */
    public function cancelSession(Request $request, Deal $deal)
    {
        $deal->update(['status' => 'cancelled']);
        if ($deal->order) {
            $deal->order->update(['status' => 'cancelled']);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم إلغاء الجلسة بنجاح.',
            ]);
        }

        return redirect()->back()->with('success', 'تم إلغاء الجلسة بنجاح.');
    }

    /**
     * Apply Product Item Discount or General Order Discount
     */
    public function applyItemDiscount(Request $request, Order $order)
    {
        $validated = $request->validate([
            'item_id' => 'nullable|exists:order_items,id',
            'discount_amount' => 'required|numeric|min:0',
        ]);

        if (! empty($validated['item_id'])) {
            $item = $order->items()->findOrFail($validated['item_id']);
            $disc = (float) $validated['discount_amount'];
            $newTotal = max(0, ($item->unit_price * $item->quantity) - $disc);
            $item->update([
                'discount_amount' => $disc,
                'total' => $newTotal,
            ]);
        } else {
            $order->update([
                'discount' => (float) $validated['discount_amount'],
            ]);
        }

        $order->recalculate();

        return response()->json([
            'success' => true,
            'message' => 'تم تطبيق الخصم بنجاح.',
            'order' => $order->fresh(['items']),
        ]);
    }

    /**
     * Custom Payment with Overpayment Handling (Tip vs Debt Credit)
     */
    public function payCustomOrder(Request $request, Order $order)
    {
        $validated = $request->validate([
            'paid_amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|in:cash,instapay,wallet,card,other',
            'overpayment_type' => 'nullable|string|in:none,tip,debt_credit',
            'notes' => 'nullable|string|max:500',
        ]);

        $paidAmount = (float) $validated['paid_amount'];
        $remaining = (float) $order->remaining_amount;
        $overpaymentType = $validated['overpayment_type'] ?? 'none';
        $overpaymentAmount = max(0, $paidAmount - $remaining);

        // Save Payment
        $note = $validated['notes'] ?? '';
        if ($overpaymentAmount > 0) {
            if ($overpaymentType === 'tip') {
                $note .= " (تم احتساب زيادة {$overpaymentAmount} ج.م كـ إكرامية/تبس)";
            } elseif ($overpaymentType === 'debt_credit') {
                $note .= " (تم تسجيل زيادة {$overpaymentAmount} ج.م كرصيد للعميل)";
                // Update customer balance if registered
                if ($order->customer) {
                    $order->customer->increment('balance', $overpaymentAmount);
                }
            }
        }

        Payment::create([
            'payment_number' => Payment::generateNumber(),
            'order_id' => $order->id,
            'customer_id' => $order->customer_id,
            'type' => 'income',
            'method' => $validated['payment_method'],
            'amount' => $paidAmount,
            'overpayment_type' => $overpaymentAmount > 0 ? $overpaymentType : 'none',
            'overpayment_amount' => $overpaymentAmount,
            'paid_at' => now(),
            'received_by' => auth()->id(),
            'notes' => trim($note),
        ]);

        $totalPaidAll = $order->payments()->where('type', 'income')->sum('amount');
        $newRemaining = max(0, $order->total - $totalPaidAll);

        $order->update([
            'paid_amount' => $totalPaidAll,
            'remaining_amount' => $newRemaining,
            'status' => $newRemaining <= 0 ? 'paid' : 'partially_paid',
            'closed_at' => $newRemaining <= 0 ? now() : null,
            'closed_by' => $newRemaining <= 0 ? auth()->id() : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الدفعة وإغلاق الحساب بنجاح.',
            'remaining' => $newRemaining,
            'overpayment_amount' => $overpaymentAmount,
        ]);
    }

    /**
     * Settle Debt for a closed deal
     */
    public function settleDebt(Request $request, Deal $deal)
    {
        $order = $deal->order;
        if (! $order || $order->remaining_amount <= 0) {
            return response()->json(['success' => false, 'message' => 'لا توجد مديونية معلقة على هذه الجلسة.'], 422);
        }

        return $this->payCustomOrder($request, $order);
    }

    /**
     * Customer Profile with Full History (Orders, Bookings, Sessions, Notes)
     */
    public function getCustomerHistory(Customer $customer)
    {
        $deals = Deal::where('customer_id', $customer->id)
            ->with(['room', 'workspaceType', 'order.items'])
            ->latest('started_at')
            ->limit(15)
            ->get();

        $bookings = Booking::where('customer_id', $customer->id)
            ->with('room')
            ->latest('start_at')
            ->limit(10)
            ->get();

        $orders = Order::where('customer_id', $customer->id)
            ->with('items.product')
            ->latest('created_at')
            ->limit(15)
            ->get();

        $totalSpent = Payment::where('customer_id', $customer->id)->where('type', 'income')->sum('amount');
        $visitsCount = Deal::where('customer_id', $customer->id)->count();

        return response()->json([
            'success' => true,
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'phone' => $customer->phone,
                'email' => $customer->email,
                'notes' => $customer->notes,
                'created_at' => $customer->created_at?->format('Y-m-d'),
                'total_spent' => (float) $totalSpent,
                'visits_count' => $visitsCount,
                'active_deal' => Deal::where('customer_id', $customer->id)->where('status', 'open')->with('room')->first(),
            ],
            'deals' => $deals->map(fn ($d) => [
                'deal_number' => $d->deal_number,
                'room' => $d->room?->name ?? 'المساحة العامة',
                'status' => $d->status,
                'started_at' => $d->started_at?->format('Y-m-d h:i A'),
                'duration' => $d->duration_minutes ? round($d->duration_minutes / 60, 1).' ساعة' : ($d->isOpen ? 'نشطة حالياً' : '-'),
                'order_total' => (float) ($d->order?->total ?? 0),
                'remaining' => (float) ($d->order?->remaining_amount ?? 0),
            ]),
            'bookings' => $bookings->map(fn ($b) => [
                'booking_number' => $b->booking_number,
                'room' => $b->room?->name ?? 'غير محددة',
                'start_at' => $b->start_at?->format('Y-m-d h:i A'),
                'status' => $b->status,
                'notes' => $b->notes,
            ]),
            'orders' => $orders->map(fn ($o) => [
                'order_number' => $o->order_number,
                'created_at' => $o->created_at?->format('Y-m-d h:i A'),
                'total' => (float) $o->total,
                'status' => $o->status,
                'items' => $o->items->map(fn ($it) => [
                    'name' => $it->name,
                    'quantity' => $it->quantity,
                    'total' => (float) $it->total,
                ]),
            ]),
        ]);
    }

    /**
     * Save/Update Customer Notes
     */
    public function updateCustomerNotes(Request $request, Customer $customer)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        $customer->update(['notes' => $request->notes]);

        return response()->json([
            'success' => true,
            'message' => 'تم حفظ ملاحظات العميل بنجاح.',
            'notes' => $customer->notes,
        ]);
    }

    public function getPortalOrders()
    {
        $orders = Order::where('source', 'portal')
            ->whereIn('fulfillment_status', ['pending', 'preparing'])
            ->with(['customer', 'items.product', 'deal.room'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'count' => $orders->count(),
            'orders' => $orders,
        ]);
    }

    public function updateFulfillmentStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,preparing,delivered,cancelled',
        ]);

        $order->update(['fulfillment_status' => $request->status]);

        return response()->json([
            'success' => true,
            'status' => $order->fulfillment_status,
            'message' => 'تم تحديث حالة الطلب بنجاح',
        ]);
    }

    // =========================================================================
    // Cashier Alert System (Schedule, Snooze, Complete)
    // =========================================================================

    /**
     * Create a new alert / reminder for the cashier.
     * POST /cashier/alerts
     */
    public function createAlert(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'scheduled_at' => 'required|date',
        ]);

        $schedule = CashierAlertSchedule::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'scheduled_at' => $data['scheduled_at'],
            'next_alert_at' => $data['scheduled_at'],
            'status' => 'pending',
            'created_by' => auth()->id(),
        ]);

        CashierAlertLog::create([
            'schedule_id' => $schedule->id,
            'action' => 'created',
            'acted_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'id' => $schedule->id,
            'message' => 'تم إنشاء التنبيه بنجاح.',
        ]);
    }

    /**
     * Return all due alerts (next_alert_at <= now AND status = pending/snoozed).
     * GET /cashier/alerts/pending
     */
    public function checkPendingAlerts()
    {
        $alerts = CashierAlertSchedule::dueNow()
            ->latest('next_alert_at')
            ->get()
            ->map(fn ($a) => [
                'id' => $a->id,
                'title' => $a->title,
                'description' => $a->description,
                'scheduled_at' => $a->scheduled_at?->format('Y-m-d H:i'),
                'snooze_count' => $a->snooze_count,
                'snooze_label' => $a->snoozeLabel(),
                'snooze_minutes' => $a->nextSnoozeMinutes(),
            ]);

        return response()->json([
            'success' => true,
            'count' => $alerts->count(),
            'alerts' => $alerts,
        ]);
    }

    /**
     * Mark an alert as completed ("تم").
     * POST /cashier/alerts/{schedule}/complete
     */
    public function completeAlert(CashierAlertSchedule $schedule)
    {
        $schedule->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        CashierAlertLog::create([
            'schedule_id' => $schedule->id,
            'action' => 'completed',
            'acted_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إكمال التنبيه بنجاح.',
        ]);
    }

    /**
     * Snooze an alert (escalating: 5 → 15 → 60 minutes).
     * POST /cashier/alerts/{schedule}/snooze
     */
    public function snoozeAlert(CashierAlertSchedule $schedule)
    {
        $minutes = $schedule->nextSnoozeMinutes();

        $schedule->update([
            'status' => 'snoozed',
            'snooze_count' => $schedule->snooze_count + 1,
            'next_alert_at' => now()->addMinutes($minutes),
        ]);

        CashierAlertLog::create([
            'schedule_id' => $schedule->id,
            'action' => 'snoozed',
            'snoozed_minutes' => $minutes,
            'acted_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'snoozed_minutes' => $minutes,
            'next_alert_at' => $schedule->fresh()->next_alert_at?->format('Y-m-d H:i:s'),
            'message' => "تم التأجيل لمدة {$minutes} دقيقة.",
        ]);
    }
}
