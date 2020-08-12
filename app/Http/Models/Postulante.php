<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Postulante extends Model
{
    protected $table = 'postulante';

    public $timestamps = false;

    public function persona(){
      return $this->hasOne('App\Http\Models\Persona','id','idPersona');
  	}

  	public function convocatoria(){
      return $this->hasOne('App\Http\Models\Convocatoria','id','idConvocatoria');
  	}

}
