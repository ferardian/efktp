<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemeriksaanRalan extends Model
{
	use HasFactory, Compoships;

	protected $table = 'pemeriksaan_ralan';
	protected $guarded = [];
	public $timestamps = false;

	function regPeriksa()
	{
		return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
	}

	function diagnosa()
	{
		return $this->hasMany(DiagnosaPasien::class, 'no_rawat', 'no_rawat');
	}

	function prosedur()
	{
		return $this->hasMany(ProsedurPasien::class, 'no_rawat', 'no_rawat');
	}

	function pegawai()
	{
		return $this->belongsTo(Pegawai::class, 'nip', 'nik');
	}

	function rujukInternal()
	{
		return $this->hasOne(RujukInternal::class, ['kd_dokter', 'no_rawat'], ['nip', 'no_rawat']);
	}

	function resep()
	{
		return $this->hasMany(ResepObat::class, 'no_rawat', 'no_rawat');
	}
}
