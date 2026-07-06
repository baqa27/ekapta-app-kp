

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
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Tabel <?php echo e($title); ?></h3>
                        </div>
                        <div class="card-body">

                            <button type="button" class="btn btn-success col-md-3 col-sm-12 mb-2" data-toggle="modal"
                                data-target="#modal-tambah">
                                <i class="bi bi-plus-circle mr-2"></i> Tambah Himpunan
                            </button>

                            <button type="button" class="btn btn-primary col-md-3 col-sm-12 mb-2" data-toggle="modal"
                                data-target="#modal-import">
                                <i class="bi bi-upload mr-2"></i> Import Data Himpunan
                            </button>

                            <table id="example1" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Username</th>
                                        <th>Nama Himpunan</th>
                                        <th>Email</th>
                                        <th>Prodi</th>
                                        <th>Pendaftaran Seminar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    <?php $__currentLoopData = $himpunans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $himpunan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($no++); ?></td>
                                            <td><code><?php echo e($himpunan->username); ?></code></td>
                                            <td><?php echo e($himpunan->nama); ?></td>
                                            <td><?php echo e($himpunan->email ?? '-'); ?></td>
                                            <td><?php echo e($himpunan->prodi->namaprodi ?? '-'); ?></td>
                                            <td>
                                                <?php if($himpunan->is_pendaftaran_seminar_open): ?>
                                                <span class="badge bg-success">Dibuka</span>
                                                <?php else: ?>
                                                <span class="badge bg-secondary">Ditutup</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm shadow" 
                                                    data-toggle="modal" data-target="#modal-edit"
                                                    data-id="<?php echo e($himpunan->id); ?>"
                                                    data-nama="<?php echo e($himpunan->nama); ?>"
                                                    data-username="<?php echo e($himpunan->username); ?>"
                                                    data-email="<?php echo e($himpunan->email); ?>"
                                                    data-prodi_id="<?php echo e($himpunan->prodi_id); ?>">
                                                    <i class="bi bi-gear mr-1"></i> Edit
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm shadow"
                                                    data-toggle="modal" data-target="#modal-hapus"
                                                    data-id="<?php echo e($himpunan->id); ?>"
                                                    data-nama="<?php echo e($himpunan->nama); ?>">
                                                    <i class="bi bi-trash mr-1"></i> Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Username</th>
                                        <th>Nama Himpunan</th>
                                        <th>Email</th>
                                        <th>Prodi</th>
                                        <th>Pendaftaran Seminar</th>
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
                <form action="<?php echo e(route('kp.himpunan.store')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Himpunan</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Himpunan <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" required placeholder="Contoh: HIMATIF - Himpunan Mahasiswa Teknik Informatika">
                        </div>
                        <div class="form-group">
                            <label>Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control" required placeholder="Contoh: himatif">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Contoh: himatif@unsiq.ac.id">
                        </div>
                        <div class="form-group">
                            <label>Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required minlength="6">
                        </div>
                        <div class="form-group">
                            <label>Prodi <span class="text-danger">*</span></label>
                            <select name="prodi_id" class="form-control" required>
                                <option value="">-- Pilih Prodi --</option>
                                <?php $__currentLoopData = $prodis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prodi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($prodi->id); ?>"><?php echo e($prodi->namaprodi); ?></option>
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

    <!-- Modal Edit -->
    <div class="modal fade" id="modal-edit">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?php echo e(route('kp.himpunan.update')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id" id="edit-id">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Himpunan</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Himpunan <span class="text-danger">*</span></label>
                            <input type="text" name="nama" id="edit-nama" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" id="edit-username" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" id="edit-email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Password <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                            <input type="password" name="password" class="form-control" minlength="6">
                        </div>
                        <div class="form-group">
                            <label>Prodi <span class="text-danger">*</span></label>
                            <select name="prodi_id" id="edit-prodi_id" class="form-control" required>
                                <option value="">-- Pilih Prodi --</option>
                                <?php $__currentLoopData = $prodis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prodi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($prodi->id); ?>"><?php echo e($prodi->namaprodi); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Hapus -->
    <div class="modal fade" id="modal-hapus">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?php echo e(route('kp.himpunan.delete')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id" id="hapus-id">
                    <div class="modal-header">
                        <h4 class="modal-title">Hapus Himpunan</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus himpunan <strong id="hapus-nama"></strong>?</p>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Import -->
    <div class="modal fade" id="modal-import">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?php echo e(route('kp.himpunan.import')); ?>" method="post" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="modal-header">
                        <h4 class="modal-title">Import Data Himpunan</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <strong>Format CSV:</strong> nama, username, email, password, kode_prodi
                        </div>
                        <div class="form-group">
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
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                </div>
                                
                            </div>
                            <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger" style="position:relative;top:-15px;left:5px"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // Edit modal
    $('#modal-edit').on('show.bs.modal', function(e) {
        var button = $(e.relatedTarget);
        $('#edit-id').val(button.data('id'));
        $('#edit-nama').val(button.data('nama'));
        $('#edit-username').val(button.data('username'));
        $('#edit-email').val(button.data('email'));
        $('#edit-prodi_id').val(button.data('prodi_id'));
    });

    // Hapus modal
    $('#modal-hapus').on('show.bs.modal', function(e) {
        var button = $(e.relatedTarget);
        $('#hapus-id').val(button.data('id'));
        $('#hapus-nama').text(button.data('nama'));
    });
});
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('kp.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/admin/himpunan/index.blade.php ENDPATH**/ ?>