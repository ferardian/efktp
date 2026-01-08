<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EfktpKategoriBerkasPenunjang extends Model
{
    use HasFactory;
    protected $table = 'efktp_kategori_upload_penunjang';
    protected $guarded = ['id'];
}
