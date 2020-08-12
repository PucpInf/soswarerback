<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Providers\RequestProvider;
use App\Http\Controllers\Controller;

class RequestController extends Controller
{
    protected $requestProvider;   

    public function __construct(){
      $this->requestProvider = new RequestProvider();
    }
    
    public function create(){
      $this->validate(request(), [
        'idUsuario' => 'required',
        'requestType' => 'required',
        'sectionId' => 'required',
        'departmentId' => 'required',
        'estado' => 'required'
      ]);

      $response = $this->requestProvider->postRequest();
      // return response()->json(['status' => true, 'message' => 'Se registro correctamente la solicitud', 'body' => $response],200);
      // $response = request()->file->extension();
      // $response = request()->idUsuario;
      return response()->json(['status' => true, 'message' => 'Se registro correctamente la solicitud', 'body' => $response],200);
    }

    public function get() {
      $this->validate(request(),[
        'id' => 'required',
        // 'requestType' => 'required'
        'filter' => 'required',
        'states' => 'required'
      ]);
        
      $requests = $this->requestProvider->getAllRequests();
      $requestFormatted = [];
      foreach ($requests as $req) {
        $req->requierInfo = $req->user()->person();
        $req->requierName = $req->requierInfo->nombres.' '.$req->requierInfo->apPaterno.' '.$req->requierInfo->apMaterno;
        switch ($req->requestType){
          case '1':
              $requestType = 'Apoyo Economico';
              break;
          case '2':
              $requestType = 'Licencia';
              break;
          case '3':
              $requestType = 'Descarga';
              break;
          case '4':
              $requestType = 'Cambio de Nivel';
              break;
        }
        $req->requestType = $requestType;
        array_push($requestFormatted,$req);
      }
      // $response = $request->all();
      return response()->json(['status' => true, 'message' => 'Lista de solicitudes', 'body' => $requestFormatted],200);
    }

    public function getRequestByType (){
      $requests = $this->requestProvider->getRequestsByType();
      return response()->json(['status' => true, 'message' => 'Lista de solicitudes de '.request()->requestType, 'body' => $requests],200);
    }

    public function getRequest() {
      $this->validate(request(), [
        'id' => 'required',
        'requestType' => 'required',
        // 'state' => 'required',
        // 'userId' => 'required'
      ]);
    
      $request = $this->requestProvider->getRequest();
      $request->requierInfo = $request->user()->person();
      // $request->userInfo = $this->userProvider->getUserInfo($request->userId);
      $request->section = $request->user()->section();
      $request->department = $request->user()->section()->departamento();
      return response()->json(['status' => true, 'message' => 'Solicitud obtenida', 'body' => $request],200);
    }

    public function responseRequest() {
      $this->validate(request(),[
        'id' => 'required',
        'fechaRespuesta' => 'required',
        'estado' => 'required',
        'observacion' => 'required',
        'requestType' => 'required'
      ]);
      $response = $this->requestProvider->responseRequest();
      return $response;
    }
}
