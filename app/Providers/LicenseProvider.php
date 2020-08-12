<?php
namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Http\Models\License;
use App\Http\Models\Ciclo;

class LicenseProvider{

  public function submitForm(){
    $license = License::create(request(['idUsuario',
    'fecha_fin',
    'fecha_inicio',
    'estado',
    'lugar',
    'motivo',
    'sectionId',
    'departmentId',
    'requestType',
    'actividadNoLectiva',
    'actividadLectiva',
    'goceHaber',
    'dedicacion']));
    $date = now();
    $term = $this->getTerm($date->month);
    $finalTerm = $date->year.$term;
    $response = Ciclo::where('ciclo', $finalTerm)->get();
    $license->idCiclo = $response[0]->id;


    $license->save();
    return $license;
  }
  public function getTerm($month) {
    if($month<8){
        return '-1';
    }
    return  '-2';
  }

  public function getLicenses(){
    $licenses = License::select('id','idUsuario','estado','created_at','requestType')->where(request()->filter,request()->id)->get();
    // $states = request()->states;
    // foreach ($states as $state) {
      
    // }
    return $licenses;
  }

  public function getRequest(){
    $request = License::where('id',request()->id)->first();
    return $request;
  }
  //
  // public function getSectionRequests(){
  //   $licenses = Licenses::select('id','userId','state','created_at','requestType')->where('sectionId',request()->sectionId)->get();
  // }
  public function response(){
    $request  = License::find(request()->id);
    
    $request->estado = request()->estado;
    $request->observaciones = request()->observacion;
    $response = $request->save();
    if($response) {
      return response()->json(['status' => $response, 'message' => 'Solicitud respondida', 'body' => $request], 200);
    }
    return response()->json(['status' => $response, 'message' => 'Error en responder la solicitud', 'body' => $request], 500);
  }
}


 ?>
