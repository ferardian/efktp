<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuRole extends Model
{
    protected $table = 'menu_role';
    public $timestamps = false;
    protected $guarded = [];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'id');
    }
}
