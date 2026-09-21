<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Deal;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Room;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
    /**
     * Customers Directory
     */
    public function customers(Request $request)
    {
        $search = $request->query('search');
        $customerType = $request->query('customer_type');
        $classification = $request->query('classification');
        $source = $request->query('source');

        $query = Customer::query()->withCount('deals');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (!empty($customerType) && in_array($customerType, ['registered', 'guest'])) {
            $query->where('customer_type', $customerType);
        }

        if (!empty($classification)) {
            $query->where('classification', $classification);
        }

        if (!empty($source)) {
            $query->where('source', $source);
        }

        $customers = $query->latest()->paginate(15)->withQueryString();
        $totalCustomers = Customer::count();
        $activeCustomers = Customer::where('status', 'active')->count();
        $registeredCustomers = Customer::where('customer_type', 'registered')->count();
        $guestCustomers = Customer::where('customer_type', 'guest')->count();

        return view('customers.index', compact(
            'customers',
            'totalCustomers',
            'activeCustomers',
            'registeredCustomers',
            'guestCustomers',
            'search',
            'customerType',
            'source'
        ));
    }

    /**
     * Store new customer with strict validation
     */
    public function storeCustomer(Request $request)
    {
        // Support either full_name or name from requests
        if (!$request->has('full_name') && $request->has('name')) {
            $request->merge(['full_name' => $request->input('name')]);
        }

        $validated = $request->validate([
            'full_name' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^[\pL\s\.\'-]+$/u',
            ],
            'phone' => [
                'required',
                'string',
                'max:25',
                'unique:customers,phone',
                'regex:/^((\+?20|0)?1[0125][0-9]{8}|\+?[1-9][0-9]{7,14})$/',
            ],
            'email' => 'nullable|email|max:255|unique:customers,email',
            'customer_type' => 'required|in:registered,guest',
            'classification' => 'nullable|string|in:freelancer,high_school,university,lecturer,other',
            'source' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1000',
        ], [
            'full_name.required' => 'يرجى إدخال اسم العميل بالكامل.',
            'full_name.min' => 'يجب ألا يقل اسم العميل عن 3 أحرف.',
            'full_name.max' => 'يجب ألا يتجاوز اسم العميل 100 حرف.',
            'full_name.regex' => 'اسم العميل يجب أن يحتوي على أحرف ومسافات فقط بدون أرقام أو رموز خاصة.',
            'phone.required' => 'يرجى إدخال رقم هاتف / موبايل العميل.',
            'phone.unique' => 'رقم الموبايل مسجل مسبقاً لعميل آخر.',
            'phone.regex' => 'يرجى إدخال رقم موبايل صحيح (مثال: 01012345678 أو +201012345678).',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة.',
            'email.unique' => 'البريد الإلكتروني مسجل بالفعل لعميل آخر.',
            'customer_type.required' => 'يرجى تحديد نوع العميل.',
            'customer_type.in' => 'نوع العميل المحدد غير صالح.',
        ]);

        $validated['classification'] = $validated['classification'] ?? 'freelancer';
        $validated['source'] = $validated['source'] ?: 'walk-in';
        $validated['status'] = 'active';

        if ($validated['customer_type'] === 'registered') {
            $validated['referral_code'] = Customer::generateUniqueReferralCode($validated['full_name']);
        }

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'تم تسجيل العميل بنجاح.');
    }

    /**
     * Download Sample CSV Template for Customers Import
     */
    public function sampleCustomerCsv()
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="customers_sample_template.csv"',
        ];

        $columns = [
            'Record ID',
            'First Name',
            'Last Name',
            'Phone Number',
            'Contact owner',
            'Last Activity Date',
            'Lead Status',
            'Create Date',
            'Close Date',
            'Source',
            'Associated Company',
            'Associated Deal IDs',
        ];

        $sampleRows = [
            [
                '866000000001',
                'تسنيم',
                'وليد',
                '01012345678',
                'Mohammed Fathy',
                '2026-09-20 14:30:00',
                'عميل نشط',
                '2026-09-01 10:00:00',
                '',
                'Friend Referral',
                'شركة التقنية',
                '521066214610',
            ],
            [
                '866000000002',
                'مؤمن',
                'محمد',
                '01123456789',
                'Mohammed Fathy',
                '2026-09-18 16:00:00',
                'طالب جامعي',
                '2026-08-15 12:00:00',
                '',
                'Facebook',
                '',
                '',
            ],
            [
                '866000000003',
                'سيف',
                'أحمد',
                '01234567890',
                'Mohammed Fathy',
                '2026-09-19 11:15:00',
                'فريلانسر',
                '2026-07-10 09:30:00',
                '',
                'Instagram',
                '',
                '',
            ],
        ];

        $callback = function () use ($columns, $sampleRows) {
            $file = fopen('php://output', 'w');
            // Write UTF-8 BOM for Excel Arabic support
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $columns);
            foreach ($sampleRows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import Customers from CSV / Excel Export
     */
    public function importCustomers(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|max:20480', // up to 20MB
            'update_existing' => 'nullable',
        ], [
            'csv_file.required' => 'يرجى اختيار ملف CSV أو Excel للاستيراد.',
            'csv_file.file' => 'الملف المرفوع غير صالح.',
            'csv_file.max' => 'الحد الأقصى لحجم الملف هو 20 ميجابايت.',
        ]);

        $file = $request->file('csv_file');
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, ['csv', 'txt', 'tsv'])) {
            return redirect()->back()->with('error', 'يرجى رفع ملف بصيغة CSV أو TXT. إذا كان الملف بصيغة XLSX، يرجى حفظه من Excel بصيغة (CSV UTF-8).');
        }

        $rawContent = file_get_contents($file->getRealPath());
        if (empty($rawContent)) {
            return redirect()->back()->with('error', 'الملف المرفوع فارغ.');
        }

        // Handle BOM
        if (str_starts_with($rawContent, "\xEF\xBB\xBF")) {
            $rawContent = substr($rawContent, 3);
        }

        // Detect and convert encoding to UTF-8
        if (!mb_check_encoding($rawContent, 'UTF-8')) {
            $converted = @iconv('Windows-1256', 'UTF-8//IGNORE', $rawContent);
            if ($converted && mb_check_encoding($converted, 'UTF-8')) {
                $rawContent = $converted;
            } else {
                $detectedEncoding = mb_detect_encoding($rawContent, ['Windows-1252', 'ISO-8859-1', 'ISO-8859-6', 'ASCII'], true);
                if ($detectedEncoding && $detectedEncoding !== 'UTF-8') {
                    $rawContent = mb_convert_encoding($rawContent, 'UTF-8', $detectedEncoding);
                }
            }
        }

        // Split into lines
        $lines = preg_split('/\r\n|\r|\n/', trim($rawContent));
        if (count($lines) < 2) {
            return redirect()->back()->with('error', 'الملف لا يحتوي على بيانات كافية (يجب أن يحتوي على صف العناوين وبيانات العملاء).');
        }

        // Detect delimiter: comma, semicolon, tab
        $firstLine = $lines[0];
        $delimiters = [',', ';', "\t"];
        $delimiter = ',';
        $maxCount = 0;
        foreach ($delimiters as $d) {
            $count = count(str_getcsv($firstLine, $d));
            if ($count > $maxCount) {
                $maxCount = $count;
                $delimiter = $d;
            }
        }

        $headers = str_getcsv(array_shift($lines), $delimiter);
        // Normalize headers
        $headerMap = [];
        foreach ($headers as $index => $colName) {
            $cleanName = mb_strtolower(trim(preg_replace('/[\x{FEFF}\x{200B}-\x{200D}]/u', '', $colName)));
            $cleanName = str_replace(['_', '-', '.', '  '], ' ', $cleanName);
            $cleanName = trim($cleanName);

            if (preg_match('/^(record\s*id|hubspot\s*record\s*id|contact\s*id|hubspot\s*id)$/i', $cleanName)) {
                $headerMap['record_id'] = $index;
            } elseif (preg_match('/^(first\s*name|الاسم\s*الأول|الاسم\s*الاول)$/iu', $cleanName)) {
                $headerMap['first_name'] = $index;
            } elseif (preg_match('/^(last\s*name|الاسم\s*الأخير|الاسم\s*الاخير|اسم\s*العائلة|اللقب)$/iu', $cleanName)) {
                $headerMap['last_name'] = $index;
            } elseif (preg_match('/^(full\s*name|name|الاسم|اسم\s*العميل|الاسم\s*بالكامل)$/iu', $cleanName)) {
                $headerMap['full_name'] = $index;
            } elseif (preg_match('/^(phone|phone\s*number|phone\s*nu|mobile|الموبايل|الهاتف|رقم\s*الهاتف|رقم\s*الموبايل)$/iu', $cleanName)) {
                $headerMap['phone'] = $index;
            } elseif (preg_match('/^(email|e-mail|email\s*address|البريد|البريد\s*الإلكتروني|البريد\s*الالكتروني)$/iu', $cleanName)) {
                $headerMap['email'] = $index;
            } elseif (preg_match('/^(source|المصدر|مصدر\s*العميل|lead\s*source)$/iu', $cleanName)) {
                $headerMap['source'] = $index;
            } elseif (preg_match('/^(contact\s*owner|owner|contact\s*ow|مسؤول\s*التواصل|المسؤول)$/iu', $cleanName)) {
                $headerMap['contact_owner'] = $index;
            } elseif (preg_match('/^(lead\s*status|lead\s*statu|status|الحالة|حالة\s*العميل|classification|التصنيف)$/iu', $cleanName)) {
                $headerMap['lead_status'] = $index;
            } elseif (preg_match('/^(create\s*date|created\s*at|create\s*da|تاريخ\s*الإنشاء|تاريخ\s*التسجيل)$/iu', $cleanName)) {
                $headerMap['create_date'] = $index;
            } elseif (preg_match('/^(last\s*activity\s*date|last\s*activity|last\s*activi|آخر\s*نشاط|اخر\s*نشاط)$/iu', $cleanName)) {
                $headerMap['last_activity'] = $index;
            } elseif (preg_match('/^(close\s*date|close\s*da|تاريخ\s*الإغلاق)$/iu', $cleanName)) {
                $headerMap['close_date'] = $index;
            } elseif (preg_match('/^(associated\s*deal\s*ids|associated\s*deals|deals|الصفقات)$/iu', $cleanName)) {
                $headerMap['associated_deals'] = $index;
            } elseif (preg_match('/^(associated\s*company|company|الشركة|المؤسسة)$/iu', $cleanName)) {
                $headerMap['associated_company'] = $index;
            }
        }

        $updateExisting = $request->boolean('update_existing', true);
        $insertedCount = 0;
        $updatedCount = 0;
        $skippedCount = 0;

        foreach ($lines as $lineIndex => $line) {
            $line = trim($line);
            if (empty($line)) continue;

            $row = str_getcsv($line, $delimiter);
            if (empty(array_filter($row))) continue;

            // 1. Phone extraction and normalization
            $rawPhone = isset($headerMap['phone']) && isset($row[$headerMap['phone']]) ? trim($row[$headerMap['phone']]) : '';
            if (preg_match('/[eE][+-]?\d+/', $rawPhone)) {
                $rawPhone = sprintf('%.0f', (float)$rawPhone);
            }
            $phoneClean = preg_replace('/[^0-9]/', '', $rawPhone);

            // Egyptian mobile normalization
            if (str_starts_with($phoneClean, '20') && strlen($phoneClean) === 12) {
                $phoneClean = '0' . substr($phoneClean, 2);
            } elseif (str_starts_with($phoneClean, '1') && strlen($phoneClean) === 10) {
                $phoneClean = '0' . $phoneClean;
            }

            if (empty($phoneClean) || strlen($phoneClean) < 7) {
                $skippedCount++;
                continue;
            }

            // 2. Name extraction
            $firstName = isset($headerMap['first_name']) && isset($row[$headerMap['first_name']]) ? trim($row[$headerMap['first_name']]) : '';
            $lastName = isset($headerMap['last_name']) && isset($row[$headerMap['last_name']]) ? trim($row[$headerMap['last_name']]) : '';
            $fullName = isset($headerMap['full_name']) && isset($row[$headerMap['full_name']]) ? trim($row[$headerMap['full_name']]) : '';

            $name = trim("$firstName $lastName");
            if (empty($name)) {
                $name = !empty($fullName) ? $fullName : 'عميل ' . $phoneClean;
            }

            // 3. Email
            $email = isset($headerMap['email']) && isset($row[$headerMap['email']]) ? trim($row[$headerMap['email']]) : null;
            if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $email = null;
            }

            // 4. Record ID / HubSpot Contact ID
            $recordId = isset($headerMap['record_id']) && isset($row[$headerMap['record_id']]) ? trim($row[$headerMap['record_id']]) : null;
            if ($recordId && preg_match('/[eE][+-]?\d+/', $recordId)) {
                $recordId = sprintf('%.0f', (float)$recordId);
            }

            // 5. Source
            $rawSource = isset($headerMap['source']) && isset($row[$headerMap['source']]) ? trim($row[$headerMap['source']]) : '';
            $source = 'hubspot';
            if (!empty($rawSource)) {
                $srcLower = strtolower($rawSource);
                if (str_contains($srcLower, 'friend') || str_contains($srcLower, 'referral') || str_contains($srcLower, 'ترشيح')) {
                    $source = 'referral';
                } elseif (str_contains($srcLower, 'facebook') || str_contains($srcLower, 'fb')) {
                    $source = 'facebook';
                } elseif (str_contains($srcLower, 'instagram') || str_contains($srcLower, 'insta')) {
                    $source = 'instagram';
                } elseif (str_contains($srcLower, 'walk')) {
                    $source = 'walk-in';
                } else {
                    $source = mb_substr($rawSource, 0, 50);
                }
            }

            // 6. Classification & Lead Status
            $leadStatus = isset($headerMap['lead_status']) && isset($row[$headerMap['lead_status']]) ? trim($row[$headerMap['lead_status']]) : '';
            $classification = 'freelancer';
            if (!empty($leadStatus)) {
                $statusLower = strtolower($leadStatus);
                if (str_contains($statusLower, 'high') || str_contains($statusLower, 'ثانوي')) {
                    $classification = 'high_school';
                } elseif (str_contains($statusLower, 'univ') || str_contains($statusLower, 'طالب') || str_contains($statusLower, 'جامع')) {
                    $classification = 'university';
                } elseif (str_contains($statusLower, 'lect') || str_contains($statusLower, 'train') || str_contains($statusLower, 'مدرب') || str_contains($statusLower, 'محاضر')) {
                    $classification = 'lecturer';
                } elseif (str_contains($statusLower, 'other') || str_contains($statusLower, 'أخرى')) {
                    $classification = 'other';
                }
            }

            // 7. Dates
            $createDate = null;
            if (isset($headerMap['create_date']) && !empty($row[$headerMap['create_date']])) {
                try {
                    $createDate = \Carbon\Carbon::parse(trim($row[$headerMap['create_date']]));
                } catch (\Exception $e) {
                    $createDate = null;
                }
            }

            $lastActivity = null;
            if (isset($headerMap['last_activity']) && !empty($row[$headerMap['last_activity']])) {
                try {
                    $lastActivity = \Carbon\Carbon::parse(trim($row[$headerMap['last_activity']]));
                } catch (\Exception $e) {
                    $lastActivity = null;
                }
            }

            // 8. Notes collection
            $notesParts = [];
            if (!empty($row[$headerMap['contact_owner'] ?? -1])) {
                $notesParts[] = "مسؤول التواصل: " . trim($row[$headerMap['contact_owner']]);
            }
            if (!empty($leadStatus)) {
                $notesParts[] = "حالة العميل / Lead: " . $leadStatus;
            }
            if (!empty($row[$headerMap['associated_deals'] ?? -1])) {
                $dealsVal = trim($row[$headerMap['associated_deals']]);
                if (preg_match('/[eE][+-]?\d+/', $dealsVal)) {
                    $dealsVal = sprintf('%.0f', (float)$dealsVal);
                }
                $notesParts[] = "صفقات HubSpot: " . $dealsVal;
            }
            if (!empty($row[$headerMap['associated_company'] ?? -1])) {
                $notesParts[] = "الشركة: " . trim($row[$headerMap['associated_company']]);
            }

            $notes = !empty($notesParts) ? implode(' | ', $notesParts) : null;

            // 9. Lookup existing customer
            $existing = Customer::where('phone', $phoneClean)->first();
            if (!$existing && $recordId) {
                $existing = Customer::where('hubspot_contact_id', $recordId)->first();
            }
            if (!$existing && $email) {
                $existing = Customer::where('email', $email)->first();
            }

            if ($existing) {
                if ($updateExisting) {
                    $updates = [];
                    if (!empty($name) && (empty($existing->full_name) || str_starts_with($existing->full_name, 'عميل '))) {
                        $updates['full_name'] = $name;
                    }
                    if ($recordId && empty($existing->hubspot_contact_id)) {
                        $updates['hubspot_contact_id'] = $recordId;
                        $updates['hubspot_synced_at'] = now();
                    }
                    if ($email && empty($existing->email)) {
                        $updates['email'] = $email;
                    }
                    if ($lastActivity && (empty($existing->last_active_at) || $lastActivity > $existing->last_active_at)) {
                        $updates['last_active_at'] = $lastActivity;
                    }
                    if ($notes) {
                        $existingNotes = $existing->notes ? $existing->notes . "\n" : '';
                        $updates['notes'] = mb_substr($existingNotes . $notes, 0, 1000);
                    }
                    if (!empty($updates)) {
                        $existing->update($updates);
                    }
                    $updatedCount++;
                } else {
                    $skippedCount++;
                }
            } else {
                // Create new Customer
                Customer::create([
                    'full_name' => $name,
                    'phone' => $phoneClean,
                    'email' => $email,
                    'customer_type' => 'registered',
                    'classification' => $classification,
                    'source' => $source,
                    'status' => 'active',
                    'notes' => $notes,
                    'hubspot_contact_id' => $recordId,
                    'hubspot_synced_at' => $recordId ? now() : null,
                    'last_active_at' => $lastActivity,
                    'referral_code' => Customer::generateUniqueReferralCode($name),
                    'created_at' => $createDate ?? now(),
                ]);
                $insertedCount++;
            }
        }

        $msg = "تم استيراد العملاء بنجاح! تم إنشاء ($insertedCount) عميل جديد، وتحديث ($updatedCount) عميل مسجل مسبقاً.";
        if ($skippedCount > 0) {
            $msg .= " تم تخطي ($skippedCount) صف (أرقام غير صالحة أو مكررة).";
        }

        return redirect()->route('customers.index')->with('success', $msg);
    }

    /**
     * Update customer details
     */
    public function updateCustomer(Request $request, Customer $customer)
    {
        if (!$request->has('full_name') && $request->has('name')) {
            $request->merge(['full_name' => $request->input('name')]);
        }

        $validated = $request->validate([
            'full_name' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^[\pL\s\.\'-]+$/u',
            ],
            'phone' => [
                'required',
                'string',
                'max:25',
                'unique:customers,phone,' . $customer->id,
                'regex:/^((\+?20|0)?1[0125][0-9]{8}|\+?[1-9][0-9]{7,14})$/',
            ],
            'email' => 'nullable|email|max:255|unique:customers,email,' . $customer->id,
            'customer_type' => 'required|in:registered,guest',
            'classification' => 'nullable|string|in:freelancer,high_school,university,lecturer,other',
            'source' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive,blocked',
            'notes' => 'nullable|string|max:1000',
        ], [
            'full_name.required' => 'يرجى إدخال اسم العميل بالكامل.',
            'full_name.min' => 'يجب ألا يقل اسم العميل عن 3 أحرف.',
            'full_name.max' => 'يجب ألا يتجاوز اسم العميل 100 حرف.',
            'full_name.regex' => 'اسم العميل يجب أن يحتوي على أحرف ومسافات فقط بدون أرقام أو رموز خاصة.',
            'phone.required' => 'يرجى إدخال رقم هاتف / موبايل العميل.',
            'phone.unique' => 'رقم الموبايل مسجل مسبقاً لعميل آخر.',
            'phone.regex' => 'يرجى إدخال رقم موبايل صحيح (مثال: 01012345678 أو +201012345678).',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة.',
            'email.unique' => 'البريد الإلكتروني مسجل بالفعل لعميل آخر.',
            'customer_type.required' => 'يرجى تحديد نوع العميل.',
            'customer_type.in' => 'نوع العميل المحدد غير صالح.',
            'status.required' => 'يرجى تحديد حالة العميل.',
            'status.in' => 'حالة العميل المحددة غير صالحة.',
        ]);

        $validated['classification'] = $validated['classification'] ?? $customer->classification ?? 'freelancer';
        $validated['source'] = $validated['source'] ?: $customer->source ?: 'walk-in';

        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'تم تحديث بيانات العميل بنجاح.');
    }

 /**
 * Rooms & Spaces
 */
 public function rooms()
 {
 $rooms = Room::with('activeDeals.customer')->get();
 $totalCapacity = $rooms->sum('capacity');
 $availableRooms = $rooms->filter(fn($r) => $r->is_available)->count();

 return view('rooms.index', compact('rooms', 'totalCapacity', 'availableRooms'));
 }

 /**
 * Store a new room
 */
 public function storeRoom(Request $request)
 {
 $data = $request->validate([
 'name' => 'required|string|max:255',
 'code' => 'nullable|string|max:50|unique:rooms,code',
 'capacity' => 'required|integer|min:1',
 'status' => 'required|in:active,maintenance,inactive',
 'description' => 'nullable|string|max:1000',
 ]);

 if (empty($data['code'])) {
 $data['code'] = 'R-' . strtoupper(substr(md5(time() . rand(100, 999)), 0, 4));
 }

 Room::create($data);

 return redirect()->back()->with('success', 'تمت إضافة الغرفة بنجاح.');
 }

 /**
 * Update an existing room
 */
 public function updateRoom(Request $request, Room $room)
 {
 $data = $request->validate([
 'name' => 'required|string|max:255',
 'code' => 'nullable|string|max:50|unique:rooms,code,' . $room->id,
 'capacity' => 'required|integer|min:1',
 'status' => 'required|in:active,maintenance,inactive',
 'description' => 'nullable|string|max:1000',
 ]);

 $room->update($data);

 return redirect()->back()->with('success', 'تم تعديل بيانات الغرفة بنجاح.');
 }

 /**
 * Delete a room
 */
 public function destroyRoom(Room $room)
 {
 if ($room->activeDeals()->exists()) {
 return redirect()->back()->with('error', 'لا يمكن حذف الغرفة لوجود جلسة نشطة بداخلها حالياً. يرجى إنهاء الحساب من الكاشير أولاً.');
 }

 $room->delete();

 return redirect()->back()->with('success', 'تم حذف الغرفة بنجاح.');
 }

    /**
     * Bookings Calendar / List with Room Filters
     */
    public function bookings(Request $request)
    {
        $roomId = $request->query('room_id');
        $status = $request->query('status');
        $date = $request->query('date');
        $search = $request->query('search');
        $type = $request->query('type'); // subscription, private_room, shared_desk

        $query = Booking::with(['customer', 'room', 'workspaceType'])->orderBy('start_at', 'desc');

        if ($roomId) {
            $query->where('room_id', $roomId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($date) {
            $query->whereDate('start_at', $date);
        }

        if ($type === 'subscription') {
            $query->where(function ($q) {
                $q->whereHas('workspaceType', fn($wq) => $wq->whereIn('code', ['monthly', 'weekly', 'subscription']))
                  ->orWhereRaw('TIMESTAMPDIFF(HOUR, start_at, end_at) >= 20');
            });
        } elseif ($type === 'private_room') {
            $query->where(function ($q) {
                $q->whereHas('workspaceType', fn($wq) => $wq->whereIn('code', ['private', 'meeting']))
                  ->orWhereHas('room', fn($rq) => $rq->whereIn('type', ['private', 'meeting']));
            });
        } elseif ($type === 'shared_desk') {
            $query->where(function ($q) {
                $q->whereHas('workspaceType', fn($wq) => $wq->whereNotIn('code', ['monthly', 'weekly', 'subscription', 'private', 'meeting']))
                  ->whereDoesntHave('room', fn($rq) => $rq->whereIn('type', ['private', 'meeting']));
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('booking_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('full_name', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        // If AJAX JSON events request
        if ($request->wantsJson() || $request->ajax() || $request->has('calendar_feed')) {
            $calendarEvents = $query->get()->map->toCalendarEvent();
            return response()->json($calendarEvents);
        }

        $bookings = $query->paginate(20)->withQueryString();

        // Fetch all calendar events (last 3 months to next 4 months for calendar view)
        $allCalendarBookings = Booking::with(['customer', 'room', 'workspaceType'])
            ->where('start_at', '>=', now()->subMonths(3))
            ->where('start_at', '<=', now()->addMonths(4))
            ->orderBy('start_at', 'asc')
            ->get();

        $calendarEvents = $allCalendarBookings->map->toCalendarEvent();

        $rooms = Room::orderBy('name')->get();
        $customers = Customer::where('status', 'active')->orderBy('full_name')->get();
        $workspaceTypes = \App\Models\WorkspaceType::all();

        // Statistics
        $todayBookingsCount = Booking::whereDate('start_at', today())
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->count();

        $activeSubscriptionsCount = Booking::where('start_at', '<=', now())
            ->where('end_at', '>=', now())
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->where(function ($q) {
                $q->whereHas('workspaceType', fn($wq) => $wq->whereIn('code', ['monthly', 'weekly', 'subscription']))
                  ->orWhereRaw('TIMESTAMPDIFF(HOUR, start_at, end_at) >= 20');
            })
            ->count();

        $upcomingRoomsBookingsCount = Booking::where('start_at', '>=', now())
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->count();

        $activeRoomsBookings = Booking::whereDate('start_at', today())
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->selectRaw('room_id, count(*) as count')
            ->groupBy('room_id')
            ->pluck('count', 'room_id');

        return view('bookings.index', compact(
            'bookings',
            'calendarEvents',
            'rooms',
            'customers',
            'workspaceTypes',
            'roomId',
            'status',
            'date',
            'type',
            'search',
            'todayBookingsCount',
            'activeSubscriptionsCount',
            'upcomingRoomsBookingsCount',
            'activeRoomsBookings'
        ));
    }

    /**
     * Store Booking for a Room or Subscription
     */
    public function storeBooking(Request $request)
    {
        $durationMode = $request->input('duration_mode', 'hourly'); // hourly, weekly, monthly, custom
        $startAt = $request->input('start_at');
        $endAt = $request->input('end_at');

        if ($durationMode === 'weekly' && $startAt) {
            $request->merge(['end_at' => \Carbon\Carbon::parse($startAt)->addWeek()->toDateTimeString()]);
        } elseif ($durationMode === 'monthly' && $startAt) {
            $request->merge(['end_at' => \Carbon\Carbon::parse($startAt)->addMonth()->toDateTimeString()]);
        }

        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'room_id' => 'nullable|exists:rooms,id',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
            'workspace_type_id' => 'nullable|exists:workspace_types,id',
            'notes' => 'nullable|string',
        ]);

        if (empty($data['workspace_type_id'])) {
            $defaultType = \App\Models\WorkspaceType::first();
            $data['workspace_type_id'] = $defaultType ? $defaultType->id : 1;
        }

        $data['booking_number'] = Booking::generateNumber();
        $data['status'] = 'confirmed';
        $data['source'] = 'admin';
        $data['created_by'] = auth()->id();

        // Check for room schedule conflicts if room is specified
        $conflict = false;
        if (!empty($data['room_id'])) {
            $conflict = Booking::where('room_id', $data['room_id'])
                ->whereNotIn('status', ['cancelled', 'no_show'])
                ->where(function ($q) use ($data) {
                    $q->whereBetween('start_at', [$data['start_at'], $data['end_at']])
                      ->orWhereBetween('end_at', [$data['start_at'], $data['end_at']])
                      ->orWhere(function ($sub) use ($data) {
                          $sub->where('start_at', '<=', $data['start_at'])
                              ->where('end_at', '>=', $data['end_at']);
                      });
                })->exists();
        }

        $booking = Booking::create($data);

        $msg = "تم تسجيل الحجز بنجاح برقم ({$booking->booking_number}).";
        if ($conflict) {
            $msg .= " (تنبيه: يوجد حجز آخر متزامن أو قريب في نفس القاعة، يرجى مراجعة الجدول).";
        }

        return redirect()->back()->with('success', $msg);
    }

    /**
     * Update Booking
     */
    public function updateBooking(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'room_id' => 'required|exists:rooms,id',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
            'status' => 'required|in:pending,confirmed,checked_in,completed,cancelled,no_show',
            'notes' => 'nullable|string',
        ]);

        $booking->update($data);

        return redirect()->back()->with('success', "تم تحديث بيانات الحجز ({$booking->booking_number}) بنجاح.");
    }

    /**
     * Delete / Cancel Booking
     */
    public function destroyBooking(Booking $booking)
    {
        $bookingNumber = $booking->booking_number;
        $booking->delete();

        return redirect()->back()->with('success', "تم حذف الحجز ({$bookingNumber}) بنجاح.");
    }

    /**
     * Check-in Booking: Start an active deal/session in the room
     */
    public function checkInBooking(Booking $booking, \App\Services\DealService $dealService)
    {
        // Check if room is available or occupied
        $room = $booking->room;

        $deal = $dealService->start([
            'customer_id' => $booking->customer_id,
            'room_id' => $booking->room_id,
            'workspace_type_id' => $booking->workspace_type_id ?? 1,
            'booking_id' => $booking->id,
            'started_at' => now(),
            'notes' => 'بدء من حجز رقم: ' . $booking->booking_number . ($booking->notes ? ' - ' . $booking->notes : ''),
        ]);

        $booking->update(['status' => 'checked_in']);

        return redirect()->route('cashier', ['deal_id' => $deal->id])
            ->with('success', "تم تسجيل حضور العميل وبدء الجلسة في غرفة ({$room?->name}) وفتح الفاتورة بنجاح.");
    }

    /**
     * Active Deals / Sessions
     */
    public function activeDeals(Request $request)
    {
        $roomFilter = $request->query('room_id');
        $statusFilter = $request->query('status'); // all, occupied, available

        $deals = Deal::open()
            ->with(['customer', 'room', 'workspaceType', 'order.items.product'])
            ->latest('started_at')
            ->get();

        $allRooms = Room::with(['activeDeals.customer', 'activeDeals.workspaceType', 'activeDeals.order.items.product'])
            ->where('status', '!=', 'inactive')
            ->get();

        // Calculate statistics across all active rooms
        $activePeopleCount = $deals->count(); // كم فرد شغال
        $totalCapacity = $allRooms->sum('capacity') ?: 1;
        $totalOccupied = min($activePeopleCount, $totalCapacity);
        $remainingSeats = max(0, $totalCapacity - $activePeopleCount); // باقي كم مقعد
        $occupancyRate = round(($activePeopleCount / $totalCapacity) * 100);

        $occupiedRoomsCount = $allRooms->filter(fn($r) => $r->activeDeals->count() > 0)->count();
        $availableRoomsCount = $allRooms->filter(fn($r) => $r->activeDeals->count() === 0 && $r->status === 'active')->count();

        // Filter rooms list based on user selections
        $rooms = $allRooms;
        if (!empty($roomFilter)) {
            $rooms = $rooms->filter(fn($r) => $r->id == $roomFilter);
        }

        if ($statusFilter === 'occupied') {
            $rooms = $rooms->filter(fn($r) => $r->activeDeals->count() > 0);
        } elseif ($statusFilter === 'available') {
            $rooms = $rooms->filter(fn($r) => $r->activeDeals->count() === 0);
        }

        // Unassigned deals (not bound to any room)
        $unassignedDeals = $deals->whereNull('room_id');

        return view('deals.active', compact(
            'deals',
            'rooms',
            'allRooms',
            'activePeopleCount',
            'totalCapacity',
            'totalOccupied',
            'remainingSeats',
            'occupancyRate',
            'occupiedRoomsCount',
            'availableRoomsCount',
            'unassignedDeals',
            'roomFilter',
            'statusFilter'
        ));
    }

    /**
     * Products & Cafe
     */
    public function products()
    {
        // Seed default suppliers if none exist
        if (\App\Models\Supplier::count() === 0) {
            \App\Models\Supplier::create(['name' => 'مورد البوفيه والمشروبات العامة', 'phone' => '01000000001', 'status' => 'active']);
            \App\Models\Supplier::create(['name' => 'شركة الألبان والمخبوزات', 'phone' => '01100000002', 'status' => 'active']);
            \App\Models\Supplier::create(['name' => 'مورد البن وحبوب القهوة', 'phone' => '01200000003', 'status' => 'active']);
        }

        $products = Product::with(['category', 'supplier', 'ingredients.rawMaterial'])->latest()->paginate(20);
        $totalProducts = Product::count();
        $categories = \App\Models\ProductCategory::where('active', true)->orderBy('name')->get();
        $suppliers = \App\Models\Supplier::where('status', 'active')->orderBy('name')->get();
        $rawMaterials = \App\Models\RawMaterial::where('active', true)->orderBy('name')->get();
        $totalCost = Product::all()->sum(fn($p) => $p->cost);

        return view('products.index', compact('products', 'totalProducts', 'categories', 'suppliers', 'rawMaterials', 'totalCost'));
    }

    /**
     * Store Product
     */
    public function storeProduct(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'selling_price' => 'required|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'category_id' => 'nullable|exists:product_categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'new_supplier_name' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:50',
            'active' => 'nullable|boolean',
            'description' => 'nullable|string|max:1000',
        ]);

        if (!empty($request->new_supplier_name)) {
            $supp = \App\Models\Supplier::firstOrCreate(['name' => trim($request->new_supplier_name)], ['status' => 'active']);
            $data['supplier_id'] = $supp->id;
        }
        unset($data['new_supplier_name']);

        $data['active'] = $request->has('active') ? (bool) $request->active : true;
        $data['unit'] = $data['unit'] ?? 'piece';
        $data['purchase_price'] = $data['purchase_price'] ?? 0;

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'تمت إضافة المنتج وبيانات التكلفة والمورد بنجاح.');
    }

    /**
     * Update Product
     */
    public function updateProduct(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'selling_price' => 'required|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'category_id' => 'nullable|exists:product_categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'new_supplier_name' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:50',
            'active' => 'nullable|boolean',
            'description' => 'nullable|string|max:1000',
        ]);

        if (!empty($request->new_supplier_name)) {
            $supp = \App\Models\Supplier::firstOrCreate(['name' => trim($request->new_supplier_name)], ['status' => 'active']);
            $data['supplier_id'] = $supp->id;
        }
        unset($data['new_supplier_name']);

        $data['active'] = $request->has('active') ? (bool) $request->active : false;
        $data['purchase_price'] = $data['purchase_price'] ?? 0;

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'تم تعديل بيانات المنتج وسعر التكلفة والمورد بنجاح.');
    }

    /**
     * Delete Product
     */
    public function destroyProduct(Product $product)
    {
        try {
            \App\Models\ProductIngredient::where('product_id', $product->id)->delete();
            $product->delete();
            return redirect()->route('products.index')->with('success', 'تم حذف الصنف بنجاح.');
        } catch (\Exception $e) {
            return redirect()->route('products.index')->with('error', 'تعذر حذف الصنف: ' . $e->getMessage());
        }
    }

    /**
     * Save Product Ingredients / Recipe
     */
    public function saveProductIngredients(Request $request, Product $product)
    {
        $request->validate([
            'ingredients' => 'nullable|array',
            'ingredients.*.raw_material_id' => 'required|exists:raw_materials,id',
            'ingredients.*.quantity' => 'required|numeric|min:0.01',
        ]);

        \App\Models\ProductIngredient::where('product_id', $product->id)->delete();

        if ($request->has('ingredients')) {
            foreach ($request->ingredients as $item) {
                if (!empty($item['raw_material_id']) && !empty($item['quantity'])) {
                    \App\Models\ProductIngredient::create([
                        'product_id' => $product->id,
                        'raw_material_id' => $item['raw_material_id'],
                        'quantity' => $item['quantity'],
                    ]);
                }
            }
        }

        // Auto update product cost from recipe
        $recipeCost = $product->calculateRecipeCost();
        if ($recipeCost > 0) {
            $product->purchase_price = $recipeCost;
            $product->save();
        }

        return redirect()->back()->with('success', 'تم حفظ مكونات وخامات الصنف واحتساب التكلفة تلقائياً بنجاح.');
    }

    /**
     * Store Raw Material
     */
    public function storeRawMaterial(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'current_stock' => 'required|numeric|min:0',
            'unit_cost' => 'required|numeric|min:0',
            'minimum_stock' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        \App\Models\RawMaterial::create($data);
        return redirect()->back()->with('success', 'تمت إضافة الخامة إلى المستودع بنجاح.');
    }

    /**
     * Update Raw Material
     */
    public function updateRawMaterial(Request $request, \App\Models\RawMaterial $rawMaterial)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'current_stock' => 'required|numeric|min:0',
            'unit_cost' => 'required|numeric|min:0',
            'minimum_stock' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $rawMaterial->update($data);
        return redirect()->back()->with('success', 'تم تعديل بيانات وتكلفة الخامة بنجاح.');
    }

    /**
     * Add Stock to Raw Material
     */
    public function addStockRawMaterial(Request $request, \App\Models\RawMaterial $rawMaterial)
    {
        $request->validate([
            'added_quantity' => 'required|numeric|min:0.01',
        ]);

        $rawMaterial->increment('current_stock', $request->added_quantity);
        return redirect()->back()->with('success', "تمت إضافة {$request->added_quantity} إلى رصيد {$rawMaterial->name} بنجاح.");
    }

    /**
     * Delete Raw Material
     */
    public function destroyRawMaterial(\App\Models\RawMaterial $rawMaterial)
    {
        $rawMaterial->delete();
        return redirect()->back()->with('success', 'تم حذف الخامة بنجاح.');
    }

    /**
     * Inventory movements & Raw Materials
     */
    public function inventory()
    {
        $products = Product::where('track_inventory', true)->orWhere('active', true)->get();
        $rawMaterials = \App\Models\RawMaterial::orderBy('name')->get();
        $lowStockMaterials = $rawMaterials->filter(fn($m) => $m->is_low_stock);

        return view('inventory.index', compact('products', 'rawMaterials', 'lowStockMaterials'));
    }

 /**
 * Payments list
 */
 public function payments(Request $request)
 {
 $method = $request->query('method');
 $query = Payment::with(['deal.customer', 'deal.room'])->latest('paid_at');

 if ($method && in_array($method, ['cash', 'instapay', 'wallet', 'card'])) {
 $query->where('method', $method);
 }

 $payments = $query->paginate(20)->withQueryString();
 $totalPaid = Payment::where('type', 'income')->sum('amount');
 $cashTotal = Payment::where('type', 'income')->where('method', 'cash')->sum('amount');
 $instapayTotal = Payment::where('type', 'income')->where('method', 'instapay')->sum('amount');
 $walletTotal = Payment::where('type', 'income')->where('method', 'wallet')->sum('amount');

 return view('payments.index', compact('payments', 'totalPaid', 'cashTotal', 'instapayTotal', 'walletTotal', 'method'));
 }
}
