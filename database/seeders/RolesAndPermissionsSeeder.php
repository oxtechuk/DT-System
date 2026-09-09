<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Customers
            'customers.view', 'customers.create', 'customers.edit', 'customers.delete',

            // Deals
            'deals.view', 'deals.create', 'deals.edit', 'deals.close', 'deals.adjust_time',

            // Orders
            'orders.view', 'orders.create', 'orders.edit', 'orders.close',

            // Payments
            'payments.create', 'payments.refund', 'payments.view',

            // Products
            'products.view', 'products.create', 'products.edit',

            // Inventory (Phase 3)
            'inventory.view', 'inventory.adjust', 'inventory.count',
            'purchases.create', 'purchases.view',

            // Shifts (Phase 4)
            'shifts.open', 'shifts.close', 'shifts.view',

            // Finance (Phase 4)
            'expenses.create', 'expenses.view',
            'receivables.view', 'receivables.create',
            'payables.view', 'payables.create',

            // Reports
            'reports.view',

            // Settings & Admin
            'settings.manage',
            'users.manage',
            'audit_logs.view',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // ── Roles ────────────────────────────────────────────────────────────────

        $owner = Role::firstOrCreate(['name' => 'owner', 'guard_name' => 'web']);
        $owner->syncPermissions(Permission::all()); // owns everything

        $manager = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $manager->syncPermissions([
            'customers.view', 'customers.create', 'customers.edit',
            'deals.view', 'deals.create', 'deals.edit', 'deals.close', 'deals.adjust_time',
            'orders.view', 'orders.create', 'orders.edit', 'orders.close',
            'payments.create', 'payments.view',
            'products.view', 'products.create', 'products.edit',
            'inventory.view', 'inventory.adjust', 'inventory.count',
            'purchases.create', 'purchases.view',
            'shifts.open', 'shifts.close', 'shifts.view',
            'expenses.create', 'expenses.view',
            'reports.view',
            'audit_logs.view',
        ]);

        $reception = Role::firstOrCreate(['name' => 'reception', 'guard_name' => 'web']);
        $reception->syncPermissions([
            'customers.view', 'customers.create', 'customers.edit',
            'deals.view', 'deals.create', 'deals.edit', 'deals.close',
            'orders.view', 'orders.create', 'orders.edit', 'orders.close',
            'payments.create', 'payments.view',
            'products.view',
            'shifts.open', 'shifts.close', 'shifts.view',
        ]);

        $inventory = Role::firstOrCreate(['name' => 'inventory', 'guard_name' => 'web']);
        $inventory->syncPermissions([
            'products.view', 'products.create', 'products.edit',
            'inventory.view', 'inventory.adjust', 'inventory.count',
            'purchases.create', 'purchases.view',
        ]);

        $accountant = Role::firstOrCreate(['name' => 'accountant', 'guard_name' => 'web']);
        $accountant->syncPermissions([
            'payments.view', 'payments.refund',
            'expenses.create', 'expenses.view',
            'receivables.view', 'receivables.create',
            'payables.view', 'payables.create',
            'shifts.view',
            'reports.view',
        ]);

        $this->command->info('Roles and permissions seeded.');
    }
}
