<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Services\EncuestaService;
use App\Http\Models\Usuario;
use App\Http\Models\Persona;
use App\Http\Models\Encuesta;
use App\Http\Models\Horario;
use App\Http\Services\PersonaService;
use App\Http\Services\FacultadService;
use App\Http\Services\CicloService;
use App\Http\Services\CategoriaService;
use App\Http\Services\SeccionService;
use App\Http\Services\CursoService;
use App\Http\Services\DepartamentoService;
use App\Http\Services\UsuarioService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Helpers\Algorithm;

class EncuestaController extends Controller {
  protected $encuestaService;
  protected $seccionService;
  protected $departamentoService;
  protected $facultadService;
  protected $categoriaService;
  protected $usuarioService;
  protected $dedicacionService;
  protected $cursoService;
  protected $cicloService;

  public function __construct(){
    $this->encuestaService =  new EncuestaService;
    $this->departamentoService =  new DepartamentoService;
    $this->facultadService =  new FacultadService;
    $this->seccionService =  new SeccionService;
    //$this->dedicacionService =  new DedicacionService;
    $this->categoriaService =  new CategoriaService;
    $this->cursoService =  new CursoService;
    $this->cicloService =  new CicloService;
    $this->usuarioService =  new UsuarioService;
  }

  public function GetEncuestas($ciclo, $curso){
        try
        {
            $encuestas = Encuesta::all();
            //where('idCiclo',$ciclo)->where('idCurso',$curso)->get()
            $retorno = array();
            foreach ($encuestas as &$enc) {
                $enc->horario;
                $enc->horario->ciclo;
                $enc->horario->curso;
                $enc->horario->curso->seccion;
                $enc->horario->usuario;
                $enc->horario->usuario->persona;
                if(($enc->horario->ciclo->id == $ciclo) * ($enc->horario->curso->id == $curso))
                    array_push($retorno,$enc);
              }
            return response()->json(['status' => true,
                'message'=> 'encuestas encontrados',
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

    public function GetAllEncuestas (Request $request)
    {
        try
        {
        //Profesor, seccion, horario, curso, cantidad de alumnos y cantidad de alumnos que reespondieron la encuesta
            $encuestas = Encuesta::all();
            foreach ($encuestas as &$enc) {
                $enc->horario;
                $enc->horario->ciclo;
                $enc->horario->curso;
                $enc->horario->curso->seccion;
                $enc->horario->usuario;
                $enc->horario->usuario->persona;
              }
            return response()->json(['status' => true,
                    'message'=> 'encuestas encontrados',
                    'body'=> $encuestas],
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

    public function file(Request $request)
    {
        return $request;
    }


  public function puntajesByFacultad($idDepartamento,$idCiclo=null){
    try {

        $departamento = $this->departamentoService->retrieveById($idDepartamento);
        if(!$departamento){
          return response()->json(['status'=>false,'message' => "No se encontró el departamento",'body'=>null ],404);
        }

        $message="Puntajes por facultad, de encuestas de profesores que pertenecen a un departamento específico";
        if($idCiclo!=null){
          $ciclo = $this->cicloService->retrieveById($idCiclo);
          if(!$ciclo){
            return response()->json(['status'=>false,'message' => "No se encontró el ciclo",'body'=>null ],404);
          }
          $message .= ", y a un ciclo especifico";
        }
        $message .= "  - (Ruta: /api/Encuesta/GetPuntajesByFacultad/{idDepartamento}/{idCiclo?})";


        $facultades = $this->facultadService->retrieveAll();
        if(count($facultades) == 0){
          return response()->json(['status'=>false,'message' => "No se encontraron facultades",'body'=>null ],404);
        }
        $body =array();
        foreach ($facultades as $facultad) {
          $obj = array ('idFacultad'=>null,'nombreFacultad'=>null,
                        'max'=>null,'min'=>null, 'media' => null,'idDepartamentoFiltro' =>null,
                        'nombreDepartamentoFiltro'=>null,'idCicloFiltro'=>null,'nombreCicloFiltro' =>null);

          $obj['idFacultad'] = $facultad->id;
          $obj['nombreFacultad'] = $facultad->nombreFacultad;
          $obj['idDepartamentoFiltro'] = $departamento->id;
          $obj['nombreDepartamentoFiltro'] = $departamento->nombre;
          if($idCiclo!=null){
            $obj['idCicloFiltro'] = $ciclo->id;
            $obj['nombreCicloFiltro'] = $ciclo->ciclo;
          }

          if($idCiclo!=null){
            $horarios = $this->facultadService->obtenerHorariosConProfesoresFiltradosPorDepartamentoCiclo($facultad,$idDepartamento,$idCiclo)->get();
          }
          else{
            $horarios = $this->facultadService->obtenerHorariosConProfesoresFiltradosPorDepartamentoCiclo($facultad,$idDepartamento)->get();
          }
          if(count($horarios)>0){
            $encuestas = $this->encuestaService->retrieveEncuestasPorCadaHorario($horarios);

            $algorithm = new Algorithm;
            $encuestas =  $algorithm->quitNullValuesFromCollection($encuestas);

            if(count($encuestas) > 0 ){
              $obj['min']   = $this->encuestaService->calculateMin($encuestas);
              $obj['max']   = $this->encuestaService->calculateMax($encuestas);
              $obj['media'] = $this->encuestaService->calculateAvg($encuestas);
            }
          }
          array_push($body,$obj);
        }
        return response()->json(['status'=>true,'message' => $message,'body'=>$body ],200);

    } catch (\Exception $e) {
        return response()->json(['status' => false,'message'=> 'Hubo un error', 'body' => $e->getMessage()], 500);
    }
  }


  public function puntajesBySeccion($idDepartamento,$idCiclo=null){
    try {

        $departamento = $this->departamentoService->retrieveById($idDepartamento);
        if(!$departamento){
          return response()->json(['status'=>false,'message' => "No se encontró el departamento",'body'=>null ],404);
        }

        $message="Puntajes por seccion de determinado departamento, de encuestas de profesores que pertenecen al mismo de partamento"."\n"."(NOTA:
                  LOS PUNTAJES DE LOS PROFESORES QUE ENSEÑAN A SECCIONES DEL DEPARTAMENTO ESPECOFICADO, PERO QUE SEAN PROFESORES PERTENECIENTES
                  A OTRO DEPARTAMENTO, NO SERÁN TOMADOS EN CUENTA)";
        if($idCiclo!=null){
          $ciclo = $this->cicloService->retrieveById($idCiclo);
          if(!$ciclo){
            return response()->json(['status'=>false,'message' => "No se encontró el ciclo",'body'=>null ],404);
          }
          $message .= ", y a un ciclo especifico";
        }
        $message .= "  - (Ruta: /api/Encuesta/GetPuntajesBySeccion/{idDepartamento}/{idCiclo?})";


        $secciones = $this->seccionService->retrieveByDepartamento($idDepartamento);
        if(count($secciones) == 0){
          return response()->json(['status'=>false,'message' => "No se encontraron secciones",'body'=>null ],404);
        }
        $body =array();

        foreach ($secciones as $seccion) {
          $obj = array ('idSeccion'=>null,'nombreSeccion'=>null,
                        'max'=>null,'min'=>null, 'media' => null,'idDepartamentoFiltro' =>null,
                        'nombreDepartamentoFiltro'=>null,'idCicloFiltro'=>null,'nombreCicloFiltro' =>null);

          $obj['idSeccion'] = $seccion->id;
          $obj['nombreSeccion'] = $seccion->nombre;
          $obj['idDepartamentoFiltro'] = $departamento->id;
          $obj['nombreDepartamentoFiltro'] = $departamento->nombre;
          if($idCiclo!=null){
            $obj['idCicloFiltro'] = $ciclo->id;
            $obj['nombreCicloFiltro'] = $ciclo->ciclo;
          }

          if($idCiclo!=null){
            $horarios = $this->seccionService->obtenerHorariosConProfesoresFiltradosPorDepartamentoCiclo($seccion,$idDepartamento,$idCiclo)->get();
          }
          else{
            $horarios = $this->seccionService->obtenerHorariosConProfesoresFiltradosPorDepartamentoCiclo($seccion,$idDepartamento)->get();
          }
          if(count($horarios)>0){
            $encuestas = $this->encuestaService->retrieveEncuestasPorCadaHorario($horarios);

            $algorithm = new Algorithm;
            $encuestas =  $algorithm->quitNullValuesFromCollection($encuestas);

            if(count($encuestas) > 0 ){
              $obj['min']   = $this->encuestaService->calculateMin($encuestas);
              $obj['max']   = $this->encuestaService->calculateMax($encuestas);
              $obj['media'] = $this->encuestaService->calculateAvg($encuestas);
            }
          }
          array_push($body,$obj);
        }
        return response()->json(['status'=>true,'message' => $message,'body'=>$body ],200);

    } catch (\Exception $e) {
        return response()->json(['status' => false,'message'=> 'Hubo un error', 'body' => $e->getMessage()], 500);
    }
  }

  public function puntajesByCategoria($idDepartamento,$idCiclo=null){

    try {

        $departamento = $this->departamentoService->retrieveById($idDepartamento);
        if(!$departamento){
          return response()->json(['status'=>false,'message' => "No se encontró el departamento",'body'=>null ],404);
        }
        $message="Puntajes por categoria, de encuestas de profesores que pertenecen a un departamento específico";
        if($idCiclo!=null){
          $ciclo = $this->cicloService->retrieveById($idCiclo);
          if(!$ciclo){
            return response()->json(['status'=>false,'message' => "No se encontró el ciclo",'body'=>null ],404);
          }
          $message .= ", y a un ciclo especifico";
        }
        $message .= "  - (Ruta: /api/Encuesta/GetPuntajesByCategoria/{idDepartamento}/{idCiclo?})";

        $categorias = $this->categoriaService->retrieveAll();
        if(count($categorias) == 0){
          return response()->json(['status'=>false,'message' => "No se encontraron categorias",'body'=>null ],404);
        }
        $body =array();
        foreach ($categorias as $categoria) {
          $obj = array ('idCategoria'=>null,'nombreCategoria'=>null,
                        'max'=>null,'min'=>null, 'media' => null,'idDepartamentoFiltro' =>null,
                        'nombreDepartamentoFiltro'=>null,'idCicloFiltro'=>null,'nombreCicloFiltro' =>null);

          $obj['idCategoria'] = $categoria->id;
          $obj['nombreCategoria'] = $categoria->nombre_categoria;
          $obj['idDepartamentoFiltro'] = $departamento->id;
          $obj['nombreDepartamentoFiltro'] = $departamento->nombre;
          if($idCiclo!=null){
            $obj['idCicloFiltro'] = $ciclo->id;
            $obj['nombreCicloFiltro'] = $ciclo->ciclo;
          }

          if($idCiclo!=null){
            $horarios = $this->categoriaService->obtenerHorariosConProfesoresFiltradosPorDepartamentoCiclo($categoria,$idDepartamento,$idCiclo)->get();
          }
          else{
            $horarios = $this->categoriaService->obtenerHorariosConProfesoresFiltradosPorDepartamentoCiclo($categoria,$idDepartamento)->get();
          }
          if(count($horarios)>0){
            $encuestas = $this->encuestaService->retrieveEncuestasPorCadaHorario($horarios);

            $algorithm = new Algorithm;
            $encuestas =  $algorithm->quitNullValuesFromCollection($encuestas);

            if(count($encuestas) > 0 ){
              $obj['min']   = $this->encuestaService->calculateMin($encuestas);
              $obj['max']   = $this->encuestaService->calculateMax($encuestas);
              $obj['media'] = $this->encuestaService->calculateAvg($encuestas);
            }
          }
          array_push($body,$obj);
        }
        return response()->json(['status'=>true,'message' => $message,'body'=>$body ],200);

    } catch (\Exception $e) {
        return response()->json(['status' => false,'message'=> 'Hubo un error', 'body' => $e->getMessage()], 500);
    }
  }

  public function puntajesByDedicacion($idDepartamento,$idCiclo=null){
    try {

        $departamento = $this->departamentoService->retrieveById($idDepartamento);
        if(!$departamento){
          return response()->json(['status'=>false,'message' => "No se encontró el departamento",'body'=>null ],404);
        }
        $message="Puntajes por dedicacion, de encuestas de profesores que pertenecen a un departamento específico";
        if($idCiclo!=null){
          $ciclo = $this->cicloService->retrieveById($idCiclo);
          if(!$ciclo){
            return response()->json(['status'=>false,'message' => "No se encontró el ciclo",'body'=>null ],404);
          }
          $message .= ", y a un ciclo especifico";
        }
        $message .= "  - (Ruta: /api/Encuesta/GetPuntajesByDedicacion/{idDepartamento}/{idCiclo?})";

        $dedicacionesArray = $this->usuarioService->retrieveDedicacionesArray();

        if(count($dedicacionesArray) == 0){
          return response()->json(['status'=>false,'message' => "No se encontraron dedicaciones",'body'=>null ],404);
        }
        $body =array();
        foreach ($dedicacionesArray as $nombreDedicacion) {
          $obj = array ('nombreDedicacion'=>null,
                        'max'=>null,'min'=>null, 'media' => null,'idDepartamentoFiltro' =>null,
                        'nombreDepartamentoFiltro'=>null,'idCicloFiltro'=>null,'nombreCicloFiltro' =>null);


          $obj['nombreDedicacion'] = $nombreDedicacion;
          $obj['idDepartamentoFiltro'] = $departamento->id;
          $obj['nombreDepartamentoFiltro'] = $departamento->nombre;
          if($idCiclo!=null){
            $obj['idCicloFiltro'] = $ciclo->id;
            $obj['nombreCicloFiltro'] = $ciclo->ciclo;
          }

          if($idCiclo!=null){
            $horarios = $this->usuarioService->obtenerHorariosPorDedicacion($nombreDedicacion,$idDepartamento,$idCiclo)->get();
          }
          else{
            $horarios = $this->usuarioService->obtenerHorariosPorDedicacion($nombreDedicacion,$idDepartamento)->get();
          }

          if(count($horarios)>0){
            $encuestas = $this->encuestaService->retrieveEncuestasPorCadaHorario($horarios);

            $algorithm = new Algorithm;
            $encuestas =  $algorithm->quitNullValuesFromCollection($encuestas);

            if(count($encuestas) > 0 ){
              $obj['min']   = $this->encuestaService->calculateMin($encuestas);
              $obj['max']   = $this->encuestaService->calculateMax($encuestas);
              $obj['media'] = $this->encuestaService->calculateAvg($encuestas);
            }
          }
          array_push($body,$obj);
        }
        return response()->json(['status'=>true,'message' => $message,'body'=>$body ],200);

    } catch (\Exception $e) {
        return response()->json(['status' => false,'message'=> 'Hubo un error', 'body' => $e->getMessage()], 500);
    }
  }

  public function puntajesByCurso($idDepartamento, $idCiclo =null){
    try {

        $departamento = $this->departamentoService->retrieveById($idDepartamento);
        if(!$departamento){
          return response()->json(['status'=>false,'message' => "No se encontró el departamento",'body'=>null ],404);
        }
        $message="Puntajes por curso, de encuestas de profesores que pertenecen a un departamento específico";
        if($idCiclo!=null){
          $ciclo = $this->cicloService->retrieveById($idCiclo);
          if(!$ciclo){
            return response()->json(['status'=>false,'message' => "No se encontró el ciclo",'body'=>null ],404);
          }
          $message .= ", y a un ciclo especifico";
        }
        $message .= "  - (Ruta: /api/Encuesta/GetPuntajesByCurso/{idDepartamento}/{idCiclo?})";

        $cursos = $this->cursoService->retrieveAll();

        if(count($cursos) == 0){
          return response()->json(['status'=>false,'message' => "No se encontraron cursos",'body'=>null ],404);
        }
        $body =array();
        foreach ($cursos as $key => $curso) {
          $obj = array ('idCurso'=>null,'codigoCurso'=>null,'nombreCurso'=>null,
                        'max'=>null,'min'=>null, 'media' => null,'idDepartamentoFiltro' =>null,
                        'nombreDepartamentoFiltro'=>null,'idCicloFiltro'=>null,'nombreCicloFiltro' =>null);

          $obj['idCurso'] = $curso->id;
          $obj['codigoCurso'] = $curso->codigo;
          $obj['nombreCurso'] = $curso->nombre;
          $obj['idDepartamentoFiltro'] = $departamento->id;
          $obj['nombreDepartamentoFiltro'] = $departamento->nombre;
          if($idCiclo!=null){
            $obj['idCicloFiltro'] = $ciclo->id;
            $obj['nombreCicloFiltro'] = $ciclo->ciclo;
          }
          $horarios=null;
          if($idCiclo!=null){
            $horarios = $this->cursoService->obtenerHorariosConProfesoresFiltradosPorDepartamentoCiclo($curso,$idDepartamento,$idCiclo)->get();
          }
          else{
            $horarios = $this->cursoService->obtenerHorariosConProfesoresFiltradosPorDepartamentoCiclo($curso,$idDepartamento)->get();
          }


          if(count($horarios)>0){
            $encuestas = $this->encuestaService->retrieveEncuestasPorCadaHorario($horarios);
            // if($key==1){
            //   return $encuestas;
            // }
            $algorithm = new Algorithm;
            $encuestas =  $algorithm->quitNullValuesFromCollection($encuestas);

            if(count($encuestas) > 0 ){
              $obj['min']   = $this->encuestaService->calculateMin($encuestas);
              $obj['max']   = $this->encuestaService->calculateMax($encuestas);
              $obj['media'] = $this->encuestaService->calculateAvg($encuestas);
            }
          }
          array_push($body,$obj);
        }
        return response()->json(['status'=>true,'message' => $message,'body'=>$body ],200);

    } catch (\Exception $e) {
        return response()->json(['status' => false,'message'=> 'Hubo un error', 'body' => $e->getMessage()], 500);
    }
  }
}
