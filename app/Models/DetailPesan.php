<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPesan extends Model
{
    use HasFactory;

    protected $table = 'detailpesan';
    public $timestamps = false;
    protected $guarded = [];

    public function barang()
    {
        return $this->belongsTo(DataBarang::class, 'kode_brng', 'kode_brng');
    }
}
