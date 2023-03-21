<?php

namespace App\Helpers;

use App\Models\Bimbingan;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\MahasiswaDetail;
use App\Models\Pendaftaran;
use App\Models\Pengajuan;
use App\Models\Prodi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            // Use when hoting
            //$lampiranPath = $lampiran->store($path, 'public');
            //return '/ekapta-app/storage/app/public/'.$lampiranPath;

            $lampiranPath = $lampiran->store($path, 'public');
            return $lampiranPath;
        }
    }

    public function deleteLampiran($lampiran)
    {
        // Use when hoting
        //$target = Str::substr($lampiran,20); //output : /app/public/[files]
        //if ($target) {
        //   if (file_exists(storage_path($target))) {
        //        unlink(storage_path($target));
        //    }
        //}
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

    public function is_expired_in_one_year($date)
    {
        $status = null;

        $date_expired = Carbon::parse($date)->addMonthsNoOverflow(12);
        if(now()->gt($date_expired)){
            $status = true;
        }

        return $status;
    }

    public function hitung_nilai_seminar($nilai_1, $nilai_2, $nilai_3, $nilai_4)
    {
        return ($nilai_1 + $nilai_2 + $nilai_3 + $nilai_4) / 4;
    }

    public function hitung_nilai_ujian($nilai_1, $nilai_2, $nilai_3, $nilai_4, $prodi)
    {
        $prodi = Prodi::findOrFail($prodi);
        $presentase_nilai = $prodi->presentase_nilai;

        $nilai = ($nilai_1 * $presentase_nilai->presentase_1 / 100) + ($nilai_2 * $presentase_nilai->presentase_2 / 100) + ($nilai_3 * $presentase_nilai->presentase_3 / 100) + ($nilai_4 * $presentase_nilai->presentase_4 / 100);

        return $nilai;
    }

    public static function instance()
    {
        return new AppHelper();
    }
}
