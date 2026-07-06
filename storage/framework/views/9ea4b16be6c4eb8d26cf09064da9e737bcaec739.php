<?php
    $active = $active ?? '';
    $module = $module ?? '';
    $isUtilityPage = request()->routeIs('profile', 'mahasiswa.account', 'kp.profile', 'kp.mahasiswa.account');
    $showModuleNavigation = ! $isUtilityPage;
    
    // Tentukan route dashboard berdasarkan module
    $dashboardRoute = 'dashboard.mahasiswa'; // Default ke pilih sistem
    if ($module == 'ta') {
        $dashboardRoute = 'dashboard.mahasiswa.ta';
    } elseif ($module == 'kp') {
        $dashboardRoute = 'kp.dashboard.mahasiswa';
    }
?>
<nav class="main-header navbar navbar-expand-md navbar-light navbar-white sticky-top">
    <div class="container">
        <a href="<?php echo e(route($dashboardRoute)); ?>" class="navbar-brand">
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

                
                <?php if($module == 'ta' || empty($module)): ?>
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="<?php echo e(route('dashboard.mahasiswa.ta')); ?>"
                               class="nav-link <?php echo e($active == 'dashboard' ? 'active' : ''); ?>">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('pengajuan.mahasiswa')); ?>"
                               class="nav-link <?php echo e($active == 'pengajuan' ? 'active' : ''); ?>">Pengajuan TA</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('pendaftaran.mahasiswa')); ?>"
                               class="nav-link <?php echo e($active == 'pendaftaran' ? 'active' : ''); ?>">Pendaftaran TA</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('bimbingan.mahasiswa')); ?>"
                               class="nav-link <?php echo e($active == 'bimbingan' ? 'active' : ''); ?>">Bimbingan</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('seminar.mahasiswa')); ?>"
                               class="nav-link <?php echo e($active == 'seminar' ? 'active' : ''); ?>">Seminar Proposal</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('ujian.mahasiswa')); ?>"
                               class="nav-link <?php echo e($active == 'ujian' ? 'active' : ''); ?>">Ujian Pendadaran</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('jilid.mahasiswa')); ?>"
                               class="nav-link <?php echo e($active == 'jilid' ? 'active' : ''); ?>">Jilid TA</a>
                        </li>
                    </ul>
                <?php endif; ?>

                
                <?php if($module == 'kp'): ?>
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="<?php echo e(route('kp.dashboard.mahasiswa')); ?>"
                               class="nav-link <?php echo e($active == 'dashboard' ? 'active' : ''); ?>">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('kp.pengajuan.mahasiswa')); ?>"
                               class="nav-link <?php echo e($active == 'pengajuan' ? 'active' : ''); ?>">Pengajuan KP</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('kp.pendaftaran.mahasiswa')); ?>"
                               class="nav-link <?php echo e($active == 'pendaftaran' ? 'active' : ''); ?>">Pendaftaran KP</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('kp.bimbingan.mahasiswa')); ?>"
                               class="nav-link <?php echo e($active == 'bimbingan' ? 'active' : ''); ?>">Bimbingan KP</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('kp.seminar.mahasiswa')); ?>"
                               class="nav-link <?php echo e($active == 'seminar' ? 'active' : ''); ?>">Seminar KP</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('kp.pengumpulan-akhir.mahasiswa')); ?>"
                               class="nav-link <?php echo e($active == 'pengumpulan-akhir' ? 'active' : ''); ?>">Jilid KP</a>
                        </li>
                    </ul>
                <?php endif; ?>

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
                    <a href="<?php echo e(route('profile')); ?>" class="dropdown-item">
                        <i class="far fa-user mr-2"></i> Profile
                    </a>
                    <a href="<?php echo e(route('mahasiswa.account')); ?>" class="dropdown-item">
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
<?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/partials/navbarMahasiswa.blade.php ENDPATH**/ ?>