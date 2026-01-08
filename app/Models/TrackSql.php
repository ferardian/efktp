<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackSql extends Model
{
    use HasFactory;
    protected $table = 'trackersql';
    protected $guarded = [];
    public $timestamps = false;
}
