<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\CargaHoraria;

class CargaHorariaController extends Controller
{
    public function GetCarga($idus,$idciclo){
        try
        {
            $carga = CargaHoraria::where('idUsuario',$idus)->where('idCiclo',$idciclo)->get();
            return response()->json(['status' => true, 
                'message'=> 'Carga del Profesor',
                'body'=> $carga],
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
