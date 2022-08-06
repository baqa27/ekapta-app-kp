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

    public function mahasiswa()
    {
        return $this->hasMany(DosenMahasiswa::class);
    }
}
