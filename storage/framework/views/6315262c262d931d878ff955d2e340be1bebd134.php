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
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header d-flex p-0">
                            <h3 class="card-title p-3">Tabel <?php echo e($title); ?></h3>
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
                                                <th>Judul Kerja Praktek</th>
                                                <th>Status Bimbingan</th>
                                                <th>Terakhir Bimbingan</th>
                                                <th>Tanggal Pendaftaran KP</th>
                                                <th>Dosen Pembimbing</th>
                                                <th>Penguji Seminar</th>
                                                <th>Tanggal Seminar KP</th>
                                                <th>Tanggal Jilid KP</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $no = 1;
                                            ?>
                                            <?php $__currentLoopData = $mahasiswas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mahasiswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if(!$mahasiswa->jilidKP): ?>
                                                    <?php
                                                        $pendaftaran_acc = \App\Models\KP\Pendaftaran::where(
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
                                                            ->pengajuansKP()
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
                                                                <?php if(count($mahasiswa->bimbingansKP) != 0): ?>
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
                                                                <?php if($mahasiswa->bimbingansKP()->orderBy('created_at', 'desc')->whereIn('status', ['revisi', 'review', 'diterima'])->first()): ?>
                                                                    <?php echo e(\App\Helpers\AppHelper::parse_date_export($mahasiswa->bimbingansKP()->orderBy('tanggal_bimbingan', 'desc')->first()->tanggal_bimbingan)); ?>

                                                                <?php endif; ?>
                                                            </td>

                                                            <td>
                                                                <?php if($mahasiswa->pendaftaransKP()->where('status', 'diterima')->first()): ?>
                                                                    <?php echo e(\App\Helpers\AppHelper::parse_date_export($mahasiswa->pendaftaransKP()->where('status', 'diterima')->first()->created_at)); ?>

                                                                <?php endif; ?>
                                                            </td>

                                                            
                                                            <td>
                                                                <?php
                                                                    $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
                                                                ?>
                                                                <?php if($dosen_pembimbing): ?>
                                                                    <small><b><?php echo e($dosen_pembimbing->nama . ', ' . $dosen_pembimbing->gelar); ?></b></small>
                                                                    <?php
                                                                        $bimbingansGrouped = $mahasiswa->bimbingansKP()->orderBy('id', 'desc')->get()->unique('bagian_id')->sortBy('bagian_id');
                                                                    ?>
                                                                    <?php $__currentLoopData = $bimbingansGrouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bimbingan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <small>
                                                                            <?php if(\App\Helpers\AppHelper::instance()->cekBagianIsAccKP($bimbingan->id)): ?>
                                                                                <span class="text-success"><?php echo e($bimbingan->bagian->bagian); ?>(ACC)</span>
                                                                            <?php else: ?>
                                                                                <span><?php echo e($bimbingan->bagian->bagian); ?></span>
                                                                            <?php endif; ?>
                                                                        </small>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                <?php else: ?>
                                                                    <span class="badge bg-secondary">BELUM ADA PEMBIMBING</span>
                                                                <?php endif; ?>
                                                            </td>

                                                            
                                                            <td>
                                                                <?php if($mahasiswa->seminarKP && $mahasiswa->seminarKP->reviews): ?>
                                                                    <?php $__currentLoopData = $mahasiswa->seminarKP->reviews()->where('dosen_status', 'penguji')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <small><?php echo e($review->dosen->nama); ?>, <?php echo e($review->dosen->gelar); ?></small><br>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                <?php endif; ?>
                                                            </td>

                                                            <td>
                                                                <?php if($mahasiswa->seminarKP): ?>
                                                                    <?php echo e($mahasiswa->seminarKP->tanggal_ujian ? \App\Helpers\AppHelper::parse_date_export($mahasiswa->seminarKP->tanggal_ujian) : ''); ?>

                                                                <?php endif; ?>
                                                            </td>

                                                            <td>
                                                                <?php if($mahasiswa->jilidKP): ?>
                                                                    <?php echo e(\App\Helpers\AppHelper::parse_date_export($mahasiswa->jilidKP->created_at)); ?>

                                                                <?php endif; ?>
                                                            </td>

                                                            <td>
                                                                <?php if(!empty($reviewRoute) && count($mahasiswa->bimbingansKP) != 0): ?>
                                                                    <a href="<?php echo e(route($reviewRoute, $mahasiswa->pengajuansKP()->where('status', 'diterima')->first()->id)); ?>"
                                                                        class="btn btn-primary btn-sm"><i
                                                                            class="bi bi-info-circle"></i>
                                                                        Detail Bimbingan</a>
                                                                <?php endif; ?>
                                                                <?php if($canCancelBimbingan && count($mahasiswa->bimbingansKP) != 0): ?>
                                                                    <a href="<?php echo e(route('kp.bimbingan.canceled', $mahasiswa->id)); ?>"
                                                                        class="btn btn-danger btn-sm"
                                                                        onclick="return confirm('Yakin ingin membatalkan bimbingan?')"><i
                                                                            class="bi bi-x-circle"></i>
                                                                        Batalkan Bimbingan</a>
                                                                <?php endif; ?>
                                                                <?php if($mahasiswa->pendaftaransKP()->where('status', 'diterima')->first()): ?>
                                                                    <a href="<?php echo e(url('kp/cetak/surat-tugas-bimbingan/' . $mahasiswa->pendaftaransKP()->where('status', 'diterima')->first()->id)); ?>"
                                                                        target="_blank" class="btn btn-success btn-sm "><i
                                                                            class="fas fa-download"></i> Surat Tugas Bimbingan
                                                                        KP</a>
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
                                                <th>Judul Kerja Praktek</th>
                                                <th>Status Bimbingan</th>
                                                <th>Terakhir Bimbingan</th>
                                                <th>Tanggal Pendaftaran KP</th>
                                                <th>Dosen Pembimbing</th>
                                                <th>Penguji Seminar</th>
                                                <th>Tanggal Seminar KP</th>
                                                <th>Tanggal Jilid KP</th>
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
                                                <th>Judul Kerja Praktek</th>
                                                <th>Status Bimbingan</th>
                                                <th>Terakhir Bimbingan</th>
                                                <th>Tanggal Pendaftaran KP</th>
                                                <th>Dosen Pembimbing</th>
                                                <th>Penguji Seminar</th>
                                                <th>Tanggal Seminar KP</th>
                                                <th>Tanggal Jilid KP</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $no = 1;
                                            ?>
                                            <?php $__currentLoopData = $mahasiswas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mahasiswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($mahasiswa->jilidKP || count($mahasiswa->bimbingan_canceledsKP) != 0): ?>
                                                    <?php
                                                        $pendaftaran_acc = \App\Models\KP\Pendaftaran::where(
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
                                                            <?php if($mahasiswa->jilidKP): ?>
                                                                <?php echo e($mahasiswa->pengajuansKP()->where('status', 'diterima')->first()->judul); ?>

                                                            <?php else: ?>
                                                                <?php echo e($mahasiswa->bimbingan_canceledsKP()->orderBy('created_at', 'desc')->first()->pengajuan->judul); ?>

                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if(count($mahasiswa->bimbingan_canceledsKP) != 0 && $mahasiswa->jilidKP): ?>
                                                                <span class="badge bg-success">SELESAI</span>
                                                            <?php elseif(count($mahasiswa->bimbingan_canceledsKP) != 0): ?>
                                                                <span class="badge bg-danger">DIBATALKAN</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-success">SELESAI</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if($mahasiswa->bimbingansKP()->orderBy('created_at', 'desc')->whereIn('status', ['revisi', 'review', 'diterima'])->first()): ?>
                                                                <?php echo e(\App\Helpers\AppHelper::parse_date_export($mahasiswa->bimbingansKP()->orderBy('tanggal_bimbingan', 'desc')->first()->tanggal_bimbingan)); ?>

                                                            <?php endif; ?>
                                                        </td>

                                                        <td>
                                                            <?php if($mahasiswa->pendaftaransKP()->where('status', 'diterima')->first()): ?>
                                                                <?php echo e(\App\Helpers\AppHelper::parse_date_export($mahasiswa->pendaftaransKP()->where('status', 'diterima')->first()->created_at)); ?>

                                                            <?php endif; ?>
                                                        </td>

                                                        
                                                        <td>
                                                            <?php if($mahasiswa->jilidKP): ?>
                                                                <?php
                                                                    $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
                                                                ?>
                                                                <?php if($dosen_pembimbing): ?>
                                                                    <small><b><?php echo e($dosen_pembimbing->nama . ', ' . $dosen_pembimbing->gelar); ?></b></small>
                                                                    <?php
                                                                        $bimbingansGrouped2 = $mahasiswa->bimbingansKP()->orderBy('id', 'desc')->get()->unique('bagian_id')->sortBy('bagian_id');
                                                                    ?>
                                                                    <?php $__currentLoopData = $bimbingansGrouped2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bimbingan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <small>
                                                                            <?php if(\App\Helpers\AppHelper::instance()->cekBagianIsAccKP($bimbingan->id)): ?>
                                                                                <span class="text-success"><?php echo e($bimbingan->bagian->bagian); ?>(ACC)</span>
                                                                            <?php else: ?>
                                                                                <span><?php echo e($bimbingan->bagian->bagian); ?></span>
                                                                            <?php endif; ?>
                                                                        </small>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                <?php else: ?>
                                                                    <span class="badge bg-secondary">BELUM ADA PEMBIMBING</span>
                                                                <?php endif; ?>
                                                            <?php else: ?>
                                                                <?php
                                                                    $canceled = $mahasiswa->bimbingan_canceledsKP()->first();
                                                                ?>
                                                                <?php if($canceled && $canceled->dosen): ?>
                                                                    <?php echo e($canceled->dosen->nama . ', ' . $canceled->dosen->gelar); ?>

                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        </td>

                                                        
                                                        <td>
                                                            <?php if($mahasiswa->seminarKP && $mahasiswa->seminarKP->reviews): ?>
                                                                <?php $__currentLoopData = $mahasiswa->seminarKP->reviews()->where('dosen_status', 'penguji')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <small><?php echo e($review->dosen->nama); ?>, <?php echo e($review->dosen->gelar); ?></small><br>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            <?php endif; ?>
                                                        </td>

                                                        <td>
                                                            <?php if($mahasiswa->seminarKP): ?>
                                                                <?php echo e($mahasiswa->seminarKP->tanggal_ujian ? \App\Helpers\AppHelper::parse_date_export($mahasiswa->seminarKP->tanggal_ujian) : ''); ?>

                                                            <?php endif; ?>
                                                        </td>

                                                        <td>
                                                            <?php if($mahasiswa->jilidKP): ?>
                                                                <?php echo e(\App\Helpers\AppHelper::parse_date_export($mahasiswa->jilidKP->created_at)); ?>

                                                            <?php endif; ?>
                                                        </td>

                                                        <td>
                                                            <?php if(!empty($reviewRoute) && count($mahasiswa->bimbingansKP) != 0): ?>
                                                                <a href="<?php echo e(route($reviewRoute, $mahasiswa->pengajuansKP()->where('status', 'diterima')->first()->id)); ?>"
                                                                    class="btn btn-primary btn-sm"><i
                                                                        class="bi bi-info-circle"></i>
                                                                    Detail Bimbingan</a>
                                                            <?php endif; ?>
                                                            <?php if($mahasiswa->pendaftaransKP()->where('status', 'diterima')->first()): ?>
                                                                <a href="<?php echo e(url('kp/cetak/surat-tugas-bimbingan/' . $mahasiswa->pendaftaransKP()->where('status', 'diterima')->first()->id)); ?>"
                                                                    target="_blank" class="btn btn-success btn-sm "><i
                                                                        class="fas fa-download"></i> Surat Tugas Bimbingan
                                                                    KP</a>
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
                                                <th>Judul Kerja Praktek</th>
                                                <th>Status Bimbingan</th>
                                                <th>Terakhir Bimbingan</th>
                                                <th>Tanggal Pendaftaran KP</th>
                                                <th>Dosen Pembimbing</th>
                                                <th>Penguji Seminar</th>
                                                <th>Tanggal Seminar KP</th>
                                                <th>Tanggal Jilid KP</th>
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




<?php echo $__env->make('kp.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/admin/bimbingan/bimbingan.blade.php ENDPATH**/ ?>