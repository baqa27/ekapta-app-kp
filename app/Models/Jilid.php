<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jilid extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'total_pembayaran',
        'status',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

}
