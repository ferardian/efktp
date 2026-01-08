<?php

namespace App\Models\Lab\Permintaan;

use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Penjab;
use App\Models\Poliklinik;
use App\Models\RegPeriksa;
use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class PermintaanLab extends Model
{
    use Compoships;
    use HasFactory;

    protected $table = 'permintaan_lab';
    protected $guarded = [];
    public $timestamps = false;

    function pemeriksaan(): HasMany
    {
        return $this->hasMany(PermintaanPemeriksaanLab::class, 'noorder', 'noorder');
    }
    function detail(): HasMany
    {
        return $this->hasMany(DetailPermintaanLab::class, 'kd_jenis_prw', 'kd_jenis_prw');
    }
	function registrasi() : BelongsTo
	{
		return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat')
			->select('no_rawat', 'kd_poli', 'no_rkm_medis', 'kd_pj', 'kd_dokter', 'umurdaftar', 'sttsumur');
	}

	function pasien() : HasOneThrough
	{
		return $this->hasOneThrough(Pasien::class, RegPeriksa::class, 'no_rawat', 'no_rkm_medis', 'no_rawat', 'no_rkm_medis')
			->select('pasien.no_rkm_medis', 'nm_pasien', 'jk', 'tgl_lahir');
	}

	function poliklinik() : HasOneThrough
	{
		return $this->hasOneThrough(Poliklinik::class, RegPeriksa::class, 'no_rawat', 'kd_poli', 'no_rawat', 'kd_poli');
	}
	function penjab() : HasOneThrough
	{
		return $this->hasOneThrough(Penjab::class, RegPeriksa::class, 'no_rawat', 'kd_pj', 'no_rawat', 'kd_pj')
			->select('penjab.kd_pj', 'png_jawab as nama');
	}

	function perujuk() : BelongsTo
	{
		return $this->belongsTo(Dokter::class, 'dokter_perujuk', 'kd_dokter')
			->select('kd_dokter', 'nm_dokter');
	}

}
