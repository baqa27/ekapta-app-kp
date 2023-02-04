<?php

namespace App\Http\Controllers;

use App\Models\Bimbingan;
use App\Models\Mahasiswa;
use App\Models\Pengajuan;
use App\Models\Prodi;
use App\Models\Seminar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeminarController extends Controller
{
    public function index()
    {
        $prodi = Prodi::where('namaprodi', Auth::guard('mahasiswa')->user()->prodi)->first();
        $bagians_is_seminar = $prodi->bagians()->where('is_seminar', 1)->get();

        $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);
        $bimbingans_is_acc = $mahasiswa->bimbingans()->where('status', Bimbingan::DITERIMA)->get();

        $dosen_utama = $mahasiswa->dosens()->where('status', 'utama')->first();
        $dosen_pendamping = $mahasiswa->dosens()->where('status', 'pendamping')->first();
        $dosen_penguji = $mahasiswa->dosens()->where('status', 'penguji')->first();

        if(count($bimbingans_is_acc) - count($bagians_is_seminar) != count($bagians_is_seminar)){
            return back()->with('warning', 'Selesaikan bimbingan anda sampai dengan BAB '.count($bagians_is_seminar));
        }

        $seminars = $mahasiswa->seminars;

        $data = [
            'title' => 'Seminar TA',
            'active' => 'seminar',
            'dosen_utama' => $dosen_utama,
            'dosen_pendamping' => $dosen_pendamping,
            'dosen_penguji' => $dosen_penguji,
            'seminars' => $seminars,
        ];

        return view('pages.mahasiswa.seminar.seminar', $data);
    }

    public function create()
    {
        $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);
        $pengajuan_acc = $mahasiswa->pengajuans()->where('status', Pengajuan::DITERIMA)->first();
        $dosen_utama = $mahasiswa->dosens()->where('status', 'utama')->first();
        $dosen_pendamping = $mahasiswa->dosens()->where('status', 'pendamping')->first();

        $data = [
            'title' => 'Form Pendaftaran Seminar TA',
            'active' => 'seminar',
            'mahasiswa' => $mahasiswa,
            'pengajuan_acc' => $pengajuan_acc,
            'dosen_utama' => $dosen_utama,
            'dosen_pendamping' => $dosen_pendamping,
        ];

        return view('pages.mahasiswa.seminar.create', $data);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tanggal_pembuatan_ta' => 'required',
            'tanggal_acc_pembimbing_utama'  => 'required',
            'tanggal_acc_pembimbing_pendamping'  => 'required',
            'lampiran_1'  => ['required', 'mimes:jpg,png,jpeg'],
            'lampiran_2' => ['required', 'mimes:jpg,png,jpeg'],
            'lampiran_3' => ['required', 'mimes:jpg,png,jpeg'],
            'lampiran_4' => ['required', 'mimes:jpg,png,jpeg'],
            'lampiran_5' => ['required', 'mimes:jpg,png,jpeg'],
            'link_video' => 'required',
        ]);
        $validatedData['nim'] = Auth::guard('mahasiswa')->user()->nim;
        Seminar::create($validatedData);
        return $validatedData;
    }

    public function edit($seminar)
    {
        $seminar = Seminar::findOrFail($seminar);
        return $seminar;
    }

    public function update(Request $request)
    {
        $seminar = Seminar::findOrFail($request->id);
        $validatedData = $request->validate([
            'tanggal_pembuatan_ta' => 'required',
            'tanggal_acc_pembimbing_utama'  => 'required',
            'tanggal_acc_pembimbing_pendamping'  => 'required',
            'lampiran_1'  => ['required', 'mimes:jpg,png,jpeg'],
            'lampiran_2' => ['required', 'mimes:jpg,png,jpeg'],
            'lampiran_3' => ['required', 'mimes:jpg,png,jpeg'],
            'lampiran_4' => ['required', 'mimes:jpg,png,jpeg'],
            'lampiran_5' => ['required', 'mimes:jpg,png,jpeg'],
            'link_video' => 'required',
        ]);
        $seminar->update($validatedData);
        return $seminar;
    }

    public function delete(Request $request)
    {
        $seminar = Seminar::findOrFail($request->id);
        $seminar->delete();
        return 'Seminar has been deleted';
    }

    public function accSeminar(Request $request)
    {
        $seminar = Seminar::findOrFail($request->id);
        $seminar->update([]);
        return $seminar;
    }
}
