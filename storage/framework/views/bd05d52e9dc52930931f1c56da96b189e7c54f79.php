

<?php $__env->startSection('content'); ?>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo e($title); ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('kp.dashboard.himpunan')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active"><?php echo e($title); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Mahasiswa Siap Dijadwalkan -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-users mr-2"></i>Mahasiswa Siap Dijadwalkan
                        <span class="badge bg-success ml-2"><?php echo e(count($seminars_siap)); ?></span>
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-buat-sesi" <?php echo e(count($seminars_siap) == 0 ? 'disabled' : ''); ?>>
                            <i class="fas fa-plus mr-1"></i> Buat Sesi Seminar
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <?php if(count($seminars_siap) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="bg-light">
                                <tr>
                                    <th width="50">No</th>
                                    <th>NIM</th>
                                    <th>Nama</th>
                                    <th>Prodi</th>
                                    <th>Judul KP</th>
                                    <th>Tgl Diterima</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $seminars_siap; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $seminar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td><?php echo e($seminar->mahasiswa ? $seminar->mahasiswa->nim : '-'); ?></td>
                                    <td><?php echo e($seminar->mahasiswa ? $seminar->mahasiswa->nama : 'Mahasiswa tidak ditemukan'); ?></td>
                                    <td><?php echo e($seminar->mahasiswa ? $seminar->mahasiswa->prodi : '-'); ?></td>
                                    <td><?php echo e(Str::limit($seminar->judul_laporan ?? ($seminar->pengajuan ? $seminar->pengajuan->judul : 'Judul tidak ditemukan'), 50)); ?></td>
                                    <td><?php echo e($seminar->tanggal_acc ? $seminar->tanggal_acc->format('d M Y') : '-'); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <p class="text-muted text-center py-3">Tidak ada mahasiswa yang siap dijadwalkan</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Daftar Sesi Seminar -->
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-calendar-alt mr-2"></i>Daftar Sesi Seminar</h3>
                </div>
                <div class="card-body">
                    <?php if(count($sesi_seminars) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tableSesi">
                            <thead class="bg-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Waktu</th>
                                    <th>Tempat</th>
                                    <th>Penguji</th>
                                    <th>Peserta</th>
                                    <th>Status Link</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $sesi_seminars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sesi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($sesi->tanggal->translatedFormat('d M Y')); ?></td>
                                    <td><?php echo e(\Carbon\Carbon::parse($sesi->jam_mulai)->format('H:i')); ?> - <?php echo e(\Carbon\Carbon::parse($sesi->jam_selesai)->format('H:i')); ?></td>
                                    <td><?php echo e($sesi->tempat); ?></td>
                                    <td><?php echo e($sesi->dosenPenguji ? $sesi->dosenPenguji->nama . ', ' . $sesi->dosenPenguji->gelar : '-'); ?></td>
                                    <td><span class="badge bg-info"><?php echo e(count($sesi->seminars)); ?> mahasiswa</span></td>
                                    <td>
                                        <?php if($sesi->is_token_used): ?>
                                            <span class="badge bg-secondary"><i class="fas fa-check"></i> Sudah Digunakan</span>
                                        <?php else: ?>
                                            <span class="badge bg-success"><i class="fas fa-link"></i> Aktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('kp.jadwal.himpunan.detail', $sesi->id)); ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if(!$sesi->is_token_used): ?>
                                        <button type="button" class="btn btn-warning btn-sm" onclick="copyLink('<?php echo e($sesi->link_penilaian); ?>')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="hapusSesi(<?php echo e($sesi->id); ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <p class="text-muted text-center py-3">Belum ada sesi seminar</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Buat Sesi -->
    <div class="modal fade" id="modal-buat-sesi">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="<?php echo e(route('kp.jadwal.himpunan.create')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-header bg-primary text-white">
                        <h4 class="modal-title">Buat Sesi Seminar Baru</h4>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tanggal Seminar <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Jam Mulai <span class="text-danger">*</span></label>
                                    <input type="time" name="jam_mulai" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Jam Selesai <span class="text-danger">*</span></label>
                                    <input type="time" name="jam_selesai" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tempat / Link Online <span class="text-danger">*</span></label>
                                    <input type="text" name="tempat" class="form-control" placeholder="Ruang A / Link Zoom" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Dosen Penguji <span class="text-danger">*</span></label>
                                    <select name="dosen_penguji_id" class="form-control select-1" required>
                                        <option value="">-- Pilih Dosen --</option>
                                        <?php $__currentLoopData = $dosens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dosen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($dosen->id); ?>"><?php echo e($dosen->nama . ', ' . $dosen->gelar); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jumlah Mahasiswa per Sesi</label>
                                    <input type="number" name="jumlah_mahasiswa" class="form-control" value="8" min="1" max="20">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Catatan Teknis</label>
                                    <input type="text" name="catatan_teknis" class="form-control" placeholder="Catatan untuk penguji...">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Pilih Mahasiswa <span class="text-danger">*</span></label>
                            <div class="border p-2" style="max-height: 200px; overflow-y: auto;">
                                <?php $__currentLoopData = $seminars_siap; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $seminar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="seminars[]" value="<?php echo e($seminar->id); ?>" id="mhs<?php echo e($seminar->id); ?>">
                                    <label class="custom-control-label" for="mhs<?php echo e($seminar->id); ?>">
                                        <?php echo e($seminar->mahasiswa ? $seminar->mahasiswa->nim : '-'); ?> - <?php echo e($seminar->mahasiswa ? $seminar->mahasiswa->nama : 'Mahasiswa tidak ditemukan'); ?>

                                    </label>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Buat Sesi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function copyLink(link) {
            navigator.clipboard.writeText(link).then(function() {
                $(document).Toasts('create', {
                    class: 'bg-success mt-5 mr-3',
                    title: 'Berhasil',
                    autohide: true,
                    delay: 3000,
                    body: 'Link penilaian berhasil disalin!'
                });
            });
        }

        function hapusSesi(id) {
            Swal.fire({
                title: 'Hapus Sesi Seminar?',
                text: 'Sesi seminar dan semua data terkait akan dihapus!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#form-hapus-' + id).submit();
                }
            });
        }
    </script>

    <?php $__currentLoopData = $sesi_seminars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sesi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if(!$sesi->is_token_used): ?>
    <form id="form-hapus-<?php echo e($sesi->id); ?>" action="<?php echo e(route('kp.jadwal.himpunan.delete', $sesi->id)); ?>" method="POST" style="display: none;">
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>
    </form>
    <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>





<?php echo $__env->make('kp.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/himpunan/seminar/jadwal.blade.php ENDPATH**/ ?>