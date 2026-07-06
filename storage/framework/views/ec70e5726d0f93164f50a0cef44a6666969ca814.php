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
                    <div class="card card-primary card-outline mt-3">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-briefcase mr-2"></i>
                                Bagian Bimbingan Kerja Praktek (KP) - Prodi <?php echo e($prodi->namaprodi); ?>

                            </h3>
                        </div>
                        <div class="card-body">

                            <button type="button" class="btn btn-primary col-md-4 col-sm-12 mb-2" data-toggle="modal"
                                data-target="#modal-create-kp">
                                <i class="fas fa-plus"></i> Buat Bagian Bimbingan KP
                            </button>

                            <button type="button" class="btn btn-info col-md-4 col-sm-12 mb-2" data-toggle="modal"
                                data-target="#modal-import-kp">
                                <i class="fas fa-upload"></i> Import Bagian Bimbingan KP
                            </button>

                            <ul class="list-group mt-3">
                                <?php $noKP = 1; ?>

                                <?php $__currentLoopData = $prodi->bagiansKP; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bagianKP): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="list-group-item text-secondary <?php echo e(count($bagianKP->bimbingans) != 0 ? 'border-success' : ''); ?>">
                                        <span class="badge <?php echo e(count($bagianKP->bimbingans) != 0 ? 'badge-success' : 'badge-secondary'); ?> mr-2"><?php echo e($noKP++); ?></span>
                                        <span style="position: relative;top:2px;"><?php echo e($bagianKP->bagian); ?></span>
                                        <small class="text-muted">(<?php echo e(count($bagianKP->bimbingans)); ?> bimbingan)</small>

                                        <?php $tahuns = explode(',', $bagianKP->tahun_masuk); ?>
                                        <?php $__currentLoopData = $tahuns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tahun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span style="position: relative;top:2px;" class="badge bg-secondary"><?php echo e($tahun); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        <?php if($bagianKP->is_seminar == 1): ?>
                                            <span class="badge bg-success ml-3" style="position: relative;top:2px;">
                                                <i class="bi bi-check-circle mr-1"></i>
                                                Sebagai Syarat Seminar KP</span>
                                        <?php endif; ?>

                                        <?php if($bagianKP->is_pendadaran == 1): ?>
                                            <span class="badge bg-success ml-3" style="position: relative;top:2px;">
                                                <i class="bi bi-check-circle mr-1"></i>
                                                Sebagai Syarat Pendadaran</span>
                                        <?php endif; ?>

                                        <div class="float-right">
                                            <div class="d-flex">
                                                <button type="button" class="btn btn-primary btn-sm mr-2"
                                                    data-toggle="modal" data-target="#modal-edit-kp-<?php echo e($bagianKP->id); ?>">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <?php if(count($bagianKP->bimbingans) == 0): ?>
                                                    <form id="delete-bagian-kp-<?php echo e($bagianKP->id); ?>" action="<?php echo e(route('kp.bagian.delete')); ?>" method="post">
                                                        <?php echo csrf_field(); ?>
                                                        <input type="hidden" name="id" value="<?php echo e($bagianKP->id); ?>">
                                                        <button class="btn btn-danger btn-sm float-right" type="button" onclick="hapusBagianKP(<?php echo e($bagianKP->id); ?>)">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <?php if(count($prodi->bagiansKP) == 0): ?>
                                    <li class="list-group-item text-muted text-center">
                                        <i class="fas fa-info-circle mr-1"></i> Belum ada bagian bimbingan KP
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            
            
            
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline mt-3">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-graduation-cap mr-2"></i>
                                Bagian Bimbingan Tugas Akhir (TA) - Prodi <?php echo e($prodi->namaprodi); ?>

                            </h3>
                        </div>
                        <div class="card-body">

                            <button type="button" class="btn btn-primary col-md-4 col-sm-12 mb-2" data-toggle="modal"
                                data-target="#modal-create-ta">
                                <i class="fas fa-plus"></i> Buat Bagian Bimbingan TA
                            </button>

                            <button type="button" class="btn btn-info col-md-4 col-sm-12 mb-2" data-toggle="modal"
                                data-target="#modal-import-ta">
                                <i class="fas fa-upload"></i> Import Bagian Bimbingan TA
                            </button>

                            <ul class="list-group mt-3">
                                <?php $noTA = 1; ?>

                                <?php $__currentLoopData = $prodi->bagians; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bagian): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="list-group-item text-secondary <?php echo e(count($bagian->bimbingans) != 0 ? 'border-success' : ''); ?>">
                                        <span class="badge <?php echo e(count($bagian->bimbingans) != 0 ? 'badge-success' : 'badge-secondary'); ?> mr-2"><?php echo e($noTA++); ?></span>
                                        <span style="position: relative;top:2px;"><?php echo e($bagian->bagian); ?></span>
                                        <small class="text-muted">(<?php echo e(count($bagian->bimbingans)); ?> bimbingan)</small>

                                        <?php $tahuns = explode(',', $bagian->tahun_masuk); ?>
                                        <?php $__currentLoopData = $tahuns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tahun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span style="position: relative;top:2px;" class="badge bg-secondary"><?php echo e($tahun); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        <?php if($bagian->is_seminar == 1): ?>
                                            <span class="badge bg-success ml-3" style="position: relative;top:2px;">
                                                <i class="bi bi-check-circle mr-1"></i>
                                                Sebagai Syarat Seminar</span>
                                        <?php endif; ?>

                                        <?php if($bagian->is_pendadaran == 1): ?>
                                            <span class="badge bg-success ml-3" style="position: relative;top:2px;">
                                                <i class="bi bi-check-circle mr-1"></i>
                                                Sebagai Syarat Pendadaran</span>
                                        <?php endif; ?>

                                        <div class="float-right">
                                            <div class="d-flex">
                                                <button type="button" class="btn btn-primary btn-sm mr-2"
                                                    data-toggle="modal" data-target="#modal-edit-ta-<?php echo e($bagian->id); ?>">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <?php if(count($bagian->bimbingans) == 0): ?>
                                                    <form id="delete-bagian-ta-<?php echo e($bagian->id); ?>" action="<?php echo e(route('bagian.delete')); ?>" method="post">
                                                        <?php echo csrf_field(); ?>
                                                        <input type="hidden" name="id" value="<?php echo e($bagian->id); ?>">
                                                        <button class="btn btn-danger btn-sm float-right" type="button" onclick="hapusBagianTA(<?php echo e($bagian->id); ?>)">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <?php if(count($prodi->bagians) == 0): ?>
                                    <li class="list-group-item text-muted text-center">
                                        <i class="fas fa-info-circle mr-1"></i> Belum ada bagian bimbingan TA
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>


    
    
    

    <!-- Modal Create TA -->
    <div class="modal fade" id="modal-create-ta">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?php echo e(route('bagian.store')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="prodi_id" value="<?php echo e($prodi->id); ?>">

                    <div class="modal-header bg-primary">
                        <h4 class="modal-title">Buat Bagian Bimbingan TA</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="" class="form-label">Nama Bagian Bimbingan</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['bagian'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="bagian"
                                placeholder="Nama bagian bimbingan..." required>
                            <?php $__errorArgs = ['bagian'];
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
                            <label for="" class="form-label">Tahun Masuk <br><small>Tekan enter jika ingin input tahun masuk lebih dari 1</small></label>
                            <input type="text" class="form-control <?php $__errorArgs = ['tahun_masuk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                name="tahun_masuk" data-role="tagsinput" placeholder="Tahun masuk bagian bimbingan..." required>
                            <?php $__errorArgs = ['tahun_masuk'];
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
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="is_seminar">
                            <label class="form-check-label" for="exampleCheck1">Sebagai Syarat Seminar KP</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="is_pendadaran">
                            <label class="form-check-label" for="exampleCheck1">Sebagai Syarat Pendadaran</label>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Import TA -->
    <div class="modal fade" id="modal-import-ta">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?php echo e(route('bagian.import')); ?>" method="post" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="prodi" value="<?php echo e($prodi->id); ?>">

                    <div class="modal-header bg-info">
                        <h4 class="modal-title">Import Bagian Bimbingan TA</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <a href="https://drive.google.com/drive/folders/1AD3y7NZGUvjkoyQAdyegVzVXWNB_XJqQ?usp=sharing"
                            class="btn btn-warning btn-sm shadow" target="_blank"><i class="fas fa-download"></i>
                            Download Template File Import</a> <br><br>
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
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text">Dokumen</span>
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
        </div>
    </div>

    <!-- Modal Edit TA -->
    <?php $__currentLoopData = $prodi->bagians; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bagian): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="modal fade" id="modal-edit-ta-<?php echo e($bagian->id); ?>">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="<?php echo e(route('bagian.update')); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id" value="<?php echo e($bagian->id); ?>">

                        <div class="modal-header bg-primary">
                            <h4 class="modal-title">Edit Bagian Bimbingan TA</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="" class="form-label">Nama Bagian Bimbingan</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['bagian'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    name="bagian" value="<?php echo e($bagian->bagian); ?>" required>
                                <?php $__errorArgs = ['bagian'];
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
                                <label for="" class="form-label">Tahun Masuk <br><small>Tekan enter jika ingin input tahun masuk lebih dari 1</small></label>
                                <input type="text" class="form-control" data-role="tagsinput" name="tahun_masuk"
                                    value="<?php echo e($bagian->tahun_masuk); ?>" required>
                                <?php $__errorArgs = ['tahun_masuk'];
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

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="is_seminar"
                                    <?php if($bagian->is_seminar == 1): ?> checked <?php endif; ?>>
                                <label class="form-check-label" for="exampleCheck1">Sebagai Syarat Seminar</label>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="is_pendadaran"
                                    <?php if($bagian->is_pendadaran == 1): ?> checked <?php endif; ?>>
                                <label class="form-check-label" for="exampleCheck1">Sebagai Syarat Pendadaran</label>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


    
    
    

    <!-- Modal Create KP -->
    <div class="modal fade" id="modal-create-kp">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?php echo e(route('kp.bagian.store')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="prodi_id" value="<?php echo e($prodi->id); ?>">

                    <div class="modal-header bg-primary">
                        <h4 class="modal-title">Buat Bagian Bimbingan KP</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="" class="form-label">Nama Bagian Bimbingan</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['bagian'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="bagian"
                                placeholder="Nama bagian bimbingan..." required>
                            <?php $__errorArgs = ['bagian'];
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
                            <label for="" class="form-label">Tahun Masuk <br><small>Tekan enter jika ingin input tahun masuk lebih dari 1</small></label>
                            <input type="text" class="form-control <?php $__errorArgs = ['tahun_masuk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                name="tahun_masuk" data-role="tagsinput" placeholder="Tahun masuk bagian bimbingan..." required>
                            <?php $__errorArgs = ['tahun_masuk'];
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
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Import KP -->
    <div class="modal fade" id="modal-import-kp">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?php echo e(route('kp.bagian.import')); ?>" method="post" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="prodi" value="<?php echo e($prodi->id); ?>">

                    <div class="modal-header bg-info">
                        <h4 class="modal-title">Import Bagian Bimbingan KP</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <a href="https://drive.google.com/drive/folders/1AD3y7NZGUvjkoyQAdyegVzVXWNB_XJqQ?usp=sharing"
                            class="btn btn-warning btn-sm shadow" target="_blank"><i class="fas fa-download"></i>
                            Download Template File Import</a> <br><br>
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
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
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
        </div>
    </div>

    <!-- Modal Edit KP -->
    <?php $__currentLoopData = $prodi->bagiansKP; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bagianKP): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="modal fade" id="modal-edit-kp-<?php echo e($bagianKP->id); ?>">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="<?php echo e(route('kp.bagian.update')); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id" value="<?php echo e($bagianKP->id); ?>">

                        <div class="modal-header bg-primary">
                            <h4 class="modal-title">Edit Bagian Bimbingan KP</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="" class="form-label">Nama Bagian Bimbingan</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['bagian'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    name="bagian" value="<?php echo e($bagianKP->bagian); ?>" required>
                                <?php $__errorArgs = ['bagian'];
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
                                <label for="" class="form-label">Tahun Masuk <br><small>Tekan enter jika ingin input tahun masuk lebih dari 1</small></label>
                                <input type="text" class="form-control" data-role="tagsinput" name="tahun_masuk"
                                    value="<?php echo e($bagianKP->tahun_masuk); ?>" required>
                                <?php $__errorArgs = ['tahun_masuk'];
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

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="is_seminar"
                                    <?php if($bagianKP->is_seminar == 1): ?> checked <?php endif; ?>>
                                <label class="form-check-label" for="exampleCheck1">Sebagai Syarat Seminar KP</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="is_pendadaran"
                                    <?php if($bagianKP->is_pendadaran == 1): ?> checked <?php endif; ?>>
                                <label class="form-check-label" for="exampleCheck1">Sebagai Syarat Pendadaran</label>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
    <script>
        $(function() {
            $('input')
                .on('change', function(event) {
                    var $element = $(event.target);
                    var $container = $element.closest('.example');

                    if (!$element.data('tagsinput')) return;

                    var val = $element.val();
                    if (val === null) val = 'null';
                    var items = $element.tagsinput('items');

                    $('code', $('pre.val', $container)).html(
                        $.isArray(val) ?
                        JSON.stringify(val) :
                        '"' + val.replace('"', '\\"') + '"'
                    );
                    $('code', $('pre.items', $container)).html(
                        JSON.stringify($element.tagsinput('items'))
                    );
                })
                .trigger('change');
        });

        function hapusBagianKP(id) {
            console.log('hapusBagianKP called with id:', id);
            var form = document.getElementById('delete-bagian-kp-' + id);
            console.log('Form found:', form);

            if (!form) {
                alert('Error: Form dengan id delete-bagian-kp-' + id + ' tidak ditemukan!');
                return;
            }

            if (typeof Swal === 'undefined') {
                if (confirm('Yakin ingin menghapus bagian KP ini?')) {
                    form.submit();
                }
                return;
            }

            Swal.fire({
                title: 'Yakin ingin menghapus bagian KP ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                console.log('Swal result:', result);
                if (result.isConfirmed) {
                    console.log('Submitting form...');
                    form.submit();
                }
            });
        }

        function hapusBagianTA(id) {
            console.log('hapusBagianTA called with id:', id);
            var form = document.getElementById('delete-bagian-ta-' + id);
            console.log('Form found:', form);

            if (!form) {
                alert('Error: Form dengan id delete-bagian-ta-' + id + ' tidak ditemukan!');
                return;
            }

            if (typeof Swal === 'undefined') {
                if (confirm('Yakin ingin menghapus bagian TA ini?')) {
                    form.submit();
                }
                return;
            }

            Swal.fire({
                title: 'Yakin ingin menghapus bagian TA ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                console.log('Swal result:', result);
                if (result.isConfirmed) {
                    console.log('Submitting form...');
                    form.submit();
                }
            });
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('kp.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/admin/prodi/detail.blade.php ENDPATH**/ ?>