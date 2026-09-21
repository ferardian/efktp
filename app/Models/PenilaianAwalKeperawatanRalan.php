<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianAwalKeperawatanRalan extends Model
{
    use HasFactory;

    protected $table = 'penilaian_awal_keperawatan_ralan';
    protected $primaryKey = 'no_rawat';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];
    public $timestamps = false;

    public function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'nip', 'nik');
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'nip', 'nip');
    }

    public function skrining()
    {
        return $this->hasOne(EfktpTindakanResikoJatuh::class, 'no_rawat', 'no_rawat');
    }

    public function masalah()
    {
        return $this->hasMany(PenilaianAwalKeperawatanRalanMasalah::class, 'no_rawat', 'no_rawat');
    }

    public function rencanaKeperawatan()
    {
        return $this->hasMany(PenilaianAwalKeperawatanRalanRencana::class, 'no_rawat', 'no_rawat');
    }
}
