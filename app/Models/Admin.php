<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Admin as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nik',
        'nama',
        'tgllahir',
        'tptlahir',
        'alamat',
        'email',
        'hp',
        'password',
    ];

    protected $hidden = [
        'password'
    ];
}
