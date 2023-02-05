<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Bimbingan;
use App\Models\Mahasiswa;
use App\Models\Pendaftaran;
use App\Models\Pengajuan;
use App\Models\Prodi;
use App\Models\Seminar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeminarController extends Controller
{
    public function index()
    {
        $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);
        $prodi = Prodi::where('namaprodi', $mahasiswa->prodi)->first();
        $bagians_is_seminar = $prodi->bagians()->where('is_seminar', 1)->get();

        $bimbingans_is_acc = $mahasiswa->bimbingans()->where('status', Bimbingan::DITERIMA)->get();
        $pendaftaran_acc = Pendaftaran::orderBy('created_at','desc')->where('mahasiswa_id', $mahasiswa->id)->where('status', 'diterima')->first();

        if(!$pendaftaran_acc){
            return back();
        }

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

        $pendaftaran_acc = Pendaftaran::orderBy('created_at','desc')->where('mahasiswa_id', $mahasiswa->id)->where('status', 'diterima')->first();

        if(!$pendaftaran_acc){
            return back();
        }

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
        $pengajuan = Pengajuan::where('mahasiswa_id', Auth::guard('mahasiswa')->user()->id)->where('status', Pengajuan::DITERIMA)->first();

        $pendaftaran_acc = Pendaftaran::orderBy('created_at','desc')->where('mahasiswa_id', $pengajuan->mahasiswa->id)->where('status', 'diterima')->first();

        if(AppHelper::instance()->is_expired_in_one_year($pendaftaran_acc->tanggal_acc)){
            return back();
        }else if($pengajuan->seminar){
            return redirect('seminar-mahasiswa')->with('warning', 'Sudah mendaftar seminar proposal');
        }

        $validatedData = $request->validate([
            'lampiran_1'  => ['required', 'mimes:jpg,png,jpeg,pdf'],
            'lampiran_2' => ['required', 'mimes:jpg,png,jpeg,pdf'],
            'lampiran_3' => ['required', 'mimes:jpg,png,jpeg,pdf'],
            'lampiran_4' => ['required', 'mimes:jpg,png,jpeg,pdf'],
            'lampiran_5' => ['required', 'mimes:jpg,png,jpeg,pdf'],
        ]);

        $validatedData['lampiran_1'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_1'), 'lampiran-pendaftaran');
        $validatedData['lampiran_2'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_2'), 'lampiran-pendaftaran');
        $validatedData['lampiran_3'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_3'), 'lampiran-pendaftaran');
        $validatedData['lampiran_4'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_4'), 'lampiran-pendaftaran');
        $validatedData['lampiran_5'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_5'), 'lampiran-pendaftaran');

        $validatedData['mahasiswa_id'] = Auth::guard('mahasiswa')->user()->id;
        $validatedData['pengajuan_id'] = $pengajuan->id;

        Seminar::create($validatedData);

        return redirect('seminar-mahasiswa')->with('success', 'Pendaftaran Seminar TA berhasil, selihkan tunggu validasi dari admin.');
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
