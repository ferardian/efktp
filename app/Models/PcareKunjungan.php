<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PcareKunjungan extends Model
{
    use HasFactory;
    protected $table = 'pcare_kunjungan_umum';
    protected $guarded = [];
    public $timestamps = false;

    function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }
    function pasien()
    {
        return $this->belongsTo(Pasien::class, 'no_rkm_medis', 'no_rkm_medis');
    }
    function rujukSubspesialis()
    {
        return $this->hasOne(PcareRujukSubspesialis::class, 'noKunjungan', 'noKunjungan');
    }
}
