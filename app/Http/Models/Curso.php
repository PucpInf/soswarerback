<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $table = 'curso';

    protected $fillable = [ 'id',
                            'codigo',
                            'nombre',
                            'idSeccion',
                            'creditosTot',
                            'credTeor',
                            'credPrac',
                            'idFacultad'
                        ];

    public $timestamps = false;

    public function seccion(){
        return $this->hasOne('App\Http\Models\Seccion','id','idSeccion');
    }

    public function facultad(){
        return $this->hasOne('App\Http\Models\Facultad','id','idFacultad');
    }

    public function tipo(){
        return $this->hasOne('App\Http\Models\TipoCurso','id','idTipoCurso');
    }

    public function horarios(){
      return $this->hasMany('App\Http\Models\Horario','idCurso','id');
    }

    public function encuestas(){

      return $this->hasManyThrough(
          'App\Http\Models\Encuesta',
          'App\Http\Models\Horario',
          'idCurso', // Foreign key on users table...
          'idHorario', // Foreign key on posts table...
          'id', // Local key on countries table...
          'id' // Local key on users table...
      );

    }
}
