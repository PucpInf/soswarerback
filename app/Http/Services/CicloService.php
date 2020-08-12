<?php

namespace App\Http\Services;

use App\Http\Models\Ciclo;


class CicloService {
  public function retrieveById($id){
    return Ciclo::find($id);
  }

  public function retrieveByNombre($nombre){
		return Ciclo::where('ciclo',$nombre)->first();
	}
  public function createAndRetrieve($cicloData){

		$ciclo = new Ciclo($cicloData);
		$ciclo->save();
		return $ciclo;
	}


}
