<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\HorarioService;
use App\Http\Models\Horario;
use App\Http\Models\ProfePorHorario;

class HorarioController extends Controller
{
    protected $horarioService;
    public function __construct(){
      $this->horarioService =  new HorarioService();
    }

    public function asignarHorario(Request $request){
        $registroArray = $request->all();
        $registroValidator = \Validator::make($registroArray,
               [ 'idHorario' => 'required', 'idUsuario' => 'required']);

        if($registroValidator->fails()){
            $array = array(
                'status' => false,
                'message' => 'Error en la validacion de datos',
                'body' => $registroValidator->errors()
            );
        }
        else{
            //Saco el nombre del horario
            $horario = Horario::where('id',$registroArray['idHorario'])->first();
            $horario->idUsuario = $registroArray['idUsuario'][0];
            $nombre = $horario->nombre;
            $bua = array();
            $cont = 1;
            foreach($registroArray['idUsuario'] as $usuario){
              //array_push($bua,$usuario);
              if($cont == 1){
                $horario->idUsuario = $usuario;
                $horario->horas = $registroArray['horas'];
                $horario->save();
                array_push($bua,$horario);
              }
              else{
                $nuevoHorario = new Horario();
                $nuevoHorario->nombre = $nombre;
                $nuevoHorario->idUsuario = $usuario;
                $nuevoHorario->idCiclo = $registroArray['idCiclo'];
                $nuevoHorario->idCurso = $registroArray['idCurso'];
                $nuevoHorario->horas = $registroArray['horas'];
                $nuevoHorario->save();
                array_push($bua,$nuevoHorario);
              }
              $cont++;
            }

            $array = array(
                'status' => true,
                'message' => 'Profesores asignados correctamente',
                'body' => $bua
            );
        }
        return response()->json($array);
    }

    public function GetCursosProfesorCiclo($idUsuario, $idCiclo){
      try
      {
        $horarios = Horario::where('idUsuario',$idUsuario)->where('idCiclo',$idCiclo)->get();
        foreach ($horarios as &$hor) {
            $hor->curso;
        }
        return response()->json(['status' => true,
              'message'=> 'Curso encontrados',
              'body'=> $horarios],

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

    public function list($idUsuario){
      try {
        $horarios = $this->horarioService->retrieveAll();
        $message = 'Lista de horarios ';
        if($idUsuario!=null){
          $horarios = $this->horarioService->retrieveByIdUsuario($idUsuario);
          $message = $message. "por usuario ";
        }

        $message =  $message."(Ruta: api/listHorarios)";
        foreach ($horarios as $h) {
          $h->horariosDetalle;
          $horasDictadas = $this->horarioService->getDictatedHourByHorarioObj($h);
          $h['horasDictado'] = $horasDictadas;
          // code...
        }
        if(count($horarios)==0){
          return response()->json(['status' => false, 'message' =>'Horarios no encontrados (Ruta: api/listHorarios)', 'body' => $e->getMessage()],404);
        }

        return response()->json(['status' => true, 'message' =>$message, 'body' => $horarios],404);

      } catch (\Exception $e) {
          return response()->json(['status' => false, 'message' =>'Error de servidor (Ruta: api/listHorarios)', 'body' => $e->getMessage()],500);
      }


    }

    public function listar($idUsuario){
      $horarios = $this->horarioService->retrieveByIdUsuario($idUsuario);
      foreach($horarios as $h) {
        $h->horariosDetalle;
        /*$horasDictadas = $this->horarioService->getDictatedHourByHorarioObj($h);
        $h['horasDictado'] = $horasDictadas;*/
      }
      return response()->json(['status' => true, 'message' => 'bua', 'body' => $horarios]);
      
    }
}
