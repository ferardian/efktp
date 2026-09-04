<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailJual extends Model
{
    use HasFactory;

    protected $table = 'detailjual';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'nota_jual', 'nota_jual');
    }

    public function barang()
    {
        return $this->belongsTo(DataBarang::class, 'kode_brng', 'kode_brng');
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'kode_sat', 'kode_sat');
    }
}
