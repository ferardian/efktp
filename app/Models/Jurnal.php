<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    use HasFactory, Compoships;
    protected $table = 'jurnal';
    protected $primaryKey = 'no_jurnal';
    protected $keyType = 'string';
    protected $guarded = [];
    public $incrementing = false;

    public function detail()
    {
        return $this->hasMany(JurnalDetail::class);
    }
}
