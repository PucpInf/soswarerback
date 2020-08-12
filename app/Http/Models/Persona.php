<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class Persona extends Model
{
    protected $table = 'persona';

    public $timestamps = false;

    protected $dateFormat = 'Y-m-d';

    protected $fillable = [ 'id','nombres','apPaterno','apMaterno','correo','telefono',
                            'fechaNacimiento','sexo'];

    public function usuario(){
        return $this->belongsTo('App\Http\Models\Usuario','id','idPersona');
    }
}
