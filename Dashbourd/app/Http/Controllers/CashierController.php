<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Deal;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Room;
use App\Models\Shift;
use App\Models\WorkspaceType;
use Illuminate\Http\Request;

class CashierController extends Controller
{
    public function index()
    {
        $rooms = Room::with(['activeDeals.customer', 'activeDeals.order.items.product'])->get();
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
            ->limit(30)
            ->get();

        // ── Paid Closed Sessions Today (تم السداد اليوم) ──
        $paidDealsToday = Deal::where('status', 'closed')
            ->whereDate('ended_at', today())
            ->whereHas('order', function ($q) {
                $q->where('remaining_amount', '<=', 0);
            })
            ->with(['customer', 'room', 'workspaceType', 'order.items.product'])
            ->latest('ended_at')
            ->limit(30)
            ->get();

        $todayCashTotal = Payment::whereDate('paid_at', today())->where('method', 'cash')->where('type', 'income')->sum('amount');

        $pendingPortalOrders = Order::where('source', 'portal')
            ->whereIn('fulfillment_status', ['pending', 'preparing'])
            ->with(['customer', 'items.product', 'deal.room'])
            ->latest()
            ->get();

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
        ]);
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

        $bookings = \App\Models\Booking::where('customer_id', $customer->id)
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
            'deals' => $deals->map(fn($d) => [
                'deal_number' => $d->deal_number,
                'room' => $d->room?->name ?? 'المساحة العامة',
                'status' => $d->status,
                'started_at' => $d->started_at?->format('Y-m-d h:i A'),
                'duration' => $d->duration_minutes ? round($d->duration_minutes / 60, 1) . ' ساعة' : ($d->isOpen ? 'نشطة حالياً' : '-'),
                'order_total' => (float) ($d->order?->total ?? 0),
                'remaining' => (float) ($d->order?->remaining_amount ?? 0),
            ]),
            'bookings' => $bookings->map(fn($b) => [
                'booking_number' => $b->booking_number,
                'room' => $b->room?->name ?? 'غير محددة',
                'start_at' => $b->start_at?->format('Y-m-d h:i A'),
                'status' => $b->status,
                'notes' => $b->notes,
            ]),
            'orders' => $orders->map(fn($o) => [
                'order_number' => $o->order_number,
                'created_at' => $o->created_at?->format('Y-m-d h:i A'),
                'total' => (float) $o->total,
                'status' => $o->status,
                'items' => $o->items->map(fn($it) => [
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
            'orders' => $orders
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
            'message' => 'تم تحديث حالة الطلب بنجاح'
        ]);
    }
}
