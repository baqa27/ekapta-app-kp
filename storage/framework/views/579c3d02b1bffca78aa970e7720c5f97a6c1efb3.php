<?php
    $active = $active ?? '';
    $module = $module ?? '';
?>
<nav class="main-header navbar navbar-expand navbar-white navbar-light sticky-top">

    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-user"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <?php echo e(Auth::guard('prodi')->user()->namaprodi); ?> <?php echo e('('.Auth::guard('prodi')->user()->kode.')'); ?>

                </a>
                <a href="<?php echo e(route('prodi.account')); ?>" class="dropdown-item">
                    <i class="bi bi-gear mr-2"></i> Pengaturan Akun
                </a>
                <div class="dropdown-divider"></div>
                <a href="<?php echo e(route('logout.prodi')); ?>" class="dropdown-item dropdown-footer bg-danger">Logout <i
                        class="bi bi-box-arrow-right ml-2"></i></a>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo e(route('dashboard.prodi')); ?>" class="brand-link">
        <img src="https://unsiq.ac.id/img/UNSIQ-bunder.ico" alt="AdminLTE Logo"
             class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">EKAPTA FASTIKOM</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?php echo e(asset('ekapta')); ?>/adminLTE/dist/img/default-profile.png" class="img-circle elevation-2"
                     alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?php echo e(Auth::guard('prodi')->user()->namaprodi); ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="<?php echo e(route('dashboard.prodi')); ?>"
                       class="nav-link <?php echo e($active=='dashboard' ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                
                <li class="nav-item has-treeview <?php echo e(in_array($active, ['pengajuan-kp', 'bimbingan-kp', 'seminar-kp', 'bimbingan-input-kp', 'pengumpulan-akhir-kp']) ? 'menu-open' : ''); ?>">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-briefcase"></i>
                        <p>
                            Menu Kerja Praktek
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo e(route('kp.pengajuan.prodi')); ?>"
                               class="nav-link <?php echo e($active=='pengajuan-kp' ? 'active' : ''); ?>">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Validasi Pengajuan KP</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('kp.bimbingan.prodi.input')); ?>"
                               class="nav-link <?php echo e($active == 'bimbingan-input-kp' ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Validasi Bimbingan KP</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?php echo e(route('kp.bimbingan.prodi')); ?>"
                               class="nav-link <?php echo e($active=='bimbingan-kp' ? 'active' : ''); ?>">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Bimbingan KP</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('kp.seminar.prodi')); ?>"
                               class="nav-link <?php echo e($active=='seminar-kp' ? 'active' : ''); ?>">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Seminar KP</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('kp.pengumpulan-akhir.prodi.index')); ?>"
                               class="nav-link <?php echo e($active=='pengumpulan-akhir-kp' ? 'active' : ''); ?>">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Data Jilid KP</p>
                            </a>
                        </li>
                    </ul>
                </li>

                
                <li class="nav-item has-treeview <?php echo e(in_array($active, ['pengajuan', 'bimbingan', 'seminar', 'ujian', 'bimbingan-input-ta', 'jilid-ta-prodi']) ? 'menu-open' : ''); ?>">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-graduation-cap"></i>
                        <p>
                            Menu Tugas Akhir
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo e(route('pengajuan.prodi')); ?>"
                               class="nav-link <?php echo e($active=='pengajuan' ? 'active' : ''); ?>">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Validasi Pengajuan TA</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('bimbingan.prodi.input')); ?>"
                               class="nav-link <?php echo e($active == 'bimbingan-input-ta' ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Validasi Bimbingan TA</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('bimbingan.prodi')); ?>"
                               class="nav-link <?php echo e($active=='bimbingan' ? 'active' : ''); ?>">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Bimbingan TA</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('seminar.prodi')); ?>"
                               class="nav-link <?php echo e($active=='seminar' ? 'active' : ''); ?>">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Seminar TA</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('ujian.prodi')); ?>"
                               class="nav-link <?php echo e($active=='ujian' ? 'active' : ''); ?>">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Ujian Pendadaran TA</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('jilid.prodi.index')); ?>"
                               class="nav-link <?php echo e($active=='jilid-ta-prodi' ? 'active' : ''); ?>">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Data Jilid TA</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
<?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/partials/sidebarProdi.blade.php ENDPATH**/ ?>