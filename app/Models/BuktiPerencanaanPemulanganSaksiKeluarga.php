<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuktiPerencanaanPemulanganSaksiKeluarga extends Model
{
	use HasFactory;

	protected $table = 'bukti_perencanaan_pemulangan_saksikeluarga';
	protected $primaryKey = 'no_rawat';
	public $incrementing = false;
	protected $keyType = 'string';
	public $timestamps = false;

	protected $guarded = [];

	public function perencanaan()
	{
		return $this->belongsTo(PerencanaanPemulangan::class, 'no_rawat', 'no_rawat');
	}
}
