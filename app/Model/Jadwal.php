<?php

class Jadwal {
    public static function byDokter(int $dokterId): array
    {
        $st = db()->prepare("
            SELECT * FROM schedules
            WHERE doctor_id = ?
            ORDER BY FIELD(day_of_week,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')
        ");
        $st->execute([$dokterId]);
        return $st->fetchAll();
    }

    public static function find(int $dokterId, string $hari): ?array
    {
        $st = db()->prepare('SELECT * FROM schedules WHERE doctor_id = ? AND day_of_week = ?');
        $st->execute([$dokterId, $hari]);
        return $st->fetch() ?: null;
    }

    public static function upsert(int $dokterId, string $hari, string $start, string $end, int $capacity = 30): bool
    {
        $sql = 'INSERT INTO schedules (doctor_id, day_of_week, time_start, time_end, capacity)
                VALUES (?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                    time_start = VALUES(time_start),
                    time_end   = VALUES(time_end),
                    capacity   = VALUES(capacity)';
        $st = db()->prepare($sql);
        return $st->execute([$dokterId, $hari, $start, $end, $capacity]);
    }

    //  Hapus jadwal di hari tertentu
    public static function delete(int $dokterId, string $hari): bool
    {
        $st = db()->prepare('DELETE FROM schedules WHERE doctor_id = ? AND day_of_week = ?');
        return $st->execute([$dokterId, $hari]);
    }

    // Grid mingguan: array per dokter dengan kolom hari

    public static function weeklyGrid(): array
    {
        $sql = "SELECT d.id AS doctor_id, d.name, d.specialization,
                       p.name AS poli_name,
                       s.day_of_week, s.time_start, s.time_end
                FROM doctors d
                JOIN poli p ON p.id = d.poli_id
                LEFT JOIN schedules s ON s.doctor_id = d.id
                WHERE d.is_active = 1
                ORDER BY p.id, d.name";
        $rows = db()->query($sql)->fetchAll();

        $grid = [];
        foreach ($rows as $r) {
            $key = $r['doctor_id'];
            if (!isset($grid[$key])) {
                $grid[$key] = [
                    'doctor_id' => $r['doctor_id'],
                    'name'      => $r['name'],
                    'spec'      => $r['specialization'],
                    'poli_name' => $r['poli_name'],
                    'blocks'    => array_fill_keys(
                        ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'],
                        null
                    ),
                ];
            }
            if ($r['day_of_week']) {
                $grid[$key]['blocks'][$r['day_of_week']] =
                    substr($r['time_start'], 0, 5) . '–' . substr($r['time_end'], 0, 5);
            }
        }
        return array_values($grid);
    }
  }
?>