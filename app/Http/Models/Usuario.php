<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class Usuario extends Model
{
    protected $table = 'usuario';

    public $timestamps = false;

    protected $fillable = [
                            'id',
                            'contrasena',
                            'fotoPerfil',
                            'areaInteres',
                            'especializacion',
                            'idSeccion',
                            'idCategoria',
                            'idPersona',
                            'idTipo'
                          ];

    public function horarios(){
      return $this->hasMany('App\Http\Models\Horario','idUsuario','id');
    }

    public function persona(){
        return $this->hasOne('App\Http\Models\Persona','id','idPersona');
    }

    public function seccion(){
        return $this->hasOne('App\Http\Models\Seccion','id','idSeccion');
    }

    public function categoria(){
        return $this->hasOne('App\Http\Models\TipoCategoria','id','idCategoria');
    }

    public function tipo(){
        return $this->hasOne('App\Http\Models\TipoUsuario','id','idTipo');
    }

    public function encuestas(){

      return $this->hasManyThrough(
          'App\Http\Models\Encuesta',
          'App\Http\Models\Horario',
          'idUsuario', // Foreign key on users table...
          'idHorario', // Foreign key on posts table...
          'id', // Local key on countries table...
          'id' // Local key on users table...
      );

    }

    public function concursos(){
      return $this->belongsTo('Appersonp\Http\Models\ConcursoPorProfesor','idUsuario', 'id');
    }
    public function person() {
      return $this->belongsTo('\App\Http\Models\Persona', 'idPersona','id')->first();
    }

    public function section() {
      return $this->belongsTo('\App\Http\Models\Seccion', 'idSeccion','id')->first();
    }

}
