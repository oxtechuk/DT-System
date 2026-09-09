<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,  // must be first
            WorkspaceTypeSeeder::class,
            PricingRuleSeeder::class,
            RoomSeeder::class,
            ProductCategorySeeder::class,
            AdminUserSeeder::class,            // must be after roles
        ]);
    }
}
