<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbList extends Model
{
    use HasFactory;

    protected $table = 'tb_list';
    protected $primaryKey = 'kd_list';
    public $timestamps = false;

    protected $fillable = [
        'date_list',
        'kd_layanan',
        'antrian',
        'jam',
        'keterangan',
    ];
}
