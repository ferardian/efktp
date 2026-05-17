<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianAwalKeperawatanRanap extends Model
{
    use HasFactory;

    protected $table = 'penilaian_awal_keperawatan_ranap';
    protected $primaryKey = 'no_rawat';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];
    public $timestamps = false;

    public function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }

    public function pegawai1()
    {
        return $this->belongsTo(Pegawai::class, 'nip1', 'nik');
    }

    public function pegawai2()
    {
        return $this->belongsTo(Pegawai::class, 'nip2', 'nik');
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'kd_dokter', 'kd_dokter');
    }
}
