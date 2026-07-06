<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class GoogleDriveLampiranService
{
    private const DRIVE_API_BASE = 'https://www.googleapis.com/drive/v3/';
    private const DRIVE_UPLOAD_BASE = 'https://www.googleapis.com/upload/drive/v3/';
    private const FOLDER_MIME_TYPE = 'application/vnd.google-apps.folder';
    private const TOKEN_URI = 'https://oauth2.googleapis.com/token';
    private const AUTH_MODE_SERVICE_ACCOUNT = 'service_account';
    private const AUTH_MODE_OAUTH_USER = 'oauth_user';

    protected $client;
    protected $config;
    protected $credentials;
    protected $accessToken;
    protected $accessTokenExpiresAt = 0;
    protected $folderCache = [];

    public function __construct(Client $client = null)
    {
        $this->config = config('services.google_drive_lampiran', []);
        $timeout = (float) ($this->config['timeout'] ?? 30);

        $this->client = $client ?: new Client([
            'timeout' => $timeout,
            'connect_timeout' => $timeout,
            'http_errors' => false,
        ]);
    }

    public function enabled()
    {
        if (!(bool) ($this->config['enabled'] ?? false)) {
            return false;
        }

        if (trim((string) ($this->config['lampiran_folder_id'] ?? '')) === '') {
            return false;
        }

        if ($this->usesServiceAccount()) {
            $credentialsPath = $this->credentialsPath();

            return $credentialsPath !== null && is_file($credentialsPath);
        }

        return $this->hasOauthUserCredentials();
    }

    public function configurationIssue()
    {
        if (!(bool) ($this->config['enabled'] ?? false)) {
            return 'Fitur Google Drive belum diaktifkan di .env.';
        }

        if (trim((string) ($this->config['lampiran_folder_id'] ?? '')) === '') {
            return 'GOOGLE_DRIVE_LAMPIRAN_FOLDER_ID belum diisi.';
        }

        if ($this->usesServiceAccount()) {
            $credentialsPath = $this->credentialsPath();

            if ($credentialsPath === null || !is_file($credentialsPath)) {
                return 'File service account Google Drive tidak ditemukan.';
            }

            return null;
        }

        if ($this->hasOauthUserCredentials()) {
            return null;
        }

        return 'GOOGLE_DRIVE_CLIENT_ID, GOOGLE_DRIVE_CLIENT_SECRET, dan GOOGLE_DRIVE_REFRESH_TOKEN harus diisi dengan nilai OAuth asli, bukan placeholder.';
    }

    public function uploadStoredFile($relativePath, $absolutePath)
    {
        if (!$this->enabled()) {
            return null;
        }

        if (!is_file($absolutePath)) {
            throw new RuntimeException('File lokal untuk upload Google Drive tidak ditemukan.');
        }

        $resolvedPath = $this->resolvePath($relativePath);
        if ($resolvedPath === null) {
            return null;
        }

        $folderId = $this->ensureFolderChain($resolvedPath['folder_names']);
        $existingFile = $this->findFileByName($folderId, $resolvedPath['file_name']);
        $content = file_get_contents($absolutePath);

        if ($content === false) {
            throw new RuntimeException('Gagal membaca file lokal sebelum upload ke Google Drive.');
        }

        $mimeType = mime_content_type($absolutePath) ?: 'application/octet-stream';

        if ($existingFile) {
            return $this->updateFile($existingFile['id'], $resolvedPath['file_name'], $content, $mimeType);
        }

        return $this->createFile($folderId, $resolvedPath['file_name'], $content, $mimeType);
    }

    public function downloadFile($relativePath)
    {
        if (!$this->enabled()) {
            return null;
        }

        try {
            $resolvedPath = $this->resolvePath($relativePath);
            if ($resolvedPath === null) {
                return null;
            }

            $folderId = $this->findFolderChain($resolvedPath['folder_names']);
            if ($folderId === null) {
                return null;
            }

            $file = $this->findFileByName($folderId, $resolvedPath['file_name']);
            if (!$file || empty($file['id'])) {
                return null;
            }

            $response = $this->request('GET', self::DRIVE_API_BASE . 'files/' . $file['id'], [
                'headers' => $this->authorizedHeaders(),
                'query' => [
                    'alt' => 'media',
                    'supportsAllDrives' => 'true',
                ],
            ]);

            if ($response['status'] >= 400) {
                Log::warning('Google Drive download lampiran gagal.', [
                    'path' => $relativePath,
                    'status' => $response['status'],
                    'body' => $response['body'],
                ]);

                return null;
            }

            return [
                'id' => $file['id'],
                'name' => $file['name'] ?? $resolvedPath['file_name'],
                'mime_type' => $file['mimeType'] ?? 'application/octet-stream',
                'content' => $response['body'],
            ];
        } catch (Throwable $throwable) {
            Log::warning('Google Drive download lampiran melempar exception.', [
                'path' => $relativePath,
                'message' => $throwable->getMessage(),
            ]);

            return null;
        }
    }

    public function deleteFile($relativePath)
    {
        if (!$this->enabled()) {
            return false;
        }

        try {
            $resolvedPath = $this->resolvePath($relativePath);
            if ($resolvedPath === null) {
                return false;
            }

            $folderId = $this->findFolderChain($resolvedPath['folder_names']);
            if ($folderId === null) {
                return false;
            }

            $file = $this->findFileByName($folderId, $resolvedPath['file_name']);
            if (!$file || empty($file['id'])) {
                return false;
            }

            $response = $this->request('DELETE', self::DRIVE_API_BASE . 'files/' . $file['id'], [
                'headers' => $this->authorizedHeaders(),
                'query' => [
                    'supportsAllDrives' => 'true',
                ],
            ]);

            return in_array($response['status'], [200, 204], true);
        } catch (Throwable $throwable) {
            Log::warning('Google Drive delete lampiran melempar exception.', [
                'path' => $relativePath,
                'message' => $throwable->getMessage(),
            ]);

            return false;
        }
    }

    protected function resolvePath($relativePath)
    {
        $path = str_replace('\\', '/', trim((string) $relativePath));
        $path = str_replace(['../', '..\\'], '', $path);
        $path = preg_replace('#/+#', '/', $path);
        $path = ltrim((string) $path, '/');

        if ($path === '') {
            return null;
        }

        if (Str::startsWith($path, 'storage/app/public/')) {
            $path = Str::after($path, 'storage/app/public/');
        } elseif (Str::startsWith($path, 'public/')) {
            $path = Str::after($path, 'public/');
        }

        if (Str::startsWith($path, 'lampiran/')) {
            $path = 'lampirans/' . Str::after($path, 'lampiran/');
        } elseif ($path === 'lampiran') {
            $path = 'lampirans/ta';
        }

        if (!Str::startsWith($path, 'lampirans/')) {
            return null;
        }

        $segments = array_values(array_filter(explode('/', Str::after($path, 'lampirans/')), 'strlen'));
        if (empty($segments)) {
            return null;
        }

        if (!in_array($segments[0], ['ta', 'kp'], true)) {
            array_unshift($segments, 'ta');
        }

        if (count($segments) < 2) {
            return null;
        }

        $fileName = array_pop($segments);

        return [
            'normalized_path' => 'lampirans/' . implode('/', $segments) . '/' . $fileName,
            'folder_names' => $segments,
            'file_name' => $fileName,
        ];
    }

    protected function ensureFolderChain($folderNames)
    {
        $currentParentId = $this->rootFolderId();

        foreach ($folderNames as $folderName) {
            $cacheKey = $currentParentId . ':' . $folderName;
            if (isset($this->folderCache[$cacheKey])) {
                $currentParentId = $this->folderCache[$cacheKey];
                continue;
            }

            $folder = $this->findFolderByName($currentParentId, $folderName);
            if (!$folder) {
                $folder = $this->createFolder($currentParentId, $folderName);
            }

            $currentParentId = $folder['id'];
            $this->folderCache[$cacheKey] = $currentParentId;
        }

        return $currentParentId;
    }

    protected function findFolderChain($folderNames)
    {
        $currentParentId = $this->rootFolderId();

        foreach ($folderNames as $folderName) {
            $cacheKey = $currentParentId . ':' . $folderName;
            if (isset($this->folderCache[$cacheKey])) {
                $currentParentId = $this->folderCache[$cacheKey];
                continue;
            }

            $folder = $this->findFolderByName($currentParentId, $folderName);
            if (!$folder) {
                return null;
            }

            $currentParentId = $folder['id'];
            $this->folderCache[$cacheKey] = $currentParentId;
        }

        return $currentParentId;
    }

    protected function findFolderByName($parentId, $folderName)
    {
        return $this->findFileByName($parentId, $folderName, self::FOLDER_MIME_TYPE);
    }

    protected function findFileByName($parentId, $fileName, $mimeType = null)
    {
        $query = sprintf(
            "'%s' in parents and name = '%s' and trashed = false",
            $this->escapeQueryValue($parentId),
            $this->escapeQueryValue($fileName)
        );

        if ($mimeType !== null) {
            $query .= sprintf(" and mimeType = '%s'", $this->escapeQueryValue($mimeType));
        }

        $response = $this->request('GET', self::DRIVE_API_BASE . 'files', [
            'headers' => $this->authorizedHeaders(),
            'query' => [
                'q' => $query,
                'fields' => 'files(id,name,mimeType)',
                'pageSize' => 1,
                'supportsAllDrives' => 'true',
                'includeItemsFromAllDrives' => 'true',
                'corpora' => 'allDrives',
            ],
        ]);

        if ($response['status'] >= 400) {
            throw new RuntimeException($this->errorMessage($response, 'Gagal mencari file di Google Drive.'));
        }

        $files = $response['json']['files'] ?? [];

        return $files[0] ?? null;
    }

    protected function createFolder($parentId, $folderName)
    {
        $response = $this->request('POST', self::DRIVE_API_BASE . 'files', [
            'headers' => $this->authorizedHeaders(),
            'query' => [
                'supportsAllDrives' => 'true',
                'fields' => 'id,name,mimeType',
            ],
            'json' => [
                'name' => $folderName,
                'mimeType' => self::FOLDER_MIME_TYPE,
                'parents' => [$parentId],
            ],
        ]);

        if ($response['status'] >= 400 || empty($response['json']['id'])) {
            throw new RuntimeException($this->errorMessage($response, 'Gagal membuat folder Google Drive.'));
        }

        return $response['json'];
    }

    protected function createFile($folderId, $fileName, $content, $mimeType)
    {
        $response = $this->multipartUpload(
            'POST',
            self::DRIVE_UPLOAD_BASE . 'files',
            [
                'name' => $fileName,
                'parents' => [$folderId],
            ],
            $content,
            $mimeType
        );

        if ($response['status'] >= 400 || empty($response['json']['id'])) {
            throw new RuntimeException($this->errorMessage($response, 'Gagal upload file ke Google Drive.'));
        }

        return $response['json'];
    }

    protected function updateFile($fileId, $fileName, $content, $mimeType)
    {
        $response = $this->multipartUpload(
            'PATCH',
            self::DRIVE_UPLOAD_BASE . 'files/' . $fileId,
            [
                'name' => $fileName,
            ],
            $content,
            $mimeType
        );

        if ($response['status'] >= 400 || empty($response['json']['id'])) {
            throw new RuntimeException($this->errorMessage($response, 'Gagal update file Google Drive.'));
        }

        return $response['json'];
    }

    protected function multipartUpload($method, $url, $metadata, $content, $mimeType)
    {
        $boundary = 'codex-' . bin2hex(random_bytes(12));
        $body = implode("\r\n", [
            '--' . $boundary,
            'Content-Type: application/json; charset=UTF-8',
            '',
            json_encode($metadata, JSON_UNESCAPED_SLASHES),
            '--' . $boundary,
            'Content-Type: ' . $mimeType,
            '',
            $content,
            '--' . $boundary . '--',
            '',
        ]);

        return $this->request($method, $url, [
            'headers' => $this->authorizedHeaders([
                'Content-Type' => 'multipart/related; boundary=' . $boundary,
            ]),
            'query' => [
                'uploadType' => 'multipart',
                'supportsAllDrives' => 'true',
                'fields' => 'id,name,mimeType',
            ],
            'body' => $body,
        ]);
    }

    protected function authorizedHeaders($headers = [])
    {
        return array_merge([
            'Authorization' => 'Bearer ' . $this->accessToken(),
        ], $headers);
    }

    protected function accessToken()
    {
        if ($this->accessToken && time() < ($this->accessTokenExpiresAt - 60)) {
            return $this->accessToken;
        }

        if ($this->usesOauthUser()) {
            return $this->refreshOauthUserAccessToken();
        }

        $credentials = $this->credentials();
        $now = time();
        $tokenUri = $credentials['token_uri'] ?? $this->tokenUri();
        $jwt = $this->buildJwt($credentials, $tokenUri, $now);

        $response = $this->request('POST', $tokenUri, [
            'form_params' => [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ],
        ]);

        if ($response['status'] >= 400 || empty($response['json']['access_token'])) {
            throw new RuntimeException($this->errorMessage($response, 'Gagal mengambil access token Google Drive.'));
        }

        $this->accessToken = $response['json']['access_token'];
        $this->accessTokenExpiresAt = $now + (int) ($response['json']['expires_in'] ?? 3600);

        return $this->accessToken;
    }

    protected function refreshOauthUserAccessToken()
    {
        $response = $this->request('POST', $this->tokenUri(), [
            'form_params' => [
                'client_id' => trim((string) ($this->config['client_id'] ?? '')),
                'client_secret' => trim((string) ($this->config['client_secret'] ?? '')),
                'refresh_token' => trim((string) ($this->config['refresh_token'] ?? '')),
                'grant_type' => 'refresh_token',
            ],
        ]);

        if ($response['status'] >= 400 || empty($response['json']['access_token'])) {
            throw new RuntimeException($this->errorMessage($response, 'Gagal mengambil access token OAuth user Google Drive.'));
        }

        $this->accessToken = $response['json']['access_token'];
        $this->accessTokenExpiresAt = time() + (int) ($response['json']['expires_in'] ?? 3600);

        return $this->accessToken;
    }

    protected function buildJwt($credentials, $tokenUri, $now)
    {
        $header = $this->base64UrlEncode(json_encode([
            'alg' => 'RS256',
            'typ' => 'JWT',
        ]));

        $payload = $this->base64UrlEncode(json_encode([
            'iss' => $credentials['client_email'],
            'scope' => 'https://www.googleapis.com/auth/drive',
            'aud' => $tokenUri,
            'iat' => $now,
            'exp' => $now + 3600,
        ]));

        $signatureInput = $header . '.' . $payload;
        $privateKey = openssl_pkey_get_private($credentials['private_key']);

        if ($privateKey === false) {
            throw new RuntimeException('Private key Google Drive tidak valid.');
        }

        $signature = '';
        $signed = openssl_sign($signatureInput, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        openssl_pkey_free($privateKey);

        if (!$signed) {
            throw new RuntimeException('Gagal menandatangani JWT Google Drive.');
        }

        return $signatureInput . '.' . $this->base64UrlEncode($signature);
    }

    protected function request($method, $url, $options = [])
    {
        try {
            $options['http_errors'] = false;
            $response = $this->client->request($method, $url, $options);
            $body = (string) $response->getBody();
            $json = json_decode($body, true);

            return [
                'status' => $response->getStatusCode(),
                'body' => $body,
                'json' => is_array($json) ? $json : null,
            ];
        } catch (Throwable $throwable) {
            throw new RuntimeException('Koneksi ke Google Drive gagal: ' . $throwable->getMessage(), 0, $throwable);
        }
    }

    protected function credentials()
    {
        if ($this->credentials !== null) {
            return $this->credentials;
        }

        if (!$this->usesServiceAccount()) {
            throw new RuntimeException('Mode Google Drive saat ini tidak menggunakan service account.');
        }

        $path = $this->credentialsPath();
        if ($path === null || !is_file($path)) {
            throw new RuntimeException('File service account Google Drive tidak ditemukan.');
        }

        $content = file_get_contents($path);
        if ($content === false) {
            throw new RuntimeException('Gagal membaca file service account Google Drive.');
        }

        $credentials = json_decode($content, true);
        if (
            !is_array($credentials) ||
            empty($credentials['client_email']) ||
            empty($credentials['private_key'])
        ) {
            throw new RuntimeException('Isi file service account Google Drive tidak valid.');
        }

        $this->credentials = $credentials;

        return $this->credentials;
    }

    protected function credentialsPath()
    {
        $path = trim((string) ($this->config['credentials_path'] ?? ''));
        if ($path === '') {
            return null;
        }

        if (preg_match('/^[A-Za-z]:[\\\\\\/]/', $path) === 1 || Str::startsWith($path, ['/', '\\'])) {
            return $path;
        }

        return base_path($path);
    }

    protected function rootFolderId()
    {
        return trim((string) ($this->config['lampiran_folder_id'] ?? ''));
    }

    protected function authMode()
    {
        $mode = trim((string) ($this->config['auth_mode'] ?? self::AUTH_MODE_SERVICE_ACCOUNT));

        return $mode !== '' ? $mode : self::AUTH_MODE_SERVICE_ACCOUNT;
    }

    protected function usesServiceAccount()
    {
        return $this->authMode() === self::AUTH_MODE_SERVICE_ACCOUNT;
    }

    protected function usesOauthUser()
    {
        return $this->authMode() === self::AUTH_MODE_OAUTH_USER;
    }

    protected function hasOauthUserCredentials()
    {
        return $this->hasRealOauthValue($this->config['client_id'] ?? null)
            && $this->hasRealOauthValue($this->config['client_secret'] ?? null)
            && $this->hasRealOauthValue($this->config['refresh_token'] ?? null);
    }

    protected function hasRealOauthValue($value)
    {
        $value = trim((string) $value);

        if ($value === '') {
            return false;
        }

        return !in_array(strtoupper($value), [
            'ISI_CLIENT_ID',
            'ISI_CLIENT_SECRET',
            'ISI_REFRESH_TOKEN',
        ], true);
    }

    protected function tokenUri()
    {
        $tokenUri = trim((string) ($this->config['token_uri'] ?? self::TOKEN_URI));

        return $tokenUri !== '' ? $tokenUri : self::TOKEN_URI;
    }

    protected function errorMessage($response, $fallback)
    {
        $message = $fallback;

        if (!empty($response['json']['error']['message'])) {
            $message = $response['json']['error']['message'];
        } elseif (!empty($response['body'])) {
            $message = $fallback . ' Response: ' . $response['body'];
        }

        if (
            $this->usesServiceAccount()
            && stripos($message, 'Service Accounts do not have storage quota') !== false
        ) {
            $message .= ' Folder di Drive Saya membutuhkan mode oauth_user atau pindahkan folder ke Shared Drive.';
        }

        return $message;
    }

    protected function escapeQueryValue($value)
    {
        return str_replace(['\\', '\''], ['\\\\', '\\\''], (string) $value);
    }

    protected function base64UrlEncode($value)
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }
}
