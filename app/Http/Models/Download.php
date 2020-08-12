<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    protected $table = 'descarga';

    public $timestamps = true;
    
    protected $fillable = [
      'idUsuario',
      'razonDescarga',
      'horasDescarga',
      'estado',
      'sectionId',
      'departmentId',
      'requestType'
    ];

    public function user() {
      return $this->belongsTo('\App\Http\Models\Usuario','idUsuario','id')->first();
    }
}
