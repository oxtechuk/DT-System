<?php

/**
 * DT-System Emoji Removal & Cleanup Script
 * Cleans all emojis from views, controllers, and services,
 * replacing them with clean SVG icons or professional typography.
 */

$rootDir = __DIR__;
$targetDirs = [
    $rootDir . '/resources/views',
    $rootDir . '/app',
];

echo "==============================================\n";
echo "  DT-System: Emoji Removal & Cleanup Script  \n";
echo "==============================================\n\n";

// Map of specific emoji containers / phrases to clean SVGs or clean text
$phraseReplacements = [
    // Status text in controllers and models
    'ناجح ✅'              => 'ناجح',
    'فشل ❌'              => 'فشل',
    'قيد الانتظار ⏳'      => 'قيد الانتظار',
    'تم التحديد تلقائياً من جلستك ✅' => 'تم التحديد تلقائياً من جلستك',
    'مرتبط بجلستك الحالية ✅' => 'مرتبط بجلستك الحالية',
    'تم تحقيق الشرط ✅'   => 'تم تحقيق الشرط',
    'متزامن ✅'           => 'متزامن',
    'تم التوصيل ✅'       => 'تم التوصيل',
    'جاري التحضير ☕'      => 'جاري التحضير',
    'إظهار التوكن 👁️'     => 'إظهار التوكن',
    'إظهار التوكن 👁'      => 'إظهار التوكن',
    'إخفاء التوكن 🙈'     => 'إخفاء التوكن',
    'اختبار الاتصال ⚡'    => 'اختبار الاتصال',
    'التقرير التحليلي المالي 📊' => 'التقرير التحليلي المالي',
    'حفظ إعدادات HubSpot 💾' => 'حفظ إعدادات HubSpot',
    'طباعة التقرير 🖨️'   => 'طباعة التقرير',
    'طباعة التقرير 🖨'    => 'طباعة التقرير',
    'إظهار كافة الأصناف 🍽️' => 'إظهار كافة الأصناف',
    'إظهار كافة الأصناف 🍽' => 'إظهار كافة الأصناف',
    'إرسال الطلب للكاشير 🚀' => 'إرسال الطلب للكاشير',
    'تصفح المنيو ☕'       => 'تصفح المنيو',
    'طلبات الموبايل ☕'    => 'طلبات الموبايل',
    'قهوة ☕'             => 'قهوة',
    'مرحباً بك! 🎉'       => 'مرحباً بك!',
    'هدية الولاء 🎁'      => 'هدية الولاء',
    'كود الإحالة 🤝'      => 'كود الإحالة',
    'تثبيت التطبيق 📱'    => 'تثبيت التطبيق',

    // Specific emoji icons replaced with clean SVGs
    '<span class="text-xl">☕</span>' => '<svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3"/></svg>',
    '<div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-amber-500/20 to-orange-500/20 border border-amber-500/30 text-amber-400 flex items-center justify-center text-lg font-bold shadow-inner">☕</div>' => '<div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-indigo-500/20 to-purple-500/20 border border-indigo-500/30 text-indigo-400 flex items-center justify-center shadow-inner"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3"/></svg></div>',
    '<div class="w-9 h-9 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center font-bold">☕</div>' => '<div class="w-9 h-9 rounded-xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center font-bold"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3"/></svg></div>',
    '<div class="w-16 h-16 mx-auto rounded-3xl bg-slate-800/80 border border-slate-700/80 flex items-center justify-center text-3xl mb-3 shadow-inner">🔍</div>' => '<div class="w-16 h-16 mx-auto rounded-3xl bg-slate-800/80 border border-slate-700/80 flex items-center justify-center text-slate-400 mb-3 shadow-inner"><svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></div>',
    '<div class="size-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg font-bold">👥</div>' => '<div class="size-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center"><svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>',
    '<div class="size-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg font-bold">💼</div>' => '<div class="size-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center"><svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect width="20" height="14" x="2" y="7" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg></div>',
    '<div class="size-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg font-bold">☕</div>' => '<div class="size-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center"><svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3"/></svg></div>',
    '<div class="size-11 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg font-bold">⚡</div>' => '<div class="size-11 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center"><svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg></div>',
    '<div class="size-12 rounded-2xl bg-primary/10 border border-primary/20 text-primary flex items-center justify-center text-2xl shadow-sm shrink-0">📊</div>' => '<div class="size-12 rounded-2xl bg-primary/10 border border-primary/20 text-primary flex items-center justify-center shadow-sm shrink-0"><svg class="size-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg></div>',
    '<span class="size-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">💰</span>' => '<span class="size-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center"><svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></span>',
    '<span class="size-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">👥</span>' => '<span class="size-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center"><svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></span>',
    '<span class="size-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-sm">💼</span>' => '<span class="size-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center"><svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect width="20" height="14" x="2" y="7" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg></span>',
    '<span class="text-xl">🚀</span>' => '<svg class="size-5 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>',
    '<span class="text-xl">⚙️</span>' => '<svg class="size-5 text-default-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>',
    '<span class="text-xl">💡</span>' => '<svg class="size-5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>',
    '<span class="text-lg">📜</span>' => '<svg class="size-5 text-default-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>',
    '<span class="text-lg">🎯</span>' => '<svg class="size-5 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>',
    '<span class="text-lg">🌐</span>' => '<svg class="size-5 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>',
    '<span class="text-lg">☕</span>' => '<svg class="size-5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3"/></svg>',
    '<span class="text-base shrink-0">🔒</span>' => '<svg class="size-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>',
    '<span class="text-blue-600">👤 عميل</span>' => '<span class="text-blue-600 font-bold">عميل</span>',
    '<span class="text-emerald-600">💼 صفقة/حجز</span>' => '<span class="text-emerald-600 font-bold">صفقة/حجز</span>',
    '<span class="text-amber-600">☕ منتج</span>' => '<span class="text-amber-600 font-bold">منتج</span>',
    '<span class="text-default-600">🌐 عام</span>' => '<span class="text-default-600 font-bold">عام</span>',
    '<span>👥 مزامنة العملاء</span>' => '<span>مزامنة العملاء</span>',
    '<span>💼 مزامنة الصفقات</span>' => '<span>مزامنة الصفقات</span>',
    '<span>☕ مزامنة المنيو</span>' => '<span>مزامنة المنيو</span>',
    '<span>📥 سحب من HubSpot</span>' => '<span>سحب من HubSpot</span>',
    '<span>⚡ مزامنة شاملة للكل</span>' => '<span>مزامنة شاملة للكل</span>',
];

