<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Deal;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Room;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
 /**
 * Customers Directory
 */
 public function customers(Request $request)
 {
 $search = $request->query('search');
 $query = Customer::query()->withCount('deals');

 if ($search) {
 $query->where(function ($q) use ($search) {
 $q->where('name', 'like', "%{$search}%")
 ->orWhere('phone', 'like', "%{$search}%")
 ->orWhere('email', 'like', "%{$search}%");
 });
 }

 $customers = $query->latest()->paginate(15)->withQueryString();
 $totalCustomers = Customer::count();
 $activeCustomers = Customer::where('status', 'active')->count();

 return view('customers.index', compact('customers', 'totalCustomers', 'activeCustomers', 'search'));
 }

 /**
 * Store new customer
 */
 public function storeCustomer(Request $request)
 {
 $data = $request->validate([
 'name' => 'required|string|max:255',
 'phone' => 'required|string|max:50|unique:customers,phone',
 'email' => 'nullable|email|max:255',
 'notes' => 'nullable|string',
 ]);

 Customer::create($data);
 return redirect()->back()->with('success', 'تم تسجيل العميل بنجاح.');
 }

 /**
 * Rooms & Spaces
 */
 public function rooms()
 {
 $rooms = Room::with('activeDeals.customer')->get();
 $totalCapacity = $rooms->sum('capacity');
 $availableRooms = $rooms->filter(fn($r) => $r->is_available)->count();

 return view('rooms.index', compact('rooms', 'totalCapacity', 'availableRooms'));
 }

 /**
 * Bookings Calendar / List
 */
 public function bookings(Request $request)
 {
 $bookings = Booking::with(['customer', 'room'])->latest('start_time')->paginate(15);
 $rooms = Room::where('status', 'available')->get();
 $customers = Customer::where('status', 'active')->get();

 return view('bookings.index', compact('bookings', 'rooms', 'customers'));
 }

 /**
 * Store Booking
 */
 public function storeBooking(Request $request)
 {
 $data = $request->validate([
 'customer_id' => 'required|exists:customers,id',
 'room_id' => 'required|exists:rooms,id',
 'start_time' => 'required|date',
 'end_time' => 'required|date|after:start_time',
 'attendees_count' => 'nullable|integer|min:1',
 'notes' => 'nullable|string',
 ]);

 $data['status'] = 'confirmed';
 Booking::create($data);

 return redirect()->back()->with('success', 'تم تسجيل الحجز بنجاح.');
 }

 /**
 * Active Deals / Sessions
 */
 public function activeDeals()
 {
 $deals = Deal::open()
 ->with(['customer', 'room', 'workspaceType', 'order.items.product'])
 ->latest('started_at')
 ->get();

 $rooms = Room::where('status', 'available')->get();
 $customers = Customer::where('status', 'active')->get();

 return view('deals.active', compact('deals', 'rooms', 'customers'));
 }

 /**
 * Products & Cafe
 */
 public function products()
 {
 $products = Product::latest()->paginate(20);
 $totalProducts = Product::count();
 $lowStockCount = Product::where('stock_trackable', true)->where('stock_quantity', '<=', 5)->count();

 return view('products.index', compact('products', 'totalProducts', 'lowStockCount'));
 }

 /**
 * Store Product
 */
 public function storeProduct(Request $request)
 {
 $data = $request->validate([
 'name' => 'required|string|max:255',
 'price' => 'required|numeric|min:0',
 'cost' => 'nullable|numeric|min:0',
 'stock_quantity' => 'nullable|integer|min:0',
 'category' => 'nullable|string',
 ]);

 $data['is_active'] = true;
 $data['stock_trackable'] = true;
 Product::create($data);

 return redirect()->back()->with('success', 'تم إضافة المنتج بنجاح.');
 }

 /**
 * Inventory movements
 */
 public function inventory()
 {
 $products = Product::where('stock_trackable', true)->orderBy('stock_quantity', 'asc')->get();
 $lowStock = $products->filter(fn($p) => $p->stock_quantity <= 5);

 return view('inventory.index', compact('products', 'lowStock'));
 }

 /**
 * Payments list
 */
 public function payments(Request $request)
 {
 $method = $request->query('method');
 $query = Payment::with(['deal.customer', 'deal.room'])->latest('paid_at');

 if ($method && in_array($method, ['cash', 'instapay', 'wallet', 'card'])) {
 $query->where('method', $method);
 }

 $payments = $query->paginate(20)->withQueryString();
 $totalPaid = Payment::where('type', 'income')->sum('amount');
 $cashTotal = Payment::where('type', 'income')->where('method', 'cash')->sum('amount');
 $instapayTotal = Payment::where('type', 'income')->where('method', 'instapay')->sum('amount');
 $walletTotal = Payment::where('type', 'income')->where('method', 'wallet')->sum('amount');

 return view('payments.index', compact('payments', 'totalPaid', 'cashTotal', 'instapayTotal', 'walletTotal', 'method'));
 }
}
