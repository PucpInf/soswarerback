<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Convocatoria extends Model
{
    protected $table = 'convocatoria';

    public $timestamps = false;

  	public function postulantes(){
      return $this->hasMany('App\Http\Models\Postulante','idConvocatoria','id');
    }

}
