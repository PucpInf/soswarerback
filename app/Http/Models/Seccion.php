<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Seccion extends Model
{
    protected $table = 'seccion';

    public $timestamps = false;

    protected $fillable = ['id','nombre', 'idDepartamento','presupuesto' ];

    public function departamento(){
        return $this->hasOne('App\Http\Models\Departamento','id','idDepartamento');
    }

    public function cursos(){
        return $this->hasMany('App\Http\Models\Curso','idSeccion','id');
    }

    public function profesores(){
        return $this->hasMany('App\Http\Models\Usuario','idSeccion','id');
    }

    public function horarios(){

      return $this->hasManyThrough(
          'App\Http\Models\Horario',
          'App\Http\Models\Curso',
          'idSeccion', // Foreign key on users table...
          'idCurso', // Foreign key on posts table...
          'id', // Local key on countries table...
          'id' // Local key on users table...
      );

    }
}
