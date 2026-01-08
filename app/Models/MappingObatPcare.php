<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MappingObatPcare extends Model
{
    use HasFactory;
    protected $table = 'maping_obat_pcare';
    protected $guarded = [];
    public $timestamps = false;

    function obat()
    {
        return $this->belongsTo(DataBarang::class, 'kode_brng', 'kode_brng');
    }
}
