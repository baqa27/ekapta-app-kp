<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('ekapta') }}/adminLTE/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('ekapta') }}/adminLTE/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="{{ asset('ekapta') }}/adminLTE/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="shortcut icon" href="https://unsiq.ac.id/img/UNSIQ-bunder.ico" type="image/x-icon">
    <style>
        body { background: #f4f6f9; min-height: 100vh; }
        .card-mahasiswa { transition: all 0.3s; border-left: 4px solid #007bff; }
        .card-mahasiswa.dinilai { border-left-color: #28a745; background: #f8fff8; }
        .card-mahasiswa:hover { box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .badge-urutan { font-size: 1.2rem; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; }
        .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.875rem; }
    </style>
</head>
<body>
    <div class="container py-4">
        <!-- Header -->
        <div class="mb-4">
            <h2 class="mb-0"><i class="fas fa-clipboard-check mr-2 text-primary"></i>Penilaian Seminar Kerja Praktek</h2>
        </div>

        <!-- Info Sesi -->
        <div class="card card-primary card-outline mb-4">
            <div class="card-header">
                <h3 class="card-title"><strong>Informasi Sesi Seminar</strong></h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td width="150"><i class="fas fa-calendar mr-2 text-primary"></i>Tanggal</td>
                                <td>: <strong>{{ $sesi->tanggal->translatedFormat('l, d F Y') }}</strong></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-clock mr-2 text-primary"></i>Waktu</td>
                                <td>: <strong>{{ \Carbon\Carbon::parse($sesi->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($sesi->jam_selesai)->format('H:i') }} WIB</strong></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-map-marker-alt mr-2 text-primary"></i>Lokasi</td>
                                <td>: <strong>{{ $sesi->tempat }}</strong></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td width="150"><i class="fas fa-users mr-2 text-primary"></i>Jumlah Peserta</td>
                                <td>: <strong>{{ count($seminars) }} Mahasiswa</strong></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-user-tie mr-2 text-primary"></i>Penguji 1</td>
                                <td>: <strong>{{ $sesi->dosenPenguji->nama ?? '-' }}</strong></td>
                            </tr>
                            @if($sesi->has_penguji_2)
                            <tr>
                                <td><i class="fas fa-user-tie mr-2 text-primary"></i>Penguji 2</td>
                                <td>: <strong>{{ $sesi->dosenPenguji2->nama ?? '-' }}</strong></td>
                            </tr>
                            @endif
                        </table>
                        @if($sesi->catatan_teknis)
                        <div class="alert alert-info mb-0 mt-2">
                            <i class="fas fa-info-circle mr-1"></i> {{ $sesi->catatan_teknis }}
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Current Viewer Info -->
                <div class="mt-3 p-3 bg-light rounded">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Anda sedang menilai sebagai:</strong>
                            <span class="badge {{ $isPenguji1 ? 'bg-success' : 'bg-info' }} ml-2">
                                {{ $isPenguji1 ? 'Penguji 1' : 'Penguji 2' }}
                            </span>
                        </div>
                        <div class="text-muted font-italic">
                            Nama: {{ $dosenPenguji->nama ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Penilaian -->
        <form id="formPenilaian" enctype="multipart/form-data">
            <!-- Card Daftar Mahasiswa -->
            <div class="card card-primary card-outline mb-4">
                <div class="card-header">
                    <h3 class="card-title"><strong>Daftar Mahasiswa</strong></h3>
                </div>
                <div class="card-body">
@foreach($seminars as $index => $seminar)
                     <div class="card card-mahasiswa mb-3" id="card-{{ $seminar->id }}">
                         <div class="card-body">
                             <div class="row align-items-center">
                                 <div class="col-12 col-lg-4">
                                     <div class="d-flex align-items-center">
                                         <div class="mr-2">
                                             <span class="badge badge-primary badge-urutan rounded-circle">{{ $seminar->urutan_presentasi ?? ($index + 1) }}</span>
                                         </div>
                                         <div>
                                             <h5 class="mb-1">{{ $seminar->mahasiswa->nama }}</h5>
                                             <p class="text-muted mb-1">NIM: {{ $seminar->mahasiswa->nim }}</p>
                                             <small class="text-secondary">{{ Str::limit($seminar->judul_laporan ?? optional($seminar->pengajuan)->judul ?? '-', 60) }}</small>
                                         </div>
                                     </div>
                                 </div>

                                 <div class="col-12 col-md-6 col-lg-2">
                                     <div class="d-flex w-100">
                                         @if(!empty($seminar->file_laporan))
                                         <a href="{{ route('kp.penilaian.seminar.laporan', ['token' => $currentToken, 'seminarId' => $seminar->id]) }}" 
                                            target="_blank" 
                                            class="btn btn-sm btn-outline-primary mr-1 mb-1 w-50">
                                             <i class="fas fa-file-pdf mr-1"></i> Unduh Laporan
                                         </a>
                                         @else
                                         <span class="badge badge-light text-muted mr-1 mb-1 w-50">Laporan Belum Ada</span>
                                         @endif

                                         @if(!empty($seminar->link_akses_produk) && (Str::startsWith($seminar->link_akses_produk, 'http://') || Str::startsWith($seminar->link_akses_produk, 'https://')))
                                         <a href="{{ $seminar->link_akses_produk }}" 
                                            target="_blank" 
                                            rel="noopener noreferrer" 
                                            class="btn btn-sm btn-outline-info mr-1 mb-1 w-50">
                                             <i class="fas fa-external-link-alt mr-1"></i> Produk
                                         </a>
                                         @endif
                                     </div>
                                 </div>

                                 <div class="col-12 col-md-6 col-lg-2">
                                     @php
                                         $reviewByCurrent = null;
                                         $reviewByOther = null;
                                         
                                         foreach($seminar->reviews as $review) {
                                             if($review->dosen_id == $dosenPengujiId) {
                                                 $reviewByCurrent = $review;
                                             } else {
                                                 $reviewByOther = $review;
                                             }
                                         }
                                     @endphp
                                     
                                     @if($reviewByCurrent)
                                     <div class="alert alert-success alert-sm mb-1 p-2 w-100">
                                         <small><i class="fas fa-check-circle mr-1"></i> Anda sudah memberikan nilai: {{ number_format($reviewByCurrent->nilai_angka ?? 0, 2) }}</small>
                                     </div>
                                     @elseif($reviewByOther)
                                     <div class="alert alert-info alert-sm mb-1 p-2 w-100">
                                         <small><i class="fas fa-user-edit mr-1"></i> Penguji lain sudah memberikan nilai: {{ number_format($reviewByOther->nilai_angka ?? 0, 2) }}</small>
                                     </div>
                                     @else
                                     <div class="alert alert-warning alert-sm mb-1 p-2 w-100">
                                         <small><i class="fas fa-hourglass-half mr-1"></i> Belum ada nilai dari siapapun</small>
                                     </div>
                                     @endif
                                 </div>

                                 <div class="col-12 col-lg-4">
                                     <div class="row">
                                         <div class="col-12 col-sm-4">
                                             <label class="small text-muted d-block">Nilai (0-100)</label>
                                             <input type="number" 
                                                    name="penilaian[{{ $seminar->id }}][nilai]" 
                                                    class="form-control form-control-sm nilai-input" 
                                                    min="0" max="100" step="0.01"
                                                    data-id="{{ $seminar->id }}" 
                                                    placeholder="0-100"
                                                    value="{{ old('penilaian.'.$seminar->id.'.nilai', optional($reviewByCurrent)->nilai_angka ?? '') }}">
                                         </div>

                                         <div class="col-12 col-sm-8">
                                             <label class="small text-muted d-block">Catatan (opsional)</label>
                                             <input type="text" 
                                                    name="penilaian[{{ $seminar->id }}][catatan]" 
                                                    class="form-control form-control-sm" 
                                                    placeholder="Catatan..."
                                                    value="{{ old('penilaian.'.$seminar->id.'.catatan', optional($reviewByCurrent)->catatan_penguji ?? '') }}">
                                         </div>
                                     </div>

                                     <div class="mt-2 w-100">
                                         <span class="badge badge-secondary status-badge" id="badge-{{ $seminar->id }}">
                                             <i class="fas fa-hourglass-half"></i> Belum Dinilai
                                         </span>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
@endforeach
                </div>
            </div>

            <!-- Submit Button -->
            <div class="card card-primary card-outline">
                <div class="card-body text-center">
                    <p class="text-muted mb-3">
                        <i class="fas fa-exclamation-triangle text-warning mr-1"></i>
                        Catatan: Partial submit didukung. Mahasiswa yang tidak diberi nilai akan dilewati/skip. Setelah submit, link ini tidak bisa digunakan lagi.
                    </p>
                    <button type="submit" class="btn btn-success btn-lg px-5" id="btnSubmit">
                        <i class="fas fa-paper-plane mr-2"></i>Submit Semua Penilaian
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script src="{{ asset('ekapta') }}/adminLTE/plugins/jquery/jquery.min.js"></script>
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <script>
        $(document).ready(function() {
            bsCustomFileInput.init();

            // Update badge saat input berubah
            function updateBadge(id) {
                var nilai = $('input[name="penilaian['+id+'][nilai]"]').val();
                var badge = $('#badge-' + id);
                var card = $('#card-' + id);
                
                if (nilai && parseFloat(nilai) >= 0 && parseFloat(nilai) <= 100) {
                    badge.removeClass('badge-secondary badge-warning').addClass('badge-success');
                    badge.html('<i class="fas fa-check"></i> Nilai: ' + nilai);
                    card.addClass('dinilai');
                } else {
                    badge.removeClass('badge-success').addClass('badge-secondary');
                    badge.html('<i class="fas fa-hourglass-half"></i> Belum Dinilai');
                    card.removeClass('dinilai');
                }
            }

            $('.nilai-input').on('change keyup', function() {
                updateBadge($(this).data('id'));
            });

            // Submit form
            $('#formPenilaian').on('submit', function(e) {
                e.preventDefault();
                
                // Cek apakah ada minimal satu yang dinilai
                var adaYangDinilai = false;
                var belumDinilai = [];
                
                @foreach($seminars as $seminar)
                var nilai{{ $seminar->id }} = $('input[name="penilaian[{{ $seminar->id }}][nilai]"]').val();
                if (nilai{{ $seminar->id }} && parseFloat(nilai{{ $seminar->id }}) >= 0 && parseFloat(nilai{{ $seminar->id }}) <= 100) {
                    adaYangDinilai = true;
                } else {
                    belumDinilai.push('{{ $seminar->mahasiswa->nama }}');
                }
                @endforeach
                
                if (!adaYangDinilai) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Belum Ada Penilaian',
                        html: 'Anda belum memberikan nilai pada mahasiswa apapun. Beri nilai pada minimal satu mahasiswa sebelum submit.',
                    });
                    return;
                }
                
                // Jika ada yang skip, tanyakan konfirmasi
                if (belumDinilai.length > 0) {
                    Swal.fire({
                        icon: 'question',
                        title: 'Beberapa Mahasiswa Akan Di-Skip',
                        html: 'Mahasiswa berikut akan dilewati (tidak diberi nilai):<br><b>' + belumDinilai.join(', ') + '</b><br><br>Lanjutkan submit?',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Lanjutkan',
                        cancelButtonText: 'Batal, Edit Nilai'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            submitForm();
                        }
                    });
                } else {
                    // Semua sudah dinilai, langsung konfirmasi submit
                    Swal.fire({
                        title: 'Konfirmasi Submit',
                        text: 'Setelah submit, link ini tidak bisa digunakan lagi. Lanjutkan?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Submit',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            submitForm();
                        }
                    });
                }
            });
            
            function submitForm() {
                $('#btnSubmit').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...');
                
                var formData = new FormData($('#formPenilaian')[0]);
                
                $.ajax({
                    url: "{{ route('kp.penilaian.seminar.submit', $currentToken) }}",
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            allowOutsideClick: false
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan';
                        Swal.fire({ icon: 'error', title: 'Gagal', text: msg });
                        $('#btnSubmit').prop('disabled', false).html('<i class="fas fa-paper-plane mr-2"></i>Submit Semua Penilaian');
                    }
                });
            }
        });
    </script>
</body>
</html>