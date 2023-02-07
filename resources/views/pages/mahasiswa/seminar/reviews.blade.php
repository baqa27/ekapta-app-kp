@extends('layouts.dashboardMahasiswa')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Review Seminar TA</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">

                @foreach($seminar->reviews as $review)
                    <div class="col-md-4">
                        <div class="card card-primary card-outline">
                                <div class="ribbon-wrapper ribbon-lg">
                                    <div class="ribbon
                                @if($review->status == 'diterima')
                                bg-success
                                @elseif($review->status == 'revisi')
                                bg-warning
                                @else
                                bg-secondary
                                @endif
                                ">
                                        @if($review->status == 'diterima')
                                            Diterima
                                        @elseif($review->status == 'revisi')
                                            Revisi
                                        @else
                                            Review
                                        @endif
                                    </div>
                                </div>

                            <div class="card-body">
                                Dosen {{ $review->dosen_status == 'pembimbing' ? 'Pembimbing' : 'Penguji'}} : <br>
                                <b>{{ $review->dosen->nama  }}, {{ $review->dosen->gelar }}</b> <br><br>

                                @php
                                    $bimbingans = $review->dosen->bimbingans()->where('status','diterima')->where('mahasiswa_id',$review->seminar->mahasiswa->id)->get();
                                @endphp

                                {{-- Reviews --}}
                                @if($review->dosen_status == 'pembimbing')
                                    Catatan Dosen <br><br>
                                    <div class="p-2 rounded reviews-box">
                                        @foreach ($bimbingans as $bimbingan)
                                            @foreach($bimbingan->revisis as $revisi)
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
                                                                    <span class="text-secondary ml-2"><b>Lampiran : </b></span>
                                                                    <a href="{{ asset($revisi->lampiran) }}"
                                                                       target="_blank">
                                                                        <i class="fas fa-paperclip ml-1"></i>
                                                                        {{ Str::substr($revisi->lampiran, 16) }}
                                                                    </a>
                                                                </small>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endforeach
                                    </div>
                                @else
                                    Catatan Dosen <br><br>
                                    <div class="p-2 rounded reviews-box">
                                        @foreach($review->revisis as $revisi)
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
                                                                <span class="text-secondary ml-2"><b>Lampiran : </b></span>
                                                                <a href="{{ asset($revisi->lampiran) }}"
                                                                   target="_blank">
                                                                    <i class="fas fa-paperclip ml-1"></i>
                                                                    {{ Str::substr($revisi->lampiran, 16) }}
                                                                </a>
                                                            </small>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection
