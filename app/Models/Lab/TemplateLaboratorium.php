<?php

namespace App\Models\Lab;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateLaboratorium extends Model
{
    use HasFactory, Compoships;

    protected $table = 'template_laboratorium';
    protected $primaryKey = 'id_template';
    protected $guarded = [];
    public $timestamps = false;

    public function jenisPerawatan()
    {
        return $this->belongsTo(JnsPerawatanLab::class, 'kd_jenis_prw', 'kd_jenis_prw');
    }

    public function detail()
    {
        return $this->hasMany(DetailPemeriksaanLab::class, 'id_template', 'id_template');
    }
}
