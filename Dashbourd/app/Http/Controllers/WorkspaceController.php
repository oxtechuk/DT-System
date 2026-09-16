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
 * Store a new room
 */
 public function storeRoom(Request $request)
 {
 $data = $request->validate([
 'name' => 'required|string|max:255',
 'code' => 'nullable|string|max:50|unique:rooms,code',
 'capacity' => 'required|integer|min:1',
 'status' => 'required|in:active,maintenance,inactive',
 'description' => 'nullable|string|max:1000',
 ]);

 if (empty($data['code'])) {
 $data['code'] = 'R-' . strtoupper(substr(md5(time() . rand(100, 999)), 0, 4));
 }

 Room::create($data);

 return redirect()->back()->with('success', 'تمت إضافة الغرفة بنجاح.');
 }

 /**
 * Update an existing room
 */
 public function updateRoom(Request $request, Room $room)
 {
 $data = $request->validate([
 'name' => 'required|string|max:255',
 'code' => 'nullable|string|max:50|unique:rooms,code,' . $room->id,
 'capacity' => 'required|integer|min:1',
 'status' => 'required|in:active,maintenance,inactive',
 'description' => 'nullable|string|max:1000',
 ]);

 $room->update($data);

 return redirect()->back()->with('success', 'تم تعديل بيانات الغرفة بنجاح.');
 }

 /**
 * Delete a room
 */
 public function destroyRoom(Room $room)
 {
 if ($room->activeDeals()->exists()) {
 return redirect()->back()->with('error', 'لا يمكن حذف الغرفة لوجود جلسة نشطة بداخلها حالياً. يرجى إنهاء الحساب من الكاشير أولاً.');
 }

 $room->delete();

 return redirect()->back()->with('success', 'تم حذف الغرفة بنجاح.');
 }

    /**
     * Bookings Calendar / List with Room Filters
     */
    public function bookings(Request $request)
    {
        $roomId = $request->query('room_id');
        $status = $request->query('status');
        $date = $request->query('date');
        $search = $request->query('search');

        $query = Booking::with(['customer', 'room', 'workspaceType'])->orderBy('start_at', 'desc');

        if ($roomId) {
            $query->where('room_id', $roomId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($date) {
            $query->whereDate('start_at', $date);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('booking_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('full_name', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        $bookings = $query->paginate(15)->withQueryString();
        $rooms = Room::orderBy('name')->get();
        $customers = Customer::where('status', 'active')->orderBy('full_name')->get();
        $workspaceTypes = \App\Models\WorkspaceType::all();

        // Calculate bookings count today per room
        $todayBookingsCount = Booking::whereDate('start_at', today())
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->count();

        $activeRoomsBookings = Booking::whereDate('start_at', today())
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->selectRaw('room_id, count(*) as count')
            ->groupBy('room_id')
            ->pluck('count', 'room_id');

        return view('bookings.index', compact(
            'bookings',
            'rooms',
            'customers',
            'workspaceTypes',
            'roomId',
            'status',
            'date',
            'search',
            'todayBookingsCount',
            'activeRoomsBookings'
        ));
    }

    /**
     * Store Booking for a Room
     */
    public function storeBooking(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'room_id' => 'required|exists:rooms,id',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
            'workspace_type_id' => 'nullable|exists:workspace_types,id',
            'notes' => 'nullable|string',
        ]);

        if (empty($data['workspace_type_id'])) {
            $defaultType = \App\Models\WorkspaceType::first();
            $data['workspace_type_id'] = $defaultType ? $defaultType->id : 1;
        }

        $data['booking_number'] = Booking::generateNumber();
        $data['status'] = 'confirmed';
        $data['source'] = 'admin';
        $data['created_by'] = auth()->id();

        // Check for room schedule conflicts
        $conflict = Booking::where('room_id', $data['room_id'])
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->where(function ($q) use ($data) {
                $q->whereBetween('start_at', [$data['start_at'], $data['end_at']])
                  ->orWhereBetween('end_at', [$data['start_at'], $data['end_at']])
                  ->orWhere(function ($sub) use ($data) {
                      $sub->where('start_at', '<=', $data['start_at'])
                          ->where('end_at', '>=', $data['end_at']);
                  });
            })->exists();

        $booking = Booking::create($data);

        $msg = "تم تسجيل حجز الغرفة بنجاح برقم ({$booking->booking_number}).";
        if ($conflict) {
            $msg .= " (تنبيه: يوجد حجز آخر متزامن أو قريب في نفس القاعة، يرجى مراجعة الجدول).";
        }

        return redirect()->back()->with('success', $msg);
    }

    /**
     * Update Booking
     */
    public function updateBooking(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'room_id' => 'required|exists:rooms,id',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
            'status' => 'required|in:pending,confirmed,checked_in,completed,cancelled,no_show',
            'notes' => 'nullable|string',
        ]);

        $booking->update($data);

        return redirect()->back()->with('success', "تم تحديث بيانات الحجز ({$booking->booking_number}) بنجاح.");
    }

    /**
     * Delete / Cancel Booking
     */
    public function destroyBooking(Booking $booking)
    {
        $bookingNumber = $booking->booking_number;
        $booking->delete();

        return redirect()->back()->with('success', "تم حذف الحجز ({$bookingNumber}) بنجاح.");
    }

    /**
     * Check-in Booking: Start an active deal/session in the room
     */
    public function checkInBooking(Booking $booking, \App\Services\DealService $dealService)
    {
        // Check if room is available or occupied
        $room = $booking->room;

        $deal = $dealService->start([
            'customer_id' => $booking->customer_id,
            'room_id' => $booking->room_id,
            'workspace_type_id' => $booking->workspace_type_id ?? 1,
            'booking_id' => $booking->id,
            'started_at' => now(),
            'notes' => 'بدء من حجز رقم: ' . $booking->booking_number . ($booking->notes ? ' - ' . $booking->notes : ''),
        ]);

        $booking->update(['status' => 'checked_in']);

        return redirect()->route('cashier', ['deal_id' => $deal->id])
            ->with('success', "تم تسجيل حضور العميل وبدء الجلسة في غرفة ({$room?->name}) وفتح الفاتورة بنجاح.");
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
        $products = Product::with(['category', 'ingredients.rawMaterial'])->latest()->paginate(20);
        $totalProducts = Product::count();
        $categories = \App\Models\ProductCategory::where('active', true)->orderBy('name')->get();
        $rawMaterials = \App\Models\RawMaterial::where('active', true)->orderBy('name')->get();
        $totalCost = Product::all()->sum(fn($p) => $p->cost);

        return view('products.index', compact('products', 'totalProducts', 'categories', 'rawMaterials', 'totalCost'));
    }

    /**
     * Store Product
     */
    public function storeProduct(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'selling_price' => 'required|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'category_id' => 'nullable|exists:product_categories,id',
            'unit' => 'nullable|string|max:50',
            'active' => 'nullable|boolean',
            'description' => 'nullable|string|max:1000',
        ]);

        $data['active'] = $request->has('active') ? (bool) $request->active : true;
        $data['unit'] = $data['unit'] ?? 'piece';
        $data['purchase_price'] = $data['purchase_price'] ?? 0;

        Product::create($data);

        return redirect()->back()->with('success', 'تمت إضافة المنتج بنجاح.');
    }

    /**
     * Update Product
     */
    public function updateProduct(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'selling_price' => 'required|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'category_id' => 'nullable|exists:product_categories,id',
            'unit' => 'nullable|string|max:50',
            'active' => 'nullable|boolean',
            'description' => 'nullable|string|max:1000',
        ]);

        $data['active'] = $request->has('active') ? (bool) $request->active : false;
        $product->update($data);

        return redirect()->back()->with('success', 'تم تعديل بيانات المنتج وسعر التكلفة بنجاح.');
    }

    /**
     * Delete Product
     */
    public function destroyProduct(Product $product)
    {
        $product->delete();
        return redirect()->back()->with('success', 'تم حذف المنتج بنجاح.');
    }

    /**
     * Save Product Ingredients / Recipe
     */
    public function saveProductIngredients(Request $request, Product $product)
    {
        $request->validate([
            'ingredients' => 'nullable|array',
            'ingredients.*.raw_material_id' => 'required|exists:raw_materials,id',
            'ingredients.*.quantity' => 'required|numeric|min:0.01',
        ]);

        \App\Models\ProductIngredient::where('product_id', $product->id)->delete();

        if ($request->has('ingredients')) {
            foreach ($request->ingredients as $item) {
                if (!empty($item['raw_material_id']) && !empty($item['quantity'])) {
                    \App\Models\ProductIngredient::create([
                        'product_id' => $product->id,
                        'raw_material_id' => $item['raw_material_id'],
                        'quantity' => $item['quantity'],
                    ]);
                }
            }
        }

        // Auto update product cost from recipe
        $recipeCost = $product->calculateRecipeCost();
        if ($recipeCost > 0) {
            $product->purchase_price = $recipeCost;
            $product->save();
        }

        return redirect()->back()->with('success', 'تم حفظ مكونات وخامات الصنف واحتساب التكلفة تلقائياً بنجاح.');
    }

    /**
     * Store Raw Material
     */
    public function storeRawMaterial(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'current_stock' => 'required|numeric|min:0',
            'unit_cost' => 'required|numeric|min:0',
            'minimum_stock' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        \App\Models\RawMaterial::create($data);
        return redirect()->back()->with('success', 'تمت إضافة الخامة إلى المستودع بنجاح.');
    }

    /**
     * Update Raw Material
     */
    public function updateRawMaterial(Request $request, \App\Models\RawMaterial $rawMaterial)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'current_stock' => 'required|numeric|min:0',
            'unit_cost' => 'required|numeric|min:0',
            'minimum_stock' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $rawMaterial->update($data);
        return redirect()->back()->with('success', 'تم تعديل بيانات وتكلفة الخامة بنجاح.');
    }

    /**
     * Add Stock to Raw Material
     */
    public function addStockRawMaterial(Request $request, \App\Models\RawMaterial $rawMaterial)
    {
        $request->validate([
            'added_quantity' => 'required|numeric|min:0.01',
        ]);

        $rawMaterial->increment('current_stock', $request->added_quantity);
        return redirect()->back()->with('success', "تمت إضافة {$request->added_quantity} إلى رصيد {$rawMaterial->name} بنجاح.");
    }

    /**
     * Delete Raw Material
     */
    public function destroyRawMaterial(\App\Models\RawMaterial $rawMaterial)
    {
        $rawMaterial->delete();
        return redirect()->back()->with('success', 'تم حذف الخامة بنجاح.');
    }

    /**
     * Inventory movements & Raw Materials
     */
    public function inventory()
    {
        $products = Product::where('track_inventory', true)->orWhere('active', true)->get();
        $rawMaterials = \App\Models\RawMaterial::orderBy('name')->get();
        $lowStockMaterials = $rawMaterials->filter(fn($m) => $m->is_low_stock);

        return view('inventory.index', compact('products', 'rawMaterials', 'lowStockMaterials'));
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
