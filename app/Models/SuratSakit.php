<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratSakit extends Model
{
    use HasFactory;
    protected $table = 'suratsakit';
    protected $guarded = [];
    public $timestamps = false;

    function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }
    function pemeriksaanRalan()
    {
        return $this->belongsTo(PemeriksaanRalan::class, 'no_rawat', 'no_rawat');
    }
    function diagnosa()
    {
        return $this->hasMany(DiagnosaPasien::class, 'no_rawat', 'no_rawat');
    }
}
