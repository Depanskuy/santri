-- ============================================================================
-- SANTRI Belajar - Database Schema
-- Untuk MySQL 8 / MariaDB 10.4+
-- Charset: utf8mb4
-- ============================================================================

CREATE DATABASE IF NOT EXISTS santri_belajar
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;

USE santri_belajar;

-- Hapus tabel lama (urutan terbalik karena foreign key)
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS notifications;
DROP TABLE IF EXISTS queue_counters;
DROP TABLE IF EXISTS queues;
DROP TABLE IF EXISTS schedules;
DROP TABLE IF EXISTS doctors;
DROP TABLE IF EXISTS poli;
DROP TABLE IF EXISTS staff;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS settings;
SET FOREIGN_KEY_CHECKS = 1;


-- ============================================================================
-- 1. users - data pasien
-- ============================================================================
CREATE TABLE users (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nik             VARCHAR(16) NOT NULL UNIQUE,
    name            VARCHAR(120) NOT NULL,
    email           VARCHAR(120) NOT NULL UNIQUE,
    password_hash   VARCHAR(255) NOT NULL,
    phone           VARCHAR(20),
    birth           DATE,
    gender          ENUM('Laki-laki','Perempuan'),
    address         TEXT,
    blood_type      VARCHAR(5),
    insurance_type  ENUM('BPJS','Asuransi','Umum') DEFAULT 'Umum',
    insurance_no    VARCHAR(40),
    insurance_class VARCHAR(10),
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_users_email (email)
) ENGINE=InnoDB;


-- ============================================================================
-- 2. staff - admin / petugas / manajer
-- ============================================================================
CREATE TABLE staff (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(120) NOT NULL,
    email         VARCHAR(120) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role          ENUM('admin','petugas','manajer') NOT NULL DEFAULT 'petugas',
    loket         VARCHAR(40),
    shift         VARCHAR(40),
    status        ENUM('online','break','offline') DEFAULT 'offline',
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login_at TIMESTAMP NULL,
    INDEX idx_staff_email (email)
) ENGINE=InnoDB;


-- ============================================================================
-- 3. poli - poli rumah sakit
-- ============================================================================
CREATE TABLE poli (
    id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code           VARCHAR(3) NOT NULL UNIQUE,
    name           VARCHAR(60) NOT NULL,
    sub            VARCHAR(120),
    icon           VARCHAR(40) DEFAULT 'Stethoscope',
    capacity_daily INT UNSIGNED DEFAULT 50,
    is_open        TINYINT(1) DEFAULT 1,
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;


-- ============================================================================
-- 4. doctors - dokter
-- ============================================================================
CREATE TABLE doctors (
    id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    poli_id        INT UNSIGNED NOT NULL,
    name           VARCHAR(120) NOT NULL,
    specialization VARCHAR(80),
    photo          VARCHAR(255),
    is_active      TINYINT(1) DEFAULT 1,
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_doctor_poli
        FOREIGN KEY (poli_id) REFERENCES poli(id)
        ON DELETE RESTRICT,
    INDEX idx_doctors_poli (poli_id)
) ENGINE=InnoDB;


-- ============================================================================
-- 5. schedules - jadwal mingguan dokter
-- ============================================================================
CREATE TABLE schedules (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    doctor_id    INT UNSIGNED NOT NULL,
    day_of_week  ENUM('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') NOT NULL,
    time_start   TIME NOT NULL,
    time_end     TIME NOT NULL,
    capacity     INT UNSIGNED DEFAULT 30,
    CONSTRAINT fk_schedule_doctor
        FOREIGN KEY (doctor_id) REFERENCES doctors(id)
        ON DELETE CASCADE,
    UNIQUE KEY uniq_doctor_day (doctor_id, day_of_week)
) ENGINE=InnoDB;


-- ============================================================================
-- 6. queues - tiket antrean
-- ============================================================================
CREATE TABLE queues (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ticket_code     VARCHAR(16) NOT NULL UNIQUE,
    prefix          VARCHAR(3) NOT NULL,
    number          INT UNSIGNED NOT NULL,
    user_id         INT UNSIGNED NULL,
    walkin_name     VARCHAR(120),
    walkin_nik      VARCHAR(16),
    walkin_phone    VARCHAR(20),
    doctor_id       INT UNSIGNED NOT NULL,
    poli_id         INT UNSIGNED NOT NULL,
    schedule_date   DATE NOT NULL,
    schedule_time   TIME,
    complaint       TEXT,
    status          ENUM('wait','call','progress','done','skip','cancel') NOT NULL DEFAULT 'wait',
    insurance_type  ENUM('BPJS','Asuransi','Umum') DEFAULT 'Umum',
    registered_via  ENUM('online','walkin') DEFAULT 'online',
    handled_by      INT UNSIGNED NULL,
    called_at       TIMESTAMP NULL,
    started_at      TIMESTAMP NULL,
    completed_at    TIMESTAMP NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_queue_user   FOREIGN KEY (user_id)    REFERENCES users(id)   ON DELETE SET NULL,
    CONSTRAINT fk_queue_doctor FOREIGN KEY (doctor_id)  REFERENCES doctors(id) ON DELETE RESTRICT,
    CONSTRAINT fk_queue_poli   FOREIGN KEY (poli_id)    REFERENCES poli(id)    ON DELETE RESTRICT,
    CONSTRAINT fk_queue_staff  FOREIGN KEY (handled_by) REFERENCES staff(id)   ON DELETE SET NULL,
    INDEX idx_queue_poli_date_status (poli_id, schedule_date, status),
    INDEX idx_queue_user_status (user_id, status),
    INDEX idx_queue_date (schedule_date)
) ENGINE=InnoDB;


-- ============================================================================
-- 7. queue_counters - generate nomor antrean per poli per tanggal (atomic)
-- ============================================================================
CREATE TABLE queue_counters (
    poli_id      INT UNSIGNED NOT NULL,
    counter_date DATE NOT NULL,
    last_number  INT UNSIGNED DEFAULT 0,
    PRIMARY KEY (poli_id, counter_date),
    CONSTRAINT fk_counter_poli
        FOREIGN KEY (poli_id) REFERENCES poli(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;


-- ============================================================================
-- 8. notifications - pesan in-app
-- ============================================================================
CREATE TABLE notifications (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id    INT UNSIGNED NULL,
    staff_id   INT UNSIGNED NULL,
    title      VARCHAR(160) NOT NULL,
    body       VARCHAR(255),
    kind       ENUM('ok','warn','alert','info') DEFAULT 'info',
    read_at    TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_notif_user  FOREIGN KEY (user_id)  REFERENCES users(id)  ON DELETE CASCADE,
    CONSTRAINT fk_notif_staff FOREIGN KEY (staff_id) REFERENCES staff(id) ON DELETE CASCADE,
    INDEX idx_notif_user (user_id, read_at),
    INDEX idx_notif_staff (staff_id, read_at)
) ENGINE=InnoDB;


-- ============================================================================
-- 9. settings - konfigurasi sistem (key-value)
-- ============================================================================
CREATE TABLE settings (
    `key`      VARCHAR(60) PRIMARY KEY,
    `value`    TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;
