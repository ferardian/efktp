<?php

namespace App\Models\Lab;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPemeriksaanLab extends Model
{
    use HasFactory, Compoships;

    protected $table = 'detail_periksa_lab';
    protected $guarded = [];
}
