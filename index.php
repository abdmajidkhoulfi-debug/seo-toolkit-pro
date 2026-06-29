<?php
// Dynamic homepage - loads blog posts and injects them into index.html
require_once __DIR__ . '/admin/blog_data.php';

// Load the static HTML content
$html = file_get_contents(__DIR__ . '/index-static.html');

if ($html === false) {
    readfile(__DIR__ . '/index-static.html');
    exit;
}

// Normalize line endings before any processing
$html = str_replace("\r\n", "\n", $html);

// Get recent blog posts
$allPosts = getBlogPosts();
$publishedPosts = array_values(array_filter($allPosts, function($p) {
    return ($p['status'] ?? 'published') === 'published';
}));
$recentBlogPosts = array_slice($publishedPosts, 0, 3);
$blogPostCount = count($publishedPosts);

// Build the blog section HTML
$blogSection = '';

if ($blogPostCount > 0) {
    $blogSection .= "\n    <section class=\"section\">\n      <div class=\"container\">\n        <div class=\"section-head\">\n          <div>\n            <h2>Latest from the blog</h2>\n            <p>Fresh SEO guides, tips, and tutorials to help you rank better.</p>\n          </div>\n          <a href=\"/blog/\" class=\"btn btn-secondary\">View all posts \xE2\x86\x92</a>\n        </div>\n\n        <div class=\"tools-grid\" style=\"grid-template-columns: repeat(" . min(3, $blogPostCount) . ", minmax(0, 1fr));\">";

    foreach ($recentBlogPosts as $post) {
        $readTime = blogReadTime($post['content']);
        $category = esc($post['category'] ?: 'General');
        $date = formatBlogDate($post['created_at']);
        $imageHtml = '';

        if (!empty($post['image'])) {
            $imageHtml = '<img src="' . esc($post['image']) . '" alt="' . esc($post['title']) . '" style="width:100%;height:180px;object-fit:cover;border-radius:16px;margin-bottom:14px;" loading="lazy">';
        }

        $blogSection .= "\n          <article class=\"section-card\" style=\"padding:0;overflow:hidden;\">\n            <div style=\"padding:20px;\">\n              " . $imageHtml . "\n              <div style=\"display:flex;gap:8px;margin-bottom:10px;flex-wrap:wrap;\">\n                <span style=\"font-size:12px;padding:4px 10px;border-radius:999px;background:var(--primary-soft);color:var(--primary);font-weight:600;\">" . $category . "</span>\n                <span style=\"font-size:13px;color:var(--muted);\">" . $date . "</span>\n                <span style=\"font-size:13px;color:var(--muted);\">\xC2\xB7 " . $readTime . " min read</span>\n              </div>\n              <h3 style=\"font-family:var(--font-display);font-size:1.2rem;letter-spacing:-0.03em;margin-bottom:8px;\">\n                <a href=\"/blog/post.php?slug=" . urlencode($post['slug']) . "\" style=\"color:inherit;text-decoration:none;\">" . esc($post['title']) . "</a>\n              </h3>\n              <p style=\"color:var(--muted);font-size:14px;margin-bottom:12px;\">" . esc($post['excerpt']) . "</p>\n              <div style=\"display:flex;justify-content:space-between;align-items:center;\">\n                <span style=\"font-size:13px;color:var(--muted);\">By " . esc($post['author']) . "</span>\n                <a href=\"/blog/post.php?slug=" . urlencode($post['slug']) . "\" style=\"color:var(--primary);font-weight:600;font-size:14px;text-decoration:none;\">Read \xE2\x86\x92</a>\n              </div>\n            </div>\n          </article>";
    }

    $blogSection .= "\n        </div>\n      </div>\n    </section>";
}

// Inject the blog section before the CTA section
$ctaSearch = "<section class=\"section\">\n      <div class=\"container\">\n        <div class=\"cta-card\">";
$replacement = $blogSection . "\n    <section class=\"section\">\n      <div class=\"container\">\n        <div class=\"cta-card\">";

$html = str_replace($ctaSearch, $replacement, $html);

echo $html;
