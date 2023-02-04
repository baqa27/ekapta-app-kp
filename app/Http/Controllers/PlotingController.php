<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Mahasiswa;
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
        Dosen::findOrFail($request->dosen_penguji);
        $mahasiswa = Mahasiswa::where(['nim' => $request->nim])->first();
        if ($mahasiswa->dosens()->where(['status' => 'penguji'])->get()->isEmpty()) {
            $mahasiswa->dosens()->attach([
                $request->dosen_penguji => ['status' => 'penguji'],
            ]);
        } else {
            DB::table('dosen_mahasiswas')->where(['mahasiswa_id' => $mahasiswa->id, 'status' => 'penguji'])->delete();
            $mahasiswa->dosens()->attach([
                $request->dosen_penguji => ['status' => 'penguji'],
            ]);
        }
        return $mahasiswa->dosens()->get();
    }
}
