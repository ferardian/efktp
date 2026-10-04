<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutasiBarang extends Model
{
    use HasFactory;

    protected $table = 'mutasibarang';
    public $timestamps = false;
    protected $guarded = [];

    public function barang()
    {
        return $this->belongsTo(DataBarang::class, 'kode_brng', 'kode_brng');
    }

    public function bangsalDari()
    {
        return $this->belongsTo(Bangsal::class, 'kd_bangsaldari', 'kd_bangsal');
    }

    public function bangsalKe()
    {
        return $this->belongsTo(Bangsal::class, 'kd_bangsalke', 'kd_bangsal');
    }
}
