

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
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Tabel <?php echo e($title); ?></h3>
                        </div>
                        <div class="card-body">

                            <span class="badge badge-success"> <i class="fas fa-check-circle mr-1"></i>
                                Diterima/Acc
                            </span>
                            <span class="badge badge-secondary"> <i class="fas fa-circle mr-1"></i>
                                Review/Belum Di Acc
                            </span>
                            <table id="example1" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Mahasiswa</th>
                                        <th>Kontak</th>
                                        <th>Prodi</th>
                                        <th>Judul Kerja Praktek</th>
                                        <th>Status Bimbingan</th>
                                        <th>Terakhir Bimbingan</th>
                                        <th>Bagian</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $no = 1;
                                    ?>
                                    <?php $__currentLoopData = $mahasiswas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mahasiswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        
                                        <tr>
                                            <td><?php echo e($no++); ?></td>
                                            <td>
                                                <?php echo e($mahasiswa->nama); ?>

                                                <?php echo e('(' . $mahasiswa->nim . ')'); ?>

                                            </td>
                                            <td>
                                                <?php if(substr($mahasiswa->hp, 0, 2) === "62"): ?>
                                                    <a href="https://api.whatsapp.com/send?phone=<?php echo e($mahasiswa->hp); ?>" class="btn btn-success btn-sm rounded-pill" target="_blank"><i class="fab fa-whatsapp"></i></a>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php echo e($mahasiswa->prodi); ?>

                                            </td>
                                            <td>
                                                <?php if($mahasiswa->pengajuansKP()->where('status', 'diterima')->first()): ?>
                                                    <?php echo e($mahasiswa->pengajuansKP()->where('status', 'diterima')->first()->judul); ?>

                                                <?php else: ?>
                                                    <span class="badge bg-secondary">BELUM PENGAJUAN JUDUL</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if(count(
                                                        $mahasiswa->bimbingansKP()->whereIn('status', ['revisi', 'review', 'diterima'])->get()) != 0): ?>
                                                    <span class="badge bg-success">AKTIF</span>
                                                <?php else: ?>
                                                    
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($mahasiswa->bimbingansKP()->orderBy('created_at', 'desc')->whereIn('status', ['revisi', 'review', 'diterima'])->first()): ?>
                                                    <?php echo e(\App\Helpers\AppHelper::parse_date($mahasiswa->bimbingansKP()->orderBy('tanggal_bimbingan', 'desc')->first()->tanggal_bimbingan)); ?>

                                                <?php endif; ?>
                                            </td>
                                            

                                            <td>
                                                <?php if(count($mahasiswa->bimbingansKP) != 0): ?>
                                                    <div>
                                                        <?php
                                                            // Support both old (utama/pendamping) and new (pembimbing) status
                                                            $pivotStatus = $mahasiswa->pivot->status;
                                                            $pembimbingLabel = match($pivotStatus) {
                                                                'utama' => '1',
                                                                'pendamping' => '2',
                                                                'pembimbing' => '',
                                                                default => ''
                                                            };

                                                            // Group bimbingan by bagian_id to avoid duplicates
                                                            // For each bagian, get the latest/best status (diterima > review > revisi)
                                                            $bimbinganByBagian = $mahasiswa->bimbingansKP->groupBy('bagian_id')->map(function($items) use ($pivotStatus) {
                                                                // Filter by pembimbing status for old system
                                                                if ($pivotStatus != 'pembimbing') {
                                                                    $items = $items->where('pembimbing', $pivotStatus);
                                                                }
                                                                // Get the one with best status (prioritize diterima)
                                                                return $items->sortByDesc(function($item) {
                                                                    return $item->status == 'diterima' ? 2 : ($item->status == 'review' ? 1 : 0);
                                                                })->first();
                                                            })->filter()->sortKeys();
                                                        ?>
                                                        <span>Sebagai Pembimbing <?php echo e($pembimbingLabel); ?></span> <br>
                                                        <?php $__currentLoopData = $bimbinganByBagian; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bimbingan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php if($bimbingan): ?>
                                                                <?php if($bimbingan->status == 'diterima'): ?>
                                                                    <a href="<?php echo e(storage_url($bimbingan->lampiran)); ?>" target="_blank">
                                                                        <span class="badge badge-success">
                                                                            <i class="fas fa-check-circle mr-1"></i>
                                                                            <?php echo e($bimbingan->bagian->bagian); ?>

                                                                        </span>
                                                                    </a>
                                                                <?php else: ?>
                                                                    <span class="badge badge-secondary">
                                                                        <i class="fas fa-circle mr-1"></i>
                                                                        <?php echo e($bimbingan->bagian->bagian); ?>

                                                                    </span>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                <?php else: ?>
                                                <span class="badge badge-secondary">BELUM ADA BIMBINGAN</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($mahasiswa->pendaftaransKP()->where('status','diterima')->first()): ?>
                                                <a href="<?php echo e(route('kp.cetak.surat.tugas.bimbingan')); ?>"
                                                    target="_blank" class="btn btn-success btn-sm"><i class="fas fa-download"></i> Surat Tugas Bimbingan KP</a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Mahasiswa</th>
                                        <th>Kontak</th>
                                        <th>Prodi</th>
                                        <th>Judul Kerja Praktek</th>
                                        <th>Status Bimbingan</th>
                                        <th>Terakhir Bimbingan</th>
                                        <th>Bagian</th>
                                        <th>Aksi</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- /.content -->
<?php $__env->stopSection(); ?>





<?php echo $__env->make('kp.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/dosen/bimbingan/bimbingan-progress.blade.php ENDPATH**/ ?>