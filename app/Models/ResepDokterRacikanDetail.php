<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResepDokterRacikanDetail extends Model
{
    use HasFactory, Compoships;
    protected $table = 'resep_dokter_racikan_detail';
    protected $guarded = [];
    public $timestamps = false;
    function obat()
    {
        return $this->belongsTo(DataBarang::class, 'kode_brng', 'kode_brng');
    }
}
