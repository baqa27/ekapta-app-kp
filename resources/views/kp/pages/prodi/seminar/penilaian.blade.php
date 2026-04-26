@extends('kp.layouts.dashboard')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/kp/prodi/dashboard">Home</a></li>
                        <li class="breadcrumb-item"><a href="/kp/prodi/seminar">Seminar KP</a></li>
                        <li class="breadcrumb-item active">Penilaian</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <!-- Info Mahasiswa -->
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title">Informasi Mahasiswa</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th width="150">NIM</th>
                                    <td>{{ $seminar->mahasiswa->nim }}</td>
                                </tr>
                                <tr>
                                    <th>Nama</th>
                                    <td>{{ $seminar->mahasiswa->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Prodi</th>
                                    <td>{{ $seminar->mahasiswa->prodi->nama ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th width="150">Judul KP</th>
                                    <td>{{ $seminar->pengajuan->judul ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Instansi</th>
                                    <td>{{ $seminar->pengajuan->instansi ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Seminar</th>
                                    <td>{{ $seminar->tanggal_ujian ? \Carbon\Carbon::parse($seminar->tanggal_ujian)->format('d M Y H:i') : '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Penilaian KP (3 Komponen) -->
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">Penilaian Seminar KP</h3>
                </div>
                <div class="card-body">
                    <div class="form-group mb-4">
                        <label for="is_lulus">Status Kelulusan</label>
                        <select class="form-control" name="is_lulus" id="is_lulus">
                            <option value="">-- pilih --</option>
                            <option value="1" {{ $seminar->is_lulus == 1 ? 'selected' : '' }}>Lulus</option>
                            <option value="0" {{ $seminar->is_lulus == 0 ? 'selected' : '' }}>Tidak Lulus</option>
                        </select>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Petunjuk:</strong> Input nilai langsung untuk setiap komponen (0-100). Nilai akan otomatis tersimpan.
                    </div>

                    <table class="table table-bordered">
                        <thead class="bg-light">
                            <tr>
                                <th width="60%">Komponen Penilaian</th>
                                <th width="40%">Nilai (0-100)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Nilai Dosen Pembimbing</strong></td>
                                <td>
                                    <input type="number" 
                                           class="form-control nilai-komponen-input" 
                                           name="nilai_pembimbing"
                                           data-seminar-id="{{ $seminar->id }}"
                                           value="{{ $seminar->nilai_pembimbing ?? '' }}"
                                           min="0" 
                                           max="100"
                                           step="0.01"
                                           placeholder="0-100">
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Nilai Dosen Penguji</strong></td>
                                <td>
                                    <input type="number" 
                                           class="form-control nilai-komponen-input" 
                                           name="nilai_penguji"
                                           data-seminar-id="{{ $seminar->id }}"
                                           value="{{ $seminar->nilai_penguji ?? '' }}"
                                           min="0" 
                                           max="100"
                                           step="0.01"
                                           placeholder="0-100">
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Nilai Instansi</strong></td>
                                <td>
                                    <input type="number" 
                                           class="form-control nilai-komponen-input" 
                                           name="nilai_instansi"
                                           data-seminar-id="{{ $seminar->id }}"
                                           value="{{ $seminar->nilai_instansi ?? '' }}"
                                           min="0" 
                                           max="100"
                                           step="0.01"
                                           placeholder="0-100">
                                    @if($seminar->is_nilai_instansi_manual)
                                        <small class="text-muted">
                                            <span class="badge badge-info">Input manual oleh prodi</span>
                                        </small>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Nilai Akhir -->
            <div class="card">
                <div class="card-header bg-success">
                    <h3 class="card-title">Nilai Akhir KP</h3>
                </div>
                <div class="card-body">
                    @if($presentase_nilai)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <p><strong>Bobot Penilaian:</strong></p>
                                <ul>
                                    <li>Nilai Instansi: {{ $presentase_nilai->bobot_instansi }}%</li>
                                    <li>Nilai Pembimbing: {{ $presentase_nilai->bobot_pembimbing }}%</li>
                                    <li>Nilai Penguji: {{ $presentase_nilai->bobot_penguji }}%</li>
                                </ul>
                            </div>
                        </div>
                    @endif

                    <table class="table table-bordered">
                        <tr bgcolor="#808080" class="text-white">
                            <th width="70%">NILAI AKHIR</th>
                            <th id="nilai-akhir" class="text-center" style="font-size: 24px;">{{ $nilai_akhir }}</th>
                        </tr>
                    </table>

                    @if(!$presentase_nilai)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Bobot penilaian belum diatur untuk prodi ini. Silahkan hubungi admin.
                        </div>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <a href="/kp/prodi/seminar" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Store original values
            $('.nilai-komponen-input').each(function() {
                $(this).data('original-value', $(this).val());
            });

            // Update status lulus
            $('#is_lulus').on('change', function() {
                var is_lulus = $(this).val();
                var seminar_id = {{ $seminar->id }};

                if (!is_lulus) return;

                $.ajax({
                    url: '/kp/prodi/seminar/penilaian/update-status-lulus',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        seminar_id: seminar_id,
                        is_lulus: is_lulus
                    },
                    success: function(response) {
                        $(document).Toasts('create', {
                            class: 'bg-success mt-5 mr-3',
                            title: 'Berhasil',
                            autohide: true,
                            delay: 2000,
                            body: response.message || 'Status berhasil diupdate'
                        });
                    },
                    error: function(xhr) {
                        $(document).Toasts('create', {
                            class: 'bg-danger mt-5 mr-3',
                            title: 'Error',
                            autohide: true,
                            delay: 3000,
                            body: 'Gagal mengupdate status'
                        });
                    }
                });
            });

            // Update nilai komponen (pembimbing, penguji, instansi)
            $('.nilai-komponen-input').on('blur change', function() {
                var $input = $(this);
                var seminarId = $input.data('seminar-id');
                var fieldName = $input.attr('name');
                var fieldValue = parseFloat($input.val());
                var originalValue = $input.data('original-value');

                // Skip jika kosong atau tidak berubah
                if (!fieldValue && fieldValue !== 0) return;
                if (fieldValue === parseFloat(originalValue)) return;

                // Validasi 0-100
                if (fieldValue < 0 || fieldValue > 100) {
                    $(document).Toasts('create', {
                        class: 'bg-warning mt-5 mr-3',
                        title: 'Peringatan',
                        autohide: true,
                        delay: 3000,
                        body: 'Nilai harus antara 0-100'
                    });
                    $input.val(originalValue);
                    return;
                }

                $input.prop('disabled', true);

                // Tentukan endpoint berdasarkan field
                var url = fieldName === 'nilai_instansi' 
                    ? '/kp/prodi/seminar/penilaian/update-nilai-instansi'
                    : '/kp/prodi/seminar/penilaian/update-nilai-komponen';

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        seminar_id: seminarId,
                        field_name: fieldName,
                        field_value: fieldValue
                    },
                    success: function(response) {
                        // Update nilai akhir
                        if (response.nilai_akhir !== undefined) {
                            $('#nilai-akhir').text(response.nilai_akhir);
                        }

                        $input.data('original-value', fieldValue);

                        $(document).Toasts('create', {
                            class: 'bg-success mt-5 mr-3',
                            title: 'Berhasil',
                            autohide: true,
                            delay: 2000,
                            body: response.message || 'Nilai berhasil disimpan'
                        });

                        // Reload untuk update tampilan
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        $input.val(originalValue);
                        
                        $(document).Toasts('create', {
                            class: 'bg-danger mt-5 mr-3',
                            title: 'Error',
                            autohide: true,
                            delay: 3000,
                            body: xhr.responseJSON?.message || 'Gagal menyimpan nilai'
                        });
                    },
                    complete: function() {
                        $input.prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endsection
