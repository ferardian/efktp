<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahasaPasien extends Model
{
    use HasFactory;
    protected $table = 'bahasa_pasien';
    protected $guarded = [''];
    public $timestamps = false;
}
