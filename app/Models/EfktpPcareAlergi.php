<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EfktpPcareAlergi extends Model
{
    use HasFactory;
    protected $table = 'efktp_alergi';
    protected $guarded = ['id'];

    function pasien()
    {
        return $this->belongsTo(Pasien::class, 'no_rkm_medis', 'no_rkm_medis');
    }
}
