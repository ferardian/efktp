<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResepObat extends Model
{
    use HasFactory;
    protected $table = 'resep_obat';
    protected $guarded = [];
    public $timestamps = false;
    protected $with =[
        'regPeriksa.pasien',
        'regPeriksa.penjab',
        'regPeriksa.poliklinik',
        'regPeriksa.dokter',
        'dokter',
        'resepDokter.obat.satuan',
        'resepRacikan.detail.obat.satuan',
        'resepRacikan.metode',
    ];

    function resepDokter()
    {
        return $this->hasMany(ResepDokter::class, 'no_resep', 'no_resep');
    }
    function resepRacikan()
    {
        return $this->hasMany(ResepDokterRacikan::class, 'no_resep', 'no_resep');
    }
    function dokter() : BelongsTo
    {
        return $this->belongsTo(Dokter::class, 'kd_dokter', 'kd_dokter');
    }
    function regPeriksa() : BelongsTo
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }

    function scopeByNoRawat($query, $no_rawat)
    {
        return $this->where('no_rawat',$no_rawat);
    }
    function scopeByNoResep($query, $no_resep)
    {
        return $this->where('no_resep',$no_resep);
    }
}
