<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Admin;
use App\Models\Dosen;
use App\Models\Jilid;
use App\Models\Mahasiswa;
use App\Models\Pendaftaran;
use App\Models\Prodi;
use App\Models\Ujian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class JilidController extends Controller
{
    public function index()
    {
        return view('pages.fotokopi.dashboard-fotokopi', [
            'title' => 'Manajemen Jilid Tugas Akhir',
            'active' => 'bimbingan',
            'sidebar' => Auth::guard('admin')->user()->type == \App\Models\Admin::TYPE_SUPER_ADMIN ? 'partials.sidebarAdmin' : null,
            'active' => Auth::guard('admin')->user()->type == \App\Models\Admin::TYPE_SUPER_ADMIN ? 'jilid' : null,
            // 'jilids' => Jilid::with(['mahasiswa'])->whereIn('status', Auth::guard('admin')->user()->type == 1 ? [Jilid::JILID_REVIEW, Jilid::JILID_SELESAI] : [Jilid::JILID_VALID, Jilid::JILID_SELESAI])->orderBy('created_at', 'desc')->get(),
            'jilids' => Auth::guard('admin')->user()->type == Admin::TYPE_SUPER_ADMIN ? Jilid::orderBy('created_at','desc')->get() : Jilid::orderBy('created_at','desc')->where('status', Jilid::JILID_VALID)->get(),
        ]);
    }

    public function jilidMahasiswa()
    {
        $mahasiswa = Mahasiswa::with(['jilid'])->findOrFail(Auth::guard('mahasiswa')->user()->id);
        // $ujian_has_complete = $mahasiswa->ujians()->where('is_lulus', Ujian::VALID_LULUS)->first();
        // $reviews_has_acc = 0;
        // if ($ujian_has_complete) {
        //     $reviews_has_acc = count($ujian_has_complete->reviews()->where('status', 'diterima')->where('dosen_status', Dosen::PENGUJI)->get());
        // }

        $check_ujian_has_done = AppHelper::check_ujian_has_done();

        if (!$check_ujian_has_done) {
            return back()->with('warning', 'Menu jilid akftif pada H+1 setelah ujian pendadaran');
        } else if (!$mahasiswa->jilid) {
            return redirect()->route('jilid.create');
        }

        return view('pages.jilid.jilid', [
            'title' => 'Pengajuan Jilid Tugas Akhir',
            'active' => 'jilid',
            'jilids' => [$mahasiswa->jilid],
            'jilid' => $mahasiswa->jilid,
        ]);
    }

    public function create()
    {
        $mahasiswa = Mahasiswa::with(['ujians','pendaftarans'])->findOrFail(Auth::guard('mahasiswa')->user()->id);
        $check_ujian_has_done = AppHelper::check_ujian_has_done();
        $pendaftaran = $mahasiswa->pendaftarans()->where('status', Pendaftaran::DITERIMA)->first();

        if ($mahasiswa->jilid) {
            return redirect()->route('jilid.mahasiswa')->with('warning','Sudah melakukan pengajuan jilid tugas akhir. Tunggu validasi oleh Admin');
        }else if (!$check_ujian_has_done) {
            return back()->with('warning', 'Menu jilid akftif pada H+1 setelah ujian pendadaran');
        }

        $data = [
            'title' => 'Submit Jilid Tugas Akhir',
            'active' => 'jilid',
            'pendaftaran' => $pendaftaran,
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
            'laporan_pdf' => ['required', 'mimes:pdf', 'max:5000'],
            'laporan_word' => ['required', 'mimes:docx', 'max:5000'],
            'lembar_pengesahan' => ['required', 'mimes:pdf', 'max:500'],
            'lembar_keaslian' => ['required', 'mimes:pdf', 'max:500'],
            'lembar_persetujuan_penguji' => ['required', 'mimes:pdf', 'max:500'],
            'lembar_persetujuan_pembimbing' => ['required', 'mimes:pdf', 'max:500'],
            'lembar_bimbingan' => ['required', 'mimes:pdf', 'max:500'],
            'lembar_revisi' => ['required', 'mimes:pdf', 'max:500'],
            'artikel' => ['required', 'mimes:docx', 'max:5000'],
            'berita_acara' => [Rule::requiredIf(function() use($request){
                if (empty($request->berita_acara)) {
                    return false;
                }
                return true;
            }), 'mimes:pdf', 'max:500'],
        ]);
        $validatedData['laporan_pdf'] = AppHelper::instance()->uploadLampiran($request->laporan_pdf, 'lampirans');
        $validatedData['laporan_word'] = AppHelper::instance()->uploadLampiran($request->laporan_word, 'lampirans');
        $validatedData['lembar_pengesahan'] = AppHelper::instance()->uploadLampiran($request->lembar_pengesahan, 'lampirans');
        $validatedData['lembar_keaslian'] = AppHelper::instance()->uploadLampiran($request->lembar_keaslian, 'lampirans');
        $validatedData['lembar_persetujuan_penguji'] = AppHelper::instance()->uploadLampiran($request->lembar_persetujuan_penguji, 'lampirans');
        $validatedData['lembar_persetujuan_pembimbing'] = AppHelper::instance()->uploadLampiran($request->lembar_persetujuan_pembimbing, 'lampirans');
        $validatedData['lembar_bimbingan'] = AppHelper::instance()->uploadLampiran($request->lembar_bimbingan, 'lampirans');
        $validatedData['lembar_revisi'] = AppHelper::instance()->uploadLampiran($request->lembar_revisi, 'lampirans');
        $validatedData['artikel'] = AppHelper::instance()->uploadLampiran($request->artikel, 'lampirans');
        if ($request->berita_acara) {
            $validatedData['berita_acara'] = AppHelper::instance()->uploadLampiran($request->berita_acara, 'lampirans');
        }
        $validatedData['mahasiswa_id'] = $mahasiswa->id;
        $validatedData['status'] = Jilid::JILID_REVIEW;
        $validatedData['link_project'] = $request->link_project;
        Jilid::create($validatedData);
        return redirect()->route('jilid.mahasiswa')->with('success', 'Pengajuan jilid behasil. Silahkan tunggu vlidasi dari Admin');
    }

    public function detail($id)
    {
        $jilid = Jilid::with(['mahasiswa'])->where('id', $id)->first();
        $mahasiswa = $jilid->mahasiswa()->with(['bimbingans'])->first();
        $prodi = Prodi::where('namaprodi', $mahasiswa->prodi)->first();
        return view('pages.fotokopi.detail', [
            'title' => 'Detail Tugas Akhir',
            'sidebar' => Auth::guard('admin')->user()->type == \App\Models\Admin::TYPE_SUPER_ADMIN ? 'partials.sidebarAdmin' : null,
            'active' => Auth::guard('admin')->user()->type == \App\Models\Admin::TYPE_SUPER_ADMIN ? 'jilid' : null,
            'jilid' => $jilid,
            'mahasiswa' => $mahasiswa,
            'prodi' => $prodi,
            'is_admin' => true,
        ]);
    }

    public function detailMahasiswa($id)
    {
        $jilid = Jilid::with(['mahasiswa'])->where('id', $id)->first();
        $mahasiswa = $jilid->mahasiswa()->with(['bimbingans'])->first();
        $prodi = Prodi::where('namaprodi', $mahasiswa->prodi)->first();
        return view('pages.fotokopi.detail', [
            'title' => 'Detail Tugas Akhir',
            'active' => 'jilid',
            'jilid' => $jilid,
            'mahasiswa' => $mahasiswa,
            'prodi' => $prodi,
            'is_admin' => false,
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
            'active' => 'jilid',
            'jilid' => $jilid,
            'pendaftaran' => $jilid->mahasiswa->pendaftarans()->where('status', Pendaftaran::DITERIMA)->first(),
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
            'laporan_pdf' => [Rule::requiredIf(function () use ($request) {
                if (empty($request->laporan_pdf)) {
                    return false;
                }
                return true;
            }), 'mimes:pdf', 'max:5000'],
            'laporan_word' => [Rule::requiredIf(function () use ($request) {
                if (empty($request->laporan_word)) {
                    return false;
                }
                return true;
            }), 'mimes:docx', 'max:5000'],
            'lembar_pengesahan' => [Rule::requiredIf(function () use ($request) {
                if (empty($request->lembar_pengesahan)) {
                    return false;
                }
                return true;
            }), 'mimes:pdf', 'max:500'],
            'lembar_keaslian' => [Rule::requiredIf(function () use ($request) {
                if (empty($request->lembar_keaslian)) {
                    return false;
                }
                return true;
            }), 'mimes:pdf', 'max:500'],
            'lembar_persetujuan_pembimbing' => [Rule::requiredIf(function () use ($request) {
                if (empty($request->lembar_persetujuan_pembimbing)) {
                    return false;
                }
                return true;
            }), 'mimes:pdf', 'max:500'],
            'lembar_persetujuan_penguji' => [Rule::requiredIf(function () use ($request) {
                if (empty($request->lembar_persetujuan_penguji)) {
                    return false;
                }
                return true;
            }), 'mimes:pdf', 'max:500'],
            'lembar_bimbingan' => [Rule::requiredIf(function () use ($request) {
                if (empty($request->lembar_bimbingan)) {
                    return false;
                }
                return true;
            }), 'mimes:pdf', 'max:500'],
            'lembar_revisi' => [Rule::requiredIf(function () use ($request) {
                if (empty($request->lembar_revisi)) {
                    return false;
                }
                return true;
            }), 'mimes:pdf', 'max:500'],
            'artikel' => [Rule::requiredIf(function () use ($request) {
                if (empty($request->artikel)) {
                    return false;
                }
                return true;
            }), 'mimes:docx', 'max:5000'],
        ]);
        if ($request->laporan_pdf) {
            AppHelper::instance()->deleteLampiran($jilid->laporan_pdf);
            $validatedData['laporan_pdf'] = AppHelper::instance()->uploadLampiran($request->laporan_pdf, 'lampirans');
        }
        if ($request->laporan_word) {
            AppHelper::instance()->deleteLampiran($jilid->laporan_word);
            $validatedData['laporan_word'] = AppHelper::instance()->uploadLampiran($request->laporan_word, 'lampirans');
        }
        if ($request->lembar_pengesahan) {
            AppHelper::instance()->deleteLampiran($jilid->lembar_pengesahan);
            $validatedData['lembar_pengesahan'] = AppHelper::instance()->uploadLampiran($request->lembar_pengesahan, 'lampirans');
        }
        if ($request->lembar_keaslian) {
            AppHelper::instance()->deleteLampiran($jilid->lembar_keaslian);
            $validatedData['lembar_keaslian'] = AppHelper::instance()->uploadLampiran($request->lembar_keaslian, 'lampirans');
        }
        if ($request->lembar_persetujuan_pembimbing) {
            AppHelper::instance()->deleteLampiran($jilid->lembar_persetujuan_pembimbing);
            $validatedData['lembar_persetujuan_pembimbing'] = AppHelper::instance()->uploadLampiran($request->lembar_persetujuan_pembimbing, 'lampirans');
        }
        if ($request->lembar_persetujuan_penguji) {
            AppHelper::instance()->deleteLampiran($jilid->lembar_persetujuan_penguji);
            $validatedData['lembar_persetujuan_penguji'] = AppHelper::instance()->uploadLampiran($request->lembar_persetujuan_penguji, 'lampirans');
        }
        if ($request->lembar_bimbingan) {
            AppHelper::instance()->deleteLampiran($jilid->lembar_bimbingan);
            $validatedData['lembar_bimbingan'] = AppHelper::instance()->uploadLampiran($request->lembar_bimbingan, 'lampirans');
        }
        if ($request->lembar_revisi) {
            AppHelper::instance()->deleteLampiran($jilid->lembar_revisi);
            $validatedData['lembar_revisi'] = AppHelper::instance()->uploadLampiran($request->lembar_revisi, 'lampirans');
        }
        if ($request->artikel) {
            AppHelper::instance()->deleteLampiran($jilid->artikel);
            $validatedData['artikel'] = AppHelper::instance()->uploadLampiran($request->artikel, 'lampirans');
        }
        $validatedData['status'] = Jilid::JILID_REVIEW;
        $jilid->update($validatedData);
        return redirect()->route('jilid.mahasiswa')->with('success', 'Pengajuan jilid behasil. Silahkan tunggu vlidasi dari Admin');
    }

    public function acc(Request $request, $id)
    {
        $jilid = Jilid::findOrFail($id);
        $jilid->update([
            'total_pembayaran' => $request->total_pembayaran,
            'status' => $request->status,
            'catatan' => $request->catatan,
        ]);
        if ($request->status == Jilid::JILID_SELESAI) {
            AppHelper::instance()->send_mail([
                'mail' => $jilid->mahasiswa->email,
                'subject' => 'Jilid Tugas Ahir',
                'title' => 'EKAPTA',
                'message' => 'Jilid Tugas Akhir Anda Berstatus SELESAI. Silahkan ambil di FOTOKOPIAN FASTIKOM dan lakukan pembayaran sebesar Rp ' . number_format($request->total_pembayaran, 0, ',', '.'),
            ]);
        }else if ($request->status == Jilid::JILID_VALID) {
            AppHelper::instance()->send_mail([
                'mail' => $jilid->mahasiswa->email,
                'subject' => 'Jilid Tugas Ahir',
                'title' => 'EKAPTA',
                'message' => 'Dokumen Tugas Akhir sudah dikonfirmasi oleh admin dan siap untuk dijilid. Silahkan konfirmasi dan melakukan pembayaran ke Fotocopy fastikom dengan membawa dokumen-dokumen asli yang akan disertakan dalam penjilidan TA seperti lembar keaslian TA, lembar pengesahan, lembar bimbingan, lampiran-lampiran, dll.',
            ]);
        }
        return redirect()->route('jilid.index')->with('success', 'Pengajuan jilid behasil di update');
    }

    public function confirmCompleted($id)
    {
        $jilid = Jilid::findOrFail($id);
        if ($jilid->status != Jilid::JILID_SELESAI) {
            return back();
        }
        $jilid->update([
            'is_completed' => Jilid::JILID_COMPLETED,
        ]);
        return redirect()->route('jilid.index')->with('success', 'Konfirmasi setor jilid ke perpus berhasil disimpan');
    }

}
