<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetHargaObat extends Model
{
    use HasFactory;

    protected $table = 'set_harga_obat';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];
}
