<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Tahap Jilid & Setor Perpustakaan
    |--------------------------------------------------------------------------
    |
    | Set false untuk menonaktifkan tahap jilid (proses cetak/bayar) dan
    | setor perpustakaan. Jika false, status jilid berhenti di VALID
    | setelah Prodi/Admin verifikasi — tidak ada tombol "Selesaikan Jilid"
    | maupun "Konfirmasi Setor Perpus".
    |
    */
    'tahap_jilid_perpus_aktif' => env('KP_TAHAP_JILID_PERPUS_AKTIF', false),

    /*
    |--------------------------------------------------------------------------
    | Daftar Dokumen Pengumpulan Akhir KP
    |--------------------------------------------------------------------------
    |
    | Single source of truth untuk semua dokumen yang harus/bisa diupload
    | mahasiswa pada tahap Pengumpulan Akhir KP. Digunakan di semua view
    | (create, edit, detail mahasiswa, detail admin, detail prodi).
    |
    */
    'dokumen_pengumpulan_akhir' => [
        // Dokumen Utama
        [
            'field' => 'lembar_pengesahan',
            'label' => 'Lembar Pengesahan KP (TTD)',
            'required' => true,
            'keterangan' => 'Lembar pengesahan yang sudah ditandatangani pembimbing, penguji, dan pihak terkait',
        ],
        [
            'field' => 'lembar_bimbingan',
            'label' => 'Lembar Bimbingan KP',
            'required' => true,
            'keterangan' => 'Lembar bimbingan KP yang sudah di-ACC pembimbing',
        ],
        [
            'field' => 'lembar_revisi',
            'label' => 'Lembar Revisi (ACC Penguji, 1 file)',
            'required' => true,
            'keterangan' => 'Lembar revisi yang sudah di-ACC penguji setelah seminar',
        ],

        // Laporan Kerja Praktek
        [
            'field' => 'laporan_pdf',
            'label' => 'Laporan KP Format PDF',
            'required' => true,
            'keterangan' => 'Digabung dengan Lembar Pengesahan TTD',
        ],
        [
            'field' => 'laporan_word',
            'label' => 'Laporan KP Format Word',
            'required' => true,
            'keterangan' => 'Format .docx',
        ],
        [
            'field' => 'file_project',
            'label' => 'File Project / Program KP',
            'required' => false,
            'keterangan' => 'File project/program dikompres dalam format .zip atau .rar (Maks 100 MB). Kosongkan jika prodi tidak mewajibkan produk.',
        ],
        [
            'field' => 'link_project',
            'label' => 'Link Produk KP',
            'required' => false,
            'keterangan' => 'Opsional - Upload ke Google Drive',
        ],

        // Dokumen Pendukung
        [
            'field' => 'form_nilai_kp',
            'label' => 'Form Nilai KP',
            'required' => true,
            'keterangan' => 'Form nilai lengkap (Pembimbing, Penguji, Instansi)',
        ],
        [
            'field' => 'berita_acara',
            'label' => 'Berita Acara Serah Terima Produk KP',
            'required' => false,
            'keterangan' => 'Berita acara serah terima produk dengan instansi/tempat penelitian KP',
            'keterangan_prodi' => 'Wajib untuk prodi Teknik Informatika',
        ],
        [
            'field' => 'panduan',
            'label' => 'Panduan Penggunaan Produk KP',
            'required' => false,
            'keterangan' => 'Format .docx atau Link Google Drive. Kosongkan jika tidak ada produk.',
            'keterangan_prodi' => 'Wajib untuk prodi Teknik Informatika',
        ],
        [
            'field' => 'bukti_nilai_instansi',
            'label' => 'Bukti Nilai Instansi',
            'required' => false,
            'keterangan' => 'Surat atau dokumen yang menunjukkan nilai yang diberikan instansi tempat KP',
        ],
        [
            'field' => 'lampiran',
            'label' => 'Dokumen Lampiran Lainnya',
            'required' => false,
            'keterangan' => 'Dokumen pendukung lain yang relevan dengan KP',
        ],
    ],
];
