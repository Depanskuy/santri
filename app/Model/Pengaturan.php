<?php

namespace App\Model;                                                                                                                       

use PDO;

class Pengaturan
{
    public static function all(): array
    {
        $rows = db()->query('SELECT `key`, `value` FROM settings')->fetchAll();
        $out = [];
        foreach ($rows as $r) {
            $out[$r['key']] = $r['value'];
        }
        return $out;
    }

    public static function get(string $key, ?string $default = null): ?string
    {
        $st = db()->prepare('SELECT `value` FROM settings WHERE `key` = ?');
        $st->execute([$key]);
        $val = $st->fetchColumn();
        return $val !== false ? $val : $default;
    }

    public static function set(string $key, string $value): bool
    {
        $sql = 'INSERT INTO settings (`key`, `value`) VALUES (?, ?)
                ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)';
        $st = db()->prepare($sql);
        return $st->execute([$key, $value]);
    }

    public static function exists(string $key): bool
    {
        $st = db()->prepare('SELECT COUNT(*) FROM settings WHERE `key` = ?');
        $st->execute([$key]);
        return (int)$st->fetchColumn() > 0;
    }

    public static function delete(string $key): bool
    {
        $st = db()->prepare('DELETE FROM settings WHERE `key` = ?');
        return $st->execute([$key]);
    }

    public static function count(): int
    {
        return (int)db()->query('SELECT COUNT(*) FROM settings')->fetchColumn();
    }
}
