<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class PreferenciaDictado extends Model
{
	protected $table = 'preferenciaDictado';
	
	public $timestamps = false;
	
    protected $fillable = [ 'id','estado','idCurso','idUsuario','idCiclo'];

    public function usuario(){
      return $this->hasOne('App\Http\Models\Usuario','id','idUsuario');
  	}

  	public function ciclo(){
  		return $this->hasOne('App\Http\Models\Ciclo','id','idCiclo');
  	}

  	public function curso(){
  		return $this->hasOne('App\Http\Models\Curso','id','idCurso');
  	}

}
