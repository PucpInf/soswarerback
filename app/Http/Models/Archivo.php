<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Archivo extends Model
{
    protected $table = 'archivo';
    public $timestamps = false;
    protected $fillable = [
                           'id',
                           'nombreArchivo',
                           'urlArchivo',
                           'extension'
                          ];
}
