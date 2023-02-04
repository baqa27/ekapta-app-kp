<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    public const DITERIMA = 'diterima';
    public const REVIEW = 'review';
    public const REVISI = 'revisi';

    protected $fillable = [
        'pengajuan_id',
        'mahasiswa_id',
        'email',
        'hp',
        'semester',
        'nomor_pembayaran',
        'tanggal_pembayaran',
        'biaya',
        'lampiran_1',
        'lampiran_2',
        'lampiran_3',
        'lampiran_4',
        'lampiran_5',
        'lampiran_acc',
        'tanggal_acc',
        'status',
    ];

    public function revisis()
    {
        return $this->hasMany(RevisiPendaftaran::class);
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
