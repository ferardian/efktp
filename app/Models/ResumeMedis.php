<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class ResumeMedis extends Model
{
    use HasFactory, Compoships;
    protected $table = 'resume_pasien_ranap';
    protected $guarded = [];
    public $timestamps = false;

    function regPeriksa() : BelongsTo
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }
    function dokter() : BelongsTo
    {
     return $this->belongsTo(Dokter::class, 'kd_dokter', 'kd_dokter');
    }
    function pasien() : HasOneThrough
    {
        return $this->hasOneThrough(Pasien::class, RegPeriksa::class, 'no_rawat', 'no_rkm_medis', 'no_rawat', 'no_rkm_medis');
    }

}
