-- ============================================================
-- SCRIPT DEPLOY DATABASE: PENJILIDAN KP REVIEW COLUMNS
-- ============================================================
-- Tanggal: 17 Oktober 2026
-- Target: Database shared hosting ekapta_unsiq (tanpa terminal)
--
-- CARA PAKAI DI phpMyAdmin:
-- 1. Buka phpMyAdmin, pilih database ekapta Anda di panel kiri.
-- 2. Klik tab "SQL" di menu atas.
-- 3. Copy-paste seluruh isi query di bawah ini.
-- 4. Klik tombol "Go" / "Kirim" di kanan bawah.
-- ============================================================

-- Tambah kolom review ke tabel jilid_kps
-- Aman dijalankan berulang (IF NOT EXISTS didukung MySQL 8.0.19+)
-- Jika muncul "Note: Duplicate column name", abaikan saja - berarti kolom sudah ada.

ALTER TABLE jilid_kps 
ADD COLUMN IF NOT EXISTS reviewed_by BIGINT UNSIGNED NULL AFTER status,
ADD COLUMN IF NOT EXISTS reviewer_type VARCHAR(20) NULL AFTER reviewed_by,
ADD COLUMN IF NOT EXISTS tanggal_review TIMESTAMP NULL AFTER reviewer_type;
