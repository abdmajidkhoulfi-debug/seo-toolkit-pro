<?php
// Router for PHP built-in server
// Ensures index.php is executed instead of serving index.html directly

$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);

// If the root is requested, serve index.php instead of index.html
if ($path === '/' || $path === '') {
    require __DIR__ . '/index.php';
    return true;
}

// For admin/blog pages, serve PHP files directly
if (preg_match('#^/admin/#', $path)) {
    return false; // Let PHP server handle it
}

if (preg_match('#^/blog/#', $path)) {
    return false; // Let PHP server handle it
}

// For all other files, let the server serve them as-is
return false;
