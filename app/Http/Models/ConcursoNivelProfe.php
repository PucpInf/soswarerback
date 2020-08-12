<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ConcursoNivelProfe extends Model
{
    protected $table = 'concursoPorProfesor';

    public $timestamps = false;
    
    public function user() {
        return $this->belongsTo('\App\Http\Models\Usuario','idUsuario','id')->first();
      }
}
