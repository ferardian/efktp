<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersetujuanPenolakanTindakan extends Model
{
	use HasFactory;

	protected $table = 'persetujuan_penolakan_tindakan';
	protected $primaryKey = 'no_pernyataan';
	public $incrementing = false;
	protected $keyType = 'string';
	public $timestamps = false;

	protected $guarded = [];

	public function regPeriksa()
	{
		return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
	}

	public function dokter()
	{
		return $this->belongsTo(Dokter::class, 'kd_dokter', 'kd_dokter');
	}

	public function petugas()
	{
		return $this->belongsTo(Petugas::class, 'nip', 'nip');
	}

	public function buktiPenerimaInformasi()
	{
		return $this->hasOne(BuktiPersetujuanPenolakanTindakanPenerimaInformasi::class, 'no_pernyataan', 'no_pernyataan');
	}

	public function buktiSaksiKeluarga()
	{
		return $this->hasOne(BuktiPersetujuanPenolakanTindakanSaksiKeluarga::class, 'no_pernyataan', 'no_pernyataan');
	}
}
