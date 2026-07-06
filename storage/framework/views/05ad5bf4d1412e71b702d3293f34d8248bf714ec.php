<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo e($title); ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('kp.bimbingan.mahasiswa')); ?>">Bimbingan KP</a></li>
                        <li class="breadcrumb-item active"><?php echo e($title); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header d-flex">
                            <h3 class="card-title flex-grow-1"><?php echo e($title); ?></h3>
                            <?php if($dosen): ?>
                            <h3 class="card-title flex-shrink-0"><?php echo e($dosen->nama . ', ' . $dosen->gelar); ?></h3>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            
                            <?php if($bimbingan->status != 'diterima'): ?>
                                <form action="<?php echo e(route('kp.bimbingan-manual.store')); ?>" method="post" enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="bimbingan_id" value="<?php echo e($bimbingan->id); ?>">

                                    <div class="form-group">
                                        <label>Bagian Bimbingan</label>
                                        <input type="text" class="form-control" value="<?php echo e($bimbingan->bagian->bagian ?? '-'); ?>" disabled>
                                    </div>

                                    <div class="form-group">
                                        <label>File Laporan</label>
                                        <div>
                                            <?php if($bimbingan->lampiran): ?>
                                                <a href="<?php echo e(\App\Helpers\StorageHelper::kpFileUrl($bimbingan->lampiran)); ?>" target="_blank" class="text-primary">
                                                    <i class="fas fa-paperclip mr-1"></i><?php echo e(basename($bimbingan->lampiran)); ?>

                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="foto_lembar_bimbingan">Lembar Bimbingan Manual (Format: PDF, PNG, JPG, JPEG | Max: 5MB)</label>
                                        <div class="input-group mb-3">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input <?php $__errorArgs = ['foto_lembar_bimbingan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                    name="foto_lembar_bimbingan" accept=".pdf,.jpeg,.png,.jpg" required>
                                                <label class="custom-file-label" for="foto_lembar_bimbingan">Pilih file</label>
                                            </div>
                                            <div class="input-group-append">
                                                <span class="input-group-text">Dokumen</span>
                                            </div>
                                        </div>
                                        <?php $__errorArgs = ['foto_lembar_bimbingan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <small class="text-danger" style="position:relative;top:-15px;left:5px"><?php echo e($message); ?></small>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <div class="form-group">
                                        <label>Tanggal Bimbingan Offline</label>
                                        <input type="date" name="tanggal_bimbingan" class="form-control <?php $__errorArgs = ['tanggal_bimbingan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            value="<?php echo e(old('tanggal_bimbingan', date('Y-m-d'))); ?>" required>
                                        <?php $__errorArgs = ['tanggal_bimbingan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <small class="text-danger"><?php echo e($message); ?></small>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <div class="form-group">
                                        <label>Status Hasil Bimbingan Offline</label>
                                        <select name="status_mahasiswa" class="form-control <?php $__errorArgs = ['status_mahasiswa'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                            <option value="acc" <?php echo e(old('status_mahasiswa') == 'acc' ? 'selected' : ''); ?>>ACC - Dosen menyetujui</option>
                                            <option value="revisi" <?php echo e(old('status_mahasiswa') == 'revisi' ? 'selected' : ''); ?>>Revisi - Masih ada perbaikan</option>
                                        </select>
                                        <?php $__errorArgs = ['status_mahasiswa'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <small class="text-danger"><?php echo e($message); ?></small>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <div class="form-group">
                                        <label>Catatan <span class="text-danger">*</span></label>
                                        <textarea name="keterangan" class="form-control <?php $__errorArgs = ['keterangan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="3" placeholder="Catatan hasil bimbingan offline dengan dosen..." required><?php echo e(old('keterangan')); ?></textarea>
                                        <?php $__errorArgs = ['keterangan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <small class="text-danger"><?php echo e($message); ?></small>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <div class="form-group mt-4">
                                        <a href="<?php echo e(route('kp.bimbingan.mahasiswa')); ?>" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> Kembali
                                        </a>
                                        <button type="submit" class="btn btn-success">Submit</button>
                                    </div>
                                </form>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Bimbingan <?php echo e($bimbingan->bagian->bagian ?? ''); ?> sudah di-ACC. Silahkan lanjutkan ke bab berikutnya.
                                </div>

                                <a href="<?php echo e(route('kp.bimbingan.mahasiswa')); ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    
                    <?php if($history->count() > 0): ?>
                    <div class="card card-secondary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Riwayat Pengajuan Bimbingan Manual</h3>
                        </div>
                        <div class="card-body table-responsive">
                            <table id="tableRiwayatManual" class="table table-bordered table-striped text-center">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Status Bimbingan</th>
                                        <th>Tanggal Bimbingan</th>
                                        <th>Tanggal Ajuan</th>
                                        <th>Status Ajuan</th>
                                        <th width="100">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($index + 1); ?></td>
                                        <td>
                                            <?php if($item->status_mahasiswa == 'acc'): ?>
                                                <span class="badge badge-success">ACC</span>
                                            <?php else: ?>
                                                <span class="badge badge-warning">Revisi</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e(\Carbon\Carbon::parse($item->tanggal_bimbingan)->format('d M Y')); ?></td>
                                        <td><?php echo e($item->created_at->format('d M Y H:i')); ?></td>
                                        <td>
                                            <span class="badge <?php echo e($item->getStatusBadgeClass()); ?>">
                                                <?php echo e($item->getStatusLabel()); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm shadow" data-toggle="modal" data-target="#modalDetail-<?php echo e($item->id); ?>">
                                                <i class="fas fa-info-circle mr-1"></i> Detail
                                            </button>

                                            <!-- Modal Detail Bimbingan Manual -->
                                            <div class="modal fade" id="modalDetail-<?php echo e($item->id); ?>" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel-<?php echo e($item->id); ?>" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content text-left">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title" id="modalDetailLabel-<?php echo e($item->id); ?>">
                                                                <i class="fas fa-file-alt mr-2 text-primary"></i> Detail Pengajuan Bimbingan Manual
                                                            </h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <table class="table table-sm table-borderless mb-0">
                                                                        <tr>
                                                                            <td width="50%"><strong>Status Hasil Dosen</strong></td>
                                                                            <td>: 
                                                                                <?php if($item->status_mahasiswa == 'acc'): ?>
                                                                                    <span class="badge badge-success">ACC</span>
                                                                                <?php else: ?>
                                                                                    <span class="badge badge-warning">Revisi</span>
                                                                                <?php endif; ?>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><strong>Status Verifikasi</strong></td>
                                                                            <td>: 
                                                                                <span class="badge <?php echo e($item->getStatusBadgeClass()); ?>">
                                                                                    <?php echo e($item->getStatusLabel()); ?>

                                                                                </span>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <table class="table table-sm table-borderless mb-0">
                                                                        <tr>
                                                                            <td width="50%"><strong>Tanggal Bimbingan</strong></td>
                                                                            <td>: <?php echo e(\Carbon\Carbon::parse($item->tanggal_bimbingan)->format('d F Y')); ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><strong>Tanggal Ajuan</strong></td>
                                                                            <td>: <?php echo e($item->created_at->format('d F Y H:i')); ?></td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            
                                                            <hr class="my-3">
                                                            
                                                            <div class="row mb-3">
                                                                <div class="col-md-6 mb-3 mb-md-0">
                                                                    <div class="card bg-light h-100 mb-0">
                                                                        <div class="card-header">
                                                                            <h5 class="card-title mb-0"><i class="fas fa-file-pdf mr-2"></i>File Laporan</h5>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <?php if($item->bimbingan && $item->bimbingan->lampiran): ?>
                                                                                <a href="<?php echo e(\App\Helpers\StorageHelper::kpFileUrl($item->bimbingan->lampiran)); ?>" target="_blank" class="text-primary">
                                                                                    <i class="fas fa-paperclip mr-1"></i><?php echo e(basename($item->bimbingan->lampiran)); ?>

                                                                                </a>
                                                                            <?php else: ?>
                                                                                <span class="text-muted">-</span>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="card bg-light h-100 mb-0">
                                                                        <div class="card-header">
                                                                            <h5 class="card-title mb-0"><i class="fas fa-file-image mr-2 text-success"></i>Lembar Bimbingan Manual</h5>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <?php if($item->foto_lembar_bimbingan && $item->foto_lembar_bimbingan != '0'): ?>
                                                                                <a href="<?php echo e(\App\Helpers\StorageHelper::kpFileUrl($item->foto_lembar_bimbingan)); ?>" target="_blank" class="text-success">
                                                                                    <i class="fas fa-paperclip mr-1"></i><?php echo e(basename($item->foto_lembar_bimbingan)); ?>

                                                                                </a>
                                                                            <?php else: ?>
                                                                                <span class="text-muted">Tidak ada file</span>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="mb-3">
                                                                <div class="card bg-light mb-0">
                                                                    <div class="card-header">
                                                                        <h5 class="card-title mb-0"><i class="fas fa-comment mr-2 text-primary"></i>Catatan Hasil Bimbingan (Mahasiswa)</h5>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <p class="mb-0" style="white-space: pre-line;"><?php echo e($item->keterangan ?? '-'); ?></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div>
                                                                <div class="card bg-light mb-0">
                                                                    <div class="card-header">
                                                                        <h5 class="card-title mb-0"><i class="fas fa-comment-dots mr-2 text-secondary"></i>Catatan Verifikasi (Admin/Prodi)</h5>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <p class="mb-0" style="white-space: pre-line;"><?php echo e($item->catatan_reviewer ?? 'Belum ada catatan verifikasi'); ?></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var fileName = e.target.files[0].name;
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });

    // DataTable untuk Riwayat Pengajuan Bimbingan Manual
    $(document).ready(function() {
        if ($('#tableRiwayatManual').length && !$.fn.DataTable.isDataTable('#tableRiwayatManual')) {
            $('#tableRiwayatManual').DataTable({
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

<?php echo $__env->make('kp.layouts.dashboardMahasiswa', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/mahasiswa/bimbingan/bimbingan-manual.blade.php ENDPATH**/ ?>