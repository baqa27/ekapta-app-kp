

<?php $__env->startSection('content'); ?>
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
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-check"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Semua Bimbingan</span>
                            <span class="info-box-number">
                                <?php echo e(count($bimbingans)); ?> Bimbingan
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-check"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Bimbingan Review</span>
                            <span class="info-box-number">
                                <?php echo e(count($bimbingans_review)); ?> Bimbingan
                            </span>
                        </div>
                    </div>
                </div>


                <div class="clearfix hidden-md-up"></div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-check"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Bimbingan Revisi</span>
                            <span class="info-box-number"> <?php echo e(count($bimbingans_revisi)); ?> Bimbingan</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Bimbingan Diterima</span>
                            <span class="info-box-number"> <?php echo e(count($bimbingans_diterima)); ?> Bimbingan</span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-info">
                            Bimbingan KP Berdasarkan Status
                        </div>
                        <div class="card-body">

                            <div class="progress-group">
                                Bimbingan Diterima
                                <span
                                    class="float-right"><b><?php echo e(count($bimbingans_diterima)); ?></b>/<?php echo e(count($bimbingans)); ?></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-success"
                                        <?php if(count($bimbingans_diterima) != 0): ?> style="width: <?php echo e((count($bimbingans_diterima) / count($bimbingans)) * 100); ?>%"
                                    <?php else: ?>
                                     style="width: 0%" <?php endif; ?>>
                                    </div>
                                </div>
                            </div>

                            <div class="progress-group">
                                Bimbingan Review
                                <span
                                    class="float-right"><b><?php echo e(count($bimbingans_review)); ?></b>/<?php echo e(count($bimbingans)); ?></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-secondary"
                                        <?php if(count($bimbingans_review) != 0): ?> style="width: <?php echo e((count($bimbingans_review) / count($bimbingans)) * 100); ?>%"
                                    <?php else: ?>
                                     style="width: 0%" <?php endif; ?>>
                                    </div>
                                </div>
                            </div>

                            <div class="progress-group">
                                Bimbingan Revisi
                                <span
                                    class="float-right"><b><?php echo e(count($bimbingans_revisi)); ?></b>/<?php echo e(count($bimbingans)); ?></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-warning"
                                        <?php if(count($bimbingans_revisi) != 0): ?> style="width: <?php echo e((count($bimbingans_revisi) / count($bimbingans)) * 100); ?>%"
                                    <?php else: ?>
                                     style="width: 0%" <?php endif; ?>>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- HEADER INTEGRASI -->
            <h4 class="mb-3 text-muted border-bottom pb-2">Integrasi Tugas Akhir (TA)</h4>
            
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-check"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Semua Bimbingan TA</span>
                            <span class="info-box-number">
                                <?php echo e(count($ta_bimbingans)); ?> Bimbingan
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-check"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Bimbingan TA Review</span>
                            <span class="info-box-number">
                                <?php echo e(count($ta_bimbingans_review)); ?> Bimbingan
                            </span>
                        </div>
                    </div>
                </div>


                <div class="clearfix hidden-md-up"></div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-check"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Bimbingan TA Revisi</span>
                            <span class="info-box-number"> <?php echo e(count($ta_bimbingans_revisi)); ?> Bimbingan</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Bimbingan TA Diterima</span>
                            <span class="info-box-number"> <?php echo e(count($ta_bimbingans_diterima)); ?> Bimbingan</span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-info">
                            Bimbingan TA Berdasarkan Status
                        </div>
                        <div class="card-body">

                            <div class="progress-group">
                                Bimbingan Diterima
                                <span
                                    class="float-right"><b><?php echo e(count($ta_bimbingans_diterima)); ?></b>/<?php echo e(count($ta_bimbingans)); ?></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-success"
                                        <?php if(count($ta_bimbingans_diterima) != 0): ?> style="width: <?php echo e((count($ta_bimbingans_diterima) / count($ta_bimbingans)) * 100); ?>%"
                                    <?php else: ?>
                                     style="width: 0%" <?php endif; ?>>
                                    </div>
                                </div>
                            </div>

                            <div class="progress-group">
                                Bimbingan Review
                                <span
                                    class="float-right"><b><?php echo e(count($ta_bimbingans_review)); ?></b>/<?php echo e(count($ta_bimbingans)); ?></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-secondary"
                                        <?php if(count($ta_bimbingans_review) != 0): ?> style="width: <?php echo e((count($ta_bimbingans_review) / count($ta_bimbingans)) * 100); ?>%"
                                    <?php else: ?>
                                     style="width: 0%" <?php endif; ?>>
                                    </div>
                                </div>
                            </div>

                            <div class="progress-group">
                                Bimbingan Revisi
                                <span
                                    class="float-right"><b><?php echo e(count($ta_bimbingans_revisi)); ?></b>/<?php echo e(count($ta_bimbingans)); ?></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-warning"
                                        <?php if(count($ta_bimbingans_revisi) != 0): ?> style="width: <?php echo e((count($ta_bimbingans_revisi) / count($ta_bimbingans)) * 100); ?>%"
                                    <?php else: ?>
                                     style="width: 0%" <?php endif; ?>>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('kp.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/dosen/dashboard/home.blade.php ENDPATH**/ ?>