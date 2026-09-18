<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerencanaanPemulangan extends Model
{
	use HasFactory;

	protected $table = 'perencanaan_pemulangan';
	protected $primaryKey = 'no_rawat';
	public $incrementing = false;
	protected $keyType = 'string';
	public $timestamps = false;

	protected $guarded = [];

	public function regPeriksa()
	{
		return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
	}

	public function petugas()
	{
		return $this->belongsTo(Petugas::class, 'nip', 'nip');
	}

	public function buktiSaksi()
	{
		return $this->hasOne(BuktiPerencanaanPemulanganSaksiKeluarga::class, 'no_rawat', 'no_rawat');
	}
}
