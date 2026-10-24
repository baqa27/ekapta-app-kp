@extends('kp.layouts.dashboardMahasiswa')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pengumpulan Akhir KP</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Pengumpulan Akhir KP</a></li>
                        <li class="breadcrumb-item active">Home</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container">

            @php
                $is_karyawan = \App\Helpers\AppHelper::isKaryawanKP(Auth::guard('mahasiswa')->user());
            @endphp

<div class="mb-3">
    <a href="https://drive.google.com/drive/folders/1k2jyiYRaPY96uoXkWVKzun3YasYgVWSJ?usp=sharing" class="btn btn-primary" target="_blank">
        <i class="fas fa-folder-open mr-2"></i> Dokumen Pendukung Kerja Praktek
    </a>
</div>

            @if (!$jilid || $jilid->isDraft())
                <a href="{{ route('kp.pengumpulan-akhir.create') }}" class="btn btn-primary mb-4">
                    <i class="fas fa-plus mr-2"></i> Submit Pengumpulan Akhir KP
                </a>
            @else
                @if ($jilid->status == 4)
                    <div class="alert alert-success py-2 px-3 mb-3">
                        <h6 class="mb-1"><i class="fas fa-check-circle mr-2"></i> KERJA PRAKTEK TELAH SELESAI</h6>
                        <small>
                            Seluruh tahapan Kerja Praktek telah selesai. Laporan dan dokumen terkait telah diserahkan serta diverifikasi oleh Perpustakaan.
                        </small>
                    </div>
                @elseif ($jilid->status == 3)
                    <div class="alert alert-primary py-2 px-3 mb-3">
                        <h6 class="mb-1"><i class="fas fa-check-circle mr-2"></i> SELAMAT, KERJA PRAKTEK TELAH SELESAI</h6>
                        <small>
                            Dokumen sudah di-ACC. Silakan jilid laporan di <strong>Fotokopi FASTIKOM UNSIQ</strong>.
                            Setelah dijilid, serahkan laporan, surat, dan dokumen terkait ke <strong>Perpustakaan</strong> untuk diselesaikan.
                        </small>
                    </div>
                @elseif ($jilid->status == 2)
                    <div class="alert alert-warning py-2 px-3 mb-3">
                        <h6 class="mb-1"><i class="fas fa-exclamation-triangle mr-2"></i> DOKUMEN PERLU REVISI</h6>
                        <small>Dokumen Anda perlu diperbaiki. Silakan submit ulang dengan mengklik tombol "Submit Revisi".</small>
                    </div>
                @elseif ($jilid->status == 1)
                    <div class="alert alert-secondary py-2 px-3 mb-3">
                        <small>
                            Dokumen Anda sedang direview oleh Admin. Tunggu konfirmasi melalui halaman ini.
                            <span class="text-muted d-block">Pastikan file upload Google Drive akses dibuat PUBLIC!</span>
                        </small>
                    </div>
                @endif
            @endif

            @if ($jilid && !$jilid->isDraft())
            @php
                $mahasiswa = Auth::guard('mahasiswa')->user()->loadMissing(['seminarKP.sesiSeminar.dosenPenguji', 'dosens']);
                $nilai_pembimbing = $mahasiswa->seminarKP->nilai_pembimbing ?? $jilid->nilai_pembimbing ?? 0;
                $nilai_penguji = $is_karyawan ? 0 : ($mahasiswa->seminarKP->nilai_seminar ?? $mahasiswa->seminarKP->nilai_penguji ?? 0);
                $nilai_instansi = $mahasiswa->seminarKP->nilai_instansi ?? 0;

                $prodi_obj = \App\Models\Prodi::where('kode', $mahasiswa->prodi)
                    ->orWhere('namaprodi', $mahasiswa->prodi)
                    ->first();
                $presentase_nilai = $prodi_obj && $prodi_obj->presentaseNilaiKP ? $prodi_obj->presentaseNilaiKP : null;
                if ($presentase_nilai) {
                    $bobot_pembimbing = $presentase_nilai->bobot_pembimbing;
                    $bobot_penguji = $is_karyawan ? 0 : $presentase_nilai->bobot_penguji;
                    $bobot_instansi = 100 - $bobot_pembimbing - $bobot_penguji;
                } else {
                    $bobot_pembimbing = 40;
                    $bobot_penguji = $is_karyawan ? 0 : 30;
                    $bobot_instansi = $is_karyawan ? 60 : 30;
                }
                $nilai_akhir = ($nilai_pembimbing * $bobot_pembimbing / 100)
                             + ($nilai_penguji * $bobot_penguji / 100)
                             + ($nilai_instansi * $bobot_instansi / 100);
            @endphp
            <div class="card card-success card-outline mb-2" style="border-width: 1px;">
                <div class="card-body py-1 px-3" style="background: #f4fdf4;">
                    <div class="row text-center g-1">
                        <div class="col-{{ $is_karyawan ? '4' : '3' }}">
                            <div class="text-xs text-muted">Pembimbing</div>
                            <div class="h6 mb-0 fw-bold">{{ $nilai_pembimbing > 0 ? number_format($nilai_pembimbing, 2) : '-' }}</div>
                        </div>
                        @if(!$is_karyawan)
                        <div class="col-3">
                            <div class="text-xs text-muted">Penguji</div>
                            <div class="h6 mb-0 fw-bold">{{ $nilai_penguji > 0 ? number_format($nilai_penguji, 2) : '-' }}</div>
                        </div>
                        @endif
                        <div class="col-{{ $is_karyawan ? '4' : '3' }}">
                            <div class="text-xs text-muted">Instansi</div>
                            <div class="h6 mb-0 fw-bold">{{ $nilai_instansi > 0 ? number_format($nilai_instansi, 2) : '-' }}</div>
                        </div>
                        <div class="col-{{ $is_karyawan ? '4' : '3' }}">
                            <div class="text-xs text-muted">Nilai Akhir KP</div>
                            <div class="h5 mb-0 fw-bold {{ $nilai_akhir > 0 ? 'text-success' : 'text-muted' }}">{{ number_format($nilai_akhir, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Pengumpulan Akhir KP Anda</h3>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul Kerja Praktek</th>
                                        <th>Tanggal Submit</th>
                                        <th>Status</th>
                                        <th>Nilai Akhir</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach ($jilids as $j)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>
                                                <a href="{{ route('kp.pengumpulan-akhir.detail.mahasiswa', $j->id) }}">
                                                    {{ $j->mahasiswa->pengajuansKP()->where('status', 'diterima')->first()->judul ?? '-' }}
                                                </a>
                                            </td>
                                            <td>{{ $j->created_at->format('d M Y H:i') }}</td>
                                            <td>
                                                @if ($j->status == 1)
                                                    <span class="badge bg-secondary">Review</span>
                                                @elseif ($j->status == 2)
                                                    <span class="badge bg-warning">Revisi</span>
                                                @elseif ($j->status == 3)
                                                    <span class="badge bg-primary">Valid</span>
                                                @elseif ($j->status == 4)
                                                    <span class="badge bg-success">Selesai</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $mhs = $j->mahasiswa;
                                                    $np = $mhs->seminarKP->nilai_pembimbing ?? $j->nilai_pembimbing ?? 0;
                                                    $nu = $is_karyawan ? 0 : ($mhs->seminarKP->nilai_seminar ?? $mhs->seminarKP->nilai_penguji ?? 0);
                                                    $ni = $mhs->seminarKP->nilai_instansi ?? 0;
                                                    $na = ($np * $bobot_pembimbing / 100) + ($nu * $bobot_penguji / 100) + ($ni * $bobot_instansi / 100);
                                                @endphp
                                                <span class="{{ $na > 0 ? 'text-success' : 'text-muted' }}"><b>{{ number_format($na, 2) }}</b></span>
                                            </td>
                                            <td>
                                                @if ($j->status == 1)
                                                    <a href="{{ route('kp.pengumpulan-akhir.detail.mahasiswa', $j->id) }}"
                                                        class="btn btn-primary btn-sm">
                                                        <i class="fas fa-info-circle mr-1"></i> Detail
                                                    </a>
                                                @elseif ($j->status == 2)
                                                    <a href="{{ route('kp.pengumpulan-akhir.detail.mahasiswa', $j->id) }}"
                                                        class="btn btn-primary btn-sm">
                                                        <i class="fas fa-info-circle mr-1"></i> Detail
                                                    </a>
                                                    <a href="{{ route('kp.pengumpulan-akhir.edit', $j->id) }}"
                                                        class="btn btn-success btn-sm shadow">
                                                        <i class="fas fa-upload mr-1"></i> Submit Revisi
                                                    </a>
                                                @elseif ($j->status == 3 || $j->status == 4)
                                                    <a href="{{ route('kp.pengumpulan-akhir.detail.mahasiswa', $j->id) }}"
                                                        class="btn btn-primary btn-sm">
                                                        <i class="fas fa-info-circle mr-1"></i> Detail
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                 <tr>
                                     <th>No</th>
                                     <th>Judul Kerja Praktek</th>
                                     <th>Tanggal Submit</th>
                                     <th>Status</th>
                                     <th>Nilai Akhir</th>
                                     <th>Aksi</th>
                                 </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection




