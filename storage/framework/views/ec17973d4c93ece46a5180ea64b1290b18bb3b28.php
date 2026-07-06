<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Form Pendaftaran Tugas Akhir</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Pendaftaran TA</a></li>
                        <li class="breadcrumb-item active">Form Pendaftaran TA</li>
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
                            <h3 class="card-title">Form Pendaftaran Tugas Akhir</h3>
                        </div>
                        <div class="card-body">
                            <?php
                                $selectedBiaya = old('biaya');
                            ?>
                            <form action="<?php echo e(route('pendaftaran.store')); ?>" method="post" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">NIM</label>
                                    <input type="text" class="form-control"
                                        value="<?php echo e(Auth::guard('mahasiswa')->user()->nim); ?>" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Nama Lengkap</label>
                                    <input type="text" class="form-control"
                                        value="<?php echo e(Auth::guard('mahasiswa')->user()->nama); ?>" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Prodi</label>
                                    <input type="text" class="form-control"
                                        value="<?php echo e(Auth::guard('mahasiswa')->user()->prodi); ?>" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Pembimbing Utama (1) Tugas Akhir <br>
                                    </label>
                                    <input type="text" class="form-control"
                                        value="<?php echo e($dosen_utama ? $dosen_utama->nama . ', ' . $dosen_utama->gelar : 'Belum ditentukan'); ?>" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Pembimbing Pendamping (2) Tugas Akhir <br>
                                    </label>
                                    <input type="text" class="form-control"
                                        value="<?php echo e($dosen_pendamping ? $dosen_pendamping->nama . ', ' . $dosen_pendamping->gelar : 'Belum ditentukan'); ?>" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Judul Tugas Akhir</label>
                                    <input type="text" class="form-control" value="<?php echo e($pengajuan->judul); ?>" disabled>
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
                                                name="lampiran_1" required>
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
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Bukti Lembar Pernyataan Keaslian Hasil Tugas
                                        Akhir
                                        <br>
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
                                                name="lampiran_2" required>
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
                                                name="lampiran_3" required>
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
                                                name="lampiran_4" required>
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
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputFile">Bukti Pembayaran Tugas Akhir <br>
                                        <small>Pembayaran melalui SIMA UNSIQ.</small> </label>
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
                                                name="lampiran_5" required>
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
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tanggal Pembayaran</label>
                                    <input type="date"
                                        class="form-control <?php $__errorArgs = ['tanggal_pembayaran'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        name="tanggal_pembayaran" value="<?php echo e(old('tanggal_pembayaran')); ?>"
                                        placeholder="Tanggal Pembayaran.." required>
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
                                    <label for="exampleInputEmail1">BIAYA TUGAS AKHIR (TA) / SKRIPSI
                                    </label>
                                    <?php $__currentLoopData = $biayaOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $biayaOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input <?php $__errorArgs = ['biaya'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                type="radio" name="biaya" value="<?php echo e($biayaOption['value']); ?>"
                                                @checked((string) $selectedBiaya === (string) $biayaOption['value'])>
                                            <label class="form-check-label" style="top: -1px; position:relative;">
                                                <?php echo e($biayaOption['label']); ?>

                                            </label>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php $__errorArgs = ['biaya'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-success" id="btn-submit"
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

<?php echo $__env->make('layouts.dashboardMahasiswa', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/pages/mahasiswa/pendaftaran/create.blade.php ENDPATH**/ ?>