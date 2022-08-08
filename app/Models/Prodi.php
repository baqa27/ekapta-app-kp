<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Prodi as Authenticatable;

class Prodi extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'namaprodi',
        'jenjang',
        'kodekaprodi',
        'password',
    ];

    protected $hidden = [
        'password'
    ];

    public function bagians()
    {
        return $this->hasMany(Bagian::class);
    }
}
