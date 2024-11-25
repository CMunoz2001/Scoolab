<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class nota extends Model
{
    use HasFactory;

    protected $table = 'notas';

    protected $fillable = ['materia_id','estudiante_id','nota1'];

    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'curso_id');
    }

    public function materias()
    {
        return $this->hasMany(Materia::class, 'curso_id'); // Si agregas una columna curso_id en materias
    }

}
