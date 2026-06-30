<?php
namespace App\Models;

use App\Core\Database;

class Subscriber
{
    public static function subscribe(string $email, string $name = ''): bool
    {
        $db = Database::getInstance();

        $existing = $db->fetch("SELECT * FROM subscribers WHERE email = ?", [$email]);
        if ($existing) {
            if (!$existing['active']) {
                $db->update('subscribers', ['active' => 1], 'email = ?', [$email]);
                return true;
            }
            return false;
        }

        $token = bin2hex(random_bytes(32));
        $db->insert('subscribers', [
            'email' => $email,
            'name' => $name,
            'token' => $token,
        ]);

        return true;
    }

    public static function unsubscribe(string $token): bool
    {
        $db = Database::getInstance();
        $sub = $db->fetch("SELECT * FROM subscribers WHERE token = ?", [$token]);

        if ($sub) {
            $db->update('subscribers', ['active' => 0], 'id = ?', [$sub['id']]);
            return true;
        }

        return false;
    }

    public static function getAll(): array
    {
        $db = Database::getInstance();
        return $db->fetchAll("SELECT * FROM subscribers WHERE active = 1 ORDER BY subscribed_at DESC");
    }

    public static function count(): int
    {
        $db = Database::getInstance();
        return $db->fetch("SELECT COUNT(*) as count FROM subscribers WHERE active = 1")['count'];
    }
}
