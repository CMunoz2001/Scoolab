<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class estudiantes extends Model implements Authenticatable // Cambiar "estudiantes" a "Estudiante" y quitar el segundo "extends"
{
    use HasFactory, AuthenticatableTrait; // Puedes combinar ambas sentencias "use"

    protected $table = 'estudiantes';

    protected $fillable = [
        'tipodoc',
        'doc',
        'nombre',
        'direccion',
        'telefono',
        'email',
        'password',
        'fechanac',
        'imagen',
        'curso_id',
        'acudiente_id'
    ];

    public function cursos()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    public function acudientes()
    {
        return $this->belongsTo(acudientes::class, 'acudiente_id');
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'estudiante_id');
    }

    public function notas()
    {
        return $this->hasMany(Nota::class, 'estudiante_id');
    }

    public function getAuthPassword()
    {
        return $this->password;
    }
}
