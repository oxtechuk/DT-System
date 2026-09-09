<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Drinks',  'code' => 'drinks',  'active' => true],
            ['name' => 'Snacks',  'code' => 'snacks',  'active' => true],
            ['name' => 'Coffee',  'code' => 'coffee',  'active' => true],
            ['name' => 'Other',   'code' => 'other',   'active' => true],
        ];

        foreach ($categories as $cat) {
            ProductCategory::firstOrCreate(['code' => $cat['code']], $cat);
        }

        $this->command->info('Product categories seeded.');
    }
}
