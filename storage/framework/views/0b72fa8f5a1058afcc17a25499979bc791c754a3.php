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
                        <li class="breadcrumb-item"><a href="#">Pendaftaran TA</a></li>
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
                        <div class="card-header">
                            <h3 class="card-title"><?php echo e($title); ?></h3>
                        </div>
                        <div class="card-body">
                            <form action="<?php echo e(route('pendaftaran.update')); ?>" method="post" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>

                                <input type="hidden" name="id" value="<?php echo e($pendaftaran->id); ?>">

                                <div class="form-group">
                                    <label for="exampleInputEmail1">NIM</label>
                                    <input type="text" class="form-control" value="<?php echo e($mahasiswa->nim); ?>" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Nama Lengkap</label>
                                    <input type="text" class="form-control" value="<?php echo e($mahasiswa->nama); ?>" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Prodi</label>
                                    <input type="text" class="form-control" value="<?php echo e($mahasiswa->prodi); ?>" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Pembimbing Utama (1) Tugas Akhir</label>
                                    <input type="text" class="form-control" value="<?php echo e($dosen_utama->nama.', '.$dosen_utama->gelar); ?>" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Pembimbing Pendamping (2) Tugas Akhir</label>
                                    <input type="text" class="form-control" value="<?php echo e($dosen_pendamping->nama.', '.$dosen_pendamping->gelar); ?>"
                                        disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Judul Tugas Akhir</label>
                                    <input type="text" class="form-control" value="<?php echo e($pendaftaran->pengajuan->judul); ?>" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputFile">Dokumen Acc. Kaprodi <br>
                                        <small>Download disini : <a
                                                href="<?php echo e(route('cetak.lembar.persetujuan.mahasiswa')); ?>"
                                                target="_blank">Download</a></small>
                                    </label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input <?php $__errorArgs = ['lampiran_1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                name="lampiran_1">
                                            <label class="custom-file-label" for="exampleInputFile">Choose
                                                file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    <?php $__errorArgs = ['lampiran_1'];
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
                                    <div class="rounded bg-light">
                                        <small>
                                            <span class="ml-3">Lampiran sebelumnya : </span>
                                            <a href="<?php echo e(asset($pendaftaran->lampiran_1)); ?>" class="text-primary"
                                                target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                                <?php echo e(Str::substr($pendaftaran->lampiran_1, 21)); ?></a>
                                        </small>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Bukti Lembar Pernyataan Keaslian Hasil TugasAkhir <br>
                                        <small>Download disini : <a href="<?php echo e(route('cetak.lembar.pernyataan.keaslian')); ?>"
                                                target="_blank">Download</a></small>
                                    </label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input <?php $__errorArgs = ['lampiran_2'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                name="lampiran_2">
                                            <label class="custom-file-label" for="exampleInputFile">Choose
                                                file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    <?php $__errorArgs = ['lampiran_2'];
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
                                    <div class="rounded bg-light">
                                        <small>
                                            <span class="ml-3">Lampiran sebelumnya : </span>
                                            <a href="<?php echo e(asset($pendaftaran->lampiran_2)); ?>" class="text-primary"
                                                target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                                <?php echo e(Str::substr($pendaftaran->lampiran_2, 21)); ?></a>
                                        </small>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputFile">Bukti Transkrip Nilai <br>
                                        <small>Jika SKS lulus lebih dari 120 maka pengajuan Surat Tugas
                                            Pembimbing akan diproses namun jika belum memenuhi 120 sks lulus
                                            maka pengajuan akan dibatalkan.</small> </label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input <?php $__errorArgs = ['lampiran_3'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                name="lampiran_3">
                                            <label class="custom-file-label" for="exampleInputFile">Choose
                                                file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    <?php $__errorArgs = ['lampiran_3'];
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
                                    <div class="rounded bg-light">
                                        <small>
                                            <span class="ml-3">Lampiran sebelumnya : </span>
                                            <a href="<?php echo e(asset($pendaftaran->lampiran_3)); ?>" class="text-primary"
                                                target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                                <?php echo e(Str::substr($pendaftaran->lampiran_3, 21)); ?></a>
                                        </small>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputFile">Bukti Pengumpulan KP <br>
                                        <small>bukti diminta di perpus FASTIKOM</small></label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input <?php $__errorArgs = ['lampiran_4'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                name="lampiran_4">
                                            <label class="custom-file-label" for="exampleInputFile">Choose
                                                file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    <?php $__errorArgs = ['lampiran_4'];
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
                                    <div class="rounded bg-light">
                                        <small>
                                            <span class="ml-3">Lampiran sebelumnya : </span>
                                            <a href="<?php echo e(asset($pendaftaran->lampiran_4)); ?>" class="text-primary"
                                                target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                                <?php echo e(Str::substr($pendaftaran->lampiran_4, 21)); ?></a>
                                        </small>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputFile">Bukti Pembayaran Tugas Akhir (Screenshot dari SIMA)<br>
                                        <small>Pembayaran Tugas Akhir (TA) melalui SIMA pada menu Program/Kegiatan Fakultas.</small> 
                                    </label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input <?php $__errorArgs = ['lampiran_5'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                name="lampiran_5">
                                            <label class="custom-file-label" for="exampleInputFile">Choose
                                                file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    <?php $__errorArgs = ['lampiran_5'];
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
                                    <div class="rounded bg-light">
                                        <small>
                                            <span class="ml-3">Lampiran sebelumnya : </span>
                                            <a href="<?php echo e(asset($pendaftaran->lampiran_5)); ?>" class="text-primary"
                                                target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                                <?php echo e(Str::substr($pendaftaran->lampiran_5, 21)); ?></a>
                                        </small>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Bank Pembayaran<br> 
                                        <small>Bank Pembayaran (Bank Jateng/ Finpay/ dll).</small>
                                    </label>
                                    <input type="text"
                                        class="form-control <?php $__errorArgs = ['nomor_pembayaran'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        placeholder="Masukkan Bank Pembayaran.."
                                        value="<?php echo e($pendaftaran->nomor_pembayaran); ?>" name="nomor_pembayaran" required>
                                    <?php $__errorArgs = ['nomor_pembayaran'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tanggal Pembayaran <br>
                                        <small>Tanggal Pembayaran Sebelumnya :
                                            <b><?php echo e($pendaftaran->tanggal_pembayaran); ?></b></small>
                                    </label>
                                    <input type="date"
                                        class="form-control <?php $__errorArgs = ['tanggal_pembayaran'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        name="tanggal_pembayaran" value="<?php echo e($pendaftaran->tanggal_pembayaran); ?>">
                                    <?php $__errorArgs = ['tanggal_pembayaran'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Jumlah Pembayaran TA
                                    </label>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input <?php $__errorArgs = ['biaya'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            type="radio" name="biaya" value="1100000">
                                        <label class="form-check-label" style="top: -1px; position:relative;">
                                            Baru : Rp. 1.100.000,-</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input <?php $__errorArgs = ['biaya'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            type="radio" name="biaya" value="550000">
                                        <label class="form-check-label" style="top: -1px; position:relative;">
                                            Perpanjang : Rp. 550.000,-</label>
                                    </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-success"
                                        onclick="this.disabled=true;this.innerHTML='Memproses...';this.form.submit();">Submit</button>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboardMahasiswa', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/pages/mahasiswa/pendaftaran/edit.blade.php ENDPATH**/ ?>