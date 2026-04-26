<?php

namespace App\Http\Controllers\KP;

use App\Helpers\AppHelper;
use App\Helpers\StorageHelper;

// Import KP Models
use App\Models\KP\Jilid;
use App\Models\KP\Pendaftaran;
use App\Models\KP\RevisiJilid;

// Import Shared Models
use App\Models\Admin;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Prodi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PengumpulanAkhirController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        // Import TA Jilid Model
        $JilidTA = \App\Models\Jilid::class;
        
        // Ambil data KP
        if (Auth::guard('admin')->user()->type == Admin::TYPE_SUPER_ADMIN) {
            $jilids_kp = Jilid::where('status', '!=', Jilid::JILID_DRAFT)
                ->orderBy('created_at','desc')
                ->get();
        } else {
            $jilids_kp = Jilid::where('status', '!=', Jilid::JILID_DRAFT)
                ->whereIn('status', [Jilid::JILID_VALID, Jilid::JILID_SELESAI])
                ->orderBy('created_at','desc')
                ->get();
        }
        
        // Ambil data TA
        if (Auth::guard('admin')->user()->type == Admin::TYPE_SUPER_ADMIN) {
            $jilids_ta = $JilidTA::orderBy('created_at','desc')->get();
        } else {
            $jilids_ta = $JilidTA::orderBy('created_at','desc')
                ->whereIn('status', ['terkumpul', 'selesai'])
                ->get();
        }

        return view('kp.pages.pengumpulan-akhir.admin-index', [
            'title' => 'Jilid KP',
            'active' => 'pengumpulan-akhir-kp',
            'sidebar' => Auth::guard('admin')->user()->type == Admin::TYPE_SUPER_ADMIN ? 'partials.sidebarAdmin' : null,
            'jilids' => $jilids_kp, // Backward compatibility
            'jilids_kp' => $jilids_kp,
            'jilids_ta' => $jilids_ta,
        ]);
    }

    /**
     * Halaman index pengumpulan akhir untuk mahasiswa
     * Syarat: Seminar KP sudah selesai
     */
    public function mahasiswaIndex()
    {
        $mahasiswa = Mahasiswa::with(['jilidKP', 'seminarKP'])->findOrFail(Auth::guard('mahasiswa')->user()->id);

        // Cek tahapan: Seminar harus selesai dulu
        if (!AppHelper::canAccessPengumpulanAkhir($mahasiswa)) {
            return redirect()->route('kp.seminar.mahasiswa')->with('warning', 'Selesaikan tahap Seminar KP terlebih dahulu. Anda harus lulus seminar untuk mengakses Pengumpulan Akhir.');
        }

        // Jika ada jilid draft (hanya nilai dari dosen), arahkan ke create untuk upload dokumen
        if ($mahasiswa->jilidKP && $mahasiswa->jilidKP->isDraft()) {
            return redirect()->route('kp.pengumpulan-akhir.create')->with('info', 'Dosen pembimbing sudah memberikan nilai. Silahkan upload dokumen Jilid KP.');
        }

        return view('kp.pages.pengumpulan-akhir.index', [
            'title' => 'Pengumpulan Akhir KP',
            'active' => 'pengumpulan-akhir-kp',
            'jilids' => $mahasiswa->jilidKP ? [$mahasiswa->jilidKP] : [],
            'jilid' => $mahasiswa->jilidKP,
        ]);
    }

    /**
     * Form Pengumpulan Akhir KP
     * Syarat: Seminar KP sudah selesai (is_lulus = 1)
     */
    public function create()
    {
        $mahasiswa = Mahasiswa::with(['seminarKP','pendaftaransKP','jilidKP'])->findOrFail(Auth::guard('mahasiswa')->user()->id);
        $pendaftaran = $mahasiswa->pendaftaransKP()->where('status', Pendaftaran::DITERIMA)->first();

        // Cek tahapan: Seminar harus selesai dulu
        if (!AppHelper::canAccessPengumpulanAkhir($mahasiswa)) {
            return redirect()->route('kp.seminar.mahasiswa')->with('warning', 'Selesaikan tahap Seminar KP terlebih dahulu. Anda harus lulus seminar untuk mengakses Pengumpulan Akhir.');
        }

        // Jika sudah ada jilid dan bukan draft, redirect
        if ($mahasiswa->jilidKP && !$mahasiswa->jilidKP->isDraft()) {
            return redirect()->route('kp.pengumpulan-akhir.mahasiswa')->with('warning','Sudah melakukan pengajuan pengumpulan akhir KP. Tunggu validasi oleh Admin');
        }

        // Hitung Waktu Pelaksanaan
        // Mulai: Saat Pendaftaran KP di-ACC
        // Selesai: Saat Bimbingan Terakhir di-ACC
        $latestBimbingan = $mahasiswa->bimbingansKP()
            ->where('status', \App\Models\KP\Bimbingan::DITERIMA)
            ->latest('tanggal_acc')
            ->first();

        $dateStart = $pendaftaran && $pendaftaran->tanggal_acc ? \Carbon\Carbon::parse($pendaftaran->tanggal_acc) : null;
        $dateEnd = $latestBimbingan && $latestBimbingan->tanggal_acc ? \Carbon\Carbon::parse($latestBimbingan->tanggal_acc) : null;

        $waktu_pelaksanaan = '-';
        if ($dateStart && $dateEnd) {
             // Format: 1 Januari 2023 s/d 1 Februari 2023
             $waktu_pelaksanaan = AppHelper::parse_date_short_surat($dateStart) . ' s/d ' . AppHelper::parse_date_short_surat($dateEnd);
        } elseif ($dateStart) {
             $waktu_pelaksanaan = AppHelper::parse_date_short_surat($dateStart) . ' s/d -';
        }

        return view('kp.pages.pengumpulan-akhir.create', [
            'title' => 'Submit Pengumpulan Akhir KP',
            'active' => 'pengumpulan-akhir-kp',
            'mahasiswa' => $mahasiswa,
            'pendaftaran' => $pendaftaran,
            'waktu_pelaksanaan' => $waktu_pelaksanaan,
            'jilid' => $mahasiswa->jilidKP, // Pass jilid draft jika ada
        ]);
    }

    public function store(Request $request)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        
        // Cek apakah ada jilid draft (hanya nilai dari dosen)
        $jilidDraft = $mahasiswa->jilidKP && $mahasiswa->jilidKP->isDraft() ? $mahasiswa->jilidKP : null;
        
        // Jika sudah ada jilid yang bukan draft, redirect
        if ($mahasiswa->jilidKP && !$mahasiswa->jilidKP->isDraft()) {
            return back();
        }
        
        // Log request untuk debug
        \Log::info('Jilid KP store attempt', [
            'mahasiswa_id' => $mahasiswa->id,
            'has_files' => $request->hasFile('lembar_pengesahan'),
            'all_files' => array_keys($request->allFiles()),
        ]);
        
        try {
            $validatedData = $request->validate([
                // lokasi_kp dan waktu_pelaksanaan_kp tidak disimpan ke database (hanya untuk display)
                'laporan_pdf' => [Rule::requiredIf(function() use($request){
                    return !empty($request->laporan_pdf);
                }), 'mimes:pdf', 'max:10000'],
                'laporan_word' => [Rule::requiredIf(function() use($request){
                    return !empty($request->laporan_word);
                }), 'mimes:docx', 'max:10000'],
                'lembar_pengesahan' => ['required', 'mimes:pdf', 'max:5000'], // 5MB
                'lembar_bimbingan' => ['required', 'mimes:pdf', 'max:5000'], // 5MB
                'lembar_revisi' => ['required', 'mimes:pdf', 'max:5000'], // 5MB
                'file_project' => ['required', 'mimes:zip,rar', 'max:30720'], // 30MB (dibawah PHP limit 40MB)
                'form_nilai_kp' => ['required', 'mimes:pdf,jpg,jpeg,png', 'max:1024'],
                'berita_acara' => ['required', 'mimes:pdf,jpg,jpeg,png', 'max:1024'],
                'panduan' => [Rule::requiredIf(function() use($request){
                    return !empty($request->panduan);
                }), 'mimes:docx', 'max:10000'],
            ]);
            
            \Log::info('Validation passed');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed for jilid KP', [
                'errors' => $e->errors(),
                'mahasiswa_id' => $mahasiswa->id
            ]);
            throw $e;
        }

        try {
            if ($request->laporan_pdf) {
                $validatedData['laporan_pdf'] = StorageHelper::storeKpFile($request->laporan_pdf, $mahasiswa->nim, 'pengumpulan_akhir');
            } else {
                $validatedData['laporan_pdf'] = $request->laporan_link_pdf;
            }
            if ($request->laporan_word) {
                $validatedData['laporan_word'] = StorageHelper::storeKpFile($request->laporan_word, $mahasiswa->nim, 'pengumpulan_akhir');
            } else {
                $validatedData['laporan_word'] = $request->laporan_link;
            }
            $validatedData['lembar_pengesahan'] = StorageHelper::storeKpFile($request->lembar_pengesahan, $mahasiswa->nim, 'pengumpulan_akhir');
            $validatedData['lembar_bimbingan'] = StorageHelper::storeKpFile($request->lembar_bimbingan, $mahasiswa->nim, 'pengumpulan_akhir');
            $validatedData['lembar_revisi'] = StorageHelper::storeKpFile($request->lembar_revisi, $mahasiswa->nim, 'pengumpulan_akhir');
            $validatedData['file_project'] = StorageHelper::storeKpFile($request->file_project, $mahasiswa->nim, 'pengumpulan_akhir');
            $validatedData['form_nilai_kp'] = StorageHelper::storeKpFile($request->form_nilai_kp, $mahasiswa->nim, 'pengumpulan_akhir');
            $validatedData['berita_acara'] = StorageHelper::storeKpFile($request->berita_acara, $mahasiswa->nim, 'pengumpulan_akhir');
            if ($request->panduan) {
                $validatedData['panduan'] = StorageHelper::storeKpFile($request->panduan, $mahasiswa->nim, 'pengumpulan_akhir');
            } else {
                $validatedData['panduan'] = $request->panduan_link;
            }
            $validatedData['mahasiswa_id'] = $mahasiswa->id;
            $validatedData['status'] = Jilid::JILID_REVIEW;
            $validatedData['link_project'] = $request->link_project;

            \Log::info('Files stored, creating/updating jilid', [
                'has_lembar_pengesahan' => !empty($validatedData['lembar_pengesahan']),
                'has_lembar_bimbingan' => !empty($validatedData['lembar_bimbingan']),
                'is_draft_update' => $jilidDraft ? true : false,
            ]);

            // Jika ada jilid draft, update dengan dokumen
            if ($jilidDraft) {
                $jilidDraft->update($validatedData);
                $jilid = $jilidDraft;
                \Log::info('Jilid KP updated from draft', [
                    'jilid_id' => $jilid->id,
                    'mahasiswa_id' => $mahasiswa->id,
                ]);
            } else {
                // Buat jilid baru
                $jilid = Jilid::create($validatedData);
                \Log::info('Jilid KP created', [
                    'jilid_id' => $jilid->id,
                    'mahasiswa_id' => $mahasiswa->id,
                ]);
            }
            
            \Log::info('Jilid KP created successfully', [
                'jilid_id' => $jilid->id,
                'mahasiswa_id' => $mahasiswa->id,
                'has_documents' => !empty($validatedData['lembar_pengesahan'])
            ]);
        } catch (\Exception $e) {
            \Log::error('Error storing jilid KP', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
        
        return redirect()->route('kp.pengumpulan-akhir.mahasiswa')->with('success', 'Pengajuan pengumpulan akhir berhasil. Silahkan tunggu validasi dari Admin');
    }

    public function detail($id)
    {
        $jilid = Jilid::with(['mahasiswa','revisis'])->findOrFail($id);
        $mahasiswa = $jilid->mahasiswa()->with(['bimbingans', 'seminarKP.sesiSeminar.dosenPenguji', 'seminarKP.dosenPenguji', 'jilidKP'])->first();
        // Cari prodi berdasarkan kode ATAU namaprodi untuk backward compatibility
        $prodi = Prodi::where('kode', $mahasiswa->prodi)
            ->orWhere('namaprodi', $mahasiswa->prodi)
            ->first();

        // Hitung Waktu Pelaksanaan
        $pendaftaran = $mahasiswa->pendaftaransKP()->where('status', \App\Models\KP\Pendaftaran::DITERIMA)->first();
        $latestBimbingan = $mahasiswa->bimbingansKP()
            ->where('status', \App\Models\KP\Bimbingan::DITERIMA)
            ->latest('tanggal_acc')
            ->first();

        $dateStart = $pendaftaran && $pendaftaran->tanggal_acc ? \Carbon\Carbon::parse($pendaftaran->tanggal_acc) : null;
        $dateEnd = $latestBimbingan && $latestBimbingan->tanggal_acc ? \Carbon\Carbon::parse($latestBimbingan->tanggal_acc) : null;

        $waktu_pelaksanaan = '-';
        if ($dateStart && $dateEnd) {
             $waktu_pelaksanaan = AppHelper::parse_date_short_surat($dateStart) . ' s/d ' . AppHelper::parse_date_short_surat($dateEnd);
        } elseif ($dateStart) {
             $waktu_pelaksanaan = AppHelper::parse_date_short_surat($dateStart) . ' s/d -';
        }

        return view('kp.pages.pengumpulan-akhir.detail', [
            'title' => 'Detail Jilid KP',
            'sidebar' => Auth::guard('admin')->user()->type == Admin::TYPE_SUPER_ADMIN ? 'partials.sidebarAdmin' : null,
            'active' => Auth::guard('admin')->user()->type == Admin::TYPE_SUPER_ADMIN ? 'pengumpulan-akhir' : null,
            'jilid' => $jilid,
            'mahasiswa' => $mahasiswa,
            'prodi' => $prodi,
            'is_admin' => true,
            'revisis' => $jilid->revisis()->paginate(5),
            'waktu_pelaksanaan' => $waktu_pelaksanaan,
        ]);
    }

    public function detailMahasiswa($id)
    {
        $jilid = Jilid::with(['mahasiswa','revisis'])->findOrFail($id);
        $mahasiswa = $jilid->mahasiswa()->with(['bimbingans', 'seminarKP.sesiSeminar.dosenPenguji', 'seminarKP.dosenPenguji', 'jilidKP'])->first();
        // Cari prodi berdasarkan kode ATAU namaprodi untuk backward compatibility
        $prodi = Prodi::where('kode', $mahasiswa->prodi)
            ->orWhere('namaprodi', $mahasiswa->prodi)
            ->first();

        if (Auth::guard('mahasiswa')->user()->id != $mahasiswa->id) {
            return back();
        }

        // Hitung Waktu Pelaksanaan
        $pendaftaran = $mahasiswa->pendaftaransKP()->where('status', \App\Models\KP\Pendaftaran::DITERIMA)->first();
        $latestBimbingan = $mahasiswa->bimbingansKP()
            ->where('status', \App\Models\KP\Bimbingan::DITERIMA)
            ->latest('tanggal_acc')
            ->first();

        $dateStart = $pendaftaran && $pendaftaran->tanggal_acc ? \Carbon\Carbon::parse($pendaftaran->tanggal_acc) : null;
        $dateEnd = $latestBimbingan && $latestBimbingan->tanggal_acc ? \Carbon\Carbon::parse($latestBimbingan->tanggal_acc) : null;

        $waktu_pelaksanaan = '-';
        if ($dateStart && $dateEnd) {
             $waktu_pelaksanaan = AppHelper::parse_date_short_surat($dateStart) . ' s/d ' . AppHelper::parse_date_short_surat($dateEnd);
        } elseif ($dateStart) {
             $waktu_pelaksanaan = AppHelper::parse_date_short_surat($dateStart) . ' s/d -';
        }

        return view('kp.pages.pengumpulan-akhir.detail', [
            'title' => 'Detail Jilid KP',
            'active' => 'pengumpulan-akhir-kp',
            'jilid' => $jilid,
            'mahasiswa' => $mahasiswa,
            'prodi' => $prodi,
            'is_admin' => false,
            'revisis' => $jilid->revisis()->paginate(5),
            'waktu_pelaksanaan' => $waktu_pelaksanaan,
        ]);
    }

    public function edit($id)
    {
        $jilid = Jilid::with(['mahasiswa','revisis'])->findOrFail($id);

        if ($jilid->status != Jilid::JILID_REVISI) {
            return back();
        } elseif (Auth::guard('mahasiswa')->user()->id != $jilid->mahasiswa->id) {
            return back();
        }

        // Hitung Waktu Pelaksanaan
        $mahasiswa = $jilid->mahasiswa;
        $pendaftaran = $mahasiswa->pendaftaransKP()->where('status', \App\Models\KP\Pendaftaran::DITERIMA)->first();
        $latestBimbingan = $mahasiswa->bimbingansKP()
            ->where('status', \App\Models\KP\Bimbingan::DITERIMA)
            ->latest('tanggal_acc')
            ->first();

        $dateStart = $pendaftaran && $pendaftaran->tanggal_acc ? \Carbon\Carbon::parse($pendaftaran->tanggal_acc) : null;
        $dateEnd = $latestBimbingan && $latestBimbingan->tanggal_acc ? \Carbon\Carbon::parse($latestBimbingan->tanggal_acc) : null;

        $waktu_pelaksanaan = '-';
        if ($dateStart && $dateEnd) {
             $waktu_pelaksanaan = AppHelper::parse_date_short_surat($dateStart) . ' s/d ' . AppHelper::parse_date_short_surat($dateEnd);
        } elseif ($dateStart) {
             $waktu_pelaksanaan = AppHelper::parse_date_short_surat($dateStart) . ' s/d -';
        }

        return view('kp.pages.pengumpulan-akhir.edit', [
            'title' => 'Revisi Pengumpulan Akhir KP',
            'active' => 'pengumpulan-akhir-kp',
            'jilid' => $jilid,
            'pendaftaran' => $pendaftaran,
            'revisis' => $jilid->revisis,
            'waktu_pelaksanaan' => $waktu_pelaksanaan,
        ]);
    }


    public function update(Request $request, $id)
    {
        $jilid = Jilid::with(['mahasiswa'])->where('id', $id)->first();
        if ($jilid->status != Jilid::JILID_REVISI) {
            return back();
        }

        $validatedData = $request->validate([
            // lokasi_kp dan waktu_pelaksanaan_kp tidak disimpan ke database (hanya untuk display)
            'laporan_pdf' => [Rule::requiredIf(fn() => !empty($request->laporan_pdf)), 'mimes:pdf', 'max:5000'],
            'laporan_word' => [Rule::requiredIf(fn() => !empty($request->laporan_word)), 'mimes:docx', 'max:5000'],
            'lembar_pengesahan' => [Rule::requiredIf(fn() => !empty($request->lembar_pengesahan)), 'mimes:pdf', 'max:500'],
            'lembar_keaslian' => [Rule::requiredIf(fn() => !empty($request->lembar_keaslian)), 'mimes:pdf', 'max:500'],
            'lembar_persetujuan_pembimbing' => [Rule::requiredIf(fn() => !empty($request->lembar_persetujuan_pembimbing)), 'mimes:pdf', 'max:500'],
            'lembar_persetujuan_penguji' => [Rule::requiredIf(fn() => !empty($request->lembar_persetujuan_penguji)), 'mimes:pdf', 'max:500'],
            'lembar_bimbingan' => [Rule::requiredIf(fn() => !empty($request->lembar_bimbingan)), 'mimes:pdf', 'max:500'],
            'lembar_revisi' => [Rule::requiredIf(fn() => !empty($request->lembar_revisi)), 'mimes:pdf', 'max:500'],
            'artikel' => [Rule::requiredIf(fn() => !empty($request->artikel)), 'mimes:docx', 'max:5000'],
            'berita_acara' => [Rule::requiredIf(fn() => !empty($request->berita_acara)), 'mimes:pdf', 'max:1000'],
            'panduan' => [Rule::requiredIf(fn() => !empty($request->panduan)), 'mimes:docx', 'max:5000'],
            'lampiran' => [Rule::requiredIf(fn() => !empty($request->lampiran)), 'mimes:pdf', 'max:1000'],
            'file_project' => [Rule::requiredIf(fn() => !empty($request->file_project)), 'mimes:zip,rar', 'max:30720'],
            'form_nilai_kp' => [Rule::requiredIf(fn() => !empty($request->form_nilai_kp)), 'mimes:pdf,jpg,jpeg,png', 'max:1024'],
        ]);

        $mahasiswa = $jilid->mahasiswa;

        if ($request->laporan_pdf) {
            StorageHelper::deleteKpFile($jilid->laporan_pdf);
            $validatedData['laporan_pdf'] = StorageHelper::storeKpFile($request->laporan_pdf, $mahasiswa->nim, 'pengumpulan_akhir');
        } elseif ($request->laporan_link_pdf) {
            StorageHelper::deleteKpFile($jilid->laporan_pdf);
            $validatedData['laporan_pdf'] = $request->laporan_link_pdf;
        }
        if ($request->laporan_word) {
            StorageHelper::deleteKpFile($jilid->laporan_word);
            $validatedData['laporan_word'] = StorageHelper::storeKpFile($request->laporan_word, $mahasiswa->nim, 'pengumpulan_akhir');
        } elseif ($request->laporan_link) {
            StorageHelper::deleteKpFile($jilid->laporan_word);
            $validatedData['laporan_word'] = $request->laporan_link;
        }
        if ($request->lembar_pengesahan) {
            StorageHelper::deleteKpFile($jilid->lembar_pengesahan);
            $validatedData['lembar_pengesahan'] = StorageHelper::storeKpFile($request->lembar_pengesahan, $mahasiswa->nim, 'pengumpulan_akhir');
        }
        if ($request->lembar_keaslian) {
            StorageHelper::deleteKpFile($jilid->lembar_keaslian);
            $validatedData['lembar_keaslian'] = StorageHelper::storeKpFile($request->lembar_keaslian, $mahasiswa->nim, 'pengumpulan_akhir');
        }
        if ($request->lembar_persetujuan_pembimbing) {
            StorageHelper::deleteKpFile($jilid->lembar_persetujuan_pembimbing);
            $validatedData['lembar_persetujuan_pembimbing'] = StorageHelper::storeKpFile($request->lembar_persetujuan_pembimbing, $mahasiswa->nim, 'pengumpulan_akhir');
        }
        if ($request->lembar_persetujuan_penguji) {
            StorageHelper::deleteKpFile($jilid->lembar_persetujuan_penguji);
            $validatedData['lembar_persetujuan_penguji'] = StorageHelper::storeKpFile($request->lembar_persetujuan_penguji, $mahasiswa->nim, 'pengumpulan_akhir');
        }
        if ($request->lembar_bimbingan) {
            StorageHelper::deleteKpFile($jilid->lembar_bimbingan);
            $validatedData['lembar_bimbingan'] = StorageHelper::storeKpFile($request->lembar_bimbingan, $mahasiswa->nim, 'pengumpulan_akhir');
        }
        if ($request->lembar_revisi) {
            StorageHelper::deleteKpFile($jilid->lembar_revisi);
            $validatedData['lembar_revisi'] = StorageHelper::storeKpFile($request->lembar_revisi, $mahasiswa->nim, 'pengumpulan_akhir');
        }
        if ($request->berita_acara) {
            StorageHelper::deleteKpFile($jilid->berita_acara);
            $validatedData['berita_acara'] = StorageHelper::storeKpFile($request->berita_acara, $mahasiswa->nim, 'pengumpulan_akhir');
        }
        if ($request->artikel) {
            StorageHelper::deleteKpFile($jilid->artikel);
            $validatedData['artikel'] = StorageHelper::storeKpFile($request->artikel, $mahasiswa->nim, 'pengumpulan_akhir');
        } elseif ($request->artikel_link) {
            StorageHelper::deleteKpFile($jilid->artikel);
            $validatedData['artikel'] = $request->artikel_link;
        }
        if ($request->panduan) {
            StorageHelper::deleteKpFile($jilid->panduan);
            $validatedData['panduan'] = StorageHelper::storeKpFile($request->panduan, $mahasiswa->nim, 'pengumpulan_akhir');
        } elseif ($request->panduan_link) {
            $validatedData['panduan'] = $request->panduan_link;
        }
        if ($request->lampiran) {
            StorageHelper::deleteKpFile($jilid->lampiran);
            $validatedData['lampiran'] = StorageHelper::storeKpFile($request->lampiran, $mahasiswa->nim, 'pengumpulan_akhir');
        }
        if ($request->file_project) {
            StorageHelper::deleteKpFile($jilid->file_project);
            $validatedData['file_project'] = StorageHelper::storeKpFile($request->file_project, $mahasiswa->nim, 'pengumpulan_akhir');
        }
        if ($request->form_nilai_kp) {
            StorageHelper::deleteKpFile($jilid->form_nilai_kp);
            $validatedData['form_nilai_kp'] = StorageHelper::storeKpFile($request->form_nilai_kp, $mahasiswa->nim, 'pengumpulan_akhir');
        }

        $validatedData['status'] = Jilid::JILID_REVIEW;
        $jilid->update($validatedData);

        return redirect()->route('kp.pengumpulan-akhir.mahasiswa')->with('success', 'Pengajuan pengumpulan akhir berhasil. Silahkan tunggu validasi dari Admin');
    }

    public function acc(Request $request, $id)
    {
        $jilid = Jilid::findOrFail($id);

        if ($request->catatan) {
            $revisi = new RevisiJilid;
            $revisi->catatan = $request->catatan;
            $revisi->jilid_id = $jilid->id;
            $jilid->revisis()->save($revisi);
        }

        $updateData = [
            'status' => $request->status,
        ];

        // Tambahkan total_pembayaran jika ada (dari fotokopian)
        if ($request->total_pembayaran) {
            $updateData['total_pembayaran'] = $request->total_pembayaran;
        }

        $jilid->update($updateData);

        if ($request->status == Jilid::JILID_SELESAI) {
            $message = 'Pengumpulan Akhir KP Anda Berstatus SELESAI. Selamat!';
            if ($request->total_pembayaran) {
                $message .= ' Silahkan ambil di FOTOKOPIAN FASTIKOM dan lakukan pembayaran sebesar Rp ' . number_format($request->total_pembayaran, 0, ',', '.');
            }
            AppHelper::instance()->send_mail([
                'mail' => $jilid->mahasiswa->email,
                'subject' => 'Pengumpulan Akhir KP',
                'title' => 'EKAPTA',
                'message' => $message,
            ]);
        } elseif ($request->status == Jilid::JILID_VALID) {
            AppHelper::instance()->send_mail([
                'mail' => $jilid->mahasiswa->email,
                'subject' => 'Pengumpulan Akhir KP',
                'title' => 'EKAPTA',
                'message' => 'Dokumen Kerja Praktek sudah dikonfirmasi oleh admin dan siap untuk dijilid. Silahkan konfirmasi dan melakukan pembayaran ke Fotocopy Fastikom dengan membawa dokumen-dokumen asli.',
            ]);
        } elseif ($request->status == Jilid::JILID_REVISI) {
            AppHelper::instance()->send_mail([
                'mail' => $jilid->mahasiswa->email,
                'subject' => 'Pengumpulan Akhir KP',
                'title' => 'EKAPTA',
                'message' => 'Dokumen Kerja Praktek Berstatus REVISI. Silahkan submit ulang! <br>Ket: '. $request->catatan,
            ]);
        }

        return redirect()->route('kp.pengumpulan-akhir.index')->with('success', 'Pengajuan pengumpulan akhir berhasil di update');
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

        return redirect()->route('kp.pengumpulan-akhir.index')->with('success', 'Konfirmasi setor ke perpus berhasil disimpan');
    }

    public function indexProdi()
    {
        $userProdi = Auth::guard('prodi')->user();

        // Ambil data KP filter by prodi (exclude draft)
        $jilids_kp = Jilid::with('mahasiswa')
            ->where('status', '!=', Jilid::JILID_DRAFT)
            ->whereHas('mahasiswa', function($q) use ($userProdi) {
                // Coba cocokan dengan namaprodi atau kode atau ID
                $q->where('prodi_id', $userProdi->id)
                  ->orWhere('prodi', $userProdi->kode)
                  ->orWhere('prodi', $userProdi->namaprodi);
            })
            ->orderBy('created_at','desc')
            ->get();

        // Ambil data TA filter by prodi
        $JilidTA = \App\Models\Jilid::class;
        $jilids_ta = $JilidTA::with('mahasiswa')
            ->whereHas('mahasiswa', function($q) use ($userProdi) {
                $q->where('prodi_id', $userProdi->id)
                  ->orWhere('prodi', $userProdi->kode)
                  ->orWhere('prodi', $userProdi->namaprodi);
            })
            ->orderBy('created_at','desc')
            ->get();

        return view('kp.pages.pengumpulan-akhir.prodi-index', [
            'sidebar' => 'kp.partials.sidebarProdi',
            'title' => 'Data Jilid KP',
            'active' => 'pengumpulan-akhir-kp',
            'jilids_kp' => $jilids_kp,
            'jilids_ta' => $jilids_ta,
        ]);
    }

    public function detailProdi($id)
    {
        $jilid = Jilid::with(['mahasiswa','revisis'])->findOrFail($id);
        $mahasiswa = $jilid->mahasiswa()->with(['bimbingansKP', 'seminarKP.sesiSeminar.dosenPenguji', 'seminarKP.dosenPenguji', 'jilidKP'])->first();
        
        // Relasi prodi
        $prodi = $mahasiswa->prodiRelation;

        // Hitung Waktu Pelaksanaan
        $pendaftaran = $mahasiswa->pendaftaransKP()->where('status', \App\Models\KP\Pendaftaran::DITERIMA)->first();
        $latestBimbingan = $mahasiswa->bimbingansKP()
            ->where('status', \App\Models\KP\Bimbingan::DITERIMA)
            ->latest('tanggal_acc')
            ->first();

        $dateStart = $pendaftaran && $pendaftaran->tanggal_acc ? \Carbon\Carbon::parse($pendaftaran->tanggal_acc) : null;
        $dateEnd = $latestBimbingan && $latestBimbingan->tanggal_acc ? \Carbon\Carbon::parse($latestBimbingan->tanggal_acc) : null;

        $waktu_pelaksanaan = '-';
        if ($dateStart && $dateEnd) {
             $waktu_pelaksanaan = \App\Helpers\AppHelper::parse_date_short_surat($dateStart) . ' s/d ' . \App\Helpers\AppHelper::parse_date_short_surat($dateEnd);
        } elseif ($dateStart) {
             $waktu_pelaksanaan = \App\Helpers\AppHelper::parse_date_short_surat($dateStart) . ' s/d -';
        }

        // Cek apakah karyawan (Pakai helper yang sudah dibuat)
        $is_karyawan = \App\Helpers\AppHelper::isKaryawanKP($mahasiswa);

        return view('kp.pages.pengumpulan-akhir.prodi-detail', [
            'sidebar' => 'kp.partials.sidebarProdi',
            'title' => 'Detail Jilid KP',
            'active' => 'pengumpulan-akhir-kp',
            'jilid' => $jilid,
            'mahasiswa' => $mahasiswa,
            'prodi' => $prodi,
            'revisis' => $jilid->revisis()->paginate(5),
            'waktu_pelaksanaan' => $waktu_pelaksanaan,
            'is_karyawan' => $is_karyawan,
        ]);
    }

    public function updateNilai(Request $request, $id)
    {
        $jilid = Jilid::findOrFail($id);
        
        $request->validate([
            'nilai_pembimbing' => 'required|numeric|min:0|max:100',
            'nilai_penguji' => 'nullable|numeric|min:0|max:100',
            'nilai_instansi' => 'required|numeric|min:0|max:100',
        ]);

        // Jika nilai penguji kosong (opsional), set 0 atau samakan dengan pembimbing (tergantung kebijakan)
        // Disini kita set 0 jika null, tapi nanti perhitungan nilai akhir pakai bobot
        $nilai_penguji = $request->nilai_penguji ?? 0;

        $jilid->update([
            'nilai_pembimbing' => $request->nilai_pembimbing,
            'nilai_penguji' => $nilai_penguji,
            'nilai_instansi' => $request->nilai_instansi,
        ]);

        // Hitung nilai akhir otomatis (method di Model Jilid)
        $jilid->hitungNilaiAkhir();

        return back()->with('success', 'Nilai Kerja Praktek berhasil disimpan.');
    }

    /**
     * Detail Jilid TA untuk Admin
     * Menampilkan dokumen pengumpulan akhir TA
     */
    public function detailTA($id)
    {
        $jilid = \App\Models\Jilid::with(['mahasiswa','revisis'])->findOrFail($id);
        $mahasiswa = $jilid->mahasiswa()->with(['bimbingans'])->first();
        $prodi = Prodi::where('kode', $mahasiswa->prodi)
            ->orWhere('namaprodi', $mahasiswa->prodi)
            ->first();

        return view('kp.pages.pengumpulan-akhir.detail-ta', [
            'title' => 'Detail Jilid TA',
            'sidebar' => Auth::guard('admin')->user()->type == Admin::TYPE_SUPER_ADMIN ? 'partials.sidebarAdmin' : null,
            'active' => 'pengumpulan-akhir',
            'jilid' => $jilid,
            'mahasiswa' => $mahasiswa,
            'prodi' => $prodi,
            'is_admin' => true,
            'revisis' => $jilid->revisis()->paginate(5),
        ]);
    }

    /**
     * Detail Jilid TA untuk Prodi
     * Menampilkan dokumen pengumpulan akhir TA
     */
    public function detailProdiTA($id)
    {
        $jilid = \App\Models\Jilid::with(['mahasiswa','revisis'])->findOrFail($id);
        $mahasiswa = $jilid->mahasiswa()->with(['bimbingans'])->first();
        $prodi = Prodi::where('kode', $mahasiswa->prodi)
            ->orWhere('namaprodi', $mahasiswa->prodi)
            ->first();

        return view('kp.pages.pengumpulan-akhir.prodi-detail-ta', [
            'sidebar' => 'kp.partials.sidebarProdi',
            'title' => 'Detail Jilid TA',
            'active' => 'pengumpulan-akhir-kp',
            'jilid' => $jilid,
            'mahasiswa' => $mahasiswa,
            'prodi' => $prodi,
            'revisis' => $jilid->revisis()->paginate(5),
        ]);
    }
}

