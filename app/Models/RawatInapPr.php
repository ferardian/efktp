<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawatInapPr extends Model
{
    use HasFactory, Compoships;

    protected $table = 'rawat_inap_pr';
    protected $guarded = [];
    public $timestamps = false;

    public function tindakan()
    {
        return $this->belongsTo(JenisPerawatanInap::class, 'kd_jenis_prw', 'kd_jenis_prw');
    }

    public function petugas()
    {
        return $this->belongsTo(Pegawai::class, 'nip', 'nik');
    }

    public function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }
}
