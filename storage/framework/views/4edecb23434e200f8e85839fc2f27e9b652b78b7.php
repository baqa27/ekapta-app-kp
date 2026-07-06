<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
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
    <div class="content">
        <div class="container">
            <?php if(Auth::guard('admin')->user()->type != \App\Models\Admin::TYPE_SUPER_ADMIN): ?>
                <div class="mb-3 d-flex">
                    <h4 class="flex-grow-1">Selamat datang <?php echo e(Auth::guard('admin')->user()->nama); ?></h4>
                    <div class="flex-shrink-0">
                        <a href="<?php echo e(route('logout.admin')); ?>" class="btn btn-danger float-end">Logout <i
                                class="bi bi-box-arrow-right ml-2"></i></a>
                    </div>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header d-flex p-0">
                            <h3 class="card-title p-3"><?php echo e($title); ?></h3>
                            <ul class="nav nav-pills ml-auto p-2">
                                <?php if(Auth::guard('admin')->user()->type == \App\Models\Admin::TYPE_SUPER_ADMIN): ?>
                                    <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Jilid
                                            Review</a>
                                    </li>
                                    <li class="nav-item"><a class="nav-link" href="#tab_3" data-toggle="tab">Jilid
                                            Revisi</a>
                                    </li>
                                    <li class="nav-item"><a class="nav-link" href="#tab_4" data-toggle="tab">Jilid
                                            Valid</a>
                                    </li>
                                     <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Jilid
                                            Selesai</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <div class="card-body">
                            <?php if(Auth::guard('admin')->user()->type == \App\Models\Admin::TYPE_SUPER_ADMIN): ?>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <table id="example1" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>NIM/NAMA MAHASISWA</th>
                                                    <th>TOTAL PEMBAYARAN</th>
                                                    <th>STATUS</th>
                                                    <th>AKSI</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $no = 1;
                                                ?>
                                                <?php $__currentLoopData = $jilids; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jilid): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($jilid->status == \App\Models\Jilid::JILID_REVIEW): ?>
                                                        <tr>
                                                            <td><?php echo e($no++); ?></td>
                                                            <td><?php echo e($jilid->mahasiswa->nim . '/' . $jilid->mahasiswa->nama); ?>

                                                            </td>
                                                            <td>
                                                                <?php if($jilid->total_pembayaran): ?>
                                                                    <span class="text-success">
                                                                        Rp
                                                                        <?php echo e(number_format($jilid->total_pembayaran, 0, ',', '.')); ?>

                                                                    </span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-secondary">PEMERIKSAAN
                                                                    DOKUMEN</span>
                                                            </td>
                                                            <td>
                                                                <?php if($jilid->status == 1): ?>
                                                                    <a href="<?php echo e(route('jilid.detail', $jilid->id)); ?>"
                                                                        class="btn btn-primary btn-sm"><i
                                                                            class="fas fa-eye"></i>
                                                                        Periksa Dokumen</a>
                                                                <?php elseif($jilid->status == 3): ?>
                                                                    <a href="<?php echo e(route('jilid.detail', $jilid->id)); ?>"
                                                                        class="btn btn-primary btn-sm"><i
                                                                            class="fas fa-book"></i> JILID
                                                                        SKRIPSI</a>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>No</th>
                                                    <th>NIM/NAMA MAHASISWA</th>
                                                    <th>TOTAL PEMBAYARAN</th>
                                                    <th>STATUS</th>
                                                    <th>AKSI</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="tab-pane" id="tab_2">
                                        <table id="example2" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>NIM/NAMA MAHASISWA</th>
                                                    <th>TOTAL PEMBAYARAN</th>
                                                    <th>STATUS</th>
                                                    <th>AKSI</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $no = 1;
                                                ?>
                                                <?php $__currentLoopData = $jilids; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jilid): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($jilid->status == \App\Models\Jilid::JILID_SELESAI): ?>
                                                        <tr>
                                                            <td><?php echo e($no++); ?></td>
                                                            <td><?php echo e($jilid->mahasiswa->nim . '/' . $jilid->mahasiswa->nama); ?>

                                                            </td>
                                                            <td>
                                                                <?php if($jilid->total_pembayaran): ?>
                                                                    <span class="text-success">
                                                                        Rp
                                                                        <?php echo e(number_format($jilid->total_pembayaran, 0, ',', '.')); ?>

                                                                    </span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-success">SELESAI</span>
                                                                <?php if($jilid->is_completed): ?>
                                                                    <br>
                                                                    <span class="badge bg-primary"><i
                                                                            class="fas fa-check-circle"></i> Sudah
                                                                        disetorkan ke perpus</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td class="text-center">
                                                                <a href="<?php echo e(route('jilid.detail', $jilid->id)); ?>"
                                                                    class="btn btn-info btn-sm"><i
                                                                        class="fas fa-info-circle"></i> Detail</a>
                                                                <?php if(!$jilid->is_completed): ?>
                                                                    <a href="<?php echo e(route('jilid.confirm.completed', $jilid->id)); ?>"
                                                                        class="btn btn-success btn-sm"
                                                                        onclick="return confirm('Yakin ingin konfirmasi bahwa mahasiswa sudah setor ke perpustakaan?')"><i
                                                                            class="fas fa-check-circle"></i> Konfirmasi Setor</a>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>No</th>
                                                    <th>NIM/NAMA MAHASISWA</th>
                                                    <th>TOTAL PEMBAYARAN</th>
                                                    <th>STATUS</th>
                                                    <th>AKSI</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="tab-pane" id="tab_3">
                                        <table id="example3" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>NIM/NAMA MAHASISWA</th>
                                                    <th>TOTAL PEMBAYARAN</th>
                                                    <th>STATUS</th>
                                                    <th>AKSI</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $no = 1;
                                                ?>
                                                <?php $__currentLoopData = $jilids; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jilid): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($jilid->status == \App\Models\Jilid::JILID_REVISI): ?>
                                                        <tr>
                                                            <td><?php echo e($no++); ?></td>
                                                            <td><?php echo e($jilid->mahasiswa->nim . '/' . $jilid->mahasiswa->nama); ?>

                                                            </td>
                                                            <td></td>
                                                            <td>
                                                                <span class="badge bg-warning">REVISI</span>
                                                            </td>
                                                            <td>
                                                                <a href="<?php echo e(route('jilid.detail', $jilid->id)); ?>"
                                                                    class="btn btn-info btn-sm"><i
                                                                        class="fas fa-info-circle"></i> Detail</a>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>No</th>
                                                    <th>NIM/NAMA MAHASISWA</th>
                                                    <th>TOTAL PEMBAYARAN</th>
                                                    <th>STATUS</th>
                                                    <th>AKSI</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="tab-pane" id="tab_4">
                                        <table id="example4" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>NIM/NAMA MAHASISWA</th>
                                                    <th>TOTAL PEMBAYARAN</th>
                                                    <th>STATUS</th>
                                                    <th>AKSI</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $no = 1;
                                                ?>
                                                <?php $__currentLoopData = $jilids; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jilid): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($jilid->status == \App\Models\Jilid::JILID_VALID): ?>
                                                        <tr>
                                                            <td><?php echo e($no++); ?></td>
                                                            <td><?php echo e($jilid->mahasiswa->nim . '/' . $jilid->mahasiswa->nama); ?>

                                                            </td>
                                                            <td></td>
                                                            <td>
                                                                <span class="badge bg-primary">VALID</span>
                                                            </td>
                                                            <td>
                                                                <a href="<?php echo e(route('jilid.detail', $jilid->id)); ?>"
                                                                    class="btn btn-info btn-sm"><i
                                                                        class="fas fa-info-circle"></i> Detail</a>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>No</th>
                                                    <th>NIM/NAMA MAHASISWA</th>
                                                    <th>TOTAL PEMBAYARAN</th>
                                                    <th>STATUS</th>
                                                    <th>AKSI</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            <?php else: ?>
                                <table id="example1" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>NIM/NAMA MAHASISWA</th>
                                            <th>TOTAL PEMBAYARAN</th>
                                            <th>STATUS</th>
                                            <th>AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $no = 1;
                                        ?>
                                        <?php $__currentLoopData = $jilids; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jilid): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($no++); ?></td>
                                                <td><?php echo e($jilid->mahasiswa->nim . '/' . $jilid->mahasiswa->nama); ?>

                                                </td>
                                                <td>
                                                    <?php if($jilid->total_pembayaran): ?>
                                                        <span class="text-success">
                                                            Rp
                                                            <?php echo e(number_format($jilid->total_pembayaran, 0, ',', '.')); ?>

                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">VALID</span>
                                                </td>
                                                <td>
                                                    <a href="<?php echo e(route('jilid.detail', $jilid->id)); ?>"
                                                        class="btn btn-primary btn-sm"><i class="fas fa-book"></i> JILID
                                                        SKRIPSI</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>No</th>
                                            <th>NIM/NAMA MAHASISWA</th>
                                            <th>TOTAL PEMBAYARAN</th>
                                            <th>STATUS</th>
                                            <th>AKSI</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            <?php endif; ?>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make(Auth::guard('admin')->user()->type == \App\Models\Admin::TYPE_SUPER_ADMIN ? 'layouts.dashboard' : 'layouts.dashboardFotokopi', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/pages/fotokopi/dashboard-fotokopi.blade.php ENDPATH**/ ?>