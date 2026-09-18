<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianPreOperasi extends Model
{
	use HasFactory, Compoships;

	protected $table = 'penilaian_pre_operasi';
	protected $primaryKey = 'no_rawat';
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
}
