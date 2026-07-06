

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
                        <li class="breadcrumb-item"><a href="#">Jilid KP</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <?php if(Auth::guard('admin')->user()->type != \App\Models\Admin::TYPE_SUPER_ADMIN): ?>
                
                <div class="card bg-gradient-info mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0"><i class="fas fa-user-circle mr-2"></i>Selamat datang, <strong><?php echo e(Auth::guard('admin')->user()->nama); ?></strong></h4>
                                <p class="mb-0 mt-2"><i class="fas fa-info-circle mr-1"></i> Dashboard Fotokopi FASTIKOM</p>
                            </div>
                            <a href="<?php echo e(route('logout.admin')); ?>" class="btn btn-light">
                                <i class="fas fa-sign-out-alt mr-1"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3><?php echo e($jilids_kp->where('status', \App\Models\KP\Jilid::JILID_VALID)->count()); ?></h3>
                                <p>Menunggu Jilid KP</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3><?php echo e($jilids_kp->where('status', \App\Models\KP\Jilid::JILID_SELESAI)->count()); ?></h3>
                                <p>Selesai Jilid KP</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3><?php echo e($jilids_ta->where('status', 'terkumpul')->count()); ?></h3>
                                <p>Menunggu Jilid TA</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3><?php echo e($jilids_ta->where('status', 'selesai')->count()); ?></h3>
                                <p>Selesai Jilid TA</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>



            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo e($title); ?></h3>
                            <?php if(Auth::guard('admin')->user()->type == \App\Models\Admin::TYPE_SUPER_ADMIN): ?>
                                <ul class="nav nav-pills ml-auto float-right">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#tab_1" data-toggle="tab">Review</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#tab_3" data-toggle="tab">Revisi</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#tab_4" data-toggle="tab">Valid</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#tab_2" data-toggle="tab">Selesai</a>
                                    </li>
                                </ul>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <?php if(Auth::guard('admin')->user()->type == \App\Models\Admin::TYPE_SUPER_ADMIN): ?>
                                <div class="tab-content">
                                    
                                    <div class="tab-pane active" id="tab_1">
                                        <table id="example1" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>NIM</th>
                                                    <th>Nama Mahasiswa</th>
                                                    <th>Total Pembayaran</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no = 1; ?>
                                                <?php $__currentLoopData = $jilids_kp; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jilid): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        $is_karyawan = \App\Helpers\AppHelper::isKaryawanKP($jilid->mahasiswa);
                                                    ?>
                                                    <?php if($jilid->status == \App\Models\KP\Jilid::JILID_REVIEW): ?>
                                                        <tr>
                                                            <td><?php echo e($no++); ?></td>
                                                            <td><?php echo e($jilid->mahasiswa->nim); ?></td>
                                                            <td>
                                                                <?php echo e($jilid->mahasiswa->nama); ?>

                                                                <?php if($is_karyawan): ?>
                                                                    <br><small class="badge badge-info">Kelas Karyawan</small>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?php if($jilid->total_pembayaran): ?>
                                                                    Rp <?php echo e(number_format($jilid->total_pembayaran, 0, ',', '.')); ?>

                                                                <?php else: ?>
                                                                    -
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-secondary">Pemeriksaan</span>
                                                            </td>
                                                            <td>
                                                                <a href="<?php echo e(route('kp.pengumpulan-akhir.detail', $jilid->id)); ?>" class="btn btn-primary btn-sm">
                                                                    <i class="fas fa-eye mr-1"></i> Periksa
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    
                                    <div class="tab-pane" id="tab_2">
                                        <table id="example2" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>NIM</th>
                                                    <th>Nama Mahasiswa</th>
                                                    <th>Total Pembayaran</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no = 1; ?>
                                                <?php $__currentLoopData = $jilids_kp; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jilid): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        $is_karyawan = \App\Helpers\AppHelper::isKaryawanKP($jilid->mahasiswa);
                                                    ?>
                                                    <?php if($jilid->status == \App\Models\KP\Jilid::JILID_SELESAI): ?>
                                                        <tr>
                                                            <td><?php echo e($no++); ?></td>
                                                            <td><?php echo e($jilid->mahasiswa->nim); ?></td>
                                                            <td>
                                                                <?php echo e($jilid->mahasiswa->nama); ?>

                                                                <?php if($is_karyawan): ?>
                                                                    <br><small class="badge badge-info">Kelas Karyawan</small>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?php if($jilid->total_pembayaran): ?>
                                                                    Rp <?php echo e(number_format($jilid->total_pembayaran, 0, ',', '.')); ?>

                                                                <?php else: ?>
                                                                    -
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-success">Selesai</span>
                                                                <?php if($jilid->is_completed): ?>
                                                                    <br><small class="text-primary">Sudah setor perpus</small>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <a href="<?php echo e(route('kp.pengumpulan-akhir.detail', $jilid->id)); ?>" class="btn btn-info btn-sm">
                                                                    <i class="fas fa-info-circle mr-1"></i> Detail
                                                                </a>
                                                                <?php if(!$jilid->is_completed): ?>
                                                                    <a href="<?php echo e(route('kp.pengumpulan-akhir.confirm.completed', $jilid->id)); ?>"
                                                                        class="btn btn-success btn-sm"
                                                                        onclick="return confirm('Yakin ingin konfirmasi bahwa mahasiswa sudah setor ke perpustakaan?')">
                                                                        <i class="fas fa-check mr-1"></i> Konfirmasi Setor
                                                                    </a>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    
                                    <div class="tab-pane" id="tab_3">
                                        <table id="example3" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>NIM</th>
                                                    <th>Nama Mahasiswa</th>
                                                    <th>Total Pembayaran</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no = 1; ?>
                                                <?php $__currentLoopData = $jilids_kp; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jilid): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        $is_karyawan = \App\Helpers\AppHelper::isKaryawanKP($jilid->mahasiswa);
                                                    ?>
                                                    <?php if($jilid->status == \App\Models\KP\Jilid::JILID_REVISI): ?>
                                                        <tr>
                                                            <td><?php echo e($no++); ?></td>
                                                            <td><?php echo e($jilid->mahasiswa->nim); ?></td>
                                                            <td>
                                                                <?php echo e($jilid->mahasiswa->nama); ?>

                                                                <?php if($is_karyawan): ?>
                                                                    <br><small class="badge badge-info">Kelas Karyawan</small>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>-</td>
                                                            <td>
                                                                <span class="badge bg-warning">Revisi</span>
                                                            </td>
                                                            <td>
                                                                <a href="<?php echo e(route('kp.pengumpulan-akhir.detail', $jilid->id)); ?>" class="btn btn-primary btn-sm">
                                                                    <i class="fas fa-info-circle mr-1"></i> Detail
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    
                                    <div class="tab-pane" id="tab_4">
                                        <table id="example4" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>NIM</th>
                                                    <th>Nama Mahasiswa</th>
                                                    <th>Total Pembayaran</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no = 1; ?>
                                                <?php $__currentLoopData = $jilids_kp; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jilid): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        $is_karyawan = \App\Helpers\AppHelper::isKaryawanKP($jilid->mahasiswa);
                                                    ?>
                                                    <?php if($jilid->status == \App\Models\KP\Jilid::JILID_VALID): ?>
                                                        <tr>
                                                            <td><?php echo e($no++); ?></td>
                                                            <td><?php echo e($jilid->mahasiswa->nim); ?></td>
                                                            <td>
                                                                <?php echo e($jilid->mahasiswa->nama); ?>

                                                                <?php if($is_karyawan): ?>
                                                                    <br><small class="badge badge-info">Kelas Karyawan</small>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?php if($jilid->total_pembayaran): ?>
                                                                    Rp <?php echo e(number_format($jilid->total_pembayaran, 0, ',', '.')); ?>

                                                                <?php else: ?>
                                                                    -
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-primary">Valid</span>
                                                            </td>
                                                            <td>
                                                                <a href="<?php echo e(route('kp.pengumpulan-akhir.detail', $jilid->id)); ?>" class="btn btn-primary btn-sm">
                                                                    <i class="fas fa-eye mr-1"></i> Dokumen
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php else: ?>
                                
                                <ul class="nav nav-pills mb-3">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#tab_kp" data-toggle="tab">
                                            <i class="fas fa-briefcase mr-1"></i> Jilid KP
                                        </a>
                                    </li>
                    <li class="nav-item">
                                        <a class="nav-link" href="#tab_ta" data-toggle="tab">
                                            <i class="fas fa-graduation-cap mr-1"></i> Jilid TA
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    
                                    <div class="tab-pane active" id="tab_kp">
                                        <ul class="nav nav-pills mb-3">
                                            <li class="nav-item">
                                                <a class="nav-link active" href="#tab_kp_valid" data-toggle="tab">
                                                    <i class="fas fa-clock mr-1"></i> Menunggu Jilid
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#tab_kp_selesai" data-toggle="tab">
                                                    <i class="fas fa-check-circle mr-1"></i> Selesai Jilid
                                                </a>
                                            </li>
                                        </ul>

                                        <div class="tab-content">
                                            
                                            <div class="tab-pane active" id="tab_kp_valid">
                                                <table id="table_kp_valid" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>NIM</th>
                                                            <th>Nama Mahasiswa</th>
                                                            <th>Tanggal Submit</th>
                                                            <th>Status</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $no = 1; ?>
                                                        <?php $__currentLoopData = $jilids_kp; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jilid): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php
                                                                $is_karyawan = \App\Helpers\AppHelper::isKaryawanKP($jilid->mahasiswa);
                                                            ?>
                                                            <?php if($jilid->status == \App\Models\KP\Jilid::JILID_VALID): ?>
                                                            <tr>
                                                                <td><?php echo e($no++); ?></td>
                                                                <td><?php echo e($jilid->mahasiswa->nim); ?></td>
                                                                <td>
                                                                    <?php echo e($jilid->mahasiswa->nama); ?>

                                                                    <?php if($is_karyawan): ?>
                                                                        <br><small class="badge badge-info">Karyawan</small>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td><?php echo e($jilid->created_at->format('d M Y H:i')); ?></td>
                                                                <td>
                                                                    <span class="badge bg-primary">Menunggu Jilid</span>
                                                                </td>
                                                                <td>
                                                                    <a href="<?php echo e(route('kp.pengumpulan-akhir.detail', $jilid->id)); ?>" class="btn btn-primary btn-sm">
                                                                        <i class="fas fa-book mr-1"></i> Proses Jilid
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                            
                                            <div class="tab-pane" id="tab_kp_selesai">
                                                <table id="table_kp_selesai" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>NIM</th>
                                                            <th>Nama Mahasiswa</th>
                                                            <th>Total Pembayaran</th>
                                                            <th>Status</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $no = 1; ?>
                                                        <?php $__currentLoopData = $jilids_kp; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jilid): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php
                                                                $is_karyawan = \App\Helpers\AppHelper::isKaryawanKP($jilid->mahasiswa);
                                                            ?>
                                                            <?php if($jilid->status == \App\Models\KP\Jilid::JILID_SELESAI): ?>
                                                            <tr>
                                                                <td><?php echo e($no++); ?></td>
                                                                <td><?php echo e($jilid->mahasiswa->nim); ?></td>
                                                                <td>
                                                                    <?php echo e($jilid->mahasiswa->nama); ?>

                                                                    <?php if($is_karyawan): ?>
                                                                        <br><small class="badge badge-info">Karyawan</small>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td>
                                                                    <?php if($jilid->total_pembayaran): ?>
                                                                        <span class="text-success font-weight-bold">
                                                                            Rp <?php echo e(number_format($jilid->total_pembayaran, 0, ',', '.')); ?>

                                                                        </span>
                                                                    <?php else: ?>
                                                                        -
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td>
                                                                    <span class="badge bg-success">Selesai</span>
                                                                    <?php if($jilid->is_completed): ?>
                                                                        <br><small class="text-primary">Sudah setor perpus</small>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td>
                                                                    <a href="<?php echo e(route('kp.pengumpulan-akhir.detail', $jilid->id)); ?>" class="btn btn-info btn-sm">
                                                                        <i class="fas fa-info-circle mr-1"></i> Detail
                                                                    </a>
                                                                    <?php if(!$jilid->is_completed): ?>
                                                                        <a href="<?php echo e(route('kp.pengumpulan-akhir.confirm.completed', $jilid->id)); ?>"
                                                                            class="btn btn-success btn-sm"
                                                                            onclick="return confirm('Yakin ingin konfirmasi bahwa mahasiswa sudah setor ke perpustakaan?')">
                                                                            <i class="fas fa-check mr-1"></i> Konfirmasi Setor
                                                                        </a>
                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>
                                                            <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    
                                    <div class="tab-pane" id="tab_ta">
                                        <ul class="nav nav-pills mb-3">
                                            <li class="nav-item">
                                                <a class="nav-link active" href="#tab_ta_valid" data-toggle="tab">
                                                    <i class="fas fa-clock mr-1"></i> Menunggu Jilid
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#tab_ta_selesai" data-toggle="tab">
                                                    <i class="fas fa-check-circle mr-1"></i> Selesai Jilid
                                                </a>
                                            </li>
                                        </ul>

                                        <div class="tab-content">
                                            
                                            <div class="tab-pane active" id="tab_ta_valid">
                                                <table id="table_ta_valid" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>NIM</th>
                                                            <th>Nama Mahasiswa</th>
                                                            <th>Tanggal Submit</th>
                                                            <th>Status</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $no = 1; ?>
                                                        <?php $__currentLoopData = $jilids_ta; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jilid): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php if($jilid->status == 'terkumpul'): ?>
                                                            <tr>
                                                                <td><?php echo e($no++); ?></td>
                                                                <td><?php echo e($jilid->mahasiswa->nim); ?></td>
                                                                <td><?php echo e($jilid->mahasiswa->nama); ?></td>
                                                                <td><?php echo e($jilid->created_at->format('d M Y H:i')); ?></td>
                                                                <td>
                                                                    <span class="badge bg-primary">Menunggu Jilid</span>
                                                                </td>
                                                                <td>
                                                                    <a href="<?php echo e(route('jilid.detail', $jilid->id)); ?>" class="btn btn-primary btn-sm">
                                                                        <i class="fas fa-book mr-1"></i> Proses Jilid
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                            
                                            <div class="tab-pane" id="tab_ta_selesai">
                                                <table id="table_ta_selesai" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>NIM</th>
                                                            <th>Nama Mahasiswa</th>
                                                            <th>Total Pembayaran</th>
                                                            <th>Status</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $no = 1; ?>
                                                        <?php $__currentLoopData = $jilids_ta; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jilid): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php if($jilid->status == 'selesai'): ?>
                                                            <tr>
                                                                <td><?php echo e($no++); ?></td>
                                                                <td><?php echo e($jilid->mahasiswa->nim); ?></td>
                                                                <td><?php echo e($jilid->mahasiswa->nama); ?></td>
                                                                <td>
                                                                    <?php if($jilid->total_pembayaran): ?>
                                                                        <span class="text-success font-weight-bold">
                                                                            Rp <?php echo e(number_format($jilid->total_pembayaran, 0, ',', '.')); ?>

                                                                        </span>
                                                                    <?php else: ?>
                                                                        -
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td>
                                                                    <span class="badge bg-success">Selesai</span>
                                                                </td>
                                                                <td>
                                                                    <a href="<?php echo e(route('jilid.detail', $jilid->id)); ?>" class="btn btn-info btn-sm">
                                                                        <i class="fas fa-info-circle mr-1"></i> Detail
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(Auth::guard('admin')->user()->type == \App\Models\Admin::TYPE_SUPER_ADMIN ? 'kp.layouts.dashboard' : 'kp.layouts.dashboardFotokopi', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/pengumpulan-akhir/admin-index.blade.php ENDPATH**/ ?>