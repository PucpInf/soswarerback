<?php
namespace App\Providers;

use App\Http\Models\ApoyoEconomico;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

class ApoyoEconomicoProvider{

  public function submitForm(){
    $economicSupport = ApoyoEconomico::create(request(['idUsuario',
                                              'montoSolicitado',
                                              'fechaViaje',
                                              'fechaEvento',
                                              'tipoPersonal',
                                              'idMotivo',
                                              'moneda',
                                              'boleto',
                                              'inscripcion',
                                              'activityId',
                                              'hospedaje',
                                              'assistCard',
                                              'alimentosMovilidad',
                                              'impuestos',
                                              'requestType',
                                              'estado',
                                              'sectionId',
                                              'departmentId']));
  // if(request()->file != '0'){
    // $len = strlen(request()->file);
    // // $len 
    // $str = substr(request()->file,($len - 23) );
    $economicSupport->file = '200.16.7.152/files/requests/economicSupport/'.request()->file->getClientOriginalName();
    $economicSupport->save();
    $storeResponse = request()->file->storeAs('/var/www/html/sos_back/public/files/requests/economicSupport',request()->file->getClientOriginalName()) ;
  // }
    // $economicSupport->save();
    return $storeResponse;

  }
  public function getRequests(){
    $economicSupports = ApoyoEconomico::select('id','idUsuario','estado','created_at','requestType', 'montoSolicitado', 'montoAprobado')->where(request()->filter,request()->id)->get();
    return $economicSupports;
  }

  public function getRequest(){
    $request = ApoyoEconomico::where('id',request()->id)->first();
    return $request;
  }
  //
  // public function getSectionRequests(){
  //   $downloads = Download::select('id','userId','state','created_at','requestType')->where('sectionId', request()->sectionId)->get();
  //   return $downloads;
  // }

  public function response(){
    $request  = ApoyoEconomico::find(request()->id);
    
    $request->estado = request()->estado;
    $request->montoAprobado = request()->montoAprobado;
    $request->observacion = request()->observacion;
    $response = $request->save();
    if($response) {
      return response()->json(['status' => $response, 'message' => 'Solicitud respondida', 'body' => $request], 200);
    }
    return response()->json(['status' => $response, 'message' => 'Error en responder la solicitud', 'body' => $request], 500);
  }
}
