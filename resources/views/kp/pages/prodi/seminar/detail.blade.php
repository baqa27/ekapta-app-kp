@extends('kp.layouts.dashboard')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('kp.seminar.prodi') }}">Seminar KP</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container">
            <div class="mb-3">
                <a href="{{ route('kp.seminar.prodi') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
                @if($seminar->status_seminar == 'selesai_seminar' || $seminar->nilai_seminar)
                <a href="/kp/prodi/seminar/penilaian/{{ $seminar->id }}" class="btn btn-primary">
                    <i class="fas fa-calculator mr-2"></i> Input Penilaian Detail
                </a>
                @endif
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="ribbon-wrapper ribbon-lg">
                            <div class="ribbon
                                @if ($seminar->is_valid == 0) bg-secondary
                                @elseif ($seminar->is_valid == 2) bg-warning
                                @elseif ($seminar->is_valid == 1) bg-success @endif">
                                @if ($seminar->is_valid == 0)
                                    REVIEW
                                @elseif ($seminar->is_valid == 1)
                                    DITERIMA
                                @elseif ($seminar->is_valid == 2)
                                    REVISI
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>NIM</strong></div>
                                <div class="col-md-8">{{ $seminar->mahasiswa->nim }}</div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Nama Lengkap</strong></div>
                                <div class="col-md-8">{{ $seminar->mahasiswa->nama }}</div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Prodi</strong></div>
                                <div class="col-md-8">{{ $seminar->mahasiswa->prodi }}</div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Judul KP</strong></div>
                                <div class="col-md-8">{{ $seminar->pengajuan->judul ?? '-' }}</div>
                            </div>

                            @php
                                $dosen_pembimbing = $seminar->mahasiswa->dosens()->where('status', 'pembimbing')->first();
                                if (!$dosen_pembimbing) {
                                    $dosen_pembimbing = $seminar->mahasiswa->dosens()->where('status', 'utama')->first();
                                }
                                $dosen_penguji = $seminar->dosenPenguji ?? null;
                            @endphp

                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Dosen Pembimbing</strong></div>
                                <div class="col-md-8">{{ $dosen_pembimbing ? $dosen_pembimbing->nama . ', ' . $dosen_pembimbing->gelar : '-' }}</div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Dosen Penguji</strong></div>
                                <div class="col-md-8">{{ $dosen_penguji ? $dosen_penguji->nama . ', ' . $dosen_penguji->gelar : '-' }}</div>
                            </div>

                            <hr>

                            @if($seminar->file_laporan)
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>File Laporan KP</strong></div>
                                <div class="col-md-8">
                                    <a href="{{ storage_url($seminar->file_laporan) }}" target="_blank">
                                        <i class="fas fa-paperclip"></i> {{ basename($seminar->file_laporan) }}
                                    </a>
                                </div>
                            </div>
                            @endif

                            @if($seminar->file_bimbingan)
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Lembar Bimbingan</strong></div>
                                <div class="col-md-8">
                                    <a href="{{ storage_url($seminar->file_bimbingan) }}" target="_blank">
                                        <i class="fas fa-paperclip"></i> {{ basename($seminar->file_bimbingan) }}
                                    </a>
                                </div>
                            </div>
                            @endif

                            @if($seminar->bukti_bayar)
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Bukti Pembayaran</strong></div>
                                <div class="col-md-8">
                                    <a href="{{ storage_url($seminar->bukti_bayar) }}" target="_blank">
                                        <i class="fas fa-paperclip"></i> {{ basename($seminar->bukti_bayar) }}
                                    </a>
                                </div>
                            </div>
                            @endif

                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Tanggal Pendaftaran</strong></div>
                                <div class="col-md-8">{{ $seminar->created_at->format('d M Y H:i') }}</div>
                            </div>

                            @if($seminar->tanggal_acc)
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Tanggal Validasi</strong></div>
                                <div class="col-md-8"><span class="text-success">{{ date('d M Y H:i', strtotime($seminar->tanggal_acc)) }}</span></div>
                            </div>
                            @endif

                            @if($seminar->tanggal_ujian)
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Tanggal Seminar</strong></div>
                                <div class="col-md-8"><span class="text-primary">{{ $seminar->tanggal_ujian->format('d M Y H:i') }}</span></div>
                            </div>
                            @endif

                            @if($seminar->tempat_ujian)
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Tempat Seminar</strong></div>
                                <div class="col-md-8">{{ $seminar->tempat_ujian }}</div>
                            </div>
                            @endif

                            @if($seminar->nilai_seminar)
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Nilai Seminar</strong></div>
                                <div class="col-md-8"><span class="text-success font-weight-bold">{{ number_format($seminar->nilai_seminar, 2) }}</span></div>
                            </div>
                            @endif
                        </div>
                        <div class="card-footer">
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle mr-2"></i>
                                Pendaftaran seminar KP dikelola oleh <strong>Himpunan</strong>. Prodi hanya dapat melihat data.
                            </div>
                        </div>
                    </div>

                    {{-- Revisi --}}
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Revisi</strong>
                                <span class="badge bg-danger rounded-pill">{{ count($seminar->revisis) }}</span>
                            </h3>
                        </div>
                        <div class="card-body">
                            @forelse ($revisis as $revisi)
                                <div class="card bg-light mb-2">
                                    <div class="card-header">
                                        <i class="fas fa-calendar mr-2"></i>
                                        {{ $revisi->created_at->format('d M Y H:i') }}
                                    </div>
                                    <div class="card-body">
                                        {!! nl2br($revisi->catatan) !!}
                                    </div>
                                    @if ($revisi->lampiran)
                                        <div class="card-footer">
                                            Lampiran :
                                            <a href="{{ storage_url($revisi->lampiran) }}" class="ml-3" target="_blank">
                                                <i class="fas fa-paperclip"></i> {{ basename($revisi->lampiran) }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <p class="text-muted text-center">Belum ada revisi</p>
                            @endforelse
                        </div>
                        <div class="d-flex justify-content-center mb-3">
                            {{ $revisis->links() }}
                        </div>
                    </div>

                    {{-- Input Manual Penilaian KP --}}
                    @if($seminar->is_lulus == 1)
                    <div class="card card-success card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-star mr-2"></i><strong>Input Manual Nilai KP</strong></h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-2"></i>
                                <strong>Petunjuk:</strong> Input nilai untuk setiap komponen. Nilai akan otomatis tersimpan dan nilai akhir akan dihitung secara real-time.
                            </div>

                            @php
                                // Ambil nilai dari sumber yang benar
                                $nilai_instansi = $seminar->nilai_instansi ?? '';
                                
                                // Nilai pembimbing: cek di jilid_kps dulu, fallback ke seminar_kps
                                $jilid = $seminar->mahasiswa->jilidKP ?? null;
                                $nilai_pembimbing = $jilid && $jilid->nilai_pembimbing 
                                    ? $jilid->nilai_pembimbing 
                                    : ($seminar->nilai_pembimbing ?? '');
                                
                                // Nilai penguji: cek di nilai_seminar dulu (dari link penilaian), fallback ke nilai_penguji
                                $nilai_penguji = $seminar->nilai_seminar && $seminar->nilai_seminar > 0
                                    ? $seminar->nilai_seminar
                                    : ($seminar->nilai_penguji ?? '');
                                
                                // Cek apakah mahasiswa karyawan
                                $is_karyawan = \App\Helpers\AppHelper::isKaryawanKP($seminar->mahasiswa);
                            @endphp

                            @if($is_karyawan)
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <strong>Mahasiswa Kelas Karyawan:</strong> Penilaian hanya terdiri dari 2 komponen (Pembimbing + Instansi). Tidak ada nilai dosen penguji karena tidak ada seminar. Persentase mengikuti pengaturan admin.
                                </div>
                            @endif

                            {{-- Tabel Nilai KP --}}
                            <form id="form-nilai-kp" method="POST">
                                @csrf
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="bg-light">
                                            <tr>
                                                <th width="60%">Komponen Penilaian</th>
                                                <th width="40%">Nilai (0-100)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <strong>Nilai Instansi</strong>
                                                    <br><small class="text-muted">Mahasiswa upload bukti nilai instansi, prodi input manual di sini</small>
                                                    @if($seminar->file_nilai_instansi)
                                                    <br><a href="{{ storage_url($seminar->file_nilai_instansi) }}" target="_blank" class="text-primary">
                                                        <i class="fas fa-paperclip"></i> Lihat Bukti Nilai Instansi
                                                    </a>
                                                    @endif
                                                </td>
                                                <td>
                                                    <input type="number" 
                                                           class="form-control" 
                                                           name="nilai_instansi"
                                                           value="{{ $nilai_instansi }}"
                                                           min="0" 
                                                           max="100"
                                                           step="0.01"
                                                           placeholder="0-100"
                                                           required>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>Nilai Dosen Pembimbing</strong>
                                                    <br><small class="text-muted">
                                                        @if($nilai_pembimbing)
                                                            <span class="text-success"><i class="fas fa-check-circle"></i> Sudah diisi via sistem</span>
                                                        @else
                                                            Jika dosen pakai kertas, input manual di sini
                                                        @endif
                                                    </small>
                                                </td>
                                                <td>
                                                    <input type="number" 
                                                           class="form-control" 
                                                           name="nilai_pembimbing"
                                                           value="{{ $nilai_pembimbing }}"
                                                           min="0" 
                                                           max="100"
                                                           step="0.01"
                                                           placeholder="0-100"
                                                           required>
                                                </td>
                                            </tr>
                                            @if(!$is_karyawan)
                                            <tr>
                                                <td>
                                                    <strong>Nilai Dosen Penguji</strong>
                                                    <br><small class="text-muted">
                                                        @if($nilai_penguji)
                                                            <span class="text-success"><i class="fas fa-check-circle"></i> Sudah diisi via sistem ({{ number_format($nilai_penguji, 2) }})</span>
                                                        @else
                                                            Jika dosen pakai kertas, input manual di sini
                                                        @endif
                                                    </small>
                                                </td>
                                                <td>
                                                    <input type="number" 
                                                           class="form-control" 
                                                           name="nilai_penguji"
                                                           value="{{ $nilai_penguji }}"
                                                           min="0" 
                                                           max="100"
                                                           step="0.01"
                                                           placeholder="0-100"
                                                           required>
                                                </td>
                                            </tr>
                                            @endif
                                        </tbody>
                                        <tfoot class="bg-secondary text-white">
                                            <tr>
                                                <th>NILAI AKHIR</th>
                                                <th id="nilai-akhir-display">
                                                    @php
                                                        // Hitung nilai akhir preview
                                                        if($is_karyawan) {
                                                            // Karyawan: Pembimbing 40% + Instansi 60%
                                                            if($nilai_instansi && $nilai_pembimbing) {
                                                                $nilai_akhir_preview = ($nilai_pembimbing * 0.4) + ($nilai_instansi * 0.6);
                                                                echo number_format($nilai_akhir_preview, 2);
                                                            } else {
                                                                echo '-';
                                                            }
                                                        } else {
                                                            // Reguler: Pembimbing 40% + Penguji 30% + Instansi 30%
                                                            if($nilai_instansi && $nilai_pembimbing && $nilai_penguji) {
                                                                $nilai_akhir_preview = ($nilai_pembimbing * 0.4) + ($nilai_penguji * 0.3) + ($nilai_instansi * 0.3);
                                                                echo number_format($nilai_akhir_preview, 2);
                                                            } else {
                                                                echo '-';
                                                            }
                                                        }
                                                    @endphp
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="fas fa-save mr-2"></i> Simpan Perubahan Nilai
                                    </button>
                                </div>
                            </form>

                            <div class="alert alert-warning mt-3">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <strong>Catatan Bobot Penilaian:</strong>
                                <ul class="mb-0 mt-2">
                                    @if($is_karyawan)
                                        <li><strong>Kelas Karyawan:</strong> Penilaian terdiri dari 2 komponen (Pembimbing + Instansi). Persentase mengikuti pengaturan admin.</li>
                                    @else
                                        <li><strong>Reguler:</strong> Penilaian terdiri dari 3 komponen (Pembimbing + Penguji + Instansi). Persentase mengikuti pengaturan admin.</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="card card-warning card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-exclamation-triangle mr-2"></i><strong>Penilaian Belum Tersedia</strong></h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-info-circle mr-2"></i>
                                Form input nilai akan tersedia setelah seminar <strong>divalidasi selesai oleh Himpunan</strong>.
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        var isKaryawan = {{ $is_karyawan ? 'true' : 'false' }};
        
        // Handle form submit
        $('#form-nilai-kp').on('submit', function(e) {
            e.preventDefault();
            
            var $form = $(this);
            var $submitBtn = $form.find('button[type="submit"]');
            var originalBtnText = $submitBtn.html();
            
            // Validasi nilai
            var nilai_instansi = parseFloat($form.find('[name="nilai_instansi"]').val());
            var nilai_pembimbing = parseFloat($form.find('[name="nilai_pembimbing"]').val());
            var nilai_penguji = isKaryawan ? 0 : parseFloat($form.find('[name="nilai_penguji"]').val());
            
            if (isNaN(nilai_instansi) || isNaN(nilai_pembimbing)) {
                $(document).Toasts('create', {
                    class: 'bg-warning mt-5 mr-3',
                    title: 'Peringatan',
                    autohide: true,
                    delay: 3000,
                    body: 'Semua nilai harus diisi'
                });
                return;
            }
            
            if (!isKaryawan && isNaN(nilai_penguji)) {
                $(document).Toasts('create', {
                    class: 'bg-warning mt-5 mr-3',
                    title: 'Peringatan',
                    autohide: true,
                    delay: 3000,
                    body: 'Semua nilai harus diisi'
                });
                return;
            }
            
            if (nilai_instansi < 0 || nilai_instansi > 100 ||
                nilai_pembimbing < 0 || nilai_pembimbing > 100 ||
                (!isKaryawan && (nilai_penguji < 0 || nilai_penguji > 100))) {
                $(document).Toasts('create', {
                    class: 'bg-warning mt-5 mr-3',
                    title: 'Peringatan',
                    autohide: true,
                    delay: 3000,
                    body: 'Nilai harus antara 0-100'
                });
                return;
            }
            
            // Disable button dan tampilkan loading
            $submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...');
            
            // Prepare data
            var postData = {
                _token: '{{ csrf_token() }}',
                seminar_id: {{ $seminar->id }},
                nilai_instansi: nilai_instansi,
                nilai_pembimbing: nilai_pembimbing,
                is_karyawan: isKaryawan
            };
            
            // Tambahkan nilai penguji hanya untuk mahasiswa reguler
            if (!isKaryawan) {
                postData.nilai_penguji = nilai_penguji;
            }
            
            // Simpan semua nilai via AJAX - SINGLE REQUEST
            $.ajax({
                url: '{{ route("kp.review.seminar.update.nilai") }}',
                method: 'POST',
                data: postData,
                success: function(response) {
                    if (response.success && response.data && response.data.nilai_akhir) {
                        $('#nilai-akhir-display').text(response.data.nilai_akhir);
                    }
                    
                    $(document).Toasts('create', {
                        class: 'bg-success mt-5 mr-3',
                        title: 'Berhasil',
                        autohide: true,
                        delay: 3000,
                        body: 'Semua nilai berhasil disimpan dan notifikasi telah dikirim'
                    });
                    
                    // Reload halaman setelah 2 detik
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                },
                error: function(xhr) {
                    var errorMessage = 'Gagal menyimpan nilai';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                         // Combine errors
                        errorMessage += ': ' + JSON.stringify(xhr.responseJSON.errors);
                    }
                    
                    $(document).Toasts('create', {
                        class: 'bg-danger mt-5 mr-3',
                        title: 'Error',
                        autohide: true,
                        delay: 3000,
                        body: errorMessage
                    });
                },
                complete: function() {
                    $submitBtn.prop('disabled', false).html(originalBtnText);
                }
            });
        });
    });
</script>
@endsection




