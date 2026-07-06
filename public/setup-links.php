<?php
/**
 * SCRIPT SETUP SYMBOLIC LINKS UNTUK HOSTING
 * 
 * Akses via: https://new-ekapta.fastikom-unsiq.ac.id/setup-links.php
 * 
 * HAPUS FILE INI setelah symbolic links berhasil dibuat!
 */

// Prevent direct access from browser for security (optional)
$secret = isset($_GET['key']) ? $_GET['key'] : '';
if ($secret !== 'ekapta2026') {
    die('Access denied. Use: setup-links.php?key=ekapta2026');
}

echo "<!DOCTYPE html><html><head><title>Setup Storage Links</title>";
echo "<style>body{font-family:monospace;padding:20px;background:#f5f5f5}";
echo "pre{background:white;padding:15px;border-radius:5px;border:1px solid #ddd}</style></head><body>";
echo "<h2>🔗 Setup Symbolic Links untuk EKAPTA</h2>";
echo "<pre>";

function findFirstFile($directory) {
    if (!is_dir($directory)) {
        return null;
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file) {
        if ($file->isFile() && strpos($file->getFilename(), '.') !== 0) {
            return $file->getPathname();
        }
    }

    return null;
}

$results = [];
$results[] = "=== MEMBUAT SYMBOLIC LINKS ===\n";

// Define links
$links = [
    'lampirans' => '../storage/app/public/lampirans',
    'images' => '../storage/app/public/images',
];

foreach ($links as $linkName => $targetPath) {
    $results[] = "\n--- Processing: $linkName ---";
    
    $linkPath = __DIR__ . '/' . $linkName;
    $fullTargetPath = __DIR__ . '/' . $targetPath;
    
    // Check if link already exists
    if (file_exists($linkPath)) {
        if (is_link($linkPath)) {
            $currentTarget = readlink($linkPath);
            $results[] = "✓ Symbolic link sudah ada";
            $results[] = "  Link: $linkPath";
            $results[] = "  Target: $currentTarget";
        } else {
            $results[] = "✗ '$linkName' sudah ada tapi BUKAN symbolic link!";
            $results[] = "  Hapus dulu via DirectAdmin File Manager";
            $results[] = "  Path: $linkPath";
        }
        continue;
    }
    
    // Check if target exists
    if (!file_exists($fullTargetPath)) {
        $results[] = "✗ Target folder tidak ditemukan!";
        $results[] = "  Expected: $fullTargetPath";
        $results[] = "  Pastikan folder storage/app/public/$linkName ada";
        continue;
    }
    
    // Create symbolic link
    try {
        if (@symlink($targetPath, $linkPath)) {
            $results[] = "✓ BERHASIL membuat symbolic link!";
            $results[] = "  Link: $linkPath";
            $results[] = "  Target: $targetPath";
        } else {
            $error = error_get_last();
            $results[] = "✗ GAGAL membuat symbolic link";
            $results[] = "  Error: " . ($error['message'] ?? 'Unknown error');
            $results[] = "  Kemungkinan: Server tidak support symlink atau permission denied";
        }
    } catch (Exception $e) {
        $results[] = "✗ Exception: " . $e->getMessage();
    }
}

// Test file access
$results[] = "\n\n=== TEST AKSES FILE ===\n";
$sampleFile = findFirstFile(__DIR__ . '/lampirans/kp');
if (!$sampleFile) {
    $sampleFile = findFirstFile(__DIR__ . '/lampirans/ta');
}

if ($sampleFile && file_exists($sampleFile)) {
    $normalizedBase = str_replace('\\', '/', __DIR__);
    $normalizedFile = str_replace('\\', '/', $sampleFile);
    $relativeFile = ltrim(str_replace($normalizedBase, '', $normalizedFile), '/');
    $relativeUrl = str_replace(DIRECTORY_SEPARATOR, '/', $relativeFile);
    $results[] = "✓ File test DITEMUKAN via symbolic link!";
    $results[] = "  Path: $sampleFile";
    $results[] = "  Size: " . filesize($sampleFile) . " bytes";
    $results[] = "\n✓ URL test: https://new-ekapta.fastikom-unsiq.ac.id/" . $relativeUrl;
} else {
    $results[] = "✗ File sample belum ditemukan di lampirans/kp atau lampirans/ta";
    $results[] = "  Symbolic link bisa saja berhasil, tapi belum ada file yang bisa dites";
}

// Show results
echo implode("\n", $results);

echo "\n\n=== SELESAI ===";
echo "\n\n⚠️ PENTING: HAPUS file setup-links.php ini setelah selesai untuk keamanan!";
echo "</pre></body></html>";
?>
