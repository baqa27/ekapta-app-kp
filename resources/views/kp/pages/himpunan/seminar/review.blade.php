@extends('kp.layouts.dashboard')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('kp.seminar.himpunan') }}">Seminar KP</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="ribbon-wrapper ribbon-lg">
                            <div class="ribbon
                                @if ($seminar->is_valid == 0) bg-secondary
                                @elseif ($seminar->is_valid == 1) bg-success
                                @elseif ($seminar->is_valid == 2) bg-warning
                                @endif">
                                @if ($seminar->is_valid == 0) Review
                                @elseif ($seminar->is_valid == 1) Diterima
                                @elseif ($seminar->is_valid == 2) Revisi
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="250">NIM</td>
                                    <td><b>{{ $seminar->mahasiswa->nim }}</b></td>
                                </tr>
                                <tr>
                                    <td>Nama Lengkap</td>
                                    <td><b>{{ $seminar->mahasiswa->nama }}</b></td>
                                </tr>
                                <tr>
                                    <td>Prodi</td>
                                    <td><b>{{ $seminar->mahasiswa->prodi }}</b></td>
                                </tr>
                                <tr>
                                    <td>Dosen Pembimbing</td>
                                    <td><b>
                                        @php
                                            $dosen_pembimbing = $seminar->mahasiswa->dosens()->where('status', 'pembimbing')->first();
                                            if (!$dosen_pembimbing) {
                                                $dosen_pembimbing = $seminar->mahasiswa->dosens()->where('status', 'utama')->first();
                                            }
                                        @endphp
                                        {{ $dosen_pembimbing ? $dosen_pembimbing->nama . ', ' . $dosen_pembimbing->gelar : '-' }}
                                    </b></td>
                                </tr>
                                <tr>
                                    <td>Judul Kerja Praktek</td>
                                    <td><b>{{ $seminar->pengajuan->judul }}</b></td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td><b>{{ $seminar->mahasiswa->email }}</b></td>
                                </tr>
                                <tr>
                                    <td>No. HP</td>
                                    <td><b>{{ $seminar->mahasiswa->hp }}</b></td>
                                </tr>
                            </table>
                            
                            <hr>
                            
                            <table class="table table-borderless">
                                <tr>
                                    <td width="250">File Laporan KP</td>
                                    <td>
                                        @if($seminar->file_laporan)
                                            <a href="{{ App\Helpers\AppHelper::instance()->storageUrl($seminar->file_laporan) }}" target="_blank">
                                                <i class="fas fa-paperclip"></i> {{ basename($seminar->file_laporan) }}
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Lembar Bimbingan</td>
                                    <td>
                                        @if($seminar->file_bimbingan)
                                            <a href="{{ App\Helpers\AppHelper::instance()->storageUrl($seminar->file_bimbingan) }}" target="_blank">
                                                <i class="fas fa-paperclip"></i> {{ basename($seminar->file_bimbingan) }}
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Bukti Pembayaran</td>
                                    <td>
                                        @if($seminar->bukti_bayar)
                                            <a href="{{ App\Helpers\AppHelper::instance()->storageUrl($seminar->bukti_bayar) }}" target="_blank">
                                                <i class="fas fa-paperclip"></i> {{ basename($seminar->bukti_bayar) }}
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Sertifikat Seminar KP 1</td>
                                    <td>
                                        @if($seminar->lampiran_1)
                                            <a href="{{ App\Helpers\AppHelper::instance()->storageUrl($seminar->lampiran_1) }}" target="_blank">
                                                <i class="fas fa-paperclip"></i> {{ basename($seminar->lampiran_1) }}
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Sertifikat Seminar KP 2</td>
                                    <td>
                                        @if($seminar->lampiran_2)
                                            <a href="{{ App\Helpers\AppHelper::instance()->storageUrl($seminar->lampiran_2) }}" target="_blank">
                                                <i class="fas fa-paperclip"></i> {{ basename($seminar->lampiran_2) }}
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($seminar->lampiran_3)
                                <tr>
                                    <td>Sertifikat Seminar KP 3</td>
                                    <td>
                                        <a href="{{ App\Helpers\AppHelper::instance()->storageUrl($seminar->lampiran_3) }}" target="_blank">
                                            <i class="fas fa-paperclip"></i> {{ basename($seminar->lampiran_3) }}
                                        </a>
                                    </td>
                                </tr>
                                @endif
                                @if($seminar->lampiran_4)
                                <tr>
                                    <td>Sertifikat Seminar KP 4</td>
                                    <td>
                                        <a href="{{ App\Helpers\AppHelper::instance()->storageUrl($seminar->lampiran_4) }}" target="_blank">
                                            <i class="fas fa-paperclip"></i> {{ basename($seminar->lampiran_4) }}
                                        </a>
                                    </td>
                                </tr>
                                @endif
                                @if($seminar->link_akses_produk)
                                <tr>
                                    <td>Link Produk KP</td>
                                    <td>
                                        <a href="{{ $seminar->link_akses_produk }}" target="_blank">
                                            <i class="fas fa-external-link-alt"></i> {{ $seminar->link_akses_produk }}
                                        </a>
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td>Jumlah Bayar</td>
                                    <td><b>Rp {{ number_format($seminar->jumlah_bayar ?? 0, 0, ',', '.') }}</b></td>
                                </tr>
                                <tr>
                                    <td>Tanggal Pendaftaran</td>
                                    <td><b>{{ $seminar->created_at->format('d M Y H:i') }}</b></td>
                                </tr>
                                <tr>
                                    <td>Tanggal Validasi</td>
                                    <td><b>{{ $seminar->tanggal_acc ? date('d M Y H:i', strtotime($seminar->tanggal_acc)) : '-' }}</b></td>
                                </tr>
                            </table>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex">
                                <a href="{{ route('kp.seminar.himpunan') }}" class="btn btn-secondary mr-2">
                                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                                </a>

                                @if ($seminar->is_valid == 0 || $seminar->is_valid == 2)
                                    <button type="button" class="btn btn-warning mr-2" data-toggle="modal" data-target="#modal-revisi">
                                        <i class="fas fa-edit mr-1"></i> Revisi Pendaftaran
                                    </button>
                                    
                                    <button type="button" class="btn btn-success mr-2" data-toggle="modal" data-target="#modal-acc">
                                        <i class="fas fa-check mr-1"></i> Acc Pendaftaran
                                    </button>
                                @endif

                                @if ($seminar->is_valid == 1)
                                    <button type="button" class="btn btn-danger mr-2" data-toggle="modal" data-target="#modal-batal-acc">
                                        <i class="fas fa-times mr-1"></i> Batal ACC
                                    </button>
                                    
                                    <a href="{{ route('kp.jadwal.himpunan') }}" class="btn btn-primary">
                                        <i class="fas fa-calendar-alt mr-1"></i> Lihat Penjadwalan
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Info Penilaian -->
                    @if ($seminar->is_lulus == 1)
                    <div class="card card-info card-outline mt-3">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i><strong>Informasi Penilaian</strong></h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-arrow-right mr-2"></i>
                                Input nilai KP dilakukan oleh <strong>Prodi</strong>. Silahkan hubungi prodi untuk input nilai.
                            </div>
                            
                            @if($seminar->nilai_instansi || $seminar->nilai_pembimbing || $seminar->nilai_penguji)
                            <hr>
                            <table class="table table-borderless mb-0">
                                @if($seminar->nilai_instansi)
                                <tr>
                                    <td width="200"><strong>Nilai Instansi</strong></td>
                                    <td>: <span class="text-success font-weight-bold">{{ number_format($seminar->nilai_instansi, 2) }}</span></td>
                                </tr>
                                @endif
                                @if($seminar->nilai_pembimbing)
                                <tr>
                                    <td><strong>Nilai Pembimbing</strong></td>
                                    <td>: <span class="text-success font-weight-bold">{{ number_format($seminar->nilai_pembimbing, 2) }}</span></td>
                                </tr>
                                @endif
                                @if($seminar->nilai_penguji)
                                <tr>
                                    <td><strong>Nilai Penguji</strong></td>
                                    <td>: <span class="text-success font-weight-bold">{{ number_format($seminar->nilai_penguji, 2) }}</span></td>
                                </tr>
                                @endif
                                @if($seminar->nilai_akhir)
                                <tr>
                                    <td><strong>Nilai Akhir</strong></td>
                                    <td>: <span class="text-primary font-weight-bold" style="font-size: 1.2rem;">{{ number_format($seminar->nilai_akhir, 2) }}</span></td>
                                </tr>
                                @endif
                            </table>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Riwayat Revisi -->
                    <div class="card card-primary card-outline mt-3">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Revisi</strong>
                                <span class="badge bg-danger rounded-pill ml-2">
                                    {{ count($seminar->revisis) }}
                                </span>
                            </h3>
                        </div>
                        <div class="card-body">
                            @forelse ($revisis as $revisi)
                            <div class="card bg-light mb-2">
                                <div class="card-header">
                                    <i class="fas fa-calendar mr-2"></i> {{ $revisi->created_at->format('d M Y H:i') }}
                                </div>
                                <div class="card-body">
                                    {!! nl2br($revisi->catatan) !!}
                                </div>
                            </div>
                            @empty
                            <p class="text-muted text-center">Belum ada revisi</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Revisi -->
    <div class="modal fade" id="modal-revisi">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('kp.seminar.himpunan.revisi') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $seminar->id }}">
                    <div class="modal-header">
                        <h4 class="modal-title">Revisi Pendaftaran</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Catatan</label>
                            <textarea name="catatan" class="form-control" rows="4" required placeholder="Catatan revisi"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-warning">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal ACC -->
    <div class="modal fade" id="modal-acc">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('kp.seminar.himpunan.acc') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $seminar->id }}">
                    <div class="modal-header">
                        <h4 class="modal-title">ACC Pendaftaran</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Catatan</label>
                            <textarea name="catatan" class="form-control" rows="4" placeholder="Catatan ACC (opsional)"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Batal ACC -->
    <div class="modal fade" id="modal-batal-acc">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('kp.seminar.himpunan.batal-acc') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $seminar->id }}">
                    <div class="modal-header">
                        <h4 class="modal-title">Batal ACC Pendaftaran</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p>Anda yakin ingin membatalkan ACC pendaftaran seminar ini?</p>
                        <p class="text-danger">Pendaftaran akan kembali ke status Review.</p>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-danger">Batalkan ACC</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection




