<?php
/**
 * SEO Proxy for Keyword Density Checker
 * Fetches HTML content from external URLs securely
 */

declare(strict_types=1);

// Set JSON response headers
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('X-Content-Type-Options: nosniff');

/**
 * Send JSON response
 */
function sendResponse(bool $success, array $data = [], int $statusCode = 200): void {
    http_response_code($statusCode);
    echo json_encode(array_merge(['ok' => $success], $data), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

// Get and validate URL parameter
$url = trim($_GET['url'] ?? '');
if ($url === '') {
    sendResponse(false, ['error' => 'Missing URL parameter. Please provide a URL to analyze.'], 400);
}

// Validate URL format
if (!filter_var($url, FILTER_VALIDATE_URL)) {
    sendResponse(false, ['error' => 'Invalid URL format. Please enter a complete URL including http:// or https://'], 400);
}

// Parse and validate URL components
$parts = parse_url($url);
$scheme = strtolower($parts['scheme'] ?? '');
$host = strtolower($parts['host'] ?? '');

// Allow only HTTP/HTTPS
if (!in_array($scheme, ['http', 'https'], true)) {
    sendResponse(false, ['error' => 'Only HTTP and HTTPS URLs are allowed.'], 400);
}

// Block empty or localhost hosts
if ($host === '' || $host === 'localhost') {
    sendResponse(false, ['error' => 'This host is not allowed for security reasons.'], 400);
}

// Block private/reserved IPs
if (filter_var($host, FILTER_VALIDATE_IP)) {
    if (!filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
        sendResponse(false, ['error' => 'Private or reserved IP addresses are not allowed.'], 400);
    }
}

// Initialize cURL
$ch = curl_init();

// Configure cURL options
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_MAXREDIRS => 8,
    CURLOPT_CONNECTTIMEOUT => 15,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; SEO-Toolkit/1.0; +https://seotoolkit.com/bot)',
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_SSL_VERIFYHOST => 2,
    CURLOPT_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS,
    CURLOPT_REDIR_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS,
    CURLOPT_ENCODING => '', // Accept any encoding
    CURLOPT_HTTPHEADER => [
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        'Accept-Language: en-US,en;q=0.9',
        'Cache-Control: no-cache'
    ],
    CURLOPT_FRESH_CONNECT => true,
    CURLOPT_FORBID_REUSE => true,
]);

// Execute cURL request
$html = curl_exec($ch);
$curlError = curl_error($ch);
$curlErrno = curl_errno($ch);
$httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
$contentType = (string)curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
$finalUrl = (string)curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);

curl_close($ch);

// Handle cURL errors
if ($html === false || $curlError) {
    $errorMsg = 'Failed to fetch URL';
    if ($curlErrno === 28) {
        $errorMsg = 'Connection timeout. The server took too long to respond.';
    } elseif ($curlErrno === 6 || $curlErrno === 7) {
        $errorMsg = 'Cannot connect to the server. Please check the URL.';
    } elseif ($curlError) {
        $errorMsg = 'cURL error: ' . $curlError;
    }
    sendResponse(false, ['error' => $errorMsg], 502);
}

// Handle HTTP errors
if ($httpCode >= 400) {
    $errorMsg = $httpCode === 404 ? 'Page not found (404)' : "Server returned HTTP {$httpCode} error";
    sendResponse(false, ['error' => $errorMsg, 'http_code' => $httpCode], 502);
}

// Check content type (allow HTML and XHTML)
if ($contentType && stripos($contentType, 'text/html') === false && stripos($contentType, 'application/xhtml+xml') === false) {
    sendResponse(false, ['error' => 'The URL does not return HTML content. Only web pages can be analyzed.', 'content_type' => $contentType], 415);
}

// Success response
sendResponse(true, [
    'html' => $html,
    'final_url' => $finalUrl,
    'http_code' => $httpCode,
    'content_length' => strlen($html)
]);