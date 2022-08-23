<?php

namespace App\Helpers;

use App\Models\Bimbingan;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Pendaftaran;
use App\Models\Prodi;
use Illuminate\Support\Facades\Storage;

class AppHelper
{
    public function getMahasiswa($nim)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();
        if ($mahasiswa) {
            return $mahasiswa;
        }
    }

    public function getDosen($nidn)
    {
        $dosen = Dosen::where('nidn', $nidn)->first();
        if ($dosen) {
            return $dosen;
        }
    }

    public function getPendaftaran($nim)
    {
        $pendaftaran = Pendaftaran::where('nim', $nim)->first();
        if ($pendaftaran) {
            return $pendaftaran;
        }
    }

    public function cekBagianIsAcc($id)
    {
        $bimbingan = Bimbingan::where('id', $id)->where('status', 'diterima')->first();
        if ($bimbingan) {
            return true;
        }
    }

    public function uploadLampiran($lampiran, $path)
    {
        if ($lampiran) {
            // $lampiranName = uniqid() . '.' . $lampiran->extension();
            // $lampiran->move(public_path('/' . $path), $lampiranName);
            // $lampiranPath = '/' . $path . '/' . $lampiranName;
            // return $lampiranPath;
            $lampiranPath = $lampiran->store($path, 'public');
            return $lampiranPath;
        }
    }

    public function deleteLampiran($lampiran)
    {
        if ($lampiran) {
            if (file_exists(public_path($lampiran))) {
                unlink(public_path($lampiran));
            }
        }
    }

    public static function instance()
    {
        return new AppHelper();
    }
}
