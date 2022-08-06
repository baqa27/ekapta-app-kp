<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $fillable = [
        'nidn',
        'nik',
        'nama',
        'gelar',
        'tgllahir',
        'tptlahir',
        'alamat',
        'email',
        'hp',
        'kodeprodi',
        'pass',
    ];

    public function mahasiswas()
    {
        return $this->belongsToMany(Mahasiswa::class, 'dosen_mahasiswas', 'mahasiswa_id', 'dosen_id')
            ->withTimestamps()
            ->withPivot(['status']);
    }
}
