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

        // Seed 5 Himpunan accounts
        $himpunans = [
            ['username' => 'himpunan_ti', 'name' => 'Himpunan TI', 'email' => 'himpunan.ti@unsiq.ac.id'],
            ['username' => 'himpunan_si', 'name' => 'Himpunan SI', 'email' => 'himpunan.si@unsiq.ac.id'],
            ['username' => 'himpunan_if', 'name' => 'Himpunan IF', 'email' => 'himpunan.if@unsiq.ac.id'],
            ['username' => 'himpunan_ptik', 'name' => 'Himpunan PTIK', 'email' => 'himpunan.ptik@unsiq.ac.id'],
            ['username' => 'himpunan_dkv', 'name' => 'Himpunan DKV', 'email' => 'himpunan.dkv@unsiq.ac.id'],
        ];

        foreach ($himpunans as $himpunan) {
            DB::table('users')->insert([
                'username' => $himpunan['username'],
                'name' => $himpunan['name'],
                'email' => $himpunan['email'],
                'password' => Hash::make('password'),
                'role' => 'himpunan',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Seed 6 Bagian Bimbingan KP untuk semua prodi
        $prodis = ['D3-TI', 'S1-SI', 'S1-IF', 'S1-PTIK', 'S1-DKV'];
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
                DB::table('bagians')->insert([
                    'nama' => $bagianName,
                    'prodi' => $prodi,
                    'jenis' => 'kp',
                    'urutan' => $index + 1,
                    'tahun_masuk' => 2024,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('✅ Himpunan & Bagian KP seeded');
    }

    private function seedTestingMahasiswaWithBimbingan()
    {
        $this->command->info('Seeding Testing Mahasiswa with Complete Bimbingan...');

        // Ambil data yang diperlukan
        $prodi = DB::table('prodis')->where('kode', 'S1-IF')->first();
        if (!$prodi) {
            $this->command->error('Prodi S1-IF not found!');
            return;
        }

        $dosen = DB::table('dosens')->first();
        if (!$dosen) {
            $this->command->error('No dosen found!');
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

        // Create user for mahasiswa
        DB::table('users')->insert([
            'username' => '2023150999',
            'name' => 'Testing Mahasiswa KP',
            'email' => 'testing.kp@student.unsiq.ac.id',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'mahasiswa_id' => $mahasiswaId,
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
        $bagians = DB::table('bagians')
            ->where('prodi', $prodi->kode)
            ->where('jenis', 'kp')
            ->orderBy('urutan')
            ->get();

        foreach ($bagians as $index => $bagian) {
            $bimbinganId = DB::table('bimbingan_kps')->insertGetId([
                'mahasiswa_id' => $mahasiswaId,
                'bagian_id' => $bagian->id,
                'tanggal' => Carbon::now()->subDays(30 - ($index * 5))->format('Y-m-d'),
                'catatan' => $this->getCatatanForBagian($bagian->nama),
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
