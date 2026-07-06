<?php
    $isUtilityPage = request()->routeIs('profile', 'mahasiswa.account', 'kp.profile', 'kp.mahasiswa.account');
    $showModuleNavigation = ! $isUtilityPage;
?>
<nav class="main-header navbar navbar-expand-md navbar-light navbar-white sticky-top">
    <div class="container">
        <a href="<?php echo e(route('kp.dashboard.mahasiswa')); ?>" class="navbar-brand">
            <img src="https://unsiq.ac.id/img/UNSIQ-bunder.ico" alt="AdminLTE Logo"
                 class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light" style="text-transform: uppercase;">
                <b><?php echo e(config('app.name')); ?></b>
            </span>
        </a>

        <?php if($showModuleNavigation): ?>
            <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        <?php endif; ?>

        <?php if($showModuleNavigation): ?>
            <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="<?php echo e(route('kp.dashboard.mahasiswa')); ?>"
                           class="nav-link <?php echo e(($active ?? '') == 'dashboard' ? 'active' : ''); ?>">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('kp.pengajuan.mahasiswa')); ?>"
                           class="nav-link <?php echo e(($active ?? '') == 'pengajuan' ? 'active' : ''); ?>">Pengajuan KP</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('kp.pendaftaran.mahasiswa')); ?>"
                           class="nav-link <?php echo e(($active ?? '') == 'pendaftaran' ? 'active' : ''); ?>">Pendaftaran KP</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('kp.bimbingan.mahasiswa')); ?>"
                           class="nav-link <?php echo e(($active ?? '') == 'bimbingan' ? 'active' : ''); ?>">Bimbingan KP</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('kp.seminar.mahasiswa')); ?>"
                           class="nav-link <?php echo e(($active ?? '') == 'seminar' ? 'active' : ''); ?>">Seminar KP</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('kp.pengumpulan-akhir.mahasiswa')); ?>"
                           class="nav-link <?php echo e(($active ?? '') == 'pengumpulan-akhir' ? 'active' : ''); ?>">Jilid KP</a>
                    </li>
                </ul>
            </div>
        <?php elseif(!empty($title)): ?>
            <div class="order-3 d-none d-md-flex align-items-center text-muted small">
                <?php echo e($title); ?>

            </div>
        <?php endif; ?>

        <!-- Right navbar links -->
        <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto mr-3">
            <!-- Notifications Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-user"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <a href="#" class="dropdown-item">
                        <?php echo e(Auth::guard('mahasiswa')->user()->nama); ?>

                        <?php echo e('('.Auth::guard('mahasiswa')->user()->nim.')'); ?>

                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="<?php echo e(route('kp.profile')); ?>" class="dropdown-item">
                        <i class="far fa-user mr-2"></i> Profile
                    </a>
                    <a href="<?php echo e(route('kp.mahasiswa.account')); ?>" class="dropdown-item">
                        <i class="bi bi-gear mr-2"></i> Pengaturan Akun
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="<?php echo e(route('dashboard.mahasiswa')); ?>" class="dropdown-item">
                        <i class="bi bi-arrow-left-circle mr-2"></i> Kembali ke Pilihan Sistem
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="<?php echo e(route('logout.mahasiswa')); ?>" class="dropdown-item dropdown-footer bg-danger">Logout <i
                            class="bi bi-box-arrow-right"></i></a>

                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
        </ul>
    </div>
</nav>
<?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/partials/navbarMahasiswa.blade.php ENDPATH**/ ?>