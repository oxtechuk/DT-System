<?php

namespace App\Observers;

use App\Models\Customer;
use App\Models\Setting;
use App\Services\HubSpotService;

class CustomerHubSpotObserver
{
 public function created(Customer $customer): void
 {
 if (Setting::get('hubspot_auto_sync', true)) {
 app(HubSpotService::class)->syncCustomer($customer);
 }
 }

 public function updated(Customer $customer): void
 {
 if (Setting::get('hubspot_auto_sync', true) && 
 $customer->isDirty(['full_name', 'phone', 'email'])) {
 app(HubSpotService::class)->syncCustomer($customer);
 }
 }
}
