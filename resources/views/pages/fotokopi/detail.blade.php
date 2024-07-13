@extends($is_admin ? (Auth::guard('admin')->user()->type == \App\Models\Admin::TYPE_SUPER_ADMIN ? 'layouts.dashboard' : 'layouts.dashboardFotokopi') : 'layouts.dashboardMahasiswa')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{ $title }}</a></li>
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
            <div class="mb-3 d-flex">
                <div class="flex-shrink-1">
                    <a href="{{ route($is_admin ? 'jilid.index' : 'jilid.mahasiswa') }}"
                        class="btn btn-secondary float-end"><i class="bi bi-arrow-left ml-2"></i> Kembali</a>
                </div>
                <h4 class="flex-grow-0"></h4>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="p-3 rounded border">
                                <table>
                                    <tr>
                                        <td>NIM/NAMA MAHASISWA</td>
                                        <td>: <b>{{ $mahasiswa->nim . '/' . $mahasiswa->nama }}</b></td>
                                    </tr>
                                    <tr>
                                        <td>PRODI</td>
                                        <td>: <b>{{ $prodi ? $prodi->namaprodi : '' }}</b></td>
                                    </tr>
                                    <tr>
                                        <td>JUDUL TUGAS AKHIR</td>
                                        <td>:
                                            <b>{{ $jilid->mahasiswa->pengajuans()->where('status', 'diterima')->first()->judul }}</b>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            @if ($is_admin)
                                @if ($jilid->status == 1)
                                    <div class="mt-3">
                                        <a href="{{ asset($jilid->lembar_keaslian) }}" class="btn btn-primary mb-3"
                                            target="_blank"><i class="fas fa-download"></i> LEMBAR KEASLIAN</a>
                                        <a href="{{ asset($jilid->lembar_persetujuan_pembimbing) }}"
                                            class="btn btn-primary mb-3" target="_blank"><i class="fas fa-download"></i>
                                            LEMBAR
                                            PERSETUJUAN PEMBIMBING</a>
                                        <a href="{{ asset($jilid->lembar_persetujuan_penguji) }}"
                                            class="btn btn-primary mb-3" target="_blank"><i class="fas fa-download"></i>
                                            LEMBAR PERSETUJUAN PENGUJI</a>
                                        <a href="{{ asset($jilid->lembar_pengesahan) }}" class="btn btn-primary mb-3"
                                            target="_blank"><i class="fas fa-download"></i> LEMBAR PENGESAHAN</a>
                                        <a href="{{ asset($jilid->lembar_bimbingan) }}" class="btn btn-primary mb-3"
                                            target="_blank"><i class="fas fa-download"></i> LEMBAR BIMBINGAN</a>
                                        <a href="{{ asset($jilid->lembar_revisi) }}" class="btn btn-primary mb-3"
                                            target="_blank"><i class="fas fa-download"></i> LEMBAR REVISI</a>
                                        <a href="{{ asset($jilid->laporan_pdf) }}" class="btn btn-primary mb-3"
                                            target="_blank"><i class="fas fa-download"></i> LAPORAN FORMAT PDF</a>
                                        <a href="{{ asset($jilid->laporan_word) }}" class="btn btn-primary mb-3"
                                            target="_blank"><i class="fas fa-download"></i> LAPORAN FORMAT WORD</a>
                                        <a href="{{ asset($jilid->artikel) }}" class="btn btn-primary mb-3"
                                            target="_blank"><i class="fas fa-download"></i> ARTIKEL FORMAT WORD</a>
                                        @if ($jilid->berita_acara)
                                            <a href="{{ asset($jilid->berita_acara) }}" class="btn btn-primary mb-3"
                                                target="_blank"><i class="fas fa-download"></i> BERITA ACARA</a>
                                        @endif
                                        @if ($jilid->link_project)
                                            <a href="{{ asset($jilid->link_project) }}" class="btn btn-secondary mb-3"
                                                target="_blank"><i class="fas fa-paper-plane"></i> LINK PROJECT</a>
                                        @endif
                                    </div>
                                @elseif ($jilid->status == 3)
                                    <a href="{{ asset($jilid->laporan_pdf) }}" class="btn btn-primary mb-3 mt-4"
                                        target="_blank"><i class="fas fa-download"></i> LAPORAN PDF</a>
                                @endif

                                <form action="{{ route('jilid.acc', $jilid->id) }}" method="post">
                                    @method('put')
                                    @csrf
                                    @if ($jilid->status == 3)
                                        <input type="hidden" name="status" value="4">
                                        <div class="mt-4">
                                            <label>JUMLAH PEMBAYARAN</label>
                                            <input type="number" name="total_pembayaran" class="form-control"
                                                placeholder="Nominal pembayaran penjilidan"
                                                value="{{ $jilid->total_pembayaran }}" required>
                                        </div>
                                    @elseif ($jilid->status == 1)
                                        <div class="form-group">
                                            <label for="">Status</label>
                                            <select name="status" class="form-control" required>
                                                <option value="">--pilih--</option>
                                                <option value="3">ACC</option>
                                                <option value="2">REVISI</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Catatan</label>
                                            <textarea name="catatan" class="form-control"></textarea>
                                        </div>
                                    @endif
                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-success"><i class="fas fa-check"></i>
                                            @if ($jilid->status == 1)
                                                SIMPAN
                                            @elseif ($jilid->status == 3)
                                                SELESAI
                                            @endif
                                        </button>
                                    </div>
                                </form>
                            @else
                                <div class="mt-3">
                                    <a href="{{ asset($jilid->lembar_keaslian) }}" class="btn btn-primary mb-3"
                                        target="_blank"><i class="fas fa-download"></i> LEMBAR KEASLIAN</a>
                                    <a href="{{ asset($jilid->lembar_persetujuan_pembimbing) }}"
                                        class="btn btn-primary mb-3" target="_blank"><i class="fas fa-download"></i>
                                        LEMBAR
                                        PERSETUJUAN PEMBIMBING</a>
                                    <a href="{{ asset($jilid->lembar_persetujuan_penguji) }}"
                                        class="btn btn-primary mb-3" target="_blank"><i class="fas fa-download"></i>
                                        LEMBAR PERSETUJUAN PENGUJI</a>
                                    <a href="{{ asset($jilid->lembar_pengesahan) }}" class="btn btn-primary mb-3"
                                        target="_blank"><i class="fas fa-download"></i> LEMBAR PENGESAHAN</a>
                                    <a href="{{ asset($jilid->lembar_bimbingan) }}" class="btn btn-primary mb-3"
                                        target="_blank"><i class="fas fa-download"></i> LEMBAR BIMBINGAN</a>
                                    <a href="{{ asset($jilid->lembar_revisi) }}" class="btn btn-primary mb-3"
                                        target="_blank"><i class="fas fa-download"></i> LEMBAR REVISI</a>
                                    <a href="{{ asset($jilid->laporan_pdf) }}" class="btn btn-primary mb-3"
                                        target="_blank"><i class="fas fa-download"></i> LAPORAN FORMAT PDF</a>
                                    <a href="{{ asset($jilid->laporan_word) }}" class="btn btn-primary mb-3"
                                        target="_blank"><i class="fas fa-download"></i> LAPORAN FORMAT WORD</a>
                                    <a href="{{ asset($jilid->artikel) }}" class="btn btn-primary mb-3"
                                        target="_blank"><i class="fas fa-download"></i> ARTIKEL FORMAT WORD</a>
                                    @if ($jilid->berita_acara)
                                        <a href="{{ asset($jilid->berita_acara) }}" class="btn btn-primary mb-3"
                                            target="_blank"><i class="fas fa-download"></i> BERITA ACARA</a>
                                    @endif
                                    @if ($jilid->link_project)
                                        <a href="{{ asset($jilid->link_project) }}" class="btn btn-secondary mb-3"
                                            target="_blank"><i class="fas fa-paper-plane"></i> LINK PROJECT</a>
                                    @endif
                                </div>
                            @endif
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
