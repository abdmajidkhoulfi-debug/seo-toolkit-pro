<?php
namespace App\Models;

use App\Core\Database;

class Comment
{
    public static function getForPost(int $postId, string $status = 'approved'): array
    {
        $db = Database::getInstance();
        return $db->fetchAll(
            "SELECT * FROM comments
             WHERE post_id = ? AND status = ? AND parent_id IS NULL
             ORDER BY created_at DESC",
            [$postId, $status]
        );
    }

    public static function getReplies(int $commentId, string $status = 'approved'): array
    {
        $db = Database::getInstance();
        return $db->fetchAll(
            "SELECT * FROM comments WHERE parent_id = ? AND status = ? ORDER BY created_at ASC",
            [$commentId, $status]
        );
    }

    public static function create(array $data): int
    {
        $db = Database::getInstance();
        return $db->insert('comments', $data);
    }

    public static function countPending(): int
    {
        $db = Database::getInstance();
        return $db->fetch("SELECT COUNT(*) as count FROM comments WHERE status = 'pending'")['count'];
    }

    public static function getPending(int $page = 1, int $perPage = 20): array
    {
        $db = Database::getInstance();
        $offset = ($page - 1) * $perPage;

        $total = $db->fetch("SELECT COUNT(*) as count FROM comments WHERE status = 'pending'")['count'];

        $comments = $db->fetchAll(
            "SELECT c.*, p.title as post_title
             FROM comments c
             LEFT JOIN posts p ON c.post_id = p.id
             WHERE c.status = 'pending'
             ORDER BY c.created_at DESC
             LIMIT ? OFFSET ?",
            [$perPage, $offset]
        );

        return [
            'comments' => $comments,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'lastPage' => max(1, (int) ceil($total / $perPage)),
        ];
    }

    public static function approve(int $id): void
    {
        $db = Database::getInstance();
        $db->update('comments', ['status' => 'approved'], 'id = ?', [$id]);
    }

    public static function reject(int $id): void
    {
        $db = Database::getInstance();
        $db->update('comments', ['status' => 'spam'], 'id = ?', [$id]);
    }

    public static function delete(int $id): void
    {
        $db = Database::getInstance();
        $db->delete('comments', 'id = ?', [$id]);
    }
}
