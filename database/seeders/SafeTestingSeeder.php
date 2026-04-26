<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SafeTestingSeeder extends Seeder
{
    /**
     * Seeder untuk testing lengkap dengan data bimbingan per BAB
     * Run this seeder: php artisan db:seed --class=SafeTestingSeeder
     */
    public function run()
    {
        // 1. Seed Himpunan & Bagian KP
        $this->seedHimpunanAndBagian();
        
        // 2. Seed Data Testing Mahasiswa dengan Bimbingan Lengkap
        $this->seedTestingMahasiswaWithBimbingan();
        
        $this->command->info('✅ SafeTestingSeeder completed successfully!');
    }

    private function seedHimpunanAndBagian()
    {
        $this->command->info('Seeding Himpunan & Bagian KP...');

        // Get all prodis
        $prodis = DB::table('prodis')->get();
        
        if ($prodis->isEmpty()) {
            $this->command->warn('No prodis found! Skipping himpunan seeding.');
        } else {
            // Seed Himpunan accounts for each prodi
            foreach ($prodis as $prodi) {
                $username = 'him_' . strtolower(str_replace(['-', ' '], '_', $prodi->kode));
                
                // Check if himpunan already exists
                $exists = DB::table('himpunan_kps')->where('username', $username)->exists();
                
                if (!$exists) {
                    DB::table('himpunan_kps')->insert([
                        'nama' => 'Himpunan ' . $prodi->namaprodi,
                        'username' => $username,
                        'password' => Hash::make('password'),
                        'prodi_id' => $prodi->id,
                        'is_pendaftaran_seminar_open' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $this->command->info("  + Himpunan: {$username} (Prodi: {$prodi->namaprodi})");
                }
            }
        }

        // Seed 6 Bagian Bimbingan KP untuk semua prodi
        $prodis = DB::table('prodis')->get();
        $bagianNames = [
            'Bimbingan Bab 1',
            'Bimbingan Bab 2',
            'Bimbingan Bab 3',
            'Bimbingan Bab 4',
            'Bimbingan Bab 5',
            'Full Laporan dan Produk',
        ];

        foreach ($prodis as $prodi) {
            foreach ($bagianNames as $index => $bagianName) {
                // Check if bagian already exists
                $exists = DB::table('bagian_kps')
                    ->where('prodi_id', $prodi->id)
                    ->where('bagian', $bagianName)
                    ->exists();
                
                if (!$exists) {
                    DB::table('bagian_kps')->insert([
                        'bagian' => $bagianName,
                        'prodi_id' => $prodi->id,
                        'tahun_masuk' => '2020,2021,2022,2023,2024,2025',
                        'is_seminar' => ($index >= 4), // Bab 5 dan Full Laporan untuk seminar
                        'is_pendadaran' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
            $this->command->info("  + Bagian KP untuk Prodi: {$prodi->namaprodi}");
        }

        $this->command->info('✅ Himpunan & Bagian KP seeded');
    }

    private function seedTestingMahasiswaWithBimbingan()
    {
        $this->command->info('Seeding Testing Mahasiswa with Complete Bimbingan...');

        // Ambil data yang diperlukan
        $prodi = DB::table('prodis')->first();
        if (!$prodi) {
            $this->command->error('No prodi found!');
            return;
        }

        $dosen = DB::table('dosens')->first();
        if (!$dosen) {
            $this->command->error('No dosen found!');
            return;
        }

        // Check if testing mahasiswa already exists
        $existingMhs = DB::table('mahasiswas')->where('nim', '2023150999')->first();
        if ($existingMhs) {
            $this->command->warn('Testing mahasiswa already exists, skipping...');
            return;
        }

        // Create testing mahasiswa
        $mahasiswaId = DB::table('mahasiswas')->insertGetId([
            'nim' => '2023150999',
            'nama' => 'Testing Mahasiswa KP',
            'prodi' => $prodi->kode,
            'email' => 'testing.kp@student.unsiq.ac.id',
            'no_hp' => '081234567890',
            'angkatan' => 2023,
            'status' => 'aktif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create pengajuan KP
        $pengajuanId = DB::table('pengajuan_kps')->insertGetId([
            'mahasiswa_id' => $mahasiswaId,
            'judul' => 'Sistem Informasi Manajemen Perpustakaan Berbasis Web',
            'tempat_kp' => 'PT. Teknologi Indonesia',
            'alamat_kp' => 'Jakarta Selatan',
            'tanggal_mulai' => Carbon::now()->subMonths(3)->format('Y-m-d'),
            'tanggal_selesai' => Carbon::now()->subMonth()->format('Y-m-d'),
            'status' => 'diterima',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assign dosen pembimbing
        DB::table('dosen_mahasiswas')->insert([
            'mahasiswa_id' => $mahasiswaId,
            'dosen_id' => $dosen->id,
            'status' => 'pembimbing',
            'jenis' => 'kp',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create pendaftaran KP
        DB::table('pendaftaran_kps')->insert([
            'mahasiswa_id' => $mahasiswaId,
            'pengajuan_id' => $pengajuanId,
            'status' => 'diterima',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create bimbingan untuk setiap BAB (Bab 1-5 + Full Laporan)
        $bagians = DB::table('bagian_kps')
            ->where('prodi_id', $prodi->id)
            ->orderBy('id')
            ->get();

        foreach ($bagians as $index => $bagian) {
            $bimbinganId = DB::table('bimbingan_kps')->insertGetId([
                'mahasiswa_id' => $mahasiswaId,
                'bagian_id' => $bagian->id,
                'tanggal' => Carbon::now()->subDays(30 - ($index * 5))->format('Y-m-d'),
                'catatan' => $this->getCatatanForBagian($bagian->bagian),
                'status' => 'selesai',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // ACC bimbingan
            DB::table('bimbingan_kps')->where('id', $bimbinganId)->update([
                'acc_pembimbing' => 1,
                'tanggal_acc_pembimbing' => Carbon::now()->subDays(29 - ($index * 5))->format('Y-m-d H:i:s'),
            ]);
        }

        $this->command->info('✅ Testing Mahasiswa with complete bimbingan seeded');
    }

    private function getCatatanForBagian($bagianNama)
    {
        $catatan = [
            'Bimbingan Bab 1' => 'Bab 1 (Pendahuluan) sudah baik. Latar belakang dan rumusan masalah sudah jelas.',
            'Bimbingan Bab 2' => 'Bab 2 (Landasan Teori) lengkap. Teori pendukung sudah sesuai dengan topik.',
            'Bimbingan Bab 3' => 'Bab 3 (Metodologi) sudah sistematis. Flowchart dan diagram sudah jelas.',
            'Bimbingan Bab 4' => 'Bab 4 (Implementasi) sudah detail. Screenshot dan penjelasan fitur lengkap.',
            'Bimbingan Bab 5' => 'Bab 5 (Penutup) sudah baik. Kesimpulan dan saran sudah sesuai.',
            'Full Laporan dan Produk' => 'Laporan lengkap dan produk sudah berfungsi dengan baik. Siap untuk seminar.',
        ];

        return $catatan[$bagianNama] ?? 'Bimbingan selesai dengan baik.';
    }
}
