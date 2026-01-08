<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EfktpTemplateRacikanDetail extends Model
{
    use HasFactory;
    protected $table = 'efktp_template_racikan_detail';
    protected $guarded = ['id'];
    public $timestamps = false;
    function template()
    {
        return $this->belongsTo(EfktpTemplateRacikan::class, 'id', 'id_racik');
    }
    function barang(){
        return $this->belongsTo(DataBarang::class, 'kode_brng', 'kode_brng');
    }
}
