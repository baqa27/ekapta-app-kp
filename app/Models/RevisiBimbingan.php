<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevisiBimbingan extends Model
{
    use HasFactory;

    protected $fillable = [
        'bimbingan_id',
        'dosen_id',
        'catatan',
        'lampiran',
    ];

    public function bimbingan()
    {
        return $this->belongsTo(Bimbingan::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }
}
