

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
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Setting Fakultas</h3>
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row mt-3">
                                        <div class="col-md-3">
                                            Nama Fakultas
                                        </div>
                                        <div class="col-md-9">
                                            <span class="mr-3">:</span>
                                            <b><?php echo e($fakultas->namafakultas); ?></b>
                                        </div>
                                    </div>
                                </div>

                                <div class="border rounded p-2" style="min-width: 160px">
                                    <button type="button" class="btn btn-primary btn-sm mr-2 mb-1" data-toggle="modal"
                                        data-target="#modal-edit-fakultas">
                                        <i class="bi bi-pencil-square mr-2"></i> Edit Stempel Fakultas
                                    </button>
                                    <img src="<?php echo e(asset($fakultas->image != null ? $fakultas->image : 'ekapta/assets/img/not-found.png')); ?>"
                                        alt="Stempel Fakultas" height="50">
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row mt-3">
                                        <div class="col-md-3">
                                            Dekan
                                        </div>
                                        <div class="col-md-9">
                                            <span class="mr-3">:</span>
                                            <b><?php echo e($dekanActive != null ? $dekanActive->namadekan . ', ' . $dekanActive->gelar : ''); ?></b>
                                        </div>
                                    </div>
                                </div>
                                <div class="border rounded p-2" style="min-width: 160px">
                                    <button type="button" class="btn btn-primary btn-sm mr-2 mb-1" data-toggle="modal"
                                        data-target="#modal-edit">
                                        <i class="bi bi-pencil-square mr-2"></i> Edit TTD Dekan
                                    </button>
                                     <?php if($dekanActive): ?>
                                        <img src="<?php echo e(asset($dekanActive->image != null ? $dekanActive->image : 'ekapta/assets/img/not-found.png')); ?>"
                                             alt="TTD Dekan" height="50">
                                    <?php else: ?>
                                        <img src="<?php echo e(asset('ekapta/assets/img/not-found.png')); ?>"
                                             alt="TTD Dekan" height="50">
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card card-primary card-outline mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Program Studi</h3>
                        </div>
                        <div class="card-body">
                            <div class="p-2 border rounded d-flex flex-wrap">

                                <?php $__currentLoopData = $fakultas->prodis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prodi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="p-1 border border-success rounded mr-2 mb-2">
                                        <span class="mr-2"
                                            style="position: relative;top:3px"><b><?php echo e($prodi->namaprodi); ?></b></span>
                                        <div class="float-right" onclick="confirmDelete()">
                                            <form action="<?php echo e(route('fakultas.delete.prodi')); ?>" method="post">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="prodi" value="<?php echo e($prodi->id); ?>">
                                                <button class="btn btn-danger btn-sm" type="submit"><i
                                                        class="bi bi-x"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </div>

                            <div class="mt-4">
                                <table id="example1" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Prodi</th>
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
                                                <td><?php echo e($prodi->namaprodi); ?></td>
                                                <td>
                                                    <div class="d-flex justify-content-center" onclick="confirmAdd()">
                                                        <form action="<?php echo e(route('fakultas.add.prodi')); ?>" method="post">
                                                            <?php echo csrf_field(); ?>
                                                            <input type="hidden" name="fakultas"
                                                                value="<?php echo e($fakultas->id); ?>">
                                                            <input type="hidden" name="prodi"
                                                                value="<?php echo e($prodi->id); ?>">
                                                            <button class="btn btn-success btn-sm"><i
                                                                    class="bi bi-plus-circle mr-1"></i>
                                                                Tambahkan</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Prodi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-primary card-outline mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Dekan Fakultas</h3>
                        </div>
                        <div class="card-body">

                            <button type="button" class="btn btn-primary mr-2" data-toggle="modal"
                                data-target="#modal-create">
                                <i class="bi bi-plus-circle mr-2"></i> Tambahkan Dekan Fakultas
                            </button>

                            <button type="button" class="btn btn-info mr-2" data-toggle="modal"
                                data-target="#modal-import">
                                <i class="fas fa-upload mr-2"></i> Import Dekan Fakultas
                            </button>

                            <div class="mt-3">
                                <table id="example2" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Dekan</th>
                                            <th>Periode</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $no = 1;
                                        ?>
                                        <?php $__currentLoopData = $fakultas->dekans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dekan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($no++); ?></td>
                                                <td><?php echo e($dekan->namadekan . ', ' . $dekan->gelar . ' (' . $dekan->nidn . ')'); ?>

                                                </td>
                                                <td>
                                                    <?php echo e(\Carbon\Carbon::parse($dekan->dari)->translatedFormat('d F Y') . ' - ' . \Carbon\Carbon::parse($dekan->sampai)->translatedFormat('d F Y')); ?>

                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <?php if($dekan->status == null): ?>
                                                            <?php if($dekanActive == null): ?>
                                                                <div onclick="confirmActive()" class="mr-1">
                                                                    <form action="<?php echo e(route('dekan.enabled')); ?>"
                                                                        method="post">
                                                                        <?php echo csrf_field(); ?>
                                                                        <input type="hidden" name="dekan"
                                                                            value="<?php echo e($dekan->id); ?>">
                                                                        <button class="btn btn-success btn-sm"><i
                                                                                class="bi bi-check-circle mr-1"></i>
                                                                            Enable
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            <?php endif; ?>
                                                            <div onclick="confirmDelete()">
                                                                <form action="<?php echo e(route('dekan.delete')); ?>"
                                                                    method="post">
                                                                    <?php echo csrf_field(); ?>
                                                                    <input type="hidden" name="dekan"
                                                                        value="<?php echo e($dekan->id); ?>">
                                                                    <button class="btn btn-danger btn-sm"><i
                                                                            class="bi bi-trash mr-1"></i>
                                                                        Hapus
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        <?php elseif($dekan->status == 'active'): ?>
                                                            <div class="d-flex">
                                                                <button type="button" class="btn btn-primary btn-sm mr-1"
                                                                    data-toggle="modal" data-target="#modal-edit">
                                                                    <i class="bi bi-pencil-square"></i>
                                                                </button>

                                                                <button type="button" class="btn btn-info btn-sm mr-1"
                                                                    data-toggle="modal" data-target="#modal-detail">
                                                                    <i class="fas fa-info-circle"></i>
                                                                </button>

                                                                <div onclick="confirmDisable()">
                                                                    <form action="<?php echo e(route('dekan.disabled')); ?>"
                                                                        method="post">
                                                                        <?php echo csrf_field(); ?>
                                                                        <input type="hidden" name="dekan"
                                                                            value="<?php echo e($dekan->id); ?>">
                                                                        <button class="btn btn-danger btn-sm"><i
                                                                                class="bi bi-x-circle"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Dekan</th>
                                            <th>Periode</th>
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
        </div>
    </section>

    <!-- Modal Create -->
    <div class="modal fade" id="modal-create">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?php echo e(route('dekan.store')); ?>" method="post" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>

                    <input type="hidden" name="fakultas_id" value="<?php echo e($fakultas->id); ?>">

                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Dekan Fakultas</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="" class="form-label">NIDN</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['nidn'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="nidn"
                                placeholder="NIDN dekan..." value="<?php echo e(old('nidn')); ?>" required>
                            <?php $__errorArgs = ['nidn'];
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
                            <label for="" class="form-label">Nama Dekan</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['namadekan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                name="namadekan" placeholder="Nama dekan fakultas..." value="<?php echo e(old('namadekan')); ?>"
                                required>
                            <?php $__errorArgs = ['namadekan'];
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
                            <label for="" class="form-label">Gelar</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['gelar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                name="gelar" placeholder="Gelar..." value="<?php echo e(old('gelar')); ?>" required>
                            <?php $__errorArgs = ['gelar'];
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

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="" class="form-label">Periode Dari</label>
                                <input type="date" class="form-control <?php $__errorArgs = ['dari'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    name="dari" value="<?php echo e(old('dari')); ?>" required>
                                <?php $__errorArgs = ['dari'];
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
                            <div class="form-group col-md-6">
                                <label for="" class="form-label">Periode Sampai</label>
                                <input type="date" class="form-control <?php $__errorArgs = ['sampai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    name="sampai" value="<?php echo e(old('sampai')); ?>" required>
                                <?php $__errorArgs = ['sampai'];
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
                        </div>
                        <div class="form-group">
                            <label for="" class="form-label">Pilih Gambar TTD Dekan</label>
                            <div class="input-group mb-3">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        name="image" required>
                                    <label class="custom-file-label" for="exampleInputFile">Choose
                                        file</label>
                                </div>
                                
                            </div>
                            <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger"><small><?php echo e($message); ?></small></div>
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

    <!-- Modal Import -->
    <div class="modal fade" id="modal-import">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?php echo e(route('dekan.import')); ?>" method="post" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>

                    <input type="hidden" name="fakultas" value="<?php echo e($fakultas->id); ?>">

                    <div class="modal-header">
                        <h4 class="modal-title">Import Dekan Fakultas</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <a href="https://drive.google.com/drive/folders/1AD3y7NZGUvjkoyQAdyegVzVXWNB_XJqQ?usp=sharing" class="btn btn-warning btn-sm shadow" target="_blank"><i class="fas fa-download"></i> Download Template File Import</a> <br><br>
                        <div class="form-group">
                            <label for="" class="form-label">Pilih File Import<br>
                                <small>Format file : <b>.csv / .xlsx </b></small></label>
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
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
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
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <!-- Modal Edit Dekan-->
    <div class="modal fade" id="modal-edit">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?php echo e(route('dekan.update')); ?>" method="post" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="dekan" value="<?php echo e($dekanActive != null ? $dekanActive->id : ''); ?>">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Dekan </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="" class="form-label">Pilih Gambar TTD Dekan</label>
                            <div class="input-group mb-3">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        name="image" required>
                                    <label class="custom-file-label" for="exampleInputFile">Choose
                                        file</label>
                                </div>
                                
                            </div>
                            <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger"><small><?php echo e($message); ?></small></div>
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

    <!-- Modal Edit Fakultas-->
    <div class="modal fade" id="modal-edit-fakultas">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?php echo e(route('fakultas.update')); ?>" method="post" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="fakultas" value="<?php echo e($fakultas->id); ?>">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Fakultas </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="" class="form-label">Pilih Gambar Stempel Fakultas</label>
                            <div class="input-group mb-3">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        name="image" required>
                                    <label class="custom-file-label" for="exampleInputFile">Choose
                                        file</label>
                                </div>
                                
                            </div>
                            <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger"><small><?php echo e($message); ?></small></div>
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

    
    <div class="modal fade" id="modal-detail">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Detail Dekan Fakultas</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="" class="form-label">NIDN</label>
                        <input type="text" class="form-control"
                            value="<?php echo e($dekanActive != null ? $dekanActive->nidn : ''); ?>" disabled>
                    </div>

                    <div class="form-group">
                        <label for="" class="form-label">Nama Dekan</label>
                        <input type="text" class="form-control"
                            value="<?php echo e($dekanActive != null ? $dekanActive->namadekan . ', ' . $dekanActive->gelar : ''); ?>"
                            disabled>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="" class="form-label">Periode Dari</label>
                            <input type="text"
                                value="<?php echo e($dekanActive != null ? \Carbon\Carbon::parse($dekanActive->dari)->translatedFormat('d F Y') : ''); ?>"
                                class="form-control" disabled>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="" class="form-label">Periode Sampai</label>
                            <input type="text"
                                value="<?php echo e($dekanActive != null ? \Carbon\Carbon::parse($dekanActive->sampai)->translatedFormat('d F Y') : ''); ?>"
                                class="form-control" disabled>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mt-2">
                        <?php if($dekanActive): ?>
                            <img src="<?php echo e(asset($dekanActive->image != null ? $dekanActive->image : 'ekapta/assets/img/not-found.png')); ?>"
                                alt="TTD Dekan" height="150">
                        <?php endif; ?>
                    </div>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
<?php $__env->stopSection(); ?>





<?php echo $__env->make('kp.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/admin/fakultas/setting.blade.php ENDPATH**/ ?>