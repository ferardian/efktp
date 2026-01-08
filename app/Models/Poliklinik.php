<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poliklinik extends Model
{
    use HasFactory;
    protected $table = 'poliklinik';
    protected $guarded = [];
    protected $hidden = ['laravel_through_key', 'registrasi', 'registrasilama'];
    public $timestamps = false;

    function maping()
    {
        return $this->hasOne(MappingPoliklinikPcare::class, 'kd_poli_rs', 'kd_poli');
    }

    function scopeActive($query)
    {
        return $query->where('status', '1')
            ->whereNot('nm_poli', '-');
    }

}