// Comprehensive Unicode Emoji pattern for any remaining emojis
$emojiPattern = '/[\x{1F000}-\x{1F9FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}\x{1F1E6}-\x{1F1FF}\x{1FA00}-\x{1FAFF}\x{FE00}-\x{FE0F}]/u';

$totalModified = 0;
$totalEmojisRemoved = 0;

foreach ($targetDirs as $dir) {
    if (!is_dir($dir)) continue;

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($iterator as $file) {
        if (!$file->isFile()) continue;
        $ext = $file->getExtension();
        if (!in_array($ext, ['php'])) continue;

        $path = $file->getPathname();
        $content = file_get_contents($path);
        $original = $content;

        // Step 1: Apply predefined clean replacements
        foreach ($phraseReplacements as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }

        // Step 2: Remove any remaining emojis safely without collapsing line breaks
        if (preg_match_all($emojiPattern, $content, $matches)) {
            $count = count($matches[0]);
            $totalEmojisRemoved += $count;
            $content = preg_replace($emojiPattern, '', $content);
        }

        // Step 3: Only cleanup horizontal spaces (never touch \r or \n)
        $content = preg_replace('/[^\S\r\n]{2,}/u', ' ', $content);

        if ($content !== $original) {
            file_put_contents($path, $content);
            $relPath = str_replace($rootDir . DIRECTORY_SEPARATOR, '', $path);
            echo "Cleaned emojis from: {$relPath}\n";
            $totalModified++;
        }
    }
}

// In portal/menu.blade.php specifically: clean up getCategoryIcon helper so it returns clean SVGs
$menuPath = $rootDir . '/resources/views/portal/menu.blade.php';
if (file_exists($menuPath)) {
    $menuContent = file_get_contents($menuPath);
    $cleanHelper = "@php
    function getCategorySvg(\$code, \$name) {
        \$code = strtolower(\$code ?? '');
        \$name = mb_strtolower(\$name ?? '');
        if (str_contains(\$code, 'hot') || str_contains(\$name, 'ساخن')) {
            return '<svg class=\"w-5 h-5 text-amber-500\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3\"/></svg>';
        }
        if (str_contains(\$code, 'cold') || str_contains(\$name, 'بارد') || str_contains(\$name, 'عصير')) {
            return '<svg class=\"w-5 h-5 text-cyan-500\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z\"/></svg>';
        }
        if (str_contains(\$code, 'snack') || str_contains(\$name, 'سناك') || str_contains(\$name, 'أكل')) {
            return '<svg class=\"w-5 h-5 text-orange-500\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z\"/></svg>';
        }
        if (str_contains(\$code, 'service') || str_contains(\$name, 'خدم')) {
            return '<svg class=\"w-5 h-5 text-indigo-500\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" viewBox=\"0 0 24 24\"><polyline points=\"6 9 6 2 18 2 18 9\"/><path d=\"M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2\"/><rect width=\"12\" height=\"8\" x=\"6\" y=\"14\"/></svg>';
        }
        return '<svg class=\"w-5 h-5 text-indigo-400\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" viewBox=\"0 0 24 24\"><circle cx=\"12\" cy=\"12\" r=\"10\"/><path d=\"m4.93 4.93 4.24 4.24\"/><path d=\"m14.83 9.17 4.24-4.24\"/><path d=\"m14.83 14.83 4.24 4.24\"/><path d=\"m9.17 14.83-4.24 4.24\"/></svg>';
    }
@endphp";

    $menuContent = preg_replace('/@php\s+function getCategoryIcon.*?@endphp/s', $cleanHelper, $menuContent);
    $menuContent = str_replace('getCategoryIcon', 'getCategorySvg', $menuContent);
    $menuContent = str_replace('{{ $itemEmoji }}', '{!! $itemEmoji !!}', $menuContent);
    $menuContent = str_replace('{{ $catEmoji }}', '{!! $catEmoji !!}', $menuContent);
    $menuContent = str_replace('<span class="text-sm">🍽️</span>', '<svg class="w-4 h-4 text-indigo-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>', $menuContent);
    $menuContent = str_replace('🍽️', '', $menuContent);
    file_put_contents($menuPath, $menuContent);
}

echo "\n==============================================\n";
echo "Finished! Total files modified: {$totalModified}\n";
echo "Total emojis stripped: {$totalEmojisRemoved}\n";
echo "==============================================\n";
