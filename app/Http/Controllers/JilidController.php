<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Jilid;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JilidController extends Controller
{
    public function index(){
        return view('pages.fotokopi.dashboard-fotokopi',[
            'title' => 'Manajemen Jilid Skripsi',
            'active' => 'bimbingan',
            'jilids' => Jilid::with(['mahasiswa'])->orderBy('created_at','desc')->get(),
        ]);
    }

    public function store(){
        $mahasiswa = Auth::guard('mahasiswa')->user();
        if ($mahasiswa->jilid) {
            return back();
        }
        Jilid::create([
            'mahasiswa_id' => $mahasiswa->id,
        ]);
        return back()->with('success', 'Pengajuan jilid behasil. Silahkan tunggu ACC dari fotokopian');
    }

    public function detail($id){
        $jilid = Jilid::with(['mahasiswa'])->where('id', $id)->first();
        $mahasiswa = $jilid->mahasiswa()->with(['bimbingans'])->first();
        $prodi = Prodi::where('namaprodi', $mahasiswa->prodi)->first();
        return view('pages.fotokopi.detail',[
            'title' => 'Detail Skripsi',
            'jilid' => $jilid,
            'mahasiswa' => $mahasiswa,
            'prodi' => $prodi,
        ]);
    }

    public function acc(Request $request, $id){
        $jilid = Jilid::findOrFail($id);
        $jilid->update([
            'total_pembayaran' => $request->total_pembayaran,
            'status' => 1,
        ]);
        return back()->with('success', 'Pengajuan jilid behasil di Acc');
    }
}
