<?php

namespace App\Models\Lab;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DetailPeriksaLab extends Model
{
    use HasFactory, Compoships;
    protected $table = 'detail_periksa_lab';
    protected $guarded = [];

    function template(): BelongsTo
    {
        return $this->belongsTo(TemplateLaboratorium::class, 'id_template', 'id_template');
    }
}
