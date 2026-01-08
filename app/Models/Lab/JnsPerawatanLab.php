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
    protected $guarded = [];
    public $timestamps = false;

    public function detail(): HasMany
    {
        return $this->hasMany(DetailPemeriksaanLab::class, 'kd_jenis_prw', 'kd_jenis_prw');
    }
    function template(): HasMany
    {
        return $this->hasMany(TemplateLaboratorium::class, 'kd_jenis_prw', 'kd_jenis_prw');
    }
}
