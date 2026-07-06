

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
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Daftar Mahasiswa Bimbingan</h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> 
                                <strong>Informasi Penilaian:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Mahasiswa dapat dinilai setelah semua bimbingan selesai (ACC).</li>
                                </ul>
                            </div>
                            <table id="example1" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIM/Nama</th>
                                        <th>Judul KP</th>
                                        <th>Nilai Pembimbing</th>
                                        <th>Status Seminar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    <?php $__currentLoopData = $mahasiswas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mhs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $is_karyawan = \App\Helpers\AppHelper::isKaryawanKP($mhs);
                                        ?>
                                        <tr>
                                            <td><?php echo e($no++); ?></td>
                                            <td>
                                                <?php echo e($mhs->nim); ?> / <?php echo e($mhs->nama); ?>

                                                <?php if($is_karyawan): ?>
                                                    <br><small class="badge badge-info">Kelas Karyawan</small>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($mhs->pengajuansKP->first()->judul ?? '-'); ?></td>
                                            <td>
                                                <?php if($mhs->jilidKP && $mhs->jilidKP->nilai_pembimbing): ?>
                                                    <span class="badge bg-success"><?php echo e($mhs->jilidKP->nilai_pembimbing); ?></span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning">Belum dinilai</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($is_karyawan): ?>
                                                    <span class="badge bg-info">
                                                        <i class="fas fa-info-circle"></i> Tidak Perlu Seminar
                                                    </span>
                                                <?php elseif($mhs->seminarKP && $mhs->seminarKP->is_lulus): ?>
                                                    <span class="badge bg-success">Lulus Seminar</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Belum Seminar</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm shadow" 
                                                        data-toggle="modal" 
                                                        data-target="#modalNilai<?php echo e($mhs->id); ?>">
                                                    <i class="fas fa-edit mr-1"></i> 
                                                    <?php echo e($mhs->jilidKP && $mhs->jilidKP->nilai_pembimbing ? 'Edit Nilai' : 'Beri Nilai'); ?>

                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Modal Penilaian -->
                                        <div class="modal fade" id="modalNilai<?php echo e($mhs->id); ?>" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary">
                                                        <h5 class="modal-title text-white">
                                                            <i class="fas fa-star"></i> Penilaian Pembimbing
                                                        </h5>
                                                        <button type="button" class="close text-white" data-dismiss="modal">
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="<?php echo e(route('kp.penilaian.pembimbing.store')); ?>" method="POST" enctype="multipart/form-data">
                                                        <?php echo csrf_field(); ?>
                                                        <input type="hidden" name="mahasiswa_id" value="<?php echo e($mhs->id); ?>">
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <strong>Mahasiswa:</strong> <?php echo e($mhs->nim); ?> / <?php echo e($mhs->nama); ?><br>
                                                                <strong>Judul KP:</strong> <?php echo e($mhs->pengajuansKP->first()->judul ?? '-'); ?>

                                                            </div>
                                                            <hr>
                                                            <div class="form-group">
                                                                <label for="nilai_pembimbing">Nilai Pembimbing <span class="text-danger">*</span></label>
                                                                <input type="number" 
                                                                       name="nilai_pembimbing" 
                                                                       class="form-control"
                                                                       value="<?php echo e($mhs->jilidKP->nilai_pembimbing ?? ''); ?>"
                                                                       min="0" 
                                                                       max="100" 
                                                                       step="0.01"
                                                                       placeholder="Masukkan nilai (0-100)"
                                                                       required>
                                                                <small class="text-muted">Nilai dalam skala 0-100</small>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="catatan">Catatan Akhir Pembimbing</label>
                                                                <textarea name="catatan" 
                                                                          class="form-control"
                                                                          rows="3"
                                                                          placeholder="Catatan akhir (opsional)"><?php echo e($mhs->jilidKP->catatan ?? ''); ?></textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="dokumen_penilaian">Dokumen Penilaian (Opsional)</label>
                                                                <div class="custom-file">
                                                                    <input type="file" 
                                                                           class="custom-file-input" 
                                                                           name="dokumen_penilaian"
                                                                           accept=".pdf">
                                                                    <label class="custom-file-label">Pilih file PDF</label>
                                                                </div>
                                                                <small class="text-muted">Upload dokumen pendukung penilaian jika ada</small>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary">
                                                                <i class="fas fa-save"></i> Simpan Nilai
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>





<?php echo $__env->make('kp.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/dosen/penilaian/index.blade.php ENDPATH**/ ?>