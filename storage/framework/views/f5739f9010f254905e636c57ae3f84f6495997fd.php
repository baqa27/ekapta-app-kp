

<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo e($title); ?></h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#"><?php echo e($title); ?></a></li>
                        <li class="breadcrumb-item active">Home</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Tabel <?php echo e($title); ?></h3>
                        </div>
                        <div class="card-body">

                            <button type="button" class="btn btn-success btn-sm shadow mb-2 mr-1" data-toggle="modal"
                                data-target="#modal-tambah">
                                <i class="bi bi-plus-circle mr-1"></i> Tambah Prodi
                            </button>

                            <button type="button" class="btn btn-primary btn-sm shadow mb-2 mr-1" data-toggle="modal"
                                data-target="#modal-import">
                                <i class="bi bi-upload mr-1"></i> Import Data Prodi
                            </button>

                            <?php if(session('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show mt-2">
                                <?php echo e(session('success')); ?>

                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                            </div>
                            <?php endif; ?>

                            <table id="example1" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Prodi</th>
                                        <th>Nama Prodi</th>
                                        <th>Jenjang</th>
                                        <th>Bagian Bimbingan KP</th>
                                        <th>Bagian Bimbingan TA</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $no = 1;
                                    ?>
                                    <?php $__currentLoopData = $prodis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prodi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($no++); ?></td>
                                            <td><?php echo e($prodi->kode); ?></td>
                                            <td><?php echo e($prodi->namaprodi); ?></td>
                                            <td><?php echo e($prodi->jenjang); ?></td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <span
                                                        class="badge <?php echo e(count($prodi->bagiansKP) == 0 ? 'bg-danger' : 'bg-success'); ?>"><?php echo e(count($prodi->bagiansKP)); ?></span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <span
                                                        class="badge <?php echo e(count($prodi->bagians) == 0 ? 'bg-danger' : 'bg-success'); ?>"><?php echo e(count($prodi->bagians)); ?></span>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="<?php echo e(url('/kp/prodi/' . $prodi->id)); ?>"
                                                    class="btn btn-primary btn-sm shadow">
                                                    <i class="fas fa-plus mr-1"></i> Bagian Bimbingan
                                                </a>
                                                <a href="<?php echo e(route('prodi.presentase.nilai' , $prodi->id)); ?>"
                                                    class="btn btn-warning btn-sm shadow">
                                                    <i class="fas fa-star"></i> Presentase TA
                                                </a>
                                                <a href="<?php echo e(route('prodi.presentase.nilai.kp' , $prodi->id)); ?>"
                                                    class="btn btn-info btn-sm shadow">
                                                    <i class="fas fa-star"></i> Presentase KP
                                                </a>
                                                <a href="<?php echo e(route('prodi.reset.password' , $prodi->id)); ?>"
                                                    class="btn btn-danger btn-sm shadow" onclick="return confirm('Yakin ingin reset password?')">
                                                    <i class="fas fa-history"></i> Reset Password
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Prodi</th>
                                        <th>Nama Prodi</th>
                                        <th>Jenjang</th>
                                        <th>Bagian Bimbingan KP</th>
                                        <th>Bagian Bimbingan TA</th>
                                        <th>Aksi</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- /.content -->

    <!-- Modal Tambah -->
    <div class="modal fade" id="modal-tambah">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?php echo e(route('prodi.store')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Prodi</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Kode Prodi <span class="text-danger">*</span></label>
                            <input type="text" name="kode" class="form-control" required placeholder="Contoh: TI">
                        </div>
                        <div class="form-group">
                            <label>Nama Prodi <span class="text-danger">*</span></label>
                            <input type="text" name="namaprodi" class="form-control" required placeholder="Contoh: Teknik Informatika">
                        </div>
                        <div class="form-group">
                            <label>Jenjang <span class="text-danger">*</span></label>
                            <select name="jenjang" class="form-control" required>
                                <option value="">-- Pilih Jenjang --</option>
                                <option value="D3">D3</option>
                                <option value="S1">S1</option>
                                <option value="S2">S2</option>
                                <option value="S3">S3</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>NIDN Kaprodi</label>
                            <input type="text" name="kodekaprodi" class="form-control" placeholder="Masukkan NIDN Kaprodi (misal: 0601018501)">
                            <small class="text-muted">NIDN dosen yang menjadi Kaprodi untuk ditampilkan di surat</small>
                        </div>
                        <div class="form-group">
                            <label>Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required minlength="6">
                        </div>
                        <div class="form-group">
                            <label>Fakultas</label>
                            <select name="fakultas_id" class="form-control">
                                <option value="">-- Pilih Fakultas --</option>
                                <?php $__currentLoopData = \App\Models\Fakultas::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fakultas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($fakultas->id); ?>"><?php echo e($fakultas->namafakultas); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Import -->
    <div class="modal fade" id="modal-import">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?php echo e(route('prodi.import')); ?>" method="post" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="modal-header">
                        <h4 class="modal-title">Import Data Prodi</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <a href="https://drive.google.com/drive/folders/1AD3y7NZGUvjkoyQAdyegVzVXWNB_XJqQ?usp=sharing" class="btn btn-warning btn-sm shadow" target="_blank"><i class="fas fa-download"></i> Download Template File Import</a> <br><br>
                            <label for="" class="form-label">Pilih File <br>
                                <small>Format file <b>.csv / .xlsx </b></small></label>
                            <div class="input-group mb-3">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        name="file" required>
                                    <label class="custom-file-label" for="exampleInputFile">Choose
                                        file</label>
                                </div>

                            </div>
                            <?php $__errorArgs = ['file'];
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
                        <button type="submit" class="btn btn-success">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>





<?php echo $__env->make('kp.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/admin/prodi/prodi.blade.php ENDPATH**/ ?>