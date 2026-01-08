<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EfktpHasilUsg extends Model
{
	use HasFactory, Compoships;

	protected $table = 'efktp_hasil_usg';
	protected $primaryKey = 'no_rawat';
	protected $keyType = 'string';

	protected $guarded = false;

	public function regPeriksa()
	{
		return $this->belongsTo(RegPeriksa::class, 'no_rawat')
			->select(['reg_periksa.no_rawat', 'no_rkm_medis', 'kd_poli', 'kd_pj', 'tgl_registrasi']);
	}

	public function pasien()
	{
		return $this->hasOneThrough(Pasien::class, RegPeriksa::class, 'no_rawat', 'no_rkm_medis', 'no_rawat', 'no_rkm_medis')
			->select(['pasien.no_rkm_medis', 'nm_pasien', 'keluarga', 'namakeluarga', 'tgl_lahir', 'jk']);
	}

	public function dokter()
	{
		return $this->hasOneThrough(Dokter::class, RegPeriksa::class, 'no_rawat', 'kd_dokter', 'no_rawat', 'kd_dokter')
			->select(['dokter.kd_dokter', 'nm_dokter', 'kd_sps']);
	}


}
