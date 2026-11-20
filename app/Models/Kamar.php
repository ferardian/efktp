<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    use HasFactory;

    protected $table = 'kamar';
    protected $primaryKey = 'kd_kamar';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];
    public $timestamps = false;

    public function bangsal()
    {
        return $this->belongsTo(Bangsal::class, 'kd_bangsal', 'kd_bangsal');
    }

    public function kamarInap()
    {
        return $this->hasMany(KamarInap::class, 'kd_kamar', 'kd_kamar');
    }

    public function pasienAktif()
    {
        return $this->hasOne(KamarInap::class, 'kd_kamar', 'kd_kamar')
            ->where('stts_pulang', '-')
            ->latest('tgl_masuk');
    }
}
