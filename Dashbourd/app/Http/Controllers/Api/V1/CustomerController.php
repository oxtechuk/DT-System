<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * GET /api/v1/customers
     * List customers with optional search — used by Cashier POS.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Customer::active()
            ->withCount(['deals as active_deals_count' => fn($q) => $q->open()]);

        if ($search = $request->get('search')) {
            $query->search($search);
        }

        $customers = $query
            ->orderBy('full_name')
            ->limit($request->get('limit', 50))
            ->get(['id', 'full_name', 'phone', 'email', 'customer_type', 'status']);

        return response()->json([
            'data'  => $customers->map(fn($c) => [
                'id'           => $c->id,
                'full_name'    => $c->full_name,
                'phone'        => $c->phone,
                'email'        => $c->email,
                'initials'     => $c->initials,
                'type'         => $c->customer_type,
                'active_deals' => $c->active_deals_count,
            ]),
            'total' => $customers->count(),
        ]);
    }

    /**
     * POST /api/v1/customers
     * Quick-create a customer from the Cashier POS.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'full_name'     => 'required|string|max:255',
            'phone'         => 'required|string|max:20|unique:customers,phone',
            'email'         => 'nullable|email|unique:customers,email',
            'customer_type' => 'nullable|in:registered,guest',
            'source'        => 'nullable|string|max:50',
            'notes'         => 'nullable|string',
        ]);

        $validated['customer_type'] ??= 'registered';

        $customer = Customer::create($validated);

        return response()->json([
            'message' => 'Customer created successfully.',
            'data'    => [
                'id'        => $customer->id,
                'full_name' => $customer->full_name,
                'phone'     => $customer->phone,
                'initials'  => $customer->initials,
            ],
        ], 201);
    }

    /**
     * GET /api/v1/customers/{id}
     * Customer details + history.
     */
    public function show(Customer $customer): JsonResponse
    {
        $customer->load([
            'deals' => fn($q) => $q->latest()->limit(10),
            'deals.room',
            'deals.workspaceType',
        ]);

        return response()->json([
            'data' => array_merge($customer->toArray(), [
                'initials' => $customer->initials,
                'active_deals' => $customer->deals->where('status', 'open')->count(),
            ]),
        ]);
    }
}
