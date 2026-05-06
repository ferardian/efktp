<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TriaseIgd extends Model
{
    use HasFactory;

    protected $table = 'data_triase_igd';
    protected $guarded = [];
    public $timestamps = false;
    protected $primaryKey = 'no_rawat';
    protected $keyType = 'string';

    public function primer()
    {
        return $this->hasOne(TriaseIgdPrimer::class, 'no_rawat', 'no_rawat');
    }

    public function sekunder()
    {
        return $this->hasOne(TriaseIgdSekunder::class, 'no_rawat', 'no_rawat');
    }

    public function skala1()
    {
        return $this->hasMany(TriaseIgdDetailSkala1::class, 'no_rawat', 'no_rawat');
    }

    public function skala2()
    {
        return $this->hasMany(TriaseIgdDetailSkala2::class, 'no_rawat', 'no_rawat');
    }

    public function skala3()
    {
        return $this->hasMany(TriaseIgdDetailSkala3::class, 'no_rawat', 'no_rawat');
    }

    public function skala4()
    {
        return $this->hasMany(TriaseIgdDetailSkala4::class, 'no_rawat', 'no_rawat');
    }

    public function skala5()
    {
        return $this->hasMany(TriaseIgdDetailSkala5::class, 'no_rawat', 'no_rawat');
    }
}
