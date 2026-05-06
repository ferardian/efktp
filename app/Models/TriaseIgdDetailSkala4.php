<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Awobaz\Compoships\Compoships;

class TriaseIgdDetailSkala4 extends Model
{
    use HasFactory, Compoships;

    protected $table = 'data_triase_igddetail_skala4';
    protected $guarded = [];
    public $timestamps = false;
    protected $primaryKey = ['no_rawat', 'kode_skala4'];
    public $incrementing = false;

    public function master()
    {
        return $this->belongsTo(MasterTriaseSkala4::class, 'kode_skala4', 'kode_skala4');
    }
}
