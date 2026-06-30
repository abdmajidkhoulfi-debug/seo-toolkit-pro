<?php
namespace App\Models;

use App\Core\Database;

class Category
{
    public static function getAll(): array
    {
        $db = Database::getInstance();
        return $db->fetchAll(
            "SELECT c.*, (SELECT COUNT(*) FROM posts WHERE category_id = c.id AND status = 'published') as post_count
             FROM categories c
             ORDER BY c.name ASC"
        );
    }

    public static function findBySlug(string $slug): ?array
    {
        $db = Database::getInstance();
        return $db->fetch("SELECT * FROM categories WHERE slug = ?", [$slug]) ?: null;
    }

    public static function findById(int $id): ?array
    {
        $db = Database::getInstance();
        return $db->fetch("SELECT * FROM categories WHERE id = ?", [$id]) ?: null;
    }

    public static function create(array $data): int
    {
        $db = Database::getInstance();
        return $db->insert('categories', $data);
    }

    public static function update(int $id, array $data): int
    {
        $db = Database::getInstance();
        return $db->update('categories', $data, 'id = ?', [$id]);
    }

    public static function delete(int $id): int
    {
        $db = Database::getInstance();
        return $db->delete('categories', 'id = ?', [$id]);
    }
}
