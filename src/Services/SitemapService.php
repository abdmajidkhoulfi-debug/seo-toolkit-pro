<?php
namespace App\Services;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Core\Database;

class SitemapService
{
    public static function generateSitemap(): string
    {
        $appConfig = require __DIR__ . '/../../config/app.php';
        $baseUrl = $appConfig['app']['url'];

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Homepage
        $xml .= self::url($baseUrl, '1.0', 'daily');

        // Static pages
        $staticPages = ['about', 'contact', 'privacy-policy', 'terms', 'disclaimer'];
        foreach ($staticPages as $page) {
            $xml .= self::url("{$baseUrl}/{$page}", '0.7', 'monthly');
        }

        // Tools index page
        $xml .= self::url("{$baseUrl}/tools", '0.9', 'daily');

        // Tool pages
        foreach ($appConfig['tools'] as $tool) {
            $xml .= self::url("{$baseUrl}/tools/{$tool['slug']}", '0.9', 'weekly');
        }

        // Blog
        $xml .= self::url("{$baseUrl}/blog", '0.9', 'daily');

        // Blog posts
        $posts = Post::getAllForSitemap();
        foreach ($posts as $post) {
            $lastmod = date('c', strtotime($post['updated_at']));
            $xml .= self::url("{$baseUrl}/blog/{$post['slug']}", '0.8', 'weekly', $lastmod);
        }

        // Categories
        $categories = Category::getAll();
        foreach ($categories as $cat) {
            if ($cat['post_count'] > 0) {
                $xml .= self::url("{$baseUrl}/blog/category/{$cat['slug']}", '0.6', 'weekly');
            }
        }

        // Tags
        $tags = Tag::getAll();
        foreach ($tags as $tag) {
            if ($tag['post_count'] > 0) {
                $xml .= self::url("{$baseUrl}/blog/tag/{$tag['slug']}", '0.5', 'weekly');
            }
        }

        // Programmatic SEO landing pages
        $landingPages = self::getLandingPages();
        foreach ($landingPages as $page) {
            $xml .= self::url("{$baseUrl}{$page['path']}", $page['priority'] ?? '0.5', $page['changefreq'] ?? 'monthly');
        }

        $xml .= '</urlset>';

        return $xml;
    }

    public static function generateImageSitemap(): string
    {
        $appConfig = require __DIR__ . '/../../config/app.php';
        $baseUrl = $appConfig['app']['url'];
        $db = Database::getInstance();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

        $posts = Post::getAllForSitemap();
        foreach ($posts as $post) {
            $fullPost = Post::findBySlug($post['slug']);
            if ($fullPost && $fullPost['featured_image']) {
                $xml .= '<url>';
                $xml .= "<loc>{$baseUrl}/blog/{$post['slug']}</loc>";
                $xml .= '<image:image>';
                $xml .= '<image:loc>' . $baseUrl . $fullPost['featured_image'] . '</image:loc>';
                if ($fullPost['alt_text']) {
                    $xml .= '<image:caption>' . htmlspecialchars($fullPost['alt_text']) . '</image:caption>';
                }
                $xml .= '</image:image>';
                $xml .= '</url>';
            }
        }

        $xml .= '</urlset>';
        return $xml;
    }

    public static function generateRss(): string
    {
        $appConfig = require __DIR__ . '/../../config/app.php';
        $baseUrl = $appConfig['app']['url'];
        $siteName = $appConfig['app']['name'];

        $posts = Post::getPublished(1, 50);

        $rss = '<?xml version="1.0" encoding="UTF-8"?>';
        $rss .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:content="http://purl.org/rss/1.0/modules/content/">';
        $rss .= '<channel>';
        $rss .= "<title>{$siteName} Blog</title>";
        $rss .= "<link>{$baseUrl}/blog</link>";
        $rss .= '<description>Latest SEO guides, tutorials, and tips</description>';
        $rss .= "<atom:link href=\"{$baseUrl}/feed.xml\" rel=\"self\" type=\"application/rss+xml\"/>";
        $rss .= '<language>en-us</language>';
        $rss .= "<lastBuildDate>" . date('r') . "</lastBuildDate>";

        foreach ($posts['posts'] as $post) {
            $rss .= '<item>';
            $rss .= "<title>" . htmlspecialchars($post['title']) . "</title>";
            $rss .= "<link>{$baseUrl}/blog/{$post['slug']}</link>";
            $rss .= "<guid>{$baseUrl}/blog/{$post['slug']}</guid>";
            $rss .= "<pubDate>" . date('r', strtotime($post['published_at'])) . "</pubDate>";
            $rss .= "<author>{$post['author']}</author>";
            if ($post['category_name']) {
                $rss .= "<category>{$post['category_name']}</category>";
            }
            $rss .= "<description>" . htmlspecialchars($post['excerpt'] ?: strip_tags(mb_substr($post['content'], 0, 200))) . "</description>";
            $rss .= '<content:encoded><![CDATA[' . $post['content'] . ']]></content:encoded>';
            $rss .= '</item>';
        }

        $rss .= '</channel></rss>';
        return $rss;
    }

