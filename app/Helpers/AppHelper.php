<?php

namespace App\Helpers;

use App\Models\Bimbingan;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\MahasiswaDetail;
use App\Models\Pendaftaran;
use App\Models\Pengajuan;
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

    public function getMahasiswaDetail($nim)
    {
        $mahasiswaDetail = MahasiswaDetail::where('nim', $nim)->first();
        if ($mahasiswaDetail) {
            return $mahasiswaDetail;
        }
    }

    public function getDosen($nidn)
    {
        $dosen = Dosen::where('nidn', $nidn)->first();
        if ($dosen) {
            return $dosen;
        }
    }

    public function getPengajuan($nim)
    {
        $pengajuan = Pengajuan::where('nim', $nim)->first();
        if ($pengajuan) {
            return $pengajuan;
        }
    }

    public function getProdi($kode)
    {
        $prodi = Prodi::where('kode', $kode)->first();
        if ($prodi) {
            return $prodi;
        }
    }

    public function getPendaftaran($nim)
    {
        $pendaftaran = Pendaftaran::where('nim', $nim)->first();
        if ($pendaftaran) {
            return $pendaftaran;
        }
    }

    public function getBimbinganIsAcc($mahasiswa_id)
    {
        $bimbingan = Bimbingan::where('mahasiswa_id', $mahasiswa_id)->where('status', 'diterima')->get();
        if ($bimbingan) {
            return $bimbingan;
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

    public function convertImage($base_path)
    {
        $path = base_path($base_path);
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $image = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $image;
    }

    public static function instance()
    {
        return new AppHelper();
    }
}