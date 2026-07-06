<?php $__env->startSection('content'); ?>
    <?php
        $canCancelBimbingan = $canCancelBimbingan ?? false;
    ?>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo e($title); ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#"><?php echo e($title); ?></a></li>
                        <li class="breadcrumb-item active">Home</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex">
                            <h3 class="card-title flex-grow-1">Tabel <?php echo e($title); ?></h3>
                            <ul class="nav nav-pills ml-auto p-2">
                                <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Bimbingan
                                        Aktif</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Bimbingan
                                        Selesai</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body table-responsive">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1">
                                    <table id="examplebutton" class="table table-bordered ">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>NIM</th>
                                                <th>Nama Mahasiswa</th>
                                                <th>Kontak</th>
                                                <th>Prodi</th>
                                                <th>Judul Tugas Akhir</th>
                                                <th>Status Bimbingan</th>
                                                <th>Terakhir Bimbingan</th>
                                                <th>Tanggal Pendaftaran TA</th>
                                                <th>Pembimbing 1</th>
                                                <th>Pembimbing 2</th>
                                                <th>Penguji Seminar 1</th>
                                                <th>Penguji Seminar 2</th>
                                                <th>Penguji Seminar 3</th>
                                                <th>Penguji Ujian 1</th>
                                                <th>Penguji Ujian 2</th>
                                                <th>Penguji Ujian 3</th>
                                                <th>Tanggal Ujian Seminar</th>
                                                <th>Tanggal Ujian Pendadaran</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $no = 1;
                                            ?>
                                            <?php $__currentLoopData = $mahasiswas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mahasiswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $ujian = $mahasiswa
                                                        ->ujians()
                                                        ->where('is_valid', \App\Models\Ujian::VALID_LULUS)
                                                        ->first();
                                                ?>
                                                <?php if(!$mahasiswa->jilid): ?>
                                                    <?php
                                                        $pendaftaran_acc = \App\Models\Pendaftaran::where(
                                                            'mahasiswa_id',
                                                            $mahasiswa->id,
                                                        )
                                                            ->where('status', 'diterima')
                                                            ->first();
                                                        $is_expired = $pendaftaran_acc
                                                            ? \App\Helpers\AppHelper::isBimbinganExpiredFromPendaftaran(
                                                                $pendaftaran_acc,
                                                            )
                                                            : null;
                                                        $pengajuan = $mahasiswa
                                                            ->pengajuans()
                                                            ->where('status', 'diterima')
                                                            ->first();
                                                    ?>

                                                    <?php if($pengajuan): ?>
                                                        <tr>
                                                            <td><?php echo e($no++); ?></td>
                                                            <td>
                                                                <?php echo e($mahasiswa->nim); ?>

                                                            </td>
                                                            <td>
                                                                <?php echo e($mahasiswa->nama); ?>

                                                            </td>
                                                            <td>
                                                                <?php if(substr($mahasiswa->hp, 0, 2) === '62'): ?>
                                                                    <a href="https://api.whatsapp.com/send?phone=<?php echo e($mahasiswa->hp); ?>"
                                                                        class="btn btn-success btn-sm rounded-pill"
                                                                        target="_blank"><i class="fab fa-whatsapp"></i></a>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo e($mahasiswa->prodi); ?>

                                                            </td>
                                                            <td>
                                                                <?php echo e($pengajuan->judul); ?>

                                                            </td>
                                                            <td>
                                                                <?php if(count($mahasiswa->bimbingans) != 0): ?>
                                                                    <?php if($pendaftaran_acc): ?>
                                                                        <?php if($is_expired): ?>
                                                                            
                                                                        <?php else: ?>
                                                                            <span class="badge bg-success">AKTIF</span>
                                                                        <?php endif; ?>
                                                                    <?php else: ?>
                                                                        
                                                                    <?php endif; ?>
                                                                <?php else: ?>
                                                                    
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?php if($mahasiswa->bimbingans()->orderBy('created_at', 'desc')->whereIn('status', ['revisi', 'review', 'diterima'])->first()): ?>
                                                                    <?php echo e(\App\Helpers\AppHelper::parse_date_export($mahasiswa->bimbingans()->orderBy('tanggal_bimbingan', 'desc')->first()->tanggal_bimbingan)); ?>

                                                                <?php endif; ?>
                                                            </td>

                                                            <td>
                                                                <?php if($mahasiswa->pendaftarans()->where('status', 'diterima')->first()): ?>
                                                                    <?php echo e(\App\Helpers\AppHelper::parse_date_export($mahasiswa->pendaftarans()->where('status', 'diterima')->first()->created_at)); ?>

                                                                <?php endif; ?>
                                                            </td>

                                                            <td>
                                                                <?php if(count($mahasiswa->bimbingans) != 0): ?>
                                                                    <?php
                                                                        $dosen_utama = $mahasiswa
                                                                            ->dosens()
                                                                            ->where('status', 'utama')
                                                                            ->first();
                                                                    ?>
                                                                    <small><b><?php echo e($dosen_utama->nama . ',' . $dosen_utama->gelar); ?></b></small>
                                                                    <?php $__currentLoopData = $mahasiswa->bimbingans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bimbingan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php if($bimbingan->pembimbing == 'utama'): ?>
                                                                            <small>
                                                                                <?php if(\App\Helpers\AppHelper::instance()->cekBagianIsAcc($bimbingan->id)): ?>
                                                                                    <span
                                                                                        class="text-success"><?php echo e($bimbingan->bagian->bagian); ?>(ACC)
                                                                                    </span>
                                                                                <?php else: ?>
                                                                                    <span><?php echo e($bimbingan->bagian->bagian); ?>

                                                                                    </span>
                                                                                <?php endif; ?>
                                                                            </small>
                                                                        <?php endif; ?>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                <?php else: ?>
                                                                    <span class="badge bg-secondary">BELUM ADA BIMBINGAN</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?php if(count($mahasiswa->bimbingans) != 0): ?>
                                                                    <?php
                                                                        $dosen_pendamping = $mahasiswa
                                                                            ->dosens()
                                                                            ->where('status', 'pendamping')
                                                                            ->first();
                                                                    ?>
                                                                    <small>
                                                                        <b><?php echo e($dosen_pendamping->nama . ',' . $dosen_pendamping->gelar); ?></b>
                                                                    </small>
                                                                    <?php $__currentLoopData = $mahasiswa->bimbingans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bimbingan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php if($bimbingan->pembimbing == 'pendamping'): ?>
                                                                            <small>
                                                                                <?php if(\App\Helpers\AppHelper::instance()->cekBagianIsAcc($bimbingan->id)): ?>
                                                                                    <span
                                                                                        class="text-success"><?php echo e($bimbingan->bagian->bagian); ?>(ACC)
                                                                                    </span>
                                                                                <?php else: ?>
                                                                                    <span><?php echo e($bimbingan->bagian->bagian); ?>

                                                                                    </span>
                                                                                <?php endif; ?>
                                                                            </small>
                                                                        <?php endif; ?>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                <?php else: ?>
                                                                    <span class="badge bg-secondary">BELUM ADA BIMBINGAN</span>
                                                                <?php endif; ?>
                                                            </td>

                                                            <?php if($mahasiswa->seminar): ?>
                                                                <?php if(count($mahasiswa->seminar->reviews) > 3): ?>
                                                                    <?php $__currentLoopData = $mahasiswa->seminar->reviews()->where('dosen_status', 'penguji')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <td>
                                                                            <?php echo e($review->dosen->nama . ',' . $review->dosen->gelar); ?>

                                                                        </td>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php if(count($mahasiswa->seminar->reviews()->where('dosen_status', 'penguji')->get()) < 3): ?>
                                                                        <td></td>
                                                                    <?php endif; ?>
                                                                <?php else: ?>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                <?php endif; ?>
                                                            <?php else: ?>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            <?php endif; ?>

                                                            <?php if($ujian): ?>
                                                                <?php if(count($ujian->reviews) > 3): ?>
                                                                    <?php $__currentLoopData = $ujian->reviews()->where('dosen_status', 'penguji')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <td>
                                                                            <?php echo e($review->dosen->nama . ',' . $review->dosen->gelar); ?>

                                                                        </td>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php if(count($ujian->reviews()->where('dosen_status', 'penguji')->get()) < 3): ?>
                                                                        <td></td>
                                                                    <?php endif; ?>
                                                                <?php else: ?>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                <?php endif; ?>
                                                            <?php else: ?>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            <?php endif; ?>

                                                            <td>
                                                                <?php if($mahasiswa->seminar): ?>
                                                                    <?php echo e($mahasiswa->seminar->tanggal_ujian ? \App\Helpers\AppHelper::parse_date_export($mahasiswa->seminar->tanggal_ujian) : ''); ?>

                                                                <?php endif; ?>
                                                            </td>

                                                            <td>
                                                                <?php if($ujian): ?>
                                                                    <?php echo e($ujian->tanggal_ujian ? \App\Helpers\AppHelper::parse_date_export($ujian->tanggal_ujian) : ''); ?>

                                                                <?php endif; ?>
                                                            </td>

                                                            <td>
                                                                <?php if(!empty($reviewRoute) && count($mahasiswa->bimbingans) != 0): ?>
                                                                    <a href="<?php echo e(route($reviewRoute, $mahasiswa->pengajuans()->where('status', 'diterima')->first()->id)); ?>"
                                                                        class="btn btn-primary btn-sm"><i
                                                                            class="bi bi-info-circle"></i>
                                                                        Detail Bimbingan</a>
                                                                <?php endif; ?>
                                                                <?php if($canCancelBimbingan && count($mahasiswa->bimbingans) != 0): ?>
                                                                    <a href="<?php echo e(route('bimbingan.canceled', $mahasiswa->id)); ?>"
                                                                        class="btn btn-danger btn-sm"
                                                                        onclick="return confirm('Yakin ingin membatalkan bimbingan?')"><i
                                                                            class="bi bi-x-circle"></i>
                                                                        Batalkan Bimbingan</a>
                                                                <?php endif; ?>
                                                                <?php if($mahasiswa->pendaftarans()->where('status', 'diterima')->first()): ?>
                                                                    <a href="<?php echo e(url('cetak/surat-tugas-bimbingan/' . $mahasiswa->pendaftarans()->where('status', 'diterima')->first()->id)); ?>"
                                                                        target="_blank" class="btn btn-success btn-sm "><i
                                                                            class="fas fa-download"></i> Surat Tugas Bimbingan
                                                                        TA</a>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>No</th>
                                                <th>NIM</th>
                                                <th>Nama Mahasiswa</th>
                                                <th>Kontak</th>
                                                <th>Prodi</th>
                                                <th>Judul Tugas Akhir</th>
                                                <th>Status Bimbingan</th>
                                                <th>Terakhir Bimbingan</th>
                                                <th>Tanggal Pendaftaran TA</th>
                                                <th>Pembimbing 1</th>
                                                <th>Pembimbing 2</th>
                                                <th>Penguji Seminar 1</th>
                                                <th>Penguji Seminar 2</th>
                                                <th>Penguji Seminar 3</th>
                                                <th>Penguji Ujian 1</th>
                                                <th>Penguji Ujian 2</th>
                                                <th>Penguji Ujian 3</th>
                                                <th>Tanggal Ujian Seminar</th>
                                                <th>Tanggal Ujian Pendadaran</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <div class="tab-pane" id="tab_2">
                                    <table id="examplebutton2" class="table table-bordered ">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>NIM</th>
                                                <th>Nama Mahasiswa</th>
                                                <th>Kontak</th>
                                                <th>Prodi</th>
                                                <th>Judul Tugas Akhir</th>
                                                <th>Status Bimbingan</th>
                                                <th>Terakhir Bimbingan</th>
                                                <th>Tanggal Pendaftaran TA</th>
                                                <th>Pembimbing 1</th>
                                                <th>Pembimbing 2</th>
                                                <th>Penguji Seminar 1</th>
                                                <th>Penguji Seminar 2</th>
                                                <th>Penguji Seminar 3</th>
                                                <th>Penguji Ujian 1</th>
                                                <th>Penguji Ujian 2</th>
                                                <th>Penguji Ujian 3</th>
                                                <th>Tanggal Ujian Seminar</th>
                                                <th>Tanggal Ujian Pendadaran</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $no = 1;
                                            ?>
                                            <?php $__currentLoopData = $mahasiswas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mahasiswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $ujian = $mahasiswa
                                                        ->ujians()
                                                        ->where('is_valid', \App\Models\Ujian::VALID_LULUS)
                                                        ->first();
                                                ?>
                                                <?php if($mahasiswa->jilid || count($mahasiswa->bimbingan_canceleds) != 0): ?>
                                                    <?php
                                                        $pendaftaran_acc = \App\Models\Pendaftaran::where(
                                                            'mahasiswa_id',
                                                            $mahasiswa->id,
                                                        )
                                                            ->where('status', 'diterima')
                                                            ->first();
                                                        $is_expired = $pendaftaran_acc
                                                            ? \App\Helpers\AppHelper::isBimbinganExpiredFromPendaftaran(
                                                                $pendaftaran_acc,
                                                            )
                                                            : null;
                                                    ?>
                                                    <tr>
                                                        <td><?php echo e($no++); ?></td>
                                                        <td>
                                                            <?php echo e($mahasiswa->nim); ?>

                                                        </td>
                                                        <td>
                                                            <?php echo e($mahasiswa->nama); ?>

                                                        </td>
                                                        <td>
                                                            <?php if(substr($mahasiswa->hp, 0, 2) === '62'): ?>
                                                                <a href="https://api.whatsapp.com/send?phone=<?php echo e($mahasiswa->hp); ?>"
                                                                    class="btn btn-success btn-sm rounded-pill"
                                                                    target="_blank"><i class="fab fa-whatsapp"></i></a>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo e($mahasiswa->prodi); ?>

                                                        </td>
                                                        <td>
                                                            <?php if($mahasiswa->jilid): ?>
                                                                <?php echo e($mahasiswa->pengajuans()->where('status', 'diterima')->first()->judul); ?>

                                                            <?php else: ?>
                                                                <?php echo e($mahasiswa->bimbingan_canceleds()->orderBy('created_at', 'desc')->first()->pengajuan->judul); ?>

                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if(count($mahasiswa->bimbingan_canceleds) != 0 && $mahasiswa->jilid): ?>
                                                                <span class="badge bg-success">SELESAI</span>
                                                            <?php elseif(count($mahasiswa->bimbingan_canceleds) != 0): ?>
                                                                <span class="badge bg-danger">DIBATALKAN</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-success">SELESAI</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if($mahasiswa->bimbingans()->orderBy('created_at', 'desc')->whereIn('status', ['revisi', 'review', 'diterima'])->first()): ?>
                                                                <?php echo e(\App\Helpers\AppHelper::parse_date_export($mahasiswa->bimbingans()->orderBy('tanggal_bimbingan', 'desc')->first()->tanggal_bimbingan)); ?>

                                                            <?php endif; ?>
                                                        </td>

                                                        <td>
                                                            <?php if($mahasiswa->pendaftarans()->where('status', 'diterima')->first()): ?>
                                                                <?php echo e(\App\Helpers\AppHelper::parse_date_export($mahasiswa->pendaftarans()->where('status', 'diterima')->first()->created_at)); ?>

                                                            <?php endif; ?>
                                                        </td>

                                                        <?php if($mahasiswa->jilid): ?>
                                                            <td>
                                                                <?php if(count($mahasiswa->bimbingans) != 0): ?>
                                                                    <?php
                                                                        $dosen_utama = $mahasiswa
                                                                            ->dosens()
                                                                            ->where('status', 'utama')
                                                                            ->first();
                                                                    ?>
                                                                    <small><b><?php echo e($dosen_utama->nama . ',' . $dosen_utama->gelar); ?></b></small>
                                                                    <?php $__currentLoopData = $mahasiswa->bimbingans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bimbingan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php if($bimbingan->pembimbing == 'utama'): ?>
                                                                            <small>
                                                                                <?php if(\App\Helpers\AppHelper::instance()->cekBagianIsAcc($bimbingan->id)): ?>
                                                                                    <span
                                                                                        class="text-success"><?php echo e($bimbingan->bagian->bagian); ?>(ACC)
                                                                                    </span>
                                                                                <?php else: ?>
                                                                                    <span><?php echo e($bimbingan->bagian->bagian); ?>

                                                                                    </span>
                                                                                <?php endif; ?>
                                                                            </small>
                                                                        <?php endif; ?>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                <?php else: ?>
                                                                    <span class="badge bg-secondary">BELUM ADA
                                                                        BIMBINGAN</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?php if(count($mahasiswa->bimbingans) != 0): ?>
                                                                    <?php
                                                                        $dosen_pendamping = $mahasiswa
                                                                            ->dosens()
                                                                            ->where('status', 'pendamping')
                                                                            ->first();
                                                                    ?>
                                                                    <small>
                                                                        <b><?php echo e($dosen_pendamping->nama . ',' . $dosen_pendamping->gelar); ?></b>
                                                                    </small>
                                                                    <?php $__currentLoopData = $mahasiswa->bimbingans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bimbingan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php if($bimbingan->pembimbing == 'pendamping'): ?>
                                                                            <small>
                                                                                <?php if(\App\Helpers\AppHelper::instance()->cekBagianIsAcc($bimbingan->id)): ?>
                                                                                    <span
                                                                                        class="text-success"><?php echo e($bimbingan->bagian->bagian); ?>(ACC)
                                                                                    </span>
                                                                                <?php else: ?>
                                                                                    <span><?php echo e($bimbingan->bagian->bagian); ?>

                                                                                    </span>
                                                                                <?php endif; ?>
                                                                            </small>
                                                                        <?php endif; ?>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                <?php else: ?>
                                                                    <span class="badge bg-secondary">BELUM ADA
                                                                        BIMBINGAN</span>
                                                                <?php endif; ?>
                                                            </td>
                                                        <?php else: ?>
                                                            <td><?php echo e($mahasiswa->bimbingan_canceleds()->where('pembimbing', 'utama')->first()->dosen->nama . ',' . $mahasiswa->bimbingan_canceleds()->where('pembimbing', 'utama')->first()->dosen->gelar); ?>

                                                            </td>
                                                            <td><?php echo e($mahasiswa->bimbingan_canceleds()->where('pembimbing', 'pendamping')->first()->dosen->nama . ',' . $mahasiswa->bimbingan_canceleds()->where('pembimbing', 'pendamping')->first()->dosen->gelar); ?>

                                                            </td>
                                                        <?php endif; ?>

                                                        <?php if($mahasiswa->seminar): ?>
                                                            <?php if(count($mahasiswa->seminar->reviews) > 3): ?>
                                                                <?php $__currentLoopData = $mahasiswa->seminar->reviews()->where('dosen_status', 'penguji')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <td>
                                                                        <?php echo e($review->dosen->nama . ',' . $review->dosen->gelar); ?>

                                                                    </td>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                <?php if(count($mahasiswa->seminar->reviews()->where('dosen_status', 'penguji')->get()) < 3): ?>
                                                                    <td></td>
                                                                <?php endif; ?>
                                                            <?php else: ?>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        <?php endif; ?>

                                                        <?php if($ujian): ?>
                                                            <?php if(count($ujian->reviews) > 3): ?>
                                                                <?php $__currentLoopData = $ujian->reviews()->where('dosen_status', 'penguji')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <td>
                                                                        <?php echo e($review->dosen->nama . ',' . $review->dosen->gelar); ?>

                                                                    </td>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                <?php if(count($ujian->reviews()->where('dosen_status', 'penguji')->get()) < 3): ?>
                                                                    <td></td>
                                                                <?php endif; ?>
                                                            <?php else: ?>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        <?php endif; ?>

                                                        <td>
                                                            <?php if($mahasiswa->seminar): ?>
                                                                <?php echo e($mahasiswa->seminar->tanggal_ujian ? \App\Helpers\AppHelper::parse_date_export($mahasiswa->seminar->tanggal_ujian) : ''); ?>

                                                            <?php endif; ?>
                                                        </td>

                                                        <td>
                                                            <?php if($ujian): ?>
                                                                <?php echo e($ujian->tanggal_ujian ? \App\Helpers\AppHelper::parse_date_export($ujian->tanggal_ujian) : ''); ?>

                                                            <?php endif; ?>
                                                        </td>

                                                        <td>
                                                            <?php if(!empty($reviewRoute) && count($mahasiswa->bimbingans) != 0): ?>
                                                                <a href="<?php echo e(route($reviewRoute, $mahasiswa->pengajuans()->where('status', 'diterima')->first()->id)); ?>"
                                                                    class="btn btn-primary btn-sm"><i
                                                                        class="bi bi-info-circle"></i>
                                                                    Detail Bimbingan</a>
                                                            <?php endif; ?>
                                                            <?php if($mahasiswa->pendaftarans()->where('status', 'diterima')->first()): ?>
                                                                <a href="<?php echo e(url('cetak/surat-tugas-bimbingan/' . $mahasiswa->pendaftarans()->where('status', 'diterima')->first()->id)); ?>"
                                                                    target="_blank" class="btn btn-success btn-sm "><i
                                                                        class="fas fa-download"></i> Surat Tugas Bimbingan
                                                                    TA</a>
                                                            <?php endif; ?>
                                                        </td>


                                                    </tr>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>No</th>
                                                <th>NIM</th>
                                                <th>Nama Mahasiswa</th>
                                                <th>Kontak</th>
                                                <th>Prodi</th>
                                                <th>Judul Tugas Akhir</th>
                                                <th>Status Bimbingan</th>
                                                <th>Terakhir Bimbingan</th>
                                                <th>Tanggal Pendaftaran TA</th>
                                                <th>Pembimbing 1</th>
                                                <th>Pembimbing 2</th>
                                                <th>Penguji Seminar 1</th>
                                                <th>Penguji Seminar 2</th>
                                                <th>Penguji Seminar 3</th>
                                                <th>Penguji Ujian 1</th>
                                                <th>Penguji Ujian 2</th>
                                                <th>Penguji Ujian 3</th>
                                                <th>Tanggal Ujian Seminar</th>
                                                <th>Tanggal Ujian Pendadaran</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- /.content -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/pages/admin/bimbingan/bimbingan.blade.php ENDPATH**/ ?>