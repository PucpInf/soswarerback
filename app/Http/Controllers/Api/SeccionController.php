<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\Seccion;
use App\Http\Models\Curso;
use App\Http\Models\Horario;
use App\Http\Models\HorarioDetalle;
use App\Http\Models\Usuario;
use App\Http\Models\Persona;
use App\Http\Models\Dia;
use App\Http\Models\TipoCategoria;
use App\Http\Models\Departamento;

use Illuminate\Support\Facades\DB;
use App\Http\Services\SeccionService;

class SeccionController extends Controller
{
    protected $seccionService;
    public function __construct(){
      $this->seccionService =  new SeccionService;
    }


    public function AddSeccion(Request $request){

      DB::beginTransaction();
      try {
        $seccionData= $request->all();
        ;
        $seccion = $this->seccionService->create($seccionData);

        $seccion->departamento;

        DB::commit();
        return response()->json(['status' => true, 'message' => "Seccion creada", 'body' => $seccion],200);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json(['status' => false, 'message'=> 'Hubo un error', 'body' => $e->getMessage()],  500);
      }
    }

    public function addSecciones(Request $request){

      //return $request;
      if($request['dptos']) {
        $bua = array();
        foreach($request['dptos'] as $depa){
            if($depa['nombre'] == "") break;
            if($depa['secciones'] != null){
              //Itero por cada seccion
              foreach($depa['secciones'] as $seccion){
                if(Seccion::where('id',$seccion['id'])->exists()){
                  $sec = Seccion::where('id',$seccion['id'])->first();
                  $sec->nombre = $seccion['nombre'];
                  $sec->anexo = $seccion['anexo'];
                  $sec->correo = $seccion['correo'];
                  $sec->idDepartamento = $seccion['idDepartamento'];
                  $sec->save();
                }
                else{
                  $newSec = new Seccion();
                  $newSec->nombre = $seccion['nombre'];
                  $newSec->anexo = $seccion['anexo'];
                  $newSec->correo = $seccion['correo'];
                  $newSec->idDepartamento = $seccion['idDepartamento'];
                  $newSec->save();
                }
              }
          }
        }
        $secciones = Seccion::all();
        $response = array(
            'status' => true,
            'message' => 'Secciones actualizadas',
            'body' => $secciones
        );
    }
    else {
        $response = array(
            'status' => false,
            'message' => 'No se mandaron secciones para actualizar',
            'body' => null
        );
    }
    return response()->json($response);
    }

