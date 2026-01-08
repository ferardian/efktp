<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EfktpPaketObatUmum extends Model
{
	use HasFactory;

	protected $table = "efktp_paket_obat_umum";
	protected $guarded = [];

	public function databarang()
	{
		return $this->belongsTo(DataBarang::class, 'kode_brng', 'kode_brng')
			->select('kode_brng', 'nama_brng', 'kapasitas');
	}
}
