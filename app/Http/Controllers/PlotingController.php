<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\ReviewSeminar;
use App\Models\Seminar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlotingController extends Controller
{

    /*
    STATUS DOSEN
        utama = Dosen pembimbing utama
        pembimbing = Dosen pembimbing pendamping
        penguji = Dosen penguji
    */

    public function plotingPembimbing(Request $request)
    {
        Dosen::findOrFail($request->dosen_utama);
        Dosen::findOrFail($request->dosen_pendamping);
        $mahasiswa = Mahasiswa::where(['nim' => $request->nim])->first();
        if (count($mahasiswa->bimbingans) == 0) {
            if ($mahasiswa->dosens()->get()->isEmpty()) {
                $mahasiswa->dosens()->attach([
                    $request->dosen_utama => ['status' => 'utama'],
                    $request->dosen_pendamping => ['status' => 'pendamping'],
                ]);
            } else {
                DB::table('dosen_mahasiswas')->where(['mahasiswa_id' => $mahasiswa->id])->whereIn('status', ['utama', 'pendamping'])->delete();
                $mahasiswa->dosens()->attach([
                    $request->dosen_utama => ['status' => 'utama'],
                    $request->dosen_pendamping => ['status' => 'pendamping'],
                ]);
            }
            return back()->with('success', 'Ploting dosen pembimbing berhasil');
        }
    }

    public function plotingPenguji(Request $request)
    {
        $seminar = Seminar::findOrFail($request->seminar_id);

        $reviews_check = $seminar->reviews()->whereIn('status', [ReviewSeminar::DITERIMA, ReviewSeminar::REVISI])->get();

        if (count($reviews_check) != 0){
            return back()->with('warning', 'Dosen penguji sudah di ploting');
        }

        $dosens_penguji = $seminar->reviews()->where('dosen_status', ReviewSeminar::DOSEN_PENGUJI)->get();

        if ($dosens_penguji){
            foreach ($dosens_penguji as $dosen){
                $dosen->delete();
            }
        }

        for($i = 0; $i < count($request->dosen_penguji); $i++){
           ReviewSeminar::create([
               'seminar_id' => $seminar->id,
               'dosen_id' => $request->dosen_penguji[$i],
               'status' => ReviewSeminar::REVIEW,
               'dosen_status' => ReviewSeminar::DOSEN_PENGUJI,
           ]);
        }

        return back()->with('success', 'Ploting dosen penguji berhasil.');
    }
}
