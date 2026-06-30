<?php
/**
 * PFSRV SEO - Main Entry Point
 */

// Start session
session_start();

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

// Timezone
date_default_timezone_set('UTC');

// Autoload
require_once __DIR__ . '/../src/autoload.php';

// Load configuration
$config = require __DIR__ . '/../config/app.php';

// Initialize database
\App\Core\Database::getInstance($config['database']);

// Initialize SEO service
\App\Services\SEOService::init();

// Share common data across all views
$config = require __DIR__ . '/../config/app.php';
\App\Core\View::share('config', $config);
\App\Core\View::share('siteName', \App\Models\Setting::get('site_name', $config['app']['name']));
\App\Core\View::share('siteTagline', \App\Models\Setting::get('site_tagline', $config['app']['tagline']));
\App\Core\View::share('headerCode', \App\Models\Setting::get('header_code', ''));
\App\Core\View::share('footerCode', \App\Models\Setting::get('footer_code', ''));

// Create request
$request = new \App\Core\Request();

// Create router
$router = new \App\Core\Router($request);

// ----- REDIRECT OLD .HTML URLs TO CLEAN URLs -----

$router->get('/contact.html', function() {
    header('Location: /contact', true, 301);
    exit;
});

$router->get('/about.html', function() {
    header('Location: /about', true, 301);
    exit;
});

$router->get('/privacy-policy.html', function() {
    header('Location: /privacy-policy', true, 301);
    exit;
});

$router->get('/terms.html', function() {
    header('Location: /terms', true, 301);
    exit;
});

$router->get('/disclaimer.html', function() {
    header('Location: /disclaimer', true, 301);
    exit;
});

// ----- SEO ROUTES -----

// Sitemaps & SEO files
$router->get('/sitemap.xml', function() {
    header('Content-Type: application/xml');
    echo \App\Services\SitemapService::generateSitemap();
});

$router->get('/sitemap-images.xml', function() {
    header('Content-Type: application/xml');
    echo \App\Services\SitemapService::generateImageSitemap();
});

$router->get('/feed.xml', function() {
    header('Content-Type: application/rss+xml');
    echo \App\Services\SitemapService::generateRss();
});

$router->get('/robots.txt', function() {
    header('Content-Type: text/plain');
    echo \App\Services\SitemapService::generateRobotsTxt();
});

// ----- HOME PAGES -----

$router->get('/', [\App\Controllers\HomeController::class, 'index']);
$router->get('/about', [\App\Controllers\HomeController::class, 'about']);
$router->get('/contact', [\App\Controllers\HomeController::class, 'contact']);
$router->get('/privacy-policy', [\App\Controllers\HomeController::class, 'privacy']);
$router->get('/terms', [\App\Controllers\HomeController::class, 'terms']);
$router->get('/disclaimer', [\App\Controllers\HomeController::class, 'disclaimer']);

// Newsletter
$router->post('/subscribe', [\App\Controllers\HomeController::class, 'subscribe']);

// ----- TOOLS -----

$router->get('/tools', [\App\Controllers\ToolController::class, 'index']);
$router->get('/tools/{slug}', [\App\Controllers\ToolController::class, 'show']);

// ----- BLOG -----

$router->get('/blog', [\App\Controllers\BlogController::class, 'index']);
$router->get('/blog/category/{slug}', [\App\Controllers\BlogController::class, 'index']);
$router->get('/blog/tag/{slug}', [\App\Controllers\BlogController::class, 'index']);
$router->get('/blog/{slug}', [\App\Controllers\BlogController::class, 'show']);
$router->post('/blog/{slug}/comment', [\App\Controllers\BlogController::class, 'comment']);

// ----- ADMIN -----

// Admin auth middleware
$adminMiddleware = function($request) {
    if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
        // Allow access to login page
        if ($request->getUri() === '/admin' || $request->getUri() === '/admin/') {
            return true;
        }
        return true; // Let the controller handle redirect
    }
    return true;
};

$router->get('/admin', [\App\Controllers\AdminController::class, 'login']);
$router->post('/admin', [\App\Controllers\AdminController::class, 'login']);
$router->get('/admin/dashboard', [\App\Controllers\AdminController::class, 'dashboard']);
$router->post('/admin/settings', [\App\Controllers\AdminController::class, 'saveSettings']);

// Blog management
$router->get('/admin/posts', [\App\Controllers\AdminController::class, 'posts']);
$router->get('/admin/posts/create', [\App\Controllers\AdminController::class, 'postEdit']);
$router->post('/admin/posts/create', [\App\Controllers\AdminController::class, 'postEdit']);
$router->get('/admin/posts/edit/{id}', [\App\Controllers\AdminController::class, 'postEdit']);
$router->post('/admin/posts/edit/{id}', [\App\Controllers\AdminController::class, 'postEdit']);
$router->post('/admin/posts/delete/{id}', [\App\Controllers\AdminController::class, 'postDelete']);

// Categories
$router->get('/admin/categories', [\App\Controllers\AdminController::class, 'categories']);
$router->post('/admin/categories', [\App\Controllers\AdminController::class, 'categories']);
$router->post('/admin/categories/delete/{id}', [\App\Controllers\AdminController::class, 'categoryDelete']);

// Comments
$router->get('/admin/comments', [\App\Controllers\AdminController::class, 'comments']);
$router->post('/admin/comments/approve/{id}', [\App\Controllers\AdminController::class, 'commentApprove']);
$router->post('/admin/comments/delete/{id}', [\App\Controllers\AdminController::class, 'commentDelete']);

// Subscribers
$router->get('/admin/subscribers', [\App\Controllers\AdminController::class, 'subscribers']);

// Media
$router->get('/admin/media', [\App\Controllers\AdminController::class, 'media']);
$router->post('/admin/media', [\App\Controllers\AdminController::class, 'media']);

// Logout
$router->get('/admin/logout', [\App\Controllers\AdminController::class, 'logout']);

// ----- PROGRAMMATIC LANDING PAGES (must be last - catch-all) -----

$router->get('/{slug}', function(\App\Core\Request $request, array $params) {
    $slug = $params['slug'] ?? '';

    // Only match specific known landing page slugs
    $knownSlugs = [
        'best-seo-tools-for-shopify', 'best-seo-tools-for-lawyers', 'best-seo-tools-for-dentists',
        'best-seo-tools-for-restaurants', 'best-seo-tools-for-real-estate', 'best-seo-tools-for-ecommerce',
        'best-seo-tools-for-small-business', 'keyword-density-checker-for-html',
        'keyword-density-checker-for-wordpress', 'meta-tag-generator-for-wordpress',
        'meta-tag-generator-for-shopify', 'meta-tag-generator-for-woocommerce',
        'schema-generator-for-local-business', 'schema-generator-for-faq-pages',
        'schema-generator-for-recipe-sites', 'robots-txt-generator-for-wordpress',
        'seo-analyzer-for-beginners', 'url-extractor-for-seo-audits',
    ];

    if (!in_array($slug, $knownSlugs)) {
        \App\Core\View::notFound();
        return;
    }

    $controller = new \App\Controllers\LandingController();
    $controller->show($request, $params);
});

// ----- DISPATCH -----

$router->dispatch();
