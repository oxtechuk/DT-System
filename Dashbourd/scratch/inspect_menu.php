<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\ProductCategory;

echo "=== CATEGORIES ===\n";
foreach (ProductCategory::all() as $c) {
    echo "ID: {$c->id} | Name: {$c->name} | Code: {$c->code} | Active: {$c->active}\n";
}

echo "\n=== PRODUCTS ===\n";
foreach (Product::all() as $p) {
    echo "ID: {$p->id} | Name: {$p->name} | CatID: {$p->category_id} | Price: {$p->selling_price} | Active: {$p->active}\n";
}
