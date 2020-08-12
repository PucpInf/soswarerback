<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $table = 'horario';

    protected $fillable = ['id','nombre','idCurso','idUsuario','idCiclo'];

    public $timestamps = false;

    public function ciclo(){
        return $this->hasOne('App\Http\Models\Ciclo','id','idCiclo');
    }

    public function usuario(){
        return $this->hasOne('App\Http\Models\Usuario','id','idUsuario');
    }

    public function curso(){
        return $this->hasOne('App\Http\Models\Curso','id','idCurso');
    }

    public function encuesta(){
        return $this->belongsTo('App\Http\Models\Encuesta','id','idHorario');
    }

    public function horariosDetalle(){
      return $this->hasMany('App\Http\Models\HorarioDetalle','idHorario','id');
    }
}
