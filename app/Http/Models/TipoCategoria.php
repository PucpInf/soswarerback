<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class TipoCategoria extends Model
{
    protected $table = 'tipoCategoria';

    public $timestamps = false;
	
	public function usuario(){
      return $this->belongsTo('App\Http\Models\Usuario','id','idCategoria');
	}
}
