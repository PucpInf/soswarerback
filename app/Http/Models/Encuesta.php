<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Encuesta extends Model
{
    protected $table = 'encuesta';

    protected $fillable = ['id',
                           'idHorario',
                           'numeroAlumnos',
                           'numeroContestados',
                           'puntajeFinal'
                          ];

    public $timestamps = false;

    public function items(){
       return $this->belongsToMany('App\Http\Models\Item','itemxencuesta',
       'idEncuesta','idItem')->withPivot('puntaje');
    }

    public function horario(){
        return $this->hasOne('App\Http\Models\Horario','id','idHorario');
    }

}
