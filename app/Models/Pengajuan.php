<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nim',
        'judul',
        'deskripsi',
        'lampiran',
        'status',
        'tanggal_acc',
    ];

    public function revisis()
    {
        return $this->hasMany(RevisiPengajuan::class);
    }
}
