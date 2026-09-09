<?php

namespace App\Services\Customer;

use App\Models\AuditLog;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class CustomerService
{
    /**
     * Create a new customer.
     */
    public function create(array $data): Customer
    {
        return DB::transaction(function () use ($data) {
            $data['customer_type'] ??= 'registered';
            $data['status'] ??= 'active';

            $customer = Customer::create($data);

            AuditLog::record('customer.created', $customer, null, [
                'full_name'     => $customer->full_name,
                'phone'         => $customer->phone,
                'customer_type' => $customer->customer_type,
            ]);

            return $customer;
        });
    }

    /**
     * Update an existing customer.
     */
    public function update(Customer $customer, array $data): Customer
    {
        return DB::transaction(function () use ($customer, $data) {
            $oldValues = $customer->only(array_keys($data));
            $customer->update($data);

            AuditLog::record('customer.updated', $customer, $oldValues, $data);

            return $customer;
        });
    }
}
