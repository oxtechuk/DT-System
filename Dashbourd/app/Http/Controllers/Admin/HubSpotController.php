<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Deal;
use App\Models\HubSpotSyncLog;
use App\Models\Product;
use App\Models\Setting;
use App\Services\HubSpotService;
use Illuminate\Http\Request;

class HubSpotController extends Controller
{
 protected HubSpotService $hubspot;

 public function __construct(HubSpotService $hubspot)
 {
 $this->hubspot = $hubspot;
 }

 public function index(Request $request)
 {
 $analytics = $this->hubspot->getAnalytics();

 $query = HubSpotSyncLog::latest('id');
 if ($request->filled('status')) {
 $query->where('status', $request->status);
 }
 if ($request->filled('type')) {
 $query->where('entity_type', $request->type);
 }
 $logs = $query->paginate(15)->withQueryString();

 $settings = [
 'enabled' => Setting::get('hubspot_enabled', false),
 'token' => Setting::get('hubspot_access_token', ''),
 'pipeline_id' => Setting::get('hubspot_pipeline_id', 'default'),
 'stage_open' => Setting::get('hubspot_stage_open', 'appointmentscheduled'),
 'stage_closed' => Setting::get('hubspot_stage_closed', 'closedwon'),
 'auto_sync' => Setting::get('hubspot_auto_sync', true),
 ];

 return view('admin.hubspot.index', compact('analytics', 'logs', 'settings'));
 }

 public function updateSettings(Request $request)
 {
 $request->validate([
 'hubspot_access_token' => 'nullable|string',
 'hubspot_pipeline_id' => 'nullable|string',
 'hubspot_stage_open' => 'nullable|string',
 'hubspot_stage_closed' => 'nullable|string',
 ]);

 Setting::set('hubspot_enabled', $request->has('hubspot_enabled'));
 Setting::set('hubspot_auto_sync', $request->has('hubspot_auto_sync'));

 if ($request->has('hubspot_access_token')) {
 Setting::set('hubspot_access_token', trim($request->hubspot_access_token));
 }
 if ($request->filled('hubspot_pipeline_id')) {
 Setting::set('hubspot_pipeline_id', trim($request->hubspot_pipeline_id));
 }
 if ($request->filled('hubspot_stage_open')) {
 Setting::set('hubspot_stage_open', trim($request->hubspot_stage_open));
 }
 if ($request->filled('hubspot_stage_closed')) {
 Setting::set('hubspot_stage_closed', trim($request->hubspot_stage_closed));
 }

 if ($request->has('hubspot_enabled') && !empty($request->hubspot_access_token)) {
 $test = $this->hubspot->testConnection();
 if (!$test['success']) {
 return back()->with('warning', 'تم حفظ الإعدادات، ولكن فحص الاتصال مع HubSpot أظهر تنبيهاً: ' . $test['message']);
 }
 }

 return back()->with('success', 'تم حفظ إعدادات الربط مع HubSpot بنجاح!');
 }

 public function testConnection(Request $request)
 {
 $result = $this->hubspot->testConnection();
 return response()->json($result);
 }

 public function sync(Request $request)
 {
 $type = $request->input('type', 'all');

 if (!$this->hubspot->isEnabled()) {
 $msg = 'الربط مع HubSpot غير مفعل أو أن كود الوصول غير مدخل. يرجى تفعيله أولاً من الإعدادات.';
 if ($request->ajax() || $request->wantsJson()) {
 return response()->json(['success' => false, 'message' => $msg], 422);
 }
 return back()->with('error', $msg);
 }

 $summary = [];

 if (in_array($type, ['customers', 'all'])) {
 $summary['customers'] = $this->hubspot->syncAllCustomers();
 }

 if (in_array($type, ['deals', 'all'])) {
 $summary['deals'] = $this->hubspot->syncAllDeals();
 }

 if (in_array($type, ['products', 'all'])) {
 $summary['products'] = $this->hubspot->syncAllProducts();
 }

 $message = 'تمت عملية المزامنة بنجاح!';
 if (isset($summary['customers'])) {
 $message .= " | عملاء: {$summary['customers']['synced']}/{$summary['customers']['total']}";
 }
 if (isset($summary['deals'])) {
 $message .= " | صفقات: {$summary['deals']['synced']}/{$summary['deals']['total']}";
 }
 if (isset($summary['products'])) {
 $message .= " | منتجات: {$summary['products']['synced']}/{$summary['products']['total']}";
 }

 if ($request->ajax() || $request->wantsJson()) {
 return response()->json([
 'success' => true,
 'message' => $message,
 'summary' => $summary,
 ]);
 }

 return back()->with('success', $message);
 }

 public function pull(Request $request)
 {
 if (!$this->hubspot->isEnabled()) {
 return back()->with('error', 'الربط مع HubSpot غير مفعل.');
 }

 $customersPull = $this->hubspot->fetchContactsFromHubSpot(50);
 $dealsPull = $this->hubspot->fetchDealsFromHubSpot(50);

 $msg = 'تم استيراد وقراءة البيانات من HubSpot بنجاح!';
 if (isset($customersPull['imported'])) {
 $msg .= " (تم استيراد {$customersPull['imported']} عميل جديد من أصل {$customersPull['total']})";
 }

 return back()->with('success', $msg);
 }

 public function report()
 {
 $analytics = $this->hubspot->getAnalytics();

 // Deal stages distribution
 $dealsByStatus = Deal::selectRaw('status, count(*) as count, sum(applied_price) as total_amount')
 ->groupBy('status')
 ->get();

 // Customer acquisition source
 $customersBySource = Customer::selectRaw('source, count(*) as count')
 ->groupBy('source')
 ->get();

 // Top synced products
 $topProducts = Product::withCount('orderItems')
 ->orderByDesc('order_items_count')
 ->take(8)
 ->get();

 return view('admin.hubspot.report', compact('analytics', 'dealsByStatus', 'customersBySource', 'topProducts'));
 }
}
