<?php

namespace App\Http\Controllers;

use App\Models\Bagian;
use App\Models\Bimbingan;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\RevisiBimbingan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\AppHelper;
use App\Models\BimbinganCanceled;
use App\Models\Dosen;
use App\Models\Pendaftaran;
use App\Models\Pengajuan;
use App\Models\SeminarCanceled;
use App\Models\Ujian;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BimbinganController extends Controller
{

    public function bimbinganProdi()
    {
        // HANYA tampilkan mahasiswa yang sudah melakukan pengajuan judul (status diterima)
        $mahasiswas = Mahasiswa::with(['bimbingans','pengajuans','pendaftarans','ujians','dosens','jilid','bimbingan_canceleds'])
            ->where('prodi', Auth::guard('prodi')->user()->namaprodi)
            ->whereHas('pengajuans', function($query) {
                $query->where('status', 'diterima');
            })
            ->with(['bimbingans'])
            ->get();

        // return view('pages.prodi.bimbingan.bimbingan', [
        return view('pages.admin.bimbingan.bimbingan', [
            'title' => 'Bimbingan Tugas Akhir',
            'active' => 'bimbingan',
            'sidebar' => 'partials.sidebarProdi',
            'reviewRoute' => 'bimbingan.review.prodi',
            'canCancelBimbingan' => false,
            'mahasiswas' => $mahasiswas,
        ]);
    }

    public function bimbinganDosen()
    {
        $dosen = Dosen::findOrFail(Auth::guard('dosen')->user()->id);

        // Filter bimbingan hanya untuk mahasiswa yang sudah pengajuan diterima
        return view('pages.dosen.bimbingan.bimbingan', [
            'title' => 'Bimbingan Tugas Akhir',
            'active' => 'bimbingan',
            'sidebar' => 'partials.sidebarDosen',
            'bimbingans' => $dosen->bimbingans()
                ->where('status', 'review')
                ->whereHas('mahasiswa.pengajuans', function($query) {
                    $query->where('status', 'diterima');
                })
                ->orderBy('tanggal_bimbingan', 'desc')
                ->get(),
            'bimbingans_diterima' => $dosen->bimbingans()
                ->where('status', 'diterima')
                ->whereHas('mahasiswa.pengajuans', function($query) {
                    $query->where('status', 'diterima');
                })
                ->orderBy('tanggal_bimbingan', 'desc')
                ->get(),
            'bimbingans_revisi' => $dosen->bimbingans()
                ->where('status', 'revisi')
                ->whereHas('mahasiswa.pengajuans', function($query) {
                    $query->where('status', 'diterima');
                })
                ->orderBy('tanggal_bimbingan', 'desc')
                ->get(),
        ]);
    }

    public function bimbinganMahasiswa()
    {
        $mahasiswa = Mahasiswa::with(['bimbingans'])->findOrFail(Auth::guard('mahasiswa')->user()->id);
        if($mahasiswa->email == '-'){
            return redirect()->route('profile');
        }
        $dosenUtama = $mahasiswa->dosens()->where('status', 'utama')->first();
        $dosenPendamping = $mahasiswa->dosens()->where('status', 'pendamping')->first();

        $pendaftaran_acc = Pendaftaran::orderBy('created_at', 'desc')->where('mahasiswa_id', Auth::guard('mahasiswa')->user()->id)->where('status', 'diterima')->first();

        if (!$pendaftaran_acc) {
            return redirect('pendaftaran-mahasiswa')->with('warning', 'Silahkan melakukan Pendaftaran Tugas Akhir terlebih dahulu');
        }

        $prodi = Prodi::where('namaprodi', Auth::guard('mahasiswa')->user()->prodi)->first();
        $bagians_is_seminar = $prodi->bagians()->where("tahun_masuk", "LIKE", "%" . $mahasiswa->thmasuk . "%")->where('is_seminar', 1)->get();
        $bagians_is_ujian = $prodi->bagians()->where("tahun_masuk", "LIKE", "%" . $mahasiswa->thmasuk . "%")->where('is_pendadaran', 1)->get();
        $bimbingans_is_acc_seminar = $mahasiswa->bimbingans()->where('status', Bimbingan::DITERIMA)
        ->whereHas('bagian', function($query) {
            $query->where('is_seminar', 1);
        })->get();
        $bimbingans_is_acc_ujian = $mahasiswa->bimbingans()->where('status', Bimbingan::DITERIMA)
        ->whereHas('bagian', function($query) {
            $query->where('is_pendadaran', 1);
        })->get();

        $is_seminar = null;
        if (count($bimbingans_is_acc_seminar) >= count($bagians_is_seminar) * 2) {
            $is_seminar = true;
        }

        $is_ujian = null;
        if (count($bimbingans_is_acc_ujian) >= count($bagians_is_ujian) * 2) {
            $is_ujian = true;
        }

        // $ujian_has_complete = $mahasiswa->ujians()->where('is_lulus', Ujian::VALID_LULUS)->first();
        // $reviews_has_acc = 0;
        // if($ujian_has_complete){
        //     $reviews_has_acc = count($ujian_has_complete->reviews()->where('status', 'diterima')->where('dosen_status', Dosen::PENGUJI)->get());
        // }

        // $bimbingans_acc = $mahasiswa->bimbingans()->where('status', Bimbingan::DITERIMA)->get();
        // $prodi = Prodi::where('namaprodi', $mahasiswa->prodi)->first();
        // return count($bimbingans_acc).'/'.count($prodi->bagians()->where("tahun_masuk", "LIKE", "%" . $mahasiswa->thmasuk . "%")->get());

        return view('pages.mahasiswa.bimbingan.bimbingan', [
            'title' => 'Bimbingan Tugas Akhir',
            'active' => 'bimbingan',
            'bimbingans_utama' => $mahasiswa->bimbingans()->where('pembimbing', 'utama')->get(),
            'bimbingans_pendamping' => $mahasiswa->bimbingans()->where('pembimbing', 'pendamping')->get(),
            'dosen_utama' => $dosenUtama,
            'dosen_pendamping' => $dosenPendamping,
            'date_expired' => AppHelper::getBimbinganExpiredDateFromPendaftaran($pendaftaran_acc),
            'is_seminar' => $is_seminar,
            'is_ujian' => $is_ujian,
            'is_expired' => AppHelper::isBimbinganExpiredFromPendaftaran($pendaftaran_acc),
            'pendaftaran_acc' => $pendaftaran_acc,
            'mahasiswa' => $mahasiswa,
            'jilid' => $mahasiswa->jilid,
            // 'reviews_has_acc' => $reviews_has_acc == 3 ? true : false,
            'check_ujian_has_done' => AppHelper::check_ujian_has_done(),
        ]);
    }

    public function create()
    {
        return back();
        $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);
        $prodi = Prodi::where('namaprodi', $mahasiswa->prodi)->first();
        return view('pages.mahasiswa.bimbingan.create', [
            'title' => 'Form Bimbingan Tugas Akhir',
            'active' => 'bimbingan',
            'bagians' => $prodi->bagians,
        ]);
    }

    public function store(Request $request)
    {
        return back();
        $cekBimbingan = Bimbingan::where('mahasiswa_id', Auth::guard('mahasiswa')->user()->id)
            ->whereIn('status', ['review', 'revisi'])
            ->get();
        $bimbinganIfExists = Bimbingan::where(['mahasiswa_id' => Auth::guard('mahasiswa')->user()->id, 'bagian_id' => $request->bagian_id, 'status' => 'diterima'])
            ->get();
        Bagian::findOrFail($request->bagian_id);
        $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);
        $dosenUtama = $mahasiswa->dosens()->where('status', 'utama')->first();
        $dosenPendamping = $mahasiswa->dosens()->where('status', 'pendamping')->first();
        if ($cekBimbingan->isEmpty()) {
            if ($bimbinganIfExists->isEmpty()) {
                $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);
                $request->validate([
                    'lampiran' => ['required', 'mimes:pdf', 'max:5000'],
                    'bagian_id' => 'required',
                ]);
                $bimbingan = new Bimbingan;
                $bimbingan->lampiran = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampirans');
                $bimbingan->keterangan = $request->keterangan;
                $bimbingan->bagian_id = $request->bagian_id;

                $mahasiswa->bimbingans()->save($bimbingan);

                $bimbingan->dosens()->attach([$dosenUtama->id, $dosenPendamping->id]);

                return redirect('bimbingan-mahasiswa')->with('success', 'Bimbingan berhasil dibuat. Silahkan tunggu review dari dosen pembiming');
            } else {
                return back()->with('warning', 'Bimbingan dengan bagian yang sama sudah di Acc');
            }
        } else {
            return redirect('bimbingan-mahasiswa')->with('warning', 'Bimbingan dengan bagian yang sama sudah dibuat. Silahkan tunggu review dari dosen pembimbing');
        }
    }

    public function edit($id)
    {
        $mahasiswa = Mahasiswa::with(['bimbingans'])->findOrFail(Auth::guard('mahasiswa')->user()->id);
        $prodi = Prodi::where('namaprodi', $mahasiswa->prodi)->first();
        $pendaftaran = Pendaftaran::orderBy('created_at', 'desc')->where('mahasiswa_id', $mahasiswa->id)->where('status', 'diterima')->first();
        $prodi = Prodi::where('namaprodi', Auth::guard('mahasiswa')->user()->prodi)->first();
        $bagians_is_seminar = $prodi->bagians()->where("tahun_masuk", "LIKE", "%" . $mahasiswa->thmasuk . "%")->where('is_seminar', 1)->get();
        $bimbingans_is_acc_seminar = $mahasiswa->bimbingans()->where('status', Bimbingan::DITERIMA)
        ->whereHas('bagian', function($query) {
            $query->where('is_seminar', 1);
        })->get();

        if($pendaftaran){
            if (AppHelper::isBimbinganExpiredFromPendaftaran($pendaftaran)) {
                return redirect('bimbingan-mahasiswa')->with('warning', 'Masa aktif bimbingan anda sudah berakhir, silahkan lakukan pendaftaran ulang');
            }
        }

        $bimbingan = Bimbingan::with(['dosens'])->findOrFail($id);

        if ($mahasiswa->id != $bimbingan->mahasiswa->id) {
            abort(404);
        } elseif ($bimbingan->status == 'review' || $bimbingan->status == 'ditolak' ||    $bimbingan->status == 'diterima') {
            return back()->with('warning', 'Bimbingan tidak dapat disubmit');
        } elseif (count($mahasiswa->bimbingans()->where('status', 'review')->get()) >= 2) {
            return back()->with('warning', 'Tunggu sampai bimbingan di Acc oleh dosen');
        } elseif(count($bimbingans_is_acc_seminar) / 2  < count($bagians_is_seminar) && $bimbingan->bagian->is_pendadaran == 1){
            return back();
        }

        return view('pages.mahasiswa.bimbingan.edit', [
            'title' => 'Form Submit Bimbingan Tugas Akhir',
            'bimbingan' => $bimbingan,
            'active' => 'bimbingan',
            'bagians' => $prodi->bagians,
            'dosen' => $bimbingan->dosens()->first(),
        ]);
    }

    public function bimbinganDetail($id)
    {
        $bimbingan = Bimbingan::with(['revisis','mahasiswa'])->findOrFail($id);
        if ($bimbingan->mahasiswa->id != Auth::guard('mahasiswa')->user()->id) {
            return back()->with('warning', 'Bimbingan tidak ditemukan');
        }
        if ($bimbingan->status == null) {
            return back()->with('warning', 'Harap edit Bimbingan terlebih dahulu');
        }
        return view('pages.mahasiswa.bimbingan.detail', [
            'title' => 'Detail Bimbingan Tugas Akhir',
            'bimbingan' => $bimbingan,
            'active' => 'bimbingan',
            'revisis' => $bimbingan->revisis()->orderBy('created_at', 'desc')->paginate(5),
        ]);
    }

    public function bimbinganReview($id)
    {
        $bimbingan = Bimbingan::with(['revisis'])->findOrFail($id);
        $mahasiswa = Mahasiswa::with(['bimbingans','pengajuans'])->find($bimbingan->mahasiswa->id);
        $prodi = Prodi::with(['bagians'])->where('namaprodi', $mahasiswa->prodi)->first();
        $pengajuan = $mahasiswa->pengajuans()->where('status', 'diterima')->first();

        $bimbingans_acc = $mahasiswa->bimbingans()->where('status', 'diterima')->get();

        return view('pages.dosen.bimbingan.review', [
            'title' => 'Review Bimbingan Tugas Akhir',
            'bimbingan' => $bimbingan,
            'active' => 'bimbingan',
            'sidebar' => 'partials.sidebarDosen',
            'revisis' => $bimbingan->revisis()->orderBy('created_at', 'desc')->paginate(5),
            'bagians' => $prodi->bagians,
            'bimbingans_acc' => $bimbingans_acc,
            'mahasiswa' => $mahasiswa,
            'pengajuan' => $pengajuan,
        ]);
    }

    public function update(Request $request)
    {
        $bimbingan = Bimbingan::findOrFail($request->id);
        $cekBimbingan = Bimbingan::where('mahasiswa_id', Auth::guard('mahasiswa')->user()->id)->where('status', 'review')->get();

        $pendaftaran = Pendaftaran::orderBy('created_at', 'desc')->where('mahasiswa_id', Auth::guard('mahasiswa')->user()->id)->where('status', 'diterima')->first();

        if (AppHelper::isBimbinganExpiredFromPendaftaran($pendaftaran)) {
            return redirect('bimbingan-mahasiswa')->with('warning', 'Masa aktif bimbingan anda sudah berakhir, silahkan lakukan pendaftaran ulang');
        }

        if (count($cekBimbingan) >= 2) {
            return redirect('bimbingan-mahasiswa')->with('warning', 'Harap menunggu Acc bimbingan dari dosen Pembimbing');
        } else {
            if ($bimbingan->status == 'diterima' || $bimbingan->status == 'review') {
                return redirect('bimbingan-mahasiswa')->with('warning', 'Bimbingan tidak bisa diedit');
            }
            $validatedData = $request->validate([
                'lampiran' => ['required', 'mimes:pdf', 'max:5000'],
            ]);
            if ($request->file('lampiran')) {
                //AppHelper::instance()->deleteLampiran($bimbingan->lampiran);
                $validatedData['lampiran'] = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampirans');
            }
            $validatedData['keterangan'] = $request->keterangan;
            if ($bimbingan->status == null) {
                $validatedData['tanggal_bimbingan'] = now();
            }
            $validatedData['status'] = 'review';
            $validatedData['tanggal_bimbingan'] = Carbon::now();
            // return $validatedData;
            $bimbingan->update($validatedData);
            return redirect('bimbingan-mahasiswa')->with('success', 'Bimbingan berhasil diupdate. Silahkan tunggu review dari dosen pembimbing');
        }
    }

    public function delete(Request $request)
    {
        return back();
        $bimbingan = Bimbingan::findOrFail($request->id);
        if (count($bimbingan->revisis) != 0) {
            return back()->with('warning', 'Bimbingan tidak bisa dihapus');
        }
        AppHelper::instance()->deleteLampiran($bimbingan->lampiran);

        DB::table('dosen_bimbingans')->where('bimbingan_id', $bimbingan->id)->delete();

        $bimbingan->delete();
        return back()->with('success', 'Bimbingan berhasil dihapus');
    }

    public function accBimbingan(Request $request)
    {
        $bimbingan = Bimbingan::findOrFail($request->id);
        if ($bimbingan->status == 'diterima') {
            return redirect('bimbingan-dosen')->with('warning', 'Bimbingan sudah di Acc');
        }
        $revisi = new RevisiBimbingan;
        $request->validate([
            'catatan' => 'required',
            'lampiran' => [Rule::requiredIf(function () use($request) {
                if (empty($request->lampiran)) {
                    return false;
                }
                return true;
            }), 'mimes:pdf,docx', 'max:5000']
        ]);
        $revisi->catatan = $request->catatan;
        //$revisi->lampiran = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampirans');
        $revisi->lampiran = $bimbingan->lampiran;
        $revisi->dosen_id = Auth::guard('dosen')->user()->id;
        $revisi->tanggal_bimbingan = $bimbingan->tanggal_bimbingan;
        $bimbingan->update([
            'status' => 'diterima',
            'tanggal_acc' => now(),
        ]);
        $bimbingan->revisis()->save($revisi);
        if ($bimbingan->mahasiswa->email != '-') {
            AppHelper::instance()->send_mail([
                'mail' => $bimbingan->mahasiswa->email,
                'subject' => 'Bimbingan Tugas Ahir',
                'title' => 'EKAPTA',
                'message' => 'Selamat Bimbingan Tugas Akhir Anda <b>'.$bimbingan->bagian->bagian.'</b> Berstatus DITERIMA. Silahkan lanjutkan ke bab berikutnya. Catatan: '.$request->catatan,
            ]);
        }
        return redirect('bimbingan-dosen')->with('success', 'Bimbingan berhasil di Acc');
    }

    public function revisiBimbingan(Request $request)
    {
        $bimbingan = Bimbingan::findOrFail($request->id);
        if ($bimbingan->status == 'diterima' || $bimbingan->status == 'revisi') {
            return redirect('bimbingan-dosen')->with('warning', 'Bimbingan tidak bisa direvisi');
        }

        $revisi = new RevisiBimbingan;
        $request->validate([
            'catatan' => 'required',
            'lampiran' => [Rule::requiredIf(function () use($request) {
                if (empty($request->lampiran)) {
                    return false;
                }
                return true;
            }), 'mimes:pdf,docx', 'max:5000']
        ]);

        if($request->lampiran){
            $revisi->lampiran_revisi = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampirans');
        }
        $revisi->catatan = $request->catatan;
        $revisi->lampiran = $bimbingan->lampiran;
        $revisi->tanggal_bimbingan = $bimbingan->tanggal_bimbingan;
        $revisi->dosen_id = Auth::guard('dosen')->user()->id;
        // return $revisi;
        $bimbingan->revisis()->save($revisi);
        $bimbingan->update([
            'status' => 'revisi',
        ]);
        if ($bimbingan->mahasiswa->email != '-') {
            AppHelper::instance()->send_mail([
                'mail' => $bimbingan->mahasiswa->email,
                'subject' => 'Bimbingan Tugas Ahir',
                'title' => 'EKAPTA',
                'message' => 'From: <b>'.Auth::guard('dosen')->user()->nama.', '.Auth::guard('dosen')->user()->gelar.'</b><br>Bimbingan Tugas Akhir Anda <b>'.$bimbingan->bagian->bagian.'</b> Berstatus REVISI. Silahkan perbaiki kemudian submit ulang. <br><br> Catatan revisi : '. $request->catatan,
            ]);
        }
        return redirect('bimbingan-dosen')->with('success', 'Bimbingan berhasil direvisi');
    }

    public function deleteRevisiBimbingan(Request $request)
    {
        $revisi = RevisiBimbingan::findOrFail($request->id);
        AppHelper::instance()->deleteLampiran($revisi->lampiran);
        $revisi->delete();
        return back()->with('success', 'Revisi berhasil dihapus');
    }

    public function cancelAcc(Request $request)
    {
        $bimbingan = Bimbingan::findOrFail($request->id);
        $bimbingan->update([
            'status' => 'review',
            'tanggal_acc' => null,
        ]);
        return back()->with('success', 'Acc bimbingan berhasil dibatalkan');
    }

    public function cancelRevisi(Request $request)
    {
        $bimbingan = Bimbingan::findOrFail($request->id);
        $bimbingan->update([
            'status' => 'review',
        ]);
        return back()->with('success', 'Revisi bimbingan berhasil dibatalkan');
    }

    public function reviewProdi($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        $dosen_utama = $pengajuan->mahasiswa->dosens()->where('status', 'utama')->first();
        $dosen_pendamping = $pengajuan->mahasiswa->dosens()->where('status', 'pendamping')->first();

        $data = [
            'title' => 'Detail Bimbingan Tugas Akhir',
            'active' => 'bimbingan',
            'sidebar' => 'partials.sidebarProdi',
            'pengajuan' => $pengajuan,
            'dosen_utama' => $dosen_utama,
            'dosen_pendamping' => $dosen_pendamping,
            'mahasiswa' => $pengajuan->mahasiswa,
        ];

        return view('pages.prodi.bimbingan.detail', $data);
    }

    public function bimbinganAdmin()
    {
        // $mahasiswas = Mahasiswa::with(['bimbingans','pendaftarans','dosens','ujian','seminar'])->where('id', 785)->first();
        $mahasiswas = Mahasiswa::with(['bimbingans', 'pendaftarans', 'dosens', 'ujians', 'seminar','pengajuans', 'jilid', 'bimbingan_canceleds'])->get();
        // return $mahasiswas->seminar;
        // return $mahasiswas->seminar->reviews;
        // return $mahasiswas->pendaftarans()->where('status','diterima')->first();
        return view('pages.admin.bimbingan.bimbingan', [
            'title' => 'Laporan Bimbingan Tugas Akhir',
            'active' => 'bimbingan',
            'sidebar' => 'partials.sidebarAdmin',
            'reviewRoute' => 'bimbingan.review.admin',
            'canCancelBimbingan' => true,
            'mahasiswas' => $mahasiswas,
        ]);
    }

    public function reviewAdmin($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        $dosen_utama = $pengajuan->mahasiswa->dosens()->where('status', 'utama')->first();
        $dosen_pendamping = $pengajuan->mahasiswa->dosens()->where('status', 'pendamping')->first();

        $data = [
            'title' => 'Detail Bimbingan Tugas Akhir',
            'active' => 'bimbingan',
            'sidebar' => 'partials.sidebarAdmin',
            'pengajuan' => $pengajuan,
            'dosen_utama' => $dosen_utama,
            'dosen_pendamping' => $dosen_pendamping,
            'mahasiswa' => $pengajuan->mahasiswa,
        ];

        return view('pages.admin.bimbingan.detail', $data);
    }

    public function bimbinganDosenProgress()
    {
        $dosen = Dosen::findOrFail(Auth::guard('dosen')->user()->id);
        // return view('pages.dosen.bimbingan.bimbingan-progress', [
        return view('pages.admin.bimbingan.bimbingan', [
            'title' => 'Bimbingan Tugas Akhir',
            'active' => 'bimbingan-progress',
            'sidebar' => 'partials.sidebarDosen',
            'reviewRoute' => null,
            'canCancelBimbingan' => false,
            'mahasiswas' => $dosen->mahasiswas()->with(['bimbingans'])->get(),
        ]);
    }

    public function rekapDosen()
    {
        $prodi =  Prodi::with(['dosens'])->findOrFail(Auth::guard('prodi')->user()->id);
        $dosens = $prodi->dosens()->with(['mahasiswas'])->get();

        return view('pages.prodi.bimbingan.rekap-dosen', [
            'title' => 'Rekap Bimbingan Dosen',
            'sidebar' => 'partials.sidebarProdi',
            'active' => 'dashboard',
            'dosens' => $dosens,
        ]);
    }

    public function bimbinganAdminInput()
    {
        $isProdiContext = request()->routeIs('bimbingan.prodi.*')
            || (Auth::guard('prodi')->check() && !Auth::guard('admin')->check());

        if ($isProdiContext) {
            $prodi = Prodi::with(['dosens'])->findOrFail(Auth::guard('prodi')->user()->id);
            $dosens = $prodi->dosens()->with(['mahasiswas'])->where('is_manual', 1)->get();
            $sidebar = 'partials.sidebarProdi';
            $createRoute = 'bimbingan.prodi.input.create';
        } else {
            $dosens = Dosen::with(['mahasiswas'])->where('is_manual', 1)->get();
            $sidebar = 'partials.sidebarAdmin';
            $createRoute = 'bimbingan.admin.input.create';
        }

        return view('pages.admin.bimbingan.bimbingan-input', [
            'title' => 'Bimbingan Dosen',
            'sidebar' => $sidebar,
            'active' => 'bimbingan-input',
            'dosens' => $dosens,
            'createRoute' => $createRoute,
        ]);
    }

    public function bimbinganAdminInputCreate($dosen_id, $mahasiswa_id)
    {
        $referer = request()->headers->get('referer');
        $isProdiContext = request()->routeIs('bimbingan.prodi.*')
            || ($referer && str_contains($referer, 'input-prodi'))
            || (Auth::guard('prodi')->check() && !Auth::guard('admin')->check());

        if ($isProdiContext) {
            $sidebar = 'partials.sidebarProdi';
            $route = 'bimbingan.prodi.input';
            $storeRoute = 'bimbingan.prodi.input.store';
        } else {
            $sidebar = 'partials.sidebarAdmin';
            $route = 'bimbingan.admin.input';
            $storeRoute = 'bimbingan.admin.input.store';
        }
        $dosen = Dosen::findOrFail($dosen_id);
        $mahasiswa = Mahasiswa::findOrFail($mahasiswa_id);
        $bimbingans = $dosen->bimbingans()->where('mahasiswa_id', $mahasiswa->id)->get();
        return view('pages.admin.bimbingan.bimbingan-store', [
            'title' => 'Input Manual Bimbingan Dosen',
            'sidebar' => $sidebar,
            'active' => 'bimbingan-input',
            'dosen' => $dosen,
            'mahasiswa' => $mahasiswa,
            'bimbingans' => $bimbingans,
            'dosen_mahasiswa' => DB::table('dosen_mahasiswas')->where('mahasiswa_id', $mahasiswa->id)->where('dosen_id', $dosen->id)->first(),
            'route' => $route,
            'storeRoute' => $storeRoute,
        ]);
    }

    public function bimbinganAdminInputStore(Request $request)
    {
        $request->validate([
            'lampiran' => [Rule::requiredIf(function() use($request) {
                if (empty($request->lampiran)) {
                    return false;
                }
                return true;
            }) ,'mimes:pdf', 'max:5000'],
        ]);
        if($request->lampiran){
            $lampiran = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampirans');
            DB::table('dosen_mahasiswas')->where('mahasiswa_id', $request->mahasiswa_id)->where('dosen_id', $request->dosen_id)->update(['lampiran' => $lampiran]);
        }
        $dates = $request->dates;
        $ids = $request->ids;
        for ($i = 0; $i < count($request->dates); $i++) {
            if ($dates[$i] != null) {
                $bimbingan = Bimbingan::findOrFail($ids[$i]);
                $bimbingan->update([
                    "status" => "diterima",
                    "tanggal_acc" => $dates[$i],
                ]);
                if ($bimbingan->mahasiswa->email != '-') {
                    AppHelper::instance()->send_mail([
                        'mail' => $bimbingan->mahasiswa->email,
                        'subject' => 'Bimbingan Tugas Ahir',
                        'title' => 'EKAPTA',
                        'message' => 'Selamat Bimbingan Tugas Akhir Anda <b>'.$bimbingan->bagian->bagian.'</b> Berstatus DITERIMA. Silahkan lanjutkan ke bab berikutnya.',
                    ]);
                }
            }
        }
        return back()->with('success', 'Berhasil disimpan');
    }

    public function public($id)
    {
        $mahasiswa = Mahasiswa::with(['bimbingans'])->where('id', base64_decode($id))->first();
        if (!$mahasiswa) {
            abort(404);
        }
        $pengajuan = $mahasiswa->pengajuans()->where('status', Pengajuan::DITERIMA)->first();
        $prodi = Prodi::where('namaprodi', $mahasiswa->prodi)->first();
        $dosen_utama = $mahasiswa->dosens()->where('status', 'utama')->first();
        $dosen_pendamping = $mahasiswa->dosens()->where('status', 'pendamping')->first();
        $pendaftaran_acc = Pendaftaran::orderBy('created_at', 'desc')->where('mahasiswa_id', $mahasiswa->id)->where('status', 'diterima')->first();
        return view('pages.public.detail', [
            'title' => 'Riwayat Bimbingan Mahasiswa',
            'mahasiswa' => $mahasiswa,
            'prodi' => $prodi,
            'dosen_utama' => $dosen_utama ? $dosen_utama : null,
            'dosen_pendamping' => $dosen_pendamping ? $dosen_pendamping : null,
            'date_expired' => AppHelper::getBimbinganExpiredDateFromPendaftaran($pendaftaran_acc),
            'is_expired' => AppHelper::isBimbinganExpiredFromPendaftaran($pendaftaran_acc),
            'pengajuan' => $pengajuan ? $pengajuan : null,
            'pendaftaran' => $pendaftaran_acc ? $pendaftaran_acc : null,
        ]);
    }

    public function bimbinganCanceled($id){
        //Comment jika ingin aktifkan pembatalan bimbingan, un-comment jika ingin non-aktifkan pembatalan bimbingan
        return back()->with('warning', 'Mohon maaf, saat ini fitur sedang di non-aktifkan');

        $mahasiswa = Mahasiswa::with(['bimbingans','pengajuans','pendaftarans','seminar','seminar.reviews','seminar.revisis'])->findOrFail($id);

        if($mahasiswa->seminar){
            // return back()->with('warning', 'Tidak bisa dibatalkan. Mahasiswa sudah terdaftar seminar.');
            $seminar = $mahasiswa->seminar;

            foreach ($seminar->revisis as $revisi) {
                $revisi->delete();
            }

            foreach ($seminar->reviews as $review) {
               $review->delete();
            }

            SeminarCanceled::create([
                'pengajuan_id' => $seminar->pengajuan_id,
                'mahasiswa_id' => $seminar->mahasiswa_id,
                'lampiran_1' => $seminar->lampiran_1,
                'lampiran_2' => $seminar->lampiran_2,
                'lampiran_3' => $seminar->lampiran_3,
                'jumlah_bayar' => $seminar->jumlah_bayar,
                'nomor_pembayaran' => $seminar->nomor_pembayaran,
                'lampiran_proposal' => $seminar->lampiran_proposal,
                'is_valid' => $seminar->is_valid,
                'is_lulus' => $seminar->is_lulus,
                'tanggal_acc' => $seminar->tanggal_acc,
                'tanggal_ujian' => $seminar->tanggal_ujian,
                'tempat_ujian' => $seminar->tempat_ujian,
            ]);

            $seminar->delete();
        }

        $pengajuan = $mahasiswa->pengajuans()->where('status', Pengajuan::DITERIMA)->first();
        $pengajuan->update(['status' => 'dibatalkan']);
        $pendaftaran = $mahasiswa->pendaftarans()->where('status', Pendaftaran::DITERIMA)->first();
        $pendaftaran->update(['status' => 'dibatalkan']);

        foreach($mahasiswa->bimbingans as $bimbingan){
            $dosen = $bimbingan->dosens()->first();
            BimbinganCanceled::create([
                'judul' => $pengajuan->judul,
                'keterangan' => $bimbingan->keterangan,
                'lampiran' => $bimbingan->lampiran,
                'status' => $bimbingan->status,
                'mahasiswa_id' => $bimbingan->mahasiswa_id,
                'bagian_id' => $bimbingan->bagian_id,
                'tanggal_bimbingan' => $bimbingan->tanggal_bimbingan,
                'tanggal_acc' => $bimbingan->tanggal_acc,
                'pembimbing' => $bimbingan->pembimbing,
                'dosen_id' => $dosen->id,
                'pengajuan_id' => $pengajuan->id,
            ]);
            DB::table('dosen_bimbingans')->where('dosen_id', $dosen->id)->where('bimbingan_id', $bimbingan->id)->delete();
            DB::table('dosen_mahasiswas')->where('dosen_id', $dosen->id)->where('mahasiswa_id', $mahasiswa->id)->delete();
        }

        foreach($mahasiswa->bimbingans as $bimbingan){
           $bimbingan->delete();
        }

        return back()->with('success', 'Bimbingan berhasil dibatalkan');
    }

     public function submitAccManual($id)
    {
        $bimbingan = Bimbingan::with(['dosens'])->findOrFail($id);

        if ($bimbingan->status == Bimbingan::REVIEW && $bimbingan->dosens()->first()->is_manual == 1) {
            return view('pages.mahasiswa.bimbingan.submit-acc-manual', [
                'title' => 'Form Submit Acc Manual Bimbingan',
                'bimbingan' => $bimbingan,
                'active' => 'bimbingan',
                'dosen' => $bimbingan->dosens()->first(),
            ]);
        }

        return back();
    }

    public function submitAccManualStore(Request $request)
    {
        $bimbingan = Bimbingan::findOrFail($request->id);

        if ($bimbingan->status == Bimbingan::REVIEW) {
            $validatedData = $request->validate([
                'lampiran_acc' => ['required', 'mimes:png,jpg,jpeg,pdf', 'max:5000'],
                'tanggal_manual_acc' => ['required'],
            ]);

            $validatedData['lampiran_acc'] = AppHelper::instance()->uploadLampiran($request->lampiran_acc, 'lampirans');
            $bimbingan->update($validatedData);

            return back()->with('success', 'Input acc bimbingan manual berhasil. Silahkan tunggu validasi dari prodi.');
        }

        return redirect()->route('bimbingan.mahasiswa');
    }

    public function bimbinganAccManual($id){
        $bimbingan = Bimbingan::with(['mahasiswa', 'bagian'])->findOrFail($id);

        if ($bimbingan->status == Bimbingan::REVIEW && $bimbingan->lampiran_acc){
            $bimbingan->update([
                'tanggal_acc' => $bimbingan->tanggal_manual_acc,
                'status' => Bimbingan::DITERIMA,
            ]);

            if ($bimbingan->mahasiswa->email != '-') {
                AppHelper::instance()->send_mail([
                    'mail' => $bimbingan->mahasiswa->email,
                    'subject' => 'Bimbingan Tugas Ahir',
                    'title' => 'EKAPTA',
                    'message' => 'Selamat Bimbingan Tugas Akhir Anda <b>' . $bimbingan->bagian->bagian . '</b> Berstatus DITERIMA. Silahkan lanjutkan ke bab berikutnya.',
                ]);
            }

            return back()->with('success', 'Bimbingan berhasil di acc');
        }

        return back();
    }

     public function bimbinganRejectManual(Request $request, $id){
        $bimbingan = Bimbingan::with(['mahasiswa', 'bagian'])->findOrFail($id);

        if ($bimbingan->status == Bimbingan::REVIEW && $bimbingan->lampiran_acc){
            $bimbingan->update([
                'lampiran_acc' => null,
                'tanggal_manual_acc' => null,
                'catatan' => $request->catatan,
            ]);

            if ($bimbingan->mahasiswa->email != '-') {
                AppHelper::instance()->send_mail([
                    'mail' => $bimbingan->mahasiswa->email,
                    'subject' => 'Bimbingan Tugas Ahir',
                    'title' => 'EKAPTA',
                    'message' => 'Input Manual Bimbingan Tugas Akhir Anda <b>' . $bimbingan->bagian->bagian . '</b> Berstatus DITOLAK. </br><b>Catatan: </b>'. $request->catatan,
                ]);
            }

            return back()->with('success', 'Bimbingan berhasil di tolak');
        }

        return back();
    }

}



