<?php

namespace App\Http\Services;

use App\Http\Models\Horario;

class HorarioService {

  public function retrieveAll(){
    return Horario::all();
  }

  public function retrieveById($id){
    return Horario::find($id);
  }

  public function retrieveByNombreYCicloId($nombre, $idCiclo){
		return Horario::where('nombre',$nombre)->where('idCiclo',$idCiclo)->first();
	}

  public function retrieveByIdUsuario($idUsuario){
    return Horario::where('idUsuario',$idUsuario)->get();
  }

  public function createAndRetrieve($horarioData){

		$horario = new Horario($horarioData);
		$horario->save();
		return $horario;
	}


  public function getDictatedHourByHorarioObj($horario){
    $detalles = $horario->horariosDetalle;
    $total=0;
    $total = $detalles->sum(function ($detail) {
        return $detail->horasDictado;
    });
    return $total;

  }

}
