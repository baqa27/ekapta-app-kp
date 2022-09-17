<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seminar extends Model
{
    use HasFactory;

    protected $fillable = [
        'nim',
        'tanggal_pembuatan_ta',
        'tanggal_acc_pembimbing_utama',
        'tanggal_acc_pembimbing_pendamping',
        'lampiran_1',
        'lampiran_2',
        'lampiran_3',
        'lampiran_4',
        'lampiran_5',
        'link_video',
        'nilai',
        'dosen_id',
    ];

    public function revisis()
    {
        return $this->hasMany(RevisiSeminar::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }
}