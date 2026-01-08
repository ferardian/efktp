<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalDetail extends Model
{
    use HasFactory, Compoships;

    protected $table = 'jurnal_detail';
    protected $primaryKey = 'no_jurnal';
    protected $guarded = [];
    public $timestamps = false;

    public function jurnal()
    {
        return $this->belongsTo(Jurnal::class);
    }
}
