<?php

namespace App\Http\Services;

use App\Http\Models\Categoria;


class TipoCategoriaService {

	public function retrieveAll() {
		return Categoria::all();
	}

	public function retrieveById($id) {
		return Categoria::find($id);
	}

	public function obtenerNombreCategoriaPorAbrev ($abrev){
		switch ($abrev) {
		    case "ASO":
		        return "Asociado";
		    case "CON":
		        return "Contratado";
		    case "JPA":
		        return "Jefe de practica";
				case "PRI":
		        return "Principal";
				case "AUX":
						return "Auxiliar";
				case "EME":
		        return "Emerito";
				default:
	      		return null;
		}

	}


	public function retrieveByNombre($nombre){
		return Categoria::where('nombre_categoria',$nombre)->first();
	}

	public function createAndRetrieve($categoriaData){

		$tipoCategoria = new Categoria($categoriaData);
		$tipoCategoria->save();
		return $tipoCategoria;
	}








}
