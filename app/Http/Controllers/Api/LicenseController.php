<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Models\License;
use App\Providers\LicenseProvider;

class LicenseController extends Controller
{
    //
    protected $licenseProvider;

    public function __construct() {
      $this->licenseProvider = new LicenseProvider();
    }

    public function store () {
      $this->validate(request(),[
        'fecha_fin' => 'required',
        'fecha_inicio' => 'required',
        'estado' => 'required',
        // 'lugar' => 'required',
        'motivo' => 'required',
        'actividadNoLectiva' => 'required',
        'actividadLectiva' => 'required',
      ]);
      // dd(request());
      $license = $this->licenseProvider->submitForm();
      return $license;
    }


    
    public function get(){
      $licenses = $this->licenseProvider->getLicenses();
      return $licenses;
    }
    public function getRequest(){
      $request = $this->licenseProvider->getRequest();
      return $request;
    }

    public function licenciasUsuario($idUsuario,$idCiclo){

      $licencias = License::where('idUsuario',$idUsuario)->where('idCiclo',$idCiclo)->where('estado','Aprobada')->get();
      //return $licencias;
      if($licencias){
        $dias = 0;
        foreach($licencias as $bua){
          $diferencia = strtotime($bua->fecha_fin) - strtotime($bua->fecha_inicio);
          $days = round($diferencia / (60*60*24));
          $dias += $days;
        }
        
        $datos = array();
        foreach($licencias as $lice){
          $data = array(
            'id' => $lice->id,
            'fecha_inicio' => $lice->fecha_inicio,
            'fecha_fin' => $lice->fecha_fin,
            'observaciones' => $lice->observaciones,
            'estado' => $lice->estado,
            'idUsuario' => $lice->idUsuario,
            'idTipoLicencia' => $lice->idTipoLicencia,
            'lugar' => $lice->lugar,
            'motivo' => $lice->motivo,
            'idActividadLectiva' => $lice->idActividadLectiva,
            'actividadNoLectiva' => $lice->actividadNoLectiva,
            'fechaRespuesta' => $lice->fechaRespuesta,
            'departmentId' => $lice->departmentId,
            'sectionId' => $lice->sectionId,
            'requestType' => $lice->requestType,
            'actividadLectiva' => $lice->actividadLectiva,
            'dedicacion' => $lice->dedicacion,
            'goceHaber' => $lice->goceHaber,
            'idCiclo' => $lice->idCiclo
          );
          array_push($datos,$data);
        }
        $dataUser = array(
          'diasLicencia' => $dias,
          'licencias' => $datos
        );
        $response = array(
          'status' => true,
          'message' => 'Licencias del usuario encontradas',
          'body' => $dataUser
        );
      }
      else{
        $response = array(
          'status' => false,
          'message' => 'Licencias no encontradas para el usuario',
          'body' => null
        );

      }
      return response()->json($response);
    }

    public function responseRequest(){
      $response = $this->licenseProvider->response();
      return $response;
    }
}
