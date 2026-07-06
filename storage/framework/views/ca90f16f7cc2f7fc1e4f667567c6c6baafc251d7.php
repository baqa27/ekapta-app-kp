<?php $__env->startSection('content'); ?>
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo e($title); ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Bimbingan KP</a></li>
                        <li class="breadcrumb-item active">Detail Bimbingan Manual</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Tombol Kembali -->
            <div class="mb-3">
                <a href="javascript:history.back()" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>

            <div class="row">
                <div class="col-12">
                    <!-- Card Utama dengan Ribbon Status -->
                    <div class="card card-primary card-outline">
                        <div class="ribbon-wrapper ribbon-lg">
                            <div class="ribbon <?php echo e($ajuan->status == 'acc' ? 'bg-success' : ($ajuan->status == 'pending' ? 'bg-secondary' : 'bg-warning')); ?>">
                                <?php echo e($ajuan->getStatusLabel()); ?>

                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Info Mahasiswa & Bimbingan -->
                            <table>
                                <tr>
                                    <td width="150"><b>NIM</b></td>
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
                                <?php if($dosen): ?>
                                <tr>
                                    <td><b>Pembimbing</b></td>
                                    <td><?php echo e($dosen->nama); ?>, <?php echo e($dosen->gelar); ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if($pengajuan): ?>
                                <tr>
                                    <td><b>Judul KP</b></td>
                                    <td><?php echo e($pengajuan->judul); ?></td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <td><b>Bagian</b></td>
                                    <td><?php echo e($bimbingan->bagian->bagian ?? '-'); ?></td>
                                </tr>
                            </table>

                            <hr>

                            <p><b>Status Bimbingan</b></p>
                            <p>
                                <?php if($ajuan->status_mahasiswa == 'acc'): ?>
                                    <span class="badge badge-success">ACC</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Revisi</span>
                                <?php endif; ?>
                            </p>

                            <hr>

                            <!-- Catatan Pembimbing -->
                            <p><b>Catatan Pembimbing</b></p>
                            <p><?php echo nl2br(e($ajuan->keterangan ?? '-')); ?></p>

                            <?php if($ajuan->catatan_reviewer): ?>
                            <hr>
                            <p><b class="text-<?php echo e($ajuan->status == 'acc' ? 'success' : 'warning'); ?>">Catatan Reviewer</b></p>
                            <p><?php echo e($ajuan->catatan_reviewer); ?></p>
                            <?php endif; ?>

                            <hr>

                            <!-- Dokumen -->
                            <p><b>Dokumen</b></p>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><b>File Laporan:</b></p>
                                    <?php if($bimbingan->lampiran): ?>
                                        <a href="<?php echo e(\App\Helpers\StorageHelper::kpFileUrl($bimbingan->lampiran)); ?>" target="_blank">
                                            <i class="fas fa-file-pdf mr-1"></i> <?php echo e(basename($bimbingan->lampiran)); ?>

                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><b>Lembar Bimbingan:</b></p>
                                    <?php if($ajuan->foto_lembar_bimbingan && $ajuan->foto_lembar_bimbingan != '0'): ?>
                                        <a href="<?php echo e(\App\Helpers\StorageHelper::kpFileUrl($ajuan->foto_lembar_bimbingan)); ?>" target="_blank">
                                            <i class="fas fa-file-alt mr-1"></i> <?php echo e(basename($ajuan->foto_lembar_bimbingan)); ?>

                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Tanggal -->
                            <div class="mt-3 text-secondary">
                                <i class="fas fa-calendar mr-2"></i> Tanggal Bimbingan: <?php echo e(\Carbon\Carbon::parse($ajuan->tanggal_bimbingan)->format('d M Y')); ?>

                            </div>
                            <div class="text-secondary">
                                <i class="fas fa-clock mr-2"></i> Tanggal Submit: <?php echo e($ajuan->created_at->format('d M Y H:i')); ?>

                            </div>
                            <?php if($ajuan->tanggal_review): ?>
                            <div class="text-success">
                                <i class="fas fa-calendar-check mr-2"></i> Tanggal Review: <?php echo e($ajuan->tanggal_review->format('d M Y H:i')); ?>

                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Footer dengan Tombol Aksi -->
                        <?php if($ajuan->status == 'pending'): ?>
                        <div class="card-footer">
                            <div class="d-flex">
                                <button type="button" class="btn btn-success mr-2" data-toggle="modal" data-target="#modal-acc">
                                    <i class="fas fa-check mr-2"></i> ACC Pengajuan
                                </button>
                                <button type="button" class="btn btn-danger mr-2" data-toggle="modal" data-target="#modal-tolak">
                                    <i class="fas fa-times mr-2"></i> Tolak Pengajuan
                                </button>
                            </div>
                        </div>
                        <?php elseif($ajuan->status == 'acc'): ?>
                        <div class="card-footer">
                            <form action="<?php echo e(route('kp.bimbingan-manual.cancel-acc')); ?>" method="post" class="d-inline"
                                  onsubmit="return confirm('Yakin ingin membatalkan ACC? Status akan kembali ke pending.')">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="id" value="<?php echo e($ajuan->id); ?>">
                                <input type="hidden" name="redirect_url" value="<?php echo e(url()->current()); ?>">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-undo mr-2"></i> Batalkan ACC
                                </button>
                            </form>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Card Riwayat -->
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <strong>Riwayat Pengajuan</strong>
                                <span class="badge bg-danger rounded-pill"><?php echo e(count($history)); ?></span>
                            </h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table id="tableRiwayatManualAdmin" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tgl. Bimbingan</th>
                                        <th>Status</th>
                                        <th>Catatan Pembimbing</th>
                                        <th>File</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="<?php echo e($item->id == $ajuan->id ? 'bg-light' : ''); ?>">
                                        <td><?php echo e($index + 1); ?></td>
                                        <td><?php echo e(\Carbon\Carbon::parse($item->tanggal_bimbingan)->format('d M Y')); ?></td>
                                        <td>
                                            <span class="badge <?php echo e($item->getStatusBadgeClass()); ?>">
                                                <?php echo e($item->getStatusLabel()); ?>

                                            </span>
                                        </td>
                                        <td><?php echo e($item->keterangan ? Str::limit($item->keterangan, 40) : '-'); ?></td>
                                        <td>
                                            <?php if($item->bimbingan && $item->bimbingan->lampiran): ?>
                                                <a href="<?php echo e(\App\Helpers\StorageHelper::kpFileUrl($item->bimbingan->lampiran)); ?>"
                                                   target="_blank" class="btn btn-xs btn-info" title="Laporan">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if($item->foto_lembar_bimbingan && $item->foto_lembar_bimbingan != '0'): ?>
                                                <a href="<?php echo e(\App\Helpers\StorageHelper::kpFileUrl($item->foto_lembar_bimbingan)); ?>"
                                                   target="_blank" class="btn btn-xs btn-success ml-1" title="Lembar">
                                                    <i class="fas fa-file-alt"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($item->id != $ajuan->id): ?>
                                            <a href="<?php echo e(route('kp.bimbingan-manual.review.detail', $item->id)); ?>"
                                               class="btn btn-xs btn-secondary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php else: ?>
                                            <span class="badge badge-primary">Saat ini</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal ACC -->
    <div class="modal fade" id="modal-acc">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?php echo e(route('kp.bimbingan-manual.acc')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id" value="<?php echo e($ajuan->id); ?>">
                    <input type="hidden" name="redirect_url" value="<?php echo e(url()->current()); ?>">
                    <div class="modal-header">
                        <h4 class="modal-title">ACC Pengajuan</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tanggal ACC <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_acc" class="form-control" value="<?php echo e(date('Y-m-d')); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Status Bimbingan <span class="text-danger">*</span></label>
                            <select name="status_mahasiswa" class="form-control" required>
                                <option value="acc" <?php echo e($ajuan->status_mahasiswa == 'acc' ? 'selected' : ''); ?>>ACC - Dosen menyetujui</option>
                                <option value="revisi" <?php echo e($ajuan->status_mahasiswa == 'revisi' ? 'selected' : ''); ?>>Revisi - Masih ada perbaikan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Catatan Pembimbing <span class="text-danger">*</span></label>
                            <textarea name="keterangan" class="form-control" rows="3" placeholder="Masukkan catatan pembimbing..." required><?php echo e($ajuan->keterangan); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Catatan Reviewer <span class="text-danger">*</span></label>
                            <textarea name="catatan" class="form-control" rows="3" placeholder="Catatan tambahan..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check mr-2"></i> Setujui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Tolak -->
    <div class="modal fade" id="modal-tolak">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?php echo e(route('kp.bimbingan-manual.revisi')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id" value="<?php echo e($ajuan->id); ?>">
                    <input type="hidden" name="redirect_url" value="<?php echo e(url()->current()); ?>">
                    <div class="modal-header">
                        <h4 class="modal-title">Tolak Pengajuan</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Status Bimbingan <span class="text-danger">*</span></label>
                            <select name="status_mahasiswa" class="form-control" required>
                                <option value="acc" <?php echo e($ajuan->status_mahasiswa == 'acc' ? 'selected' : ''); ?>>ACC - Dosen menyetujui</option>
                                <option value="revisi" <?php echo e($ajuan->status_mahasiswa == 'revisi' ? 'selected' : ''); ?>>Revisi - Masih ada perbaikan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Catatan Pembimbing <span class="text-danger">*</span></label>
                            <textarea name="keterangan" class="form-control" rows="4" placeholder="Masukkan catatan pembimbing..." required><?php echo e($ajuan->keterangan); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea name="catatan" class="form-control" rows="4" placeholder="Alasan penolakan..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times mr-2"></i> Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    if ($('#tableRiwayatManualAdmin').length && !$.fn.DataTable.isDataTable('#tableRiwayatManualAdmin')) {
        $('#tableRiwayatManualAdmin').DataTable({
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

<?php echo $__env->make('kp.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/admin/bimbingan/detail-bimbingan-manual.blade.php ENDPATH**/ ?>