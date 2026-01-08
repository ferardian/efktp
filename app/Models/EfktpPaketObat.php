<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EfktpPaketObat extends Model
{
	use HasFactory, Compoships;

	protected $guarded = [];
	protected $table = 'efktp_paket_obat';

	public function umum()
	{
		return $this->hasMany(EfktpPaketObatUmum::class, 'paket_id');
	}

	public function racikan()
	{
		return $this->hasMany(EfktpPaketObatRacik::class, 'paket_id');
	}

	public function poliklinik()
	{
		return $this->belongsTo(Poliklinik::class, 'kd_poli', 'kd_poli');
	}

	public function metode()
	{
		return $this->belongsTo(MetodeRacik::class, 'kd_racik', 'kd_racik');
	}
}
