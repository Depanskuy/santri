<?php
/**
 * Dummy data SANTRI - sementara pakai array PHP.
 * Nanti di Tahap 9 diganti query MySQL.
 *
 * Catatan password: untuk sekarang plain text supaya gampang debug.
 * Tahap 5 (Auth) baru kita hash pakai password_hash().
 */

return [

    'polis' => [
        ['id' => 1, 'code' => 'UMU', 'name' => 'Poli Umum',     'sub' => 'Pemeriksaan umum',  'icon' => 'Stethoscope', 'is_open' => true],
        ['id' => 2, 'code' => 'ANK', 'name' => 'Poli Anak',     'sub' => 'Kesehatan anak',    'icon' => 'Baby',        'is_open' => true],
        ['id' => 3, 'code' => 'GIG', 'name' => 'Poli Gigi',     'sub' => 'Gigi & mulut',      'icon' => 'Tooth',       'is_open' => true],
        ['id' => 4, 'code' => 'JTG', 'name' => 'Poli Jantung',  'sub' => 'Kardiologi',        'icon' => 'Heart',       'is_open' => true],
        ['id' => 5, 'code' => 'MAT', 'name' => 'Poli Mata',     'sub' => 'Oftalmologi',       'icon' => 'Eye',         'is_open' => true],
    ],

    'doctors' => [
        ['id' => 1, 'poli_id' => 1, 'name' => 'dr. Ayu Larasati',          'spec' => 'Dokter Umum',     'days' => 'Sen-Jum',     'time' => '08:00-14:00', 'queue_now' => 6],
        ['id' => 2, 'poli_id' => 1, 'name' => 'dr. Bagas Pratama',         'spec' => 'Dokter Umum',     'days' => 'Sen-Sab',     'time' => '14:00-20:00', 'queue_now' => 3],
        ['id' => 3, 'poli_id' => 2, 'name' => 'dr. Eka Wulandari, Sp.A',   'spec' => 'Spesialis Anak',  'days' => 'Sen-Jum',     'time' => '09:00-14:00', 'queue_now' => 8],
        ['id' => 4, 'poli_id' => 3, 'name' => 'drg. Citra Halim, Sp.KG',   'spec' => 'Konservasi Gigi', 'days' => 'Sen,Rab,Jum', 'time' => '09:00-13:00', 'queue_now' => 4],
        ['id' => 5, 'poli_id' => 4, 'name' => 'dr. Gilang Sutisna, Sp.JP', 'spec' => 'Kardiologi',      'days' => 'Sel,Kam',     'time' => '10:00-14:00', 'queue_now' => 3],
        ['id' => 6, 'poli_id' => 5, 'name' => 'dr. Hana Pertiwi, Sp.M',    'spec' => 'Oftalmologi',     'days' => 'Sen,Rab',     'time' => '09:00-13:00', 'queue_now' => 2],
    ],

    'users' => [
        [
            'id' => 1,
            'nik' => '3201234567890001',
            'name' => 'Raihan Putra Wijaya',
            'email' => 'raihan@mail.com',
            'password' => 'password123',
            'phone' => '+62 812 3456 7890',
            'birth' => '1994-08-14',
            'gender' => 'Laki-laki',
            'address' => 'Jl. Cendana No. 27, Jakarta Pusat',
            'blood_type' => 'O+',
            'insurance' => 'BPJS',
            'insurance_no' => '0001 2345 6789',
        ],
        [
            'id' => 2,
            'nik' => '3201234567890002',
            'name' => 'Sari Wulandari',
            'email' => 'sari@mail.com',
            'password' => 'password123',
            'phone' => '+62 813 1234 5678',
            'birth' => '1992-03-10',
            'gender' => 'Perempuan',
            'address' => 'Jl. Mawar No. 5, Jakarta Selatan',
            'blood_type' => 'A+',
            'insurance' => 'BPJS',
            'insurance_no' => '0001 2345 6712',
        ],
        [
            'id' => 3,
            'nik' => '3201234567890003',
            'name' => 'Maya Anggraini',
            'email' => 'maya@mail.com',
            'password' => 'password123',
            'phone' => '+62 821 4567 8901',
            'birth' => '1990-07-22',
            'gender' => 'Perempuan',
            'address' => 'Jl. Kenanga No. 12, Tangerang',
            'blood_type' => 'B+',
            'insurance' => 'Umum',
            'insurance_no' => null,
        ],
    ],

    'staff' => [
        [
            'id' => 1,
            'name' => 'Faisal Rahman',
            'email' => 'admin@medicaria.id',
            'password' => 'admin123',
            'role' => 'admin',
            'loket' => null,
            'shift' => 'Penuh 07:00-17:00',
            'status' => 'online',
        ],
        [
            'id' => 2,
            'name' => 'Bagas Hermawan',
            'email' => 'petugas@medicaria.id',
            'password' => 'admin123',
            'role' => 'petugas',
            'loket' => 'Loket 03',
            'shift' => 'Pagi 07:00-14:00',
            'status' => 'online',
        ],
        [
            'id' => 3,
            'name' => 'Diana Saputri',
            'email' => 'diana@medicaria.id',
            'password' => 'admin123',
            'role' => 'petugas',
            'loket' => 'Loket 02',
            'shift' => 'Pagi 07:00-14:00',
            'status' => 'online',
        ],
    ],

    /**
     * Status valid: wait, call, progress, done, skip, cancel
     */
    'queues' => [
        ['id' => 1, 'ticket' => 'UMU-025', 'prefix' => 'UMU', 'number' => 25, 'user_id' => 3,    'doctor_id' => 1, 'poli_id' => 1, 'status' => 'done',     'date' => '2026-04-29', 'time' => '08:00', 'complaint' => 'Kontrol rutin'],
        ['id' => 2, 'ticket' => 'UMU-026', 'prefix' => 'UMU', 'number' => 26, 'user_id' => 2,    'doctor_id' => 1, 'poli_id' => 1, 'status' => 'progress', 'date' => '2026-04-29', 'time' => '08:15', 'complaint' => 'Demam ringan'],
        ['id' => 3, 'ticket' => 'UMU-027', 'prefix' => 'UMU', 'number' => 27, 'user_id' => 1,    'doctor_id' => 1, 'poli_id' => 1, 'status' => 'call',     'date' => '2026-04-29', 'time' => '08:30', 'complaint' => 'Sakit kepala'],
        ['id' => 4, 'ticket' => 'UMU-028', 'prefix' => 'UMU', 'number' => 28, 'user_id' => null, 'doctor_id' => 1, 'poli_id' => 1, 'status' => 'wait',     'date' => '2026-04-29', 'time' => '08:45', 'complaint' => 'Walk-in'],
        ['id' => 5, 'ticket' => 'GIG-014', 'prefix' => 'GIG', 'number' => 14, 'user_id' => 3,    'doctor_id' => 4, 'poli_id' => 3, 'status' => 'wait',     'date' => '2026-04-29', 'time' => '09:00', 'complaint' => 'Sakit gigi'],
        ['id' => 6, 'ticket' => 'ANK-021', 'prefix' => 'ANK', 'number' => 21, 'user_id' => null, 'doctor_id' => 3, 'poli_id' => 2, 'status' => 'wait',     'date' => '2026-04-29', 'time' => '09:30', 'complaint' => 'Imunisasi'],
    ],

    /**
     * Counter untuk generate nomor antrean berikutnya per poli
     * Format key: "<poli_id>_<tanggal>"
     */
    'queue_counters' => [
        '1_2026-04-29' => 28,
        '2_2026-04-29' => 21,
        '3_2026-04-29' => 14,
    ],

];
