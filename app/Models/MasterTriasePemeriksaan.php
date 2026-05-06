<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterTriasePemeriksaan extends Model
{
    use HasFactory;

    protected $table = 'master_triase_pemeriksaan';
    protected $primaryKey = 'kode_pemeriksaan';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'kode_pemeriksaan',
        'nama_pemeriksaan'
    ];
}
