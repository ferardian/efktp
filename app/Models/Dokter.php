<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;
    protected $table = 'dokter';
    protected $guarded = [];
    public $timestamps = false;

    function maping()
    {
        return $this->hasOne(MapingDokterPcare::class, 'kd_dokter', 'kd_dokter');
    }
    function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'kd_dokter', 'nik');
    }
}
