<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPerawatan extends Model
{
    use HasFactory, Compoships;

    protected $table = 'jns_perawatan';
    protected $primaryKey = 'kd_jenis_prw';
    protected $keyType = 'string';
    public $timestamps = false;
    protected $guarded = [];

    public function kategori()
    {
        return $this->belongsTo(KategoriPerawatan::class, 'kd_kategori', 'kd_kategori');
    }
    public function penjab()
    {
        return $this->belongsTo(Penjab::class, 'kd_pj', 'kd_pj');
    }
    public function poliklinik()
    {
        return $this->belongsTo(Poliklinik::class, 'kd_poli', 'kd_poli');
    }
}

