<?php

namespace App\Helpers;

use App\Models\Mahasiswa;
use App\Models\Prodi;

class AppHelper
{
    public function getNamaMahasiswa($nim)
    {
        $manahasiswa = Mahasiswa::where('nim', $nim)->first();
        return $manahasiswa->nama;
    }

    public function uploadLampiran($lampiran, $path)
    {
        if ($lampiran) {
            $lampiranName = uniqid() . '.' . $lampiran->extension();
            $lampiran->move(public_path('/' . $path), $lampiranName);
            $lampiranPath = '/' . $path . '/' . $lampiranName;
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
