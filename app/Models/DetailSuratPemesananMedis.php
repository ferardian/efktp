<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailSuratPemesananMedis extends Model
{
    protected $table = 'detail_surat_pemesanan_medis';
    public $timestamps = false;
    protected $guarded = [];

    public function barang()
    {
        return $this->belongsTo(DataBarang::class, 'kode_brng', 'kode_brng');
    }

    public function suratPemesanan()
    {
        return $this->belongsTo(SuratPemesananMedis::class, 'no_pemesanan', 'no_pemesanan');
    }
}
