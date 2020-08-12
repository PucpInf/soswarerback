<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class ApoyoEconomico extends Model
{

  protected $table = 'apoyoEconomico';

  protected $fillable = [ 'id',
                          'montoSolicitado',
                          'fechaViaje',
                          'fechaEvento',
                          'observacion',
                          'estado',
                          'idUsuario',
                          'montoAprobado',
                          'tipoPersonal',
                          'idMotivo',
                          'moneda',
                          'fechaRespuesta',
                          'boleto',
                          'inscripcion',
                          'hospedaje',
                          'assistCard',
                          'alimentosMovilidad',
                          'impuestos',
                          'sectionId',
                          'departmentId',
                          'activityId',
                          'requestType',
                          'file'
                        ];
  /*estado (boolean).- true : activo, false : inactivo*/

  public $timestamps = true;

  public function documentos(){
    return $this->hasMany('App\Http\Models\Documento','apoyoeconomicoId','id');
  }

  public function user() {
    return $this->belongsTo('\App\Http\Models\Usuario','idUsuario','id')->first();
  }

  public function tripReason(){
    return $this->belongsTo('\App\Http\Models\MotivoViaje','idMotivo','id')->first();
  }
}
