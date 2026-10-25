<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Deal;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomerPortalApiController extends Controller
{
    /**
     * Mobile Dashboard summary for authenticated customer.
     */
    public function dashboard(Request $request): JsonResponse
    {
        $customer = $request->user();

        if (! $customer instanceof Customer) {
            return response()->json([
                'success' => false,
                'message' => 'هذه الواجهة مخصصة لحسابات العملاء فقط.',
            ], 403);
        }

        // Active Session / Deal
        $activeDeal = Deal::with('room')
            ->where('customer_id', $customer->id)
            ->where('status', 'active')
            ->latest('started_at')
            ->first();

        $activeDealData = null;
        if ($activeDeal) {
            $started = $activeDeal->started_at;
            $durationMinutes = $started ? (int) $started->diffInMinutes(now()) : 0;
            $hours = intdiv($durationMinutes, 60);
            $mins = $durationMinutes % 60;

            $activeDealData = [
                'deal_id' => $activeDeal->id,
                'deal_number' => $activeDeal->deal_number,
                'room_id' => $activeDeal->room_id,
                'room_name' => $activeDeal->room?->name ?? 'المساحة العامة',
                'started_at' => $started?->toIso8601String(),
                'duration_formatted' => "{$hours} س {$mins} د",
                'current_total' => (float) $activeDeal->total_amount,
            ];
        }

        // Recent Orders
        $recentOrders = Order::with('items.product')
            ->where('customer_id', $customer->id)
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($order) => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'total' => (float) $order->total,
                'fulfillment_status' => $order->fulfillment_status,
                'location' => $order->table_or_room_name,
                'created_at' => $order->created_at?->toIso8601String(),
                'items_count' => $order->items->count(),
            ]);

        $isTeacher = $customer->isTeacher();
        $teacher = $isTeacher ? $customer->teacher() : null;

        return response()->json([
            'success' => true,
            'data' => [
                'customer' => [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                    'email' => $customer->email,
                    'referral_code' => $customer->referral_code,
                    'qr_code' => 'DDT-CUST-'.$customer->id.'-'.($customer->referral_code ?: $customer->id),
                    'is_teacher' => $isTeacher,
                    'teacher_code' => $teacher?->code,
                ],
                'loyalty' => $customer->loyalty_status,
                'active_session' => $activeDealData,
                'recent_orders' => $recentOrders,
            ],
        ]);
    }

    /**
     * Get menu categories.
     */
    public function categories(): JsonResponse
    {
        $categories = ProductCategory::withCount(['products' => function ($q) {
            $q->where('active', true);
        }])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(fn ($cat) => [
                'id' => $cat->id,
                'name' => $cat->name,
                'code' => $cat->code,
                'products_count' => $cat->products_count,
            ]);

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    /**
     * Get menu products.
     */
    public function products(Request $request): JsonResponse
    {
        $categoryId = $request->query('category_id');
        $search = trim((string) $request->query('q', ''));

        $query = Product::with('category')
            ->where('active', true)
            ->when($categoryId, function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            })
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->orderBy('name');

        $products = $query->get()->map(fn ($prod) => [
            'id' => $prod->id,
            'category_id' => $prod->category_id,
            'category_name' => $prod->category?->name ?? 'عام',
            'name' => $prod->name,
            'description' => $prod->description,
            'price' => (float) $prod->selling_price,
            'unit' => $prod->unit,
        ]);

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    /**
     * Create a new mobile cafe order.
     */
    public function createOrder(Request $request): JsonResponse
    {
        $customer = $request->user();

        if (! $customer instanceof Customer) {
            return response()->json([
                'success' => false,
                'message' => 'الطلب متاح للعملاء فقط.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:50'],
            'room_id' => ['nullable', 'exists:rooms,id'],
            'table_or_room_name' => ['nullable', 'string', 'max:100'],
            'customer_notes' => ['nullable', 'string', 'max:500'],
        ], [
            'items.required' => 'سلة الطلبات فارغة',
            'items.*.product_id.exists' => 'أحد المنتجات المختارة غير متوفر',
            'items.*.quantity.min' => 'الكمية يجب أن تكون 1 على الأقل',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $activeDeal = Deal::with('room')
            ->where('customer_id', $customer->id)
            ->where('status', 'active')
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
                'table_or_room_name' => $location ?: 'طلب مباشر من التطبيق',
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
            $orderItems = [];

            foreach ($request->items as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);
                $qty = (int) $itemData['quantity'];
                $itemPrice = (float) $product->selling_price;
                $itemTotal = $itemPrice * $qty;

                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'item_type' => 'product',
                    'item_name' => $product->name,
                    'unit_price' => $itemPrice,
                    'quantity' => $qty,
                    'total_price' => $itemTotal,
                ]);

                $subtotal += $itemTotal;
                $orderItems[] = [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'unit_price' => $itemPrice,
                    'quantity' => $qty,
                    'total' => $itemTotal,
                ];
            }

            $order->update([
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'remaining_amount' => $subtotal,
            ]);

            // Sync with active deal if linked
            if ($activeDeal) {
                $activeDeal->recalculateTotals();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم استلام طلبك بنجاح وجارٍ تحضيره فوراً!',
                'data' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'total' => (float) $order->total,
                    'location' => $order->table_or_room_name,
                    'fulfillment_status' => $order->fulfillment_status,
                    'items' => $orderItems,
                ],
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ الطلب: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get customer orders history.
     */
    public function myOrders(Request $request): JsonResponse
    {
        $customer = $request->user();

        if (! $customer instanceof Customer) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح',
            ], 403);
        }

        $orders = Order::with('items.product')
            ->where('customer_id', $customer->id)
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }

    /**
     * Teacher portal analytics and students list for mobile.
     */
    public function teacherPortal(Request $request): JsonResponse
    {
        $customer = $request->user();

        if (! $customer instanceof Customer || ! $customer->isTeacher()) {
            return response()->json([
                'success' => false,
                'message' => 'هذه الصفحة مخصصة للمدرسين المعتمدين فقط.',
            ], 403);
        }

        $teacher = $customer->teacher();
        $now = now();

        $stats = [
            'total_students' => $teacher->totalVisits(),
            'month_students' => $teacher->visits()->whereMonth('visited_at', $now->month)->whereYear('visited_at', $now->year)->count(),
            'today_students' => $teacher->visits()->whereDate('visited_at', $now->toDateString())->count(),
            'total_earnings' => (float) $teacher->totalEarnings(),
            'pending_commission' => (float) $teacher->pendingCommission(),
            'commission_rate' => (float) $teacher->commission_rate,
            'referral_code' => $teacher->code,
            'referral_link' => url('/?ref='.$teacher->code),
        ];

        $visits = $teacher->visits()
            ->with('customer')
            ->latest('visited_at')
            ->paginate(20)
            ->through(fn ($v) => [
                'id' => $v->id,
                'student_name' => $v->visitor_name,
                'student_phone' => $v->visitor_phone,
                'visited_at' => $v->visited_at?->format('Y-m-d H:i'),
                'commission_amount' => (float) ($v->commission?->commission_amount ?? 0),
                'status' => $v->commission?->status ?? 'pending',
            ]);

        return response()->json([
            'success' => true,
            'data' => [
                'teacher' => [
                    'id' => $teacher->id,
                    'name' => $teacher->name,
                    'code' => $teacher->code,
                ],
                'stats' => $stats,
                'visits' => $visits,
            ],
        ]);
    }

    /**
     * Customer loyalty stamps and rewards.
     */
    public function loyalty(Request $request): JsonResponse
    {
        $customer = $request->user();

        if (! $customer instanceof Customer) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'loyalty' => $customer->loyalty_status,
                'terms' => 'احصل على مشروب مجاني عند إتمام 5 زيارات لمساحة العمل DDT.',
            ],
        ]);
    }
}
