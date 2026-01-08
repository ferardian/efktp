<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class RujukInternal extends Model
{

    use HasFactory, Compoships;
    protected $table = 'rujukan_internal_poli';
    protected $guarded = [];
    public $timestamps = false;

    function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }
    function dokter()
    {
        return $this->belongsTo(Dokter::class, 'kd_dokter', 'kd_dokter');
    }
    function poliklinik()
    {
        return $this->belongsTo(Poliklinik::class, 'kd_poli', 'kd_poli');
    }
    function pemeriksaan()
    {
        return $this->hasOne(PemeriksaanRalan::class, ['no_rawat', 'nip'], ['no_rawat', 'kd_dokter']);
    }
    function pemeriksaanAwal() : HasOneThrough
    {
        return $this->hasOneThrough(PemeriksaanRalan::class, RegPeriksa::class, 'no_rawat', 'no_rawat', 'no_rawat', 'no_rawat' );
    }

    function poliAsal() : HasOneThrough
    {
        return $this->hasOneThrough(Poliklinik::class, RegPeriksa::class, 'no_rawat', 'kd_poli', 'no_rawat', 'kd_poli');
    }
    function DokterAsal() : HasOneThrough
    {
        return $this->hasOneThrough(Dokter::class, RegPeriksa::class, 'no_rawat', 'kd_dokter', 'no_rawat', 'kd_dokter');
    }
}
