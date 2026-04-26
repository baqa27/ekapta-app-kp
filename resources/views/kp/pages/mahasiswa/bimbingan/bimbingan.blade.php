@extends('kp.layouts.dashboardMahasiswa')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Bimbingan Kerja Praktek</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Pengajuan KP</a></li>
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
            <div class="row">
                <div class="col-md-10">
                    @if (\App\Helpers\AppHelper::check_bimbingan_kp_is_complete($mahasiswa))
                        <a href="{{ route('kp.cetak.riwayat.bimbingan.mahasiswa') }}" class="btn btn-primary mb-3"
                            target="_blank"><i class="fas fa-download"></i> DOWNLOAD LEMBAR BIMBINGAN KP</a>
                    @endif

                    @if ($is_expired)
                        <div class="mb-3 bg-danger rounded p-2">
                            Masa bimbingan anda sudah habis, silahkan lakukan <a
                                href="{{ route('kp.pendaftaran.disable', $pendaftaran_acc->id) }}"><u><b>Perpanjangan
                                        KP!</b></u></a>
                        </div>
                    @else
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Tanggal Berakhir Bimbingan : <b>{{ \Carbon\Carbon::parse($date_expired)->locale('id')->isoFormat('D MMMM Y') }}
                            </b>
                            @if ($is_seminar)
                                , Selamat anda sudah bisa melakukan
                                <b><a href="{{ route('kp.seminar.create') }}">Pendaftaran Seminar KP</a></b>
                            @endif
                            @if ($check_ujian_has_done)
                                , <b><a href="{{ route('kp.pengumpulan-akhir.create') }}">Ajukan Penjilidan Kerja Praktek</a></b>
                            @endif
                        </div>

                        <div class="d-flex justify-content-center mb-3 bg-primary rounded p-2 countdown"
                            data-expire="{{ \Carbon\Carbon::parse($date_expired)->endOfDay()->format('Y/m/d H:i:s') }}">
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary card-outline">
                                <div class="card-header d-flex p-0">
                                    <h3 class="card-title p-3">Bimbingan Anda</h3>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab_1">

                                            Dosen Pembimbing : <strong>
                                                @if ($dosen_utama)
                                                    {{ $dosen_utama->nama . ', ' . $dosen_utama->gelar }}
                                                @endif
                                            </strong>

                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Bagian Bimbingan</th>
                                                        <th>Tanggal Bimbingan</th>
                                                        <th>Tanggal ACC</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $no = 1; @endphp
                                                    @foreach ($bimbingan_per_bagian as $index => $item)
                                                        @php
                                                            $bagian = $item['bagian'];
                                                            $bimbingan = $item['bimbingan'];

                                                            // Ambil pengajuan terakhir untuk BAB ini (hanya untuk bimbingan manual)
                                                            $lastAjuan = null;
                                                            if ($bimbingan && $dosen_utama && $dosen_utama->is_manual) {
                                                                $lastAjuan = \App\Models\KP\AjuanBimbinganManualKP::where('bimbingan_id', $bimbingan->id)
                                                                    ->orderBy('created_at', 'desc')
                                                                    ->first();
                                                            }
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $no++ }}</td>
                                                            <td>{{ $bagian->bagian }}</td>
                                                            <td>
                                                                @if ($bimbingan && $bimbingan->tanggal_bimbingan)
                                                                    {{ date('d M Y H:i', strtotime($bimbingan->tanggal_bimbingan)) }}
                                                                @else
                                                                    <span class="text-muted">-</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($bimbingan && $bimbingan->tanggal_acc)
                                                                    {{ date('d M Y H:i', strtotime($bimbingan->tanggal_acc)) }}
                                                                @else
                                                                    <span class="text-muted">-</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                {{-- Status BAB (hanya tampil jika sudah ada bimbingan/sudah submit) --}}
                                                                @if ($bimbingan && $bimbingan->status)
                                                                    <div>

                                                                        @if ($bimbingan->status == 'diterima')
                                                                            <span class="badge bg-success">Diterima</span>
                                                                        @elseif ($bimbingan->status == 'revisi')
                                                                            <span class="badge bg-warning">Revisi</span>
                                                                        @elseif ($bimbingan->status == 'review')
                                                                            <span class="badge bg-secondary">Review</span>
                                                                        @endif
                                                                    </div>
                                                                @else
                                                                    <span class="text-muted">-</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if (!$is_expired)
                                                                    @php
                                                                        $showManualBtn = ($dosen_utama && $dosen_utama->is_manual);
                                                                    @endphp

                                                                    {{-- Status: Belum Submit (NULL) --}}
                                                                    @if (!$bimbingan || $bimbingan->status == null)
                                                                        @php
                                                                            $canSubmit = false;
                                                                            // Bagian pertama selalu bisa submit
                                                                            if ($index == 0) {
                                                                                $canSubmit = true;
                                                                            }
                                                                            // Bagian selanjutnya: cek apakah bagian sebelumnya sudah ACC
                                                                            else {
                                                                                $prevItem = $bimbingan_per_bagian[$index - 1] ?? null;
                                                                                if ($prevItem && $prevItem['bimbingan'] && $prevItem['bimbingan']->status == 'diterima') {
                                                                                    $canSubmit = true;
                                                                                }
                                                                            }
                                                                        @endphp

                                                                        @if ($canSubmit)
                                                                            {{-- Semua submti file laporan dulu lewat create biasa --}}
                                                                            <a href="{{ route('kp.bimbingan.create') }}?bagian_id={{ $bagian->id }}"
                                                                                class="btn btn-primary btn-sm shadow">
                                                                                <i class="fas fa-upload mr-1"></i>Submit</a>
                                                                        @endif

                                                                    {{-- Status: Review (Pending) --}}
                                                                    @elseif ($bimbingan->status == 'review')
                                                                        @php
                                                                            // Cek apakah ada ajuan bimbingan manual yang sudah di-ACC dengan status_mahasiswa = revisi
                                                                            // Jika ada, berarti dosen offline minta revisi dan sudah divalidasi admin/prodi
                                                                            $ajuanRevisi = \App\Models\KP\AjuanBimbinganManualKP::where('bimbingan_id', $bimbingan->id)
                                                                                ->where('status_mahasiswa', 'revisi')
                                                                                ->where('status', 'acc')
                                                                                ->exists();
                                                                        @endphp

                                                                        <a href="{{ route('kp.bimbingan.detail', $bimbingan->id) }}"
                                                                            class="btn btn-primary btn-sm shadow mb-1">
                                                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                                                        </a>

                                                                        @if($ajuanRevisi)
                                                                            {{-- Jika dosen offline minta revisi dan sudah di-ACC, mahasiswa bisa submit ulang file laporan --}}
                                                                            <a href="{{ route('kp.bimbingan.edit', $bimbingan->id) }}"
                                                                                class="btn btn-warning btn-sm shadow mb-1">
                                                                                <i class="fas fa-upload mr-1"></i> Submit Ulang
                                                                            </a>
                                                                        @endif

                                                                        @if($showManualBtn)
                                                                            <div class="mt-1"></div>
                                                                            <a href="{{ route('kp.bimbingan-manual.create', $bimbingan->id) }}"
                                                                               class="btn btn-info btn-sm shadow">
                                                                               <i class="fas fa-upload mr-1"></i> Bimbingan Manual
                                                                            </a>
                                                                        @endif

                                                                    {{-- Status: Revisi --}}
                                                                    @elseif ($bimbingan->status == 'revisi')
                                                                        <div class="d-flex flex-wrap gap-1">
                                                                            <a href="{{ route('kp.bimbingan.detail', $bimbingan->id) }}"
                                                                                class="btn btn-primary btn-sm shadow mr-2 mb-1">
                                                                                <i class="fas fa-info-circle mr-1"></i> Detail
                                                                            </a>
                                                                            {{-- Revisi: Bisa submit ulang file laporan --}}
                                                                            <a href="{{ route('kp.bimbingan.edit', $bimbingan->id) }}"
                                                                                class="btn btn-success btn-sm shadow mb-1">
                                                                                <i class="fas fa-upload mr-1"></i>Submit Ulang
                                                                            </a>
                                                                        </div>

                                                                        @if($showManualBtn)
                                                                            <div class="mt-1"></div>
                                                                            <a href="{{ route('kp.bimbingan-manual.create', $bimbingan->id) }}"
                                                                               class="btn btn-info btn-sm shadow">
                                                                               <i class="fas fa-upload mr-1"></i> Bimbingan Manual
                                                                            </a>
                                                                        @endif

                                                                    {{-- Status: Diterima (ACC) --}}
                                                                    @elseif ($bimbingan->status == 'diterima')
                                                                        <a href="{{ route('kp.bimbingan.detail', $bimbingan->id) }}"
                                                                            class="btn btn-primary btn-sm shadow mb-1">
                                                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                                                        </a>

                                                                        @if($showManualBtn)
                                                                            <div class="mt-1"></div>
                                                                            <a href="{{ route('kp.bimbingan-manual.create', $bimbingan->id) }}"
                                                                               class="btn btn-info btn-sm shadow">
                                                                                <i class="fas fa-info-circle mr-1"></i> Detail Bimbingan Manual
                                                                            </a>
                                                                        @endif

                                                                    @endif
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Bagian Bimbingan</th>
                                                        <th>Tanggal Bimbingan</th>
                                                        <th>Tanggal ACC</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </tfoot>
                                            </table>

                                        </div>
                                        <!-- /.tab-pane -->
                                    </div>
                                    <!-- /.tab-content -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 text-center mb-3 card" style="max-height: 300px;">
                    <div class="text-center mt-3">
                        <a href="{{ route('kp.bimbingan.public', base64_encode(Auth::guard('mahasiswa')->user()->id) . uniqid()) }}"
                            class="btn btn-secondary btn-sm shadow mb-2" target="_blank">
                            Tracking Bimbingan <small><i class="bi bi-chevron-right"></i></small>
                        </a>
                        <p class="text-secondary">Atau scan QRCODE dibawah:</p>
                        <div class="mb-3">
                            {!! QrCode::size(150)->generate(
                                route('kp.bimbingan.public', base64_encode(Auth::guard('mahasiswa')->user()->id) . uniqid())
                            ) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Cek dulu apakah sudah diinisialisasi
    if (!$.fn.DataTable.isDataTable('#example1')) {
        $('#example1').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
            "language": {
                "search": "Search:",
                "lengthMenu": "Tampilkan _MENU_ data",
                "info": "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                "infoEmpty": "Tidak ada data",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "paginate": {
                    "first": "Awal",
                    "last": "Akhir",
                    "next": "Next",
                    "previous": "Previous"
                },
                "emptyTable": "Tidak ada data tersedia"
            }
        });
    }
});
</script>
@endpush
