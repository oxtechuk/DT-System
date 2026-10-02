<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CashierAlertSchedule;
use App\Models\Customer;
use App\Models\CustomerFeedback;
use App\Models\Deal;
use App\Models\Event;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Room;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PortalController extends Controller
{
    protected function currentCustomer(): Customer
    {
        /** @var Customer $customer */
        $customer = Auth::guard('customer')->user();
        if ($customer && (! $customer->last_active_at || $customer->last_active_at->diffInMinutes(now()) >= 2)) {
            Customer::where('id', $customer->id)->update(['last_active_at' => now()]);
            $customer->last_active_at = now();
        }

        return $customer;
    }

    public function home()
    {
        $customer = $this->currentCustomer();

        // Active open deal if customer is currently in space
        $activeDeal = Deal::where('customer_id', $customer->id)
            ->where('status', 'open')
            ->with(['room', 'workspaceType'])
            ->latest('started_at')
            ->first();

        // Loyalty status
        $loyalty = $customer->loyalty_status;

        // Pending portal orders
        $recentOrders = Order::where('customer_id', $customer->id)
            ->with(['items.product', 'deal.room'])
            ->latest('id')
            ->take(3)
            ->get();

        // Featured Star Event (البانر المميز بالنجمة)
        $featuredEvent = Event::featured()->upcoming()->first() ?? Event::upcoming()->first();
        $upcomingEvents = Event::upcoming()->get();

        $referralDiscount = Setting::get('affiliate_discount_value', 20);
        $referralType = Setting::get('affiliate_discount_type', 'percentage');

        return view('portal.home', [
            'customer' => $customer,
            'activeDeal' => $activeDeal,
            'loyalty' => $loyalty,
            'recentOrders' => $recentOrders,
            'featuredEvent' => $featuredEvent,
            'upcomingEvents' => $upcomingEvents,
            'referralDiscount' => $referralDiscount,
            'referralType' => $referralType,
        ]);
    }

    public function community()
    {
        $customer = $this->currentCustomer();
        $events = Event::active()->orderBy('event_date', 'asc')->get();
        $featuredEvent = Event::featured()->upcoming()->first();

        return view('portal.community', [
            'customer' => $customer,
            'events' => $events,
            'featuredEvent' => $featuredEvent,
        ]);
    }

    public function menu()
    {
        $customer = $this->currentCustomer();

        // Check if customer has active deal to auto-bind room
        $activeDeal = Deal::where('customer_id', $customer->id)
            ->where('status', 'open')
            ->with('room')
            ->latest('started_at')
            ->first();

        $categories = ProductCategory::where('active', true)->with(['products' => function ($q) {
            $q->active()->orderBy('name');
        }])->get();

        // All active products
        $allProducts = Product::active()
            ->with('category')
            ->orderBy('name')
            ->get();

        $rooms = Room::where('status', 'active')->orderBy('name')->get();

        return view('portal.menu', [
            'customer' => $customer,
            'activeDeal' => $activeDeal,
            'categories' => $categories,
            'allProducts' => $allProducts,
            'rooms' => $rooms,
        ]);
    }

    public function storeOrder(Request $request)
    {
        $customer = $this->currentCustomer();

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'room_id' => 'nullable|exists:rooms,id',
            'table_or_room_name' => 'nullable|string|max:100',
            'customer_notes' => 'nullable|string|max:500',
        ], [
            'items.required' => 'يرجى اختيار صنف واحد على الأقل.',
            'items.min' => 'يرجى اختيار صنف واحد على الأقل.',
        ]);

        // Active deal if any
        $activeDeal = Deal::where('customer_id', $customer->id)
            ->where('status', 'open')
            ->latest('started_at')
            ->first();

        $roomId = $request->room_id ?: ($activeDeal ? $activeDeal->room_id : null);
        $location = $request->table_or_room_name;
        if (empty($location) && $activeDeal && $activeDeal->room) {
            $location = $activeDeal->room->name;
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'order_number' => Order::generateNumber(),
                'customer_id' => $customer->id,
                'deal_id' => $activeDeal ? $activeDeal->id : null,
                'booking_id' => $activeDeal ? $activeDeal->booking_id : null,
                'source' => 'portal',
                'room_id' => $roomId,
                'table_or_room_name' => $location ?: 'طلب خارجي/مباشر',
                'fulfillment_status' => 'pending',
                'customer_notes' => $request->customer_notes,
                'subtotal' => 0,
                'discount' => 0,
                'tax' => 0,
                'total' => 0,
                'paid_amount' => 0,
                'remaining_amount' => 0,
                'status' => 'open',
            ]);

            $subtotal = 0;
            foreach ($request->items as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);
                $qty = (int) $itemData['quantity'];
                $itemPrice = (float) $product->selling_price;
                $itemTotal = $itemPrice * $qty;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'item_type' => 'product',
                    'name' => $product->name,
                    'quantity' => $qty,
                    'unit_price' => $itemPrice,
                    'total' => $itemTotal,
                    'metadata' => isset($itemData['notes']) ? ['notes' => $itemData['notes']] : null,
                ]);

                $subtotal += $itemTotal;
            }

            $order->update([
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'remaining_amount' => $subtotal,
            ]);

            DB::commit();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم إرسال طلبك إلى الكاشير بنجاح! جاري تحضيره ',
                    'order_id' => $order->id,
                ]);
            }

            return redirect()->route('portal.orders')->with('success', 'تم إرسال طلبك بنجاح! جاري تحضيره لك.');
        } catch (\Throwable $e) {
            DB::rollBack();
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'حدث خطأ أثناء إرسال الطلب: '.$e->getMessage()], 500);
            }

            return back()->with('error', 'حدث خطأ أثناء إرسال الطلب.');
        }
    }

    public function orders()
    {
        $customer = $this->currentCustomer();

        $activeOrders = Order::where('customer_id', $customer->id)
            ->whereIn('fulfillment_status', ['pending', 'preparing'])
            ->with(['items.product', 'deal.room'])
            ->latest('id')
            ->get();

        $pastOrders = Order::where('customer_id', $customer->id)
            ->whereIn('fulfillment_status', ['delivered', 'cancelled'])
            ->with(['items.product', 'deal.room'])
            ->latest('id')
            ->paginate(10);

        return view('portal.orders', [
            'customer' => $customer,
            'activeOrders' => $activeOrders,
            'pastOrders' => $pastOrders,
        ]);
    }

    public function loyalty()
    {
        $customer = $this->currentCustomer();
        $loyalty = $customer->loyalty_status;

        $referralDiscount = Setting::get('affiliate_discount_value', 20);
        $referralType = Setting::get('affiliate_discount_type', 'percentage');
        $referralsCount = $customer->referrals()->count();

        // History of deals for loyalty verification
        $dealsHistory = $customer->deals()
            ->where('status', 'closed')
            ->latest('started_at')
            ->take(10)
            ->get();

        return view('portal.loyalty', [
            'customer' => $customer,
            'loyalty' => $loyalty,
            'referralDiscount' => $referralDiscount,
            'referralType' => $referralType,
            'referralsCount' => $referralsCount,
            'dealsHistory' => $dealsHistory,
        ]);
    }

    public function profile()
    {
        $customer = $this->currentCustomer();

        $totalSpent = Order::where('customer_id', $customer->id)->where('status', 'paid')->sum('total');
        $totalVisits = $customer->deals()->where('status', 'closed')->count();

        return view('portal.profile', [
            'customer' => $customer,
            'totalSpent' => $totalSpent,
            'totalVisits' => $totalVisits,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $customer = $this->currentCustomer();

        $request->validate([
            'full_name' => 'required|string|max:150',
            'email' => 'nullable|email|max:150|unique:customers,email,'.$customer->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $data = [
            'full_name' => trim($request->full_name),
            'email' => $request->email ? trim($request->email) : null,
        ];

        if (! empty($request->password)) {
            $data['password'] = $request->password;
        }

        $customer->update($data);

        return back()->with('success', 'تم تحديث بيانات حسابك بنجاح.');
    }

    public function submitFeedback(Request $request)
    {
        $customer = $this->currentCustomer();
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        CustomerFeedback::create([
            'customer_id' => $customer->id,
            'type' => 'rating',
            'rating' => (int) $request->rating,
            'comment' => $request->comment,
            'status' => 'pending',
        ]);

        return response()->json(['success' => true, 'message' => 'شكراً لك! تم استلام تقييمك بنجاح.']);
    }

    public function reportProblem(Request $request)
    {
        $customer = $this->currentCustomer();
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        CustomerFeedback::create([
            'customer_id' => $customer->id,
            'type' => 'problem',
            'rating' => null,
            'comment' => $request->comment,
            'status' => 'pending',
        ]);

        // Also schedule high priority Cashier alert for instant response!
        CashierAlertSchedule::create([
            'title' => '🚨 بلاغ عن مشكلة من عميل: '.($customer->full_name ?: $customer->name),
            'description' => $request->comment,
            'scheduled_at' => now(),
            'status' => 'pending',
        ]);

        return response()->json(['success' => true, 'message' => 'تم إرسال بلاغ المشكلة للكاشير وإدارة المكان وسنقوم بالمعالجة فوراً.']);
    }
}
