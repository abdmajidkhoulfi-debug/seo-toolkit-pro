<?php
namespace App\Services;

use App\Helpers\SEO;
use App\Models\Setting;

class SEOService
{
    private static array $config = [];

    public static function init(): void
    {
        $settings = Setting::getAll();
        $appConfig = require __DIR__ . '/../../config/app.php';
        $app = $appConfig['app'];
        $seo = $appConfig['seo'];

        self::$config = [
            'name' => $settings['site_name'] ?? $app['name'],
            'description' => $app['description'],
            'url' => $app['url'],
            'logo' => $app['logo'],
            'default_image' => $app['url'] . $seo['default_image'],
            'twitter_handle' => $seo['twitter_handle'],
            'separator' => $seo['separator'],
        ];

        SEO::init(self::$config);
    }

    public static function page(array $overrides = []): array
    {
        return array_merge([
            'title' => '',
            'description' => '',
            'url' => '',
            'image' => '',
            'og_type' => 'website',
            'locale' => 'en_US',
            'robots' => 'index, follow, max-image-preview:large',
        ], $overrides);
    }

    public static function toolPage(string $toolSlug, string $title = '', string $description = ''): array
    {
        $appConfig = require __DIR__ . '/../../config/app.php';
        $tools = $appConfig['tools'];
        $tool = null;

        foreach ($tools as $t) {
            if ($t['slug'] === $toolSlug) {
                $tool = $t;
                break;
            }
        }

        if (!$tool) {
            return self::page();
        }

        return self::page([
            'title' => $title ?: $tool['name'],
            'description' => $description ?: $tool['description'],
            'url' => (self::$config['url'] ?? '') . '/tools/' . $toolSlug,
            'og_type' => 'website',
        ]);
    }

    public static function blogPost(array $post): array
    {
        return self::page([
            'title' => $post['meta_title'] ?: $post['title'],
            'description' => $post['meta_description'] ?: SEO::truncate($post['excerpt'] ?: $post['content']),
            'url' => (self::$config['url'] ?? '') . '/blog/' . $post['slug'],
            'image' => $post['og_image'] ?: ($post['featured_image'] ? (self::$config['url'] ?? '') . $post['featured_image'] : ''),
            'og_type' => 'article',
        ]);
    }

    public static function blogIndex(int $page = 1): array
    {
        $pagination = $page > 1 ? " - Page {$page}" : '';
        return self::page([
            'title' => "Blog{$pagination}",
            'description' => 'Expert SEO guides, tutorials, and tips. Learn technical SEO, keyword research, content marketing, and more.',
            'url' => (self::$config['url'] ?? '') . '/blog' . ($page > 1 ? "?page={$page}" : ''),
            'og_type' => 'blog',
        ]);
    }

    public static function categoryPage(string $name, string $description = '', int $page = 1): array
    {
        $pagination = $page > 1 ? " - Page {$page}" : '';
        return self::page([
            'title' => "{$name}{$pagination}",
            'description' => $description ?: "Browse all articles in the {$name} category.",
            'url' => (self::$config['url'] ?? '') . '/blog/category/' . SEO::slugify($name) . ($page > 1 ? "?page={$page}" : ''),
        ]);
    }

    public static function getConfig(): array
    {
        return self::$config;
    }
}
