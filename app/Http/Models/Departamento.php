<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = 'departamento';

    public $timestamps = false;
    
    protected $fillable = ['id','nombre', 'presupuesto' ];

    public function secciones(){
        return $this->hasMany('App\Http\Models\Seccion','idDepartamento','id');
    }

	


}
