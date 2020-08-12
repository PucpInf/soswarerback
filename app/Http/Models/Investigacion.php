<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Investigacion extends Model
{
    protected $table = 'investigacion';
    
    public $timestamps = false;

    protected $fillable = [
        'titulo',
        'abstract',
        'indicador_calidad',
        'codigo_validacion',
        'otros_autores',
        'link',
        'fecha_inicio',
        'fecha_fin',
        'idUsuario'
    ];
}
