<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Rujuk extends Model
{
    use HasFactory, Compoships, HasRelationships;
    protected $table = 'rujuk';
    protected $guarded = [];
    public $timestamps = false;
    protected $with = ['regPeriksa', 'pasien.kel', 'pasien.kec', 'pasien.kab', 'dokter', 'poliklinik', 'pemeriksaan'];


    function regPeriksa(): BelongsTo
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat')
            ->select(['no_reg', 'no_rawat', 'kd_poli', 'kd_dokter', 'no_rawat', 'tgl_registrasi', 'jam_reg', 'umurdaftar', 'sttsumur']);
    }

    function pemeriksaan(): HasOne
    {
        return $this->hasOne(PemeriksaanRalan::class, 'no_rawat', 'no_rawat');
    }
    function pasien(): HasOneThrough
    {
        return $this->hasOneThrough(Pasien::class, RegPeriksa::class, 'no_rawat', 'no_rkm_medis', 'no_rawat', 'no_rkm_medis')
            ->select(['pasien.no_rkm_medis', 'nm_pasien', 'tgl_lahir', 'jk', 'alamat', 'kd_kel', 'kd_kec', 'kd_kab']);
    }
    function poliklinik(): HasOneThrough
    {
        return $this->hasOneThrough(Poliklinik::class, RegPeriksa::class, 'no_rawat', 'kd_poli', 'no_rawat', 'kd_poli');
    }
    function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class, 'kd_dokter', 'kd_dokter')
            ->select(['kd_dokter', 'nm_dokter', 'no_ijn_praktek']);
    }
    function scopeTglBetween($query, $request)
    {
        $query->whereBetween(
            'tgl_rujuk',
            [
                date('Y-m-d', strtotime($request[0])),
                date('Y-m-d', strtotime($request[1]))
            ]
        );
    }
    function scopeNoRawat($query, $key): void
    {
        $query->where('no_rawat', $key);
    }

    function scopeByDokter($query, $key): void
    {
        $query->where('kd_dokter', $key);
    }
    function scopeNoRujuk($query, $key): void
    {
        $query->where('no_rujuk', $key);
    }
}
