<?php
namespace App\Providers;

use App\Http\Models\Download;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

class DownloadProvider{

  public function submitForm(){
    $download = Download::create(request(['idUsuario',
    'razonDescarga',
    'horasDescarga',
    'estado',
    'sectionId',
    'departmentId',
    'requestType']));
    $download->save();
    return $download;
  }
  public function getUserDownloads(){
    $downloads = Download::select('id','idUsuario','estado','created_at','requestType')->where(request()->filter,request()->id)->get();
    return $downloads;
  }

  public function getRequest(){
    $request = Download::where('id',request()->id)->first();
    return $request;
  }
  //
  // public function getSectionRequests(){
  //   $downloads = Download::select('id','userId','state','created_at','requestType')->where('sectionId', request()->sectionId)->get();
  //   return $downloads;
  // }

  public function response(){
    $request  = Download::find(request()->id);
    
    $request->estado = request()->estado;
    // $request->montoAprobado = request()->montoAprobado;
    $request->observacion = request()->observacion;
    $response = $request->save();
    if($response) {
      return response()->json(['status' => $response, 'message' => 'Solicitud respondida', 'body' => $request], 200);
    }
    return response()->json(['status' => $response, 'message' => 'Error en responder la solicitud', 'body' => $request], 500);
  }
}

 ?>
