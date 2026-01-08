<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AturanPakai extends Model
{
    use HasFactory;
    protected $table = 'aturan_pakai';
    protected $guarded = [];
    public $timestamps = false;
}
