<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bimbingan extends Model
{
    use HasFactory;

    protected $fillable = [
        'keterangan',
        'lampiran',
        'status',
        'mahasiswa_id',
        'bagian_id',
        'tanggal_acc',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function bagian()
    {
        return $this->belongsTo(Bagian::class);
    }

    public function revisis()
    {
        return $this->hasMany(RevisiBimbingan::class);
    }

    public function dosens()
    {
        return $this->belongsToMany(Dosen::class, 'revisi_bimbingans');
    }
}
