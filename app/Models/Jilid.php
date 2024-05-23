<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jilid extends Model
{
    use HasFactory;

    public const JILID_REVIEW = 1;
    public const JILID_REVISI = 2;
    public const JILID_VALID = 3;
    public const JILID_SELESAI = 4;

    protected $fillable = [
        'mahasiswa_id',
        'total_pembayaran',
        'status',
        'laporan_pdf',
        'laporan_word',
        'lembar_pengesahan',
        'link_project',
        'catatan',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

}
