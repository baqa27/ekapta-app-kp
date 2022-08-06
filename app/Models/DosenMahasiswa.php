<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DosenMahasiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'nim',
        'dosbim_utama',
        'dosbim_pendamping',
        'dosen_penguji',
    ];

    public function mahasiswas()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function dosens()
    {
        return $this->belongsTo(Dosen::class);
    }
}
