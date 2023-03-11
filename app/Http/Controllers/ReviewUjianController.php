<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\ReviewUjian;
use App\Models\RevisiReviewUjian;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReviewUjianController extends Controller
{

    public function edit($id)
    {
        $review_ujian = ReviewUjian::findOrFail($id);

        if ($review_ujian->status == 'review' || $review_ujian->status == 'diterima'){
            return back();
        }

        $data = [
            'title' => 'Submit Laporan Proposal',
            'active' => 'ujian',
            'review' => $review_ujian,
        ];

        return view('pages.mahasiswa.ujian.submit-review', $data);
    }

    public function update(Request $request)
    {
        $review_ujian = ReviewUjian::findOrFail($request->id);

        if ($review_ujian->status == 'review' || $review_ujian->status == 'diterima'){
            return back();
        }

        $validatedData = $request->validate([
            'lampiran' => ['required', 'mimes:pdf, docx', 'max:5000'],
        ]);

        $validatedData['keterangan'] = $request->keterangan;
        $validatedData['status'] = ReviewUjian::REVIEW;

        if ($review_ujian->lampiran){
            AppHelper::instance()->deleteLampiran($review_ujian->lampiran);
        }

        $validatedData['lampiran'] = AppHelper::instance()->uploadLampiran($request->lampiran,'lampirans');

        $review_ujian->update($validatedData);

        return redirect('ujian/reviews/'.$review_ujian->ujian->id)->with('success','Laporan proposal berhasil disubmit.');
    }

    public function reviewDosen($id)
    {
        $review_ujian = ReviewUjian::findOrFail($id);

        $is_dosen_penguji_utama = $review_ujian->ujian->reviews()->where('dosen_status', ReviewUjian::DOSEN_PENGUJI)->first();

        $form_status = false;
        if ($is_dosen_penguji_utama->id == $review_ujian->id){
            $form_status = true;
        }

        $data = [
            'title' => 'Review Ujian TA',
            'active' => 'ujian',
            'sidebar' => 'partials.sidebarDosen',
            'review_ujian' => $review_ujian,
            'revisis' => $review_ujian->revisis()->orderBy('created_at','desc')->paginate(5),
            'form_status' => $form_status ? 1 : 0,
        ];

        return view('pages.dosen.ujian.review', $data);
    }
    public function revisiStore(Request $request)
    {
        $review_ujian = ReviewUjian::findOrFail($request->id);

        $revisi = new RevisiReviewUjian();
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

        if ($review_ujian->status == ReviewUjian::REVIEW) {
            $review_ujian->update([
                'status' => ReviewUjian::REVISI,
            ]);
            $review_ujian->revisis()->save($revisi);
            return back()->with('success', 'Revisi berhasil ditambahkan.');
        } elseif ($review_ujian->status == ReviewUjian::REVISI) {
            $review_ujian->revisis()->save($revisi);
            return back()->with('success', 'Revisi berhasil ditambahkan.');
        }
    }

    public function revisiDelete(Request $request)
    {
        $revisi = RevisiReviewUjian::findOrFail($request->id);
        AppHelper::instance()->deleteLampiran($revisi->lampiran);
        $revisi->delete();
        return back()->with('success', 'Revisi berhasil dihapus');
    }

    public function reviewAcc(Request $request)
    {
        $review_ujian = ReviewUjian::findOrFail($request->id);

        $review_ujian->update([
            'status' => ReviewUjian::DITERIMA,
            'tanggal_acc' => now(),
        ]);

        return back()->with('success','Ujian TA berhasil di Acc.');
    }

    public function reviewCancelAcc(Request $request)
    {
        $review_ujian = ReviewUjian::findOrFail($request->id);

        $review_ujian->update([
            'status' => ReviewUjian::REVIEW,
            'tanggal_acc' => null,
        ]);

        return back()->with('success','Ujian TA berhasil di Cancel Acc.');
    }

    public function reviewNilai(Request $request)
    {
        $review_ujian = ReviewUjian::findOrFail($request->id);

        if ($request->is_lulus){
            $review_ujian->ujian->update([
                'is_lulus' => $request->is_lulus,
            ]);
        }

        $review_ujian->update([
            'nilai_1' => $request->nilai_1,
            'nilai_2' => $request->nilai_2,
            'nilai_3' => $request->nilai_3,
            'nilai_4' => $request->nilai_4,
            'status' => ReviewUjian::DITERIMA,
        ]);

        return back()->with('success','Nilai Ujian TA berhasil disimpan');
    }

}
