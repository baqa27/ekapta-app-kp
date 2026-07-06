

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
                    <div class="info-box">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Jumlah Mahasiswa</span>
                            <span class="info-box-number">
                                <?php echo e(count($mahasiswas)); ?> Mahasiswa
                            </span>
                            <small class="bg-light d-flex justify-content-center">
                                <a href="<?php echo e(route('mahasiswas')); ?>" class="small-box-footer text-primary">More info
                                    <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </small>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Jumlah Dosen</span>
                            <span class="info-box-number">
                                <?php echo e(count($dosens)); ?> Dosen
                            </span>
                            <small class="bg-light d-flex justify-content-center">
                                <a href="<?php echo e(route('dosens')); ?>" class="small-box-footer text-primary">More info
                                    <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </small>
                        </div>
                    </div>
                </div>


                <div class="clearfix hidden-md-up"></div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-building"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Jumlah Prodi</span>
                            <span class="info-box-number"><?php echo e(count($prodis)); ?> Prodi</span>
                            <small class="bg-light d-flex justify-content-center">
                                <a href="<?php echo e(route('prodis')); ?>" class="small-box-footer text-primary">More info
                                    <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </small>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-building"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Jumlah Fakultas</span>
                            <span class="info-box-number"><?php echo e(count($fakultas)); ?> Fakultas</span>
                            <small class="bg-light d-flex justify-content-center">
                                <a href="<?php echo e(route('fakultas')); ?>" class="small-box-footer text-primary">More info
                                    <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </small>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
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
                            <span class="info-box-text">Pendaftaran KP</span>
                            <span class="info-box-number">
                                <?php echo e(count($pendaftarans)); ?> Mahasiswa
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
                            Pendaftaran KP Berdasarkan Status
                        </div>
                        <div class="card-body">

                            <div class="progress-group">
                                Pendaftaran Diterima
                                <span
                                    class="float-right"><b><?php echo e(count($pendaftarans_diterima)); ?></b>/<?php echo e(count($pendaftarans)); ?></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-success"
                                        <?php if(count($pendaftarans_diterima) != 0): ?> style="width: <?php echo e((count($pendaftarans_diterima) / count($pendaftarans)) * 100); ?>%"
                                    <?php else: ?>
                                     style="width: 0%" <?php endif; ?>>
                                    </div>
                                </div>
                            </div>

                            <div class="progress-group">
                                Pendaftaran Review
                                <span
                                    class="float-right"><b><?php echo e(count($pendaftarans_review)); ?></b>/<?php echo e(count($pendaftarans)); ?></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-secondary"
                                        <?php if(count($pendaftarans_review) != 0): ?> style="width: <?php echo e((count($pendaftarans_review) / count($pendaftarans)) * 100); ?>%"
                                    <?php else: ?>
                                     style="width: 0%" <?php endif; ?>>
                                    </div>
                                </div>
                            </div>

                            <div class="progress-group">
                                Pendaftaran Revisi
                                <span
                                    class="float-right"><b><?php echo e(count($pendaftarans_revisi)); ?></b>/<?php echo e(count($pendaftarans)); ?></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-warning"
                                        <?php if(count($pendaftarans_revisi) != 0): ?> style="width: <?php echo e((count($pendaftarans_revisi) / count($pendaftarans)) * 100); ?>%"
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
            <!-- HEADER INTEGRASI TA -->
            <h4 class="mb-3 text-muted border-bottom pb-2">Integrasi Tugas Akhir (TA)</h4>
            
            <div class="row">
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
                            <span class="info-box-text">Pendaftaran TA</span>
                            <span class="info-box-number">
                                <?php echo e(count($ta_pendaftarans)); ?> Mahasiswa
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
                                    <div class="progress-bar bg-danger"
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
                            Pendaftaran TA Berdasarkan Status
                        </div>
                        <div class="card-body">

                            <div class="progress-group">
                                Pendaftaran Diterima
                                <span
                                    class="float-right"><b><?php echo e(count($ta_pendaftarans_diterima)); ?></b>/<?php echo e(count($ta_pendaftarans)); ?></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-success"
                                        <?php if(count($ta_pendaftarans_diterima) != 0): ?> style="width: <?php echo e((count($ta_pendaftarans_diterima) / count($ta_pendaftarans)) * 100); ?>%"
                                    <?php else: ?>
                                     style="width: 0%" <?php endif; ?>>
                                    </div>
                                </div>
                            </div>

                            <div class="progress-group">
                                Pendaftaran Review
                                <span
                                    class="float-right"><b><?php echo e(count($ta_pendaftarans_review)); ?></b>/<?php echo e(count($ta_pendaftarans)); ?></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-secondary"
                                        <?php if(count($ta_pendaftarans_review) != 0): ?> style="width: <?php echo e((count($ta_pendaftarans_review) / count($ta_pendaftarans)) * 100); ?>%"
                                    <?php else: ?>
                                     style="width: 0%" <?php endif; ?>>
                                    </div>
                                </div>
                            </div>

                            <div class="progress-group">
                                Pendaftaran Revisi
                                <span
                                    class="float-right"><b><?php echo e(count($ta_pendaftarans_revisi)); ?></b>/<?php echo e(count($ta_pendaftarans)); ?></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-warning"
                                        <?php if(count($ta_pendaftarans_revisi) != 0): ?> style="width: <?php echo e((count($ta_pendaftarans_revisi) / count($ta_pendaftarans)) * 100); ?>%"
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
                            Seminar Proposal TA Berdasarkan Status
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
                            Ujian Pendadaran TA Berdasarkan Status
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





<?php echo $__env->make('kp.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/admin/dashboard/home.blade.php ENDPATH**/ ?>