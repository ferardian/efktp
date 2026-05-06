<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Awobaz\Compoships\Compoships;

class TriaseIgdDetailSkala2 extends Model
{
    use HasFactory, Compoships;

    protected $table = 'data_triase_igddetail_skala2';
    protected $guarded = [];
    public $timestamps = false;
    protected $primaryKey = ['no_rawat', 'kode_skala2'];
    public $incrementing = false;

    public function master()
    {
        return $this->belongsTo(MasterTriaseSkala2::class, 'kode_skala2', 'kode_skala2');
    }
}
