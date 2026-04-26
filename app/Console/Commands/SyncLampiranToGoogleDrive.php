<?php

namespace App\Console\Commands;

use App\Services\GoogleDriveLampiranService;
use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class SyncLampiranToGoogleDrive extends Command
{
    protected $signature = 'lampiran:sync-google-drive
                            {--source=all : public|storage|all}
                            {--dry-run : Tampilkan file tanpa upload}';

    protected $description = 'Sinkronkan file lampiran lokal ke Google Drive folder lampiran/ta dan lampiran/kp.';

    protected $googleDrive;

    public function __construct(GoogleDriveLampiranService $googleDrive)
    {
        parent::__construct();

        $this->googleDrive = $googleDrive;
    }

    public function handle()
    {
        if (!$this->googleDrive->enabled()) {
            $message = $this->googleDrive->configurationIssue()
                ?: 'Google Drive lampiran belum aktif. Cek .env dan file service account.';

            $this->error($message);

            return self::FAILURE;
        }

        $files = $this->collectFiles((string) $this->option('source'));

        if (empty($files)) {
            $this->warn('Tidak ada file lampiran lokal yang ditemukan.');

            return self::SUCCESS;
        }

        $this->info('Total file ditemukan: ' . count($files));

        if ($this->option('dry-run')) {
            foreach ($files as $relativePath => $absolutePath) {
                $this->line($relativePath . ' <= ' . $absolutePath);
            }

            $this->info('Dry run selesai. Tidak ada file yang diupload.');

            return self::SUCCESS;
        }

        $success = 0;
        $failed = 0;
        $bar = $this->output->createProgressBar(count($files));
        $bar->start();

        foreach ($files as $relativePath => $absolutePath) {
            try {
                $this->googleDrive->uploadStoredFile($relativePath, $absolutePath);
                $success++;
            } catch (\Throwable $throwable) {
                $failed++;
                $this->newLine();
                $this->error($relativePath . ' gagal: ' . $throwable->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info('Sinkron selesai. Berhasil: ' . $success . ', Gagal: ' . $failed);

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }

    protected function collectFiles($source)
    {
        $files = [];

        foreach ($this->sourceRoots($source) as $root) {
            if (!is_dir($root)) {
                continue;
            }

            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($root, RecursiveDirectoryIterator::SKIP_DOTS)
            );

            $normalizedRoot = rtrim(str_replace('\\', '/', $root), '/');

            foreach ($iterator as $fileInfo) {
                if (!$fileInfo->isFile()) {
                    continue;
                }

                if (substr($fileInfo->getFilename(), 0, 1) === '.') {
                    continue;
                }

                $absolutePath = str_replace('\\', '/', $fileInfo->getPathname());
                $relativeInsideLampiran = ltrim(substr($absolutePath, strlen($normalizedRoot)), '/');
                $relativePath = 'lampirans/' . $relativeInsideLampiran;

                if (!isset($files[$relativePath])) {
                    $files[$relativePath] = $fileInfo->getPathname();
                }
            }
        }

        ksort($files);

        return $files;
    }

    protected function sourceRoots($source)
    {
        $roots = [];
        $source = strtolower(trim($source));

        if (in_array($source, ['public', 'all'], true)) {
            $roots[] = public_path('lampirans');
        }

        if (in_array($source, ['storage', 'all'], true)) {
            $roots[] = storage_path('app/public/lampirans');
        }

        return $roots;
    }
}
