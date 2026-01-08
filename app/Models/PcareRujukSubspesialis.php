<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PcareRujukSubspesialis extends Model
{
    use HasFactory;
    protected $table = 'pcare_rujuk_subspesialis';
    protected $guarded = [];
    public $timestamps = false;

    function detail()
    {
        return $this->hasOne(EfktpPcareRujukSubspesialis::class, 'noKunjungan', 'noKunjungan');
    }
    function pasien()
    {
        return $this->belongsTo(Pasien::class, 'no_rkm_medis', 'no_rkm_medis');
    }
    function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }
}
