<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemeriksaanGigiHasil extends Model
{
    use HasFactory;
    protected $table = 'efktp_pemeriksaan_gigi_hasil';
    protected $guarded = [];

    function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }
    function dokter()
    {
        return $this->belongsTo(Dokter::class, 'kd_dokter', 'kd_dokter');
    }
    function diagnosa()
    {
        return $this->belongsTo(Penyakit::class, 'kd_penyakit', 'kd_penyakit');
    }
    function tindakan()
    {
        return $this->belongsTo(Icd9::class, 'kd_tindakan', 'kode');
    }
}
