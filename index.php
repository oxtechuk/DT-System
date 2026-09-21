<?php

/**
 * Forward cleanly to Dashbourd/public/index.php without browser URL redirection.
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? ''
);

if ($uri !== '/' && file_exists(__DIR__ . '/Dashbourd/public' . $uri)) {
    return false;
}

require_once __DIR__ . '/Dashbourd/public/index.php';
