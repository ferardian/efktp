<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersetujuanPenolakanRujukan extends Model
{
    use HasFactory;

    protected $table = 'persetujuan_penolakan_rujukan';
    protected $primaryKey = 'no_surat';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

    public function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'kd_dokter', 'kd_dokter');
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'nip', 'nip');
    }

    public function rujukan()
    {
        return $this->hasOne(Rujuk::class, 'no_rawat', 'no_rawat');
    }
}
