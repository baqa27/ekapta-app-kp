<?php
/**
 * Test apakah server support symbolic link
 * Akses: https://new-ekapta.fastikom-unsiq.ac.id/test-symlink.php
 * HAPUS setelah selesai!
 */

echo "<h2>Test Symbolic Link</h2>";
echo "<pre>";

$links = [
    'lampirans' => __DIR__ . '/../storage/app/public/lampirans',
    'images' => __DIR__ . '/../storage/app/public/images',
];

$index = 1;
foreach ($links as $linkName => $targetPath) {
    $publicLink = __DIR__ . '/' . $linkName;

    echo $index . ". Cek public/$linkName:\n";
    if (file_exists($publicLink)) {
        if (is_link($publicLink)) {
            echo "   ✓ Sudah ada (symbolic link)\n";
            echo "   Target: " . readlink($publicLink) . "\n";
        } else {
            echo "   ✓ Sudah ada (folder biasa, bukan symlink)\n";
        }
    } else {
        echo "   ✗ Belum ada\n";
    }

    echo "\n" . ($index + 1) . ". Cek " . str_replace(__DIR__ . '/../', '', $targetPath) . ":\n";
    if (file_exists($targetPath)) {
        echo "   ✓ Ada\n";
        if (is_dir($targetPath)) {
            $files = scandir($targetPath);
            $fileCount = count($files) - 2;
            echo "   Jumlah file/folder: $fileCount\n";
        }
    } else {
        echo "   ✗ Tidak ada\n";
    }

    echo "\n";
    $index += 2;
}

// Test terakhir: Coba buat symbolic link
echo $index . ". Test buat symbolic link sementara:\n";
$testLink = __DIR__ . '/test-link';
$testTarget = __DIR__ . '/../storage/app/public/lampirans';

if (file_exists($testLink)) {
    unlink($testLink);
}

if (@symlink($testTarget, $testLink)) {
    echo "   ✓ Server SUPPORT symbolic link!\n";
    echo "   Link berhasil dibuat: $testLink\n";
    unlink($testLink); // Hapus test link
} else {
    echo "   ✗ Server TIDAK support symbolic link\n";
    echo "   Error: " . error_get_last()['message'] . "\n";
}

echo "\n=== KESIMPULAN ===\n";
if (is_link(__DIR__ . '/lampirans')) {
    echo "✓ Symbolic link lampirans sudah ada, file lampiran seharusnya bisa diakses langsung\n";
} else {
    echo "✗ Symbolic link lampirans belum ada\n";
    echo "  Jalankan /setup/storage-link atau setup-links.php jika server mengizinkan symlink\n";
    echo "  Kalau symlink gagal, route Laravel /lampirans/{path} tetap bisa jadi fallback akses file\n";
}

echo "\n⚠️ HAPUS file test-symlink.php ini setelah selesai!\n";
echo "</pre>";
?>
