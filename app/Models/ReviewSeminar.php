<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewSeminar extends Model
{
    use HasFactory;

    public const DITERIMA = 'diterima';
    public const REVISI = 'revisi';
    public const REVIEW = 'review';
    public const DOSEN_PEMBIMBING = 'pembimbing';
    public const DOSEN_PENGUJI = 'penguji';

    protected $fillable = [
        'seminar_id',
        'dosen_id',
        'status',
        'nilai_1',
        'nilai_2',
        'nilai_3',
        'nilai_4',
        'tanggal_acc',
        'dosen_status',
        'lampiran',
        'keterangan',
        'tanggal_acc_manual',
        'lampiran_lembar_revisi',
    ];

    public function revisis(){
        return $this->hasMany(RevisiReviewSeminar::class);
    }
    public function seminar()
    {
        return $this->belongsTo(Seminar::class);
    }

    function dosen(){
        return $this->belongsTo(Dosen::class);
    }
}
