@extends('kp.layouts.dashboard')

@section('content')
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6"></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Pengaturan</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title }}</h3>
                        </div>
                        <div class="card-body">

                            {{-- Foto Profil Preview --}}
                            <div class="text-center mb-4">
                                <img id="preview-foto"
                                     src="{{ $himpunan->foto_profil_url }}"
                                     class="img-circle elevation-2"
                                     alt="Foto Profil"
                                     style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #dee2e6;">
                                <div class="mt-2">
                                    <small class="text-muted">Foto Profil Himpunan</small>
                                </div>
                            </div>

                            <form action="{{ route('kp.himpunan.account.update', $himpunan->id) }}"
                                  method="post"
                                  enctype="multipart/form-data"
                                  onsubmit="return confirm('Yakin ingin menyimpan perubahan akun?')">
                                @method('PUT')
                                @csrf

                                {{-- Upload Foto Profil --}}
                                <div class="form-group">
                                    <label for="foto_profil">Foto Profil</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file"
                                                   id="foto_profil"
                                                   class="custom-file-input @error('foto_profil') is-invalid @enderror"
                                                   name="foto_profil"
                                                   accept="image/jpg,image/jpeg,image/png"
                                                   onchange="previewFoto(this)">
                                            <label class="custom-file-label" for="foto_profil">Pilih foto...</label>
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mt-1">Format: JPG, JPEG, PNG. Maksimal 2MB. Kosongkan jika tidak ingin mengganti foto.</small>
                                    @error('foto_profil')
                                        <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" id="username" class="form-control" value="{{ $himpunan->username }}" disabled>
                                    <small class="text-muted d-block mt-2">Username login tetap menggunakan username yang sudah terdaftar.</small>
                                </div>

                                <div class="form-group">
                                    <label for="nama">Nama Himpunan</label>
                                    <input type="text" id="nama" class="form-control @error('nama') is-invalid @enderror" name="nama" value="{{ old('nama', $himpunan->nama) }}" required>
                                    @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $himpunan->email) }}" placeholder="contoh@domain.com">
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="password">Password Baru</label>
                                    <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
                                    <small class="text-muted d-block mt-2">Kosongkan jika tidak ingin mengganti password.</small>
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="password_confirmation">Konfirmasi Password Baru</label>
                                    <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" autocomplete="new-password">
                                </div>

                                <div class="mt-3">
                                    <button class="btn btn-success" type="submit">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewFoto(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-foto').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);

                // Update label custom file input
                const fileName = input.files[0].name;
                input.nextElementSibling.textContent = fileName;
            }
        }
    </script>
@endsection
