<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Awobaz\Compoships\Compoships;

class TriaseIgdDetailSkala1 extends Model
{
    use HasFactory, Compoships;

    protected $table = 'data_triase_igddetail_skala1';
    protected $guarded = [];
    public $timestamps = false;
    protected $primaryKey = ['no_rawat', 'kode_skala1'];
    public $incrementing = false;

    public function master()
    {
        return $this->belongsTo(MasterTriaseSkala1::class, 'kode_skala1', 'kode_skala1');
    }
}
