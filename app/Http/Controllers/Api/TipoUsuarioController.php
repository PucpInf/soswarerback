<?php

namespace App\Http\Controllers\Api;

use App\Http\Models\TipoUsuario;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TipoUsuarioController extends Controller
{
	public function GetTipoUsuario(){
        try
        {
            $tipo = TipoUsuario::all();
            return response()->json(['status' => true, 
                'message'=> 'Tipos de usuarios',
                'body'=> $tipo],
                200);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => false,
                'message'=> 'Hubo un error',
                'body' => $e->getMessage()],
                500);
        }
    }
}