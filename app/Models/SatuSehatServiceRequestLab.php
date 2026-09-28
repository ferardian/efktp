<?php

namespace App\Models;

use App\Models\Lab\TemplateLaboratorium;
use App\Models\Lab\JnsPerawatanLab;
use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatuSehatServiceRequestLab extends Model
{
    use HasFactory, Compoships;

    protected $table = 'satu_sehat_servicerequest_lab';
    protected $primaryKey = ['noorder', 'kd_jenis_prw', 'id_template'];
    public $incrementing = false;
    protected $guarded = [];
    public $timestamps = false;

    public function templateLaboratorium()
    {
        return $this->belongsTo(TemplateLaboratorium::class, 'id_template', 'id_template');
    }

    public function jenisPerawatan()
    {
        return $this->belongsTo(JnsPerawatanLab::class, 'kd_jenis_prw', 'kd_jenis_prw');
    }
}
