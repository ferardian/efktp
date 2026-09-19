<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerifikasiPemantauanAnestesi extends Model
{
    protected $table = 'verifikasi_pemantauan_anestesi';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = ['no_rawat', 'tanggal_verifikasi'];

    protected $guarded = [];

    public function regPeriksa(): BelongsTo
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }

    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class, 'kd_dokter', 'kd_dokter');
    }
}
