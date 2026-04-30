<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;

    // Because of composite primary key, we might need this if we use find()
    // but for simple list/save, it should be fine.
    protected $primaryKey = ['kd_dokter', 'hari_kerja', 'jam_mulai'];

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'kd_dokter', 'kd_dokter');
    }

    public function poliklinik()
    {
        return $this->belongsTo(Poliklinik::class, 'kd_poli', 'kd_poli');
    }
}
