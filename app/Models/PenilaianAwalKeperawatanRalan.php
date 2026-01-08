<?php

namespace App\Models;

use Clockwork\Request\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianAwalKeperawatanRalan extends Model
{
    use HasFactory;
    protected $table = 'penilaian_awal_keperawatan_ralan';
    protected $guarded = [];
    public $timestamps = false;

    function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }

    function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'nip', 'nik');
    }

    function skrining()
    {
        return $this->hasOne(EfktpTindakanResikoJatuh::class, 'no_rawat', 'no_rawat');
    }
}
