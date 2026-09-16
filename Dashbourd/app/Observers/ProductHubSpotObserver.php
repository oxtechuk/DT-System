<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\Setting;
use App\Services\HubSpotService;

class ProductHubSpotObserver
{
 public function created(Product $product): void
 {
 if (Setting::get('hubspot_auto_sync', true)) {
 app(HubSpotService::class)->syncProduct($product);
 }
 }

 public function updated(Product $product): void
 {
 if (Setting::get('hubspot_auto_sync', true) && 
 $product->isDirty(['name', 'selling_price', 'description', 'sku'])) {
 app(HubSpotService::class)->syncProduct($product);
 }
 }
}
