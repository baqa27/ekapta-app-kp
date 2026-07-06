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

        p,
        b,
        span {
            font-size: 11pt;
        }

        .titik-dua {
            margin-left: 10px;
            margin-right: 10px;
        }

        .top {
            position: relative;
            top: -29px;
        }

        .text-keterangan {
            position: relative;
            left: 560px;
        }

        .table-bordered {
            border: 1px solid black;
            border-collapse: collapse;
            /* text-align: center; */
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
            <td height="60">
                <center>
                    <h4>
                        <u><?php echo e($title); ?></u>
                    </h4>
                </center>
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <td colspan="3">
                <p class="margin-left"><i>Bismillaahirrohmaanirrokhiim</i></p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <br>
                <p class="margin-left">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Kerja Praktek Fakultas Teknik dan Ilmu Komputer (FASTIKOM) Universitas
                    Sains Al-Qur’an (UNSIQ) Jawa Tengah di Wonosobo telah mengadakan Sidang pada:
                </p>
                <br>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td width="100"></td>
            <td width="100">
                <p>Hari</p>
            </td>
            <td width="1">:</td>
            <td>
                <?php if($ujian_or_seminar->tanggal_ujian): ?>
                    <?php echo e(\Carbon\Carbon::parse($ujian_or_seminar->tanggal_ujian)->dayName); ?>

                <?php else: ?>
                    ……………………………
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td width="100"></td>
            <td>
                <p>Tanggal</p>
            </td>
            <td width="1">:</td>
            <td>
                <?php if($ujian_or_seminar->tanggal_ujian): ?>
                    <?php echo e(\Carbon\Carbon::parse($ujian_or_seminar->tanggal_ujian)->day . ' ' . \Carbon\Carbon::parse($ujian_or_seminar->tanggal_ujian)->monthName . ' ' . \Carbon\Carbon::parse($ujian_or_seminar->tanggal_ujian)->year); ?>

                <?php else: ?>
                    ……………………………
                <?php endif; ?>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td width="588" height="30">
                <center>
                    <b>MEMUTUSKAN</b>
                </center>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td height="20"></td>
            <td colspan="4">
                <p>Bahwa Saudara:</p>
            </td>
        </tr>
        <tr>
            <td width="100"></td>
            <td width="100">
                <p><b>NIM</b></p>
            </td>
            <td width="1">:</td>
            <td>
                <b><?php echo e($ujian_or_seminar->mahasiswa->nim); ?></b>
            </td>
        </tr>
        <tr>
            <td width="100"></td>
            <td>
                <p><b>Nama</b></p>
            </td>
            <td width="1">:</td>
            <td>
                <b><?php echo e($ujian_or_seminar->mahasiswa->nama); ?></b>
            </td>
        </tr>
        <tr>
            <td width="100"></td>
            <td>
                <p><b>Program Studi</b></p>
            </td>
            <td width="1">:</td>
            <td>
                <b><?php echo e($ujian_or_seminar->mahasiswa->prodi); ?></b>
            </td>
        </tr>
        <tr>
            <td width="100"></td>
            <td style="vertical-align: top !important;">
                <p><b>Judul Kerja Praktek</b></p>
            </td>
            <td style="vertical-align: top !important;" width="1">:</td>
            <td width="350" style="text-align: justify;">
                <b style="padding-right: 10px;"><?php echo e($ujian_or_seminar->pengajuan->judul); ?></b>
            </td>
        </tr>
        <tr>
            <td height="20" colspan="4"></td>
        </tr>
        <tr>
            <td width="100"></td>
            <td>
                <p>Dinyatakan </p>
            </td>
            <td width="1">:</td>
            <td>
                <?php if(!$is_blank): ?>
                    <p>
                        <?php if($ujian_or_seminar->is_lulus == 1): ?>
                            LULUS
                        <?php elseif($ujian_or_seminar->is_lulus == 2): ?>
                            TIDAK LULUS
                        <?php endif; ?>
                    </p>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td width="100"></td>
            <td>
                <p>Nilai </p>
            </td>
            <td width="1">:</td>
            <td>
                <p>
                    <?php if($is_complete): ?>
                        <?php echo e($nilai); ?>

                    <?php elseif($nilai_angka): ?>
                        <?php echo e(number_format($nilai_angka, 2)); ?>

                    <?php endif; ?>
                </p>
            </td>
        </tr>
        <tr>
            <td width="100"></td>
            <td>
                <p>Predikat </p>
            </td>
            <td width="1">:</td>
            <td>
                <p>
                    <?php if($is_complete || $nilai): ?>
                        <?php if($nilai == 'A'): ?>
                            Baik Sekali
                        <?php elseif($nilai == 'B'): ?>
                            Baik
                        <?php elseif($nilai == 'C'): ?>
                            Cukup
                        <?php elseif($nilai == 'D'): ?>
                            Kurang
                        <?php elseif($nilai == 'E'): ?>
                            Kurang Sekali
                        <?php endif; ?>
                    <?php endif; ?>
                </p>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="3" width="700" height="50">
                <p class="text-keterangan">Wonosobo, <?php echo e($tanggal_ujian); ?></p>
            </td>
        </tr>
        <tr>
            <?php
                $no = 0;
                $reviews_penguji = $ujian_or_seminar->reviews()->where('dosen_status', 'penguji')->get();
            ?>
            <?php if(count($reviews_penguji) > 0): ?>
                <?php $__currentLoopData = $reviews_penguji; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $no++;
                        $ttd_dosen = null;
                        if (!$is_blank) {
                            $ttd_dosen = $review->dosen->ttd
                                ? \App\Helpers\AppHelper::instance()->convertImage(
                                    'storage/app/public/' . substr($review->dosen->ttd, 31),
                                )
                                : null;
                        }
                    ?>
                    <td height="120">
                        <center>
                            <span>Penguji
                                <?php if($no == 1): ?>
                                    I
                                <?php elseif($no == 2): ?>
                                    II
                                <?php elseif($no == 3): ?>
                                    III
                                <?php endif; ?>
                            </span>
                            <br><br>
                            <img src="<?php echo e($ttd_dosen); ?>" alt="TTD Dekan" height="70" id="ttd">
                            <br><br>
                            <span><?php echo e($review->dosen->nama); ?>, <?php echo e($review->dosen->gelar); ?></span>
                        </center>
                    </td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                
                <td height="120" colspan="3">
                    <center>
                        <span class="text-muted">Dosen penguji belum diploting</span>
                    </center>
                </td>
            <?php endif; ?>
        </tr>
    </table>
    <br><br><br><br>

    <?php
        $no = 0;
        $reviews_penguji = $ujian_or_seminar->reviews()->where('dosen_status', 'penguji')->get();
    ?>
    <?php $__currentLoopData = $reviews_penguji; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $no++;
            if (!$is_blank) {
                $ttd_dosen_penguji = $review->dosen->ttd
                    ? \App\Helpers\AppHelper::instance()->convertImage(
                        'storage/app/public/' . substr($review->dosen->ttd, 31),
                    )
                    : null;
                // Untuk KP, nilai diambil dari seminar_kps, bukan dari review
                $nilai_penguji_display = $ujian_or_seminar->nilai_penguji ?? $ujian_or_seminar->nilai_seminar ?? 0;
            }
        ?>
        <table>
            <tr>
                <td>
                    <img src="<?php echo e($kop_surat); ?>" alt="Kop Surat" height="151">
                </td>
            </tr>
            <tr>
                <td height="60">
                    <center>
                        <h4>
                            <u><?php echo e($title_form_nilai); ?></u>
                        </h4>
                    </center>
                </td>
            </tr>
        </table>
        <table class="margin-left">
            <tr>
                <td width="100">
                    <p><b>NIM</b></p>
                </td>
                <td width="1">:</td>
                <td>
                    <b><?php echo e($ujian_or_seminar->mahasiswa->nim); ?></b>
                </td>
            </tr>
            <tr>
                <td>
                    <p><b>Nama</b></p>
                </td>
                <td width="1">:</td>
                <td>
                    <b><?php echo e($ujian_or_seminar->mahasiswa->nama); ?></b>
                </td>
            </tr>
            <tr>
                <td>
                    <p><b>Program Studi</b></p>
                </td>
                <td width="1">:</td>
                <td>
                    <b><?php echo e($ujian_or_seminar->mahasiswa->prodi); ?></b>
                </td>
            </tr>
            <tr>
                <td style="vertical-align: top !important;">
                    <p><b>Judul Kerja Praktek</b></p>
                </td>
                <td style="vertical-align: top !important;" width="1">:</td>
                <td width="400" style="text-align: justify;">
                    <b style="padding-right: 10px;"><?php echo e($ujian_or_seminar->pengajuan->judul); ?></b>
                </td>
            </tr>
        </table>
        <br>
        
        <table class="margin-left table-bordered">
            <tr>
                <td height="20" width="275"
                    style="border: 1px solid black; background-color:rgb(189, 189, 189); text-align: center; padding: 10px;">
                    <b>NILAI SEMINAR KP</b>
                </td>
                <td width="150"
                    style="border: 1px solid black; background-color:rgb(189, 189, 189); text-align: center;">
                    <b>JUMLAH NILAI</b>
                </td>
            </tr>
            <tr>
                <td height="60" style="border: 1px solid black; text-align: center; padding: 10px;">
                    <h4><b>NILAI TOTAL</b></h4>
                </td>
                <td height="60" style="border: 1px solid black; text-align:center; font-size: 18pt;">
                    <b><?php echo e($is_blank ? '……………' : number_format($nilai_penguji_display, 2)); ?></b>
                </td>
            </tr>
        </table>
        <br>
        <table class="margin-left">
            <tr>
                <td width="50" style="vertical-align: top !important;">
                    Catatan :
                </td>
                <td style="vertical-align: top !important;" width="1"></td>
                <td width="390">
                    <?php for($i = 0; $i < 455; $i++): ?>
                        <?php echo e('.'); ?>

                    <?php endfor; ?>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td colspan="3" width="700" height="50">
                    <p class="text-keterangan">Wonosobo, <?php echo e($tanggal_ujian); ?></p>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <center>
                        <span>Penguji
                            <?php if($no == 1): ?>
                                I
                            <?php elseif($no == 2): ?>
                                II
                            <?php elseif($no == 3): ?>
                                III
                            <?php endif; ?>
                        </span>
                        <?php if($is_blank): ?>
                            <br><br><br><br>
                        <?php else: ?>
                            <br>
                            <img src="<?php echo e($ttd_dosen_penguji); ?>" alt="TTD Dekan" height="70" id="ttd">
                            <br>
                        <?php endif; ?>
                        <span><?php echo e($review->dosen->nama); ?>, <?php echo e($review->dosen->gelar); ?></span>
                    </center>
                </td>
            </tr>
        </table>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <?php if(!$is_blank): ?>
        <?php
            $reviews_pembimbing = $ujian_or_seminar->reviews()->where('dosen_status', 'pembimbing')->get();
        ?>
        <?php $__currentLoopData = $reviews_pembimbing; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $ttd_dosen_pembimbing = $review->dosen->ttd
                    ? \App\Helpers\AppHelper::instance()->convertImage(
                        'storage/app/public/' . substr($review->dosen->ttd, 31),
                    )
                    : null;
                // Untuk KP, nilai diambil dari seminar_kps, bukan dari review
                $nilai_pembimbing_display = $ujian_or_seminar->nilai_pembimbing ?? 0;
            ?>
            <table>
                <tr>
                    <td>
                        <img src="<?php echo e($kop_surat); ?>" alt="Kop Surat" height="151">
                    </td>
                </tr>
                <tr>
                    <td height="60">
                        <center>
                            <h4>
                                <u><?php echo e($title_form_nilai); ?></u>
                            </h4>
                        </center>
                    </td>
                </tr>
            </table>
            <table class="margin-left">
                <tr>
                    <td width="100">
                        <p><b>NIM</b></p>
                    </td>
                    <td width="1">:</td>
                    <td>
                        <b><?php echo e($ujian_or_seminar->mahasiswa->nim); ?></b>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><b>Nama</b></p>
                    </td>
                    <td width="1">:</td>
                    <td>
                        <b><?php echo e($ujian_or_seminar->mahasiswa->nama); ?></b>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><b>Program Studi</b></p>
                    </td>
                    <td width="1">:</td>
                    <td>
                        <b><?php echo e($ujian_or_seminar->mahasiswa->prodi); ?></b>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top !important;">
                        <p><b>Judul Kerja Praktek</b></p>
                    </td>
                    <td style="vertical-align: top !important;" width="1">:</td>
                    <td width="400" style="text-align: justify;">
                        <b style="padding-right: 10px;"><?php echo e($ujian_or_seminar->pengajuan->judul); ?></b>
                    </td>
                </tr>
            </table>
            <br>
            
            <table class="margin-left table-bordered">
                <tr>
                    <td height="20" width="275"
                        style="border: 1px solid black; background-color:rgb(189, 189, 189); text-align: center; padding: 10px;">
                        <b>NILAI SEMINAR KP</b>
                    </td>
                    <td width="150"
                        style="border: 1px solid black; background-color:rgb(189, 189, 189); text-align: center;">
                        <b>JUMLAH NILAI</b>
                    </td>
                </tr>
                <tr>
                    <td height="60" style="border: 1px solid black; text-align: center; padding: 10px;">
                        <h4><b>NILAI TOTAL</b></h4>
                    </td>
                    <td height="60" style="border: 1px solid black; text-align:center; font-size: 18pt;">
                        <b><?php echo e(number_format($nilai_pembimbing_display, 2)); ?></b>
                    </td>
                </tr>
            </table>
            <br>
            <table class="margin-left">
                <tr>
                    <td width="50" style="vertical-align: top !important;">
                        Catatan :
                    </td>
                    <td style="vertical-align: top !important;" width="1"></td>
                    <td width="390">
                        <?php for($i = 0; $i < 455; $i++): ?>
                            <?php echo e('.'); ?>

                        <?php endfor; ?>
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td colspan="3" width="700" height="50">
                        <p class="text-keterangan">Wonosobo, <?php echo e($tanggal_ujian); ?></p>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <center>
                            <span>Pembimbing
                                <?php if($no == 1): ?>
                                    I
                                <?php elseif($no == 2): ?>
                                    II
                                <?php endif; ?>
                            </span>
                            <br>
                            <img src="<?php echo e($ttd_dosen_pembimbing); ?>" alt="TTD Dekan" height="70" id="ttd">
                            <br>
                            <span><?php echo e($review->dosen->nama); ?>, <?php echo e($review->dosen->gelar); ?></span>
                        </center>
                    </td>
                </tr>
            </table>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>

    <?php if($is_blank): ?>
        <?php
            $no = 0;
        ?>
        <?php $__currentLoopData = $ujian_or_seminar->reviews()->where('dosen_status', 'penguji')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $no++;
            ?>
            <table>
                <tr>
                    <td>
                        <img src="<?php echo e($kop_surat); ?>" alt="Kop Surat" height="151">
                    </td>
                </tr>
                <tr>
                    <td height="60">
                        <center>
                            <h4>
                                <u><?php echo e($title_form_revisi); ?></u>
                            </h4>
                        </center>
                    </td>
                </tr>
            </table>
            <table class="margin-left">
                <tr>
                    <td width="100">
                        <p><b>NIM</b></p>
                    </td>
                    <td width="1">:</td>
                    <td>
                        <b><?php echo e($ujian_or_seminar->mahasiswa->nim); ?></b>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><b>Nama</b></p>
                    </td>
                    <td width="1">:</td>
                    <td>
                        <b><?php echo e($ujian_or_seminar->mahasiswa->nama); ?></b>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><b>Program Studi</b></p>
                    </td>
                    <td width="1">:</td>
                    <td>
                        <b><?php echo e($ujian_or_seminar->mahasiswa->prodi); ?></b>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top !important;">
                        <p><b>Judul Kerja Praktek</b></p>
                    </td>
                    <td style="vertical-align: top !important;" width="1">:</td>
                    <td width="400" style="text-align: justify;">
                        <b style="padding-right: 10px;"><?php echo e($ujian_or_seminar->pengajuan->judul); ?></b>
                    </td>
                </tr>
            </table>
            <br>
            <table class="margin-left table-bordered">
                <tr>
                    <td height="20" width="20"
                        style="border: 1px solid black; background-color:rgb(189, 189, 189); text-align: center;">NO
                    </td>
                    <td width="370"
                        style="border: 1px solid black; background-color:rgb(189, 189, 189); text-align: center;">
                        URAIAN
                        PENILAIAN</td>
                    <td width="100"
                        style="border: 1px solid black; background-color:rgb(189, 189, 189); text-align: center;">TANDA
                        TANGAN
                    </td>
                </tr>
                <tr>
                    <td height="340" style="border: 1px solid black; text-align: center;"></td>
                    <td style="border: 1px solid black; padding-left:5px;"></td>
                    <td style="border: 1px solid black; text-align:center;"></td>
                </tr>
            </table>
            <br>
            <table>
                <tr>
                    <td width="320">
                        <center>
                            <span>Acc. Revisi pada tanggal</span><br><br>
                            <span>....................................................</span><br><br>
                            <span>Penguji
                                <?php if($no == 1): ?>
                                    I
                                <?php elseif($no == 2): ?>
                                    II
                                <?php elseif($no == 3): ?>
                                    III
                                <?php endif; ?>
                            </span>
                            <br><br><br><br>
                            <span><?php echo e($review->dosen->nama); ?>, <?php echo e($review->dosen->gelar); ?></span>
                        </center>
                    </td>
                    <td>
                        <center>
                            <span>Wonosobo, <?php echo e($tanggal_ujian); ?></span><br><br>
                            </span><br><br>
                            <span>Penguji
                                <?php if($no == 1): ?>
                                    I
                                <?php elseif($no == 2): ?>
                                    II
                                <?php elseif($no == 3): ?>
                                    III
                                <?php endif; ?>
                            </span>
                            <br><br><br><br>
                            <span><?php echo e($review->dosen->nama); ?>, <?php echo e($review->dosen->gelar); ?></span>
                        </center>
                    </td>
                </tr>
            </table>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>

</body>

</html>




<?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/cetak/berita-acara-seminar.blade.php ENDPATH**/ ?>