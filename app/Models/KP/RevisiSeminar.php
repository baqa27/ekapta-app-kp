<?php

namespace App\Models\KP;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model RevisiSeminar untuk Kerja Praktek
 * Tabel: revisi_seminar_kps (dengan suffix _kp)
 */
class RevisiSeminar extends Model
{
    use HasFactory;

    protected $table = 'revisi_seminar_kps';

    protected $fillable = [
        'seminar_id',
        'catatan',
        'lampiran',
    ];

    public function seminar()
    {
        return $this->belongsTo(Seminar::class);
    }
}
