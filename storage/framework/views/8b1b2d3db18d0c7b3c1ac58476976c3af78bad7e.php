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
                            <a href="<?php echo e(route($route)); ?>" class="btn btn-secondary btn-sm shadow">
                                <i class="bi bi-chevron-left"></i> Kembali
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="border p-3 col-md-6">
                                    Mahasiswa: <b> <?php echo e($mahasiswa->nim . '/' . $mahasiswa->nama); ?></b>
                                </div>
                                <div class="border p-3 col-md-6">
                                    Dosen: <b> <?php echo e($dosen->nama . ', ' . $dosen->gelar); ?></b>
                                </div>
                            </div>
                            <form action="<?php echo e(route('kp.bimbingan.admin.input.store')); ?>" method="post"
                                enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="dosen_id" value="<?php echo e($dosen->id); ?>">
                                <input type="hidden" name="mahasiswa_id" value="<?php echo e($mahasiswa->id); ?>">
                                
                                <div class="mt-3 d-flex">
                                    <b class="flex-grow-1">BAB BIMBINGAN</b>
                                    <div class="flex-shrink-0">
                                        <b>TANGGAL ACC</b>
                                    </div>
                                </div>
                                <div>
                                    <?php $__currentLoopData = $bimbingans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bimbingan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input type="hidden" name="ids[]" value="<?php echo e($bimbingan->id); ?>">
                                        <div class="border p-3 d-flex">
                                            <div class="flex-grow-1">
                                                <b
                                                    class="text-<?php echo e($bimbingan->tanggal_acc ? 'success' : 'secondary'); ?>"><?php echo e($bimbingan->bagian->bagian); ?></b>
                                                <?php if($bimbingan->lampiran): ?>
                                                    <br> <a href="<?php echo e(asset($bimbingan->lampiran)); ?>" target="_blank"><i
                                                            class="fas fa-paperclip ml-1"></i> Lampiran</a>
                                                <?php else: ?>
                                                    <br><span class="text-secondary">Belum Upload File Bimbingan</span>
                                                <?php endif; ?>

                                                <?php if($bimbingan->lampiran_acc && $bimbingan->status == 'review'): ?>
                                                    | <a href="<?php echo e(asset($bimbingan->lampiran_acc)); ?>" target="_blank"><i
                                                            class="fas fa-paperclip ml-1"></i> Lembar Acc Bimbingan</a>
                                                     | Tanggal acc : <span class="text-muted"><?php echo e($bimbingan->tanggal_manual_acc); ?></span>
                                                     <a href="<?php echo e(route('bimbingan.acc.submit.manual', $bimbingan->id)); ?>" class="btn btn-success btn-sm" onclick="return confirm('Yakin ingin acc?')"><i class="fas fa-check"></i> Acc Bimbingan</a>
                                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                        data-target="#modal-tolak"
                                                        onclick="setTolakAction('<?php echo e(route('bimbingan.reject.submit.manual', $bimbingan->id)); ?>')">
                                                        <i class="bi bi-x-circle mr-2"></i> Tolak Bimbingan
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <input type="date" name="dates[]" class="form-control"
                                                    <?php if($bimbingan->status == null): ?> disabled <?php endif; ?>>
                                                <?php if($bimbingan->tanggal_acc): ?>
                                                    <span
                                                        class="text-success"><?php echo e(\App\Helpers\AppHelper::parse_date_short($bimbingan->tanggal_acc)); ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary shadow"><i class="fas fa-save"></i>
                                        Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modal-tolak">
        <div class="modal-dialog">
            <div class="modal-content">
                
                <form id="form-tolak" method="POST">
                    <?php echo method_field('put'); ?>
                    <?php echo csrf_field(); ?>
                    <div class="modal-header">
                        <h4 class="modal-title">Tolak Bimbingan</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Catatan</label>
                            <textarea class="form-control" name="catatan" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function setTolakAction(action) {
            const form = document.getElementById('form-tolak');
            form.action = action;
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('kp.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/admin/bimbingan/bimbingan-input-create.blade.php ENDPATH**/ ?>