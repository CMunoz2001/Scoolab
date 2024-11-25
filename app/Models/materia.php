<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class materia extends Model
{
    use HasFactory;

    protected $table = 'materias';

    protected $fillable = ['nombre','profesor_id'];

    public function profesores()
    {
        return $this->belongsTo(Profesores::class, 'profesor_id');
    }

    public function notas()
    {
        return $this->hasMany(Nota::class);
    }

}
