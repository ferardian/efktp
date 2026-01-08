<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EfktpTindakanResikoJatuh extends Model
{
    use HasFactory;
    protected $table = 'efktp_tindakan_resiko_jatuh';
    protected $guarded = ['id'];

    function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat'); //
    }
    function penilaianAwal()
    {
        return $this->belongsTo(PenilaianAwalKeperawatanRalan::class, 'no_rawat', 'no_rawat'); //
    }
    function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'nip', 'nik');
    }
}
