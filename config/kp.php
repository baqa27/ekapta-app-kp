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
];
