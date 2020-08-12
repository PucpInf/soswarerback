<?php

namespace App\Http\Services;

use App\Http\Models\ApoyoEconomico;
use Illuminate\Support\Facades\DB;

class ApoyoEconomicoService {
	public function saveAndRetrieve($apoyoeconomicoDataArray) {

		//$apoyoeconomicoDataArray['estado']=true;//seteo siempre esta opicon por defecto al crear para asegurarme de que se reigstre con estado activo

		$apoyoeconomico = ApoyoEconomico::create($apoyoeconomicoDataArray);

		return $apoyoeconomico;
	}

	public function update($apoyoeconomico, $apoyoeconomicoDataArray){
		//$apoyoeconomico->update($apoyoeconomicoDataArray);
		//$apoyoeconomico = solicitud a aprobar
		//$apoyoeconomicoDataArray = datos a cambiar
		$apoyoeconomico->montoAprobado = $apoyoeconomicoDataArray['montoAprobado'];
		$apoyoeconomico->observacion = $apoyoeconomicoDataArray['observacion'];
		$apoyoeconomico->estado = $apoyoeconomicoDataArray['estado'];
		$apoyoeconomico->fechaRespuesta = $apoyoeconomicoDataArray['fechaRespuesta'];
		//$apoyoeconomico->save();
		return $apoyoeconomico;

	}

	public function updateWallet($user,$cryptocoinalias, $walletData){
		$user->
		$apoyoeconomico->update($apoyoeconomicoDataArray);
		return $apoyoeconomico;

	}

	public function retrieveById($id){
		$apoyoeconomico = ApoyoEconomico::find($id);
		return $apoyoeconomico;
	}

	public function retrieveLastEconomicSupport($userid){
		$lastEconomicSupport = ApoyoEconomico::where('idUsuario',$userid)->where('estado',true)->orderby('fechaViaje','desc')->first();
		if (!$lastEconomicSupport){
			return null;
		}
		return $lastEconomicSupport;
	}

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
    $economicSupport->save();
    return $economicSupport;

	}

}
