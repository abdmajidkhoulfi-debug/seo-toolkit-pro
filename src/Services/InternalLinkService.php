<?php
namespace App\Services;

use App\Models\Post;
use App\Models\Category;

class InternalLinkService
{
    private static ?array $toolLinks = null;
    private static ?array $allPosts = null;

    public static function getToolLinks(): array
    {
        if (self::$toolLinks === null) {
            $appConfig = require __DIR__ . '/../../config/app.php';
            self::$toolLinks = [];
            foreach ($appConfig['tools'] as $tool) {
                self::$toolLinks[] = [
                    'name' => $tool['name'],
                    'url' => "/tools/{$tool['slug']}",
                    'description' => $tool['description'],
                ];
            }
        }
        return self::$toolLinks;
    }

    public static function getRelatedTools(string $currentSlug, int $limit = 3): array
    {
        $tools = self::getToolLinks();
        $filtered = array_values(array_filter($tools, fn($t) => !str_contains($t['url'], $currentSlug)));
        shuffle($filtered);
        return array_slice($filtered, 0, $limit);
    }

    public static function getCategoryLinks(): array
    {
        $categories = Category::getAll();
        $links = [];
        foreach ($categories as $cat) {
            if ($cat['post_count'] > 0) {
                $links[] = [
                    'name' => $cat['name'],
                    'url' => "/blog/category/{$cat['slug']}",
                    'count' => $cat['post_count'],
                ];
            }
        }
        return $links;
    }

    public static function injectContentLinks(string $content): string
    {
        // Add contextual links to tools within content
        $tools = self::getToolLinks();
        foreach ($tools as $tool) {
            $name = preg_quote($tool['name'], '/');
            $shortName = preg_quote(explode(' ', $tool['name'])[0], '/');

            // Replace first occurrence of tool name with linked version (if not already linked)
            $content = preg_replace(
                "/(?<!href=\")(?<!>)\b({$name})\b(?!<\/a>)/i",
                '<a href="' . $tool['url'] . '" class="internal-link">$1</a>',
                $content,
                1
            );
        }

        return $content;
    }

    public static function getPopularPosts(int $limit = 5): array
    {
        return Post::getRecent($limit);
    }

    public static function getRelatedPostsForTool(string $toolSlug, int $limit = 3): array
    {
        $appConfig = require __DIR__ . '/../../config/app.php';
        $tool = null;
        foreach ($appConfig['tools'] as $t) {
            if ($t['slug'] === $toolSlug) {
                $tool = $t;
                break;
            }
        }

        if (!$tool) {
            return [];
        }

        $searchTerms = explode(' ', $tool['name']);
        $likeClauses = [];
        $params = ['published'];

        foreach ($searchTerms as $term) {
            if (strlen($term) > 2) {
                $likeClauses[] = '(p.title LIKE ? OR p.content LIKE ?)';
                $params[] = "%{$term}%";
                $params[] = "%{$term}%";
            }
        }

        if (empty($likeClauses)) {
            return [];
        }

        $where = '(' . implode(' OR ', $likeClauses) . ')';
        $params[] = $limit;

        $db = \App\Core\Database::getInstance();
        return $db->fetchAll(
            "SELECT p.*, c.name as category_name, c.slug as category_slug
             FROM posts p
             LEFT JOIN categories c ON p.category_id = c.id
             WHERE p.status = ? AND {$where}
             ORDER BY p.published_at DESC
             LIMIT ?",
            $params
        );
    }
}
