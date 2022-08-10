<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // '/pengajuan/store',
        // '/pengajuan/edit',
        // '/pengajuan/update',
        // '/pengajuan/delete',
        // '/pengajuan/acc',
        // '/pengajuan/revisi',
        // '/pengajuan/tolak',
        // '/pengajuan/revisi/delete',
        // '/ploting/pembimbing',
        // '/ploting/penguji',
        // '/pendaftaran/store',
        // '/pendaftaran/edit',
        // '/pendaftaran/update',
        // '/pendaftaran/delete',
        // '/pendaftaran/acc',
        // '/pendaftaran/acc/update',
        // '/pendaftaran/revisi',
        // '/pendaftaran/revisi/delete',
        // '/bagian/store',
        // '/bagian/edit',
        // '/bagian/update',
        // '/bagian/delete',
        // '/bimbingan/store',
        // '/bimbingan/edit',
        // '/bimbingan/update',
        // '/bimbingan/revisi/store',
        // '/bimbingan/revisi/delete',
        // '/bimbingan/acc',
    ];
}
