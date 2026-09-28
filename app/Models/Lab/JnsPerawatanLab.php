<?php

namespace App\Models\Lab;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use App\Models\Lab\DetailPemeriksaanLab;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JnsPerawatanLab extends Model
{
    use Compoships;
    use HasFactory;

    protected $table = 'jns_perawatan_lab';
    protected $primaryKey = 'kd_jenis_prw';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];
    public $timestamps = false;

    public function penjab()
    {
        return $this->belongsTo(\App\Models\Penjab::class, 'kd_pj', 'kd_pj');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(DetailPemeriksaanLab::class, 'kd_jenis_prw', 'kd_jenis_prw');
    }

    public function template(): HasMany
    {
        return $this->hasMany(TemplateLaboratorium::class, 'kd_jenis_prw', 'kd_jenis_prw')->orderBy('urut', 'asc')->orderBy('id_template', 'asc');
    }
}
