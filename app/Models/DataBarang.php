<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataBarang extends Model
{
    use HasFactory;
    protected $table = 'databarang';
    protected $guarded = [];
    public $timestamps;

    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'kode_sat', 'kode_sat');
    }
    public function satuanBesar()
    {
        return $this->belongsTo(SatuanBesar::class, 'kode_satbesar', 'kode_sat');
    }
    public function jenis()
    {
        return $this->belongsTo(Jenis::class, 'kdjns', 'kdjns');
    }

    public function golongan()
    {
        return $this->belongsTo(GolonganBarang::class, 'kode_golongan', 'kode');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriBarang::class, 'kode_kategori', 'kode');
    }

    public function industri()
    {
        return $this->belongsTo(IndustriFarmasi::class, 'kode_industri', 'kode_industri');
    }
    public function mapping()
    {
        return $this->belongsTo(MappingObatPcare::class, 'kode_brng', 'kode_brng');
    }
    public function gudangBarang()
    {
        return $this->hasMany(GudangBarang::class, 'kode_brng', 'kode_brng');
    }
}
