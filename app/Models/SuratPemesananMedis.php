<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratPemesananMedis extends Model
{
    protected $table = 'surat_pemesanan_medis';
    protected $primaryKey = 'no_pemesanan';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $guarded = [];

    public function suplier()
    {
        return $this->belongsTo(DataSuplier::class, 'kode_suplier', 'kode_suplier');
    }

    public function detail()
    {
        return $this->hasMany(DetailSuratPemesananMedis::class, 'no_pemesanan', 'no_pemesanan');
    }
}
