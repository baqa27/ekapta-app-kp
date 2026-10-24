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
                        <li class="breadcrumb-item"><a href="{{ route('kp.pengumpulan-akhir.prodi.index') }}">Pengumpulan Akhir KP</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    {{-- Card Info --}}
                    <div class="card card-primary card-outline">
                        <div class="ribbon-wrapper ribbon-lg">
                            <div class="ribbon
                                @if ($jilid->status == 1) bg-secondary
                                @elseif ($jilid->status == 2) bg-warning
                                @elseif ($jilid->status == 3) bg-primary
                                @elseif ($jilid->status == 4) bg-success @endif">
                                @if ($jilid->status == 1)
                                    REVIEW
                                @elseif ($jilid->status == 2)
                                    REVISI
                                @elseif ($jilid->status == 3)
                                    VALID
                                @elseif ($jilid->status == 4)
                                    SELESAI
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">NIM</div>
                                <div class="col-md-8"><b>{{ $mahasiswa->nim }}</b></div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">Nama Lengkap</div>
                                <div class="col-md-8"><b>{{ $mahasiswa->nama }}</b></div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">Prodi</div>
                                <div class="col-md-8"><b>{{ $prodi ? $prodi->namaprodi : $mahasiswa->prodi }}</b></div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">Judul KP</div>
                                <div class="col-md-8"><b>{{ $jilid->mahasiswa->pengajuansKP()->where('status', 'diterima')->first()->judul ?? '-' }}</b></div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">Tanggal Submit</div>
                                <div class="col-md-8"><b>{{ $jilid->created_at->format('d M Y H:i') }}</b></div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">Waktu Pelaksanaan KP</div>
                                <div class="col-md-8"><b>{{ $waktu_pelaksanaan }}</b></div>
                            </div>
                            <hr>

                            @if($jilid->updated_at && $jilid->updated_at != $jilid->created_at)
                            <div class="row">
                                <div class="col-md-4">Terakhir Update</div>
                                <div class="col-md-8"><b>{{ $jilid->updated_at->format('d M Y H:i') }}</b></div>
                            </div>
                            <hr>
                            @endif

                            @php
                                // Ambil presentase nilai dari database (relasi presentaseNilaiKP)
                                $prodi_obj = \App\Models\Prodi::where('kode', $mahasiswa->prodi)
                                    ->orWhere('namaprodi', $mahasiswa->prodi)
                                    ->first();
                                $presentase_nilai = $prodi_obj && $prodi_obj->presentaseNilaiKP ? $prodi_obj->presentaseNilaiKP : null;
                            @endphp

                            {{-- Nilai KP --}}
                            <h5 class="mt-3"><strong>Nilai Kerja Praktek</strong></h5>
                            <hr>

                            {{-- Nilai Pembimbing --}}
                            <div class="row">
                                <div class="col-md-4">Nilai Dosen Pembimbing</div>
                                <div class="col-md-8">
                                    @php
                                        $nilai_pembimbing = 0;
                                        if($mahasiswa->seminarKP && $mahasiswa->seminarKP->nilai_pembimbing) {
                                            $nilai_pembimbing = $mahasiswa->seminarKP->nilai_pembimbing;
                                        } elseif($jilid->nilai_pembimbing) {
                                            $nilai_pembimbing = $jilid->nilai_pembimbing;
                                        }
                                    @endphp
                                    @if($nilai_pembimbing > 0)
                                        <b>{{ number_format($nilai_pembimbing, 2) }}</b>
                                        @php
                                            $dosen_pembimbing = $mahasiswa->dosens()->whereIn('status', ['pembimbing', 'utama'])->first();
                                        @endphp
                                        @if($dosen_pembimbing)
                                        <small class="text-muted">({{ $dosen_pembimbing->nama }})</small>
                                        @endif
                                    @else
                                        <span class="text-muted">Belum dinilai</span>
                                    @endif
                                </div>
                            </div>
                            <hr>

                            @if(!$is_karyawan)
                            {{-- Nilai Penguji - HANYA untuk mahasiswa reguler --}}
                            <div class="row">
                                <div class="col-md-4">Nilai Dosen Penguji</div>
                                <div class="col-md-8">
                                    @php
                                        $nilai_penguji = 0;
                                        if($mahasiswa->seminarKP) {
                                            $nilai_penguji = $mahasiswa->seminarKP->nilai_seminar ?? $mahasiswa->seminarKP->nilai_penguji ?? 0;
                                        }
                                    @endphp
                                    @if($nilai_penguji > 0)
                                        <b>{{ number_format($nilai_penguji, 2) }}</b>
                                        @if($mahasiswa->seminarKP && $mahasiswa->seminarKP->sesiSeminar && $mahasiswa->seminarKP->sesiSeminar->dosenPenguji)
                                        <small class="text-muted">({{ $mahasiswa->seminarKP->sesiSeminar->dosenPenguji->nama ?? '-' }})</small>
                                        @endif
                                    @else
                                        <span class="text-muted">Belum dinilai</span>
                                    @endif
                                </div>
                            </div>
                            <hr>
                            @endif

                            {{-- Nilai Instansi --}}
                            <div class="row">
                                <div class="col-md-4">Nilai Instansi</div>
                                <div class="col-md-8">
                                    @php
                                        $nilai_instansi = 0;
                                        if($mahasiswa->seminarKP) {
                                            $nilai_instansi = $mahasiswa->seminarKP->nilai_instansi ?? 0;
                                        }
                                    @endphp
                                    @if($nilai_instansi > 0)
                                        <b>{{ number_format($nilai_instansi, 2) }}</b>
                                    @else
                                        <span class="text-muted">Belum dinilai</span>
                                    @endif
                                </div>
                            </div>
                            <hr>

                            {{-- Nilai Akhir KP --}}
                            @php
                                // Hitung nilai akhir berdasarkan jenis mahasiswa dan presentase dari database
                                $nilai_pembimbing_display = $nilai_pembimbing;
                                $nilai_penguji_display = $is_karyawan ? 0 : ($nilai_penguji ?? 0);
                                $nilai_instansi_display = $nilai_instansi;
                                
                                // Ambil bobot dari database atau gunakan default
                                if ($presentase_nilai) {
                                    $bobot_pembimbing = $presentase_nilai->bobot_pembimbing;
                                    $bobot_penguji = $is_karyawan ? 0 : $presentase_nilai->bobot_penguji;
                                    $bobot_instansi = 100 - $bobot_pembimbing - $bobot_penguji;
                                } else {
                                    // Default jika tidak ada presentase_nilai
                                    $bobot_pembimbing = $is_karyawan ? 40 : 40;
                                    $bobot_penguji = $is_karyawan ? 0 : 30;
                                    $bobot_instansi = $is_karyawan ? 60 : 30;
                                }
                                
                                // Hitung nilai akhir
                                $nilai_akhir_calculated = ($nilai_pembimbing_display * $bobot_pembimbing / 100) + 
                                                         ($nilai_penguji_display * $bobot_penguji / 100) + 
                                                         ($nilai_instansi_display * $bobot_instansi / 100);
                            @endphp
                            
                            <div class="row">
                                <div class="col-md-4"><strong>Nilai Akhir KP</strong></div>
                                <div class="col-md-8">
                                    @if($nilai_akhir_calculated > 0)
                                        <h4 class="text-success"><b>{{ number_format($nilai_akhir_calculated, 2) }}</b></h4>
                                    @else
                                        <h4 class="text-muted"><b>0.00</b></h4>
                                    @endif
                                    <small class="text-muted">
                                        @if($is_karyawan)
                                            Formula: (Pembimbing × {{ $bobot_pembimbing }}%) + (Instansi × {{ $bobot_instansi }}%)
                                        @else
                                            Formula: (Pembimbing × {{ $bobot_pembimbing }}%) + (Penguji × {{ $bobot_penguji }}%) + (Instansi × {{ $bobot_instansi }}%)
                                        @endif
                                    </small>
                                </div>
                            </div>
                            <hr>

                            <h5 class="mt-3"><strong>Dokumen Pengumpulan Akhir</strong></h5>
                            <hr>

                            @foreach(config('kp.dokumen_pengumpulan_akhir') as $document)
                                @php($value = $jilid->{$document['field']})
                                <div class="row">
                                    <div class="col-md-4">{{ $document['label'] }}</div>
                                    <div class="col-md-8">
                                        @if($value)
                                            @if($document['field'] === 'link_project')
                                                <a href="{{ $value }}" target="_blank" class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-external-link-alt"></i> Buka Link
                                                </a>
                                            @elseif(filter_var($value, FILTER_VALIDATE_URL))
                                                <a href="{{ $value }}" target="_blank" class="text-primary">
                                                    <i class="fas fa-external-link-alt"></i> Buka Link Google Drive
                                                </a>
                                            @else
                                                <a href="{{ storage_url($value) }}" target="_blank" class="text-primary">
                                                    <i class="fas fa-paperclip"></i> {{ basename($value) }}
                                                </a>
                                            @endif
                                        @else
                                            <span class="text-muted">Belum diunggah</span>
                                        @endif
                                    </div>
                                </div>
                                <hr>
                            @endforeach

                            {{-- Validasi Prodi: ACC / Revisi (hanya saat status REVIEW) --}}
                            @if ($jilid->status == \App\Models\KP\Jilid::JILID_REVIEW)
                            <h5 class="mt-4"><strong>Validasi Pengumpulan Akhir</strong></h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <form action="{{ route('kp.pengumpulan-akhir.prodi.acc', $jilid->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="{{ \App\Models\KP\Jilid::JILID_VALID }}">
                                        <button type="submit" class="btn btn-success btn-block" onclick="return confirm('ACC pengajuan ini?');">
                                            <i class="fas fa-check mr-1"></i> ACC Pengumpulan Akhir
                                        </button>
                                    </form>
                                </div>
                                <div class="col-md-6">
                                    <form action="{{ route('kp.pengumpulan-akhir.prodi.acc', $jilid->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="{{ \App\Models\KP\Jilid::JILID_REVISI }}">
                                        <div class="input-group">
                                            <input type="text" name="catatan" class="form-control" placeholder="Catatan revisi..." required>
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Kirim revisi ke mahasiswa?');">
                                                <i class="fas fa-times mr-1"></i> Revisi
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <hr>
                            @endif

                            {{-- Riwayat Revisi --}}
                            @if($revisis->count() > 0)
                            <h5 class="mt-4"><strong>Riwayat Revisi</strong></h5>
                            <hr>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th width="50">No</th>
                                            <th width="150">Tanggal</th>
                                            <th>Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($revisis as $revisi)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $revisi->created_at->format('d M Y H:i') }}</td>
                                            <td>{{ $revisi->catatan }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{ $revisis->links() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
