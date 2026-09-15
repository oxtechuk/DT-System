<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductMenuSeeder extends Seeder
{
    public function run(): void
    {
        $cats = [
            'مشروبات ساخنة' => 'hot_drinks',
            'مشروبات باردة' => 'cold_drinks',
            'سناكس ومأكولات' => 'snacks',
            'خدمات وطباعة' => 'services'
        ];

        foreach ($cats as $name => $code) {
            ProductCategory::firstOrCreate(['code' => $code], ['name' => $name, 'active' => true]);
        }

        $hotCat = ProductCategory::where('code', 'hot_drinks')->value('id');
        $coldCat = ProductCategory::where('code', 'cold_drinks')->value('id');
        $snackCat = ProductCategory::where('code', 'snacks')->value('id');
        $servCat = ProductCategory::where('code', 'services')->value('id');

        $products = [
            ['name' => 'قهوة تركي مظبوط', 'category_id' => $hotCat, 'selling_price' => 25, 'purchase_price' => 10, 'sku' => 'HOT-01'],
            ['name' => 'قهوة فرنساوي بالحليب', 'category_id' => $hotCat, 'selling_price' => 35, 'purchase_price' => 15, 'sku' => 'HOT-02'],
            ['name' => 'إسبريسو دبل', 'category_id' => $hotCat, 'selling_price' => 45, 'purchase_price' => 18, 'sku' => 'HOT-03'],
            ['name' => 'كابتشينو فوم', 'category_id' => $hotCat, 'selling_price' => 45, 'purchase_price' => 18, 'sku' => 'HOT-04'],
            ['name' => 'كافيه لاتيه', 'category_id' => $hotCat, 'selling_price' => 50, 'purchase_price' => 20, 'sku' => 'HOT-05'],
            ['name' => 'شاي أحمر بالنعناع', 'category_id' => $hotCat, 'selling_price' => 15, 'purchase_price' => 5, 'sku' => 'HOT-06'],
            ['name' => 'شاي أخضر ياسمين', 'category_id' => $hotCat, 'selling_price' => 20, 'purchase_price' => 7, 'sku' => 'HOT-07'],
            ['name' => 'آيس لاتيه كراميل', 'category_id' => $coldCat, 'selling_price' => 55, 'purchase_price' => 22, 'sku' => 'COLD-01'],
            ['name' => 'آيس موكا شوكولاتة', 'category_id' => $coldCat, 'selling_price' => 60, 'purchase_price' => 25, 'sku' => 'COLD-02'],
            ['name' => 'عصير برتقال فريش', 'category_id' => $coldCat, 'selling_price' => 35, 'purchase_price' => 15, 'sku' => 'COLD-03'],
            ['name' => 'كانز بيبسي بارد', 'category_id' => $coldCat, 'selling_price' => 20, 'purchase_price' => 13, 'sku' => 'COLD-04'],
            ['name' => 'مياه معدنية صغيرة', 'category_id' => $coldCat, 'selling_price' => 10, 'purchase_price' => 5, 'sku' => 'COLD-05'],
            ['name' => 'كرواسون زبدة وشوكولاتة', 'category_id' => $snackCat, 'selling_price' => 30, 'purchase_price' => 16, 'sku' => 'SNK-01'],
            ['name' => 'ساندوتش تركي وجبن', 'category_id' => $snackCat, 'selling_price' => 45, 'purchase_price' => 25, 'sku' => 'SNK-02'],
            ['name' => 'شيبسي عائلي متنوع', 'category_id' => $snackCat, 'selling_price' => 20, 'purchase_price' => 14, 'sku' => 'SNK-03'],
            ['name' => 'طباعة وتصوير أوراق (ورقة)', 'category_id' => $servCat, 'selling_price' => 5, 'purchase_price' => 1, 'sku' => 'SRV-01'],
        ];

        foreach ($products as $p) {
            Product::updateOrCreate(['sku' => $p['sku']], array_merge($p, ['active' => true, 'unit' => 'piece']));
        }
    }
}
