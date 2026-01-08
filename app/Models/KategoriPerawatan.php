<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriPerawatan extends Model
{
    use HasFactory, Compoships;

    protected $table = 'kategori_perawatan';
    protected $primaryKey = 'kd_kategori';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
}
