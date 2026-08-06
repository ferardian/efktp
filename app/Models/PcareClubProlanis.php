<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PcareClubProlanis extends Model
{
    use HasFactory;

    protected $table = 'pcare_club_prolanis';
    protected $primaryKey = 'clubId';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];
}
