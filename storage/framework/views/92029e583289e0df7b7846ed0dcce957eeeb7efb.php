<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title); ?></title>
    <style>
        * { margin: 0; }
        body { font-family: 'Times New Roman', serif; font-size: 12pt; }
        .margin-left { margin-left: 80px; }
        .margin-right { margin-right: 80px; }
        p { text-align: justify; line-height: 1.6; }
        .text-center { text-align: center; }
        .mt-3 { margin-top: 15px; }
        .mb-3 { margin-bottom: 15px; }
        .bold { font-weight: bold; }
        table { width: 100%; }
        .info-table td { padding: 3px 0; }
        .info-table td:first-child { width: 150px; }
        .catatan-box {
            border: 1px solid #333;
            padding: 15px;
            margin: 15px 80px;
            background-color: #f9f9f9;
        }
        .footer-note {
            margin-top: 20px;
            padding: 10px;
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            margin-left: 80px;
            margin-right: 80px;
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
    </table>

    <div class="text-center mt-3">
        <h3 style="text-decoration: underline;">SURAT KETERANGAN PENOLAKAN PENGAJUAN</h3>
        <h3>KERJA Praktek (KP)</h3>
    </div>

    <div class="margin-left margin-right mt-3">
        <p>Yang bertanda tangan di bawah ini, Ketua Program Studi <?php echo e($prodi->namaprodi); ?> Fakultas Teknik dan Ilmu Komputer (FASTIKOM) Universitas Sains Al-Qur'an (UNSIQ) Jawa Tengah di Wonosobo, menerangkan bahwa:</p>

        <table class="info-table mt-3" style="margin-left: 20px;">
            <tr>
                <td>Nama</td>
                <td>: <?php echo e($mahasiswa->nama); ?></td>
            </tr>
            <tr>
                <td>NIM</td>
                <td>: <?php echo e($mahasiswa->nim); ?></td>
            </tr>
            <tr>
                <td>Program Studi</td>
                <td>: <?php echo e($mahasiswa->prodi); ?></td>
            </tr>
            <tr>
                <td>Judul KP</td>
                <td>: <?php echo e($pengajuan->judul); ?></td>
            </tr>
            <tr>
                <td>Lokasi KP</td>
                <td>: <?php echo e($pengajuan->lokasi_kp); ?></td>
            </tr>
        </table>

        <p class="mt-3">Pengajuan Kerja Praktek mahasiswa tersebut di atas telah <strong>DITOLAK</strong> pada tanggal <?php echo e($tanggal_tolak); ?> dengan alasan sebagai berikut:</p>
    </div>

    <div class="catatan-box">
        <strong>Catatan Penolakan:</strong><br>
        <?php echo e($catatan); ?>

    </div>

    <div class="margin-left margin-right">
        <p>Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
    </div>

    <table style="margin-top: 30px;">
        <tr>
            <td width="400"></td>
            <td>
                <p>Wonosobo, <?php echo e($tanggal_tolak); ?></p>
                <p>Ketua Program Studi <?php echo e($prodi->namaprodi); ?></p>
                <br><br><br><br>
                <?php if(\App\Helpers\AppHelper::instance()->getDosen($prodi->kodekaprodi)): ?>
                <p><u><strong><?php echo e(\App\Helpers\AppHelper::instance()->getDosen($prodi->kodekaprodi)->nama); ?>, <?php echo e(\App\Helpers\AppHelper::instance()->getDosen($prodi->kodekaprodi)->gelar); ?></strong></u></p>
                <p>NIDN. <?php echo e(\App\Helpers\AppHelper::instance()->getDosen($prodi->kodekaprodi)->nidn); ?></p>
                <?php else: ?>
                <p><u><strong>____________________</strong></u></p>
                <p>NIDN. ____________________</p>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</body>
</html>




<?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/cetak/suratPenolakan.blade.php ENDPATH**/ ?>