<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetPenjualan extends Model
{
    use HasFactory;

    protected $table = 'setpenjualan';
    protected $primaryKey = 'kdjns';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $guarded = [];

    public function jenis()
    {
        return $this->belongsTo(Jenis::class, 'kdjns', 'kdjns');
    }
}
