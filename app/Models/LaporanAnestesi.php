<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanAnestesi extends Model
{
	use HasFactory, Compoships;

	protected $table = 'laporan_anestesi';
	protected $primaryKey = 'no_rawat';
	public $incrementing = false;
	protected $keyType = 'string';
	public $timestamps = false;

	protected $guarded = [];

	public function regPeriksa()
	{
		return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
	}

	public function dokterAnestesi()
	{
		return $this->belongsTo(Dokter::class, 'dokter_anestesi', 'kd_dokter');
	}

	public function dokterOperator1()
	{
		return $this->belongsTo(Dokter::class, 'operator1', 'kd_dokter');
	}

	public function dokterOperator2()
	{
		return $this->belongsTo(Dokter::class, 'operator2', 'kd_dokter');
	}

	public function asisten()
	{
		return $this->belongsTo(Petugas::class, 'asisten_operator', 'nip');
	}

	public function penata()
	{
		return $this->belongsTo(Petugas::class, 'penata_anestesi', 'nip');
	}

	public function petugasRecovery()
	{
		return $this->belongsTo(Petugas::class, 'nip_recovery_room', 'nip');
	}
}
