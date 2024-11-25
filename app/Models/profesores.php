<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class profesores extends Model implements Authenticatable
{
    use HasFactory;
    use AuthenticatableTrait;

    protected $table = 'profesores';

    protected $fillable = ['tipodoc','doc','nombre','direccion','telefono','email','direccion','contrasena','fechanac','rol','imagen'];


    public function materias()
    {
        return $this->hasMany(Materia::class, 'profesor_id');
    }

    public function cursos()
    {
        return $this->hasMany(Curso::class, 'profesor_id');
    }

}
