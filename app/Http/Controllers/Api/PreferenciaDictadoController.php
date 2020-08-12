<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\PreferenciaDictado;
use App\Http\Models\Usuario;
use App\Http\Models\Facultad;
use App\Http\Models\Ciclo;
use App\Http\Models\Persona;
use App\Http\Models\Curso;

class PreferenciaDictadoController extends Controller
{
    public function AddPreferencia (Request $request)
    {
    	//return $request;
    	try
    	{
    		$curs = $request->cursos;
    		$size = count($curs);
    		$retorno = array();
    		for ($i = 0; $i < $size; $i++) {
	          if ($curs[$i]["ciclo"]) {
	          	if($curs[$i]["ciclo"] == 3)
	          	{
	          		$pref = new PreferenciaDictado();
		            $pref->idUsuario = $request->idus;
		            $pref->estado = "pendiente";
		            $pref->idCiclo = $request->ciclo1;
		            $pref->idCurso = $curs[$i]["id"];
		            $pref->save();
		            array_push($retorno,$pref);

		            
		            $pref = new PreferenciaDictado();
		            $pref->idUsuario = $request->idus;
		            $pref->estado = "pendiente";
		            $pref->idCiclo = $request->ciclo2;
					$pref->idCurso = $curs[$i]["id"];
		            $pref->save();
		            array_push($retorno,$pref);
					

	          	} else if ($curs[$i]["ciclo"] == 2) 
	          	{
	          		$pref = new PreferenciaDictado();
		            $pref->idUsuario = $request->idus;
		            $pref->estado = "pendiente";
		            $pref->idCiclo = $request->ciclo2;
					$pref->idCurso = $curs[$i]["id"];
		            $pref->save();
		            array_push($retorno,$pref);

	          	}
	          	else 
	          	{
	          		$pref = new PreferenciaDictado();
		            $pref->idUsuario = $request->idus;
		            $pref->estado = "pendiente";
		            $pref->idCiclo = $request->ciclo1;
					$pref->idCurso = $curs[$i]["id"];
		            $pref->save();
		            array_push($retorno,$pref);

	          	}
	          }
	        }
			return response()->json(['status' => true, 
                'message'=> 'preferencias guardadas', 
                'body'=> $retorno], 
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

    public function GetProfesoresCurso($curso)
    {
    	try
    	{
    		$preferencias = PreferenciaDictado::where('idCurso',$curso)->get();
    		foreach ($preferencias as $pref) {
				$user = $pref->usuario;
				$persona = Persona::where('id',$user->idPersona)->first();
				$profesor = $persona->nombres.' '.$persona->apPaterno.' '.$persona->apMaterno;
				$pref->profe = $profesor;
	    		$pref->usuario;
	    		$pref->usuario->persona;
			}
			return response()->json(['status' => true, 
                'message'=> 'profesores encontrados', 
                'body'=> $preferencias], 
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

    public function GetPreferencias($idUsuario)
    {
    	try
    	{
    		$preferencias = PreferenciaDictado::where('idUsuario',$idUsuario)->get();
    		foreach ($preferencias as $pref) {
	    		$pref->ciclo;
	    		$pref->curso;
			}
			return response()->json(['status' => true, 
                'message'=> 'preferencias encontradas', 
                'body'=> $preferencias], 
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
	
	public function getPreferenciasSeccion($idSeccion){
		try
		{
			$data = array();
			$usuarios = Usuario::where('idSeccion',$idSeccion)->get();
			foreach($usuarios as $user){
				$idUsuario = $user->id;
				$preferencias = PreferenciaDictado::where('idUsuario',$idUsuario)->get();
				$count = PreferenciaDictado::where('idUsuario',$idUsuario)->count();
				if($count != 0){
					foreach($preferencias as $pref){
						$curso = Curso::where('id',$pref->idCurso)->first();
						$facultad = Facultad::where('id',$curso->idFacultad)->first();
						$usuario = Usuario::where('id',$pref->idUsuario)->first();
						$persona = Persona::where('id',$usuario->idPersona)->first();
						$ciclo = Ciclo::where('id',$pref->idCiclo)->first();
						$array = array(
							'codigo' => $curso->codigo,
							'curso' => $curso->nombre,
							'facultad' => $facultad['nombreFacultad'],
							'profesor' => $persona->nombres.' '.$persona->apPaterno.' '.$persona->apMaterno,
							'ciclo1' => 'Si',
							'ciclo2' => 'No'
						);
					}
					array_push($data,$array);
				}
			}
			$response = array(
				'status' => true,
				'message' => 'Preferencias de la sección encontradas',
				'body' => $data
			);
			return response()->json($response);

		}
		catch(\Exception $e)
		{
			$response = array(
				'status' => false,
				'message' => 'Error en la transacción',
				'body' => $e->getMessage()
			);			
			return response()->json($response);
		}
	}

    public function DeletePreferencia($id)
    {
    	try
    	{
    		$preferencia = PreferenciaDictado::where('id',$id)->first();
    		$preferencia->delete();
			return response()->json(['status' => true, 
                'message'=> 'preferencia eliminada', 
                'body'=> $preferencia], 
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
