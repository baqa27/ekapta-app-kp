<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\RevisiPendaftaran;
use Illuminate\Http\Request;
use App\Helpers\AppHelper;
use App\Models\Bimbingan;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Pengajuan;
use App\Models\Prodi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PendaftaranController extends Controller
{
    public function index()
    {
        $pendaftarans = Pendaftaran::where('status', Pendaftaran::REVIEW)->orderBy('created_at', 'desc')->get();
        $pendaftarans_acc = Pendaftaran::where('status', Pendaftaran::DITERIMA)->orderBy('created_at', 'desc')->get();
        $pendaftarans_revisi = Pendaftaran::where('status', Pendaftaran::REVISI)->orderBy('created_at', 'desc')->get();
        return view('pages.admin.pendaftaran.pendaftaran', [
            'title' => 'Pendaftaran Tugas Akhir',
            'active' => 'pendaftaran',
            'sidebar' => 'partials.sidebarAdmin',
            'pendaftarans' => $pendaftarans,
            'pendaftarans_acc' => $pendaftarans_acc,
            'pendaftarans_revisi' => $pendaftarans_revisi,
        ]);
    }

    public function pendaftaranMahasiswa()
    {
        $mahasiswa = Mahasiswa::where('nim', Auth::guard('mahasiswa')->user()->nim)->first();
        $pengajuan = $mahasiswa->pengajuans()->where('status', Pengajuan::DITERIMA)->first();

        $dosenUtama = $mahasiswa->dosens()->where('status', Dosen::UTAMA)->first();
        $dosenPendamping = $mahasiswa->dosens()->where('status', Dosen::PENDAMPING)->first();

        $pendaftarans = Pendaftaran::where('mahasiswa_id', $mahasiswa->id)->with(['revisis'])->get();

        if (!$pengajuan) {
            return back()->with('warning', 'Silahkan melakukan Pengajuan Tugas Akhir terlebih dahulu');
        }

        $pendaftaranIsAcc = Pendaftaran::where('pengajuan_id', $pengajuan->id)->with(['revisis'])->where('status', Pendaftaran::DITERIMA)->get();

        return view('pages.mahasiswa.pendaftaran.pendaftaran', [
            'title' => 'Pendaftaran Tugas Akhir',
            'active' => 'pendaftaran',
            'pendaftarans' => $pendaftarans,
            'dosen_utama' => $dosenUtama,
            'dosen_pendamping' => $dosenPendamping,
            'pendaftaranIsAcc' => $pendaftaranIsAcc,
        ]);
    }

    public function create()
    {
        $mahasiswa = Mahasiswa::where('nim', Auth::guard('mahasiswa')->user()->nim)->first();
        $pengajuan = $mahasiswa->pengajuans()->where('status', Pengajuan::DITERIMA)->first();

        $pendaftaranIsAcc = Pendaftaran::where('pengajuan_id', $pengajuan->id)->where('status', Pendaftaran::DITERIMA)->first();

        if ($pendaftaranIsAcc) {
            return redirect('pendaftaran-mahasiswa')->with('warning', 'Anda sudah melakukan pendaftaran tugas akhir');
        } else if (count(Auth::guard('mahasiswa')->user()->dosens) == 0) {
            return back()->with('warning', 'Silahkan tunggu ploting dosen pembimbing oleh Prodi');
        }

        $dosenUtama = $mahasiswa->dosens()->where('status', Dosen::UTAMA)->first();
        $dosenPendamping = $mahasiswa->dosens()->where('status', Dosen::PENDAMPING)->first();

        return view('pages.mahasiswa.pendaftaran.create', [
            'title' => 'Form Pendaftaran Tugas Akhir',
            'active' => 'pendaftaran',
            'dosen_utama' => $dosenUtama,
            'dosen_pendamping' => $dosenPendamping,
            'pengajuan' => $pengajuan,
        ]);
    }

    public function store(Request $request)
    {
        $mahasiswa = Mahasiswa::where('nim', Auth::guard('mahasiswa')->user()->nim)->first();
        $pengajuan = $mahasiswa->pengajuans()->where('status', Pengajuan::DITERIMA)->first();

        $pendaftaran_acc = Pendaftaran::where('pengajuan_id', $pengajuan->id)->where('status', Pendaftaran::DITERIMA)->first();

        if ($pendaftaran_acc) {
            return redirect('pendaftaran-mahasiswa')->with('warning', 'Anda sudah melakukan pendaftaran');
        } else {
            $validatedData = $request->validate([
                'nomor_pembayaran' => 'required',
                'tanggal_pembayaran' => 'required',
                'biaya' => 'required',
                'lampiran_1' => ['required', 'mimes:pdf'],
                'lampiran_2' => ['required', 'mimes:pdf'],
                'lampiran_3' => ['required', 'mimes:pdf'],
                'lampiran_4' => ['required', 'mimes:pdf,png,jpg,jpeg'],
                'lampiran_5' => ['required', 'mimes:pdf,png,jpg,jpeg'],
            ]);

            $validatedData['lampiran_1'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_1'), 'lampiran-pendaftaran');
            $validatedData['lampiran_2'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_2'), 'lampiran-pendaftaran');
            $validatedData['lampiran_3'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_3'), 'lampiran-pendaftaran');
            $validatedData['lampiran_4'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_4'), 'lampiran-pendaftaran');
            $validatedData['lampiran_5'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_5'), 'lampiran-pendaftaran');

            $validatedData['mahasiswa_id'] = Auth::guard('mahasiswa')->user()->id;
            $validatedData['pengajuan_id'] = $pengajuan->id;

            setlocale(LC_TIME, 'id');
            $tanggal_pembayaran = Carbon::parse($request->tanggal_pembayaran)->formatLocalized('%d %B %Y');
            $validatedData['tanggal_pembayaran'] = $tanggal_pembayaran;

            Pendaftaran::create($validatedData);
            return redirect('pendaftaran-mahasiswa')->with('success', 'Berhasil melakukan pendaftaran');
        }
    }

    public function edit($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        if ($pendaftaran->status == Pendaftaran::REVIEW ||  $pendaftaran->status == Pendaftaran::DITERIMA) {
            return back()->with('warning', 'Pendaftaran tidak bisa diedit');
        }
        $mahasiswa = Mahasiswa::where('id', $pendaftaran->mahasiswa_id)->first();
        $dosenUtama = $mahasiswa->dosens()->where('status', Dosen::UTAMA)->first();
        $dosenPendamping = $mahasiswa->dosens()->where('status', Dosen::PENDAMPING)->first();

        return view('pages.mahasiswa.pendaftaran.edit', [
            'title' => 'Form Edit Pendaftaran Tugas Akhir',
            'active' => 'pendaftaran',
            'dosen_utama' => $dosenUtama,
            'dosen_pendamping' => $dosenPendamping,
            'pendaftaran' => $pendaftaran,
            'mahasiswa' => $mahasiswa,
        ]);
    }

    public function pendaftaranReview($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        $mahasiswa = Mahasiswa::where('id', $pendaftaran->mahasiswa_id)->first();
        $dosenUtama = $mahasiswa->dosens()->where('status', Dosen::UTAMA)->first();
        $dosenPendamping = $mahasiswa->dosens()->where('status', Dosen::PENDAMPING)->first();

        return view('pages.admin.pendaftaran.review', [
            'title' => 'Review Pendaftaran Tugas Akhir',
            'active' => 'pendaftaran',
            'sidebar' => 'partials.sidebarAdmin',
            'dosen_utama' => $dosenUtama,
            'dosen_pendamping' => $dosenPendamping,
            'pendaftaran' => $pendaftaran,
            'mahasiswa' => $mahasiswa,
            'revisis' => $pendaftaran->revisis()->orderBy('created_at', 'desc')->paginate(5),
        ]);
    }

    public function pendaftaranDetail($id)
    {
        $pendaftaran = Pendaftaran::where('mahasiswa_id', Auth::guard('mahasiswa')->user()->id)->first();
        $mahasiswa = Mahasiswa::where('id', Auth::guard('mahasiswa')->user()->nim)->first();
        $dosenUtama = $mahasiswa->dosens()->where('status', Dosen::UTAMA)->first();
        $dosenPendamping = $mahasiswa->dosens()->where('status', Dosen::PENDAMPING)->first();

        if (!$pendaftaran) {
            return back()->with('warning', 'Pendaftaran tidak ditemukan');
        }

        return view('pages.mahasiswa.pendaftaran.detail', [
            'title' => 'Detail Pendaftaran Tugas Akhir',
            'active' => 'pendaftaran',
            'dosen_utama' => $dosenUtama,
            'dosen_pendamping' => $dosenPendamping,
            'pendaftaran' => $pendaftaran,
            'revisis' => $pendaftaran->revisis()->orderBy('created_at', 'desc')->paginate(5),
        ]);
    }

    public function update(Request $request)
    {
        $pendaftaran = Pendaftaran::findOrFail($request->id);
        $validatedData = $request->validate([
            'nomor_pembayaran' => 'required',
            'biaya' => 'required',
            'lampiran_1' => [Rule::requiredIf(function () {
                if (empty($this->request->lampiran_1)) {
                    return false;
                }
                return true;
            }), 'mimes:pdf'],
            'lampiran_2' => [Rule::requiredIf(function () {
                if (empty($this->request->lampiran_2)) {
                    return false;
                }
                return true;
            }), 'mimes:pdf'],
            'lampiran_3' => [Rule::requiredIf(function () {
                if (empty($this->request->lampiran_3)) {
                    return false;
                }
                return true;
            }), 'mimes:pdf'],
            'lampiran_4' => [Rule::requiredIf(function () {
                if (empty($this->request->lampiran_4)) {
                    return false;
                }
                return true;
            }), 'mimes:pdf,png,jpg,jpeg'],
            'lampiran_5' => [Rule::requiredIf(function () {
                if (empty($this->request->lampiran_5)) {
                    return false;
                }
                return true;
            }), 'mimes:pdf,png,jpg,jpeg'],
        ]);

        if ($request->file('lampiran_1')) {
            AppHelper::instance()->deleteLampiran($pendaftaran->lampiran_1);
            $validatedData['lampiran_1'] = AppHelper::instance()->uploadLampiran($request->lampiran_1, 'lampiran-pendaftaran');
        }
        if ($request->file('lampiran_2')) {
            AppHelper::instance()->deleteLampiran($pendaftaran->lampiran_2);
            $validatedData['lampiran_2'] = AppHelper::instance()->uploadLampiran($request->lampiran_2, 'lampiran-pendaftaran');
        }
        if ($request->file('lampiran_3')) {
            AppHelper::instance()->deleteLampiran($pendaftaran->lampiran_3);
            $validatedData['lampiran_3'] = AppHelper::instance()->uploadLampiran($request->lampiran_3, 'lampiran-pendaftaran');
        }
        if ($request->file('lampiran_4')) {
            AppHelper::instance()->deleteLampiran($pendaftaran->lampiran_4);
            $validatedData['lampiran_4'] = AppHelper::instance()->uploadLampiran($request->lampiran_4, 'lampiran-pendaftaran');
        }
        if ($request->file('lampiran_5')) {
            AppHelper::instance()->deleteLampiran($pendaftaran->lampiran_5);
            $validatedData['lampiran_5'] = AppHelper::instance()->uploadLampiran($request->lampiran_5, 'lampiran-pendaftaran');
        }

        $validatedData['mahasiswa_id'] = Auth::guard('mahasiswa')->user()->id;
        $validatedData['judul'] = $pendaftaran->judul;
        $validatedData['status'] = Pendaftaran::REVIEW;

        if ($request->tanggal_pembayaran) {
            setlocale(LC_TIME, 'id');
            $tanggal_pembayaran = Carbon::parse($request->tanggal_pembayaran)->formatLocalized('%d %B %Y');
            $validatedData['tanggal_pembayaran'] = $tanggal_pembayaran;
        }

        $pendaftaran->update($validatedData);
        return redirect('pendaftaran-mahasiswa')->with('success', 'Pendaftaran berhasil diupdate');
    }

    public function delete(Request $request)
    {
        $pendaftaran = Pendaftaran::findOrFail($request->id);
        if ($pendaftaran->status == Pendaftaran::DITERIMA) {
            return back()->with('error', 'Pendaftaran gagal dihapus');
        } else {
            AppHelper::instance()->deleteLampiran($pendaftaran->lampiran_2);
            AppHelper::instance()->deleteLampiran($pendaftaran->lampiran_3);
            AppHelper::instance()->deleteLampiran($pendaftaran->lampiran_4);
            AppHelper::instance()->deleteLampiran($pendaftaran->lampiran_5);
            $pendaftaran->delete();
            return back()->with('success', 'Pendaftaran berhasil dihapus');
        }
    }

    public function accPendaftaran(Request $request)
    {
        $pendaftaran = Pendaftaran::findOrFail($request->id);

        $mahasiswa = Mahasiswa::where('id', $pendaftaran->mahasiswa_id)->first();
        $dosenUtama = $mahasiswa->dosens()->where('status', Dosen::UTAMA)->first();
        $dosenPendamping = $mahasiswa->dosens()->where('status', Dosen::PENDAMPING)->first();

        $pendaftaran_disabled = Pendaftaran::where('mahasiswa_id', $mahasiswa->id)->where('status', Pendaftaran::DISABLED)->first();

        $prodi = Prodi::where('namaprodi', $mahasiswa->prodi)->first();

        if (count($prodi->bagians) == 0) {
            return back()->with('warning', 'Bagian bimbingan untuk prodi' . $mahasiswa->prodi . ' masih kosong');
        } elseif ($pendaftaran->status == Pendaftaran::DITERIMA) {
            return back()->with('warning', 'Pendaftaran sudah diacc');
        } else {
            $pendaftaran->update([
                'status' => Pendaftaran::DITERIMA,
                'tanggal_acc' => now(),
            ]);

            if (!$pendaftaran_disabled) {
                // Otomatis create bimbingan dengan pembimbing dosen utam
                foreach ($prodi->bagians as $bagian) {
                    $bimbingan = Bimbingan::create([
                        'mahasiswa_id' => $mahasiswa->id,
                        'bagian_id' => $bagian->id,
                        'pembimbing' => Dosen::UTAMA,
                    ]);
                    $bimbingan->dosens()->attach([$dosenUtama->id]);
                }

                // Otomatis create bimbingan dengan pembimbing dosen pendamping
                foreach ($prodi->bagians as $bagian) {
                    $bimbingan = Bimbingan::create([
                        'mahasiswa_id' => $mahasiswa->id,
                        'bagian_id' => $bagian->id,
                        'pembimbing' => Dosen::PENDAMPING,
                    ]);
                    $bimbingan->dosens()->attach([$dosenPendamping->id]);
                }
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
            return redirect('pendaftaran/review/' . $pendaftaran->id)->with('success', 'Acc pendaftaran berhasil dibatalkan');
        }
    }

    public function revisiPendaftaran(Request $request)
    {
        $pendaftaran = Pendaftaran::findOrFail($request->id);
        $revisi = new RevisiPendaftaran;
        $revisi->catatan = $request->catatan;
        $request->validate([
            'lampiran' => [Rule::requiredIf(function () {
                if (empty($this->request->lampiran)) {
                    return false;
                }
                return true;
            }), 'mimes:pdf,docx']
        ]);
        if ($request->file('lampiran')) {
            $revisi->lampiran = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampiran-revisi');
        }
        if ($pendaftaran->status == Pendaftaran::REVIEW) {
            $pendaftaran->update([
                'status' => Pendaftaran::REVISI,
            ]);
            $pendaftaran->revisis()->save($revisi);
            return redirect('pendaftarans')->with('success', 'Pendaftaran berhasil direvisi');
        } elseif ($pendaftaran->status == Pendaftaran::REVISI) {
            $pendaftaran->revisis()->save($revisi);
            return back()->with('success', 'Revisi berhasil ditambahkan');
        }
    }

    public function deleteRevisiPendaftaran(Request $request)
    {
        $revisi = RevisiPendaftaran::findOrFail($request->id);
        AppHelper::instance()->deleteLampiran($revisi->lampiran);
        $revisi->delete();
        return back()->with('success', 'Revisi berhasil dihapus');
    }

    public function disablePendaftaran($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        $pendaftaran->update([
            'status' => Pendaftaran::DISABLED,
        ]);

        return redirect('pendaftaran/create');
    }
}
