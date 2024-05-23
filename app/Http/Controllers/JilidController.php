<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Jilid;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class JilidController extends Controller
{
    public function index()
    {
        return view('pages.fotokopi.dashboard-fotokopi', [
            'title' => 'Manajemen Jilid Skripsi',
            'active' => 'bimbingan',
            'jilids' => Jilid::with(['mahasiswa'])->where('status', Auth::guard('admin')->user()->type == 1 ? Jilid::JILID_REVIEW : Jilid::JILID_VALID)->orderBy('created_at', 'desc')->get(),
        ]);
    }

    public function create()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        if ($mahasiswa->jilid) {
            return back();
        }
        $data = [
            'title' => 'Submit Jilid Tugas Akhir',
            'active' => 'bimbingan',
        ];

        return view('pages.jilid.submit-jilid', $data);
    }

    public function store(Request $request)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        if ($mahasiswa->jilid) {
            return back();
        }
        $validatedData = $request->validate([
            'link_project' => ['required'],
            'laporan_pdf' => ['required', 'mimes:pdf', 'max:5000'],
            'laporan_word' => ['required', 'mimes:docx', 'max:5000'],
            'lembar_pengesahan' => ['required', 'mimes:pdf', 'max:500'],
        ]);
        $validatedData['laporan_pdf'] = AppHelper::instance()->uploadLampiran($request->laporan_pdf, 'lampirans');
        $validatedData['laporan_word'] = AppHelper::instance()->uploadLampiran($request->laporan_pdf, 'lampirans');
        $validatedData['lembar_pengesahan'] = AppHelper::instance()->uploadLampiran($request->laporan_pdf, 'lampirans');
        $validatedData['mahasiswa_id'] = $mahasiswa->id;
        $validatedData['status'] = Jilid::JILID_REVIEW;
        Jilid::create($validatedData);
        return redirect()->route('bimbingan.mahasiswa')->with('success', 'Pengajuan jilid behasil. Silahkan tunggu vlidasi dari Admin');
    }

    public function detail($id)
    {
        $jilid = Jilid::with(['mahasiswa'])->where('id', $id)->first();
        $mahasiswa = $jilid->mahasiswa()->with(['bimbingans'])->first();
        $prodi = Prodi::where('namaprodi', $mahasiswa->prodi)->first();
        return view('pages.fotokopi.detail', [
            'title' => 'Detail Skripsi',
            'jilid' => $jilid,
            'mahasiswa' => $mahasiswa,
            'prodi' => $prodi,
        ]);
    }

    public function edit($id)
    {
        $jilid = Jilid::with(['mahasiswa'])->where('id', $id)->first();
        if ($jilid->status != Jilid::JILID_REVISI) {
            return back();
        }
        $data = [
            'title' => 'Revisi Jilid Tugas Akhir',
            'active' => 'bimbingan',
            'jilid' => $jilid,
        ];

        return view('pages.jilid.submit-jilid-revisi', $data);
    }

    public function update(Request $request, $id)
    {
        $jilid = Jilid::with(['mahasiswa'])->where('id', $id)->first();
        if ($jilid->status != Jilid::JILID_REVISI) {
            return back();
        }
        $validatedData = $request->validate([
            'link_project' => ['required'],
            'laporan_pdf' => [Rule::requiredIf(function () {
                if (empty($this->request->laporan_pdf)) {
                    return false;
                }
                return true;
            }), 'mimes:pdf', 'max:5000'],
            'laporan_word' => [Rule::requiredIf(function () {
                if (empty($this->request->laporan_word)) {
                    return false;
                }
                return true;
            }), 'mimes:docx', 'max:5000'],
            'lembar_pengesahan' => [Rule::requiredIf(function () {
                if (empty($this->request->lembar_pengesahan)) {
                    return false;
                }
                return true;
            }), 'mimes:pdf', 'max:500'],
        ]);
        if ($request->laporan_pdf) {
            AppHelper::instance()->deleteLampiran($jilid->laporan_pdf);
            $validatedData['laporan_pdf'] = AppHelper::instance()->uploadLampiran($request->laporan_pdf, 'lampirans');
        }
        if ($request->laporan_word) {
            AppHelper::instance()->deleteLampiran($jilid->laporan_word);
            $validatedData['laporan_word'] = AppHelper::instance()->uploadLampiran($request->laporan_pdf, 'lampirans');
        }
        if ($request->lembar_pengesahan) {
            AppHelper::instance()->deleteLampiran($jilid->lembar_pengesahan);
            $validatedData['lembar_pengesahan'] = AppHelper::instance()->uploadLampiran($request->laporan_pdf, 'lampirans');
        }
        $validatedData['status'] = Jilid::JILID_REVIEW;
        $jilid->update($validatedData);
        return redirect()->route('bimbingan.mahasiswa')->with('success', 'Pengajuan jilid behasil. Silahkan tunggu vlidasi dari Admin');
    }

    public function acc(Request $request, $id)
    {
        $jilid = Jilid::findOrFail($id);
        $jilid->update([
            'total_pembayaran' => $request->total_pembayaran,
            'status' => $request->status,
            'catatan' => $request->catatan,
        ]);
        return redirect()->route('jilid.index')->with('success', 'Pengajuan jilid behasil di update');
    }
}
