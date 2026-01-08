<?php

namespace App\Models;

use App\Models\MetodeRacik;
use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use App\Models\ResepDokterRacikanDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ResepDokterRacikan extends Model
{
    use HasFactory, Compoships;
    protected $table = 'resep_dokter_racikan';
    protected $guarded = [];
    public $timestamps = false;

    function detail()
    {
        return $this->hasMany(ResepDokterRacikanDetail::class, ['no_racik', 'no_resep'], ['no_racik', 'no_resep']);
    }
    function metode()
    {
        return $this->belongsTo(MetodeRacik::class, 'kd_racik', 'kd_racik');
    }
}
