<?php

namespace App\Models\Lab\Permintaan;

use Awobaz\Compoships\Compoships;
use App\Models\Lab\JnsPerawatanLab;
use Illuminate\Database\Eloquent\Model;
use App\Models\Lab\Permintaan\DetailPermintaanLab;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermintaanPemeriksaanLab extends Model
{
    use Compoships;
    use HasFactory;

    protected $table = 'permintaan_pemeriksaan_lab';
    protected $guarded = [];
    public $timestamps = false;

    function jenis(): BelongsTo
    {
        return $this->belongsTo(JnsPerawatanLab::class, 'kd_jenis_prw', 'kd_jenis_prw');
    }
    function detail(): HasMany
    {
        return $this->hasMany(DetailPermintaanLab::class, ['noorder', 'kd_jenis_prw'], ['noorder', 'kd_jenis_prw']);
    }
}
