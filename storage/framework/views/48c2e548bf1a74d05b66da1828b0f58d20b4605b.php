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
                        <li class="breadcrumb-item"><a href="#">Review Seminar TA</a></li>
                        <li class="breadcrumb-item active"><?php echo e($title); ?></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">

                <?php $__currentLoopData = $seminar->reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($review->dosen_status == 'penguji'): ?>
                        <div class="col-md-4">
                            <div class="card card-primary card-outline">
                                <div class="ribbon-wrapper ribbon-lg">
                                    <div
                                        class="ribbon
                                <?php if($review->status == 'diterima'): ?> bg-success
                                <?php elseif($review->status == 'revisi'): ?>
                                bg-warning
                                <?php elseif($review->status == 'review'): ?>
                                bg-secondary
                                <?php else: ?>
                                bg-danger <?php endif; ?>
                                ">
                                        <?php if($review->status == 'diterima'): ?>
                                            Diterima
                                        <?php elseif($review->status == 'revisi'): ?>
                                            Revisi
                                        <?php elseif($review->status == 'review'): ?>
                                            Review
                                        <?php else: ?>
                                            Belum Submit
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="card-body">
                                    Dosen Penguji : <br>
                                    <b><?php echo e($review->dosen->nama); ?>, <?php echo e($review->dosen->gelar); ?></b> <br><br>

                                    
                                    Catatan Dosen <span class="badge bg-danger"> <?php echo e(count($review->revisis)); ?>

                                    </span><br><br>
                                    <div class="p-2 rounded reviews-box">
                                        <?php $__currentLoopData = $review->revisis()->orderBy('created_at', 'desc')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $revisi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="direct-chat-msg">
                                                <div class="direct-chat-infos clearfix">
                                                    <span class="direct-chat-name float-left"><?php echo e($review->dosen->nama); ?>,
                                                        <?php echo e($review->dosen->gelar); ?></span>
                                                    <span class="direct-chat-timestamp float-right">
                                                        <?php echo e($revisi->created_at->format('d M Y H:m a')); ?>

                                                    </span>
                                                </div>
                                                <img class="direct-chat-img"
                                                    src="<?php echo e(asset('ekapta/adminLTE/dist/img/default-profile.png')); ?>"
                                                    alt="message user image">
                                                <div class="direct-chat-text p-2">
                                                    <?php echo nl2br($revisi->catatan); ?>

                                                    <?php if($revisi->lampiran): ?>
                                                        <div class="p-1 mt-3 bg-light rounded">
                                                            <small>
                                                                <span class="text-secondary ml-2"><b>Lampiran : </b></span>
                                                                <a href="<?php echo e(asset($revisi->lampiran)); ?>" target="_blank">
                                                                    <i class="fas fa-paperclip ml-1"></i>
                                                                    <?php echo e(Str::substr($revisi->lampiran, 40)); ?>

                                                                </a>
                                                            </small>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>

                                </div>

                                <?php if($review->status == 'review'): ?>
                                    <div class="card-footer">
                                       <?php if($review->tanggal_acc_manual && $review->lampiran_lembar_revisi && $review->status =='review'): ?>
                                       <a href="#"
                                        class="btn btn-secondary col-md-12">
                                        <i class="bi bi-hourglass-bottom"></i> Submit Acc Manual Dalam Review Prodi
                                    </a>
                                       <?php else: ?>
                                       <a href="<?php echo e(route('review.seminar.submit.acc.manual', $review->id)); ?>"
                                        class="btn btn-primary col-md-12">
                                        <i class="bi bi-upload"></i> Submit Acc Manual
                                    </a>
                                       <?php endif; ?>
                                    </div>
                                <?php elseif($review->status == null || $review->status == 'revisi'): ?>
                                    <div class="card-footer">
                                        <a href="<?php echo e(route('review.seminar.edit', $review->id)); ?>"
                                            class="btn btn-primary col-md-12">
                                            <i class="bi bi-upload"></i> Submit Laporan Proposal
                                        </a>
                                    </div>
                                <?php elseif($review->status == 'review' || $review->status == 'diterima'): ?>
                                    <div class="card-footer">
                                        Keterangan :
                                        <div class="bg-secondary rounded p-2"><?php echo $review->keterangan; ?>

                                            <div class="bg-light p-1 rounded mt-1">
                                                <small>
                                                    <b>Lampiran sebelumnya: </b>
                                                    <a href="<?php echo e(asset($review->lampiran ? $review->lampiran : $review->seminar->lampiran_3)); ?>"
                                                        class="ml-3 text-primary" target="_blank"><i
                                                            class="fas fa-paperclip mr-2"></i>
                                                        <?php echo e(Str::substr($review->lampiran ? $review->lampiran : $review->seminar->lampiran_3, 40)); ?></a>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                            </div>

                        </div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
    <!-- /.content -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboardMahasiswa', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/pages/mahasiswa/seminar/reviews.blade.php ENDPATH**/ ?>