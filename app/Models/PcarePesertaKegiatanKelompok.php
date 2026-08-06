<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PcarePesertaKegiatanKelompok extends Model
{
    use HasFactory;

    protected $table = 'pcare_peserta_kegiatan_kelompok';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];

    public function kegiatan()
    {
        return $this->belongsTo(PcareKegiatanKelompok::class, 'eduId', 'eduId');
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'noKartu', 'no_peserta');
    }
}
