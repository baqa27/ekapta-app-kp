

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
                <div class="col-12">
                    <div class="mb-4">
                        <a href="<?php echo e(route('kp.bimbingan.rekap.dosen')); ?>" class="btn btn-secondary btn-sm shadow" target="_blank">
                            <i class="bi bi-people"></i> Rekap Bimbingan Dosen
                        </a>
                        <a href="<?php echo e(route('kp.bimbingan.prodi')); ?>" class="btn btn-success btn-sm shadow">
                            <i class="bi bi-download"></i> Download Laporan Progres Bimbingan KP
                        </a>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-check"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Pengajuan KP</span>
                            <span class="info-box-number">
                                <?php echo e(count($pengajuans)); ?> Mahasiswa
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-check"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Bimbingan KP</span>
                            <span class="info-box-number">
                                <?php echo e(count($bimbingans)); ?> Bimbingan
                            </span>
                        </div>
                    </div>
                </div>


                <div class="clearfix hidden-md-up"></div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-check"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Seminar KP</span>
                            <span class="info-box-number"><?php echo e(count($seminars)); ?> Mahasiswa</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Jilid KP</span>
                            <span class="info-box-number"><?php echo e(count($pengumpulan_akhir)); ?> Mahasiswa</span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card" style="min-height: 16rem">
                        <div class="card-header bg-secondary">
                            Pengajuan KP Berdasarkan Status
                        </div>
                        <div class="card-body">

                            <div class="progress-group">
                                Pengajuan Diterima
                                <span
                                    class="float-right"><b><?php echo e(count($pengajuans_diterima)); ?></b>/<?php echo e(count($pengajuans)); ?></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-success"
                                        <?php if(count($pengajuans_diterima) != 0): ?> style="width: <?php echo e((count($pengajuans_diterima) / count($pengajuans)) * 100); ?>%"
                                    <?php else: ?>
                                     style="width: 0%" <?php endif; ?>>
                                    </div>
                                </div>
                            </div>

                            <div class="progress-group">
                                Pengajuan Review
                                <span
                                    class="float-right"><b><?php echo e(count($pengajuans_review)); ?></b>/<?php echo e(count($pengajuans)); ?></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-secondary"
                                        <?php if(count($pengajuans_review) != 0): ?> style="width: <?php echo e((count($pengajuans_review) / count($pengajuans)) * 100); ?>%"
                                    <?php else: ?>
                                     style="width: 0%" <?php endif; ?>>
                                    </div>
                                </div>
                            </div>

                            <div class="progress-group">
                                Pengajuan Revisi
                                <span
                                    class="float-right"><b><?php echo e(count($pengajuans_revisi)); ?></b>/<?php echo e(count($pengajuans)); ?></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-warning"
                                        <?php if(count($pengajuans_revisi) != 0): ?> style="width: <?php echo e((count($pengajuans_revisi) / count($pengajuans)) * 100); ?>%"
                                    <?php else: ?>
                                     style="width: 0%" <?php endif; ?>>
                                    </div>
                                </div>
                            </div>

                            <div class="progress-group">
                                Pengajuan Ditolak
                                <span
                                    class="float-right"><b><?php echo e(count($pengajuans_ditolak)); ?></b>/<?php echo e(count($pengajuans)); ?></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-success"
                                        <?php if(count($pengajuans_ditolak) != 0): ?> style="width: <?php echo e((count($pengajuans_ditolak) / count($pengajuans)) * 100); ?>%"
                                    <?php else: ?>
                                     style="width: 0%" <?php endif; ?>>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card" style="min-height: 16rem">
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

            <div class="row">
                <div class="col-md-6">
                    <div class="card" style="min-height: 16rem">
                        <div class="card-header bg-primary">
                            Seminar KP Berdasarkan Status
                        </div>
                        <div class="card-body">

                            <div class="progress-group">
                                Seminar KP Diterima
                                <span class="float-right"><b><?php echo e(count($seminars_diterima)); ?></b>/<?php echo e(count($seminars)); ?></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-success" style="width: <?php echo e(count($seminars_diterima) != 0 ? (count($seminars_diterima) / count($seminars)) * 100 : 0); ?>%">
                                    </div>
                                </div>
                            </div>

                            <div class="progress-group">
                                Seminar KP Review
                                <span class="float-right"><b><?php echo e(count($seminars_review)); ?></b>/<?php echo e(count($seminars)); ?></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-secondary" style="width: <?php echo e(count($seminars_review) != 0 ? (count($seminars_review) / count($seminars)) * 100 : 0); ?>%">
                                    </div>
                                </div>
                            </div>

                            <div class="progress-group">
                                Seminar KP Revisi
                                <span class="float-right"><b><?php echo e(count($seminars_revisi)); ?></b>/<?php echo e(count($seminars)); ?></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-warning" style="width: <?php echo e(count($seminars_revisi) != 0 ? (count($seminars_revisi) / count($seminars)) * 100 : 0); ?>%">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card" style="min-height: 16rem">
                        <div class="card-header bg-success">
                            Jilid KP Berdasarkan Status
                        </div>
                        <div class="card-body">

                            <div class="progress-group">
                                Jilid KP Diterima
                                <span class="float-right"><b><?php echo e(count($pengumpulan_akhir_diterima)); ?></b>/<?php echo e(count($pengumpulan_akhir)); ?></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-success" style="width: <?php echo e(count($pengumpulan_akhir_diterima) != 0 ? (count($pengumpulan_akhir_diterima) / count($pengumpulan_akhir)) * 100 : 0); ?>%">
                                    </div>
                                </div>
                            </div>

                            <div class="progress-group">
                                Jilid KP Review
                                <span class="float-right"><b><?php echo e(count($pengumpulan_akhir_review)); ?></b>/<?php echo e(count($pengumpulan_akhir)); ?></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-secondary" style="width: <?php echo e(count($pengumpulan_akhir_review) != 0 ? (count($pengumpulan_akhir_review) / count($pengumpulan_akhir)) * 100 : 0); ?>%">
                                    </div>
                                </div>
                            </div>

                            <div class="progress-group">
                                Jilid KP Revisi
                                <span class="float-right"><b><?php echo e(count($pengumpulan_akhir_revisi)); ?></b>/<?php echo e(count($pengumpulan_akhir)); ?></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-warning" style="width: <?php echo e(count($pengumpulan_akhir_revisi) != 0 ? (count($pengumpulan_akhir_revisi) / count($pengumpulan_akhir)) * 100 : 0); ?>%">
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
                <div class="col-12">
                    <div class="mb-4">
                        <!-- Buttons copied from TA, ensure routes exist or comment out if unsure -->
                        <a href="<?php echo e(route('bimbingan.rekap.dosen')); ?>" class="btn btn-secondary btn-sm shadow" target="_blank">
                            <i class="bi bi-people"></i> Rekap Bimbingan Dosen (TA)
                        </a>
                        <!--
                        <a href="<?php echo e(route('bimbingan.prodi')); ?>" class="btn btn-success btn-sm shadow">
                            <i class="bi bi-download"></i> Download Laporan Progres Bimbingan TA
                        </a>
                        -->
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-check"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Pengajuan TA</span>
                            <span class="info-box-number">
                                <?php echo e(count($ta_pengajuans)); ?> Mahasiswa
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-check"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Bimbingan TA</span>
                            <span class="info-box-number">
                                <?php echo e(count($ta_bimbingans)); ?> Bimbingan
                            </span>
                        </div>
                    </div>
                </div>


                <div class="clearfix hidden-md-up"></div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-check"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Seminar Proposal</span>
                            <span class="info-box-number"><?php echo e(count($ta_seminars)); ?> Mahasiswa</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Ujian Pendadaran</span>
                            <span class="info-box-number"><?php echo e(count($ta_ujians)); ?> Mahasiswa</span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card" style="min-height: 16rem">
                        <div class="card-header bg-secondary">
                            Pengajuan TA Berdasarkan Status
                        </div>
                        <div class="card-body">

                            <div class="progress-group">
                                Pengajuan Diterima
                                <span
                                    class="float-right"><b><?php echo e(count($ta_pengajuans_diterima)); ?></b>/<?php echo e(count($ta_pengajuans)); ?></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-success"
                                        <?php if(count($ta_pengajuans_diterima) != 0): ?> style="width: <?php echo e((count($ta_pengajuans_diterima) / count($ta_pengajuans)) * 100); ?>%"
                                    <?php else: ?>
                                     style="width: 0%" <?php endif; ?>>
                                    </div>
                                </div>
                            </div>

                            <div class="progress-group">
                                Pengajuan Review
                                <span
                                    class="float-right"><b><?php echo e(count($ta_pengajuans_review)); ?></b>/<?php echo e(count($ta_pengajuans)); ?></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-secondary"
                                        <?php if(count($ta_pengajuans_review) != 0): ?> style="width: <?php echo e((count($ta_pengajuans_review) / count($ta_pengajuans)) * 100); ?>%"
                                    <?php else: ?>
                                     style="width: 0%" <?php endif; ?>>
                                    </div>
                                </div>
                            </div>

                            <div class="progress-group">
                                Pengajuan Revisi
                                <span
                                    class="float-right"><b><?php echo e(count($ta_pengajuans_revisi)); ?></b>/<?php echo e(count($ta_pengajuans)); ?></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-warning"
                                        <?php if(count($ta_pengajuans_revisi) != 0): ?> style="width: <?php echo e((count($ta_pengajuans_revisi) / count($ta_pengajuans)) * 100); ?>%"
                                    <?php else: ?>
                                     style="width: 0%" <?php endif; ?>>
                                    </div>
                                </div>
                            </div>

                            <div class="progress-group">
                                Pengajuan Ditolak
                                <span
                                    class="float-right"><b><?php echo e(count($ta_pengajuans_ditolak)); ?></b>/<?php echo e(count($ta_pengajuans)); ?></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-success"
                                        <?php if(count($ta_pengajuans_ditolak) != 0): ?> style="width: <?php echo e((count($ta_pengajuans_ditolak) / count($ta_pengajuans)) * 100); ?>%"
                                    <?php else: ?>
                                     style="width: 0%" <?php endif; ?>>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card" style="min-height: 16rem">
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

            <div class="row">
                <div class="col-md-6">
                    <div class="card" style="min-height: 16rem">
                        <div class="card-header bg-primary">
                            Seminar Proposal Berdasarkan Status
                        </div>
                        <div class="card-body">

                            <div class="progress-group">
                                Seminar Proposal Diterima
                                <span class="float-right"><b><?php echo e(count($ta_seminars_diterima)); ?></b>/<?php echo e(count($ta_seminars)); ?></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-success" style="width: <?php echo e(count($ta_seminars_diterima) != 0 ? (count($ta_seminars_diterima) / count($ta_seminars)) * 100 : 0); ?>%">
                                    </div>
                                </div>
                            </div>

                            <div class="progress-group">
                                Seminar Proposal Review
                                <span class="float-right"><b><?php echo e(count($ta_seminars_review)); ?></b>/<?php echo e(count($ta_seminars)); ?></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-secondary" style="width: <?php echo e(count($ta_seminars_review) != 0 ? (count($ta_seminars_review) / count($ta_seminars)) * 100 : 0); ?>%">
                                    </div>
                                </div>
                            </div>

                            <div class="progress-group">
                                Seminar Proposal Revisi
                                <span class="float-right"><b><?php echo e(count($ta_seminars_revisi)); ?></b>/<?php echo e(count($ta_seminars)); ?></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-warning" style="width: <?php echo e(count($ta_seminars_revisi) != 0 ? (count($ta_seminars_revisi) / count($ta_seminars)) * 100 : 0); ?>%">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card" style="min-height: 16rem">
                        <div class="card-header bg-success">
                            Ujian Pendadaran Berdasarkan Status
                        </div>
                        <div class="card-body">

                            <div class="progress-group">
                                Ujian Pendadaran Diterima
                                <span class="float-right"><b><?php echo e(count($ta_ujians_diterima)); ?></b>/<?php echo e(count($ta_ujians)); ?></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-success" style="width: <?php echo e(count($ta_ujians_diterima) != 0 ? (count($ta_ujians_diterima) / count($ta_ujians)) * 100 : 0); ?>%">
                                    </div>
                                </div>
                            </div>

                            <div class="progress-group">
                                Ujian Pendadaran Review
                                <span class="float-right"><b><?php echo e(count($ta_ujians_review)); ?></b>/<?php echo e(count($ta_ujians)); ?></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-secondary" style="width: <?php echo e(count($ta_ujians_review) != 0 ? (count($ta_ujians_review) / count($ta_ujians)) * 100 : 0); ?>%">
                                    </div>
                                </div>
                            </div>

                            <div class="progress-group">
                                Ujian Pendadaran Revisi
                                <span class="float-right"><b><?php echo e(count($ta_ujians_revisi)); ?></b>/<?php echo e(count($ta_ujians)); ?></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-warning" style="width: <?php echo e(count($ta_ujians_revisi) != 0 ? (count($ta_ujians_revisi) / count($ta_ujians)) * 100 : 0); ?>%">
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





<?php echo $__env->make('kp.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/prodi/dashboard/home.blade.php ENDPATH**/ ?>