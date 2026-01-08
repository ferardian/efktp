<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResumePasienRanap extends Model
{
    use HasFactory;
    protected $table = 'resume_pasien_ranap';
    protected $guarded = [];
    public $timestamps = false;
}
