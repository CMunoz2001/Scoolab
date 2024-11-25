<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;

class directivos extends Model implements AuthenticatableContract
{
    use HasFactory, Authenticatable;

    protected $table = 'directivos';

    protected $fillable = ['email', 'password','nombre'];

    protected $hidden = ['password'];
}
