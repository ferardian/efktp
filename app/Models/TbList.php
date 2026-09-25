<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbList extends Model
{
    use HasFactory;

    protected $table = 'tb_list';
    protected $primaryKey = 'kd_list';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'kd_list',
        'date_list',
        'kd_layanan',
        'antrian',
        'kd_loket',
        'status',
    ];
}
