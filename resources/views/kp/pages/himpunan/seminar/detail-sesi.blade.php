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
                        <li class="breadcrumb-item"><a href="{{ route('kp.jadwal.himpunan') }}">Penjadwalan</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <!-- Info Sesi -->
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Informasi Sesi</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td><i class="fas fa-calendar mr-2"></i>Tanggal</td>
                                    <td>: <strong>{{ $sesi->tanggal->translatedFormat('l, d F Y') }}</strong></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-clock mr-2"></i>Waktu</td>
                                    <td>: <strong>{{ \Carbon\Carbon::parse($sesi->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($sesi->jam_selesai)->format('H:i') }} WIB</strong></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-map-marker-alt mr-2"></i>Tempat</td>
                                    <td>: <strong>{{ $sesi->tempat }}</strong></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-user-tie mr-2"></i>Penguji</td>
                                    <td>: <strong>{{ $sesi->dosenPenguji ? $sesi->dosenPenguji->nama . ', ' . $sesi->dosenPenguji->gelar : '-' }}</strong></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-users mr-2"></i>Peserta</td>
                                    <td>: <strong>{{ count($sesi->seminars) }} mahasiswa</strong></td>
                                </tr>
                            </table>
                            
                            @if($sesi->catatan_teknis)
                            <div class="alert alert-info mt-3">
                                <i class="fas fa-info-circle mr-1"></i> {{ $sesi->catatan_teknis }}
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Link Penilaian -->
                    <div class="card {{ $sesi->is_token_used ? 'card-secondary' : 'card-success' }} card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Link Penilaian Dosen</h3>
                        </div>
                        <div class="card-body">
                            @if($sesi->is_token_used)
                                <div class="alert alert-secondary">
                                    <i class="fas fa-check-circle mr-1"></i> Link sudah digunakan
                                    <br><small>{{ $sesi->token_used_at->translatedFormat('d F Y, H:i') }}</small>
                                </div>
                                
                                {{-- Tombol Validasi Selesai Seminar --}}
                                @php
                                    $adaMenungguValidasi = false;
                                    $allSelesai = true;
                                    foreach($sesi->seminars as $seminar) {
                                        if($seminar->status_seminar == 'menunggu_validasi_himpunan') {
                                            $adaMenungguValidasi = true;
                                            $allSelesai = false;
                                        }
                                        if($seminar->status_seminar != 'selesai_seminar' && $seminar->status_seminar != 'selesai') {
                                            $allSelesai = false;
                                        }
                                    }
                                @endphp
                                
                                @if($adaMenungguValidasi)
                                <div class="alert alert-warning mb-2">
                                    <i class="fas fa-exclamation-triangle mr-1"></i> Ada seminar yang menunggu validasi
                                </div>
                                <form action="{{ route('kp.jadwal.himpunan.validasi.selesai') }}" method="POST" id="formValidasiSelesai">
                                    @csrf
                                    <input type="hidden" name="sesi_id" value="{{ $sesi->id }}">
                                    <button type="submit" class="btn btn-success btn-block">
                                        <i class="fas fa-check-double mr-1"></i> Validasi Seminar Selesai
                                    </button>
                                </form>
                                <small class="text-muted">Dosen penguji sudah input nilai, klik untuk validasi</small>
                                @elseif($allSelesai)
                                <div class="alert alert-success">
                                    <i class="fas fa-check-double mr-1"></i> Semua seminar sudah divalidasi selesai
                                </div>
                                @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle mr-1"></i> Menunggu penilaian dari dosen penguji
                                </div>
                                @endif
                            @else
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" value="{{ $sesi->link_penilaian }}" id="linkPenilaian" readonly>
                                    <div class="input-group-append">
                                        <button class="btn btn-success btn-sm" onclick="copyLink()">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <small class="text-muted">Kirim link ini ke dosen penguji</small>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <!-- Daftar Peserta -->
                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Daftar Peserta Seminar</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="60">Urutan</th>
                                        <th>NIM</th>
                                        <th>Nama</th>
                                        <th>Judul</th>
                                        <th>Nilai</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sesi->seminars->sortBy('urutan_presentasi') as $seminar)
                                    <tr>
                                        <td>
                                            <span class="badge badge-primary" style="font-size: 1rem;">{{ $seminar->urutan_presentasi }}</span>
                                        </td>
                                        <td>{{ $seminar->mahasiswa->nim }}</td>
                                        <td>{{ $seminar->mahasiswa->nama }}</td>
                                        <td>{{ Str::limit($seminar->judul_laporan ?? $seminar->pengajuan->judul, 40) }}</td>
                                        <td>
                                            @if($seminar->nilai_seminar)
                                                <strong class="text-success">{{ $seminar->nilai_seminar }}</strong>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @switch($seminar->status_seminar)
                                                @case('dijadwalkan')
                                                    <span class="badge bg-info">Dijadwalkan</span>
                                                    @break
                                                @case('menunggu_validasi_himpunan')
                                                    <span class="badge bg-warning">Menunggu Validasi</span>
                                                    @break
                                                @case('selesai_seminar')
                                                    <span class="badge bg-success">Selesai</span>
                                                    @break
                                                @case('revisi_pasca')
                                                    <span class="badge bg-warning">Revisi</span>
                                                    @break
                                                @case('revisi_disetujui')
                                                    <span class="badge bg-primary">Revisi OK</span>
                                                    @break
                                                @case('selesai')
                                                    <span class="badge bg-success">Selesai KP</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $seminar->status_label }}</span>
                                            @endswitch
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Copy link penilaian
        function copyLink() {
            var link = document.getElementById('linkPenilaian');
            link.select();
            document.execCommand('copy');
            $(document).Toasts('create', {
                class: 'bg-success mt-5 mr-3',
                title: 'Berhasil',
                autohide: true,
                delay: 3000,
                body: 'Link berhasil disalin!'
            });
        }

        // Validasi form selesai seminar
        $(document).ready(function() {
            $('#formValidasiSelesai').on('submit', function(e) {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Validasi Seminar Selesai?',
                    html: 'Seminar akan divalidasi selesai.<br>Mahasiswa dapat melanjutkan ke tahap berikutnya.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-check mr-1"></i> Ya, Validasi',
                    cancelButtonText: '<i class="fas fa-times mr-1"></i> Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Memproses...',
                            html: 'Mohon tunggu sebentar',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // Submit form
                        this.submit();
                    }
                });
            });
        });
    </script>
@endsection




