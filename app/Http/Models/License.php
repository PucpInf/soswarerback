<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    protected $table = 'licencia';
    public $timestamps = true;
    protected $fillable = [
      'idUsuario',
      'fecha_fin',
      'fecha_inicio',
      'estado',
      'lugar',
      'motivo',
      'sectionId',
      'departmentId',
      'requestType',
      'actividadNoLectiva',
      'actividadLectiva',
      'goceHaber',
      'dedicacion',
      'idCiclo'
    ];

    public function user() {
      return $this->belongsTo('\App\Http\Models\Usuario','idUsuario','id')->first();
    }
}
