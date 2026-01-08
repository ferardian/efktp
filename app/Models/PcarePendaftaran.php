<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PcarePendaftaran extends Model
{
    use HasFactory;
    protected $table = 'pcare_pendaftaran';
    protected $guarded = [];
    public $timestamps = false;

    function pasien()
    {
        return $this->belongsTo(Pasien::class, 'no_rkm_medis', 'no_rkm_medis');
    }
    function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }
    function pemeriksaan()
    {
        return $this->belongsTo(PemeriksaanRalan::class, 'no_rawat', 'no_rawat');
    }
    function kunjungan()
    {
        return $this->belongsTo(PcareKunjungan::class, 'no_rawat', 'no_rawat');
    }
}
