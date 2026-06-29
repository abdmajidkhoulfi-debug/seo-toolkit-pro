<?php
declare(strict_types=1); 
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('X-Content-Type-Options: nosniff'); 

function respond(bool $ok, array $data = [], int $status = 200): void {
    http_response_code($status); 
    echo json_encode(array_merge(['ok' => $ok], $data), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); 
    exit;
} 

$url = trim($_GET['url']?? ''); 
if ($url === '') {
    respond(false, ['error' => 'Missing URL parameter.'], 400);
} 

if (!filter_var($url, FILTER_VALIDATE_URL)) {
    respond(false, ['error' => 'Invalid URL format.'], 400);
} 

$parts = parse_url($url);
$scheme = strtolower($parts['scheme']?? '');
$host = strtolower($parts['host']?? ''); 

if (!in_array($scheme, ['http', 'https'], true)) {
    respond(false, ['error' => 'Only HTTP and HTTPS URLs are allowed.'], 400);
} 

if ($host === '' || $host === 'localhost') {
    respond(false, ['error' => 'This host is not allowed.'], 400);
} 

if (filter_var($host, FILTER_VALIDATE_IP)) {
    if (!filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
        respond(false, ['error' => 'Private or reserved IPs are not allowed.'], 400);
    }
} 

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $url, 
    CURLOPT_RETURNTRANSFER => true, 
    CURLOPT_FOLLOWLOCATION => true, 
    CURLOPT_MAXREDIRS => 8, 
    CURLOPT_CONNECTTIMEOUT => 15, 
    CURLOPT_TIMEOUT => 30, 
    CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; SEO-Toolkit/1.0)', 
    CURLOPT_SSL_VERIFYPEER => true, 
    CURLOPT_SSL_VERIFYHOST => 2, 
    CURLOPT_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS, 
    CURLOPT_REDIR_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS, 
    CURLOPT_ENCODING => '', 
    CURLOPT_HTTPHEADER => [
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        'Cache-Control: no-cache'
    ],
]); 

$html = curl_exec($ch);
$curlError = curl_error($ch);
$httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
$contentType = (string) curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
$finalUrl = (string) curl_getinfo($ch, CURLINFO_EFFECTIVE_URL); 

if ($html === false) {
    curl_close($ch); 
    respond(false, ['error' => 'cURL error: '. ($curlError?: 'Unknown fetch error')], 502);
} 

curl_close($ch); 

if ($httpCode >= 400) {
    respond(false, ['error' => 'Target server returned HTTP '. $httpCode. '.'], 502);
} 

if ($contentType && stripos($contentType, 'text/html') === false && stripos($contentType, 'application/xhtml+xml') === false) {
    respond(false, ['error' => 'The target did not return an HTML document.'], 415);
} 

respond(true, ['html' => $html, 'final_url' => $finalUrl, 'http_code' => $httpCode]);