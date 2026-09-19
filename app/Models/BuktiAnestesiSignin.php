<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BuktiAnestesiSignin extends Model
{
    protected $table = 'bukti_anestesi_signin';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = ['no_rawat', 'tanggal'];

    protected $guarded = [];

    public function regPeriksa(): BelongsTo
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }

    public function dokterBedah(): BelongsTo
    {
        return $this->belongsTo(Dokter::class, 'kd_dokter_bedah', 'kd_dokter');
    }

    public function dokterAnestesi(): BelongsTo
    {
        return $this->belongsTo(Dokter::class, 'kd_dokter_anestesi', 'kd_dokter');
    }

    public function petugasOk(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'nip_perawat_ok', 'nik');
    }
}
