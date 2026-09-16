<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Deal;
use App\Models\HubSpotSyncLog;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HubSpotService
{
 protected string $baseUrl = 'https://api.hubapi.com';

 public function getAccessToken(): ?string
 {
 return Setting::get('hubspot_access_token', env('HUBSPOT_ACCESS_TOKEN'));
 }

 public function isEnabled(): bool
 {
 return (bool) Setting::get('hubspot_enabled', false) && !empty($this->getAccessToken());
 }

 public function getPipelineId(): string
 {
 return Setting::get('hubspot_pipeline_id', 'default') ?: 'default';
 }

 public function getStageOpen(): string
 {
 return Setting::get('hubspot_stage_open', 'appointmentscheduled') ?: 'appointmentscheduled';
 }

 public function getStageClosed(): string
 {
 return Setting::get('hubspot_stage_closed', 'closedwon') ?: 'closedwon';
 }

 protected function client()
 {
 $token = $this->getAccessToken();
 return Http::withToken($token)
 ->withHeaders([
 'Accept' => 'application/json',
 'Content-Type' => 'application/json',
 ])
 ->timeout(15);
 }

 // ──────────────────────────────────────────
 // 1. Connection & Health Check
 // ──────────────────────────────────────────

 public function testConnection(): array
 {
 $token = $this->getAccessToken();
 if (empty($token)) {
 return [
 'success' => false,
 'message' => 'لم يتم إدخال كود الوصول الخاص بـ HubSpot (Private App Token).',
 ];
 }

 try {
 // Check account details or contacts endpoint
 $response = $this->client()->get("{$this->baseUrl}/crm/v3/objects/contacts", [
 'limit' => 1,
 ]);

 if ($response->successful()) {
 $this->log('general', null, null, 'test', 'success', null, $response->json());
 return [
 'success' => true,
 'message' => 'تم الاتصال بحسابك على HubSpot بنجاح! الصلاحيات تعمل بشكل سليم ',
 'details' => $response->json(),
 ];
 }

 $errorMessage = $response->json('message') ?? $response->body();
 $this->log('general', null, null, 'test', 'failed', null, null, $errorMessage);

 return [
 'success' => false,
 'message' => 'فشل الاتصال بـ HubSpot: ' . $errorMessage,
 'status' => $response->status(),
 ];
 } catch (\Throwable $e) {
 $this->log('general', null, null, 'test', 'failed', null, null, $e->getMessage());
 return [
 'success' => false,
 'message' => 'خطأ أثناء الاتصال بالخادم: ' . $e->getMessage(),
 ];
 }
 }

 // ──────────────────────────────────────────
 // 2. Contacts (العملاء)
 // ──────────────────────────────────────────

 public function syncCustomer(Customer $customer): ?string
 {
 if (!$this->isEnabled()) {
 return null;
 }

 $names = explode(' ', trim($customer->full_name), 2);
 $firstName = $names[0] ?? 'عميل';
 $lastName = $names[1] ?? 'DT';

 $properties = [
 'firstname' => $firstName,
 'lastname' => $lastName,
 'phone' => $customer->phone,
 ];

 if (!empty($customer->email)) {
 $properties['email'] = $customer->email;
 }

 $payload = ['properties' => $properties];

 try {
 if (!empty($customer->hubspot_contact_id)) {
 // Update existing
 $res = $this->client()->patch("{$this->baseUrl}/crm/v3/objects/contacts/{$customer->hubspot_contact_id}", $payload);
 if ($res->successful()) {
 $customer->update(['hubspot_synced_at' => now()]);
 $this->log('customer', $customer->id, $customer->hubspot_contact_id, 'update', 'success', $payload, $res->json());
 return $customer->hubspot_contact_id;
 }
 }

 // Create new
 $res = $this->client()->post("{$this->baseUrl}/crm/v3/objects/contacts", $payload);

 if ($res->successful()) {
 $contactId = (string) $res->json('id');
 $customer->update([
 'hubspot_contact_id' => $contactId,
 'hubspot_synced_at' => now(),
 ]);
 $this->log('customer', $customer->id, $contactId, 'push', 'success', $payload, $res->json());
 return $contactId;
 }

 // If contact already exists in HubSpot (409 Conflict with existing ID)
 if ($res->status() === 409) {
 $body = $res->json();
 if (isset($body['message']) && preg_match('/Existing ID:\s*(\d+)/i', $body['message'], $matches)) {
 $existingId = $matches[1];
 $customer->update([
 'hubspot_contact_id' => $existingId,
 'hubspot_synced_at' => now(),
 ]);
 // Update the existing contact
 $this->client()->patch("{$this->baseUrl}/crm/v3/objects/contacts/{$existingId}", $payload);
 $this->log('customer', $customer->id, $existingId, 'update', 'success', $payload, $body);
 return $existingId;
 }
 }

 $errorMsg = $res->json('message') ?? $res->body();
 $this->log('customer', $customer->id, null, 'push', 'failed', $payload, null, $errorMsg);
 return null;
 } catch (\Throwable $e) {
 $this->log('customer', $customer->id, null, 'push', 'failed', $payload, null, $e->getMessage());
 return null;
 }
 }

 public function syncAllCustomers(): array
 {
 $customers = Customer::all();
 $synced = 0;
 $failed = 0;

 foreach ($customers as $customer) {
 $res = $this->syncCustomer($customer);
 if ($res) {
 $synced++;
 } else {
 $failed++;
 }
 }

 return ['total' => $customers->count(), 'synced' => $synced, 'failed' => $failed];
 }

 public function fetchContactsFromHubSpot(int $limit = 50): array
 {
 if (!$this->isEnabled()) {
 return [];
 }

 try {
 $res = $this->client()->get("{$this->baseUrl}/crm/v3/objects/contacts", [
 'limit' => $limit,
 'properties' => 'firstname,lastname,email,phone,createdate',
 ]);

 if ($res->successful()) {
 $results = $res->json('results') ?? [];
 $importedCount = 0;

 foreach ($results as $item) {
 $props = $item['properties'] ?? [];
 $phone = $props['phone'] ?? null;
 $email = $props['email'] ?? null;
 $name = trim(($props['firstname'] ?? '') . ' ' . ($props['lastname'] ?? ''));

 if (empty($name)) {
 $name = 'عميل HubSpot #' . $item['id'];
 }

 // Check if exists locally
 $existing = null;
 if ($phone) {
 $existing = Customer::where('phone', $phone)->first();
 }
 if (!$existing && $email) {
 $existing = Customer::where('email', $email)->first();
 }
 if (!$existing) {
 $existing = Customer::where('hubspot_contact_id', (string) $item['id'])->first();
 }

 if ($existing) {
 $existing->update([
 'hubspot_contact_id' => (string) $item['id'],
 'hubspot_synced_at' => now(),
 ]);
 } else {
 // Create local customer
 Customer::create([
 'full_name' => $name,
 'phone' => $phone ?: '010' . rand(10000000, 99999999),
 'email' => $email,
 'source' => 'hubspot',
 'hubspot_contact_id' => (string) $item['id'],
 'hubspot_synced_at' => now(),
 'notes' => 'مستورد من HubSpot CRM',
 ]);
 $importedCount++;
 }
 }

 $this->log('customer', null, null, 'pull', 'success', ['limit' => $limit], ['imported' => $importedCount, 'fetched' => count($results)]);
 return ['success' => true, 'total' => count($results), 'imported' => $importedCount, 'items' => $results];
 }

 return ['success' => false, 'message' => $res->json('message') ?? 'فشل جلب العملاء'];
 } catch (\Throwable $e) {
 return ['success' => false, 'message' => $e->getMessage()];
 }
 }

 // ──────────────────────────────────────────
 // 3. Deals (الحجوزات والجلسات)
 // ──────────────────────────────────────────

 public function syncDeal(Deal $deal): ?string
 {
 if (!$this->isEnabled()) {
 return null;
 }

 $customer = $deal->customer;
 $contactId = $customer ? ($customer->hubspot_contact_id ?: $this->syncCustomer($customer)) : null;

 $roomName = $deal->room ? $deal->room->name : 'المساحة العامة';
 $customerName = $customer ? $customer->full_name : 'عميل غير مسجل';
 $dealName = "جلسة #{$deal->deal_number} - {$customerName} ({$roomName})";

 $amount = (float) $deal->applied_price;
 if ($deal->order) {
 $amount += (float) $deal->order->total;
 }

 $stage = match ($deal->status) {
 'closed' => $this->getStageClosed(),
 'cancelled' => 'closedlost',
 default => $this->getStageOpen(),
 };

 $properties = [
 'dealname' => $dealName,
 'amount' => (string) $amount,
 'dealstage' => $stage,
 'pipeline' => $this->getPipelineId(),
 'closedate' => ($deal->ended_at ?: now())->toISOString(),
 ];

 $payload = [
 'properties' => $properties,
 ];

 // Associations with contact
 if ($contactId) {
 $payload['associations'] = [
 [
 'to' => ['id' => $contactId],
 'types' => [
 [
 'associationCategory' => 'HUBSPOT_DEFINED',
 'associationTypeId' => 3, // Deal to Contact
 ]
 ]
 ]
 ];
 }

 try {
 if (!empty($deal->hubspot_deal_id)) {
 $res = $this->client()->patch("{$this->baseUrl}/crm/v3/objects/deals/{$deal->hubspot_deal_id}", ['properties' => $properties]);
 if ($res->successful()) {
 $deal->update(['hubspot_synced_at' => now()]);
 $this->log('deal', $deal->id, $deal->hubspot_deal_id, 'update', 'success', $payload, $res->json());
 return $deal->hubspot_deal_id;
 }
 }

 $res = $this->client()->post("{$this->baseUrl}/crm/v3/objects/deals", $payload);

 if ($res->successful()) {
 $dealId = (string) $res->json('id');
 $deal->update([
 'hubspot_deal_id' => $dealId,
 'hubspot_synced_at' => now(),
 ]);
 $this->log('deal', $deal->id, $dealId, 'push', 'success', $payload, $res->json());
 return $dealId;
 }

 $errorMsg = $res->json('message') ?? $res->body();
 $this->log('deal', $deal->id, null, 'push', 'failed', $payload, null, $errorMsg);
 return null;
 } catch (\Throwable $e) {
 $this->log('deal', $deal->id, null, 'push', 'failed', $payload, null, $e->getMessage());
 return null;
 }
 }

 public function syncBooking(Booking $booking): ?string
 {
 if (!$this->isEnabled()) {
 return null;
 }

 $customer = $booking->customer;
 $contactId = $customer ? ($customer->hubspot_contact_id ?: $this->syncCustomer($customer)) : null;

 $roomName = $booking->room ? $booking->room->name : 'مساحة خاصة';
 $customerName = $customer ? $customer->full_name : 'حجز عميل';
 $dealName = "حجز مسبق #{$booking->booking_number} - {$customerName} ({$roomName})";

 $stage = match ($booking->status) {
 'completed' => $this->getStageClosed(),
 'cancelled' => 'closedlost',
 default => $this->getStageOpen(),
 };

 $properties = [
 'dealname' => $dealName,
 'dealstage' => $stage,
 'pipeline' => $this->getPipelineId(),
 'closedate' => $booking->start_at ? $booking->start_at->toISOString() : now()->toISOString(),
 ];

 $payload = ['properties' => $properties];

 if ($contactId) {
 $payload['associations'] = [
 [
 'to' => ['id' => $contactId],
 'types' => [
 [
 'associationCategory' => 'HUBSPOT_DEFINED',
 'associationTypeId' => 3,
 ]
 ]
 ]
 ];
 }

 try {
 if (!empty($booking->hubspot_deal_id)) {
 $res = $this->client()->patch("{$this->baseUrl}/crm/v3/objects/deals/{$booking->hubspot_deal_id}", ['properties' => $properties]);
 if ($res->successful()) {
 $booking->update(['hubspot_synced_at' => now()]);
 $this->log('deal', $booking->id, $booking->hubspot_deal_id, 'update', 'success', $payload, $res->json());
 return $booking->hubspot_deal_id;
 }
 }

 $res = $this->client()->post("{$this->baseUrl}/crm/v3/objects/deals", $payload);
 if ($res->successful()) {
 $dealId = (string) $res->json('id');
 $booking->update([
 'hubspot_deal_id' => $dealId,
 'hubspot_synced_at' => now(),
 ]);
 $this->log('deal', $booking->id, $dealId, 'push', 'success', $payload, $res->json());
 return $dealId;
 }

 $this->log('deal', $booking->id, null, 'push', 'failed', $payload, null, $res->json('message') ?? $res->body());
 return null;
 } catch (\Throwable $e) {
 $this->log('deal', $booking->id, null, 'push', 'failed', $payload, null, $e->getMessage());
 return null;
 }
 }

 public function syncAllDeals(): array
 {
 $deals = Deal::all();
 $synced = 0;
 $failed = 0;

 foreach ($deals as $deal) {
 $res = $this->syncDeal($deal);
 if ($res) {
 $synced++;
 } else {
 $failed++;
 }
 }

 return ['total' => $deals->count(), 'synced' => $synced, 'failed' => $failed];
 }

 public function fetchDealsFromHubSpot(int $limit = 50): array
 {
 if (!$this->isEnabled()) {
 return [];
 }

 try {
 $res = $this->client()->get("{$this->baseUrl}/crm/v3/objects/deals", [
 'limit' => $limit,
 'properties' => 'dealname,amount,dealstage,closedate,createdate',
 ]);

 if ($res->successful()) {
 $results = $res->json('results') ?? [];
 $this->log('deal', null, null, 'pull', 'success', ['limit' => $limit], ['fetched' => count($results)]);
 return ['success' => true, 'total' => count($results), 'items' => $results];
 }

 return ['success' => false, 'message' => $res->json('message') ?? 'فشل جلب الصفقات'];
 } catch (\Throwable $e) {
 return ['success' => false, 'message' => $e->getMessage()];
 }
 }

 // ──────────────────────────────────────────
 // 4. Products (المنتجات والخدمات)
 // ──────────────────────────────────────────

 public function syncProduct(Product $product): ?string
 {
 if (!$this->isEnabled()) {
 return null;
 }

 $properties = [
 'name' => $product->name,
 'price' => (string) $product->selling_price,
 'description' => $product->description ?: ($product->category ? $product->category->name : 'منتج DT Space'),
 'hs_sku' => $product->sku ?: ('SKU-' . $product->id),
 ];

 $payload = ['properties' => $properties];

 try {
 if (!empty($product->hubspot_product_id)) {
 $res = $this->client()->patch("{$this->baseUrl}/crm/v3/objects/products/{$product->hubspot_product_id}", $payload);
 if ($res->successful()) {
 $product->update(['hubspot_synced_at' => now()]);
 $this->log('product', $product->id, $product->hubspot_product_id, 'update', 'success', $payload, $res->json());
 return $product->hubspot_product_id;
 }
 }

 $res = $this->client()->post("{$this->baseUrl}/crm/v3/objects/products", $payload);

 if ($res->successful()) {
 $productId = (string) $res->json('id');
 $product->update([
 'hubspot_product_id' => $productId,
 'hubspot_synced_at' => now(),
 ]);
 $this->log('product', $product->id, $productId, 'push', 'success', $payload, $res->json());
 return $productId;
 }

 $this->log('product', $product->id, null, 'push', 'failed', $payload, null, $res->json('message') ?? $res->body());
 return null;
 } catch (\Throwable $e) {
 $this->log('product', $product->id, null, 'push', 'failed', $payload, null, $e->getMessage());
 return null;
 }
 }

 public function syncAllProducts(): array
 {
 $products = Product::all();
 $synced = 0;
 $failed = 0;

 foreach ($products as $product) {
 $res = $this->syncProduct($product);
 if ($res) {
 $synced++;
 } else {
 $failed++;
 }
 }

 return ['total' => $products->count(), 'synced' => $synced, 'failed' => $failed];
 }

 // ──────────────────────────────────────────
 // 5. Analytics & Dashboard Summary
 // ──────────────────────────────────────────

 public function getAnalytics(): array
 {
 $totalCustomers = Customer::count();
 $syncedCustomers = Customer::whereNotNull('hubspot_contact_id')->count();

 $totalDeals = Deal::count();
 $syncedDeals = Deal::whereNotNull('hubspot_deal_id')->count();

 $totalProducts = Product::count();
 $syncedProducts = Product::whereNotNull('hubspot_product_id')->count();

 $totalDealsValue = Deal::sum('applied_price');
 $syncedDealsValue = Deal::whereNotNull('hubspot_deal_id')->sum('applied_price');

 $totalLogs = HubSpotSyncLog::count();
 $successLogs = HubSpotSyncLog::where('status', 'success')->count();
 $failedLogs = HubSpotSyncLog::where('status', 'failed')->count();

 $successRate = $totalLogs > 0 ? round(($successLogs / $totalLogs) * 100, 1) : 100;

 return [
 'customers' => [
 'total' => $totalCustomers,
 'synced' => $syncedCustomers,
 'percentage' => $totalCustomers > 0 ? round(($syncedCustomers / $totalCustomers) * 100, 1) : 0,
 ],
 'deals' => [
 'total' => $totalDeals,
 'synced' => $syncedDeals,
 'total_val' => $totalDealsValue,
 'synced_val' => $syncedDealsValue,
 'percentage' => $totalDeals > 0 ? round(($syncedDeals / $totalDeals) * 100, 1) : 0,
 ],
 'products' => [
 'total' => $totalProducts,
 'synced' => $syncedProducts,
 'percentage' => $totalProducts > 0 ? round(($syncedProducts / $totalProducts) * 100, 1) : 0,
 ],
 'logs' => [
 'total' => $totalLogs,
 'success' => $successLogs,
 'failed' => $failedLogs,
 'success_rate' => $successRate,
 ],
 'is_configured' => !empty($this->getAccessToken()),
 'is_enabled' => $this->isEnabled(),
 'last_sync_time' => HubSpotSyncLog::latest('id')->value('created_at'),
 ];
 }

 // ──────────────────────────────────────────
 // 6. Logging
 // ──────────────────────────────────────────

 public function log(
 string $entityType,
 $entityId = null,
 ?string $hubspotId = null,
 string $action = 'push',
 string $status = 'success',
 $payload = null,
 $response = null,
 ?string $errorMessage = null
 ): void {
 try {
 HubSpotSyncLog::create([
 'entity_type' => $entityType,
 'entity_id' => $entityId,
 'hubspot_id' => $hubspotId,
 'action' => $action,
 'status' => $status,
 'payload' => is_array($payload) ? json_encode($payload, JSON_UNESCAPED_UNICODE) : $payload,
 'response' => is_array($response) ? json_encode($response, JSON_UNESCAPED_UNICODE) : $response,
 'error_message' => $errorMessage,
 ]);
 } catch (\Throwable $e) {
 Log::error('Failed to write HubSpot log: ' . $e->getMessage());
 }
 }
}
