<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataSuplier extends Model
{
    use HasFactory;

    protected $table = 'datasuplier';
    protected $primaryKey = 'kode_suplier';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $guarded = [];
}