    public static function generateRobotsTxt(): string
    {
        $appConfig = require __DIR__ . '/../../config/app.php';
        $baseUrl = $appConfig['app']['url'];

        $robots = "User-agent: *\n";
        $robots .= "Allow: /\n\n";
        $robots .= "Disallow: /admin/\n";
        $robots .= "Disallow: /assets/\n";
        $robots .= "Disallow: /storage/\n";
        $robots .= "Disallow: /src/\n";
        $robots .= "Disallow: /config/\n";
        $robots .= "Disallow: /migrations/\n";
        $robots .= "Disallow: /vendor/\n";
        $robots .= "Disallow: /cgi-bin/\n\n";
        $robots .= "Sitemap: {$baseUrl}/sitemap.xml\n";
        $robots .= "Sitemap: {$baseUrl}/sitemap-images.xml\n";

        return $robots;
    }

    public static function getLandingPages(): array
    {
        return [
            ['path' => '/best-seo-tools-for-shopify', 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['path' => '/best-seo-tools-for-lawyers', 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['path' => '/best-seo-tools-for-dentists', 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['path' => '/best-seo-tools-for-restaurants', 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['path' => '/best-seo-tools-for-real-estate', 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['path' => '/best-seo-tools-for-ecommerce', 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['path' => '/best-seo-tools-for-small-business', 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['path' => '/keyword-density-checker-for-html', 'priority' => '0.4', 'changefreq' => 'monthly'],
            ['path' => '/keyword-density-checker-for-wordpress', 'priority' => '0.4', 'changefreq' => 'monthly'],
            ['path' => '/meta-tag-generator-for-wordpress', 'priority' => '0.4', 'changefreq' => 'monthly'],
            ['path' => '/meta-tag-generator-for-shopify', 'priority' => '0.4', 'changefreq' => 'monthly'],
            ['path' => '/meta-tag-generator-for-woocommerce', 'priority' => '0.4', 'changefreq' => 'monthly'],
            ['path' => '/schema-generator-for-local-business', 'priority' => '0.4', 'changefreq' => 'monthly'],
            ['path' => '/schema-generator-for-faq-pages', 'priority' => '0.4', 'changefreq' => 'monthly'],
            ['path' => '/schema-generator-for-recipe-sites', 'priority' => '0.4', 'changefreq' => 'monthly'],
            ['path' => '/robots-txt-generator-for-wordpress', 'priority' => '0.4', 'changefreq' => 'monthly'],
            ['path' => '/seo-analyzer-for-beginners', 'priority' => '0.4', 'changefreq' => 'monthly'],
            ['path' => '/url-extractor-for-seo-audits', 'priority' => '0.4', 'changefreq' => 'monthly'],
        ];
    }

    private static function url(string $loc, string $priority = '0.5', string $changefreq = 'monthly', ?string $lastmod = null): string
    {
        $url = '<url>';
        $url .= "<loc>" . htmlspecialchars($loc) . "</loc>";
        $url .= "<changefreq>{$changefreq}</changefreq>";
        $url .= "<priority>{$priority}</priority>";
        if ($lastmod) {
            $url .= "<lastmod>{$lastmod}</lastmod>";
        }
        $url .= '</url>';
        return $url;
    }
}
