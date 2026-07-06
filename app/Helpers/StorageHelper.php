<?php

namespace App\Helpers;

use App\Services\GoogleDriveLampiranService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class StorageHelper
{
    /**
     * Base path untuk lampiran TA (langsung di folder lampirans)
     */
    const TA_BASE_PATH = 'lampirans/ta';

    /**
     * Base path untuk lampiran KP (di folder lampirans/kp)
     */
    const KP_BASE_PATH = 'lampirans/kp';

    /**
     * Get path untuk lampiran pengajuan KP
     * Format: lampirans/kp/
     *
     * @param string|null $filename
     * @return string
     */
    public static function kpPengajuanPath($filename = null)
    {
        $path = self::KP_BASE_PATH;
        return $filename ? $path . '/' . $filename : $path;
    }

    /**
     * Get path untuk lampiran pendaftaran KP
     * Format: lampirans/kp/
     *
     * @param string|null $filename
     * @return string
     */
    public static function kpPendaftaranPath($filename = null)
    {
        $path = self::KP_BASE_PATH;
        return $filename ? $path . '/' . $filename : $path;
    }

    /**
     * Get path untuk lampiran bimbingan KP
     * Format: lampirans/kp/
     *
     * @param string|null $filename
     * @return string
     */
    public static function kpBimbinganPath($filename = null)
    {
        $path = self::KP_BASE_PATH;
        return $filename ? $path . '/' . $filename : $path;
    }

    /**
     * Get path untuk lampiran seminar KP
     * Format: lampirans/kp/
     *
     * @param string|null $filename
     * @return string
     */
    public static function kpSeminarPath($filename = null)
    {
        $path = self::KP_BASE_PATH;
        return $filename ? $path . '/' . $filename : $path;
    }

    /**
     * Get path untuk lampiran pengumpulan akhir KP
     * Format: lampirans/kp/
     *
     * @param string|null $filename
     * @return string
     */
    public static function kpPengumpulanAkhirPath($filename = null)
    {
        $path = self::KP_BASE_PATH;
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
        $path = self::TA_BASE_PATH;
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
        $type = str_replace('-', '_', $type);
        $type = [
            'bimbingan_offline' => 'bimbingan',
        ][$type] ?? $type;

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

        $uploadedToGoogleDrive = self::syncToGoogleDrive($stored);

        if (
            $uploadedToGoogleDrive &&
            !(bool) config('services.google_drive_lampiran.keep_local_copy', true)
        ) {
            Storage::disk('public')->delete($stored);
        }

        Log::info('File stored', [
            'path' => $path,
            'stored' => $stored,
            'original_name' => $file->getClientOriginalName(),
            'google_drive_synced' => $uploadedToGoogleDrive,
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

        $filepath = self::normalizePath($filepath);

        if (file_exists(public_path($filepath))) {
            $deleted = unlink(public_path($filepath));
            self::deleteFromGoogleDrive($filepath);

            return $deleted;
        }

        $deleted = Storage::disk('public')->delete($filepath);
        self::deleteFromGoogleDrive($filepath);

        return $deleted;
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

        return \storage_url(self::normalizePath($filepath));
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

        $normalizedPath = self::normalizePath($filepath);

        if (Storage::disk('public')->exists($normalizedPath)) {
            return true;
        }

        if (file_exists(public_path($normalizedPath))) {
            return true;
        }

        try {
            $googleDrive = app(GoogleDriveLampiranService::class);
            if (!$googleDrive->enabled()) {
                return false;
            }

            return $googleDrive->downloadFile($normalizedPath) !== null;
        } catch (Throwable $throwable) {
            Log::warning('Google Drive exists check gagal.', [
                'path' => $normalizedPath,
                'message' => $throwable->getMessage(),
            ]);

            return false;
        }
    }

    private static function normalizePath($filepath)
    {
        $filepath = ltrim($filepath, '/');

        if (Str::startsWith($filepath, 'lampiran/')) {
            return 'lampirans/' . Str::after($filepath, 'lampiran/');
        }

        return $filepath;
    }

    private static function syncToGoogleDrive($filepath)
    {
        $normalizedPath = self::normalizePath($filepath);

        try {
            $googleDrive = app(GoogleDriveLampiranService::class);
            if (!$googleDrive->enabled()) {
                return false;
            }

            $absolutePath = storage_path('app/public/' . $normalizedPath);
            if (!file_exists($absolutePath)) {
                return false;
            }

            $googleDrive->uploadStoredFile($normalizedPath, $absolutePath);

            return true;
        } catch (Throwable $throwable) {
            Log::warning('Google Drive upload lampiran KP gagal.', [
                'path' => $normalizedPath,
                'message' => $throwable->getMessage(),
            ]);

            return false;
        }
    }

    private static function deleteFromGoogleDrive($filepath)
    {
        $normalizedPath = self::normalizePath($filepath);

        try {
            $googleDrive = app(GoogleDriveLampiranService::class);
            if ($googleDrive->enabled()) {
                $googleDrive->deleteFile($normalizedPath);
            }
        } catch (Throwable $throwable) {
            Log::warning('Google Drive delete lampiran KP gagal.', [
                'path' => $normalizedPath,
                'message' => $throwable->getMessage(),
            ]);
        }
    }
}
