<?php

namespace App\Models\Lab\Permintaan;

use App\Models\Lab\JnsPerawatanLab;
use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use App\Models\Lab\TemplateLaboratorium;
use App\Models\Lab\Permintaan\PermintaanLab;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailPermintaanLab extends Model
{
    use HasFactory, Compoships;

    protected $table = 'permintaan_detail_permintaan_lab';
    protected $guarded = [];
    public $timestamps = false;
    function permintaan(): BelongsTo
    {
        return $this->belongsTo(PermintaanLab::class, 'noorder', 'noorder');
    }
    function item(): BelongsTo
    {
        return $this->belongsTo(TemplateLaboratorium::class, ['kd_jenis_prw', 'id_template'], ['kd_jenis_prw', 'id_template']);
    }
	function jenis():BelongsTo
	{
		return $this->belongsTo(JnsPerawatanLab::class, 'kd_jenis_prw', 'kd_jenis_prw');
	}
}
