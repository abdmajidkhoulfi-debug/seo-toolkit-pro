<?php
namespace App\Models;

use App\Core\Database;

class Tag
{
    public static function getAll(): array
    {
        $db = Database::getInstance();
        return $db->fetchAll(
            "SELECT t.*, (SELECT COUNT(*) FROM post_tags WHERE tag_id = t.id) as post_count
             FROM tags t
             ORDER BY t.name ASC"
        );
    }

    public static function findBySlug(string $slug): ?array
    {
        $db = Database::getInstance();
        return $db->fetch("SELECT * FROM tags WHERE slug = ?", [$slug]) ?: null;
    }

    public static function findOrCreate(string $name): int
    {
        $db = Database::getInstance();
        $slug = \App\Helpers\SEO::slugify($name);

        $existing = $db->fetch("SELECT id FROM tags WHERE slug = ?", [$slug]);
        if ($existing) {
            return $existing['id'];
        }

        return $db->insert('tags', [
            'name' => $name,
            'slug' => $slug,
        ]);
    }

    public static function syncForPost(int $postId, array $tagIds): void
    {
        $db = Database::getInstance();
        $db->delete('post_tags', 'post_id = ?', [$postId]);

        $stmt = $db->getPdo()->prepare('INSERT INTO post_tags (post_id, tag_id) VALUES (?, ?)');
        foreach ($tagIds as $tagId) {
            $stmt->execute([$postId, $tagId]);
        }
    }
}
