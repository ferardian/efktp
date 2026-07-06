<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataBatch extends Model
{
    use HasFactory;

    protected $table = 'data_batch';
    public $timestamps = false;
    protected $guarded = [];

    public function barang()
    {
        return $this->belongsTo(DataBarang::class, 'kode_brng', 'kode_brng');
    }
}
