<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ConcursoPorProfesor extends Model
{
    protected $table = 'concursoPorProfesor';

    public $timestamps = false;

    public function concurso(){
      return $this->hasOne('App\Http\Models\ConcursoNivel','id','idConcurso');
  	}
}
