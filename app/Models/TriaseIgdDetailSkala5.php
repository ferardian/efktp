<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Awobaz\Compoships\Compoships;

class TriaseIgdDetailSkala5 extends Model
{
    use HasFactory, Compoships;

    protected $table = 'data_triase_igddetail_skala5';
    protected $guarded = [];
    public $timestamps = false;
    protected $primaryKey = ['no_rawat', 'kode_skala5'];
    public $incrementing = false;

    public function master()
    {
        return $this->belongsTo(MasterTriaseSkala5::class, 'kode_skala5', 'kode_skala5');
    }
}
