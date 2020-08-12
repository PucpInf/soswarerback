<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Investigacion;

class InvestigacionController extends Controller
{
    
    public function new(Request $request){

        $data = $request->all();

        $investigacion = new Investigacion();
        $investigacion->titulo = $data['titulo'];
        $investigacion->abstract = $data['abstract'];
        $investigacion->indicador_calidad = $data['indicador_calidad'];
        $investigacion->codigo_validacion = $data['codigo_validacion'];
        $investigacion->otros_autores = $data['otros_autores'];
        $investigacion->link = $data['link'];
        $investigacion->fecha_inicio = $data['fecha_inicio'];
        $investigacion->fecha_fin = $data['fecha_fin'];
        $investigacion->idUsuario = $data['idUsuario'];

        
        try{
            $investigacion->save();

            return response()->json([
                'status' => true,
                'data' => $data,
                'message' => 'SUCCESS'

            ], 200);
        } catch (Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'FAILURE',
                'error' => $e.getMessage()
            ], 500);
        }


    }

    public function get(){

        try{
            $investigaciones = Investigacion::all();

            return response()->json([
                'status' => true,
                'data' => $investigaciones,
                'message' => 'SUCCESS'

            ], 200);

        } catch (Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'FAILURE',
                'error' => $e.getMessage()
            ], 500);
        }

    }
}
