<?php

/**
 * Laravel - Dynamic Entry Point Fallback
 * Handles requests seamlessly if web server DocumentRoot points to root directory instead of /public.
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? ''
);

if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}

require_once __DIR__.'/public/index.php';
