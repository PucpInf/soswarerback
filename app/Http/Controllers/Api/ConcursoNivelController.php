<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Models\ConcursoNivel;
use App\Http\Models\TipoCategoria;
use App\Http\Models\Usuario;
use App\Http\Models\Seccion;
use App\Http\Models\ConcursoPorProfesor;
use App\Http\Controllers\Controller;

class ConcursoNivelController extends Controller
{
    public function guardarConcursoNivel(Request $request) {
        $registroArray = $request->all();
        $registroValidator = \Validator::make($registroArray,
                   [ 'titulo' => 'required', 'fecha_inicio' => 'required',
                     'fecha_fin' => 'required', 'nivel' => 'required',
                     'descripcion' => 'required',
                     'estado' => 'required', 'idUsuario' => 'required',
                     'idDepartamento' => 'required']);
    
        if($registroValidator->fails()){
          $response = array(
            'status' => false,
            'message' => 'Error en validación de datos',
            'body' => $registroValidator->errors()
          );
        }
        else{
          //Procede el registro de ConcursoNivel
          $concurso = new ConcursoNivel();
          $concurso->titulo = $registroArray['titulo'];
          $concurso->fecha_inicio = $registroArray['fecha_inicio'];
          $concurso->fecha_fin = $registroArray['fecha_fin'];
          $concurso->nivel = $registroArray['nivel'];
          $concurso->descripcion = $registroArray['descripcion'];
          $concurso->idUsuario = $registroArray['idUsuario'];
          $concurso->estado = $registroArray['estado'];
          $concurso->idDepartamento = $registroArray['idDepartamento'];
    
          $concurso->save();
    
          $response = array(
            'status' => true,
            'message' => 'Concurso de nivel registrado correctamente',
            'body' => $concurso
          );
        }
    
        return response()->json($response);
      }
    
      public function getConcursosActivos($idDepartamento){
        /*$usuario = Usuario::where('id',$idUsuario)->first();
        $seccion = Seccion::where('id',$usuario->idSeccion)->first();*/
        $concursosV = ConcursoNivel::where('idDepartamento',$idDepartamento)->exists();
        $concursos = ConcursoNivel::where('idDepartamento',$idDepartamento)->get();
        if($concursosV){
          $data = array();
          foreach($concursos as $concursoNivel){
            $concur = array(
              'id' => $concursoNivel->id,
              'titulo' => $concursoNivel->titulo,
              'categoria' => $concursoNivel->nivel,
              'fecha_inicio' => $concursoNivel->fecha_inicio,
              'fecha_fin' => $concursoNivel->fecha_fin
            );
            array_push($data,$concur);
          }
    
          $response = array(
            'status' => true,
            'message' => 'Concursos de nivel encontrados en el departamento',
            'body' => $concursos
          );
      }
      else{
        $response = array(
          'status' => false,
          'message' => 'Actualmente no hay concursos de nivel activos en el departamento',
          'body' => null
        );
      }    
        return response()->json($response);
      }

      public function aprobarPostulacion(Request $request){

        if(ConcursoPorProfesor::where('idConcurso',$request->idConcurso)->where('idUsuario',$request->idUsuario)->exists()){
          $concurso = ConcursoPorProfesor::where('idConcurso',$request->idConcurso)->where('idUsuario',$request->idUsuario)->first();
          $gg = ConcursoNivel::where('id',$request->idConcurso)->first();
          $concurso->estado = $request->estado;
          $postulante = Usuario::where('id',$request->idUsuario)->first(); //Para cambiar de categoria
          $nivel = $gg->nivel;
          $categoria = TipoCategoria::where('nombre_categoria',$nivel)->first();
          $idCategoria = $categoria->id;
          $postulante->idCategoria = $idCategoria;
          $concurso->save();
          $postulante->save();
          $response = array(
            'status' => true,
            'message' => 'Cambio de nivel aprobado',
            'body' => $postulante
          );
        }
        else{
          $response = array(
            'status' => false,
            'message' => 'Postulación no encontrada',
            'body' => null
          );
        }        
        return response()->json($response);
      }
}