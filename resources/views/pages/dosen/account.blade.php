@extends('layouts.dashboard')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    {{-- <h1 class="m-0"> Hai! {{ Auth::guard('dosen')->user()->nama }}</h1> --}}
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Pengaturan</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container">

            <!-- Alur Ekapta -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title }}</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('dosen.account.update', $dosen->id) }}" method="post" enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
                                <div class="form-group">
                                    <label for="exampleInputEmail1">NIDN</label>
                                    <input type="text" class="form-control" value="{{ $dosen->nidn }}"
                                           disabled>
                                </div>

                                <div class="form-group">
                                    <label for="nama">Nama Dosen dan Gelar Depan</label>
                                    <input type="text" id="nama" class="form-control @error('nama') is-invalid @enderror"
                                           name="nama" value="{{ old('nama', $dosen->nama) }}" required>
                                    @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="gelar">Gelar Belakang</label>
                                    <input type="text" id="gelar" class="form-control @error('gelar') is-invalid @enderror"
                                           name="gelar" value="{{ old('gelar', $dosen->gelar) }}" required>
                                    @error('gelar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>								

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" class="form-control @error('email') is-invalid @enderror"
                                           name="email" value="{{ old('email', $dosen->email == '-' ? '' : $dosen->email) }}" placeholder="contoh@domain.com">
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="password">Password Baru</label>
                                    <input type="password" id="password" class="form-control @error('password') is-invalid @enderror"
                                           name="password" autocomplete="new-password">
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Kosongkan jika tidak ingin mengganti password.</small>
                                </div>

                                <div class="form-group">
                                    <label for="password_confirmation">Konfirmasi Password Baru</label>
                                    <input type="password" id="password_confirmation" class="form-control"
                                           name="password_confirmation" autocomplete="new-password">
                                </div>

                                <div class="form-group">
                                    <label for="ttd">Tandatangan</label>
                                    <div class="input-group mb-2">
                                        <div class="custom-file">
                                            <input type="file" id="ttd"
                                                   class="custom-file-input @error('ttd') is-invalid @enderror"
                                                   name="ttd" accept=".png,.jpg,.jpeg">
                                            <label class="custom-file-label" for="ttd">Choose file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Gambar</span>
                                        </div>
                                        @error('ttd')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="text-muted d-block">Format PNG/JPG/JPEG, maksimal 1 MB.</small>
                                    @if ($dosen->ttd)
                                        <div class="mt-3">
                                            <div class="mb-2 text-muted">Tandatangan saat ini:</div>
                                            <img src="{{ asset($dosen->ttd) }}" alt="Tandatangan Dosen"
                                                 style="max-width: 260px; max-height: 140px;" class="img-thumbnail">
                                        </div>
                                    @endif
                                </div>

                                <div class="mt-3">
                                    <button class="btn btn-success" type="submit" >Simpan</button>
                                </div>
                            </form>
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
