<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'item';

    protected $fillable = ['id','titulo'];

    public $timestamps = false;

    public function encuestas(){
       return $this->belongsToMany('App\Http\Models\Encuesta','itemxencuesta',
       'idItem','idEncuesta')->withPivot('puntaje');
    }
}
