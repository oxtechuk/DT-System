<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Services\Customer\CustomerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct(
        private CustomerService $customerService
    ) {}

    /**
     * GET /api/v1/customers
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('customers.view');

        $customers = Customer::query()
            ->when($request->search, fn($q, $s) => $q->where('full_name', 'like', "%{$s}%")
                ->orWhere('phone', 'like', "%{$s}%"))
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->when($request->type, fn($q, $t) => $q->where('customer_type', $t))
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($customers);
    }

    /**
     * POST /api/v1/customers
     */
    public function store(Request $request): JsonResponse
    {
        $this->authorize('customers.create');

        $data = $request->validate([
            'full_name'     => 'required|string|max:255',
            'phone'         => 'required|string|max:30|unique:customers,phone',
            'email'         => 'nullable|email|unique:customers,email',
            'customer_type' => 'nullable|in:registered,guest',
            'source'        => 'nullable|string|max:100',
            'notes'         => 'nullable|string',
        ]);

        $customer = $this->customerService->create($data);

        return response()->json($customer, 201);
    }

    /**
     * GET /api/v1/customers/{customer}
     */
    public function show(Customer $customer): JsonResponse
    {
        $this->authorize('customers.view');

        $customer->load(['deals' => fn($q) => $q->latest()->limit(10), 'orders' => fn($q) => $q->latest()->limit(10)]);

        return response()->json($customer);
    }

    /**
     * PUT /api/v1/customers/{customer}
     */
    public function update(Request $request, Customer $customer): JsonResponse
    {
        $this->authorize('customers.edit');

        $data = $request->validate([
            'full_name'     => 'sometimes|required|string|max:255',
            'phone'         => "sometimes|required|string|max:30|unique:customers,phone,{$customer->id}",
            'email'         => "nullable|email|unique:customers,email,{$customer->id}",
            'customer_type' => 'nullable|in:registered,guest',
            'source'        => 'nullable|string|max:100',
            'status'        => 'nullable|in:active,inactive,blocked',
            'notes'         => 'nullable|string',
        ]);

        $customer = $this->customerService->update($customer, $data);

        return response()->json($customer);
    }
}
