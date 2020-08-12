<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class TipoUsuario extends Model
{
    protected $table = 'tipoUsuario';

    public $timestamps = false;
	
	public function usuario(){
      return $this->belongsTo('App\Http\Models\Usuario','id','idTipo');
	}
}
