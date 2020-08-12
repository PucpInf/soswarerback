<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Providers\ApoyoEconomicoProvider;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Helpers\Algorithm;
use App\Http\Models\ApoyoEconomico;
use App\Http\Models\MotivoViaje;


class ApoyoEconomicoController extends Controller
{

  protected $apoyoEconomicoProvider;

  public function __construct(){
    $this->apoyoEconomicoProvider= new ApoyoEconomicoProvider();
  }

  public function store(){

    $this->validate(request(), [
        'montoSolicitado' => 'required',
        'fechaViaje' => 'required',
        'fechaEvento' => 'required',
        // 'tipoPersonal' => 'required',
        'idMotivo' => 'required',
        'moneda' => 'required',
        'boleto' => 'required',
        'inscripcion' => 'required',
        // 'activityId' => 'required',
        'hospedaje' => 'required',
        'assistCard' => 'required',
        'alimentosMovilidad' => 'required',
        'impuestos' => 'required',
        'file' => 'required'
      ]);

      $economicSupport = $this->apoyoEconomicoProvider->submitForm();
      return $economicSupport;
  }

  public function responseRequest(){
    $response = $this->apoyoEconomicoProvider->response();
    return $response;
  }

  // public function actualizar(Request $request, $idApoyoEconomico){
  //
  //   try {
  //     $apoyoEconomicoArray= $request->all();
  //     $algorithm = new Algorithm;
  //
  //     //obtenemos en el arreglo solo los datos que vamos a editar
  //     $apoyoEconomicoArray = $algorithm->quitNullValues($apoyoEconomicoArray);
  //     DB::beginTransaction();
  //     $apoyoeconomico = $this->apoyoeconomicoService->retrieveById($idApoyoEconomico);
  //     if(!$apoyoeconomico){
  //       return response()->json(['status' => false, 'message'=> 'El apoyo economico no existe', 'body' => null], 400);
  //     }
  //     $apoyoeconomico  = $this->apoyoeconomicoService->update($apoyoeconomico , $apoyoEconomicoArray);
  //     DB::commit();
  //     return response()->json(['status' => true, 'message'=> 'Apoyo económico actualizado', 'body' => $apoyoeconomico], 200);
  //   }catch(\Exception $e) {
  //     DB::rollback();
  //     Log::critical("El apoyo economico no pudo ser registrado: {$e->getCode()}, {$e->getLine()},{$e->getMessage()}" . PHP_EOL . "\n");
  //     return response()->json(['status' => false, 'message'=> 'El apoyo economico no pudo ser registrado', 'body' => $e->getMessage()], 500);
  //   }
  //
  // }

  // public function aprobar(Request $request){
  //   try {
  //     $apoyoEconomicoArray= $request->all();
  //
  //     $apoyoeconomicoEvaluateValidator = \Validator::make($apoyoEconomicoArray,
  //         [ 'id' => 'required',
  //           'montoAprobado' => 'required',
  //           'observacion' => 'required',
  //           'estado'=>'required',
  //           'fechaRespuesta'=>'required'
  //         ]);
  //
  //     if ($apoyoeconomicoEvaluateValidator->fails()) {
  //       Log::critical("Error en la validacion del registro de apoyo economico: {$apoyoeconomicoEvaluateValidator->errors()}\n\n");
  //       return response()->json(['status' => false, 'message'=> 'Error en la validacion del registro de apoyo economico.', 'body' => $apoyoeconomicoEvaluateValidator->errors()], 422);
  //     }
      /*$estadoNum=$apoyoEconomicoArray['estado'];
      if($estadoNum==1){
        $apoyoEconomicoArray['estado']="Aprobado por el jefe de departamento";
      }
      else if($estadoNum==2){
        $apoyoEconomicoArray['estado']="Aprobado por el jefe de seccion";
      }
      else if($estadoNum==3){

        $apoyoEconomicoArray['estado']="Pendiente";

      }
      else if($estadoNum==4){
        $apoyoEconomicoArray['estado']="Rechazado";
      }
      else{
        return response()->json(['status' => false, 'message'=> 'El estado no es valido', 'body' => null], 400);
      }*/
      //DB::beginTransaction();
  //     $idApoyoEconomico = $apoyoEconomicoArray['id'];
  //     $apoyoeconomico = $this->apoyoeconomicoService->retrieveById($idApoyoEconomico);
  //     if(!$apoyoeconomico){
  //       return response()->json(['status' => false, 'message'=> 'El apoyo economico no existe', 'body' => null], 400);
  //     }
  //
  //     $apoyoeconomico  = $this->apoyoeconomicoService->update($apoyoeconomico , $apoyoEconomicoArray);
  //     //DB::commit();
  //     $apoyoeconomico->save();
  //     return response()->json(['status' => true, 'message'=> 'Apoyo economico aprobado', 'body' => $apoyoeconomico], 200);
  //   }catch(\Exception $e) {
  //     DB::rollback();
  //     Log::critical("El apoyo economico no pudo ser registrado: {$e->getCode()}, {$e->getLine()},{$e->getMessage()}" . PHP_EOL . "\n");
  //     return response()->json(['status' => false, 'message'=> 'El apoyo economico no pudo ser aprobado', 'body' => $e->getMessage()], 500);
  //   }
  // }

  public function get(){
    $economicSupports = $this->apoyoEconomicoProvider->getRequests();
    return $economicSupports;

  }

  public function getRequest(){
    $request = $this->apoyoEconomicoProvider->getRequest();
    $request->tripReason = $request->tripReason();
    return $request;
  }
  // public function get(){
  //
  //   $economicReqs = ApoyoEconomico::all();
  //   $listReqs = [];
  //   foreach ($economicReqs as $req) {
  //     # code...
  //     $data = array(
  //       "id" => $req->id,
  //       "motivoViaje" => MotivoViaje::where('id',$req->idMotivo)->first()->descripcion,
  //       "montoSolicitado" => $req->montoSolicitado,
  //       "fechaViaje" => $req->fechaViaje,
  //       "estado" => $req->estado
  //     );
  //     array_push($listReqs, $data);
  //   };

  //
  //   $response = array(
  //     "status" => 200,
  //     "message" => 'lista de solicitudes de apoyo economico',
  //     "data" => $listReqs
  //   );
  //
  //   return response()->json($response);
  // }
}
