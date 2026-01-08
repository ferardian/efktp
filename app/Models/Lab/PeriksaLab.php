<?php

namespace App\Models\Lab;

use App\Models\Pasien;
use App\Models\Pegawai;
use App\Models\RegPeriksa;
use Awobaz\Compoships\Compoships;
use Awobaz\Compoships\Database\Eloquent\Relations\HasMany as RelationsHasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class PeriksaLab extends Model
{
    use HasFactory, Compoships;

    protected $guarded = [];
    protected $table = 'periksa_lab';
    public $timestamps = false;

    function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'nip', 'nik')->select(['id', 'nik', 'nama', 'jk']);
    }
    function jenis(): BelongsTo
    {
        return $this->belongsTo(JnsPerawatanLab::class, 'kd_jenis_prw', 'kd_jenis_prw');
    }

    function detail(): RelationsHasMany
    {
        return $this->hasMany(DetailPeriksaLab::class, ['no_rawat', 'kd_jenis_prw', 'tgl_periksa'], ['no_rawat', 'kd_jenis_prw', 'tgl_periksa']);
    }
}
