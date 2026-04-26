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
                        <li class="breadcrumb-item"><a href="#">{{ $title }}</a></li>
                        <li class="breadcrumb-item active">Home</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <!-- Biaya Seminar -->
                    <div class="card card-warning card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="bi bi-credit-card mr-2"></i>Biaya Seminar KP</h3>
                        </div>
                        <form action="{{ route('kp.payment.himpunan.update') }}" method="post">
                            @csrf
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Pengaturan biaya seminar akan ditampilkan di form pendaftaran seminar mahasiswa.
                                </div>
                                
                                @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                                
                                <div class="form-group">
                                    <label>Biaya Seminar <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number" name="biaya_seminar" class="form-control @error('biaya_seminar') is-invalid @enderror" required min="0" value="{{ old('biaya_seminar', $himpunan->biaya_seminar ?? 25000) }}">
                                        @error('biaya_seminar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-warning"><i class="bi bi-save mr-2"></i>Simpan Biaya Seminar</button>
                            </div>
                        </form>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-wallet mr-2"></i>Metode Pembayaran</h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-light border">
                                <i class="fas fa-info-circle text-primary mr-2"></i>
                                Tambahkan metode pembayaran yang tersedia. Mahasiswa akan melihat pilihan ini saat mendaftar seminar.
                            </div>

                            <!-- Daftar Metode Pembayaran -->
                            <div id="metode-list" class="mb-3">
                                @php
                                    $metodes = $himpunan->metodePembayarans()->orderBy('urutan')->get();
                                @endphp
                                
                                @if($metodes->count() > 0)
                                    @foreach($metodes as $metode)
                                    <div class="card mb-2 metode-item" data-id="{{ $metode->id }}">
                                        <div class="card-body py-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span class="badge badge-{{ $metode->tipe == 'bank' ? 'primary' : 'success' }} mr-2">
                                                        {{ $metode->tipe == 'bank' ? 'Bank' : 'E-Wallet' }}
                                                    </span>
                                                    <strong>{{ $metode->nama }}</strong> - {{ $metode->nomor }} 
                                                    <small class="text-muted">a.n. {{ $metode->nama_pemilik }}</small>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-danger btn-delete-metode" data-id="{{ $metode->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="alert alert-warning" id="empty-warning">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        Belum ada metode pembayaran. Silakan tambahkan metode pembayaran.
                                    </div>
                                @endif
                            </div>

                            <!-- Form Tambah Metode -->
                            <div class="card card-light">
                                <div class="card-header py-2">
                                    <h6 class="card-title mb-0"><i class="fas fa-plus-circle mr-2"></i>Tambah Metode Pembayaran</h6>
                                </div>
                                <div class="card-body">
                                    <form id="form-add-metode">
                                        @csrf
                                        <div class="form-group">
                                            <label>Tipe <span class="text-danger">*</span></label>
                                            <select name="tipe" id="tipe" class="form-control" required>
                                                <option value="">-- Pilih Tipe --</option>
                                                <option value="bank">Bank</option>
                                                <option value="ewallet">E-Wallet</option>
                                            </select>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nama <span class="text-danger">*</span></label>
                                                    <input type="text" name="nama" id="nama" class="form-control" placeholder="Contoh: BNI, DANA, OVO" required>
                                                    <small class="text-muted">Nama bank atau e-wallet</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nomor <span class="text-danger">*</span></label>
                                                    <input type="text" name="nomor" id="nomor" class="form-control" placeholder="Contoh: 1234567890" required>
                                                    <small class="text-muted">Nomor rekening/e-wallet</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Nama Pemilik <span class="text-danger">*</span></label>
                                            <input type="text" name="nama_pemilik" id="nama_pemilik" class="form-control" placeholder="Contoh: HIMATIF UNSIQ" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-plus mr-2"></i>Tambah Metode Pembayaran
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preview -->
                <div class="col-md-4">
                    <div class="card card-secondary card-outline sticky-top" style="top: 20px;">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-eye mr-2"></i>Preview Mahasiswa</h3>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Info yang akan dilihat mahasiswa:</p>
                            <hr>
                            <p><strong>Biaya Seminar:</strong> <br>Rp {{ number_format($himpunan->biaya_seminar ?? 25000, 0, ',', '.') }}</p>
                            
                            <p><strong>Metode Pembayaran:</strong></p>
                            <ul class="pl-3" id="preview-list">
                                <li><strong>Cash</strong> - Di Sekre Himpunan</li>
                                @foreach($himpunan->metodePembayarans()->orderBy('urutan')->get() as $metode)
                                <li class="preview-item" data-id="{{ $metode->id }}">
                                    <strong>{{ $metode->nama }}</strong> - {{ $metode->nomor }} <br>
                                    <small class="text-muted">a.n. {{ $metode->nama_pemilik }}</small>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
    $(document).ready(function() {
        // CSRF Token - Setup untuk semua AJAX request
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Tambah Metode Pembayaran
        $('#form-add-metode').on('submit', function(e) {
            e.preventDefault();
            
            $.ajax({
                url: '{{ route("kp.payment.metode.store") }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        // Hapus warning jika ada
                        $('#empty-warning').remove();
                        
                        // Tambah ke list
                        const metode = response.data;
                        const badgeClass = metode.tipe == 'bank' ? 'primary' : 'success';
                        const badgeText = metode.tipe == 'bank' ? 'Bank' : 'E-Wallet';
                        
                        const html = `
                            <div class="card mb-2 metode-item" data-id="${metode.id}">
                                <div class="card-body py-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="badge badge-${badgeClass} mr-2">${badgeText}</span>
                                            <strong>${metode.nama}</strong> - ${metode.nomor} 
                                            <small class="text-muted">a.n. ${metode.nama_pemilik}</small>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-danger btn-delete-metode" data-id="${metode.id}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        $('#metode-list').append(html);
                        
                        // Tambah ke preview
                        const previewHtml = `
                            <li class="preview-item" data-id="${metode.id}">
                                <strong>${metode.nama}</strong> - ${metode.nomor} <br>
                                <small class="text-muted">a.n. ${metode.nama_pemilik}</small>
                            </li>
                        `;
                        $('#preview-list').append(previewHtml);
                        
                        // Reset form
                        $('#form-add-metode')[0].reset();
                        
                        // Toast success
                        $(document).Toasts('create', {
                            class: 'bg-success mt-5 mr-3',
                            title: 'Berhasil',
                            autohide: true,
                            delay: 3000,
                            body: response.message
                        });
                    }
                },
                error: function(xhr) {
                    $(document).Toasts('create', {
                        class: 'bg-danger mt-5 mr-3',
                        title: 'Error',
                        autohide: true,
                        delay: 3000,
                        body: xhr.responseJSON?.message || 'Gagal menambahkan metode pembayaran'
                    });
                }
            });
        });

        // Hapus Metode Pembayaran
        $(document).on('click', '.btn-delete-metode', function() {
            const id = $(this).data('id');
            
            Swal.fire({
                title: 'Hapus Metode Pembayaran?',
                text: 'Metode pembayaran ini akan dihapus dari daftar!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/kp/himpunan/payment/metode/${id}`,
                        method: 'DELETE',
                        success: function(response) {
                            if (response.success) {
                                // Hapus dari list
                                $(`.metode-item[data-id="${id}"]`).fadeOut(300, function() {
                                    $(this).remove();
                                });
                                
                                // Hapus dari preview
                                $(`.preview-item[data-id="${id}"]`).fadeOut(300, function() {
                                    $(this).remove();
                                });
                                
                                // Toast success
                                $(document).Toasts('create', {
                                    class: 'bg-success mt-5 mr-3',
                                    title: 'Berhasil',
                                    autohide: true,
                                    delay: 3000,
                                    body: response.message
                                });
                            }
                        },
                        error: function(xhr) {
                            $(document).Toasts('create', {
                                class: 'bg-danger mt-5 mr-3',
                                title: 'Error',
                                autohide: true,
                                delay: 3000,
                                body: xhr.responseJSON?.message || 'Gagal menghapus metode pembayaran'
                            });
                        }
                    });
                }
            });
        });
    });
    </script>
    @endpush
@endsection
