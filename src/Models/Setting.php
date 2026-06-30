<?php
namespace App\Models;

use App\Core\Database;

class Setting
{
    private static ?array $cache = null;

    public static function get(string $key, $default = null): ?string
    {
        if (self::$cache === null) {
            self::loadAll();
        }

        return self::$cache[$key] ?? $default;
    }

    public static function set(string $key, string $value): void
    {
        $db = Database::getInstance();

        $existing = $db->fetch("SELECT * FROM settings WHERE key = ?", [$key]);
        if ($existing) {
            $db->update('settings', ['value' => $value, 'updated_at' => date('Y-m-d H:i:s')], 'key = ?', [$key]);
        } else {
            $db->insert('settings', ['key' => $key, 'value' => $value]);
        }

        if (self::$cache !== null) {
            self::$cache[$key] = $value;
        }
    }

    public static function getAll(): array
    {
        if (self::$cache === null) {
            self::loadAll();
        }
        return self::$cache;
    }

    private static function loadAll(): void
    {
        $db = Database::getInstance();
        $rows = $db->fetchAll("SELECT * FROM settings");
        self::$cache = [];
        foreach ($rows as $row) {
            self::$cache[$row['key']] = $row['value'];
        }
    }

    public static function clearCache(): void
    {
        self::$cache = null;
    }
}
