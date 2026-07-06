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
                        <li class="breadcrumb-item"><a href="#"><?php echo e($title); ?></a></li>
                        <li class="breadcrumb-item active">Home</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="mb-3">
                <a href="<?php echo e(route($route)); ?>" class="btn btn-secondary shadow">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>

            <div class="row">
                <div class="col-12">
                    <!-- Info Mahasiswa -->
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-user mr-2"></i>Informasi Mahasiswa</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="200"><b>NIM</b></td>
                                    <td><?php echo e($mahasiswa->nim); ?></td>
                                </tr>
                                <tr>
                                    <td><b>Nama</b></td>
                                    <td><?php echo e($mahasiswa->nama); ?></td>
                                </tr>
                                <tr>
                                    <td><b>Prodi</b></td>
                                    <td><?php echo e($mahasiswa->prodi); ?></td>
                                </tr>
                                <tr>
                                    <td><b>Dosen Pembimbing</b></td>
                                    <td><?php echo e($dosen->nama); ?>, <?php echo e($dosen->gelar); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    
                    <?php if(isset($ajuan_manuals) && $ajuan_manuals->count() > 0): ?>
                    <div class="card card-warning card-outline mb-4">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-file-signature mr-2"></i>Validasi Bimbingan Manual (Offline)</h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-2"></i> Berikut daftar pengajuan lembar bimbingan manual offline. Silahkan ACC untuk validasi bimbingan.
                            </div>
                            <div class="table-responsive">
                                <table id="tableAjuanManual" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Detail</th>
                                            <th>Catatan Pembimbing</th>
                                            <th>File Laporan</th>
                                            <th>Lembar Bimbingan</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $ajuan_manuals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $manual): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($loop->iteration); ?></td>
                                            <td><?php echo e(\Carbon\Carbon::parse($manual->tanggal_bimbingan)->format('d/m/Y')); ?></td>
                                            <td>
                                                <small class="text-muted">BAB: <?php echo e($manual->bimbingan->bagian->bagian ?? '-'); ?></small><br>
                                                Status Bimbingan:
                                                <b><?php echo e(strtoupper($manual->status_mahasiswa)); ?></b>
                                            </td>
                                            <td>
                                                <?php echo e($manual->keterangan ? Str::limit($manual->keterangan, 50) : '-'); ?>

                                            </td>
                                            <td>
                                                <?php if($manual->bimbingan && $manual->bimbingan->lampiran): ?>
                                                    <a href="<?php echo e(\App\Helpers\StorageHelper::kpFileUrl($manual->bimbingan->lampiran)); ?>" target="_blank" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-file-pdf"></i> Lihat
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($manual->foto_lembar_bimbingan && $manual->foto_lembar_bimbingan != '0'): ?>
                                                    <a href="<?php echo e(\App\Helpers\StorageHelper::kpFileUrl($manual->foto_lembar_bimbingan)); ?>" target="_blank" class="btn btn-sm btn-info">
                                                        <i class="fas fa-file-alt"></i> Lihat
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($manual->status == 'pending'): ?>
                                                    <span class="badge bg-secondary">PENDING</span>
                                                <?php elseif($manual->status == 'acc'): ?>
                                                    <span class="badge bg-success">ACC</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">DITOLAK</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($manual->status == 'pending'): ?>
                                                    <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-manual-acc-<?php echo e($manual->id); ?>">ACC</button>
                                                    <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-manual-revisi-<?php echo e($manual->id); ?>">Tolak</button>
                                                <?php else: ?>
                                                    <a href="<?php echo e(route('kp.bimbingan-manual.review.detail', $manual->id)); ?>" class="btn btn-info btn-sm">
                                                        <i class="fas fa-info-circle"></i> Detail
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </section>

    
    <?php if(isset($ajuan_manuals)): ?>
    <?php $__currentLoopData = $ajuan_manuals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $manual): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($manual->status == 'pending'): ?>
            <div class="modal fade" id="modal-manual-acc-<?php echo e($manual->id); ?>">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="<?php echo e(route('kp.bimbingan-manual.acc')); ?>" method="post">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="id" value="<?php echo e($manual->id); ?>">
                            <input type="hidden" name="redirect_url" value="<?php echo e(url()->current()); ?>">
                            <div class="modal-header bg-success text-white">
                                <h4 class="modal-title">ACC Bimbingan Manual</h4>
                                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <p>Setujui lembar bimbingan manual ini?</p>

                                <div class="form-group">
                                    <label>Tanggal Bimbingan <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_bimbingan" class="form-control"
                                        value="<?php echo e(\Carbon\Carbon::parse($manual->tanggal_bimbingan)->format('Y-m-d')); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Status Bimbingan <span class="text-danger">*</span></label>
                                    <select name="status_mahasiswa" class="form-control" required>
                                        <option value="acc" <?php echo e($manual->status_mahasiswa == 'acc' ? 'selected' : ''); ?>>ACC - Dosen menyetujui</option>
                                        <option value="revisi" <?php echo e($manual->status_mahasiswa == 'revisi' ? 'selected' : ''); ?>>Revisi - Masih ada perbaikan</option>
                                    </select>
                                    <small class="text-muted">Sesuaikan dengan isi lembar bimbingan manual yang diunggah mahasiswa.</small>
                                </div>
                                <div class="form-group">
                                    <label>Catatan Pembimbing <span class="text-danger">*</span></label>
                                    <textarea name="keterangan" class="form-control" rows="3" required placeholder="Masukkan catatan pembimbing..."><?php echo e($manual->keterangan); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Catatan Reviewer <span class="text-danger">*</span></label>
                                    <textarea name="catatan" class="form-control" rows="3" required placeholder="Masukkan catatan validasi..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success">ACC</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modal-manual-revisi-<?php echo e($manual->id); ?>">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="<?php echo e(route('kp.bimbingan-manual.revisi')); ?>" method="post">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="id" value="<?php echo e($manual->id); ?>">
                            <input type="hidden" name="redirect_url" value="<?php echo e(url()->current()); ?>">
                            <div class="modal-header bg-danger text-white">
                                <h4 class="modal-title">Tolak Bimbingan Manual</h4>
                                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    Pengajuan bimbingan manual akan ditolak. Mahasiswa harus mengajukan ulang.
                                </div>

                                <div class="form-group">
                                    <label>Status Bimbingan <span class="text-danger">*</span></label>
                                    <select name="status_mahasiswa" class="form-control" required>
                                        <option value="acc" <?php echo e($manual->status_mahasiswa == 'acc' ? 'selected' : ''); ?>>ACC - Dosen menyetujui</option>
                                        <option value="revisi" <?php echo e($manual->status_mahasiswa == 'revisi' ? 'selected' : ''); ?>>Revisi - Masih ada perbaikan</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Catatan Pembimbing <span class="text-danger">*</span></label>
                                    <textarea name="keterangan" class="form-control" rows="3" required placeholder="Masukkan catatan pembimbing..."><?php echo e($manual->keterangan); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Alasan Penolakan <span class="text-danger">*</span></label>
                                    <textarea name="catatan" class="form-control" rows="3" required placeholder="Masukkan alasan penolakan..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger">Tolak</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // DataTable untuk tabel Ajuan Manual
    if ($('#tableAjuanManual').length && !$.fn.DataTable.isDataTable('#tableAjuanManual')) {
        $('#tableAjuanManual').DataTable({
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
                "search": "Cari:",
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

<?php echo $__env->make('kp.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/admin/bimbingan/bimbingan-store.blade.php ENDPATH**/ ?>