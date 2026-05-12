<?php
namespace App\Model;

use PDO;

class Notifikasi
{
      public static function pushNotifUser(int $userId, string $title, string $body = '', string $kind = 'info'): int
      {
          $sql = 'INSERT INTO notifications (user_id, title, body, kind) VALUES (?, ?, ?, ?)';
          $st = db()->prepare($sql);
          $st->execute([$userId, $title, $body, $kind]);
          return (int)db()->lastInsertId();
      }
  
      public static function pushNotifStaff(int $staffId, string $title, string $body = '', string $kind = 'info'): int
      {
          $sql = 'INSERT INTO notifications (staff_id, title, body, kind) VALUES (?, ?, ?, ?)';
          $st = db()->prepare($sql);
          $st->execute([$staffId, $title, $body, $kind]);
          return (int)db()->lastInsertId();
      }
  
      public static function listForUser(int $userId, int $limit = 30): array
      {
          $st = db()->prepare('
              SELECT * FROM notifications
              WHERE user_id = ?
              ORDER BY created_at DESC
              LIMIT ?
          ');
          $st->bindValue(1, $userId, PDO::PARAM_INT);
          $st->bindValue(2, $limit, PDO::PARAM_INT);
          $st->execute();
          return $st->fetchAll();
      }
  
      public static function listForStaff(int $staffId, int $limit = 30): array
      {
          $st = db()->prepare('
              SELECT * FROM notifications
              WHERE staff_id = ?
              ORDER BY created_at DESC
              LIMIT ?
          ');
          $st->bindValue(1, $staffId, PDO::PARAM_INT);
          $st->bindValue(2, $limit, PDO::PARAM_INT);
          $st->execute();
          return $st->fetchAll();
      }
  
      public static function unreadCountUser(int $userId): int
      {
          $st = db()->prepare('
              SELECT COUNT(*) FROM notifications
              WHERE user_id = ? AND read_at IS NULL
          ');
          $st->execute([$userId]);
          return (int)$st->fetchColumn();
      }
  
      public static function unreadCountStaff(int $staffId): int
      {
          $st = db()->prepare('
              SELECT COUNT(*) FROM notifications
              WHERE staff_id = ? AND read_at IS NULL
          ');
          $st->execute([$staffId]);
          return (int)$st->fetchColumn();
      }
  
      public static function markRead(int $id, int $userId): bool
      {
          $st = db()->prepare('
              UPDATE notifications SET read_at = NOW()
              WHERE id = ? AND user_id = ? AND read_at IS NULL
          ');
          $st->execute([$id, $userId]);
          return $st->rowCount() > 0;
      }
  
      public static function markAllRead(int $userId): int
      {
          $st = db()->prepare('
              UPDATE notifications SET read_at = NOW()
              WHERE user_id = ? AND read_at IS NULL
          ');
          $st->execute([$userId]);
          return $st->rowCount();
      }
  
      public static function delete(int $id): bool
      {
          $st = db()->prepare('DELETE FROM notifications WHERE id = ?');
          return $st->execute([$id]);
      }
}
