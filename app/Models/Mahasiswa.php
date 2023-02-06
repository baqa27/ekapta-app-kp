<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Mahasiswa as Authenticatable;

class Mahasiswa extends Authenticatable
{
    use HasFactory;

    protected $hidden = [
        'password',
    ];

    protected $fillable = [
        'nim',
        'nama',
        'thmasuk',
        'prodi',
        'tptlahir',
        'tgllahir',
        'jeniskelamin',
        'kodedosenwali',
        'nik',
        'kelas',
        'email',
        'hp',
        'alamat',
        'password',
    ];

    public function dosens()
    {
        return $this->belongsToMany(Dosen::class, 'dosen_mahasiswas', 'mahasiswa_id', 'dosen_id')
            ->withTimestamps()
            ->withPivot(['status']);
    }

    public function bimbingans()
    {
        return $this->hasMany(Bimbingan::class);
    }

    public function bagians()
    {
        return $this->belongsToMany(Bagian::class, 'bimbingans');
    }

    public function pengajuans(){
        return $this->hasMany(Pengajuan::class);
    }

    public function pendaftarans(){
        return $this->hasMany(Pendaftaran::class);
    }


    public function seminar(){
        return $this->hasOne(Seminar::class);
    }
}
