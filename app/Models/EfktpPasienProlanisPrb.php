<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EfktpPasienProlanisPrb extends Model
{
    use HasFactory;

    protected $table = 'efktp_pasien_prolanis_prb';
    protected $guarded = [];

    protected $casts = [
        'is_prolanis' => 'boolean',
        'is_prb'      => 'boolean',
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'no_peserta', 'no_peserta');
    }
}

