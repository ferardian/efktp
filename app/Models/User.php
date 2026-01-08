<?php

namespace App\Models;

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticable;

class User extends Authenticable
{
    use HasFactory;
    protected $table = 'user';

    // primary key wajib diinisiasi untuk loloskan ke midleware
    protected $primaryKey = 'id_user';
}
