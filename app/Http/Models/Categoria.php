<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'tipoCategoria';

    public $timestamps = false;
    protected $fillable = ['id','nombre_categoria' ];

    public function usuarios(){
      return $this->hasMany('App\Http\Models\Usuario','idCategoria','id');
    }
    
    public function horarios(){

      return $this->hasManyThrough(
          'App\Http\Models\Horario',
          'App\Http\Models\Usuario',
          'idCategoria', // Foreign key on users table...
          'idUsuario', // Foreign key on posts table...
          'id', // Local key on countries table...
          'id' // Local key on users table...
      );

    }





}
