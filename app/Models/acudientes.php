<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class acudientes extends Model implements Authenticatable
{
    use HasFactory;
    use AuthenticatableTrait;

    protected $table = 'acudientes';
    public $timestamps = false;

    protected $fillable = [
        'doc',
        'nombre',
        'direccion',
        'telefono',
        'email',
        'password',
        'imagen',
    ];

    public function estudiantes()
    {
        return $this->hasMany(estudiantes::class, 'acudiente_id');
    }
}
