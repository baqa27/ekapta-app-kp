<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Dosen;
use App\Models\DosenProdi;
use App\Models\Fakultas;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class LocalhostTestingSeeder extends Seeder
{
    /**
     * Seeder akun testing localhost.
     *
     * Jalankan dengan:
     * php artisan db:seed --class=LocalhostTestingSeeder
     */
    public function run()
    {
        $fakultas = Fakultas::query()->firstOrCreate(
            ['namafakultas' => 'FASTIKOM Testing'],
            ['image' => null]
        );

        Admin::query()->updateOrCreate(
            ['kode' => 'admintesting'],
            [
                'nik' => '0000000000000001',
                'nama' => 'Admin Testing',
                'tgllahir' => '1990-01-01',
                'tptlahir' => 'Wonosobo',
                'alamat' => 'Alamat Admin Testing',
                'email' => 'admintesting@localhost.test',
                'hp' => '081111111111',
                'password' => Hash::make('123456'),
                'type' => Admin::TYPE_SUPER_ADMIN,
            ]
        );

        $prodiPayload = [
            'namaprodi' => 'Prodi Testing',
            'jenjang' => 'S1',
            'kodekaprodi' => 'dosen1',
            'password' => Hash::make('123456'),
        ];

        if (Schema::hasColumn('prodis', 'fakultas_id')) {
            $prodiPayload['fakultas_id'] = $fakultas->id;
        }

        $prodi = Prodi::query()->updateOrCreate(
            ['kode' => '12345'],
            $prodiPayload
        );

        $dosen1 = Dosen::query()->updateOrCreate(
            ['nidn' => 'dosen1'],
            [
                'nik' => '0000000000000002',
                'nama' => 'Dosen Testing 1',
                'gelar' => 'M.Kom',
                'tgllahir' => '1985-01-01',
                'tptlahir' => 'Wonosobo',
                'alamat' => 'Alamat Dosen Testing 1',
                'email' => 'dosen1@localhost.test',
                'hp' => '081111111112',
                'kodeprodi' => $prodi->kode,
                'password' => Hash::make('dosen1'),
            ]
        );

        $dosen2 = Dosen::query()->updateOrCreate(
            ['nidn' => 'dosen2'],
            [
                'nik' => '0000000000000003',
                'nama' => 'Dosen Testing 2',
                'gelar' => 'M.Kom',
                'tgllahir' => '1986-01-01',
                'tptlahir' => 'Wonosobo',
                'alamat' => 'Alamat Dosen Testing 2',
                'email' => 'dosen2@localhost.test',
                'hp' => '081111111113',
                'kodeprodi' => $prodi->kode,
                'password' => Hash::make('dosen2'),
            ]
        );

        DosenProdi::query()->updateOrCreate(
            [
                'dosen_id' => $dosen1->id,
                'prodi_id' => $prodi->id,
            ],
            [
                'kode' => $prodi->kode,
                'nidn' => $dosen1->nidn,
            ]
        );

        DosenProdi::query()->updateOrCreate(
            [
                'dosen_id' => $dosen2->id,
                'prodi_id' => $prodi->id,
            ],
            [
                'kode' => $prodi->kode,
                'nidn' => $dosen2->nidn,
            ]
        );

        $mahasiswaPayload = [
            'nama' => 'Mahasiswa Testing',
            'thmasuk' => '2023',
            'prodi' => $prodi->kode,
            'tptlahir' => 'Wonosobo',
            'tgllahir' => '2003-01-01',
            'jeniskelamin' => 'L',
            'kodedosenwali' => $dosen1->nidn,
            'nik' => '0000000000000004',
            'kelas' => 'A',
            'email' => 'test123@localhost.test',
            'hp' => '081111111114',
            'alamat' => 'Alamat Mahasiswa Testing',
            'password' => Hash::make('test123'),
        ];

        if (Schema::hasColumn('mahasiswas', 'prodi_id')) {
            $mahasiswaPayload['prodi_id'] = $prodi->id;
        }

        if (Schema::hasColumn('mahasiswas', 'status_kp')) {
            $mahasiswaPayload['status_kp'] = Mahasiswa::STATUS_KP_BELUM_MULAI;
        }

        if (Schema::hasColumn('mahasiswas', 'tanggal_mulai_kp')) {
            $mahasiswaPayload['tanggal_mulai_kp'] = null;
        }

        if (Schema::hasColumn('mahasiswas', 'tanggal_selesai_kp')) {
            $mahasiswaPayload['tanggal_selesai_kp'] = null;
        }

        Mahasiswa::query()->updateOrCreate(
            ['nim' => 'test123'],
            $mahasiswaPayload
        );

        $this->command?->info('Akun testing localhost berhasil disiapkan.');
        $this->command?->line('Admin    : admintesting / 123456');
        $this->command?->line('Prodi    : 12345 / 123456');
        $this->command?->line('Dosen 1  : dosen1 / dosen1');
        $this->command?->line('Dosen 2  : dosen2 / dosen2');
        $this->command?->line('Mahasiswa: test123 / test123');
    }
}
