<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianAwalKeperawatanRalanMasalah extends Model
{
    use HasFactory;

    protected $table = 'penilaian_awal_keperawatan_ralan_masalah';
    public $timestamps = false;
    public $incrementing = false;
    protected $guarded = [];

    public function masterMasalah()
    {
        return $this->belongsTo(MasterMasalahKeperawatan::class, 'kode_masalah', 'kode_masalah');
    }

    public function penilaianAwal()
    {
        return $this->belongsTo(PenilaianAwalKeperawatanRalan::class, 'no_rawat', 'no_rawat');
    }
}
