@extends('layouts.dashboard')

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
                        <li class="breadcrumb-item"><a href="#">Ujian TA</a></li>
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
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="ribbon-wrapper ribbon-lg">
                            <div
                                class="ribbon
                            @if ($ujian->is_valid == 0) bg-secondary
                            @elseif ($ujian->is_valid == 2)
                            bg-warning
                            @elseif ($ujian->is_valid == 1)
                            bg-success @endif
                            ">
                                @if ($ujian->is_valid == 0)
                                    TIDAK VALID
                                @elseif ($ujian->is_valid == 1)
                                    VALID
                                @elseif ($ujian->is_valid == 2)
                                    REVISI
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5">
                                    NIM
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $ujian->mahasiswa->nim }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Nama Lengkap
                                </div>
                                <div class="col-md-7">
                                    <b>{{  $ujian->mahasiswa->nama }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Prodi
                                </div>
                                <div class="col-md-7">
                                    <b>{{  $ujian->mahasiswa->prodi }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Judul TA
                                </div>
                                <div class="col-md-7">
                                    <b>{{  $ujian->pengajuan->judul }}</b>
                                </div>
                            </div>
                            <hr>

                            @if($ujian->lampiran_proposal)
                                <div class="row">
                                    <div class="col-md-5">
                                        Proposal Ujian TA
                                    </div>
                                    <div class="col-md-7">
                                        <b><a href="{{ asset($ujian->lampiran_proposal) }}" target="_blank"><i
                                                    class="fas fa-download"></i>
                                                {{ Str::substr($ujian->lampiran_proposal, 40) }}</a>
                                        </b>
                                    </div>
                                </div>
                            @endif

                        </div>

                        @if ($ujian->is_valid == 1)
                            <div class="card-footer">
                                <div class="d-flex">
                                    <a href="{{ route('cetak.berita.acara.ujian.pendadaran', $ujian->id) }}"
                                       class="btn btn-success" target="_blank">
                                        <i class="bi bi-download"></i> Berita Acara Ujian Pendadaran
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Revisi --}}
                    <div class="card card-primary card-outline mt-2">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Revisi</strong>
                                <span class="badge bg-danger rounded-pill">
                                    {{ count($ujian->revisis) }}
                                </span>
                            </h3>
                        </div>

                        <div class="card-body">

                            @foreach ($revisis as $revisi)
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <i class="fas fa-calendar mr-2"></i>
                                        {{ $revisi->created_at->format('d M Y H:m') }}
                                        <div class="float-right" onclick="confirmDelete()">
                                            <form action="{{ route('ujian.revisi.delete') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $revisi->id }}">
                                                <button class="btn btn-danger btn-sm float-right" type="submit">
                                                    <i class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        {!! nl2br($revisi->catatan) !!}
                                    </div>

                                    @if ($revisi->lampiran)
                                        <div class="card-footer">
                                            Lampiran :
                                            @if ($revisi->lampiran)
                                                <a href="{{ asset($revisi->lampiran) }}" class="ml-3" target="_blank"><i
                                                        class="fas fa-paperclip"></i>
                                                    {{ Str::substr($revisi->lampiran, 40) }}</a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endforeach

                        </div>
                        <div class="d-flex justify-content-center mb-3">
                            {{ $revisis->links() }}
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>

            <div class="row mb-3">
                @foreach($ujian->reviews as $review)
                    @if($review->dosen_status == 'penguji')
                        <div class="col-md-4">
                            <div class="card card-primary card-outline">
                                <div class="ribbon-wrapper ribbon-lg">
                                    <div class="ribbon
                                @if($review->status == 'diterima')
                                bg-success
                                @elseif($review->status == 'revisi')
                                bg-warning
                                @elseif($review->status == 'review')
                                bg-secondary
                                @else
                                bg-danger
                                @endif
                                ">
                                        @if($review->status == 'diterima')
                                            Diterima
                                        @elseif($review->status == 'revisi')
                                            Revisi
                                        @elseif($review->status == 'review')
                                            Review
                                        @else
                                            Belum Submit
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                    Dosen Penguji : <br>
                                    <b>{{ $review->dosen->nama  }}, {{ $review->dosen->gelar }}</b> <br><br>

                                    {{-- Reviews --}}
                                    Catatan Dosen <span
                                        class="badge bg-danger"> {{ count($review->revisis)  }} </span><br><br>
                                    <div class="p-2 rounded reviews-box">
                                        @foreach($review->revisis()->orderBy('created_at', 'desc')->get() as $revisi)
                                            <div class="direct-chat-msg">
                                                <div class="direct-chat-infos clearfix">
                                                    <span class="direct-chat-name float-left">{{ $review->dosen->nama  }}, {{ $review->dosen->gelar  }}</span>
                                                    <span class="direct-chat-timestamp float-right">
                                            {{ $revisi->created_at->format('d M Y H:m a') }}
                                        </span>
                                                </div>
                                                <img class="direct-chat-img"
                                                     src="{{ asset('ekapta/adminLTE/dist/img/default-profile.png') }}"
                                                     alt="message user image">
                                                <div class="direct-chat-text p-2">
                                                    {!! nl2br($revisi->catatan) !!}
                                                    @if ($revisi->lampiran)
                                                        <div class="p-1 mt-3 bg-light rounded">
                                                            <small>
                                                                <span
                                                                    class="text-secondary ml-2"><b>Lampiran : </b></span>
                                                                <a href="{{ asset($revisi->lampiran) }}"
                                                                   target="_blank">
                                                                    <i class="fas fa-paperclip ml-1"></i>
                                                                    {{ Str::substr($revisi->lampiran, 40) }}
                                                                </a>
                                                            </small>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                </div>

                                @if($review->status == 'review' || $review->status == 'diterima')
                                    <div class="card-footer">
                                        Keterangan :
                                        <div class="bg-secondary rounded p-2">{!! $review->keterangan !!}
                                            <div class="bg-light p-1 rounded mt-1">
                                                <small>
                                                    <b>Lampiran : </b>
                                                    <a href="{{ asset($review->lampiran) }}" class="ml-3 text-primary"
                                                       target="_blank"><i class="fas fa-paperclip mr-2"></i>
                                                        {{ Str::substr($review->lampiran, 40) }}</a>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>

                        </div>
                    @endif
                @endforeach
            </div>

            <div class="card card-primary card-outline col-md-12 mb-3">
                <div class="card-header">
                    Nilai Seminar TA
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Dosen</th>
                            <th>Status</th>
                            <th>Substansi / Isi Materi</th>
                            <th>Kompetensi Ilmu</th>
                            <th>Metodologi dan Redaksi TA</th>
                            <th>Presentasi</th>
                            <th>Rata-Rata</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($ujian->reviews as $review)
                            <tr>
                                <td>{{ $review->dosen->nama.', '.$review->dosen->gelar }}</td>
                                <td>{{ $review->dosen_status == \App\Models\ReviewSeminar::DOSEN_PENGUJI ? 'Penguji' : 'Pembimbing' }}</td>
                                <td>{{ $review->nilai_1 }}</td>
                                <td>{{ $review->nilai_2 }}</td>
                                <td>{{ $review->nilai_3 }}</td>
                                <td>{{ $review->nilai_4 }}</td>
                                <td>{{ \App\Helpers\AppHelper::instance()->hitung_nilai_ujian($review->nilai_1,$review->nilai_2,$review->nilai_3,$review->nilai_4, $prodi->id) }}</td>
                            </tr>
                        @endforeach
                        </tbody>

                        <tfoot>
                        <tr bgcolor="#a9a9a9" class="text-white">
                            <th colspan="6">RATA - RATA NILAI DOSEN PEMBIMBING</th>
                            <th>{{ $nilai_dosen_pembimbing }}</th>
                        </tr>
                        <tr bgcolor="#a9a9a9" class="text-white">
                            <th colspan="6">RATA - RATA NILAI DOSEN PENGUJI</th>
                            <th>{{ $nilai_dosen_penguji }}</th>
                        </tr>
                        <tr bgcolor="#808080" class="text-white">
                            <th colspan="6">NILAI AKHIR</th>
                            <th>{{ $nilai }}</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->

@endsection
