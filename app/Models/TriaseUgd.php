<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TriaseUgd extends Model
{
    use HasFactory;

    protected $table = 'data_triase_ugd';
    protected $guarded = [];
    public $timestamps = true;
    protected $primaryKey = 'no_rawat';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $casts = [
        'survey_primer' => 'array',
        'body_map_points' => 'array',
    ];

    public function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'nip', 'nip');
    }
}
