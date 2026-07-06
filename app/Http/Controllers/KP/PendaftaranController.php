<?php

namespace App\Http\Controllers\KP;

use App\Models\KP\Pendaftaran;
use App\Models\KP\RevisiPendaftaran;
use Illuminate\Http\Request;
use App\Helpers\AppHelper;
use App\Helpers\StorageHelper;
use App\Models\KP\Bimbingan;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\KP\Pengajuan;
use App\Models\Prodi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PendaftaranController extends \App\Http\Controllers\Controller
{
    public function pendaftaranAdmin()
    {
        $pendaftarans = Pendaftaran::with(['mahasiswa', 'pengajuan'])->where('status', Pendaftaran::REVIEW)->orderBy('created_at', 'desc')->get();
        $pendaftarans_acc = Pendaftaran::with(['mahasiswa', 'pengajuan'])->where('status', Pendaftaran::DITERIMA)->orderBy('created_at', 'desc')->get();
        $pendaftarans_revisi = Pendaftaran::with(['mahasiswa', 'pengajuan'])->where('status', Pendaftaran::REVISI)->orderBy('created_at', 'desc')->get();
        return view('kp.pages.admin.pendaftaran.pendaftaran', [
            'title' => 'Pendaftaran Kerja Praktek',
            'active' => 'pendaftaran-kp',
            'sidebar' => 'kp.partials.sidebarAdmin',
            'module' => 'kp',
            'pendaftarans' => $pendaftarans,
            'pendaftarans_acc' => $pendaftarans_acc,
            'pendaftarans_revisi' => $pendaftarans_revisi,
        ]);
    }

    public function pendaftaranMahasiswa()
    {
        $mahasiswa = Mahasiswa::where('nim', Auth::guard('mahasiswa')->user()->nim)->first();
        if($mahasiswa->email == '-'){
            return redirect()->route('kp.profile');
        }
        $pengajuan = $mahasiswa->pengajuansKP()->where('status', Pengajuan::DITERIMA)->first();

        // KP workflow: single dosen pembimbing
        $dosenPembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        // Fallback for legacy data
        if (!$dosenPembimbing) {
            $dosenPembimbing = $mahasiswa->dosens()->where('status', Dosen::UTAMA)->first();
        }

        $pendaftarans = Pendaftaran::orderBy('created_at','desc')->where('mahasiswa_id', $mahasiswa->id)->with(['revisis'])->get();

        if (!$pengajuan) {
            return redirect()->route('kp.pengajuan.mahasiswa')->with('warning', 'Silahkan melakukan Pengajuan Kerja Praktek terlebih dahulu dan tunggu hingga disetujui Prodi');
        }

        $pendaftaranIsAcc = Pendaftaran::where('pengajuan_id', $pengajuan->id)->with(['revisis'])->where('status', Pendaftaran::DITERIMA)->get();
        $pendaftarans_review_acc_revisi = Pendaftaran::where('pengajuan_id', $pengajuan->id)->with(['revisis'])->whereIn('status', [Pendaftaran::REVIEW, Pendaftaran::DITERIMA, Pendaftaran::REVISI])->get();

        return view('kp.pages.mahasiswa.pendaftaran.pendaftaran', [
            'title' => 'Pendaftaran Kerja Praktek',
            'active' => 'pendaftaran-kp',
            'pendaftarans' => $pendaftarans,
            'dosen_pembimbing' => $dosenPembimbing,
            'pendaftaranIsAcc' => $pendaftaranIsAcc,
            'pendaftarans_review_acc_revisi' => $pendaftarans_review_acc_revisi,
        ]);
    }

    public function create()
    {
        $mahasiswa = Mahasiswa::where('nim', Auth::guard('mahasiswa')->user()->nim)->first();
        $pengajuan = $mahasiswa->pengajuansKP()->where('status', Pengajuan::DITERIMA)->first();

        $pendaftarans_review_acc = Pendaftaran::where('pengajuan_id', $pengajuan->id)->whereIn('status', [Pendaftaran::DITERIMA, Pendaftaran::REVIEW])->get();

        if (count($pendaftarans_review_acc) != 0) {
            return redirect()->route('kp.pendaftaran.mahasiswa')->with('warning', 'Anda sudah melakukan pendaftaran kerja praktek');
        } else if (count(Auth::guard('mahasiswa')->user()->dosens) == 0) {
            return back()->with('warning', 'Silahkan tunggu ploting dosen pembimbing oleh Prodi');
        }

        // KP workflow: single dosen pembimbing (status = 'pembimbing')
        $dosenPembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        // Fallback: check 'utama' for legacy data
        if (!$dosenPembimbing) {
            $dosenPembimbing = $mahasiswa->dosens()->where('status', 'utama')->first();
        }

        return view('kp.pages.mahasiswa.pendaftaran.create', [
            'title' => 'Form Pendaftaran Kerja Praktek',
            'active' => 'pendaftaran-kp',
            'dosen_pembimbing' => $dosenPembimbing,
            'pengajuan' => $pengajuan,
            'biayaOptions' => Pendaftaran::getBiayaOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $mahasiswa = Mahasiswa::where('nim', Auth::guard('mahasiswa')->user()->nim)->first();
        $pengajuan = $mahasiswa->pengajuansKP()->where('status', Pengajuan::DITERIMA)->first();

        $pendaftarans_review_acc = Pendaftaran::where('pengajuan_id', $pengajuan->id)->whereIn('status', [Pendaftaran::DITERIMA, Pendaftaran::REVIEW])->get();

        if (count($pendaftarans_review_acc) != 0) {
            return redirect()->route('kp.pendaftaran.mahasiswa')->with('warning', 'Anda sudah melakukan pendaftaran');
        } else {
            $validatedData = $request->validate([
                'nomor_pembayaran' => 'nullable',
                'tanggal_pembayaran' => 'required',
                'biaya' => 'required',
                'lampiran_1' => ['required', 'mimes:pdf', 'max:5000'],
                'lampiran_2' => ['required', 'mimes:pdf', 'max:5000'],
                'lampiran_3' => ['required', 'mimes:pdf', 'max:5000'],
                'lampiran_5' => ['required', 'mimes:pdf,png,jpg,jpeg', 'max:5000'],
                'lampiran_6' => ['nullable', 'mimes:pdf', 'max:5000'],
                'lampiran_7' => ['required', 'mimes:pdf', 'max:5000'],
                'dokumen_pendukung' => ['required', 'mimes:pdf', 'max:5000'],
            ]);

            $statusPendaftaran = Pendaftaran::resolveStatusPendaftaranFromBiaya($validatedData['biaya']);

            if (!$statusPendaftaran) {
                throw ValidationException::withMessages([
                    'biaya' => 'Biaya tidak sesuai dengan status pendaftaran yang dipilih.',
                ]);
            }

            $validatedData['lampiran_1'] = StorageHelper::storeKpFile($request->file('lampiran_1'), $mahasiswa->nim, 'pendaftaran');
            $validatedData['lampiran_2'] = StorageHelper::storeKpFile($request->file('lampiran_2'), $mahasiswa->nim, 'pendaftaran');
            $validatedData['lampiran_3'] = StorageHelper::storeKpFile($request->file('lampiran_3'), $mahasiswa->nim, 'pendaftaran');
            $validatedData['lampiran_5'] = StorageHelper::storeKpFile($request->file('lampiran_5'), $mahasiswa->nim, 'pendaftaran');
            if ($request->file('lampiran_6')) {
                $validatedData['lampiran_6'] = StorageHelper::storeKpFile($request->file('lampiran_6'), $mahasiswa->nim, 'pendaftaran');
            }
            $validatedData['lampiran_7'] = StorageHelper::storeKpFile($request->file('lampiran_7'), $mahasiswa->nim, 'pendaftaran');
            $validatedData['dokumen_pendukung'] = StorageHelper::storeKpFile($request->file('dokumen_pendukung'), $mahasiswa->nim, 'pendaftaran');

            $validatedData['mahasiswa_id'] = $mahasiswa->id;
            $validatedData['pengajuan_id'] = $pengajuan->id;

            // Keep the date in proper format for database (Y-m-d)
            $validatedData['tanggal_pembayaran'] = $request->tanggal_pembayaran;

            // Tentukan jenis_mahasiswa dan kelas berdasarkan data mahasiswa dari database
            // Cek field 'kelas' di tabel mahasiswa
            if ($mahasiswa->kelas == 'B') {
                // Mahasiswa Karyawan
                $validatedData['jenis_mahasiswa'] = Pendaftaran::JENIS_KARYAWAN;
                $validatedData['kelas'] = 'B';
            } else {
                // Mahasiswa Reguler - cek D3 atau S1
                if (str_contains(strtoupper($mahasiswa->prodi), 'D3')) {
                    $validatedData['jenis_mahasiswa'] = Pendaftaran::JENIS_REGULER_D3;
                } else {
                    $validatedData['jenis_mahasiswa'] = Pendaftaran::JENIS_REGULER_S1;
                }
                $validatedData['kelas'] = 'Reguler';
            }

            if ($statusPendaftaran === Pendaftaran::STATUS_PENDAFTARAN_PERPANJANG) {
                $validatedData['jumlah_perpanjangan'] = 1;
                $validatedData['tanggal_perpanjangan_terakhir'] = now();
            } else {
                $validatedData['jumlah_perpanjangan'] = 0;
                $validatedData['tanggal_perpanjangan_terakhir'] = null;
            }

            Pendaftaran::create($validatedData);
            return redirect()->route('kp.pendaftaran.mahasiswa')->with('success', 'Berhasil melakukan pendaftaran');
        }
    }

    public function edit($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        if ($pendaftaran->mahasiswa_id != Auth::guard('mahasiswa')->user()->id) {
            abort(404);
        }

        if ($pendaftaran->status == Pendaftaran::REVIEW || $pendaftaran->status == Pendaftaran::DITERIMA) {
            return redirect()->route('kp.pendaftaran.mahasiswa');
        }

        if ($pendaftaran->status == Pendaftaran::REVIEW ||  $pendaftaran->status == Pendaftaran::DITERIMA) {
            return back()->with('warning', 'Pendaftaran tidak bisa diedit');
        }

        $mahasiswa = Mahasiswa::where('id', $pendaftaran->mahasiswa_id)->first();
        // KP workflow: single dosen pembimbing
        $dosenPembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        if (!$dosenPembimbing) {
            $dosenPembimbing = $mahasiswa->dosens()->where('status', Dosen::UTAMA)->first();
        }

        return view('kp.pages.mahasiswa.pendaftaran.edit', [
            'title' => 'Form Edit Pendaftaran Kerja Praktek',
            'active' => 'pendaftaran-kp',
            'dosen_pembimbing' => $dosenPembimbing,
            'pendaftaran' => $pendaftaran,
            'mahasiswa' => $mahasiswa,
            'biayaOptions' => Pendaftaran::getBiayaOptions(),
        ]);
    }

    public function pendaftaranReview($id)
    {
        $pendaftaran = Pendaftaran::with('mahasiswa')->findOrFail($id);
        $mahasiswa = $pendaftaran->mahasiswa;
        
        // KP workflow: single dosen pembimbing
        $dosenPembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        if (!$dosenPembimbing) {
            $dosenPembimbing = $mahasiswa->dosens()->where('status', Dosen::UTAMA)->first();
        }

        return view('kp.pages.admin.pendaftaran.review', [
            'title' => 'Review Pendaftaran Kerja Praktek',
            'active' => 'pendaftaran-kp',
            'sidebar' => 'kp.partials.sidebarAdmin',
            'module' => 'kp',
            'dosen_pembimbing' => $dosenPembimbing,
            'pendaftaran' => $pendaftaran,
            'mahasiswa' => $mahasiswa,
            'revisis' => $pendaftaran->revisis()->orderBy('created_at', 'desc')->paginate(5),
        ]);
    }

    public function pendaftaranDetail($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        $mahasiswa = $pendaftaran->mahasiswa;

        // KP workflow: single dosen pembimbing
        $dosenPembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        if (!$dosenPembimbing) {
            $dosenPembimbing = $mahasiswa->dosens()->where('status', Dosen::UTAMA)->first();
        }

        if ($pendaftaran->mahasiswa_id != Auth::guard('mahasiswa')->user()->id) {
            abort(404);
        }

        if (!$pendaftaran) {
            return back()->with('warning', 'Pendaftaran tidak ditemukan');
        }

        return view('kp.pages.mahasiswa.pendaftaran.detail', [
            'title' => 'Detail Pendaftaran Kerja Praktek',
            'active' => 'pendaftaran-kp',
            'dosen_pembimbing' => $dosenPembimbing,
            'pendaftaran' => $pendaftaran,
            'revisis' => $pendaftaran->revisis()->orderBy('created_at', 'desc')->paginate(5),
        ]);
    }

    public function update(Request $request)
    {
        $pendaftaran = Pendaftaran::findOrFail($request->id);
        $validatedData = $request->validate([
            'nomor_pembayaran' => 'nullable',
            'biaya' => 'required',
            'lampiran_1' => [Rule::requiredIf(function () {
                if (empty($this->request->lampiran_1)) {return false;}
                return true;
            }), 'mimes:pdf', 'max:5000'],
            'lampiran_2' => [Rule::requiredIf(function () {
                if (empty($this->request->lampiran_2)) {return false;}
                return true;
            }), 'mimes:pdf', 'max:5000'],
            'lampiran_3' => [Rule::requiredIf(function () {
                if (empty($this->request->lampiran_3)) {return false;}
                return true;
            }), 'mimes:pdf', 'max:5000'],
            'lampiran_5' => [Rule::requiredIf(function () {
                if (empty($this->request->lampiran_5)) {return false;}
                return true;
            }), 'mimes:pdf,png,jpg,jpeg', 'max:5000'],
            'lampiran_6' => [Rule::requiredIf(function () {
                if (empty($this->request->lampiran_6)) {return false;}
                return true;
            }), 'mimes:pdf', 'max:5000'],
            'lampiran_7' => [Rule::requiredIf(function () {
                if (empty($this->request->lampiran_7)) {return false;}
                return true;
            }), 'mimes:pdf', 'max:5000'],
            'dokumen_pendukung' => [Rule::requiredIf(function () {
                if (empty($this->request->dokumen_pendukung)) {return false;}
                return true;
            }), 'mimes:pdf', 'max:5000'],
        ]);

        $statusPendaftaran = Pendaftaran::resolveStatusPendaftaranFromBiaya($validatedData['biaya']);

        if (!$statusPendaftaran) {
            throw ValidationException::withMessages([
                'biaya' => 'Biaya tidak sesuai dengan status pendaftaran yang dipilih.',
            ]);
        }

        $mahasiswa = $pendaftaran->mahasiswa;
        if ($request->file('lampiran_1')) {
            StorageHelper::deleteKpFile($pendaftaran->lampiran_1);
            $validatedData['lampiran_1'] = StorageHelper::storeKpFile($request->lampiran_1, $mahasiswa->nim, 'pendaftaran');
        }
        if ($request->file('lampiran_2')) {
            StorageHelper::deleteKpFile($pendaftaran->lampiran_2);
            $validatedData['lampiran_2'] = StorageHelper::storeKpFile($request->lampiran_2, $mahasiswa->nim, 'pendaftaran');
        }
        if ($request->file('lampiran_3')) {
            StorageHelper::deleteKpFile($pendaftaran->lampiran_3);
            $validatedData['lampiran_3'] = StorageHelper::storeKpFile($request->lampiran_3, $mahasiswa->nim, 'pendaftaran');
        }
        if ($request->file('lampiran_5')) {
            StorageHelper::deleteKpFile($pendaftaran->lampiran_5);
            $validatedData['lampiran_5'] = StorageHelper::storeKpFile($request->lampiran_5, $mahasiswa->nim, 'pendaftaran');
        }
        if ($request->file('lampiran_6')) {
            if($pendaftaran->lampiran_6) StorageHelper::deleteKpFile($pendaftaran->lampiran_6);
            $validatedData['lampiran_6'] = StorageHelper::storeKpFile($request->lampiran_6, $mahasiswa->nim, 'pendaftaran');
        }
        if ($request->file('lampiran_7')) {
            if($pendaftaran->lampiran_7) StorageHelper::deleteKpFile($pendaftaran->lampiran_7);
            $validatedData['lampiran_7'] = StorageHelper::storeKpFile($request->lampiran_7, $mahasiswa->nim, 'pendaftaran');
        }
        if ($request->file('dokumen_pendukung')) {
            if($pendaftaran->dokumen_pendukung) StorageHelper::deleteKpFile($pendaftaran->dokumen_pendukung);
            $validatedData['dokumen_pendukung'] = StorageHelper::storeKpFile($request->dokumen_pendukung, $mahasiswa->nim, 'pendaftaran');
        }

        $validatedData['mahasiswa_id'] = $mahasiswa->id;
        $validatedData['judul'] = $pendaftaran->judul;
        $validatedData['status'] = Pendaftaran::REVIEW;

        if ($request->tanggal_pembayaran) {
            // Keep the date in proper format for database (Y-m-d)
            $validatedData['tanggal_pembayaran'] = $request->tanggal_pembayaran;
        }

        if ($statusPendaftaran === Pendaftaran::STATUS_PENDAFTARAN_PERPANJANG) {
            $validatedData['jumlah_perpanjangan'] = max((int) $pendaftaran->jumlah_perpanjangan, 1);
            $validatedData['tanggal_perpanjangan_terakhir'] = $pendaftaran->tanggal_perpanjangan_terakhir ?: now();
        } else {
            $validatedData['jumlah_perpanjangan'] = 0;
            $validatedData['tanggal_perpanjangan_terakhir'] = null;
        }

        // Tidak perlu update kelas karena sudah ditentukan saat create
        // Kelas tidak berubah meskipun edit

        $pendaftaran->update($validatedData);
        return redirect()->route('kp.pendaftaran.mahasiswa')->with('success', 'Pendaftaran berhasil diupdate');
    }

    public function delete(Request $request)
    {
        $pendaftaran = Pendaftaran::findOrFail($request->id);
        if ($pendaftaran->status == Pendaftaran::DITERIMA) {
            return back()->with('error', 'Pendaftaran gagal dihapus');
        } else {
            StorageHelper::deleteKpFile($pendaftaran->lampiran_2);
            StorageHelper::deleteKpFile($pendaftaran->lampiran_3);
            StorageHelper::deleteKpFile($pendaftaran->lampiran_4);
            StorageHelper::deleteKpFile($pendaftaran->lampiran_5);
            $pendaftaran->delete();
            return back()->with('success', 'Pendaftaran berhasil dihapus');
        }
    }

    public function accPendaftaran(Request $request)
    {
        $pendaftaran = Pendaftaran::findOrFail($request->id);

        $mahasiswa = Mahasiswa::where('id', $pendaftaran->mahasiswa_id)->first();
        $mahasiswa->update([
            'thmasuk' => $request->tahun_masuk,
        ]);

        // KP hanya butuh 1 dosen pembimbing (status = 'pembimbing')
        $dosenPembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();

        $pendaftaran_disabled = Pendaftaran::where('mahasiswa_id', $mahasiswa->id)->where('status', Pendaftaran::DISABLED)->first();

        // Cari prodi berdasarkan kode ATAU namaprodi untuk backward compatibility
        $prodi = Prodi::where('kode', $mahasiswa->prodi)
            ->orWhere('namaprodi', $mahasiswa->prodi)
            ->first();

        // Validasi dosen pembimbing sudah di-assign di tahap pengajuan
        if (!$dosenPembimbing) {
            return back()->with('warning', 'Dosen pembimbing belum ditentukan. Pastikan Prodi sudah menentukan dosen pembimbing di tahap Pengajuan KP.');
        } elseif (!$prodi || count($prodi->bagiansKP()->where("tahun_masuk", "LIKE", "%" . $mahasiswa->thmasuk . "%")->get()) == 0) {
            return back()->with('warning', 'Bagian bimbingan KP untuk prodi ' . $mahasiswa->prodi . ' dan tahun masuk '.$mahasiswa->thmasuk.' masih kosong');
        } elseif ($pendaftaran->status == Pendaftaran::DITERIMA) {
            return back()->with('warning', 'Pendaftaran sudah diacc');
        } else {
            $pendaftaran->update([
                'status' => Pendaftaran::DITERIMA,
                'tanggal_acc' => now(),
            ]);

            if (!$pendaftaran_disabled) {
                // KP: Otomatis create bimbingan dengan 1 dosen pembimbing untuk setiap bagian KP
                foreach ($prodi->bagiansKP()->where("tahun_masuk", "LIKE", "%" . $mahasiswa->thmasuk . "%")->get() as $bagian) {
                    $bimbingan = Bimbingan::create([
                        'mahasiswa_id' => $mahasiswa->id,
                        'bagian_id' => $bagian->id,
                        'pembimbing' => 'pembimbing', // KP hanya 1 pembimbing
                        'status' => null, // Status null sampai mahasiswa submit bimbingan
                    ]);
                    $bimbingan->dosens()->attach([$dosenPembimbing->id]);
                }
            }
            if ($pendaftaran->mahasiswa->email != '-') {
                AppHelper::instance()->send_mail([
                    'mail' => $pendaftaran->mahasiswa->email,
                    'subject' => 'Pendaftaran Kerja Praktek',
                    'title' => 'EKAPTA',
                    'message' => 'Selamat Pendaftaran Kerja Praktek Anda Berstatus DITERIMA. Anda bisa memulai Bimbingan Kerja Praktek.',
                ]);
            }
            return back()->with('success', 'Pendaftaran berhasil diacc');
        }
    }

    public function cancelAcc(Request $request)
    {
        $pendaftaran = Pendaftaran::findOrFail($request->id);
        $mahasiswa = Mahasiswa::where('id', $pendaftaran->mahasiswa_id)->first();
        if ($pendaftaran->status != Pendaftaran::DITERIMA) {
            return back()->with('error', 'Pendaftaran tidak ditemukan');
        } else {
            $pendaftaran->update([
                'status' => Pendaftaran::REVIEW,
                'tanggal_acc' => null,
            ]);
            $mahasiswa->bimbingans->each->delete();
            return redirect()->route('kp.pendaftaran.review', $pendaftaran->id)->with('success', 'Acc pendaftaran berhasil dibatalkan');
        }
    }

    public function revisiPendaftaran(Request $request)
    {
        $pendaftaran = Pendaftaran::findOrFail($request->id);
        $revisi = new RevisiPendaftaran;
        $revisi->keterangan = $request->catatan;
        $request->validate([
            'lampiran' => [Rule::requiredIf(function () {
                if (empty($this->request->lampiran)) {
                    return false;
                }
                return true;
            }), 'mimes:pdf,docx', 'max:5000']
        ]);
        if ($request->file('lampiran')) {
            $revisi->lampiran = StorageHelper::storeKpFile($request->lampiran, $pendaftaran->mahasiswa->nim, 'pendaftaran');
        }
        if ($pendaftaran->status == Pendaftaran::REVIEW) {
            $pendaftaran->update([
                'status' => Pendaftaran::REVISI,
            ]);
            $pendaftaran->revisis()->save($revisi);
            if ($pendaftaran->mahasiswa->email != '-') {
                AppHelper::instance()->send_mail([
                    'mail' => $pendaftaran->mahasiswa->email,
                    'subject' => 'Pendaftaran Kerja Praktek',
                    'title' => 'EKAPTA',
                    'message' => 'Pendaftaran Kerja Praktek Anda Berstatus REVISI. Silahkan perbaiki kemudian submit ulang. <br><br> Catatan Revisi: '.$request->catatan,
                ]);
            }
            return redirect()->route('kp.pendaftaran.admin')->with('success', 'Pendaftaran berhasil direvisi');
        } elseif ($pendaftaran->status == Pendaftaran::REVISI) {
            $pendaftaran->revisis()->save($revisi);
            return back()->with('success', 'Revisi berhasil ditambahkan');
        }
    }

    public function deleteRevisiPendaftaran(Request $request)
    {
        $revisi = RevisiPendaftaran::findOrFail($request->id);
        StorageHelper::deleteKpFile($revisi->lampiran);
        $revisi->delete();
        return back()->with('success', 'Revisi berhasil dihapus');
    }

    public function disablePendaftaran($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        $pendaftaran->update([
            'status' => Pendaftaran::DISABLED,
        ]);

        return redirect()->route('kp.pendaftaran.create');
    }
}

