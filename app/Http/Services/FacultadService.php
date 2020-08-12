<?php

namespace App\Http\Services;

use App\Http\Models\Facultad;

class FacultadService {

  public function retrieveAll(){
    return Facultad::all();
  }

  public function retrieveById($id){
    return Facultad::find($id);
  }

  public function createAndRetrieve($facultadData){

		$facultad = new Facultad($facultadData);
		$facultad->save();
		return $facultad;
	}

  public function retrieveByNombre($nombre){
		return Facultad::where('nombreFacultad',$nombre)->first();
	}

  public function obtenerHorarios($facultad,$idCiclo=null){
    //Obtiene todos los horarios de una facultad en donde el usuario asociado al horario sea de tipo profesor,
    //este caso deberia darse siempre, pero por si las dudas lo limito solo a usuarios de tipo profesor

    $consulta= $facultad->horarios()->whereHas('usuario',function($query){
      $query->where('idTipo',1);
    });
    if($idCiclo!=null){
      $consulta->where('idCiclo',$idCiclo);
    }
    return $consulta;

  }

  public function obtenerHorariosConProfesoresFiltradosPorDepartamentoCiclo($facultad,$idDepartamento,$idCiclo=null){
    //Obtiene todos los horarios de una facultad en donde el usuario asociado al horario sea de tipo profesor,
    //este caso deberia darse siempre, pero por si las dudas lo limito solo a usuarios de tipo profesor
    if($idCiclo!=null){
      $horarios = $this->obtenerHorarios($facultad,$idCiclo);
    }
    else{
      $horarios = $this->obtenerHorarios($facultad);
    }


    $horarios = $horarios->whereHas('curso',function ($query) use ($idDepartamento){
      $query->whereHas('seccion',function($queryPrima) use ($idDepartamento){
        $queryPrima->where('idDepartamento',$idDepartamento);
      });
    });
    return $horarios;
  }
}
