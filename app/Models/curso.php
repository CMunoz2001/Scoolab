<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class curso extends Model
{
    use HasFactory;
    
    protected $table = 'cursos';

    protected $fillable = ['nombre','periodo','profesor_id'];

    public function profesor()
    {
        return $this->belongsTo(Profesor::class);
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class);
    }

    public function materias()
    {
        return $this->hasMany(Materia::class);
    }

}
