<?php

namespace App\Observers;

use App\Models\Deal;
use App\Models\Setting;
use App\Services\HubSpotService;

class DealHubSpotObserver
{
 public function created(Deal $deal): void
 {
 if (Setting::get('hubspot_auto_sync', true)) {
 app(HubSpotService::class)->syncDeal($deal);
 }
 }

 public function updated(Deal $deal): void
 {
 if (Setting::get('hubspot_auto_sync', true) && 
 $deal->isDirty(['status', 'applied_price', 'ended_at', 'room_id'])) {
 app(HubSpotService::class)->syncDeal($deal);
 }
 }
}
