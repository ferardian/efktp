<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangBarang extends Model
{
    use HasFactory;
    protected $table = 'gudangbarang';
    protected $guarded = [];
    public $timestamps = false;

    public function lokasi()
    {
        return $this->belongsTo(Bangsal::class, 'kd_bangsal', 'kd_bangsal');
    }
    public function apotek()
    {
        return $this->hasOne(Bangsal::class, 'kd_bangsal', 'kd_bangsal')->where('kd_bangsal', 'AP');
    }
}
