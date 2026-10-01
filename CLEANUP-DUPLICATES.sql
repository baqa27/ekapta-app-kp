-- =====================================================================
-- PEMBERSIHAN DATA GANDA: pendaftaran_kps & bimbingan_kps
-- =====================================================================
-- PENTING: Jalankan bertahap. JANGAN langsung DELETE tanpa backup.
-- Skema: database/migrations/kp/2026_01_23_100004_create_pendaftaran_kps_table.php
--        database/migrations/kp/2026_01_23_100007_create_bimbingan_kps_table.php
-- =====================================================================

-- =====================================================================
-- BAGIAN 1: DETEKSI DUPLIKAT pendaftaran_kps
-- =====================================================================

-- 1.1: Cari semua pengajuan_id yang punya >1 baris pendaftaran aktif (review/diterima)
SELECT
    p.pengajuan_id,
    p.mahasiswa_id,
    m.nim,
    m.nama,
    COUNT(*) AS jumlah_baris,
    GROUP_CONCAT(p.id ORDER BY p.id) AS daftar_id,
    GROUP_CONCAT(p.status ORDER BY p.id) AS daftar_status,
    GROUP_CONCAT(p.created_at ORDER BY p.id) AS daftar_created_at
FROM pendaftaran_kps p
JOIN mahasiswas m ON m.id = p.mahasiswa_id
WHERE p.status IN ('review', 'diterima', 'revisi')
GROUP BY p.pengajuan_id, p.mahasiswa_id, m.nim, m.nama
HAVING COUNT(*) > 1
ORDER BY jumlah_baris DESC;

-- 1.2: Detail lengkap untuk NIM 2021157013 (dari screenshot)
SELECT
    p.id,
    p.mahasiswa_id,
    p.pengajuan_id,
    p.status,
    p.status_pembayaran,
    p.tanggal_acc,
    p.nomor_surat_tugas,
    p.file_surat_tugas,
    p.jumlah_perpanjangan,
    p.created_at,
    p.updated_at
FROM pendaftaran_kps p
JOIN mahasiswas m ON m.id = p.mahasiswa_id
WHERE m.nim = '2021157013'
ORDER BY p.id;

-- =====================================================================
-- BAGIAN 2: DETEKSI DUPLIKAT bimbingan_kps
-- =====================================================================

-- 2.1: Cari semua mahasiswa_id + bagian_id yang punya >1 baris bimbingan
SELECT
    b.mahasiswa_id,
    m.nim,
    m.nama,
    b.bagian_id,
    bg.nama AS bagian_nama,
    COUNT(*) AS jumlah_bimbingan,
    GROUP_CONCAT(b.id ORDER BY b.id) AS daftar_id_bimbingan,
    GROUP_CONCAT(b.status ORDER BY b.id) AS daftar_status,
    GROUP_CONCAT(b.tanggal_acc ORDER BY b.id) AS daftar_tanggal_acc
FROM bimbingan_kps b
JOIN mahasiswas m ON m.id = b.mahasiswa_id
JOIN bagian_kps bg ON bg.id = b.bagian_id
GROUP BY b.mahasiswa_id, m.nim, m.nama, b.bagian_id, bg.nama
HAVING COUNT(*) > 1
ORDER BY jumlah_bimbingan DESC;

-- 2.2: Detail bimbingan untuk NIM 2021157013 (jika ada duplikat)
SELECT
    b.id,
    b.mahasiswa_id,
    b.bagian_id,
    bg.nama AS bagian_nama,
    b.status,
    b.tanggal_bimbingan,
    b.tanggal_acc,
    b.lampiran,
    b.keterangan,
    b.created_at,
    b.updated_at
FROM bimbingan_kps b
JOIN mahasiswas m ON m.id = b.mahasiswa_id
JOIN bagian_kps bg ON bg.id = b.bagian_id
WHERE m.nim = '2021157013'
ORDER BY b.bagian_id, b.id;

-- =====================================================================
-- BAGIAN 3: BACKUP SEBELUM DELETE
-- =====================================================================

-- 3.1: Backup pendaftaran_kps yang duplikat
CREATE TABLE IF NOT EXISTS pendaftaran_kps_backup_20261001 AS
SELECT * FROM pendaftaran_kps WHERE 1=0;

INSERT INTO pendaftaran_kps_backup_20261001
SELECT p.* FROM pendaftaran_kps p
WHERE p.pengajuan_id IN (
    SELECT pengajuan_id
    FROM pendaftaran_kps
    WHERE status IN ('review', 'diterima', 'revisi')
    GROUP BY pengajuan_id
    HAVING COUNT(*) > 1
);

-- 3.2: Backup bimbingan_kps yang duplikat
CREATE TABLE IF NOT EXISTS bimbingan_kps_backup_20261001 AS
SELECT * FROM bimbingan_kps WHERE 1=0;

INSERT INTO bimbingan_kps_backup_20261001
SELECT b.* FROM bimbingan_kps b
WHERE (b.mahasiswa_id, b.bagian_id) IN (
    SELECT mahasiswa_id, bagian_id
    FROM bimbingan_kps
    GROUP BY mahasiswa_id, bagian_id
    HAVING COUNT(*) > 1
);

