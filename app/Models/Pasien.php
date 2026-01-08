<?php

namespace App\Models;

use App\Models\Penjab;
use App\Models\Propinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\RegPeriksa;
use App\Models\EfktpPcareAlergi;
use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pasien extends Model
{
    use HasFactory, Compoships;
    protected $table = 'pasien';
    protected $guarded = [];
    public $timestamps = false;
    protected $hidden = ['laravel_through_key'];

    function regPeriksa()
    {
        return $this->hasMany(RegPeriksa::class, 'no_rkm_medis', 'no_rkm_medis');
    }
    function penjab()
    {
        return $this->belongsTo(Penjab::class, 'kd_pj', 'kd_pj');
    }
    function alergi()
    {
        return $this->hasMany(EfktpPcareAlergi::class, 'no_rkm_medis', 'no_rkm_medis');
    }
    function kel()
    {
        return $this->belongsTo(Kelurahan::class, 'kd_kel', 'kd_kel');
    }
    function kec()
    {
        return $this->belongsTo(Kecamatan::class, 'kd_kec', 'kd_kec');
    }
    function kab()
    {
        return $this->belongsTo(Kabupaten::class, 'kd_kab', 'kd_kab');
    }
    function prop()
    {
        return $this->belongsTo(Propinsi::class, 'kd_prop', 'kd_prop');
    }
    function instansi()
    {
        return $this->belongsTo(PerusahaanPasien::class, 'kode_perusahaan', 'perusahaan_pasien');
    }
    function sukuBangsa()
    {
        return $this->belongsTo(SukuBangsa::class, 'suku_bangsa', 'id');
    }
    function bahasaPasien()
    {
        return $this->belongsTo(BahasaPasien::class, 'bahasa_pasien', 'id');
    }
    function cacatFisik()
    {
        return $this->belongsTo(CacatFisik::class, 'cacat_fisik', 'id');
    }
    function perusahaanPasien()
    {
        return $this->belongsTo(PerusahaanPasien::class, 'perusahaan_pasien', 'kode_perusahaan');
    }
}
