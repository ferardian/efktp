<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MappingPoliklinikPcare extends Model
{
    use HasFactory;
    protected $table = 'maping_poliklinik_pcare';
    protected $primaryKey = 'kd_poli_rs';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];
    public $timestamps = false;

    function poliklinik()
    {
        return $this->belongsTo(Poliklinik::class, 'kd_poli_rs', 'kd_poli');
    }
}
