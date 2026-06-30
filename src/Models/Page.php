<?php
namespace App\Models;

use App\Core\Database;

class Page
{
    public static function findBySlug(string $slug): ?array
    {
        $db = Database::getInstance();
        return $db->fetch(
            "SELECT * FROM pages WHERE slug = ? AND status = 'published'",
            [$slug]
        ) ?: null;
    }

    public static function getAll(): array
    {
        $db = Database::getInstance();
        return $db->fetchAll(
            "SELECT * FROM pages WHERE status = 'published' ORDER BY title ASC"
        );
    }

    public static function create(array $data): int
    {
        $db = Database::getInstance();
        return $db->insert('pages', $data);
    }

    public static function update(int $id, array $data): int
    {
        $db = Database::getInstance();
        return $db->update('pages', $data, 'id = ?', [$id]);
    }
}
