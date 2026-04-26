<?php

namespace App\Http\Controllers;

use App\Services\GoogleDriveLampiranService;
use Illuminate\Support\Str;

class StorageController extends Controller
{
    protected $googleDrive;

    public function __construct(GoogleDriveLampiranService $googleDrive)
    {
        $this->googleDrive = $googleDrive;
    }

    /**
     * Serve file from Google Drive first when local copies are disabled,
     * otherwise use local storage first and Google Drive as fallback.
     */
    public function serveFile($path)
    {
        return $this->serveResolvedPath($path);
    }

    public function serveLegacyLampiran($path)
    {
        return $this->serveResolvedPath('lampiran/' . ltrim($path, '/'));
    }

    public function serveLampirans($path)
    {
        return $this->serveResolvedPath('lampirans/' . ltrim($path, '/'));
    }

    protected function serveResolvedPath($path)
    {
        $path = $this->sanitizePath($path);
        $candidatePaths = $this->candidatePaths($path);

        if ($this->shouldPreferGoogleDrive()) {
            $googleDriveResponse = $this->serveFromGoogleDrive($candidatePaths);
            if ($googleDriveResponse) {
                return $googleDriveResponse;
            }
        }

        foreach ($candidatePaths as $candidatePath) {
            $publicPath = public_path($candidatePath);
            if (file_exists($publicPath)) {
                $mimeType = mime_content_type($publicPath) ?: 'application/octet-stream';

                return response()->file($publicPath, [
                    'Content-Type' => $mimeType,
                    'X-Lampiran-Source' => 'public',
                ]);
            }

            $storagePath = storage_path('app/public/' . $candidatePath);
            if (file_exists($storagePath)) {
                $mimeType = mime_content_type($storagePath) ?: 'application/octet-stream';

                return response()->file($storagePath, [
                    'Content-Type' => $mimeType,
                    'X-Lampiran-Source' => 'storage',
                ]);
            }
        }

        $googleDriveResponse = $this->serveFromGoogleDrive($candidatePaths);
        if ($googleDriveResponse) {
            return $googleDriveResponse;
        }

        abort(404, 'File not found');
    }

    private function serveFromGoogleDrive($candidatePaths)
    {
        foreach ($candidatePaths as $candidatePath) {
            $googleDriveFile = $this->googleDrive->downloadFile($candidatePath);
            if ($googleDriveFile) {
                return response($googleDriveFile['content'], 200, [
                    'Content-Type' => $googleDriveFile['mime_type'] ?: 'application/octet-stream',
                    'Content-Disposition' => 'inline; filename="' . addcslashes($googleDriveFile['name'], '"\\') . '"',
                    'X-Lampiran-Source' => 'google-drive',
                ]);
            }
        }

        return null;
    }

    private function shouldPreferGoogleDrive()
    {
        if (!$this->googleDrive->enabled()) {
            return false;
        }

        return !$this->configBoolean('services.google_drive_lampiran.keep_local_copy', true);
    }

    private function configBoolean($key, $default)
    {
        $value = config($key, $default);

        if (is_bool($value)) {
            return $value;
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    private function candidatePaths($path)
    {
        $path = ltrim($path, '/');
        $paths = [];

        if (Str::startsWith($path, 'storage/app/public/')) {
            $path = Str::after($path, 'storage/app/public/');
        } elseif (Str::startsWith($path, 'public/')) {
            $path = Str::after($path, 'public/');
        }

        if ($path !== '') {
            $paths[] = $path;
        }

        if (Str::startsWith($path, 'lampiran/')) {
            $paths[] = 'lampirans/' . Str::after($path, 'lampiran/');
        }

        return array_values(array_unique($paths));
    }

    private function sanitizePath($path)
    {
        $path = str_replace('\\', '/', (string) $path);

        return str_replace(['../', '..\\'], '', $path);
    }

    /**
     * Serve lampiran file by filename only (no folder prefix).
     * Searches in lampirans/ta/ and lampirans/kp/ both on Drive and local.
     * URL: /abc123random.pdf
     */
    public function serveByFilename($filename)
    {
        $filename = basename($this->sanitizePath($filename));

        $candidates = [
            'lampirans/ta/' . $filename,
            'lampirans/kp/' . $filename,
        ];

        // Prefer Google Drive when local copies are disabled
        if ($this->shouldPreferGoogleDrive()) {
            $driveResponse = $this->serveFromGoogleDrive($candidates);
            if ($driveResponse) {
                return $driveResponse;
            }
        }

        // Check local files
        foreach ($candidates as $candidatePath) {
            $publicPath = public_path($candidatePath);
            if (file_exists($publicPath)) {
                return response()->file($publicPath, [
                    'Content-Type' => mime_content_type($publicPath) ?: 'application/octet-stream',
                    'X-Lampiran-Source' => 'public',
                ]);
            }

            $storagePath = storage_path('app/public/' . $candidatePath);
            if (file_exists($storagePath)) {
                return response()->file($storagePath, [
                    'Content-Type' => mime_content_type($storagePath) ?: 'application/octet-stream',
                    'X-Lampiran-Source' => 'storage',
                ]);
            }
        }

        // Final fallback: try Google Drive
        $driveResponse = $this->serveFromGoogleDrive($candidates);
        if ($driveResponse) {
            return $driveResponse;
        }

        abort(404, 'File not found');
    }
}
