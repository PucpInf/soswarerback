<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Facultad extends Model
{
    protected $table = 'facultad';

    public $timestamps = false;

    protected $fillable = ['id','nombreFacultad' ];

    public function cursos(){
      return $this->hasMany('App\Http\Models\Curso','idFacultad','id');
    }

    public function horarios(){
      return $this->hasManyThrough(
          'App\Http\Models\Horario',
          'App\Http\Models\Curso',
          'idFacultad', // Foreign key on users table...
          'idCurso', // Foreign key on posts table...
          'id', // Local key on countries table...
          'id' // Local key on users table...
      );
    }
}
