<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Bimbingan;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Pendaftaran;
use App\Models\Pengajuan;
use App\Models\Prodi;
use App\Models\ReviewUjian;
use App\Models\RevisiUjian;
use App\Models\Ujian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UjianController extends Controller
{
    public function ujianMahasiswa()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        $ujian = $mahasiswa->ujian;
        $prodi = Prodi::where('namaprodi', $mahasiswa->prodi)->first();
        $bagians_is_ujian = $prodi->bagians()->where('is_pendadaran', 1)->get();

        $bimbingans_is_acc = $mahasiswa->bimbingans()->where('status', Bimbingan::DITERIMA)->get();
        $pendaftaran_acc = Pendaftaran::orderBy('created_at', 'desc')->where('mahasiswa_id', $mahasiswa->id)->where('status', 'diterima')->first();

        if (!$pendaftaran_acc) {
            return redirect('pendaftaran-mahasiswa');
        }

        $dosen_utama = $mahasiswa->dosens()->where('status', 'utama')->first();
        $dosen_pendamping = $mahasiswa->dosens()->where('status', 'pendamping')->first();

        if (count($bimbingans_is_acc) - count($bagians_is_ujian) < count($bagians_is_ujian)) {
            return redirect('bimbingan-mahasiswa')->with('warning', 'Selesaikan bimbingan anda sampai dengan BAB ' . count($bagians_is_ujian));
        }

        if (!$ujian) {
            return redirect('ujian/create');
        }

        $ujians_acc = $ujian->reviews()->where('status', ReviewUjian::DITERIMA)->get();

        $ujian_is_completed = false;
        if (count($ujians_acc) == 5){
            $ujian_is_completed = true;
        }

        $data = [
            'title' => 'Ujian Pendadaran TA',
            'active' => 'ujian',
            'mahasiswa' => $mahasiswa,
            'ujian' => $ujian,
            'dosen_utama' => $dosen_utama,
            'dosen_pendamping' => $dosen_pendamping,
            'dosens_penguji' => $ujian->reviews()->where('dosen_status', ReviewUjian::DOSEN_PENGUJI)->get(),
            'reviews_acc' => $ujian->reviews()->where('dosen_status', ReviewUjian::DOSEN_PENGUJI)->where('status', ReviewUjian::DITERIMA)->get(),
            'ujian_is_completed' => $ujian_is_completed,
        ];

        return view('pages.mahasiswa.ujian.ujian', $data);
    }

    public function ujianAdmin()
    {
        $ujians_review = Ujian::orderBy('created_at', 'desc')->where('is_valid', Ujian::REVIEW)->get();
        $ujians_revisi = Ujian::orderBy('created_at', 'desc')->where('is_valid', Ujian::REVISI)->get();
        $ujians_acc = Ujian::orderBy('created_at', 'desc')->where('is_valid', Ujian::DITERIMA)->get();

        $data = [
            'title' => 'Validasi Ujian TA',
            'active' => 'ujian',
            'sidebar' => 'partials.sidebarAdmin',
            'ujians_review' => $ujians_review,
            'ujians_revisi' => $ujians_revisi,
            'ujians_acc' => $ujians_acc,
        ];

        return view('pages.admin.ujian.ujian', $data);
    }

    public function ujianDosen()
    {
        $dosen = Dosen::findOrFail(Auth::guard('dosen')->user()->id);

        $data = [
            'title' => 'Review Ujian TA',
            'active' => 'ujian',
            'sidebar' => 'partials.sidebarDosen',
            'ujians_review' => $dosen->ujians()->where('status', ReviewUjian::REVIEW)->get(),
            'ujians_acc' => $dosen->ujians()->where('status', ReviewUjian::DITERIMA)->get(),
            'ujians_revisi' => $dosen->ujians()->where('status', ReviewUjian::REVISI)->get(),
        ];

        return view('pages.dosen.ujian.ujian', $data);
    }

    public function ujianProdi()
    {
        $prodi = Auth::guard('prodi')->user();
        $ujians = Ujian::with(['mahasiswa'])->get();

        $ujians_prodi = [];
        foreach ($ujians as $ujian) {
            if ($ujian->mahasiswa->prodi == $prodi->namaprodi) {
                $ujians_prodi[] = $ujian;
            }
        }

        $data = [
            'title' => 'Daftar Ujian Pendadaran Mahasiswa',
            'active' => 'ujian',
            'sidebar' => 'partials.sidebarProdi',
            'ujians' => $ujians_prodi,
        ];

        return view('pages.prodi.ujian.ujian', $data);
    }

    public function create()
    {
        $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);
        $pengajuan_acc = $mahasiswa->pengajuans()->where('status', Pengajuan::DITERIMA)->first();

        $pendaftaran_acc = Pendaftaran::orderBy('created_at', 'desc')->where('mahasiswa_id', $mahasiswa->id)->where('status', 'diterima')->first();

        if (!$pendaftaran_acc) {
            return redirect('pendaftaran-mahasiswa');
        } else if ($pengajuan_acc->ujian) {
            return redirect('ujian-mahasiswa')->with('warning', 'Sudah mendaftar ujian pendadaran TA');
        }

        $data = [
            'title' => 'Form Pendaftaran Ujian Pendadaran TA',
            'active' => 'ujian',
            'mahasiswa' => $mahasiswa,
            'pengajuan_acc' => $pengajuan_acc,
        ];

        return view('pages.mahasiswa.ujian.create', $data);
    }

    public function store(Request $request)
    {
        $pengajuan = Pengajuan::where('mahasiswa_id', Auth::guard('mahasiswa')->user()->id)->where('status', Pengajuan::DITERIMA)->first();

        $pendaftaran_acc = Pendaftaran::orderBy('created_at', 'desc')->where('mahasiswa_id', $pengajuan->mahasiswa->id)->where('status', 'diterima')->first();

        if (AppHelper::instance()->is_expired_in_one_year($pendaftaran_acc->tanggal_acc)) {
            return redirect('pedaftaran-mahasiswa');
        } else if ($pengajuan->ujian) {
            return redirect('ujian-mahasiswa')->with('warning', 'Sudah mendaftar ujian pendadaran');
        }

        $validatedData = $request->validate([
            'lampiran_1' => ['required', 'mimes:jpg,png,jpeg,pdf', 'max:5000'],
            'lampiran_2' => ['required', 'mimes:jpg,png,jpeg,pdf', 'max:5000'],
            'lampiran_3' => ['required', 'mimes:jpg,png,jpeg,pdf', 'max:5000'],
            'lampiran_4' => ['required', 'mimes:jpg,png,jpeg,pdf', 'max:5000'],
            'lampiran_5' => ['required', 'mimes:jpg,png,jpeg,pdf', 'max:5000'],
            'lampiran_6' => ['required', 'mimes:jpg,png,jpeg,pdf', 'max:5000'],
            'lampiran_7' => ['required', 'mimes:jpg,png,jpeg,pdf', 'max:5000'],
            'lampiran_8' => ['required', 'mimes:jpg,png,jpeg,pdf', 'max:5000'],
        ]);

        $validatedData['lampiran_1'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_1'), 'lampirans');
        $validatedData['lampiran_2'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_2'), 'lampirans');
        $validatedData['lampiran_3'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_3'), 'lampirans');
        $validatedData['lampiran_4'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_4'), 'lampirans');
        $validatedData['lampiran_5'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_5'), 'lampirans');
        $validatedData['lampiran_6'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_6'), 'lampirans');
        $validatedData['lampiran_7'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_7'), 'lampirans');
        $validatedData['lampiran_8'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_8'), 'lampirans');

        $validatedData['mahasiswa_id'] = Auth::guard('mahasiswa')->user()->id;
        $validatedData['pengajuan_id'] = $pengajuan->id;
        $validatedData['status'] = Ujian::REVIEW;

        Ujian::create($validatedData);

        return redirect('ujian-mahasiswa')->with('success', 'Pendaftaran Ujian Pendadaran TA berhasil, selihkan tunggu validasi dari admin.');
    }

    public function edit($id)
    {
        $ujian = Ujian::findOrFail($id);
        $mahasiswa = $ujian->mahasiswa;

        $pendaftaran_acc = Pendaftaran::orderBy('created_at', 'desc')->where('mahasiswa_id', $mahasiswa->id)->where('status', 'diterima')->first();

        if (!$pendaftaran_acc) {
            return redirect('pendaftaran-mahasiswa');
        }

        $data = [
            'title' => 'Submit Ujian TA',
            'active' => 'ujian',
            'ujian' => $ujian,
        ];

        return view('pages.mahasiswa.ujian.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $ujian = Ujian::findOrFail($id);

        $validatedData = $request->validate([
            'lampiran_1' => [
                Rule::requiredIf(function () {
                    if (empty($this->request->lampiran_1)) {
                        return false;
                    }
                    return true;
                }),
                'mimes:pdf,png,jpg,jpeg', 'max:5000'
            ],
            'lampiran_2' => [
                Rule::requiredIf(function () {
                    if (empty($this->request->lampiran_2)) {
                        return false;
                    }
                    return true;
                }),
                'mimes:pdf,png,jpg,jpeg', 'max:5000'
            ],
            'lampiran_3' => [
                Rule::requiredIf(function () {
                    if (empty($this->request->lampiran_3)) {
                        return false;
                    }
                    return true;
                }),
                'mimes:pdf,png,jpg,jpeg', 'max:5000'
            ],
            'lampiran_4' => [
                Rule::requiredIf(function () {
                    if (empty($this->request->lampiran_4)) {
                        return false;
                    }
                    return true;
                }),
                'mimes:pdf,png,jpg,jpeg', 'max:5000'
            ],
            'lampiran_5' => [
                Rule::requiredIf(function () {
                    if (empty($this->request->lampiran_5)) {
                        return false;
                    }
                    return true;
                }),
                'mimes:pdf,png,jpg,jpeg', 'max:5000'
            ],
            'lampiran_6' => [
                Rule::requiredIf(function () {
                    if (empty($this->request->lampiran_5)) {
                        return false;
                    }
                    return true;
                }),
                'mimes:pdf,png,jpg,jpeg', 'max:5000'
            ],
            'lampiran_7' => [
                Rule::requiredIf(function () {
                    if (empty($this->request->lampiran_5)) {
                        return false;
                    }
                    return true;
                }),
                'mimes:pdf,png,jpg,jpeg', 'max:5000'
            ],
            'lampiran_8' => [
                Rule::requiredIf(function () {
                    if (empty($this->request->lampiran_5)) {
                        return false;
                    }
                    return true;
                }),
                'mimes:pdf,png,jpg,jpeg', 'max:5000'
            ],
        ]);

        if ($request->file('lampiran_1')) {
            AppHelper::instance()->deleteLampiran($ujian->lampiran_1);
            $validatedData['lampiran_1'] = AppHelper::instance()->uploadLampiran($request->lampiran_1, 'lampirans');
        }
        if ($request->file('lampiran_2')) {
            AppHelper::instance()->deleteLampiran($ujian->lampiran_2);
            $validatedData['lampiran_2'] = AppHelper::instance()->uploadLampiran($request->lampiran_2, 'lampirans');
        }
        if ($request->file('lampiran_3')) {
            AppHelper::instance()->deleteLampiran($ujian->lampiran_3);
            $validatedData['lampiran_3'] = AppHelper::instance()->uploadLampiran($request->lampiran_3, 'lampirans');
        }
        if ($request->file('lampiran_4')) {
            AppHelper::instance()->deleteLampiran($ujian->lampiran_4);
            $validatedData['lampiran_4'] = AppHelper::instance()->uploadLampiran($request->lampiran_4, 'lampirans');
        }
        if ($request->file('lampiran_5')) {
            AppHelper::instance()->deleteLampiran($ujian->lampiran_5);
            $validatedData['lampiran_5'] = AppHelper::instance()->uploadLampiran($request->lampiran_5, 'lampirans');
        }
        if ($request->file('lampiran_6')) {
            AppHelper::instance()->deleteLampiran($ujian->lampiran_6);
            $validatedData['lampiran_6'] = AppHelper::instance()->uploadLampiran($request->lampiran_5, 'lampirans');
        }
        if ($request->file('lampiran_7')) {
            AppHelper::instance()->deleteLampiran($ujian->lampiran_7);
            $validatedData['lampiran_7'] = AppHelper::instance()->uploadLampiran($request->lampiran_5, 'lampirans');
        }
        if ($request->file('lampiran_8')) {
            AppHelper::instance()->deleteLampiran($ujian->lampiran_8);
            $validatedData['lampiran_8'] = AppHelper::instance()->uploadLampiran($request->lampiran_5, 'lampirans');
        }

        $validatedData['is_valid'] = 0;

        $ujian->update($validatedData);

        return redirect('ujian-mahasiswa')->with('success', 'Pendaftaran Ujian TA berhasil diupdate, silahkan tunggu review dari Admin');
    }

    public function detail($id)
    {
        $ujian = Ujian::findOrFail($id);
        $mahasiswa = $ujian->mahasiswa;

        $pendaftaran_acc = Pendaftaran::orderBy('created_at', 'desc')->where('mahasiswa_id', $mahasiswa->id)->where('status', 'diterima')->first();

        if (!$pendaftaran_acc) {
            return redirect('pendaftaran-mahasiswa');
        }

        $dosen_utama = $mahasiswa->dosens()->where('status', 'utama')->first();
        $dosen_pendamping = $mahasiswa->dosens()->where('status', 'pendamping')->first();

        $data = [
            'title' => 'Detail Ujian TA',
            'active' => 'ujian',
            'dosen_utama' => $dosen_utama,
            'dosen_pendamping' => $dosen_pendamping,
            'ujian' => $ujian,
            'revisis' => $ujian->revisis()->orderBy('created_at', 'desc')->paginate(5),
        ];

        return view('pages.mahasiswa.ujian.detail', $data);
    }

    public function ujianReviewAdmin($id)
    {
        $ujian = Ujian::findOrFail($id);
        $mahasiswa = $ujian->mahasiswa;
        $prodi = Prodi::where('namaprodi', $mahasiswa->prodi)->first();

        $dosens = Dosen::where('kodeprodi', $prodi->kode)->get();

        $dosen_utama = $mahasiswa->dosens()->where('status', 'utama')->first();
        $dosen_pendamping = $mahasiswa->dosens()->where('status', 'pendamping')->first();

        $reviews_check = $ujian->reviews()->whereIn('status', [ReviewUjian::DITERIMA, ReviewUjian::REVISI])->get();

        $data = [
            'title' => 'Review Pendaftaran Ujian TA',
            'active' => 'ujian',
            'sidebar' => 'partials.sidebarAdmin',
            'ujian' => $ujian,
            'dosens' => $dosens,
            'dosen_utama' => $dosen_utama,
            'dosen_pendamping' => $dosen_pendamping,
            'revisis' => $ujian->revisis()->orderBy('created_at', 'desc')->paginate(5),
            'dosens_penguji' => $ujian->reviews()->where('dosen_status', ReviewUjian::DOSEN_PENGUJI)->get(),
            'reviews_check' => $reviews_check,
        ];

        return view('pages.admin.ujian.review', $data);
    }

    public function revisiUjian(Request $request)
    {
        $ujian = Ujian::findOrFail($request->id);
        $revisi = new RevisiUjian();
        $revisi->catatan = $request->catatan;
        $request->validate([
            'lampiran' => [
                Rule::requiredIf(function () {
                    if (empty($this->request->lampiran)) {
                        return false;
                    }
                    return true;
                }),
                'mimes:pdf,docx', 'max:5000'
            ]
        ]);
        if ($request->file('lampiran')) {
            $revisi->lampiran = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampirans');
        }
        if ($ujian->is_valid == Ujian::REVIEW) {
            $ujian->update([
                'is_valid' => Ujian::REVISI,
            ]);
            $ujian->revisis()->save($revisi);
            return redirect('ujian-admin')->with('success', 'Ujian TA berhasil direvisi');
        } elseif ($ujian->is_valid == Ujian::REVISI) {
            $ujian->revisis()->save($revisi);
            return back()->with('success', 'Revisi berhasil ditambahkan');
        }
    }

    public function accUjian(Request $request)
    {
        $ujian = Ujian::findOrFail($request->id);

        if ($ujian->is_valid == 1 || count($ujian->reviews) == 5) {
            return back();
        }

        $mahasiswa = $ujian->mahasiswa;

        $dosen_utama = $mahasiswa->dosens()->where('status', 'utama')->first();
        $dosen_pendamping = $mahasiswa->dosens()->where('status', 'pendamping')->first();

        ReviewUjian::create([
            'ujian_id' => $ujian->id,
            'dosen_id' => $dosen_utama->id,
            'status' => ReviewUjian::REVIEW,
            'dosen_status' => ReviewUjian::DOSEN_PEMBIMBING,
        ]);

        ReviewUjian::create([
            'ujian_id' => $ujian->id,
            'dosen_id' => $dosen_pendamping->id,
            'status' => ReviewUjian::REVIEW,
            'dosen_status' => ReviewUjian::DOSEN_PEMBIMBING,
        ]);

        $ujian->update([
            'is_valid' => 1,
            'tanggal_acc' => now(),
        ]);

        return back()->with('success', 'Pendaftaran Ujian TA berhasil di Acc.');
    }

    public function cancelAcc(Request $request)
    {
        $ujian = Ujian::findOrFail($request->id);

        if (count($ujian->reviews) == 5) {
            return back();
        }

        foreach ($ujian->reviews as $review) {
            $review->delete();
        }

        $ujian->update([
            'is_valid' => 0,
        ]);

        return back()->with('success', 'Acc Ujian TA berhasil dibatalkan.');
    }

    public function deleteRevisi(Request $request)
    {
        $revisi = RevisiUjian::findOrFail($request->id);
        AppHelper::instance()->deleteLampiran($revisi->lampiran);
        $revisi->delete();
        return back()->with('success', 'Revisi berhasil dihapus');
    }

    public function setDateExam(Request $request)
    {
        $ujian = Ujian::findOrFail($request->ujian_id);
        $validatedData = $request->validate([
            'tanggal_ujian' => 'required',
        ]);
        $ujian->update($validatedData);
        return back();
    }

    public function ujianReviews($id)
    {
        $ujian = Ujian::findOrFail($id);

        $data = [
            'title' => 'Review Ujian TA',
            'active' => 'ujian',
            'ujian' => $ujian,
        ];

        return view('pages.mahasiswa.ujian.reviews', $data);
    }

    public function editProposal($id)
    {
        $ujian = Ujian::findOrFail($id);

        $data = [
            'title' => 'Submit Laporan Ujian Proposal',
            'active' => 'ujian',
            'ujian' => $ujian,
        ];

        return view('pages.mahasiswa.ujian.submit-proposal', $data);
    }

    public function updateProposal(Request $request, $id)
    {
        $ujian = Ujian::findOrFail($id);

        $request->validate([
            'lampiran_proposal' => ['required', 'mimes:pdf, docx', 'max:5000'],
        ]);

        $ujian->update([
            'lampiran_proposal' => AppHelper::instance()->uploadLampiran($request->lampiran_proposal, 'lampirans'),
        ]);

        return redirect('ujian-mahasiswa')->with('success', 'Laporan Ujian Proposal Berhasil di submit.');
    }

    public function ujianProdiDetail($id)
    {
        $ujian = Ujian::findOrFail($id);

        $prodi = Prodi::where('namaprodi', $ujian->mahasiswa->prodi)->first();
        $presentase_nilai = $prodi->presentase_nilai;

        if (count($ujian->reviews) != 5 || !$presentase_nilai) {
            return back();
        }

        $reviews_penguji = $ujian->reviews()->where('dosen_status', ReviewUjian::DOSEN_PENGUJI)->get();
        $reviews_pembimbing = $ujian->reviews()->where('dosen_status', ReviewUjian::DOSEN_PEMBIMBING)->get();

        $nilai_dosen_penguji_1 = AppHelper::instance()->hitung_nilai_ujian($reviews_penguji[0]->nilai_1, $reviews_penguji[0]->nilai_2, $reviews_penguji[0]->nilai_3, $reviews_penguji[0]->nilai_4, $prodi->id);
        $nilai_dosen_penguji_2 = AppHelper::instance()->hitung_nilai_ujian($reviews_penguji[1]->nilai_1, $reviews_penguji[1]->nilai_2, $reviews_penguji[1]->nilai_3, $reviews_penguji[1]->nilai_4, $prodi->id);
        $nilai_dosen_penguji_3 = AppHelper::instance()->hitung_nilai_ujian($reviews_penguji[2]->nilai_1, $reviews_penguji[2]->nilai_2, $reviews_penguji[2]->nilai_3, $reviews_penguji[2]->nilai_4, $prodi->id);

        $nilai_dosen_pembimbing_1 = AppHelper::instance()->hitung_nilai_ujian($reviews_pembimbing[0]->nilai_1, $reviews_pembimbing[0]->nilai_2, $reviews_pembimbing[0]->nilai_3, $reviews_pembimbing[0]->nilai_4, $prodi->id);
        $nilai_dosen_pembimbing_2 = AppHelper::instance()->hitung_nilai_ujian($reviews_pembimbing[1]->nilai_1, $reviews_pembimbing[1]->nilai_2, $reviews_pembimbing[1]->nilai_3, $reviews_pembimbing[1]->nilai_4, $prodi->id);

        $nilai_dosen_pembimbing = ($nilai_dosen_pembimbing_1 + $nilai_dosen_pembimbing_2) / 2;
        $nilai_dosen_penguji = ($nilai_dosen_penguji_1 + $nilai_dosen_penguji_2 + $nilai_dosen_penguji_3) / 3;

        $nilai = ($presentase_nilai->bobot_pembimbing / 100 * $nilai_dosen_pembimbing) + ($presentase_nilai->bobot_penguji / 100 * $nilai_dosen_penguji);

        $data = [
            'title' => 'Detail Seminar Mahasiswa',
            'active' => 'ujian',
            'sidebar' => 'partials.sidebarProdi',
            'ujian' => $ujian,
            'revisis' => $ujian->revisis()->paginate(5),
            'nilai' => $nilai,
            'nilai_dosen_penguji' => $nilai_dosen_penguji,
            'nilai_dosen_pembimbing' => $nilai_dosen_pembimbing,
            'prodi' => $prodi,
        ];

        return view('pages.prodi.ujian.detail', $data);
    }
}
