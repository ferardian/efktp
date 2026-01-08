<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EfktpTemplateRacikan extends Model
{
    use HasFactory;
    protected $table = 'efktp_template_racikan';
    protected $guarded = [''];
    public $timestamps = false;

    function detail()
    {
        return $this->hasMany(EfktpTemplateRacikanDetail::class, 'id_racik', 'id');
    }
    function dokter()
    {
        return $this->belongsTo(Dokter::class, 'kd_dokter', 'kd_dokter');
    }
}
