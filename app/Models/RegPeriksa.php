<?php

namespace App\Models;

use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Penjab;
use App\Models\KamarInap;
use App\Models\PemeriksaanRalan;
use Awobaz\Compoships\Compoships;
use App\Models\PcareRujukSubspesialis;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RegPeriksa extends Model
{
	use HasFactory, Compoships;

	protected $table = 'reg_periksa';
	protected $guarded = [];
	public $timestamps = false;
	protected $hidden = ['laravel_through_key'];

	protected $primaryKey = 'no_rawat';
	protected $keyType = 'string';

	function pasien()
	{
		return $this->belongsTo(Pasien::class, 'no_rkm_medis', 'no_rkm_medis');
	}

	function dokter()
	{
		return $this->belongsTo(Dokter::class, 'kd_dokter', 'kd_dokter');
	}

	function penjab()
	{
		return $this->belongsTo(Penjab::class, 'kd_pj', 'kd_pj');
	}

	function pemeriksaanRalan()
	{
		return $this->hasOne(PemeriksaanRalan::class, 'no_rawat', 'no_rawat');
	}

	function pemeriksaanRanap()
	{
		return $this->hasMany(PemeriksaanRanap::class, 'no_rawat', 'no_rawat');
	}

	function pemeriksaanDokter()
	{
		return $this->hasOne(PemeriksaanRalan::class, ['no_rawat', 'nip'], ['no_rawat', 'kd_dokter']);
	}

	function riwayatPemeriksaan()
	{
		return $this->hasMany(PemeriksaanRalan::class, 'no_rawat', 'no_rawat');
	}

	function resepObat()
	{
		return $this->hasMany(ResepObat::class, 'no_rawat', 'no_rawat');
	}

	function diagnosa()
	{
		return $this->hasMany(DiagnosaPasien::class, 'no_rawat', 'no_rawat');
	}

	function prosedur()
	{
		return $this->hasMany(ProsedurPasien::class, 'no_rawat', 'no_rawat');
	}

	function poliklinik()
	{
		return $this->belongsTo(Poliklinik::class, 'kd_poli', 'kd_poli');
	}

	function pcarePendaftaran()
	{
		return $this->belongsTo(PcarePendaftaran::class, 'no_rawat', 'no_rawat');
	}

	function pcareRujukSubspesialis()
	{
		return $this->belongsTo(PcareRujukSubspesialis::class, 'no_rawat', 'no_rawat');
	}

	function gigi()
	{
		return $this->hasOne(PemeriksaanGigi::class, 'no_rawat', 'no_rawat');
	}

	function kamarInap()
	{
		return $this->hasOne(KamarInap::class, 'no_rawat', 'no_rawat');
	}

	public function scopeMaxByTanggal($query, string|null $tanggal)
	{
		$tanggal = $tanggal ? $tanggal : now()->format('Y-m-d');
		$query->where('tgl_registrasi', $tanggal)->orderBy('no_rawat', 'DESC');
	}
}
