<?php

namespace App\Model;                                                                                                                       

use PDO;

class Poli                                                                                                                                   {                                                                                                                                          
      public static function all(bool $onlyOpen = false): array
      {
          $sql = 'SELECT * FROM poli';
          if ($onlyOpen) $sql .= ' WHERE is_open = 1';
          $sql .= ' ORDER BY id';
          return db()->query($sql)->fetchAll();
      }

      public static function findById(int $id): ?array
      {
          $st = db()->prepare('SELECT * FROM poli WHERE id = ?');
          $st->execute([$id]);
          $r = $st->fetch();
          return $r ?: null;
      }

      // code harus kapital
      public static function findByCode(string $code): ?array
      {
          $st = db()->prepare('SELECT * FROM poli WHERE code = ?');
          $st->execute([strtoupper($code)]);
          $r = $st->fetch();
          return $r ?: null;
      }
  
      //   cth:
      //   Poli::create([
      //        'code' => 'ASD',
      //        'name' => 'Poli Baru',
      //        'sub'  => 'Layanan baru',
      //        'icon' => 'Stethoscope',
      //        'capacity_daily' => 30,
      //        'is_open' => true,
      //    ]);

      public static function create(array $data): int
      {
          $sql = 'INSERT INTO poli (code, name, sub, icon, capacity_daily, is_open)
                  VALUES (?, ?, ?, ?, ?, ?)';
          $st = db()->prepare($sql);
          $st->execute([
              strtoupper(substr($data['code'], 0, 3)),  
              $data['name'],
              $data['sub'] ?? null,
              $data['icon'] ?? 'Stethoscope',
              (int)($data['capacity_daily'] ?? 50),
              !empty($data['is_open']) ? 1 : 0,
          ]);
          return (int)db()->lastInsertId();
      }
  
      public static function update(int $id, array $data): bool
      {
          $sql = 'UPDATE poli
                  SET code = ?, name = ?, sub = ?, icon = ?, capacity_daily = ?, is_open = ?
                  WHERE id = ?';
          $st = db()->prepare($sql);
          return $st->execute([
              strtoupper(substr($data['code'], 0, 3)),
              $data['name'],
              $data['sub'] ?? null,
              $data['icon'] ?? 'Stethoscope',
              (int)($data['capacity_daily'] ?? 50),
              !empty($data['is_open']) ? 1 : 0,
              $id,
          ]);
      }
  
      public static function delete(int $id): bool
      {
          $st = db()->prepare('DELETE FROM poli WHERE id = ?');
          return $st->execute([$id]);
      }
  
      public static function count(bool $onlyOpen = false): int
      {
          $sql = 'SELECT COUNT(*) FROM poli';
          if ($onlyOpen) $sql .= ' WHERE is_open = 1';
          return (int)db()->query($sql)->fetchColumn();
      }
}

?>