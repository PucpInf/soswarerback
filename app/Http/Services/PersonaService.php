<?php

namespace App\Http\Services;

use App\Http\Models\Persona;


class PersonaService {


	public function createAndRetrieve($usuarioData){

		$persona = new Persona($usuarioData);
		$persona->save();
		return $persona;
	}

	public function retrieveById($id){
		return Persona::find($id);
	}


}
