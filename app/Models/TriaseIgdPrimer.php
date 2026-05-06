<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TriaseIgdPrimer extends Model
{
    use HasFactory;

    protected $table = 'data_triase_igdprimer';
    protected $guarded = [];
    public $timestamps = false;
    protected $primaryKey = 'no_rawat';
    protected $keyType = 'string';

    public function triase()
    {
        return $this->belongsTo(TriaseIgd::class, 'no_rawat', 'no_rawat');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'nik', 'nik');
    }
}