-- Verifikasi backup
SELECT 'pendaftaran_kps_backup' AS tabel, COUNT(*) AS jumlah_baris FROM pendaftaran_kps_backup_20261001
UNION ALL
SELECT 'bimbingan_kps_backup' AS tabel, COUNT(*) AS jumlah_baris FROM bimbingan_kps_backup_20261001;

-- =====================================================================
-- BAGIAN 4: HAPUS DUPLIKAT pendaftaran_kps
-- =====================================================================
-- STRATEGI: Pertahankan baris dengan id TERKECIL per pengajuan_id
-- (baris paling awal dibuat), KECUALI jika baris kedua punya progres
-- lebih lanjut (nomor_surat_tugas, tanggal_acc, dll). 
-- REVIEW MANUAL hasil BAGIAN 1.2 dulu sebelum menjalankan DELETE ini.
-- =====================================================================

START TRANSACTION;

DELETE p1 FROM pendaftaran_kps p1
INNER JOIN pendaftaran_kps p2
    ON p1.pengajuan_id = p2.pengajuan_id
    AND p1.id > p2.id
WHERE p1.status IN ('review', 'diterima', 'revisi')
  AND p2.status IN ('review', 'diterima', 'revisi');

-- PERIKSA jumlah baris terhapus sebelum COMMIT:
-- SELECT ROW_COUNT(); 
-- Jika sesuai ekspektasi dari BAGIAN 1.1, lanjutkan COMMIT.
-- Jika tidak, jalankan ROLLBACK.

-- COMMIT;
-- ROLLBACK;

-- =====================================================================
-- BAGIAN 5: HAPUS DUPLIKAT bimbingan_kps
-- =====================================================================
-- STRATEGI: Pertahankan baris dengan id TERKECIL per (mahasiswa_id, bagian_id)
-- KECUALI jika baris kedua punya tanggal_acc atau lampiran.
-- REVIEW MANUAL hasil BAGIAN 2.2 dulu.
-- =====================================================================

-- 5.1: Identifikasi baris yang aman dihapus (baris kedua tanpa progres)
SELECT b1.id AS id_dipertahankan, b2.id AS id_kandidat_hapus,
       b2.status, b2.tanggal_acc, b2.lampiran
FROM bimbingan_kps b1
INNER JOIN bimbingan_kps b2
    ON b1.mahasiswa_id = b2.mahasiswa_id
    AND b1.bagian_id = b2.bagian_id
    AND b1.id < b2.id
WHERE b2.tanggal_acc IS NULL
  AND (b2.lampiran IS NULL OR b2.lampiran = '');

-- 5.2: Hapus baris ganda yang tidak ada progres
START TRANSACTION;

DELETE b2 FROM bimbingan_kps b1
INNER JOIN bimbingan_kps b2
    ON b1.mahasiswa_id = b2.mahasiswa_id
    AND b1.bagian_id = b2.bagian_id
    AND b1.id < b2.id
WHERE b2.tanggal_acc IS NULL
  AND (b2.lampiran IS NULL OR b2.lampiran = '');

-- PERIKSA jumlah baris terhapus sebelum COMMIT:
-- SELECT ROW_COUNT();

-- COMMIT;
-- ROLLBACK;

-- =====================================================================
-- BAGIAN 6: VERIFIKASI AKHIR
-- =====================================================================

-- 6.1: Tidak boleh ada lagi duplikat pendaftaran_kps
SELECT pengajuan_id, COUNT(*) AS jumlah
FROM pendaftaran_kps
WHERE status IN ('review', 'diterima', 'revisi')
GROUP BY pengajuan_id
HAVING COUNT(*) > 1;

-- 6.2: Tidak boleh ada lagi duplikat bimbingan_kps
SELECT mahasiswa_id, bagian_id, COUNT(*) AS jumlah
FROM bimbingan_kps
GROUP BY mahasiswa_id, bagian_id
HAVING COUNT(*) > 1;

-- =====================================================================
-- BAGIAN 7: TERAPKAN UNIQUE INDEX (setelah duplikat bersih)
-- =====================================================================
-- Jalankan migration: 2026_01_29_100000_add_unique_index_to_bimbingan_kps_table.php
-- Di Laravel: php artisan migrate --path=database/migrations/kp

-- Untuk pendaftaran_kps, unique index lebih kompleks karena mahasiswa bisa
-- punya multiple pendaftaran untuk pengajuan berbeda, tapi tidak boleh
-- duplikat untuk pengajuan_id yang sama dengan status aktif.
-- Solusi: Enforce di level aplikasi (sudah fixed di PendaftaranController)
-- atau buat unique index manual:

-- ALTER TABLE pendaftaran_kps 
-- ADD CONSTRAINT uniq_pendaftaran_pengajuan_mahasiswa 
-- UNIQUE (pengajuan_id, mahasiswa_id);

-- =====================================================================
-- CATATAN PENTING
-- =====================================================================
-- 1. revisi_pendaftaran_kps akan ikut terhapus otomatis via CASCADE
-- 2. bimbingan_kps TIDAK cascade ke pendaftaran_kps (tidak ada FK)
-- 3. dosen_bimbingan_kps (pivot table) tidak otomatis terhapus,
--    tapi foreign key ON DELETE CASCADE di migration akan handle itu
-- 4. File orphan (lampiran_1..7, dokumen_pendukung) tetap ada di storage
--    tapi tidak corrupt data. Cleanup manual jika perlu hemat disk.
-- =====================================================================
