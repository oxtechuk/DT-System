<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Deal;
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

        return view('cashier.index', [
            'rooms' => $rooms,
            'products' => $products,
            'customers' => $customers,
            'workspaceTypes' => $workspaceTypes,
            'currentShift' => $currentShift,
            'activeDeals' => $activeDeals,
            'todayCashTotal' => (float) $todayCashTotal,
        ]);
    }
}
