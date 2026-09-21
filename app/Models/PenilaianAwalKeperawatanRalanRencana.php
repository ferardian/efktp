<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianAwalKeperawatanRalanRencana extends Model
{
    use HasFactory;

    protected $table = 'penilaian_awal_keperawatan_ralan_rencana';
    public $timestamps = false;
    public $incrementing = false;
    protected $guarded = [];

    public function masterRencana()
    {
        return $this->belongsTo(MasterRencanaKeperawatan::class, 'kode_rencana', 'kode_rencana');
    }

    public function penilaianAwal()
    {
        return $this->belongsTo(PenilaianAwalKeperawatanRalan::class, 'no_rawat', 'no_rawat');
    }
}
