<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';
    public $timestamps = false;
    protected $guarded = [];

    function dokter()
    {
        return $this->hasOne(Dokter::class, 'kd_dokter', 'nik');
    }
    function departemen()
    {
        return $this->belongsTo(Departemen::class, 'departemen', 'dep_id');
    }
}
