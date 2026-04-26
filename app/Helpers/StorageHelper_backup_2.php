<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StorageHelper
{
    /**
     * Base path untuk lampiran TA (langsung di folder lampirans)
     */
    const TA_BASE_PATH = 'lampirans';

    /**
     * Base path untuk lampiran KP (di folder lampirans/kp)
     */
    const KP_BASE_PATH = 'lampirans/kp';

    /**
     * Get path untuk lampiran pengajuan KP
     * Format: lampirans/kp/pengajuan/
     *
     * @param string|null $filename
     * @return string
     */
    public static function kpPengajuanPath($filename = null)
    {
        $path = self::KP_BASE_PATH . '/pengajuan';
        return $filename ? $path . '/' . $filename : $path;
    }

    /**
     * Get path untuk lampiran pendaftaran KP
     * Format: lampirans/kp/pendaftaran/
     *
     * @param string|null $filename
     * @return string
     */
    public static function kpPendaftaranPath($filename = null)
    {
        $path = self::KP_BASE_PATH . '/pendaftaran';
        return $filename ? $path . '/' . $filename : $path;
    }

    /**
     * Get path untuk lampiran bimbingan KP
     * Format: lampirans/kp/bimbingan/
     *
     * @param string|null $filename
     * @return string
     */
    public static function kpBimbinganPath($filename = null)
    {
        $path = self::KP_BASE_PATH . '/bimbingan';
        return $filename ? $path . '/' . $filename : $path;
    }

    /**
     * Get path untuk lampiran seminar KP
     * Format: lampirans/kp/seminar/
     *
     * @param string|null $filename
     * @return string
     */
    public static function kpSeminarPath($filename = null)
    {
        $path = self::KP_BASE_PATH . '/seminar';
        return $filename ? $path . '/' . $filename : $path;
    }

    /**
     * Get path untuk lampiran pengumpulan akhir KP
     * Format: lampirans/kp/pengumpulan_akhir/
     *
     * @param string|null $filename
     * @return string
     */
    public static function kpPengumpulanAkhirPath($filename = null)
    {
        $path = self::KP_BASE_PATH . '/pengumpulan_akhir';
        return $filename ? $path . '/' . $filename : $path;
    }

    /**
     * Get path untuk lampiran TA (langsung di folder lampirans/ta)
     * Format: lampirans/ta/
     *
     * @param string|null $filename
     * @return string
     */
    public static function taPath($filename = null)
    {
        $path = self::TA_BASE_PATH . '/ta';
        return $filename ? $path . '/' . $filename : $path;
    }

    /**
     * Store file untuk KP dengan path yang benar (tanpa folder per mahasiswa)
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $nim (tidak digunakan lagi, untuk backward compatibility)
     * @param string $type (pengajuan|pendaftaran|bimbingan|seminar|pengumpulan_akhir)
     * @return string|false Path file yang disimpan
     */
    public static function storeKpFile($file, $nim, $type)
    {
        // Convert snake_case to camelCase
        $typeFormatted = str_replace('_', '', ucwords($type, '_'));
        $method = 'kp' . $typeFormatted . 'Path';

        if (!method_exists(self::class, $method)) {
            Log::error('StorageHelper method not found', [
                'method' => $method,
                'type' => $type,
                'typeFormatted' => $typeFormatted
            ]);
            return false;
        }

        $path = self::$method();
        $stored = $file->store($path, 'public');

        Log::info('File stored', [
            'path' => $path,
            'stored' => $stored,
            'original_name' => $file->getClientOriginalName()
        ]);

        return $stored;
    }

    /**
     * Delete file KP
     *
     * @param string $filepath
     * @return bool
     */
    public static function deleteKpFile($filepath)
    {
        if (!$filepath) {
            return false;
        }

        return Storage::disk('public')->delete($filepath);
    }

    /**
     * Get URL untuk file KP
     *
     * @param string $filepath
     * @return string
     */
    public static function kpFileUrl($filepath)
    {
        if (!$filepath) {
            return null;
        }

        // Karena symlink adalah /lampirans bukan /storage
        // URL format: /lampirans/kp/{nim}/{type}/{filename}
        return asset($filepath);
    }

    /**
     * Check if file exists
     *
     * @param string $filepath
     * @return bool
     */
    public static function fileExists($filepath)
    {
        if (!$filepath) {
            return false;
        }

        return Storage::disk('public')->exists($filepath);
    }
}
