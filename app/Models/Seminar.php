<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seminar extends Model
{
    use HasFactory;

    public const DITERIMA = 'diterima';
    public const REVISI = 'revisi';
    public const REVIEW = 'review';
    public const VALID = 1;
    public const NOT_VALID = 0;

    protected $fillable = [
        'pengajuan_id',
        'mahasiswa_id',
        'lampiran_1',
        'lampiran_2',
        'lampiran_3',
        'lampiran_4',
        'lampiran_5',
        'is_valid',
    ];

    public function revisis()
    {
        return $this->hasMany(RevisiSeminar::class);
    }

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
