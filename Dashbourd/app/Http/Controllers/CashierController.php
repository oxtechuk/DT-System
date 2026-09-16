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
        $customers = Customer::where('status', 'active')->orderBy('name')->get();
        $workspaceTypes = WorkspaceType::where('active', true)->with('pricingRules')->get();
        
        $currentShift = Shift::where('status', 'open')->latest('opened_at')->first();
        $activeDeals = Deal::open()->with(['customer', 'room', 'workspaceType', 'order.items.product'])->get();

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
            'workspaceTypes' => $workspaceTypes,
            'currentShift' => $currentShift,
            'activeDeals' => $activeDeals,
            'todayCashTotal' => (float) $todayCashTotal,
            'pendingPortalOrders' => $pendingPortalOrders,
        ]);
    }

    public function getPortalOrders()
    {
        $orders = Order::where('source', 'portal')
            ->whereIn('fulfillment_status', ['pending', 'preparing'])
            ->with(['customer', 'items.product', 'deal.room'])
            ->latest()
            ->get();

        return response()->json(['orders' => $orders]);
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