    public function GetCursos ($seccion)
    {
        try
        {
            $sec = Seccion::where('id',$seccion)->first();
            $cursos = Curso::where('idSeccion',$seccion)->get();
            $data = array();
            foreach($cursos as $curso){
              //Cada curso tiene uno o mas horarios
              //Cada horario tiene detalle
              $horarios = Horario::where('idCurso',$curso->id)->get();
              $gg = array();
              foreach($horarios as $horario){
                $nombreHorario = $horario->nombre;
                //De $horario solo necesito el nombre
                $clases = array();
                $detalle = HorarioDetalle::where('idHorario',$horario->id)->get();
                foreach($detalle as $detail){
                  $dia = Dia::where('id',$detail->idDia)->first();
                  $d = array(
                    'id' => $detail->id,
                    'dia' => $dia->dia,
                    'hini' => $detail->horaInicio,
                    'hfin' => $detail->horaFin
                  );
                  array_push($clases,$d);
                }
                $nuevo = array(
                  'horario' => $nombreHorario,
                  'clases' => $clases
                );
                array_push($gg,$nuevo);
              }
              $course = array(
                'nombre' => $curso->nombre,
                'idSeccion' => $curso->idSeccion,
                'creditosTot' => $curso->creditosTot,
                'credTeor' => $curso->credTeor,
                'credPrac' => $curso->credPrac,
                'idFacultad' => $curso->idFacultad,
                'idTipoCurso' => $curso->idTipoCurso,
                'id' => $curso->id,
                'codigo' => $curso->codigo,
                'horarios'=> $gg
              );      
              array_push($data,$course);
            }
            return response()->json(['status' => true,
                    'message'=> 'cursos encontrados',
                    'body'=> $data],
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

    public function GetCursillos ($seccion)
    {
        try
        {
            $sec = Seccion::where('id',$seccion)->first();
            $cursos = Curso::where('idSeccion',$seccion)->get();
            $data = array();
            foreach($cursos as $curso){
              //Cada curso tiene uno o mas horarios
              //Cada horario tiene detalle
              $horarios = Horario::where('idCurso',$curso->id)->get();
              $gg = array();
              foreach($horarios as $horario){
                $nombreHorario = $horario->nombre;
                //De $horario solo necesito el nombre
                $clases = array();
                $detalle = HorarioDetalle::where('idHorario',$horario->id)->get();
                foreach($detalle as $detail){
                  $dia = Dia::where('id',$detail->idDia)->first();
                  $d = array(
                    'id' => $detail->id,
                    'dia' => $dia->dia,
                    'hini' => $detail->horaInicio,
                    'hfin' => $detail->horaFin
                  );
                  array_push($clases,$d);
                }
                $nuevo = array(
                  'horario' => $nombreHorario,
                  'clases' => $clases
                );
                array_push($gg,$nuevo);
              }
              $course = array(
                'nombre' => $curso->nombre,
                'idSeccion' => $curso->idSeccion,
                'creditosTot' => $curso->creditosTot,
                'credTeor' => $curso->credTeor,
                'credPrac' => $curso->credPrac,
                'idFacultad' => $curso->idFacultad,
                'idTipoCurso' => $curso->idTipoCurso,
                'id' => $curso->id,
                'codigo' => $curso->codigo,
                'horarios'=> $gg
              );      
              array_push($data,$course);
            }
            return response()->json(['status' => true,
                    'message'=> 'cursos encontrados',
                    'data'=> $data],
                    200);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => false,
                'message'=> 'Hubo un error',
                'data' => $e->getMessage()],
                500);
        }
    }

    public function listaProfesores($idSeccion){
      if(Usuario::where('idTipo',1)->orWhere('idTipo',3)->orWhere('idTipo',4)->exists()){      
      $profes = array();
      $totalProfes = Usuario::where('idTipo',1)->orWhere('idTipo',3)->orWhere('idTipo',4)->get();
      $seccion = Seccion::where('id',$idSeccion)->first();
      //El departamento se obtiene a través del idSeccion del usuario
      $departamento = Departamento::where('id',$seccion->idDepartamento)->first();
      foreach($totalProfes as $profesor){
        if($profesor->idSeccion == $idSeccion){
          //El nombre se extra de la tabla Persona
          $nombreProfe = Persona::where('id',$profesor->idPersona)->first();
          //La categoria se obtiene a través del idCategoria
          $categoria = TipoCategoria::where('id',$profesor->idCategoria)->first();
          $data = array(
          "codigo" => $profesor->id,
          "nombre" => $nombreProfe->nombres.' '.$nombreProfe->apPaterno.' '.$nombreProfe->apMaterno,
          "departamento" => $departamento->nombre,
          "seccion" => $seccion->nombre,
          'categoria' => $categoria['nombre_categoria']
          );
          array_push($profes,$data);
        }
      }
      $response = array(
          'status' => true,
          'message' => 'Lista de profesores',
          'data' => $profes
      );
      }
      else{
          $response = array(
              "status" => false,
              "message" => "Lista de profesores vacía",
              "data" => null
          );
    }
      return response()->json($response);
  }

  public function getHorariosCurso($seccion) {
    $data = array();
    $cursos = Curso::where('idSeccion',$seccion)->get();
    foreach($cursos as $curso){
      $horarios = Horario::where('idCurso',$curso->id)->get();
      foreach($horarios as $horario){
          if(Usuario::where('id',$horario->idUsuario)->exists()){
              $profe = Usuario::where('id',$horario->idUsuario)->first();
              $persona = Persona::where('id',$profe->idPersona)->first();
              $people = array(
                  'id' => $persona->id,
                  'nombres' => $persona->nombres,
                  'apPaterno' => $persona->apPaterno,
                  'apMaterno' => $persona->apMaterno,
                  'correo' => $persona->correo,
                  'telefono' => $persona->telefono,
                  'fechaNacimiento' => $persona->fechaNacimiento,
                  'sexo' => $persona->sexo,
                  'pais' => $persona->pais,
                  'nombre_completo' => $persona->nombres.' '.$persona->apPaterno.' '.$persona->apMaterno
              );
              $usuario = array(
                  'id' => $profe->id,
                  'contrasena' => $profe->contrasena,
                  'fotoPerfil' => $profe->fotoPerfil,
                  'areaInteres' => $profe->areaInteres,
                  'especializacion' => $profe->especializacion,
                  'idSeccion' => $profe->idSeccion,
                  'idCategoria' => $profe->idCategoria,
                  'idPersona' => $profe->idPersona,
                  'idTipo' => $profe->idTipo,
                  'correoPucp' => $profe->correoPucp,
                  'nuevoUsuario' => $profe->nuevoUsuario,
                  'idCarga' => $profe->idCarga,
                  'persona' => $people
              );
              $gg = array(
                  'id' => $horario->id,
                  'nombre' => $horario->nombre,
                  'idUsuario' => $horario->idUsuario,
                  'idCiclo' => $horario->idCiclo,
                  'idCurso' => $horario->idCurso,
                  'horas' => $horario->horas,
                  'usuario' => $usuario,
                  'curso' => $curso
              );
          } else{
              $profe = null;
              $persona = null;
              $gg = array(
                  'id' => $horario->id,
                  'nombre' => $horario->nombre,
                  'idUsuario' => $horario->idUsuario,
                  'idCiclo' => $horario->idCiclo,
                  'idCurso' => $horario->idCurso,
                  'horas' => $horario->horas,
                  'usuario' => $profe,
                  'curso' => $curso
              );
          }
          array_push($data,$gg);
      }
    }
    $response = array(
      'status' => true,
      'message' => 'Horarios encontrados',
      'data' => $data 
    );
    return response()->json($response);
  }

}
