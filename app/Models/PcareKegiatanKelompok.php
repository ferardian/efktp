<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PcareKegiatanKelompok extends Model
{
    use HasFactory;

    protected $table = 'pcare_kegiatan_kelompok';
    protected $primaryKey = 'eduId';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $guarded = [];

    public function peserta()
    {
        return $this->hasMany(PcarePesertaKegiatanKelompok::class, 'eduId', 'eduId');
    }
}
