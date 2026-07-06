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
                        <li class="breadcrumb-item"><a href="#">Ujian Pendadaran TA</a></li>
                        <li class="breadcrumb-item active"><?php echo e($title); ?></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container">
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="ribbon-wrapper ribbon-lg">
                            <div
                                class="ribbon
                            <?php if($ujian->is_valid == \App\Models\Ujian::REVIEW): ?> bg-secondary
                            <?php elseif($ujian->is_valid == \App\Models\Ujian::NOT_VALID_LULUS): ?>
                            bg-warning
                            <?php elseif($ujian->is_valid == \App\Models\Ujian::VALID_LULUS): ?>
                            bg-success <?php endif; ?>
                            ">
                                <?php if($ujian->is_valid == \App\Models\Ujian::REVIEW): ?>
                                    TIDAK VALID
                                <?php elseif($ujian->is_valid == \App\Models\Ujian::VALID_LULUS): ?>
                                    VALID
                                <?php elseif($ujian->is_valid == \App\Models\Ujian::NOT_VALID_LULUS): ?>
                                    REVISI
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5">
                                    NIM
                                </div>
                                <div class="col-md-7">
                                    <b><?php echo e($ujian->mahasiswa->nim); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Nama Lengkap
                                </div>
                                <div class="col-md-7">
                                    <b><?php echo e($ujian->mahasiswa->nama); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Prodi
                                </div>
                                <div class="col-md-7">
                                    <b><?php echo e($ujian->mahasiswa->prodi); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Judul TA
                                </div>
                                <div class="col-md-7">
                                    <b><?php echo e($ujian->pengajuan->judul); ?></b>
                                </div>
                            </div>
                            <hr>

                            <?php if($ujian->lampiran_proposal): ?>
                                <div class="row">
                                    <div class="col-md-5">
                                        Laporan Tugas Akhir
                                    </div>
                                    <div class="col-md-7">
                                        <b><a href="<?php echo e(storage_url($ujian->lampiran_proposal)); ?>" target="_blank"><i
                                                    class="fas fa-download"></i>
                                                <?php echo e(Str::substr($ujian->lampiran_proposal, 40)); ?></a>
                                        </b>
                                    </div>
                                </div>
                                <hr>
                            <?php endif; ?>
                            <?php if($ujian->artikel): ?>
                                <div class="row">
                                    <div class="col-md-5">
                                        File Artikel
                                    </div>
                                    <div class="col-md-7">
                                        <b><a href="<?php echo e(storage_url($ujian->artikel)); ?>" target="_blank"><i
                                                    class="fas fa-download"></i>
                                                <?php echo e(Str::substr($ujian->artikel, 40)); ?></a>
                                        </b>
                                    </div>
                                </div>
                                <hr>
                            <?php endif; ?>
                            <?php if($ujian->link_artikel): ?>
                                <div class="row">
                                    <div class="col-md-5">
                                        Link Artikel
                                    </div>
                                    <div class="col-md-7">
                                        <b><a href="<?php echo e($ujian->link_artikel); ?>" target="_blank"
                                                rel="noopener noreferrer"><i
                                                    class="fas fa-link"></i>
                                                <?php echo e($ujian->link_artikel); ?></a>
                                        </b>
                                    </div>
                                </div>
                                <hr>
                            <?php endif; ?>
                            <div class="row">
                                <div class="col-md-5">
                                    Tanggal Ujian
                                </div>
                                <div class="col-md-7">
                                    <b
                                        class="text-danger"><?php echo e(\App\Helpers\AppHelper::parse_date_short($ujian->tanggal_ujian)); ?></b>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-5">
                                    Tempat Ujian
                                </div>
                                <div class="col-md-7">
                                    <b class="text-danger"><?php echo e($ujian->tempat_ujian); ?></b>
                                </div>
                            </div>

                        </div>

                        <?php if($ujian->is_valid == 1): ?>
                            <div class="card-footer">
                                <div class="d-flex">
                                    <a href="<?php echo e(route('cetak.berita.acara.ujian.pendadaran', $ujian->id)); ?>"
                                        class="btn btn-success" target="_blank">
                                        <i class="bi bi-download"></i> Berita Acara Ujian Pendadaran
                                    </a>
                                    &nbsp;
                                    <a href="<?php echo e(route('cetak.berita.acara.ujian.proposal.blank', [$ujian->id, 2])); ?>"
                                        class="btn btn-secondary" target="_blank">
                                        <i class="bi bi-download"></i> Berita Acara Ujian Pendadaran Kosong
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    
                    <div class="card card-primary card-outline mt-2">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Revisi</strong>
                                <span class="badge bg-danger rounded-pill">
                                    <?php echo e(count($ujian->revisis)); ?>

                                </span>
                            </h3>
                        </div>

                        <div class="card-body">

                            <?php $__currentLoopData = $revisis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $revisi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <i class="fas fa-calendar mr-2"></i>
                                        <?php echo e($revisi->created_at->format('d M Y H:m')); ?>

                                        <div class="float-right" onclick="confirmDelete()">
                                            <form action="<?php echo e(route('ujian.revisi.delete')); ?>" method="post">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="id" value="<?php echo e($revisi->id); ?>">
                                                <button class="btn btn-danger btn-sm float-right" type="submit">
                                                    <i class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <?php echo nl2br($revisi->catatan); ?>

                                    </div>

                                    <?php if($revisi->lampiran): ?>
                                        <div class="card-footer">
                                            Lampiran :
                                            <?php if($revisi->lampiran): ?>
                                                <a href="<?php echo e(storage_url($revisi->lampiran)); ?>" class="ml-3" target="_blank"><i
                                                        class="fas fa-paperclip"></i>
                                                    <?php echo e(Str::substr($revisi->lampiran, 40)); ?></a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </div>
                        <div class="d-flex justify-content-center mb-3">
                            <?php echo e($revisis->links()); ?>

                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>

            <div class="row mb-3">
                <?php
                    $no = 1;
                ?>
                <?php $__currentLoopData = $ujian->reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($review->dosen_status == 'penguji'): ?>
                        <div class="col-md-4">
                            <div class="card card-primary card-outline">
                                <div class="ribbon-wrapper ribbon-lg">
                                    <div
                                        class="ribbon
                                <?php if($review->status == 'diterima'): ?> bg-success
                                <?php elseif($review->status == 'revisi'): ?>
                                bg-warning
                                <?php elseif($review->status == 'review'): ?>
                                bg-secondary
                                <?php else: ?>
                                bg-danger <?php endif; ?>
                                ">
                                        <?php if($review->status == 'diterima'): ?>
                                            Diterima
                                        <?php elseif($review->status == 'revisi'): ?>
                                            Revisi
                                        <?php elseif($review->status == 'review'): ?>
                                            Review
                                        <?php else: ?>
                                            Belum Submit
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="card-body">
                                    Dosen Penguji <?php echo e($no++); ?>: <br>
                                    <b><?php echo e($review->dosen->nama); ?>, <?php echo e($review->dosen->gelar); ?></b>
                                    <p>
                                        Lampiran Proposal:
                                        <a href="<?php echo e(storage_url($review->lampiran ? $review->lampiran : $review->ujian->lampiran_3)); ?>"
                                            class="ml-3 text-primary" target="_blank"><i class="fas fa-paperclip mr-2"></i>
                                            <?php echo e(Str::substr($review->lampiran ? $review->lampiran : $review->ujian->lampiran_3, 40)); ?></a>
                                    </p>
                                    <?php if($review->status == 'diterima'): ?>
                                        <p>
                                            Tanggal Acc:
                                            <b
                                                class="text-success"><?php echo e(\App\Helpers\AppHelper::parse_date_short($review->tanggal_acc)); ?></b>
                                        </p>
                                    <?php endif; ?>
                                    <?php if($review->tanggal_acc_manual && $review->lampiran_lembar_revisi && $review->status == 'review'): ?>
                                        <p>
                                            Tanggal Acc Manual:
                                            <b><?php echo e(\App\Helpers\AppHelper::parse_date_short($review->tanggal_acc_manual)); ?></b>
                                        </p>
                                        <p>
                                            Lampiran Lembar Revisi:
                                            <a href="<?php echo e(storage_url($review->lampiran_lembar_revisi)); ?>"
                                                class="ml-3 text-primary" target="_blank"><i
                                                    class="fas fa-paperclip mr-2"></i>
                                                <?php echo e(Str::substr($review->lampiran_lembar_revisi, 40)); ?></a>
                                        </p>
                                        <button type="button" class="btn btn-success col-12" data-toggle="modal"
                                            data-target="#modal-acc">
                                            <i class="fas fa-check"></i> Acc bimbingan
                                        </button>

                                        <div class="modal fade" id="modal-acc">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="<?php echo e(route('review.ujian.acc.prodi')); ?>" method="post">
                                                        <?php echo csrf_field(); ?>
                                                        <input type="hidden" name="id"
                                                            value="<?php echo e($review->id); ?>">
                                                        <input type="hidden" name="type" value="input_manual">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Acc Bimbingan</h4>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="" class="form-label">Catatan</label>
                                                                <textarea id="summernote" name="catatan" required></textarea>
                                                                <?php $__errorArgs = ['catatan'];
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
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="submit" class="btn btn-success">Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                    <?php endif; ?>
                                </div>

                            </div>

                        </div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="card card-primary card-outline col-md-12 mb-3">
                <div class="card-header">
                    Nilai ujian TA
                </div>
                <div class="card-body table-responsive">
                    <div class="form-group mb-4">
                        <label for="exampleInputFile">Status</label>
                        <select class="form-control" name="is_lulus" id="is_lulus">
                            <option value="">-- pilih --</option>
                            <option value="<?php echo e(App\Models\Ujian::VALID_LULUS); ?>"
                                <?php echo e($review->ujian->is_lulus == App\Models\Ujian::VALID_LULUS ? 'selected' : ''); ?>>Lulus
                            </option>
                            <option value="<?php echo e(App\Models\Ujian::NOT_VALID_LULUS); ?>"
                                <?php echo e($review->ujian->is_lulus == App\Models\Ujian::NOT_VALID_LULUS ? 'selected' : ''); ?>>Tidak
                                Lulus
                            </option>
                        </select>
                        <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Dosen</th>
                                <th>Status</th>
                                <th>Substansi / Isi Materi</th>
                                <th>Kompetensi Ilmu</th>
                                <th>Metodologi dan Redaksi TA</th>
                                <th>Presentasi</th>
                                <th>Rata-Rata</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $__currentLoopData = $ujian->reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($review->dosen->nama . ', ' . $review->dosen->gelar); ?></td>
                                    <td><?php echo e($review->dosen_status == \App\Models\Reviewujian::DOSEN_PENGUJI ? 'Penguji' : 'Pembimbing'); ?>

                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="nilai_1"
                                            data-review-id="<?php echo e($review->id); ?>" value="<?php echo e($review->nilai_1); ?>">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="nilai_2"
                                            data-review-id="<?php echo e($review->id); ?>" value="<?php echo e($review->nilai_2); ?>">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="nilai_3"
                                            data-review-id="<?php echo e($review->id); ?>" value="<?php echo e($review->nilai_3); ?>">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="nilai_4"
                                            data-review-id="<?php echo e($review->id); ?>" value="<?php echo e($review->nilai_4); ?>">
                                    </td>
                                    <td><?php echo e(round(\App\Helpers\AppHelper::instance()->hitung_nilai_mean($review->nilai_1, $review->nilai_2, $review->nilai_3, $review->nilai_4), 2)); ?>

                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>

                        <tfoot>
                            <tr bgcolor="#a9a9a9" class="text-white">
                                <th colspan="6">RATA - RATA NILAI DOSEN PEMBIMBING</th>
                                <th><?php echo e($nilai_dosen_pembimbing); ?></th>
                            </tr>
                            <tr bgcolor="#a9a9a9" class="text-white">
                                <th colspan="6">RATA - RATA NILAI DOSEN PENGUJI</th>
                                <th><?php echo e($nilai_dosen_penguji); ?></th>
                            </tr>
                            <tr bgcolor="#808080" class="text-white">
                                <th colspan="6">NILAI AKHIR</th>
                                <th><?php echo e($nilai); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        $(document).ready(function() {
            $('#is_lulus').on('change', function() {
                var is_lulus = $(this).val();
                var ujian_id = <?php echo e($review->ujian->id); ?>;

                $.ajax({
                    url: '/ujian/update-status',
                    method: 'POST',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>',
                        ujian_id: ujian_id,
                        is_lulus: is_lulus
                    },
                    success: function(response) {
                        $(document).Toasts('create', {
                            class: 'bg-success mt-5 mr-3',
                            title: 'Success',
                            autohide: true,
                            delay: 3000,
                            body: response.message
                        })
                    },
                    error: function(xhr, status, error) {
                        $(document).Toasts('create', {
                            class: 'bg-danger mt-5 mr-3',
                            title: 'Error',
                            autohide: true,
                            delay: 3000,
                            body: 'Terjadi kesalahan: '+ error
                        })
                    }
                });
            });
        });

        $(document).ready(function() {
            $('input[type="number"]').on('change', function() {
                var input = $(this);
                var reviewId = input.data('review-id');
                var fieldName = input.attr('name');
                var fieldValue = input.val();

                $.ajax({
                    url: '/review/ujian/update-nilai',
                    method: 'POST',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>',
                        review_id: reviewId,
                        field_name: fieldName,
                        field_value: fieldValue
                    },
                    success: function(response) {
                        // alert('Nilai berhasil diperbarui');
                        $(document).Toasts('create', {
                            class: 'bg-success mt-5 mr-3',
                            title: 'Success',
                            autohide: true,
                            delay: 3000,
                            body: 'Nilai berhasil diperbarui'
                        })
                        input.closest('tr').find('td:last').text(response.nilai_akhir);
                    },
                    error: function(xhr, status, error) {
                        // alert('Terjadi kesalahan: ' + error);
                        $(document).Toasts('create', {
                            class: 'bg-danger mt-5 mr-3',
                            title: 'Error',
                            autohide: true,
                            delay: 3000,
                            body: 'Terjadi kesalahan: '+ error
                        })
                    }
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/pages/prodi/ujian/detail.blade.php ENDPATH**/ ?>