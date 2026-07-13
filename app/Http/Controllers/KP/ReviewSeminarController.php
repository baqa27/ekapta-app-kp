<?php

namespace App\Http\Controllers\KP;

use App\Helpers\AppHelper;
use App\Helpers\StorageHelper;

// Import KP Models
use App\Models\KP\ReviewSeminar;
use App\Models\KP\RevisiReviewSeminar;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReviewSeminarController extends \App\Http\Controllers\Controller
{
    public function edit($id)
    {
        $review_seminar = ReviewSeminar::findOrFail($id);

        if ($review_seminar->status == 'review' || $review_seminar->status == 'diterima'){
            return back();
        }

        $data = [
            'title' => 'Submit Laporan Proposal',
            'active' => 'seminar-kp',
            'review' => $review_seminar,
        ];

        return view('kp.pages.mahasiswa.seminar.submit-review', $data);
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

        $validatedData['lampiran'] = StorageHelper::storeKpFile($request->lampiran, $review_seminar->seminar->mahasiswa->nim, 'seminar');



        $review_seminar->update($validatedData);

        return redirect()->route('kp.seminar.reviews', $review_seminar->seminar->id)->with('success','Laporan proposal berhasil disubmit.');
    }

    public function reviewDosen($id)
    {
        $review_seminar = ReviewSeminar::findOrFail($id);

        $is_dosen_penguji_utama = $review_seminar->seminar->reviews()->where('dosen_status', ReviewSeminar::DOSEN_PENGUJI)->first();

        $form_status = false;
        if ($is_dosen_penguji_utama){
            if ($is_dosen_penguji_utama->id == $review_seminar->id){
                $form_status = true;
            }
        }

        $data = [
            'title' => 'Review Seminar TA',
            'active' => 'seminar-kp',
            'sidebar' => 'kp.partials.sidebarDosen',
            'module' => 'kp',
            'review_seminar' => $review_seminar,
            'revisis' => $review_seminar->revisis()->orderBy('created_at','desc')->paginate(5),
            'form_status' => $form_status ? 1 : 0,
        ];

        return view('kp.pages.dosen.seminar.review', $data);
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


        $revisi->lampiran = $review_seminar->lampiran ? $review_seminar->lampiran : $review_seminar->seminar->lampiran_3;

        if ($review_seminar->status == ReviewSeminar::REVIEW) {
            $review_seminar->update([
                'status' => ReviewSeminar::REVISI,
            ]);
            $review_seminar->revisis()->save($revisi);
            if ($review_seminar->seminar->mahasiswa->email != '-') {
                AppHelper::instance()->send_mail([
                    'mail' => $review_seminar->seminar->mahasiswa->email,
                    'subject' => 'Seminar Kerja Praktek',
                    'title' => 'EKAPTA',
                    'message' => 'Seminar Kerja Praktek Anda Berstatus REVISI. Silahkan perbaiki kemudian lakukan submit ulang!. <br><br>Catatan revisi: '.$request->catatan,
                ]);
            }
            return back()->with('success', 'Revisi berhasil ditambahkan.');
        } elseif ($review_seminar->status == ReviewSeminar::REVISI) {
            $review_seminar->revisis()->save($revisi);
            return back()->with('success', 'Revisi berhasil ditambahkan.');
        }
    }

    public function revisiDelete(Request $request)
    {
        $revisi = RevisiReviewSeminar::findOrFail($request->id);

        $revisi->delete();
        return back()->with('success', 'Revisi berhasil dihapus');
    }

    public function reviewAcc(Request $request)
    {
        $review_seminar = ReviewSeminar::findOrFail($request->id);

        $revisi = new RevisiReviewSeminar();
        $revisi->catatan = $request->catatan;
        $revisi->lampiran = $review_seminar->lampiran ? $review_seminar->lampiran : $review_seminar->seminar->lampiran_3;
        $review_seminar->update([
            'status' => ReviewSeminar::DITERIMA,
            'tanggal_acc' => $request->type ? $review_seminar->tanggal_acc_manual: now(),
        ]);
        $review_seminar->revisis()->save($revisi);
        if ($review_seminar->seminar->mahasiswa->email != '-') {
            AppHelper::instance()->send_mail([
                'mail' => $review_seminar->seminar->mahasiswa->email,
                'subject' => 'Seminar Kerja Praktek',
                'title' => 'EKAPTA',
                'message' => 'Selamat Seminar Kerja Praktek Anda Berstatus DITERIMA.',
            ]);
        }
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

    public function submitManual($id)
    {
        $review_seminar = ReviewSeminar::findOrFail($id);
        if ($review_seminar->status == 'revisi' || $review_seminar->status == 'diterima'){
            return back();
        }

        $data = [
            'title' => 'Submit Acc Manual',
            'active' => 'seminar-kp',
            'review' => $review_seminar,
        ];

        return view('kp.pages.mahasiswa.seminar.submit-acc-manual', $data);
    }

    public function submitManualStore(Request $request, $id)
    {
        $review_seminar = ReviewSeminar::findOrFail($id);
        if ($review_seminar->status == 'revisi' || $review_seminar->status == 'diterima'){
            return back();
        }

        $validatedData = $request->validate([
            'lampiran_lembar_revisi' => ['required', 'mimes:pdf, jpg,jpeg,png', 'max:5000'],
            'tanggal_acc_manual' => 'required',
         ]);
         $validatedData['lampiran_lembar_revisi'] = StorageHelper::storeKpFile($request->lampiran_lembar_revisi, $review_seminar->seminar->mahasiswa->nim, 'seminar');
        $review_seminar->update($validatedData);

        return redirect()->route('kp.seminar.reviews', $review_seminar->seminar->id);
    }

    /**
     * Update nilai KP via AJAX (3 komponen nilai)
     * Dipanggil dari halaman detail seminar prodi untuk input manual
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateNilai(Request $request)
    {
        try {
            // Cari seminar KP
            $seminar = \App\Models\KP\Seminar::findOrFail($request->seminar_id);
            
            // Cek apakah mahasiswa karyawan
            $is_karyawan = $request->has('is_karyawan') && $request->is_karyawan;

            // Handle bulk update or single field update
            if ($request->has(['nilai_instansi', 'nilai_pembimbing'])) {
                // Validasi berbeda untuk karyawan vs reguler
                $rules = [
                    'seminar_id' => 'required|exists:seminar_kps,id',
                    'nilai_instansi' => 'required|numeric|min:0|max:100',
                    'nilai_pembimbing' => 'required|numeric|min:0|max:100',
                ];
                
                // Nilai penguji hanya required untuk mahasiswa reguler
                if (!$is_karyawan) {
                    $rules['nilai_penguji'] = 'required|numeric|min:0|max:100';
                }
                
                $validated = $request->validate($rules);
                
                $seminar->nilai_instansi = $validated['nilai_instansi'];
                $seminar->nilai_pembimbing = $validated['nilai_pembimbing'];
                $seminar->nilai_penguji = $is_karyawan ? 0 : ($validated['nilai_penguji'] ?? 0);
            } else {
                // Validasi input single
                $validated = $request->validate([
                    'seminar_id' => 'required|exists:seminar_kps,id',
                    'field_name' => 'required|in:nilai_instansi,nilai_pembimbing,nilai_penguji',
                    'field_value' => 'required|numeric|min:0|max:100',
                ]);
                // Update field yang diminta
                $fieldName = $validated['field_name'];
                $seminar->$fieldName = $validated['field_value'];
            }
            
            // Hitung nilai akhir
            $nilaiAkhir = null;
            
            // Untuk karyawan: cukup pembimbing dan instansi
            // Untuk reguler: perlu semua komponen
            $canCalculate = $is_karyawan 
                ? ($seminar->nilai_pembimbing > 0 && $seminar->nilai_instansi > 0)
                : ($seminar->nilai_pembimbing > 0 && $seminar->nilai_penguji > 0 && $seminar->nilai_instansi > 0);
            
            if ($canCalculate) {
                // Ambil bobot dari presentase nilai KP
                $prodi = \App\Models\Prodi::where('kode', $seminar->mahasiswa->prodi)
                    ->orWhere('namaprodi', $seminar->mahasiswa->prodi)
                    ->first();
                    
                $presentaseNilai = $prodi ? \App\Models\PresentaseNilai::where('prodi_id', $prodi->id)->first() : null;
                
                if ($is_karyawan) {
                    // Karyawan: Pembimbing + Instansi
                    $bobotPembimbing = $presentaseNilai ? $presentaseNilai->bobot_pembimbing : 40;
                    $bobotInstansi = 100 - $bobotPembimbing;
                    
                    $nilaiAkhir = ($seminar->nilai_pembimbing * $bobotPembimbing / 100) + 
                                 ($seminar->nilai_instansi * $bobotInstansi / 100);
                } else {
                    // Reguler: Pembimbing + Penguji + Instansi
                    $bobotPembimbing = $presentaseNilai ? $presentaseNilai->bobot_pembimbing : 40;
                    $bobotPenguji = $presentaseNilai ? $presentaseNilai->bobot_penguji : 30;
                    $bobotInstansi = 100 - $bobotPembimbing - $bobotPenguji;
                    
                    $nilaiAkhir = ($seminar->nilai_pembimbing * $bobotPembimbing / 100) + 
                                 ($seminar->nilai_penguji * $bobotPenguji / 100) + 
                                 ($seminar->nilai_instansi * $bobotInstansi / 100);
                }
                
                $seminar->nilai_akhir = round($nilaiAkhir, 2);
                $seminar->status_nilai = 'diterima';

                // Kirim Notifikasi Email ke Mahasiswa
                if ($seminar->mahasiswa->email != '-') {
                    try {
                        $emailMessage = 'Nilai Akhir Kerja Praktek Anda telah diterbitkan.<br><br>' .
                                       'Nilai Instansi: ' . $seminar->nilai_instansi . '<br>' .
                                       'Nilai Pembimbing: ' . $seminar->nilai_pembimbing . '<br>';
                        
                        if (!$is_karyawan) {
                            $emailMessage .= 'Nilai Penguji: ' . $seminar->nilai_penguji . '<br>';
                        }
                        
                        $emailMessage .= '<br><b>Nilai Akhir: ' . $seminar->nilai_akhir . '</b><br><br>' .
                                        'Silahkan login ke sistem untuk melihat detail.';
                        
                        \App\Helpers\AppHelper::instance()->send_mail([
                            'mail' => $seminar->mahasiswa->email,
                            'subject' => 'Nilai Akhir Kerja Praktek',
                            'title' => 'EKAPTA',
                            'message' => $emailMessage,
                        ]);
                    } catch (\Exception $e) {
                         \Log::error('Gagal kirim email nilai KP: ' . $e->getMessage());
                    }
                }
            }
            
            $seminar->save();

            return response()->json([
                'success' => true,
                'message' => 'Nilai berhasil disimpan',
                'data' => [
                    'nilai_akhir' => $nilaiAkhir ? number_format($nilaiAkhir, 2) : '0.00',
                    'status' => $seminar->status_nilai ?? 'pending',
                ]
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
            
        } catch (\Exception $e) {
            \Log::error('Error updating nilai KP: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan nilai. Silakan coba lagi.',
            ], 500);
        }
    }
    public function reviewPublic($token)
    {
        $review_seminar = ReviewSeminar::where('token', $token)->firstOrFail();

        // Prevent review if already accepted (optional, but good practice)
        if ($review_seminar->status == 'diterima') {
             // You might want to show a read-only view or a message

        }

        $is_dosen_penguji_utama = $review_seminar->seminar->reviews()->where('dosen_status', ReviewSeminar::DOSEN_PENGUJI)->first();

        $form_status = false;
        if ($is_dosen_penguji_utama){
            if ($is_dosen_penguji_utama->id == $review_seminar->id){
                $form_status = true;
            }
        }

        $data = [
            'title' => 'Review Seminar KP (Public)',
            'active' => 'seminar-kp',
            'review_seminar' => $review_seminar,
            'revisis' => $review_seminar->revisis()->orderBy('created_at','desc')->paginate(5),
            'form_status' => $form_status ? 1 : 0,
        ];

        return view('kp.pages.public.review-seminar', $data);
    }

    public function storePublic(Request $request, $token)
    {
        $review_seminar = ReviewSeminar::where('token', $token)->firstOrFail();

        // Validation similar to reviewNilai but adapted
        $validatedData = $request->validate([
            'nilai_1' => 'required|numeric|min:0|max:100',
            'nilai_2' => 'required|numeric|min:0|max:100',
            'nilai_3' => 'required|numeric|min:0|max:100',
            'nilai_4' => 'required|numeric|min:0|max:100',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:diterima,revisi',
        ]);
        
        // Handling Status (Acc or Revisi)
        // If Revisi, we might need a separate revision logic or just update status+catatan
        // Simplified flow: Dosen submits grades + status.

        if ($request->status == 'revisi') {
             $revisi = new RevisiReviewSeminar();
             $revisi->catatan = $request->keterangan ?? '-';
             // Attachment logic if needed, skipping for public simplicity or add if required
             $revisi->lampiran = $review_seminar->lampiran ? $review_seminar->lampiran : $review_seminar->seminar->lampiran_3;
             
             $review_seminar->update([
                'status' => ReviewSeminar::REVISI,
             ]);
             $review_seminar->revisis()->save($revisi);
             
             return back()->with('success', 'Review (Revisi) berhasil dikirim.');

        } else {
            // DITERIMA
            $review_seminar->update([
                'nilai_1' => $request->nilai_1,
                'nilai_2' => $request->nilai_2,
                'nilai_3' => $request->nilai_3,
                'nilai_4' => $request->nilai_4,
                'status' => ReviewSeminar::DITERIMA,

            ]);
            
            // If Dosen Penguji Utama decides Lulus/Tidak (if implemented)
            if ($request->has('is_lulus')) {
                 $review_seminar->seminar->update([
                    'is_lulus' => $request->is_lulus,
                ]);
            }

            return back()->with('success', 'Nilai Seminar KP berhasil disimpan.');
        }
    }
}




