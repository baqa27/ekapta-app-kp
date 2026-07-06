

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
                        <li class="breadcrumb-item"><a href="#">Jilid KP</a></li>
                        <li class="breadcrumb-item active">Revisi</li>
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
                <div class="col-md-12">
                    <?php if(count($revisis) != 0): ?>
                        <div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Pengajuan Jilid KP Anda berstatus <strong>REVISI</strong>. Silahkan submit ulang dokumen yang diperlukan.
                            <br>
                            <a href="<?php echo e(route('kp.pengumpulan-akhir.detail.mahasiswa', $jilid->id)); ?>" class="btn btn-sm btn-outline-dark mt-2">
                                <i class="fas fa-eye mr-1"></i> Lihat Catatan Revisi
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo e($title); ?></h3>
                        </div>
                        <div class="card-body">
                            <form action="<?php echo e(route('kp.pengumpulan-akhir.update', $jilid->id)); ?>" method="post" enctype="multipart/form-data">
                                <?php echo method_field('put'); ?>
                                <?php echo csrf_field(); ?>

                                <h5><strong>Data Mahasiswa</strong></h5>
                                <hr>

                                <?php
                                    $mahasiswa = $jilid->mahasiswa;
                                    $pengajuan = $mahasiswa->pengajuansKP()->where('status', 'diterima')->first();
                                    $pendaftaran = $mahasiswa->pendaftaransKP()->where('status', 'diterima')->first();
                                    $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
                                    if (!$dosen_pembimbing) {
                                        $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'utama')->first();
                                    }
                                ?>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="text" class="form-control" value="<?php echo e($mahasiswa->email); ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>NIM</label>
                                            <input type="text" class="form-control" value="<?php echo e($mahasiswa->nim); ?>" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama</label>
                                            <input type="text" class="form-control" value="<?php echo e($mahasiswa->nama); ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Semester Pelaksanaan KP</label>
                                            <input type="text" class="form-control" value="Semester <?php echo e($mahasiswa->semesterKP ?? '-'); ?>" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Judul KP</label>
                                            <textarea class="form-control" rows="2" disabled><?php echo e($pengajuan->judul ?? '-'); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Pembimbing KP</label>
                                            <input type="text" class="form-control" value="<?php echo e($dosen_pembimbing ? $dosen_pembimbing->nama . ', ' . $dosen_pembimbing->gelar : '-'); ?>" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Lokasi KP</label>
                                            <input type="text" class="form-control" value="<?php echo e($jilid->lokasi_kp ?? $pengajuan->lokasi_kp ?? '-'); ?>" disabled>
                                            <input type="hidden" name="lokasi_kp" value="<?php echo e($jilid->lokasi_kp ?? $pengajuan->lokasi_kp ?? ''); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Waktu Pelaksanaan KP</label>
                                            <input type="text" class="form-control" value="<?php echo e($waktu_pelaksanaan); ?>" disabled>
                                            <input type="hidden" name="waktu_pelaksanaan_kp" value="<?php echo e($waktu_pelaksanaan); ?>">
                                        </div>
                                    </div>
                                </div>

                                <h5 class="mt-4"><strong>Dokumen Utama</strong></h5>
                                <hr>

                                <div class="form-group">
                                    <label>Lembar Keaslian</label>
                                    <div class="input-group mb-2">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input <?php $__errorArgs = ['lembar_keaslian'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                name="lembar_keaslian" accept=".pdf" id="lembar_keaslian">
                                            <label class="custom-file-label" for="lembar_keaslian">Pilih file baru (opsional)...</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    <?php $__errorArgs = ['lembar_keaslian'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <small class="text-danger"><?php echo e($message); ?></small>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <?php if($jilid->lembar_keaslian): ?>
                                        <small>File saat ini: <a href="<?php echo e(storage_url($jilid->lembar_keaslian)); ?>" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> <?php echo e(basename($jilid->lembar_keaslian)); ?></a></small>
                                    <?php else: ?>
                                        <small class="text-muted">Belum ada file</small>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <label>Lembar Persetujuan Pembimbing (TTD)</label>
                                    <div class="input-group mb-2">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input <?php $__errorArgs = ['lembar_persetujuan_pembimbing'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                name="lembar_persetujuan_pembimbing" accept=".pdf" id="lembar_persetujuan_pembimbing">
                                            <label class="custom-file-label" for="lembar_persetujuan_pembimbing">Pilih file baru (opsional)...</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    <?php $__errorArgs = ['lembar_persetujuan_pembimbing'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <small class="text-danger"><?php echo e($message); ?></small>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <?php if($jilid->lembar_persetujuan_pembimbing): ?>
                                        <small>File saat ini: <a href="<?php echo e(storage_url($jilid->lembar_persetujuan_pembimbing)); ?>" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> <?php echo e(basename($jilid->lembar_persetujuan_pembimbing)); ?></a></small>
                                    <?php else: ?>
                                        <small class="text-muted">Belum ada file</small>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <label>Lembar Persetujuan Penguji (TTD)</label>
                                    <div class="input-group mb-2">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input <?php $__errorArgs = ['lembar_persetujuan_penguji'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                name="lembar_persetujuan_penguji" accept=".pdf" id="lembar_persetujuan_penguji">
                                            <label class="custom-file-label" for="lembar_persetujuan_penguji">Pilih file baru (opsional)...</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    <?php $__errorArgs = ['lembar_persetujuan_penguji'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <small class="text-danger"><?php echo e($message); ?></small>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <?php if($jilid->lembar_persetujuan_penguji): ?>
                                        <small>File saat ini: <a href="<?php echo e(storage_url($jilid->lembar_persetujuan_penguji)); ?>" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> <?php echo e(basename($jilid->lembar_persetujuan_penguji)); ?></a></small>
                                    <?php else: ?>
                                        <small class="text-muted">Belum ada file</small>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <label>Lembar Pengesahan (TTD)</label>
                                    <div class="input-group mb-2">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input <?php $__errorArgs = ['lembar_pengesahan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                name="lembar_pengesahan" accept=".pdf" id="lembar_pengesahan">
                                            <label class="custom-file-label" for="lembar_pengesahan">Pilih file baru (opsional)...</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    <?php $__errorArgs = ['lembar_pengesahan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <small class="text-danger"><?php echo e($message); ?></small>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <?php if($jilid->lembar_pengesahan): ?>
                                        <small>File saat ini: <a href="<?php echo e(storage_url($jilid->lembar_pengesahan)); ?>" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> <?php echo e(basename($jilid->lembar_pengesahan)); ?></a></small>
                                    <?php else: ?>
                                        <small class="text-muted">Belum ada file</small>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <label>
                                        Lembar Bimbingan KP
                                        <br><small><a href="<?php echo e(route('kp.cetak.riwayat.bimbingan.mahasiswa')); ?>" target="_blank" class="text-primary"><i class="fas fa-download"></i> Download Lembar Bimbingan</a></small>
                                    </label>
                                    <div class="input-group mb-2">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input <?php $__errorArgs = ['lembar_bimbingan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                name="lembar_bimbingan" accept=".pdf" id="lembar_bimbingan">
                                            <label class="custom-file-label" for="lembar_bimbingan">Pilih file baru (opsional)...</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    <?php $__errorArgs = ['lembar_bimbingan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <small class="text-danger"><?php echo e($message); ?></small>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <?php if($jilid->lembar_bimbingan): ?>
                                        <small>File saat ini: <a href="<?php echo e(storage_url($jilid->lembar_bimbingan)); ?>" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> <?php echo e(basename($jilid->lembar_bimbingan)); ?></a></small>
                                    <?php else: ?>
                                        <small class="text-muted">Belum ada file</small>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <label>Lembar Revisi (ACC Penguji, 1 file)</label>
                                    <div class="input-group mb-2">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input <?php $__errorArgs = ['lembar_revisi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                name="lembar_revisi" accept=".pdf" id="lembar_revisi">
                                            <label class="custom-file-label" for="lembar_revisi">Pilih file baru (opsional)...</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    <?php $__errorArgs = ['lembar_revisi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <small class="text-danger"><?php echo e($message); ?></small>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <?php if($jilid->lembar_revisi): ?>
                                        <small>File saat ini: <a href="<?php echo e(storage_url($jilid->lembar_revisi)); ?>" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> <?php echo e(basename($jilid->lembar_revisi)); ?></a></small>
                                    <?php else: ?>
                                        <small class="text-muted">Belum ada file</small>
                                    <?php endif; ?>
                                </div>

                                <h5 class="mt-4"><strong>Laporan Kerja Praktek</strong></h5>
                                <hr>

                                <div class="form-group">
                                    <label>
                                        Laporan KP Format PDF
                                        <br><small class="text-muted">Digabung dengan Lembar Pengesahan TTD</small>
                                    </label>
                                    <select name="type_laporan_pdf" id="type_laporan_pdf" class="form-control mb-2" onchange="toggleInputFieldsPdf()">
                                        <option value="">-- Pilih Metode Upload --</option>
                                        <option value="upload">Upload File Langsung</option>
                                        <option value="link">Link Google Drive</option>
                                    </select>
                                    <div id="upload_field_pdf" style="display:none;">
                                        <div class="custom-file mb-2">
                                            <input type="file" class="custom-file-input <?php $__errorArgs = ['laporan_pdf'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                name="laporan_pdf" accept=".pdf" id="laporan_pdf">
                                            <label class="custom-file-label">Pilih file PDF...</label>
                                        </div>
                                    </div>
                                    <div id="link_field_pdf" style="display:none;">
                                        <input type="url" name="laporan_link_pdf" class="form-control mb-2" placeholder="https://drive.google.com/..." id="laporan_link_pdf">
                                    </div>
                                    <?php $__errorArgs = ['laporan_pdf'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <small class="text-danger"><?php echo e($message); ?></small>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <?php if($jilid->laporan_pdf): ?>
                                        <small>File saat ini: <a href="<?php echo e(storage_url($jilid->laporan_pdf)); ?>" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> <?php echo e(basename($jilid->laporan_pdf)); ?></a></small>
                                    <?php else: ?>
                                        <small class="text-muted">Belum ada file</small>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <label>
                                        Laporan KP Format Word
                                        <br><small class="text-muted">Format .docx</small>
                                    </label>
                                    <select name="type_laporan" id="type_laporan" class="form-control mb-2" onchange="toggleInputFields()">
                                        <option value="">-- Pilih Metode Upload --</option>
                                        <option value="upload">Upload File Langsung</option>
                                        <option value="link">Link Google Drive</option>
                                    </select>
                                    <div id="upload_field" style="display:none;">
                                        <div class="custom-file mb-2">
                                            <input type="file" class="custom-file-input <?php $__errorArgs = ['laporan_word'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                name="laporan_word" accept=".docx" id="laporan_word">
                                            <label class="custom-file-label">Pilih file Word...</label>
                                        </div>
                                    </div>
                                    <div id="link_field" style="display:none;">
                                        <input type="url" name="laporan_link" class="form-control mb-2" placeholder="https://drive.google.com/..." id="laporan_link">
                                    </div>
                                    <?php $__errorArgs = ['laporan_word'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <small class="text-danger"><?php echo e($message); ?></small>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <?php if($jilid->laporan_word): ?>
                                        <small>File saat ini: <a href="<?php echo e(storage_url($jilid->laporan_word)); ?>" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> <?php echo e(basename($jilid->laporan_word)); ?></a></small>
                                    <?php else: ?>
                                        <small class="text-muted">Belum ada file</small>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <label>
                                        Artikel KP Format Word
                                        <br><small class="text-muted">Format .docx (Opsional)</small>
                                    </label>
                                    <select name="type_artikel" id="type_artikel" class="form-control mb-2" onchange="toggleInputFieldsArtikel()">
                                        <option value="">-- Pilih Metode Upload --</option>
                                        <option value="upload">Upload File Langsung</option>
                                        <option value="link">Link Google Drive</option>
                                    </select>
                                    <div id="artikel_upload_field" style="display:none;">
                                        <div class="custom-file mb-2">
                                            <input type="file" class="custom-file-input <?php $__errorArgs = ['artikel'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                name="artikel" id="artikel" accept=".docx">
                                            <label class="custom-file-label">Pilih file Word...</label>
                                        </div>
                                    </div>
                                    <div id="artikel_link_field" style="display:none;">
                                        <input type="url" name="artikel_link" class="form-control mb-2" placeholder="https://drive.google.com/..." id="artikel_link">
                                    </div>
                                    <?php $__errorArgs = ['artikel'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <small class="text-danger"><?php echo e($message); ?></small>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <?php if($jilid->artikel): ?>
                                    <small>File saat ini: <a href="<?php echo e(storage_url($jilid->artikel)); ?>" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> <?php echo e(basename($jilid->artikel)); ?></a></small>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <label>
                                        Link Produk KP
                                        <br><small class="text-muted">Opsional - Upload ke Google Drive</small>
                                    </label>
                                    <input type="url" class="form-control" name="link_project" value="<?php echo e($jilid->link_project); ?>" placeholder="https://drive.google.com/...">
                                </div>

                                <div class="form-group">
                                    <label>
                                        File Project / Program KP
                                        <br><small class="text-muted">File project/program dikompres dalam format .zip atau .rar (Maks 100 MB)</small>
                                    </label>
                                    <div class="input-group mb-2">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input <?php $__errorArgs = ['file_project'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                name="file_project" accept=".zip,.rar" id="file_project">
                                            <label class="custom-file-label" for="file_project">Pilih file baru (opsional)...</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    <?php $__errorArgs = ['file_project'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <small class="text-danger"><?php echo e($message); ?></small>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <?php if($jilid->file_project): ?>
                                        <small>File saat ini: <a href="<?php echo e(storage_url($jilid->file_project)); ?>" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> <?php echo e(basename($jilid->file_project)); ?></a></small>
                                    <?php else: ?>
                                        <small class="text-muted">Belum ada file</small>
                                    <?php endif; ?>
                                </div>

                                <h5 class="mt-4"><strong>Dokumen Pendukung</strong></h5>
                                <hr>

                                <div class="form-group">
                                    <label>
                                        Form Nilai KP
                                        <br><small><a href="<?php echo e(route('kp.cetak.formulir.nilai.akhir')); ?>" target="_blank" class="text-primary"><i class="fas fa-download"></i> Download Formulir Nilai Akhir KP</a></small>
                                    </label>
                                    <div class="input-group mb-2">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input <?php $__errorArgs = ['form_nilai_kp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                name="form_nilai_kp" accept=".pdf,.jpg,.jpeg,.png" id="form_nilai_kp">
                                            <label class="custom-file-label" for="form_nilai_kp">Pilih file baru (opsional)...</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    <?php $__errorArgs = ['form_nilai_kp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <small class="text-danger"><?php echo e($message); ?></small>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <?php if($jilid->form_nilai_kp): ?>
                                        <small>File saat ini: <a href="<?php echo e(storage_url($jilid->form_nilai_kp)); ?>" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> <?php echo e(basename($jilid->form_nilai_kp)); ?></a></small>
                                    <?php else: ?>
                                        <small class="text-muted">Belum ada file</small>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <label>
                                        Berita Acara Serah Terima Produk KP
                                        <br><small class="text-muted">Berita acara serah terima produk dengan instansi/tempat penelitian KP dengan template
                                            <a href="https://drive.google.com/file/d/1X9eJxyj5GiPYP2MYHGOZiZEEbWWgGJ0J/view" target="_blank" class="text-primary">https://drive.google.com/file/d/1X9eJxyj5GiPYP2MYHGOZiZEEbWWgGJ0J/view</a>
                                        </small>
                                        <br><small><a href="<?php echo e(route('kp.cetak.berita.acara.serah.terima')); ?>" target="_blank" class="text-primary"><i class="fas fa-download"></i> Download Template Berita Acara</a></small>
                                    </label>
                                    <div class="input-group mb-2">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input <?php $__errorArgs = ['berita_acara'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                name="berita_acara" accept=".pdf" id="berita_acara">
                                            <label class="custom-file-label" for="berita_acara">Pilih file baru (opsional)...</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    <?php $__errorArgs = ['berita_acara'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <small class="text-danger"><?php echo e($message); ?></small>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <?php if($jilid->berita_acara): ?>
                                    <small>File saat ini: <a href="<?php echo e(storage_url($jilid->berita_acara)); ?>" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> <?php echo e(basename($jilid->berita_acara)); ?></a></small>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <label>
                                        Dokumen Lampiran
                                        <br><small class="text-muted">Opsional - Surat ijin KP, data KP, dll</small>
                                    </label>
                                    <div class="input-group mb-2">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input <?php $__errorArgs = ['lampiran'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                name="lampiran" accept=".pdf" id="lampiran">
                                            <label class="custom-file-label" for="lampiran">Pilih file baru (opsional)...</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    <?php $__errorArgs = ['lampiran'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <small class="text-danger"><?php echo e($message); ?></small>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <?php if($jilid->lampiran): ?>
                                    <small>File saat ini: <a href="<?php echo e(storage_url($jilid->lampiran)); ?>" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> <?php echo e(basename($jilid->lampiran)); ?></a></small>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <label>
                                        Panduan Penggunaan Produk KP
                                        <br><small class="text-muted">Format .docx atau Link Google Drive</small>
                                    </label>
                                    <select name="type_panduan" id="type_panduan" class="form-control mb-2" onchange="toggleInputFieldsPanduan()">
                                        <option value="">-- Pilih Metode Upload --</option>
                                        <option value="upload">Upload File Langsung</option>
                                        <option value="link">Link Google Drive</option>
                                    </select>
                                    <div id="panduan_upload_field" style="display:none;">
                                        <div class="custom-file mb-2">
                                            <input type="file" class="custom-file-input <?php $__errorArgs = ['panduan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                name="panduan" id="panduan" accept=".docx">
                                            <label class="custom-file-label">Pilih file Word...</label>
                                        </div>
                                    </div>
                                    <div id="panduan_link_field" style="display:none;">
                                        <input type="url" name="panduan_link" class="form-control mb-2" placeholder="https://drive.google.com/..." id="panduan_link">
                                    </div>
                                    <?php $__errorArgs = ['panduan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <small class="text-danger"><?php echo e($message); ?></small>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <?php if($jilid->panduan): ?>
                                    <small>File saat ini: <a href="<?php echo e(storage_url($jilid->panduan)); ?>" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> <?php echo e(basename($jilid->panduan)); ?></a></small>
                                    <?php endif; ?>
                                </div>

                                <hr>
                                <div class="d-flex justify-content-between">
                                    <a href="<?php echo e(route('kp.pengumpulan-akhir.mahasiswa')); ?>" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-paper-plane mr-1"></i> Submit Revisi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        function toggleInputFields() {
            var typeLaporan = document.getElementById('type_laporan').value;
            var uploadField = document.getElementById('upload_field');
            var linkField = document.getElementById('link_field');
            const laporan_word = document.getElementById('laporan_word');
            const laporan_link = document.getElementById('laporan_link');

            if (typeLaporan === 'upload') {
                uploadField.style.display = 'block';
                linkField.style.display = 'none';
                laporan_word.required = true;
                laporan_link.required = false;
            } else if (typeLaporan === 'link') {
                uploadField.style.display = 'none';
                linkField.style.display = 'block';
                laporan_word.required = false;
                laporan_link.required = true;
            } else {
                uploadField.style.display = 'none';
                laporan_word.required = true;
                laporan_link.required = false;
                linkField.style.display = 'none';
            }
        }

        function toggleInputFieldsPdf() {
            var typeLaporan = document.getElementById('type_laporan_pdf').value;
            var uploadField = document.getElementById('upload_field_pdf');
            var linkField = document.getElementById('link_field_pdf');
            const laporan_pdf = document.getElementById('laporan_pdf');
            const laporan_link = document.getElementById('laporan_link_pdf');

            if (typeLaporan === 'upload') {
                uploadField.style.display = 'block';
                linkField.style.display = 'none';
                laporan_pdf.required = true;
                laporan_link.required = false;
            } else if (typeLaporan === 'link') {
                uploadField.style.display = 'none';
                linkField.style.display = 'block';
                laporan_pdf.required = false;
                laporan_link.required = true;
            } else {
                uploadField.style.display = 'none';
                laporan_pdf.required = true;
                laporan_link.required = false;
                linkField.style.display = 'none';
            }
        }

        function toggleInputFieldsArtikel() {
            var typeArtikel = document.getElementById('type_artikel').value;
            var uploadField = document.getElementById('artikel_upload_field');
            var linkField = document.getElementById('artikel_link_field');
            const artikel = document.getElementById('artikel');
            const artikel_link = document.getElementById('artikel_link');

            if (typeArtikel === 'upload') {
                uploadField.style.display = 'block';
                linkField.style.display = 'none';
                artikel.required = true;
                artikel_link.required = false;
            } else if (typeArtikel === 'link') {
                uploadField.style.display = 'none';
                linkField.style.display = 'block';
                artikel.required = false;
                artikel_link.required = true;
            } else {
                uploadField.style.display = 'none';
                linkField.style.display = 'none';
                artikel.required = false;
                artikel_link.required = false;
            }
        }

        function toggleInputFieldsPanduan() {
            var typeLaporan = document.getElementById('type_panduan').value;
            var uploadField = document.getElementById('panduan_upload_field');
            var linkField = document.getElementById('panduan_link_field');
            const panduan = document.getElementById('panduan');
            const panduan_link = document.getElementById('panduan_link');

            if (typeLaporan === 'upload') {
                uploadField.style.display = 'block';
                linkField.style.display = 'none';
                panduan.required = true;
                panduan_link.required = false;
            } else if (typeLaporan === 'link') {
                uploadField.style.display = 'none';
                linkField.style.display = 'block';
                panduan.required = false;
                panduan_link.required = true;
            } else {
                uploadField.style.display = 'none';
                linkField.style.display = 'none';
                panduan.required = false;
                panduan_link.required = false;
            }
        }
    </script>
<?php $__env->stopSection(); ?>





<?php echo $__env->make('kp.layouts.dashboardMahasiswa', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/pengumpulan-akhir/edit.blade.php ENDPATH**/ ?>