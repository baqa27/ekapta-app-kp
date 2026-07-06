<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Bimbingan Kerja Praktek</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Pengajuan KP</a></li>
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
            <div class="row">
                <div class="col-md-10">
                    <?php if(\App\Helpers\AppHelper::check_bimbingan_kp_is_complete($mahasiswa)): ?>
                        <a href="<?php echo e(route('kp.cetak.riwayat.bimbingan.mahasiswa')); ?>" class="btn btn-primary mb-3"
                            target="_blank"><i class="fas fa-download"></i> DOWNLOAD LEMBAR BIMBINGAN KP</a>
                    <?php endif; ?>

                    <?php if($is_expired): ?>
                        <div class="mb-3 bg-danger rounded p-2">
                            Masa bimbingan anda sudah habis, silahkan lakukan <a
                                href="<?php echo e(route('kp.pendaftaran.disable', $pendaftaran_acc->id)); ?>"><u><b>Perpanjangan
                                        KP!</b></u></a>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Tanggal Berakhir Bimbingan : <b><?php echo e(\Carbon\Carbon::parse($date_expired)->locale('id')->isoFormat('D MMMM Y')); ?>

                            </b>
                            <?php if($is_seminar): ?>
                                , Selamat anda sudah bisa melakukan
                                <b><a href="<?php echo e(route('kp.seminar.create')); ?>">Pendaftaran Seminar KP</a></b>
                            <?php endif; ?>
                            <?php if($check_ujian_has_done): ?>
                                , <b><a href="<?php echo e(route('kp.pengumpulan-akhir.create')); ?>">Ajukan Penjilidan Kerja Praktek</a></b>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex justify-content-center mb-3 bg-primary rounded p-2 countdown"
                            data-expire="<?php echo e(\Carbon\Carbon::parse($date_expired)->endOfDay()->format('Y/m/d H:i:s')); ?>">
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary card-outline">
                                <div class="card-header d-flex p-0">
                                    <h3 class="card-title p-3">Bimbingan Anda</h3>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab_1">

                                            Dosen Pembimbing : <strong>
                                                <?php if($dosen_utama): ?>
                                                    <?php echo e($dosen_utama->nama . ', ' . $dosen_utama->gelar); ?>

                                                <?php endif; ?>
                                            </strong>

                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Bagian Bimbingan</th>
                                                        <th>Tanggal Bimbingan</th>
                                                        <th>Tanggal ACC</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $no = 1; ?>
                                                    <?php $__currentLoopData = $bimbingan_per_bagian; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php
                                                            $bagian = $item['bagian'];
                                                            $bimbingan = $item['bimbingan'];

                                                            // Ambil pengajuan terakhir untuk BAB ini (hanya untuk bimbingan manual)
                                                            $lastAjuan = null;
                                                            if ($bimbingan && $dosen_utama && $dosen_utama->is_manual) {
                                                                $lastAjuan = \App\Models\KP\AjuanBimbinganManualKP::where('bimbingan_id', $bimbingan->id)
                                                                    ->orderBy('created_at', 'desc')
                                                                    ->first();
                                                            }
                                                        ?>
                                                        <tr>
                                                            <td><?php echo e($no++); ?></td>
                                                            <td><?php echo e($bagian->bagian); ?></td>
                                                            <td>
                                                                <?php if($bimbingan && $bimbingan->tanggal_bimbingan): ?>
                                                                    <?php echo e(date('d M Y', strtotime($bimbingan->tanggal_bimbingan))); ?>

                                                                <?php else: ?>
                                                                    <span class="text-muted">-</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?php if($bimbingan && $bimbingan->tanggal_acc): ?>
                                                                    <?php echo e(date('d M Y', strtotime($bimbingan->tanggal_acc))); ?>

                                                                <?php else: ?>
                                                                    <span class="text-muted">-</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                
                                                                <?php if($bimbingan && $bimbingan->status): ?>
                                                                    <div>

                                                                        <?php if($bimbingan->status == 'diterima'): ?>
                                                                            <span class="badge bg-success">Diterima</span>
                                                                        <?php elseif($bimbingan->status == 'revisi'): ?>
                                                                            <span class="badge bg-warning">Revisi</span>
                                                                        <?php elseif($bimbingan->status == 'review'): ?>
                                                                            <span class="badge bg-secondary">Review</span>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                <?php else: ?>
                                                                    <span class="text-muted">-</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?php if(!$is_expired): ?>
                                                                    <?php
                                                                        $showManualBtn = ($dosen_utama && $dosen_utama->is_manual);
                                                                    ?>

                                                                    
                                                                    <?php if(!$bimbingan || $bimbingan->status == null): ?>
                                                                        <?php
                                                                            $canSubmit = false;
                                                                            // Bagian pertama selalu bisa submit
                                                                            if ($index == 0) {
                                                                                $canSubmit = true;
                                                                            }
                                                                            // Bagian selanjutnya: cek apakah bagian sebelumnya sudah ACC
                                                                            else {
                                                                                $prevItem = $bimbingan_per_bagian[$index - 1] ?? null;
                                                                                if ($prevItem && $prevItem['bimbingan'] && $prevItem['bimbingan']->status == 'diterima') {
                                                                                    $canSubmit = true;
                                                                                }
                                                                            }
                                                                        ?>

                                                                        <?php if($canSubmit): ?>
                                                                            
                                                                            <a href="<?php echo e(route('kp.bimbingan.create')); ?>?bagian_id=<?php echo e($bagian->id); ?>"
                                                                                class="btn btn-primary btn-sm shadow">
                                                                                <i class="fas fa-upload mr-1"></i>Submit</a>
                                                                        <?php endif; ?>

                                                                    
                                                                    <?php elseif($bimbingan->status == 'review'): ?>
                                                                        <?php
                                                                            // Cek apakah ada ajuan bimbingan manual yang sudah di-ACC dengan status_mahasiswa = revisi
                                                                            // Jika ada, berarti dosen offline minta revisi dan sudah divalidasi admin/prodi
                                                                            $ajuanRevisi = \App\Models\KP\AjuanBimbinganManualKP::where('bimbingan_id', $bimbingan->id)
                                                                                ->where('status_mahasiswa', 'revisi')
                                                                                ->where('status', 'acc')
                                                                                ->exists();
                                                                        ?>

                                                                        <a href="<?php echo e(route('kp.bimbingan.detail', $bimbingan->id)); ?>"
                                                                            class="btn btn-primary btn-sm shadow mb-1">
                                                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                                                        </a>

                                                                        <?php if($ajuanRevisi): ?>
                                                                            
                                                                            <a href="<?php echo e(route('kp.bimbingan.edit', $bimbingan->id)); ?>"
                                                                                class="btn btn-warning btn-sm shadow mb-1">
                                                                                <i class="fas fa-upload mr-1"></i> Submit Ulang
                                                                            </a>
                                                                        <?php endif; ?>

                                                                        <?php if($showManualBtn): ?>
                                                                            <div class="mt-1"></div>
                                                                            <a href="<?php echo e(route('kp.bimbingan-manual.create', $bimbingan->id)); ?>"
                                                                               class="btn btn-info btn-sm shadow">
                                                                               <i class="fas fa-upload mr-1"></i> Bimbingan Manual
                                                                            </a>
                                                                        <?php endif; ?>

                                                                    
                                                                    <?php elseif($bimbingan->status == 'revisi'): ?>
                                                                        <div class="d-flex flex-wrap gap-1">
                                                                            <a href="<?php echo e(route('kp.bimbingan.detail', $bimbingan->id)); ?>"
                                                                                class="btn btn-primary btn-sm shadow mr-2 mb-1">
                                                                                <i class="fas fa-info-circle mr-1"></i> Detail
                                                                            </a>
                                                                            
                                                                            <a href="<?php echo e(route('kp.bimbingan.edit', $bimbingan->id)); ?>"
                                                                                class="btn btn-success btn-sm shadow mb-1">
                                                                                <i class="fas fa-upload mr-1"></i>Submit Ulang
                                                                            </a>
                                                                        </div>

                                                                        <?php if($showManualBtn): ?>
                                                                            <div class="mt-1"></div>
                                                                            <a href="<?php echo e(route('kp.bimbingan-manual.create', $bimbingan->id)); ?>"
                                                                               class="btn btn-info btn-sm shadow">
                                                                               <i class="fas fa-upload mr-1"></i> Bimbingan Manual
                                                                            </a>
                                                                        <?php endif; ?>

                                                                    
                                                                    <?php elseif($bimbingan->status == 'diterima'): ?>
                                                                        <a href="<?php echo e(route('kp.bimbingan.detail', $bimbingan->id)); ?>"
                                                                            class="btn btn-primary btn-sm shadow mb-1">
                                                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                                                        </a>

                                                                        <?php if($showManualBtn): ?>
                                                                            <div class="mt-1"></div>
                                                                            <a href="<?php echo e(route('kp.bimbingan-manual.create', $bimbingan->id)); ?>"
                                                                               class="btn btn-info btn-sm shadow">
                                                                                <i class="fas fa-info-circle mr-1"></i> Detail Bimbingan Manual
                                                                            </a>
                                                                        <?php endif; ?>

                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Bagian Bimbingan</th>
                                                        <th>Tanggal Bimbingan</th>
                                                        <th>Tanggal ACC</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </tfoot>
                                            </table>

                                        </div>
                                        <!-- /.tab-pane -->
                                    </div>
                                    <!-- /.tab-content -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 text-center mb-3 card" style="max-height: 300px;">
                    <div class="text-center mt-3">
                        <a href="<?php echo e(route('kp.bimbingan.public', base64_encode(Auth::guard('mahasiswa')->user()->id) . uniqid())); ?>"
                            class="btn btn-secondary btn-sm shadow mb-2" target="_blank">
                            Tracking Bimbingan <small><i class="bi bi-chevron-right"></i></small>
                        </a>
                        <p class="text-secondary">Atau scan QRCODE dibawah:</p>
                        <div class="mb-3">
                            <?php echo QrCode::size(150)->generate(
                                route('kp.bimbingan.public', base64_encode(Auth::guard('mahasiswa')->user()->id) . uniqid())
                            ); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // Cek dulu apakah sudah diinisialisasi
    if (!$.fn.DataTable.isDataTable('#example1')) {
        $('#example1').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
            "language": {
                "search": "Search:",
                "lengthMenu": "Tampilkan _MENU_ data",
                "info": "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                "infoEmpty": "Tidak ada data",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "paginate": {
                    "first": "Awal",
                    "last": "Akhir",
                    "next": "Next",
                    "previous": "Previous"
                },
                "emptyTable": "Tidak ada data tersedia"
            }
        });
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('kp.layouts.dashboardMahasiswa', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/mahasiswa/bimbingan/bimbingan.blade.php ENDPATH**/ ?>