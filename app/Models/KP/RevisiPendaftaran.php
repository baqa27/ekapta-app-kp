<?php

namespace App\Models\KP;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model RevisiPendaftaran untuk Kerja Praktek
 * Tabel: revisi_pendaftaran_kps (dengan suffix _kp)
 */
class RevisiPendaftaran extends Model
{
    use HasFactory;

    protected $table = 'revisi_pendaftaran_kps';

    protected $fillable = [
        'pendaftaran_id',
        'keterangan',
        'lampiran',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }
}
