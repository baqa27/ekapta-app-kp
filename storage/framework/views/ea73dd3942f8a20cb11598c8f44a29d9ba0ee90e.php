<?php
$bulan = null;
if ($date->format('m') == '01'){
$bulan = 'I';
}elseif ($date->format('m') == '02'){
$bulan = 'II';
}elseif ($date->format('m') == '03'){
$bulan = 'III';
}elseif ($date->format('m') == '04'){
$bulan = 'IV';
}elseif ($date->format('m') == '05'){
$bulan = 'V';
}elseif ($date->format('m') == '06'){
$bulan = 'VI';
}elseif ($date->format('m') == '07'){
$bulan = 'VII';
}elseif ($date->format('m') == '08'){
$bulan = 'VIII';
}elseif ($date->format('m') == '09'){
$bulan = 'IX';
}elseif ($date->format('m') == '10'){
$bulan = 'X';
}elseif ($date->format('m') == '11'){
$bulan = 'XI';
}elseif ($date->format('m') == '12'){
$bulan = 'XII';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo e($title); ?></title>
    <style>
        * {
            margin: 0;
        }

        .margin-left {
            margin-left: 80px;
        }

        p {
            font-size: 12pt;
            text-align: justify
        }

        .margin-right {
            margin-right: 40px;
        }

        .margin-top {
            position: relative;
            top: 10px;
        }

        .titik-dua {
            margin-left: 10px;
            margin-right: 10px;
        }

        .top {
            position: relative;
            top: -24px;
        }

        .text-keterangan {
            position: relative;
            left: 495px;
        }

        #qr-code {
            margin-top: -40px;
            margin-left: 670px;
        }

        .text-expired {
            margin-left: 80px;
            color: rgb(255, 89, 191);
        }

        .d-flex {
            display: flex;
        }

        #stempel {
            opacity: 20%;
            position: relative;
            top: 10px;
            right: -10px;
        }

        #ttd {
            position: relative;
            left: -80px;
            max-width: 140px;
            top: -30px;
        }

        #detail-dekan {
            position: relative;
            top: -70px
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <td>
                <img src="<?php echo e($kop_surat); ?>" alt="Kop Surat" height="151">
            </td>
        </tr>
        <tr>
            <td>
                <center>
                    <h3>LEMBAR BIMBINGAN SKRIPSI / TUGAS AKHIR (TA)</h3>
                    <br>
                </center>
            </td>
        </tr>
    </table>
    <img src="<?php echo e($qr_code); ?>" alt="QR Code" height="80" id="qr-code">

    <table class="margin-left">
         <tr>
            <td width="120">NAMA</td>
            <td width="2">:</td>
            <td width="350"><?php echo e($mahasiswa->nama); ?></td>
        </tr>
        <tr>
            <td>NIM</td>
            <td width="2">:</td>
            <td><?php echo e($mahasiswa->nim); ?></td>
        </tr>
        <tr>
            <td>PRODI</td>
            <td width="2">:</td>
            <td><?php echo e($prodi->namaprodi); ?></td>
        </tr>
        <tr>
            <td style="text-align: left;vertical-align: top;">JUDUL TA</td>
            <td width="2" style="text-align: left;vertical-align: top;">:</td>
            <td><?php echo e($pengajuan->judul); ?></td>
        </tr>
        <tr>
            <td>PEMBIMBING 1</td>
            <td width="2">:</td>
            <td><?php echo e($dosen_utama->nama . ', ' . $dosen_utama->gelar); ?></td>
        </tr>
        <tr>
            <td>NO SURAT TUGAS</td>
            <td width="2">:</td>
            <td><?php echo e($no_urut); ?>/ST.TA/FASTIKOM-UNSIQ/<?php echo e($bulan); ?>/<?php echo e($date->format('Y')); ?></td>
        </tr>
    </table>

    <table class="margin-left table-border" style="border: 1px solid black; border-collapse: collapse;height: 670px;">
        <tr style="background-color: gray">
            <td width="20" style="text-align:center; border: 1px solid black; border-collapse: collapse;">No</td>
            <td width="100" style="text-align:center; border: 1px solid black; border-collapse: collapse;">TANGGAL
            </td>
            <td width="250" style="text-align:center; border: 1px solid black; border-collapse: collapse;">KETERANGAN
            </td>
            <td width="100" style="text-align:center; border: 1px solid black; border-collapse: collapse;">TANDA
                TANGAN</td>
        </tr>
        <?php
            $no_utama = 1;
        ?>
        <?php $__currentLoopData = $bimbingan_dosen_utama; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bimbingan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($bimbingan->status == 'diterima'): ?>
                <tr>
                    <td style="text-align:center; border: 1px solid black; border-collapse: collapse;"><?php echo e($no_utama++); ?>

                    </td>
                    <td style="border: 1px solid black; border-collapse: collapse;">
                        <?php echo e(\App\Helpers\AppHelper::parse_date_short($bimbingan->tanggal_acc)); ?></td>
                    <td style="border: 1px solid black; border-collapse: collapse;">
                        <p><?php echo e($bimbingan->bagian->bagian); ?></p>
                        <?php if(count($bimbingan->revisis) != 0): ?>
                            <ul>
                                <?php $__currentLoopData = $bimbingan->revisis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $revisi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e(strip_tags($revisi->catatan)); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        <?php endif; ?>
                        
                    </td>
                    <td style="text-align:center; border: 1px solid black; border-collapse: collapse;">
                        <img src="<?php echo e($ttd_dosen_utama); ?>" height="50"  style="max-width:60px;">
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </table>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <table>
        <tr>
            <td>
                <img src="<?php echo e($kop_surat); ?>" alt="Kop Surat" height="151">
            </td>
        </tr>
        <tr>
            <td>
                <center>
                    <h3>LEMBAR BIMBINGAN SKRIPSI / TUGAS AKHIR (TA)</h3>
                    <br>
                </center>
            </td>
        </tr>
    </table>
    <img src="<?php echo e($qr_code); ?>" alt="QR Code" height="80" id="qr-code">

    <table class="margin-left">
        <tr>
            <td width="120">NAMA</td>
            <td width="2">:</td>
            <td width="350"><?php echo e($mahasiswa->nama); ?></td>
        </tr>
        <tr>
            <td>NIM</td>
            <td width="2">:</td>
            <td><?php echo e($mahasiswa->nim); ?></td>
        </tr>
        <tr>
            <td>PRODI</td>
            <td width="2">:</td>
            <td><?php echo e($prodi->namaprodi); ?></td>
        </tr>
        <tr>
            <td style="text-align: left;vertical-align: top;">JUDUL TA</td>
            <td width="2" style="text-align: left;vertical-align: top;">:</td>
            <td><?php echo e($pengajuan->judul); ?></td>
        </tr>
        <tr>
            <td>PEMBIMBING 2</td>
            <td width="2">:</td>
            <td><?php echo e($dosen_pendamping->nama . ', ' . $dosen_pendamping->gelar); ?></td>
        </tr>
        <tr>
            <td>NO SURAT TUGAS</td>
            <td width="2">:</td>
            <td><?php echo e($no_urut); ?>/ST.TA/FASTIKOM-UNSIQ/<?php echo e($bulan); ?>/<?php echo e($date->format('Y')); ?></td>
        </tr>
    </table>

    <table class="margin-left table-border" style="border: 1px solid black; border-collapse: collapse;height: 670px;">
        <tr style="background-color: gray">
            <td width="20" style="text-align:center; border: 1px solid black; border-collapse: collapse;">No</td>
            <td width="100" style="text-align:center; border: 1px solid black; border-collapse: collapse;">TANGGAL
            </td>
            <td width="250" style="text-align:center; border: 1px solid black; border-collapse: collapse;">KETERANGAN
            </td>
            <td width="100" style="text-align:center; border: 1px solid black; border-collapse: collapse;">TANDA
                TANGAN</td>
        </tr>
        <?php
            $no_pendamping = 1;
        ?>
        <?php $__currentLoopData = $bimbingan_dosen_pendamping; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bimbingan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($bimbingan->status == 'diterima'): ?>
                <tr>
                    <td style="text-align:center; border: 1px solid black; border-collapse: collapse;">
                        <?php echo e($no_pendamping++); ?>

                    </td>
                    <td style="border: 1px solid black; border-collapse: collapse;">
                        <?php echo e(\App\Helpers\AppHelper::parse_date_short($bimbingan->tanggal_acc)); ?></td>
                    <td style="border: 1px solid black; border-collapse: collapse;">
                        <p><?php echo e($bimbingan->bagian->bagian); ?></p>
                        <?php if(count($bimbingan->revisis) != 0): ?>
                            <ul>
                                <?php $__currentLoopData = $bimbingan->revisis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $revisi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e(strip_tags($revisi->catatan)); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        <?php endif; ?>
                        
                    </td>
                    <td style="text-align:center; border: 1px solid black; border-collapse: collapse;">
                        <img src="<?php echo e($ttd_dosen_pendamping); ?>" height="50" style="max-width:60px;">
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </table>

    <p class="text-expired">
        <b><i>NB. BATAS MAKSIMAL SAMPAI PADA : <?php echo e($date_expired); ?></i></b>
    </p>
</body>

</html>
<?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/pages/cetak/lembarBimbinganSkripsi.blade.php ENDPATH**/ ?>