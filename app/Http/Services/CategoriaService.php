<?php

namespace App\Http\Services;

use App\Http\Models\Categoria;

class CategoriaService {

  public function retrieveAll(){
    return Categoria::all();
  }

  public function retrieveById($id){
    return Categoria::find($id);
  }

  public function obtenerHorarios($categoria){
    //Obtiene todos los horarios de una categoria en donde el usuario asociado al horario sea de tipo profesor,
    //este caso deberia darse siempre, pero por si las dudas lo limito solo a usuarios de tipo profesor

    $consulta= $categoria->horarios()->whereHas('usuario',function($query){
      $query->where('idTipo',1);
    });

    return $consulta;

  }

  public function obtenerHorariosConProfesoresFiltradosPorDepartamentoCiclo($categoria,$idDepartamento,$idCiclo=null){
    //Obtiene todos los horarios de una categoria en donde el usuario asociado al horario sea de tipo profesor,
    //este caso deberia darse siempre, pero por si las dudas lo limito solo a usuarios de tipo profesor
    $horarios = $this->obtenerHorarios($categoria);
    if($idCiclo!=null){
      $horarios = $horarios->where('idCiclo',$idCiclo);
    }

    $horarios = $horarios->whereHas('curso',function ($query) use ($idDepartamento){
      $query->whereHas('seccion',function($queryPrima) use ($idDepartamento){
        $queryPrima->where('idDepartamento',$idDepartamento);
      });
    });
    return $horarios;
  }



}
