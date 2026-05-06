<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Awobaz\Compoships\Compoships;

class TriaseIgdDetailSkala3 extends Model
{
    use HasFactory, Compoships;

    protected $table = 'data_triase_igddetail_skala3';
    protected $guarded = [];
    public $timestamps = false;
    protected $primaryKey = ['no_rawat', 'kode_skala3'];
    public $incrementing = false;

    public function master()
    {
        return $this->belongsTo(MasterTriaseSkala3::class, 'kode_skala3', 'kode_skala3');
    }
}
