<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;

    protected $table = 'pemesanan';
    protected $primaryKey = 'no_faktur';
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
        return $this->hasMany(DetailPesan::class, 'no_faktur', 'no_faktur');
    }

    public function bangsal()
    {
        return $this->belongsTo(Bangsal::class, 'kd_bangsal', 'kd_bangsal');
    }
}
