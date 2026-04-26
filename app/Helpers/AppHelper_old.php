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
use Illuminate\Support\Str;
use App\Models\Mail;
use App\Models\Ujian;
use Illuminate\Support\Facades\Auth;

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
        return null;
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
            $lampiranPath = $lampiran->store($path, 'public');
            return '/ekapta-app/storage/app/public/'.$lampiranPath;
            // return $lampiranPath;
        }
    }

    public function deleteLampiran($lampiran)
    {
        // Use when hoting
        $target = Str::substr($lampiran,20); //output : /app/public/[files]
        if ($target) {
           if (file_exists(storage_path($target))) {
                unlink(storage_path($target));
           }
        }

        // if ($lampiran) {
        //    if (file_exists(public_path($lampiran))) {
        //        unlink(public_path($lampiran));
        //    }
        // }
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

    public function hitung_nilai_mean($nilai_1, $nilai_2, $nilai_3, $nilai_4)
    {
        return ($nilai_1 + $nilai_2 + $nilai_3 + $nilai_4) / 4;
    }

    public function hitung_nilai_total($nilai_1, $nilai_2, $nilai_3, $nilai_4)
    {
        return $nilai_1 + $nilai_2 + $nilai_3 + $nilai_4;
    }

    public static function parse_date($date){
        $parse_date = Carbon::parse($date);
        $new_date = $parse_date->isoFormat('dddd, D MMMM YYYY H:mm');
        return $new_date.' WIB';
    }

    public static function parse_date_short($date){
        $parse_date = Carbon::parse($date);
        $new_date = $parse_date->isoFormat('dddd, D MMMM YYYY H:mm');
        return $new_date.' WIB';
    }

     public static function parse_date_export($date){
        $parse_date = Carbon::parse($date);
        $new_date = $parse_date->format('d-m-Y');
        return $new_date;
    }

    public static function parse_date_short_surat($date){
        $parse_date = Carbon::parse($date);
        $new_date = $parse_date->isoFormat('D MMMM YYYY');
        return $new_date;
    }

    public static function count_mahasiswa_bimbingan_dosen($dosen, $is_utama = true){
        if ($is_utama) {
            $mahasiswas = $dosen->mahasiswas()->wherePivot('status', 'utama')->whereDoesntHave('jilid')->get();
            // $mahasiswas = $dosen->mahasiswas()->wherePivot('status', 'utama')->get();
        }else{
            $mahasiswas = $dosen->mahasiswas()->wherePivot('status', 'pendamping')->whereDoesntHave('jilid')->get();
            // $mahasiswas = $dosen->mahasiswas()->wherePivot('status', 'pendamping')->get();
        }
        return count($mahasiswas);
    }

    public static function check_bimbingan_is_complete($mahasiswa){
        $bimbingans_acc = $mahasiswa->bimbingans()->where('status', Bimbingan::DITERIMA)->get();
        $prodi = Prodi::where('namaprodi', $mahasiswa->prodi)->first();
        $bagians = $prodi->bagians()->where("tahun_masuk", "LIKE", "%" . $mahasiswa->thmasuk . "%")->get();

        if (count($bimbingans_acc ) - count($bagians) == count($bagians)){
            return true;
        }
        return false;
    }

    public function send_mail($details)
    {
        //\Mail::to($details['mail'])->send(new \App\Mail\NotificationMail($details));
        try {
            \Mail::to($details['mail'])->send(new \App\Mail\NotificationMail($details));
        } catch (\Throwable $e) {
            return back()->with('warning','Email notifikasi gagal terkirim');
        }
    }

    public static function hitung_nilai_mahasiswa($ujian_or_seminar)
    {
        $reviews = $ujian_or_seminar->reviews;
        $prodi = Prodi::where('namaprodi', $ujian_or_seminar->mahasiswa->prodi)->first();
        $presentase_nilai = $prodi->presentase_nilai;

        $nilai_penguji = 0;
        $nilai_pembimbing = 0;
        foreach ($reviews as $review) {
            if($review->dosen_status == 'penguji'){
                $nilai_penguji += AppHelper::instance()->hitung_nilai_total($review->nilai_1 * $presentase_nilai->presentase_1 / 100,$review->nilai_2 * $presentase_nilai->presentase_2 / 100, $review->nilai_3 * $presentase_nilai->presentase_3 / 100, $review->nilai_4 * $presentase_nilai->presentase_4 / 100);
            }else if($review->dosen_status == 'pembimbing'){
                $nilai_pembimbing += AppHelper::instance()->hitung_nilai_total($review->nilai_1 * $presentase_nilai->presentase_1 / 100,$review->nilai_2 * $presentase_nilai->presentase_2 / 100, $review->nilai_3 * $presentase_nilai->presentase_3 / 100, $review->nilai_4 * $presentase_nilai->presentase_4 / 100);
            }
        }

        $nilai_dosen_pembimbing = round($nilai_pembimbing / 2, 2);
        $nilai_dosen_penguji = round($nilai_penguji / count($ujian_or_seminar->reviews()->where('dosen_status', 'penguji')->get()), 2);

        $nilai = round(($presentase_nilai->bobot_pembimbing / 100 * $nilai_dosen_pembimbing) + ($presentase_nilai->bobot_penguji / 100 * $nilai_dosen_penguji), 2);

        $nilai_huruf = null;
        if ($nilai > 85) {
            $nilai_huruf = 'A';
        } else if ($nilai > 69) {
            $nilai_huruf = 'B';
        } else if ($nilai > 55) {
            $nilai_huruf = 'C';
        } else if ($nilai > 45) {
            $nilai_huruf = 'D';
        } else if ($nilai > 0) {
            $nilai_huruf = 'E';
        }
        return [
            'nilai_huruf' => $nilai_huruf,
            'nilai' => $nilai,
            'nilai_penguji' => $nilai_dosen_penguji,
            'nilai_pembimbing' => $nilai_dosen_pembimbing,
        ];
    }

    public static function check_ujian_has_done()
    {
        $user = Mahasiswa::with(['ujians'])->findOrFail(Auth::guard('mahasiswa')->user()->id);
        $ujian = $user->ujians()->whereNotIn('is_lulus', [Ujian::NOT_VALID_LULUS])->where('tanggal_ujian', '!=', null)->first();
        if ($ujian) {
            $date_expired = Carbon::parse($ujian->tanggal_ujian)->addDay();
            if(now()->gt($date_expired)){
                return  true;
            }
        }
        return false;
    }

    public static function instance()
    {
        return new AppHelper();
    }

    /**
     * Generate proper storage URL for file paths
     * Handles both old format (/ekapta-app/storage/...) and new format (storage/...)
     */
    public function storageUrl($path)
    {
        if (!$path) {
            return null;
        }

        // Jika path sudah lengkap dengan /ekapta-app/storage/app/public/
        if (str_starts_with($path, '/ekapta-app/storage/app/public/')) {
            return $path;
        }

        // Jika path dimulai dengan storage/
        if (str_starts_with($path, 'storage/')) {
            return '/' . $path;
        }

        // Jika path dimulai dengan lampirans/ atau folder lain
        if (str_starts_with($path, 'lampirans/') || str_starts_with($path, 'public/')) {
            return '/ekapta-app/storage/app/public/' . $path;
        }

        // Default: anggap path relatif dari storage/app/public
        return '/ekapta-app/storage/app/public/' . $path;
    }
}
