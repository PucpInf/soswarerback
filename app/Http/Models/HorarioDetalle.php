<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class HorarioDetalle extends Model
{
    protected $table = 'horario_detalle';

    protected $fillable = [ 'id','idHorario','idDia','horaInicio','horaFin','horasDictado'];

    public $timestamps = false;
}
