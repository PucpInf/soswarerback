<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Ciclo extends Model
{
    protected $table = 'ciclo';

    public $timestamps = false;
    
    protected $fillable = ['id','ciclo'];

    //public function curso(){
    //    return $this->belongsTo('App\Http\Models\Curso','id','idCiclo');
    //  }
}
