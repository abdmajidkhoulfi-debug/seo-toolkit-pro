<?php
namespace App\Models;

use App\Core\Database;

class Post
{
    public static function getPublished(int $page = 1, int $perPage = 12, ?int $categoryId = null, ?string $search = null): array
    {
        $db = Database::getInstance();
        $conditions = ['p.status = ?'];
        $params = ['published'];

        if ($categoryId) {
            $conditions[] = 'p.category_id = ?';
            $params[] = $categoryId;
        }

        if ($search) {
            $conditions[] = '(p.title LIKE ? OR p.content LIKE ? OR p.excerpt LIKE ?)';
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        $where = implode(' AND ', $conditions);
        $offset = ($page - 1) * $perPage;

        $total = $db->fetch(
            "SELECT COUNT(*) as count FROM posts p WHERE {$where}",
            $params
        )['count'];

        $posts = $db->fetchAll(
            "SELECT p.*, c.name as category_name, c.slug as category_slug
             FROM posts p
             LEFT JOIN categories c ON p.category_id = c.id
             WHERE {$where}
             ORDER BY p.published_at DESC
             LIMIT ? OFFSET ?",
            array_merge($params, [$perPage, $offset])
        );

        return [
            'posts' => $posts,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'lastPage' => max(1, (int) ceil($total / $perPage)),
        ];
    }

    public static function findBySlug(string $slug): ?array
    {
        $db = Database::getInstance();
        $post = $db->fetch(
            "SELECT p.*, c.name as category_name, c.slug as category_slug
             FROM posts p
             LEFT JOIN categories c ON p.category_id = c.id
             WHERE p.slug = ? AND p.status = 'published'",
            [$slug]
        );

        if ($post) {
            // Get tags
            $post['tags'] = $db->fetchAll(
                "SELECT t.* FROM tags t
                 JOIN post_tags pt ON t.id = pt.tag_id
                 WHERE pt.post_id = ?",
                [$post['id']]
            );
        }

        return $post ?: null;
    }

    public static function getRelated(int $postId, ?int $categoryId, int $limit = 3): array
    {
        $db = Database::getInstance();

        if ($categoryId) {
            return $db->fetchAll(
                "SELECT p.*, c.name as category_name, c.slug as category_slug
                 FROM posts p
                 LEFT JOIN categories c ON p.category_id = c.id
                 WHERE p.id != ? AND p.category_id = ? AND p.status = 'published'
                 ORDER BY p.published_at DESC
                 LIMIT ?",
                [$postId, $categoryId, $limit]
            );
        }

        return $db->fetchAll(
            "SELECT p.*, c.name as category_name, c.slug as category_slug
             FROM posts p
             LEFT JOIN categories c ON p.category_id = c.id
             WHERE p.id != ? AND p.status = 'published'
             ORDER BY p.published_at DESC
             LIMIT ?",
            [$postId, $limit]
        );
    }

    public static function getFeatured(int $limit = 5): array
    {
        $db = Database::getInstance();
        return $db->fetchAll(
            "SELECT p.*, c.name as category_name, c.slug as category_slug
             FROM posts p
             LEFT JOIN categories c ON p.category_id = c.id
             WHERE p.featured = 1 AND p.status = 'published'
             ORDER BY p.published_at DESC
             LIMIT ?",
            [$limit]
        );
    }

    public static function getRecent(int $limit = 5, ?int $excludeId = null): array
    {
        $db = Database::getInstance();
        $conditions = ["status = 'published'"];
        $params = [];

        if ($excludeId) {
            $conditions[] = 'id != ?';
            $params[] = $excludeId;
        }

        $where = implode(' AND ', $conditions);
        $params[] = $limit;

        return $db->fetchAll(
            "SELECT p.*, c.name as category_name, c.slug as category_slug
             FROM posts p
             LEFT JOIN categories c ON p.category_id = c.id
             WHERE {$where}
             ORDER BY p.published_at DESC
             LIMIT ?",
            $params
        );
    }

    public static function incrementViews(int $id): void
    {
        $db = Database::getInstance();
        $db->query("UPDATE posts SET view_count = view_count + 1 WHERE id = ?", [$id]);
    }

    public static function getByCategory(string $slug, int $page = 1, int $perPage = 12): array
    {
        $db = Database::getInstance();
        $category = $db->fetch("SELECT * FROM categories WHERE slug = ?", [$slug]);

        if (!$category) {
            return ['posts' => [], 'total' => 0, 'page' => 1, 'perPage' => $perPage, 'lastPage' => 1, 'category' => null];
        }

        $result = self::getPublished($page, $perPage, $category['id']);
        $result['category'] = $category;
        return $result;
    }

    public static function getByTag(string $slug, int $page = 1, int $perPage = 12): array
    {
        $db = Database::getInstance();
        $tag = $db->fetch("SELECT * FROM tags WHERE slug = ?", [$slug]);

        if (!$tag) {
            return ['posts' => [], 'total' => 0, 'page' => 1, 'perPage' => $perPage, 'lastPage' => 1, 'tag' => null];
        }

        $offset = ($page - 1) * $perPage;

        $total = $db->fetch(
            "SELECT COUNT(*) as count FROM post_tags pt
             JOIN posts p ON pt.post_id = p.id
             WHERE pt.tag_id = ? AND p.status = 'published'",
            [$tag['id']]
        )['count'];

        $posts = $db->fetchAll(
            "SELECT p.*, c.name as category_name, c.slug as category_slug
             FROM post_tags pt
             JOIN posts p ON pt.post_id = p.id
             LEFT JOIN categories c ON p.category_id = c.id
             WHERE pt.tag_id = ? AND p.status = 'published'
             ORDER BY p.published_at DESC
             LIMIT ? OFFSET ?",
            [$tag['id'], $perPage, $offset]
        );

        return [
            'posts' => $posts,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'lastPage' => max(1, (int) ceil($total / $perPage)),
            'tag' => $tag,
        ];
    }

    public static function search(string $query, int $page = 1, int $perPage = 12): array
    {
        return self::getPublished($page, $perPage, null, $query);
    }

    public static function getAllForSitemap(): array
    {
        $db = Database::getInstance();
        return $db->fetchAll(
            "SELECT slug, updated_at FROM posts WHERE status = 'published' ORDER BY updated_at DESC"
        );
    }
}
