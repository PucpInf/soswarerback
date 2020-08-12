<?php

namespace App\Http\Services;

use App\Http\Models\Curso;
use App\Http\Models\Encuesta;

class CursoService {
  public function retrieveAll(){
    return Curso::all();
  }

  public function createAndRetrieve($cursoData){

		$curso = new Curso($cursoData);
		$curso->save();
		return $curso;
	}


  public function retrieveById($id){
    return Curso::find($id);
  }

  public function retrieveByCodigo($codigo){
    return Curso::where('codigo',$codigo)->first();
  }
  public function retrieveEncuestas($curso){

    return Encuesta::where('idCurso',$curso->id)->where('horario',$curso->horario)->where('idCiclo',$curso->idCiclo)->get();
  }

  public function retrieveEncuesta($curso){

    return Encuesta::where('idCurso',$curso->id)->where('horario',$curso->horario)->where('idCiclo',$curso->idCiclo)->first();
  }

  public function retrieveEncuestasCursoCiclo($curso){
    return Encuesta::where('idCurso',$curso->id)->where('idCiclo',$curso->idCiclo)->get();
  }

  public function retrieveEncuestasProfesorCiclo($curso){
    return Encuesta::where('idUsuario',$curso->idUsuario)->where('idCiclo',$curso->idCiclo)->get();
  }
  public function retrieveCursoByCodigo($codigo){
    return Curso::where('codigo',$codigo)->first();
  }

  public function obtenerHorarios($curso){
    //Obtiene todos los horarios de un curso en donde el usuario asociado al horario sea de tipo profesor,
    //este caso deberia darse siempre, pero por si las dudas lo limito solo a usuarios de tipo profesor

    $consulta= $curso->horarios()->whereHas('usuario',function($query){
      $query->where('idTipo',1);
      //$query->where('idTipo','>',0);
    });

    return $consulta;

  }

  public function obtenerHorariosConProfesoresFiltradosPorDepartamentoCiclo($curso,$idDepartamento,$idCiclo=null){
    //Obtiene todos los horarios de un curso en donde el usuario asociado al horario sea de tipo profesor,
    //este caso deberia darse siempre, pero por si las dudas lo limito solo a usuarios de tipo profesor
    $horarios = $this->obtenerHorarios($curso);

    if($idCiclo!=null){
      $horarios = $horarios->where('idCiclo',$idCiclo);


    $horarios = $horarios->whereHas('curso',function ($query) use ($idDepartamento){
      $query->whereHas('seccion',function($queryPrima) use ($idDepartamento){
        $queryPrima->where('idDepartamento',$idDepartamento);
      });
    });
    return $horarios;
  }


}
}
