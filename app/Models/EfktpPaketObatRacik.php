<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EfktpPaketObatRacik extends Model
{
	use HasFactory;

	protected $table = 'efktp_paket_obat_racik';

	protected $guarded = [];

	function template()
	{
		return $this->belongsTo(EfktpTemplateRacikan::class, 'template_id', 'id');
	}

	function metode()
	{
		return $this->belongsTo(MetodeRacik::class, 'kd_racik', 'kd_racik');
	}
}
