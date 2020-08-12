<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class MotivoViaje extends Model
{
    protected $table = 'motivoViaje';

    protected $fillable = [ 'id','descripcion'];
}
