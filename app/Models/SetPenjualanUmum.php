<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetPenjualanUmum extends Model
{
    use HasFactory;

    protected $table = 'setpenjualanumum';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];
}
