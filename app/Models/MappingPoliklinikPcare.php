<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MappingPoliklinikPcare extends Model
{
    use HasFactory;
    protected $table = 'maping_poliklinik_pcare';
    protected $guarded = [];
    public $timestamp = false;

    function poliklinik()
    {
        return $this->belongsTo(Poliklinik::class, 'kd_poli_rs', 'kd_poli');
    }
}
