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

        Admin::create([
            'kode' => 123456,
            'nik' => rand(pow(10, 16 - 1), pow(10, 16) - 1),
            'nama' => 'Admin Ekapta',
            'tgllahir' => $faker->date(),
            'tptlahir' => 'Wonosobo',
            'alamat' => $faker->address(),
            'email' => $faker->email(),
            'hp' => $faker->phoneNumber(),
            'password' => Hash::make(123456),
            'type' => Admin::TYPE_SUPER_ADMIN,
        ]);
    }
}
