<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjab extends Model
{
    use HasFactory;
    protected $table = 'penjab';
    protected $guarded = [];
    public $timestamps = false;
}
