<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengkajianPrimerAbcde extends Model
{
    use HasFactory;

    protected $table = 'pengkajian_primer_abcde';
    protected $guarded = [];
    public $timestamps = true;
    protected $primaryKey = 'no_rawat';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $casts = [
        'airway_tindakan' => 'array',
        'breathing_tindakan' => 'array',
        'circulation_tindakan' => 'array',
        'disability_tindakan' => 'array',
        'exposure_tindakan' => 'array',
    ];

    public function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'nip', 'nip');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'nip', 'nik');
    }
}
