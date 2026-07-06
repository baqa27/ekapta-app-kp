<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\KP\Himpunan;
use App\Models\Prodi;

class HimpunanBagianSeeder extends Seeder
{
    /**
     * Seeder untuk Himpunan dan Bagian Bimbingan KP saja.
     */
    public function run()
    {
        $this->command->info('');
        $this->command->info('╔════════════════════════════════════════════════════════╗');
        $this->command->info('║        📦 HIMPUNAN & BAGIAN BIMBINGAN KP SEEDER       ║');
        $this->command->info('╚════════════════════════════════════════════════════════╝');
        $this->command->info('');

        // Mapping Kode Prodi (Alias -> Real Code in DB)
        $prodiMap = [
            'TI' => '55201', // Teknik Informatika
            'MI' => '57401', // Manajemen Informatika
            'TS' => '22201', // Teknik Sipil
            'AR' => '23201', // Arsitektur
            'TM' => '21201', // Teknik Mesin
        ];

        // 1. Seed Bagian Bimbingan KP
        $this->seedBagianKP($prodiMap);

        // 2. Seed Himpunan KP
        $this->seedHimpunanKP($prodiMap);

        $this->command->info('');
        $this->command->info('✅ DONE! Himpunan & Bagian Bimbingan KP berhasil di-seed.');
    }

    private function seedBagianKP($prodiMap)
    {
        $this->command->info('📦 [1/2] Seeding Bagian Bimbingan KP...');
        $prodis = Prodi::all();
        
        foreach ($prodis as $prodi) {
            $bagians = [
                ['bagian' => 'Bimbingan Bab 1', 'tahun_masuk' => '2020,2021,2022,2023,2024,2025', 'is_seminar' => false, 'is_pendadaran' => false],
                ['bagian' => 'Bimbingan Bab 2', 'tahun_masuk' => '2020,2021,2022,2023,2024,2025', 'is_seminar' => false, 'is_pendadaran' => false],
                ['bagian' => 'Bimbingan Bab 3', 'tahun_masuk' => '2020,2021,2022,2023,2024,2025', 'is_seminar' => false, 'is_pendadaran' => false],
                ['bagian' => 'Bimbingan Bab 4', 'tahun_masuk' => '2020,2021,2022,2023,2024,2025', 'is_seminar' => false, 'is_pendadaran' => false],
                ['bagian' => 'Bimbingan Bab 5', 'tahun_masuk' => '2020,2021,2022,2023,2024,2025', 'is_seminar' => false, 'is_pendadaran' => false],
                ['bagian' => 'Full Laporan dan Produk', 'tahun_masuk' => '2020,2021,2022,2023,2024,2025', 'is_seminar' => true, 'is_pendadaran' => false],
            ];
            
            foreach ($bagians as $data) {
                DB::table('bagian_kps')->updateOrInsert(
                    ['prodi_id' => $prodi->id, 'bagian' => $data['bagian']],
                    array_merge($data, ['updated_at' => now()])
                );
            }
            
            $this->command->info("   + Bagian KP untuk Prodi: {$prodi->namaprodi}");
        }
    }

    private function seedHimpunanKP($prodiMap)
    {
        $this->command->info('📦 [2/2] Seeding Himpunan KP...');
        
        $himpunans = [
            ['kode' => 'TI', 'username' => 'himti', 'nama' => 'HIMTI'], 
            ['kode' => 'MI', 'username' => 'himami', 'nama' => 'HIMAMI'],
            ['kode' => 'TS', 'username' => 'himatesip', 'nama' => 'HIMATESIP'],
            ['kode' => 'AR', 'username' => 'himars', 'nama' => 'HIMARS'],
            ['kode' => 'TM', 'username' => 'himatem', 'nama' => 'HIMATEM']
        ];
        
        foreach ($himpunans as $h) {
            $realKode = $prodiMap[$h['kode']] ?? null;
            
            if ($realKode) {
                $prodi = Prodi::where('kode', $realKode)->first();
                
                if ($prodi) {
                    if (!Himpunan::where('username', $h['username'])->exists()) {
                        Himpunan::create([
                            'nama' => $h['nama'],
                            'username' => $h['username'],
                            'password' => Hash::make($h['username'] . '123'),
                            'prodi_id' => $prodi->id,
                            'is_pendaftaran_seminar_open' => true
                        ]);
                        $this->command->info("   + Himpunan: {$h['username']} / {$h['username']}123 (Prodi: {$prodi->namaprodi})");
                    } else {
                        $this->command->warn("   - Himpunan {$h['username']} sudah ada, skip.");
                    }
                }
            }
        }
    }
}
