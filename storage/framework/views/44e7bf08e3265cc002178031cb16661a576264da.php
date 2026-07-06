<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo e($title); ?></h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Pendaftaran KP</a></li>
                        <li class="breadcrumb-item active"><?php echo e($title); ?></li>
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
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="ribbon-wrapper ribbon-lg">
                            <div
                                class="ribbon
                            <?php if($pendaftaran->status == 'review'): ?> bg-secondary
                            <?php elseif($pendaftaran->status == 'revisi'): ?>
                            bg-warning
                            <?php elseif($pendaftaran->status == 'diterima'): ?>
                            bg-success <?php endif; ?>
                            ">
                                <?php echo e($pendaftaran->status); ?>

                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5">
                                    NIM
                                </div>
                                <div class="col-md-7">
                                    <b><?php echo e($mahasiswa->nim); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Nama Lengkap
                                </div>
                                <div class="col-md-7">
                                    <b><?php echo e($mahasiswa->nama ?? $pendaftaran->mahasiswa->nama ?? '-'); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Prodi
                                </div>
                                <div class="col-md-7">
                                <b><?php echo e($mahasiswa->prodi ?? '-'); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Tahun Masuk
                                </div>
                                <div class="col-md-7">
                                <b><?php echo e($mahasiswa->thmasuk ?? '-'); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Dosen Pembimbing Kerja Praktek
                                </div>
                                <div class="col-md-7">
                                    <b><?php echo e($dosen_pembimbing ? $dosen_pembimbing->nama . ', ' . $dosen_pembimbing->gelar : 'Belum ditentukan'); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Judul Kerja Praktek
                                </div>
                                <div class="col-md-7">
                                    <b><?php echo e($pendaftaran->pengajuan->judul); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Status Pendaftaran
                                </div>
                                <div class="col-md-7">
                                    <b><?php echo e($pendaftaran->status_pendaftaran_label); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Email
                                </div>
                                <div class="col-md-7">
                                <b><?php echo e($mahasiswa->email ?? '-'); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    No. HP
                                </div>
                                <div class="col-md-7">
                                <b><?php echo e($mahasiswa->hp ?? '-'); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Semester
                                </div>
                                <div class="col-md-7">
                                    <b><?php echo e(\App\Helpers\AppHelper::instance()->getMahasiswaDetail($pendaftaran->nim) != null ? \App\Helpers\AppHelper::instance()->getMahasiswaDetail($pendaftaran->nim)->semester : ''); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Dokumen Acc. Kaprodi
                                </div>
                                <div class="col-md-7">
                                    <a href="<?php echo e(storage_url($pendaftaran->lampiran_1)); ?>" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        <?php echo e(Str::substr($pendaftaran->lampiran_1, 40)); ?></a>
                                </div>
                            </div>
                            <hr>



                            <div class="row">
                                <div class="col-md-5">
                                    Bukti Transkrip Nilai
                                </div>
                                <div class="col-md-7">
                                    <a href="<?php echo e(storage_url($pendaftaran->lampiran_2)); ?>" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        <?php echo e(Str::substr($pendaftaran->lampiran_2, 40)); ?></a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Sertifikat KKL
                                </div>
                                <div class="col-md-7">
                                    <a href="<?php echo e(storage_url($pendaftaran->lampiran_3)); ?>" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        <?php echo e(Str::substr($pendaftaran->lampiran_3, 40)); ?></a>
                                </div>
                            </div>
                            <hr>



                            <div class="row">
                                <div class="col-md-5">
                                    Bukti Pembayaran Kerja Praktek
                                </div>
                                <div class="col-md-7">
                                    <a href="<?php echo e(storage_url($pendaftaran->lampiran_5)); ?>" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        <?php echo e(Str::substr($pendaftaran->lampiran_5, 40)); ?></a>
                                </div>
                            </div>
                            <hr>



                            <div class="row">
                                <div class="col-md-5">
                                    Bukti Diterima Instansi
                                </div>
                                <div class="col-md-7">
                                    <a href="<?php echo e(storage_url($pendaftaran->lampiran_7)); ?>" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        <?php echo e(Str::substr($pendaftaran->lampiran_7, 40)); ?></a>
                                </div>
                            </div>
                            <hr>

                            <?php if($pendaftaran->dokumen_pendukung): ?>
                            <div class="row">
                                <div class="col-md-5">
                                    Dokumen Pendukung
                                </div>
                                <div class="col-md-7">
                                    <a href="<?php echo e(storage_url($pendaftaran->dokumen_pendukung)); ?>" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        <?php echo e(Str::substr($pendaftaran->dokumen_pendukung, 40)); ?></a>
                                </div>
                            </div>
                            <hr>
                            <?php endif; ?>

                            <?php if($pendaftaran->lampiran_8): ?>
                            <div class="row">
                                <div class="col-md-5">
                                    Dokumen Lainnya
                                </div>
                                <div class="col-md-7">
                                    <a href="<?php echo e(storage_url($pendaftaran->lampiran_8)); ?>" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        <?php echo e(Str::substr($pendaftaran->lampiran_8, 40)); ?></a>
                                </div>
                            </div>
                            <hr>
                            <?php endif; ?>

                            

                            <div class="row">
                                <div class="col-md-5">
                                    Tanggal Pembayaran
                                </div>
                                <div class="col-md-7">
                                    <b><?php echo e(\Carbon\Carbon::parse($pendaftaran->tanggal_pembayaran)->format('d-m-Y')); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Biaya
                                </div>
                                <div class="col-md-7">
                                    <span class="text-success fs-5">Rp. <?php echo e(number_format((float) $pendaftaran->biaya, 0, ',', '.')); ?>,-</span>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Tanggal Pendaftaran
                                </div>
                                <div class="col-md-7">
                                    <b><?php echo e($pendaftaran->created_at->format('d M Y H:m')); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Tanggal Validasi
                                </div>
                                <div class="col-md-7">
                                    <?php if($pendaftaran->tanggal_acc): ?>
                                        <b><?php echo e(date('d M Y H:m', strtotime($pendaftaran->tanggal_acc))); ?></b>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <?php if($pendaftaran->status == 'diterima'): ?>
                                <hr>
                                <div class="row">
                                    <div class="col-md-5">
                                        Surat Tugas Bimbingan
                                    </div>
                                    <div class="col-md-7">

                                        <a href="<?php echo e(url('kp/cetak/surat-tugas-bimbingan/' . $pendaftaran->id)); ?>"
                                            target="_blank"><i class="fas fa-download"></i> Surat tugas Bimbingan KP</a>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>

                        <div class="card-footer">
                            <div class="d-flex">
                                <?php if($pendaftaran->status == 'review'): ?>
                                    <a href="<?php echo e(route('kp.pendaftaran.admin')); ?>" class="btn btn-secondary mr-2">
                                            <i class="bi bi-arrow-left mr-2"></i> Kembali
                                    </a>
                                    <button type="button" class="btn btn-primary mr-2" data-toggle="modal"
                                        data-target="#modal-revisi">
                                        <i class="bi bi-pencil-square mr-2"></i> Revisi Pendaftaran
                                    </button>

                                    

                                    <!-- Modal Confirm Acc -->
                                    <button type="button" class="btn btn-success"
                                        data-toggle="modal" data-target="#modal-confirm-acc">
                                        <i class="fas fa-check mr-2"></i> Acc Pendaftaran
                                    </button>

                                    <div class="modal fade" id="modal-confirm-acc">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="<?php echo e(route('kp.pendaftaran.acc')); ?>" method="post">
                                                    <?php echo csrf_field(); ?>

                                                    <input type="hidden" name="id" value="<?php echo e($pendaftaran->id); ?>">

                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Konfirmasi Acc Pendaftaran Kerja Praktek</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-5">
                                                                NIM
                                                            </div>
                                                            <div class="col-md-7">
                                                                <b><?php echo e($mahasiswa->nim ?? '-'); ?></b>
                                                            </div>
                                                        </div>
                                                        <hr>

                                                        <div class="row">
                                                            <div class="col-md-5">
                                                                Nama Lengkap
                                                            </div>
                                                            <div class="col-md-7">
                                                                <b><?php echo e($mahasiswa->nama ?? '-'); ?></b>
                                                            </div>
                                                        </div>
                                                        <hr>

                                                        <div class="row">
                                                            <div class="col-md-5">
                                                                Prodi
                                                            </div>
                                                            <div class="col-md-7">
                                                                <b><?php echo e($mahasiswa->prodi ?? '-'); ?></b>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="form-group">
                                                            <label for="" class="form-label text-danger">Mahasiswa akan tergabung pada bimbingan dengan tahun masuk:</label>
                                                            <input type="text" name="tahun_masuk" value="<?php echo e($mahasiswa->thmasuk ?? ''); ?>" class="form-control" required>
                                                        </div>
                                                        <br>
                                                        <span class="text-danger">* Jika ingin mengubah tahun masuk bimbingan, maka ubah data tahun masuk mahasiswa!</span>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="submit" class="btn btn-success">Konfirmasi</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    
                    <div class="card card-primary card-outline mt-2">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Revisi</strong>
                                <span class="badge bg-danger rounded-pill">
                                    <?php echo e(count($pendaftaran->revisis)); ?>

                                </span>
                            </h3>
                            <?php if($pendaftaran->status == 'revisi'): ?>
                                <div class="float-right">
                                    <button type="button" class="btn btn-primary mr-2" data-toggle="modal"
                                        data-target="#modal-revisi">
                                        <i class="bi bi-plus-square mr-2"></i> Tambahkan Revisi
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">

                            <?php $__currentLoopData = $revisis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $revisi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <i class="fas fa-calendar mr-2"></i>
                                        <?php echo e($revisi->created_at->format('d M Y H:m')); ?>

                                        <div class="float-right" onclick="confirmDelete()">
                                            <form action="<?php echo e(route('kp.pendaftaran.revisi.delete')); ?>" method="post">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="id" value="<?php echo e($revisi->id); ?>">
                                                <button class="btn btn-danger btn-sm float-right" type="submit">
                                                    <i class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <?php echo nl2br($revisi->catatan); ?>

                                    </div>

                                    <?php if($revisi->lampiran): ?>
                                    <div class="card-footer">
                                        Lampiran :
                                        <?php if($revisi->lampiran): ?>
                                            <a href="<?php echo e(storage_url($pendaftaran->lampiran)); ?>" class="ml-3" target="_blank"><i
                                                    class="fas fa-paperclip"></i>
                                                <?php echo e(Str::substr($revisi->lampiran, 40)); ?></a>
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </div>
                        <div class="d-flex justify-content-center mb-3">
                            <?php echo e($revisis->links()); ?>

                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->

    <!-- Modal Revisi -->
    <?php if($pendaftaran->status != 'diterima'): ?>
        <div class="modal fade" id="modal-revisi">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="<?php echo e(route('kp.pendaftaran.revisi')); ?>" method="post" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id" value="<?php echo e($pendaftaran->id); ?>">
                        <div class="modal-header">
                            <h4 class="modal-title">Revisi Pendaftaran</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="" class="form-label">Catatan</label>
                                <textarea class="form-control" name="catatan" rows="4" placeholder="Tuliskan catatan revisi..." required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="" class="form-label">Lampiran</label>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file"
                                            class="custom-file-input <?php $__errorArgs = ['lampiran'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="lampiran">
                                        <label class="custom-file-label" for="exampleInputFile">Choose
                                            file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Dokumen</span>
                                    </div>
                                </div>
                                <?php $__errorArgs = ['lampiran'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <small class="text-danger"
                                        style="position:relative;top:-15px;left:5px"><?php echo e($message); ?></small>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>





<?php echo $__env->make('kp.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/admin/pendaftaran/review.blade.php ENDPATH**/ ?>