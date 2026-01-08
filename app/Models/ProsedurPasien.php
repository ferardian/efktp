<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProsedurPasien extends Model
{
    use HasFactory;
    protected $table = 'prosedur_pasien';
    protected $guarded = [];
    public $timestamps = false;

    function icd9()
    {
        return $this->belongsTo(Icd9::class, 'kode', 'kode');
    }
}
