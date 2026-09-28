<?php

namespace App\Models;

use App\Models\Lab\TemplateLaboratorium;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatuSehatMappingLab extends Model
{
    use HasFactory;

    protected $table = 'satu_sehat_mapping_lab';
    protected $primaryKey = 'id_template';
    public $incrementing = false;
    protected $keyType = 'int';
    protected $guarded = [];
    public $timestamps = false;

    public function templateLaboratorium()
    {
        return $this->belongsTo(TemplateLaboratorium::class, 'id_template', 'id_template');
    }
}
