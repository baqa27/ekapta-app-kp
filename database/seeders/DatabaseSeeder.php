<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');
        $nims = [2020150031, 2020150032, 2020150033, 2020150034];
        $jenisKelamin = ['Laki-laki', 'Perempuan'];

        for ($i = 0; $i < sizeof($nims); $i++) {
            Mahasiswa::create([
                'nim' => $nims[$i],
                'nama' => $faker->name(),
                'thmasuk' => 2020,
                'prodi' => "Teknik Informatika",
                'tptlahir' => 'Wonosobo',
                'tgllahir' => $faker->date(),
                'jeniskelamin' => $jenisKelamin[rand(0, 1)],
                'kodedosenwali' => 1001,
                'nik' => rand(pow(10, 16 - 1), pow(10, 16) - 1),
                'kelas' => 'Reguler',
                'email' => $faker->email(),
                'hp' => $faker->phoneNumber(),
                'semester' => rand(7, 14),
                'status' => 'aktif',
                'alamat' => $faker->address(),
                'password' => Hash::make($nims[$i]),
            ]);
        }

        $nidns = [1001, 1002, 1003];

        for ($i = 0; $i < sizeof($nidns); $i++) {
            Dosen::create([
                'nidn' => $nidns[$i],
                'nik' => rand(pow(10, 16 - 1), pow(10, 16) - 1),
                'nama' => $faker->name(),
                'gelar' => "M.Kom.",
                'tgllahir' => $faker->date(),
                'tptlahir' => 'Wonosobo',
                'alamat' => $faker->address(),
                'email' => $faker->email(),
                'hp' => $faker->phoneNumber(),
                'kodeprodi' => 2001,
                'password' => Hash::make($nidns[$i]),
            ]);
        }

        Prodi::create([
            'kode' => 2001,
            'namaprodi' => 'Teknik Informatika',
            'jenjang' => 'S1',
            'kodekaprodi' => 1001,
            'password' => Hash::make(2001)
        ]);

        Admin::create([
            'kode' => 12345,
            'nik' => rand(pow(10, 16 - 1), pow(10, 16) - 1),
            'nama' => $faker->name(),
            'tgllahir' => $faker->date(),
            'tptlahir' => 'Wonosobo',
            'alamat' => $faker->address(),
            'email' => $faker->email(),
            'hp' => $faker->phoneNumber(),
            'password' => Hash::make(12345),
        ]);
    }
}
