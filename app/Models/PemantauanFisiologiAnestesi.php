<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PemantauanFisiologiAnestesi extends Model
{
    protected $table = 'pemantauan_fisiologi_anestesi';
    public $timestamps = false;

    protected $guarded = [];

    public function regPeriksa(): BelongsTo
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }
}
