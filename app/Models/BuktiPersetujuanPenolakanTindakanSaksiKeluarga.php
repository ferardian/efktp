<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuktiPersetujuanPenolakanTindakanSaksiKeluarga extends Model
{
	use HasFactory;

	protected $table = 'bukti_persetujuan_penolakan_tindakan_saksikeluarga';
	protected $primaryKey = 'no_pernyataan';
	public $incrementing = false;
	protected $keyType = 'string';
	public $timestamps = false;

	protected $guarded = [];

	public function persetujuan()
	{
		return $this->belongsTo(PersetujuanPenolakanTindakan::class, 'no_pernyataan', 'no_pernyataan');
	}
}
