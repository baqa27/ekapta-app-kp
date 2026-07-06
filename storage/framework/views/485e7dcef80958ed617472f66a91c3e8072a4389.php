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
            <div class="mb-3 d-flex">
                <div class="flex-shrink-1">
                    <a href="<?php echo e(route($is_admin ? 'jilid.index' : (isset($is_prodi) && $is_prodi ? 'jilid.prodi.index' : 'jilid.mahasiswa'))); ?>"
                        class="btn btn-secondary float-end"><i class="bi bi-arrow-left ml-2"></i> Kembali</a>
                </div>
                <h4 class="flex-grow-0"></h4>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo e($title); ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="p-3 rounded border mb-4">
                                <table>
                                    <tr>
                                        <td>NIM/NAMA MAHASISWA</td>
                                        <td>: <b><?php echo e($mahasiswa->nim . '/' . $mahasiswa->nama); ?></b></td>
                                    </tr>
                                    <tr>
                                        <td>PRODI</td>
                                        <td>: <b><?php echo e($prodi ? $prodi->namaprodi : ''); ?></b></td>
                                    </tr>
                                    <tr>
                                        <td>JUDUL TUGAS AKHIR</td>
                                        <td>:
                                            <b><?php echo e($jilid->mahasiswa->pengajuans()->where('status', 'diterima')->first()->judul); ?></b>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <?php if($is_admin): ?>
                                <?php if(Auth::guard('admin')->user()->type == \App\Models\Admin::TYPE_SUPER_ADMIN): ?>
                                    
                                    <h5 class="mt-3"><strong>Dokumen Pengumpulan Akhir TA</strong></h5>
                                    <hr>
                                    <div class="mt-3">
                                        <?php if($jilid->lembar_pengesahan): ?>
                                        <a href="<?php echo e(storage_url($jilid->lembar_pengesahan)); ?>" class="btn btn-primary mb-3"
                                            target="_blank"><i class="fas fa-download"></i> LEMBAR PENGESAHAN</a>
                                        <?php endif; ?>
                                        <?php if($jilid->lembar_keaslian): ?>
                                        <a href="<?php echo e(storage_url($jilid->lembar_keaslian)); ?>" class="btn btn-primary mb-3"
                                            target="_blank"><i class="fas fa-download"></i> LEMBAR KEASLIAN</a>
                                        <?php endif; ?>
                                        <?php if($jilid->lembar_persetujuan_pembimbing): ?>
                                        <a href="<?php echo e(storage_url($jilid->lembar_persetujuan_pembimbing)); ?>"
                                            class="btn btn-primary mb-3" target="_blank"><i class="fas fa-download"></i>
                                            LEMBAR PERSETUJUAN PEMBIMBING</a>
                                        <?php endif; ?>
                                        <?php if($jilid->lembar_persetujuan_penguji): ?>
                                        <a href="<?php echo e(storage_url($jilid->lembar_persetujuan_penguji)); ?>"
                                            class="btn btn-primary mb-3" target="_blank"><i class="fas fa-download"></i>
                                            LEMBAR PERSETUJUAN PENGUJI</a>
                                        <?php endif; ?>
                                        <?php if($jilid->lembar_bimbingan): ?>
                                        <a href="<?php echo e(storage_url($jilid->lembar_bimbingan)); ?>" class="btn btn-primary mb-3"
                                            target="_blank"><i class="fas fa-download"></i> LEMBAR BIMBINGAN</a>
                                        <?php endif; ?>
                                        <?php if($jilid->lembar_revisi): ?>
                                        <a href="<?php echo e(storage_url($jilid->lembar_revisi)); ?>" class="btn btn-primary mb-3"
                                            target="_blank"><i class="fas fa-download"></i> LEMBAR REVISI</a>
                                        <?php endif; ?>
                                        <?php if($jilid->laporan_pdf): ?>
                                        <a href="<?php echo e(storage_url($jilid->laporan_pdf)); ?>" class="btn btn-primary mb-3"
                                            target="_blank"><i class="fas fa-download"></i> LAPORAN FORMAT PDF</a>
                                        <?php endif; ?>
                                        <?php if($jilid->laporan_word): ?>
                                        <a href="<?php echo e(storage_url($jilid->laporan_word)); ?>" class="btn btn-primary mb-3"
                                            target="_blank"><i class="fas fa-download"></i> LAPORAN FORMAT WORD</a>
                                        <?php endif; ?>
                                        <?php if($jilid->artikel): ?>
                                        <a href="<?php echo e(storage_url($jilid->artikel)); ?>" class="btn btn-primary mb-3"
                                            target="_blank"><i class="fas fa-download"></i> ARTIKEL FORMAT WORD</a>
                                        <?php endif; ?>
                                        <?php if($jilid->berita_acara): ?>
                                            <a href="<?php echo e(storage_url($jilid->berita_acara)); ?>" class="btn btn-primary mb-3"
                                                target="_blank"><i class="fas fa-download"></i> BERITA ACARA</a>
                                        <?php endif; ?>
                                        <?php if($jilid->panduan): ?>
                                            <a href="<?php echo e(storage_url($jilid->panduan)); ?>" class="btn btn-primary mb-3"
                                                target="_blank"><i class="fas fa-download"></i> PANDUAN PENGGUNAAN PRODUK TA</a>
                                        <?php endif; ?>
                                        <?php if($jilid->lampiran): ?>
                                            <a href="<?php echo e(storage_url($jilid->lampiran)); ?>" class="btn btn-primary mb-3"
                                                target="_blank"><i class="fas fa-download"></i> DOKUMEN LAMPIRAN</a>
                                        <?php endif; ?>
                                        <?php if($jilid->link_project): ?>
                                            <a href="<?php echo e($jilid->link_project); ?>" class="btn btn-secondary mb-3"
                                                target="_blank"><i class="fas fa-paper-plane"></i> LINK PROJECT</a>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    
                                    <?php if($jilid->laporan_pdf): ?>
                                        <a href="<?php echo e(storage_url($jilid->laporan_pdf)); ?>" class="btn btn-primary mb-3 mt-4"
                                            target="_blank"><i class="fas fa-download"></i> LAPORAN PDF</a>
                                    <?php endif; ?>
                                <?php endif; ?>

                                
                                <form action="<?php echo e(route('jilid.acc', $jilid->id)); ?>" method="post">
                                    <?php echo method_field('put'); ?>
                                    <?php echo csrf_field(); ?>
                                    <?php if($jilid->status == 3): ?>
                                        <input type="hidden" name="status" value="4">
                                        <div class="mt-4">
                                            <label>JUMLAH PEMBAYARAN</label>
                                            <input type="number" name="total_pembayaran" class="form-control"
                                                placeholder="Nominal pembayaran penjilidan"
                                                value="<?php echo e($jilid->total_pembayaran); ?>" required>
                                        </div>
                                    <?php elseif($jilid->status == 1): ?>
                                        <div class="form-group">
                                            <label for="">Status</label>
                                            <select name="status" class="form-control" required>
                                                <option value="">--pilih--</option>
                                                <option value="3">VALID</option>
                                                <option value="2">TIDAK VALID</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Catatan</label>
                                            <textarea name="catatan" id="summernote" required></textarea>
                                        </div>
                                    <?php endif; ?>
                                    <?php if($jilid->status == 1 || $jilid->status == 3): ?>
                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-success"><i class="fas fa-check"></i>
                                            <?php if($jilid->status == 1): ?>
                                                SIMPAN
                                            <?php elseif($jilid->status == 3): ?>
                                                SELESAI
                                            <?php endif; ?>
                                        </button>
                                    </div>
                                    <?php endif; ?>
                                </form>

                            <?php else: ?>
                                
                                <h5 class="mt-3"><strong>Dokumen Pengumpulan Akhir TA</strong></h5>
                                <hr>

                                <?php if($jilid->lembar_pengesahan): ?>
                                <div class="row mb-2">
                                    <div class="col-md-4">Lembar Pengesahan (TTD)</div>
                                    <div class="col-md-8">
                                        <a href="<?php echo e(storage_url($jilid->lembar_pengesahan)); ?>" target="_blank" class="text-primary">
                                            <i class="fas fa-paperclip"></i> Buka File
                                        </a>
                                    </div>
                                </div>
                                <hr>
                                <?php endif; ?>

                                <?php if($jilid->lembar_keaslian): ?>
                                <div class="row mb-2">
                                    <div class="col-md-4">Lembar Keaslian</div>
                                    <div class="col-md-8">
                                        <a href="<?php echo e(storage_url($jilid->lembar_keaslian)); ?>" target="_blank" class="text-primary">
                                            <i class="fas fa-paperclip"></i> Buka File
                                        </a>
                                    </div>
                                </div>
                                <hr>
                                <?php endif; ?>

                                <?php if($jilid->lembar_persetujuan_pembimbing): ?>
                                <div class="row mb-2">
                                    <div class="col-md-4">Lembar Persetujuan Pembimbing</div>
                                    <div class="col-md-8">
                                        <a href="<?php echo e(storage_url($jilid->lembar_persetujuan_pembimbing)); ?>" target="_blank" class="text-primary">
                                            <i class="fas fa-paperclip"></i> Buka File
                                        </a>
                                    </div>
                                </div>
                                <hr>
                                <?php endif; ?>

                                <?php if($jilid->lembar_persetujuan_penguji): ?>
                                <div class="row mb-2">
                                    <div class="col-md-4">Lembar Persetujuan Penguji</div>
                                    <div class="col-md-8">
                                        <a href="<?php echo e(storage_url($jilid->lembar_persetujuan_penguji)); ?>" target="_blank" class="text-primary">
                                            <i class="fas fa-paperclip"></i> Buka File
                                        </a>
                                    </div>
                                </div>
                                <hr>
                                <?php endif; ?>

                                <?php if($jilid->lembar_bimbingan): ?>
                                <div class="row mb-2">
                                    <div class="col-md-4">Lembar Bimbingan TA</div>
                                    <div class="col-md-8">
                                        <a href="<?php echo e(storage_url($jilid->lembar_bimbingan)); ?>" target="_blank" class="text-primary">
                                            <i class="fas fa-paperclip"></i> Buka File
                                        </a>
                                    </div>
                                </div>
                                <hr>
                                <?php endif; ?>

                                <?php if($jilid->lembar_revisi): ?>
                                <div class="row mb-2">
                                    <div class="col-md-4">Lembar Revisi (ACC Penguji)</div>
                                    <div class="col-md-8">
                                        <a href="<?php echo e(storage_url($jilid->lembar_revisi)); ?>" target="_blank" class="text-primary">
                                            <i class="fas fa-paperclip"></i> Buka File
                                        </a>
                                    </div>
                                </div>
                                <hr>
                                <?php endif; ?>

                                <?php if($jilid->laporan_pdf): ?>
                                <div class="row mb-2">
                                    <div class="col-md-4">Laporan TA (PDF)</div>
                                    <div class="col-md-8">
                                        <?php if(filter_var($jilid->laporan_pdf, FILTER_VALIDATE_URL)): ?>
                                            <a href="<?php echo e($jilid->laporan_pdf); ?>" target="_blank" class="text-primary">
                                                <i class="fas fa-external-link-alt"></i> Buka Link Google Drive
                                            </a>
                                        <?php else: ?>
                                            <a href="<?php echo e(storage_url($jilid->laporan_pdf)); ?>" target="_blank" class="text-primary">
                                                <i class="fas fa-paperclip"></i> Buka File
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <hr>
                                <?php endif; ?>

                                <?php if($jilid->laporan_word): ?>
                                <div class="row mb-2">
                                    <div class="col-md-4">Laporan TA (Word)</div>
                                    <div class="col-md-8">
                                        <?php if(filter_var($jilid->laporan_word, FILTER_VALIDATE_URL)): ?>
                                            <a href="<?php echo e($jilid->laporan_word); ?>" target="_blank" class="text-primary">
                                                <i class="fas fa-external-link-alt"></i> Buka Link Google Drive
                                            </a>
                                        <?php else: ?>
                                            <a href="<?php echo e(storage_url($jilid->laporan_word)); ?>" target="_blank" class="text-primary">
                                                <i class="fas fa-paperclip"></i> Buka File
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <hr>
                                <?php endif; ?>

                                <?php if($jilid->artikel): ?>
                                <div class="row mb-2">
                                    <div class="col-md-4">Artikel (Word)</div>
                                    <div class="col-md-8">
                                        <?php if(filter_var($jilid->artikel, FILTER_VALIDATE_URL)): ?>
                                            <a href="<?php echo e($jilid->artikel); ?>" target="_blank" class="text-primary">
                                                <i class="fas fa-external-link-alt"></i> Buka Link Google Drive
                                            </a>
                                        <?php else: ?>
                                            <a href="<?php echo e(storage_url($jilid->artikel)); ?>" target="_blank" class="text-primary">
                                                <i class="fas fa-paperclip"></i> Buka File
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <hr>
                                <?php endif; ?>

                                <?php if($jilid->berita_acara): ?>
                                <div class="row mb-2">
                                    <div class="col-md-4">Berita Acara</div>
                                    <div class="col-md-8">
                                        <a href="<?php echo e(storage_url($jilid->berita_acara)); ?>" target="_blank" class="text-primary">
                                            <i class="fas fa-paperclip"></i> Buka File
                                        </a>
                                    </div>
                                </div>
                                <hr>
                                <?php endif; ?>

                                <?php if($jilid->panduan): ?>
                                <div class="row mb-2">
                                    <div class="col-md-4">Panduan Penggunaan Produk TA</div>
                                    <div class="col-md-8">
                                        <?php if(filter_var($jilid->panduan, FILTER_VALIDATE_URL)): ?>
                                            <a href="<?php echo e($jilid->panduan); ?>" target="_blank" class="text-primary">
                                                <i class="fas fa-external-link-alt"></i> Buka Link Google Drive
                                            </a>
                                        <?php else: ?>
                                            <a href="<?php echo e(storage_url($jilid->panduan)); ?>" target="_blank" class="text-primary">
                                                <i class="fas fa-paperclip"></i> Buka File
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <hr>
                                <?php endif; ?>

                                <?php if($jilid->lampiran): ?>
                                <div class="row mb-2">
                                    <div class="col-md-4">Dokumen Lampiran</div>
                                    <div class="col-md-8">
                                        <a href="<?php echo e(storage_url($jilid->lampiran)); ?>" target="_blank" class="text-primary">
                                            <i class="fas fa-paperclip"></i> Buka File
                                        </a>
                                    </div>
                                </div>
                                <hr>
                                <?php endif; ?>

                                <?php if($jilid->link_project): ?>
                                <div class="row mb-2">
                                    <div class="col-md-4">Link Project TA</div>
                                    <div class="col-md-8">
                                        <a href="<?php echo e($jilid->link_project); ?>" target="_blank" class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-external-link-alt"></i> Buka Link
                                        </a>
                                    </div>
                                </div>
                                <?php endif; ?>

                            <?php endif; ?>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    
                    
                    <div class="card card-outline card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <b>Revisi</b>
                                <span class="badge bg-danger rounded-pill">
                                    <?php echo e(count($revisis)); ?>

                                </span>
                            </h3>

                            <div class="card-tools">
                                <?php echo e($revisis->links()); ?>

                            </div>
                        </div>

                        <div class="card-body">
                            <div class="p-2">

                                <?php $__currentLoopData = $revisis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $revisi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="direct-chat-msg">
                                        <div class="direct-chat-infos clearfix">
                                            <span
                                                class="direct-chat-name float-left">Admin Ekapta</span>
                                            <span class="direct-chat-timestamp float-right">
                                                <?php echo e($revisi->created_at->format('d M Y H:m a')); ?>

                                            </span>
                                        </div>
                                        <img class="direct-chat-img"
                                            src="<?php echo e(asset('ekapta/adminLTE/dist/img/default-profile.png')); ?>"
                                            alt="message user image">
                                        <div class="direct-chat-text p-2">
                                            <?php echo nl2br($revisi->catatan); ?>

                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make($is_admin ? (Auth::guard('admin')->user()->type == \App\Models\Admin::TYPE_SUPER_ADMIN ? 'layouts.dashboard' : 'layouts.dashboardFotokopi') : (isset($is_prodi) && $is_prodi ? 'layouts.dashboard' : 'layouts.dashboardMahasiswa'), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/pages/fotokopi/detail.blade.php ENDPATH**/ ?>