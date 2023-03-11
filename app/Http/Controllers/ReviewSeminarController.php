<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\ReviewSeminar;
use App\Models\RevisiReviewSeminar;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReviewSeminarController extends Controller
{
    public function edit($id)
    {
        $review_seminar = ReviewSeminar::findOrFail($id);

        if ($review_seminar->status == 'review' || $review_seminar->status == 'diterima'){
            return back();
        }

        $data = [
            'title' => 'Submit Laporan Proposal',
            'active' => 'seminar',
            'review' => $review_seminar,
        ];

        return view('pages.mahasiswa.seminar.submit-review', $data);
    }

    public function update(Request $request)
    {
        $review_seminar = ReviewSeminar::findOrFail($request->id);

        if ($review_seminar->status == 'review' || $review_seminar->status == 'diterima'){
            return back();
        }

        $validatedData = $request->validate([
           'lampiran' => ['required', 'mimes:pdf, docx', 'max:5000'],
        ]);

        $validatedData['keterangan'] = $request->keterangan;
        $validatedData['status'] = ReviewSeminar::REVIEW;

        if ($review_seminar->lampiran){
            AppHelper::instance()->deleteLampiran($review_seminar->lampiran);
        }

        $validatedData['lampiran'] = AppHelper::instance()->uploadLampiran($request->lampiran,'lampirans');

        $review_seminar->update($validatedData);

        return redirect('seminar/reviews/'.$review_seminar->seminar->id)->with('success','Laporan proposal berhasil disubmit.');
    }

    public function reviewDosen($id)
    {
        $review_seminar = ReviewSeminar::findOrFail($id);

        $is_dosen_penguji_utama = $review_seminar->seminar->reviews()->where('dosen_status', ReviewSeminar::DOSEN_PENGUJI)->first();

        $form_status = false;
        if ($is_dosen_penguji_utama->id == $review_seminar->id){
            $form_status = true;
        }

        $data = [
            'title' => 'Review Seminar TA',
            'active' => 'seminar',
            'sidebar' => 'partials.sidebarDosen',
            'review_seminar' => $review_seminar,
            'revisis' => $review_seminar->revisis()->orderBy('created_at','desc')->paginate(5),
            'form_status' => $form_status ? 1 : 0,
        ];

        return view('pages.dosen.seminar.review', $data);
    }
    public function revisiStore(Request $request)
    {
        $review_seminar = ReviewSeminar::findOrFail($request->id);

        $revisi = new RevisiReviewSeminar();
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

        if ($review_seminar->status == ReviewSeminar::REVIEW) {
            $review_seminar->update([
                'status' => ReviewSeminar::REVISI,
            ]);
            $review_seminar->revisis()->save($revisi);
            return back()->with('success', 'Revisi berhasil ditambahkan.');
        } elseif ($review_seminar->status == ReviewSeminar::REVISI) {
            $review_seminar->revisis()->save($revisi);
            return back()->with('success', 'Revisi berhasil ditambahkan.');
        }
    }

    public function revisiDelete(Request $request)
    {
        $revisi = RevisiReviewSeminar::findOrFail($request->id);
        AppHelper::instance()->deleteLampiran($revisi->lampiran);
        $revisi->delete();
        return back()->with('success', 'Revisi berhasil dihapus');
    }

    public function reviewAcc(Request $request)
    {
        $review_seminar = ReviewSeminar::findOrFail($request->id);

        $review_seminar->update([
            'status' => ReviewSeminar::DITERIMA,
            'tanggal_acc' => now(),
        ]);

        return back()->with('success','Seminar TA berhasil di Acc.');
    }

    public function reviewCancelAcc(Request $request)
    {
        $review_seminar = ReviewSeminar::findOrFail($request->id);

        $review_seminar->update([
            'status' => ReviewSeminar::REVIEW,
            'tanggal_acc' => null,
        ]);

        return back()->with('success','Seminar TA berhasil di Cancel Acc.');
    }

    public function reviewNilai(Request $request)
    {
        $review_seminar = ReviewSeminar::findOrFail($request->id);

        if ($request->is_lulus){
            $review_seminar->seminar->update([
                'is_lulus' => $request->is_lulus,
            ]);
        }

        $review_seminar->update([
            'nilai_1' => $request->nilai_1,
            'nilai_2' => $request->nilai_2,
            'nilai_3' => $request->nilai_3,
            'nilai_4' => $request->nilai_4,
            'status' => ReviewSeminar::DITERIMA,
        ]);

        return back()->with('success','Nilai Seminar TA berhasil disimpan');
    }
}
