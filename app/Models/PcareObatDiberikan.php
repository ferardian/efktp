<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PcareObatDiberikan extends Model
{
    use HasFactory;

    protected $table = 'pcare_obat_diberikan';
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = ['no_rawat', 'noKunjungan', 'tgl_perawatan', 'jam', 'kode_brng', 'no_batch', 'no_faktur'];

    public function barang()
    {
        return $this->belongsTo(DataBarang::class, 'kode_brng', 'kode_brng');
    }

    public function mappingPcare()
    {
        return $this->belongsTo(MappingObatPcare::class, 'kode_brng', 'kode_brng');
    }
}
