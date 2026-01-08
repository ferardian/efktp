<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemeriksaanGigi extends Model
{
    use HasFactory;

    protected $table = 'efktp_pemeriksaan_gigi';
    protected $guarded = [];

    function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }
    function dokter()
    {
        return $this->belongsTo(Dokter::class, 'kd_dokter', 'kd_dokter');
    }
    function hasil()
    {
        return $this->hasMany(PemeriksaanGigiHasil::class, 'no_rawat', 'no_rawat');
    }
}
