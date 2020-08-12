<?php

namespace App\Http\Services;

use App\Http\Models\Seccion;
use App\Http\Models\Departamento;

class SeccionService {

  public function create($seccionArray){
    /*$seccionCreada =  Seccion::create($seccionArray);
    return $seccionCreada;*/
    $seccion = new Seccion();
    $seccion->nombre = $seccionArray['nombre'];
    $seccion->idDepartamento = $seccionArray['idDepartamento'];
    $seccion->presupuesto = $seccionArray['presupuesto'];
    $seccion->anexo = $seccionArray['anexo'];
    $seccion->correo = $seccionArray['correo'];
    $seccion->save();
    return $seccion;
  }

  public function retrieveById($seccionId){
    return Seccion::find($seccionId);
  }

  public function retrieveByDepartamento($idDepartamento){
    $departamento = Departamento::find($idDepartamento);
    return $departamento->secciones;
  }


  public function obtenerHorarios($seccion,$idCiclo=null){
    //Obtiene todos los horarios de una facultad en donde el usuario asociado al horario sea de tipo profesor,
    //este caso deberia darse siempre, pero por si las dudas lo limito solo a usuarios de tipo profesor

    $consulta= $seccion->horarios()->whereHas('usuario',function($query){
      $query->where('idTipo',1);
    });
    if($idCiclo!=null){
      $consulta->where('idCiclo',$idCiclo);
    }
    return $consulta;

  }

  public function obtenerHorariosConProfesoresFiltradosPorDepartamentoCiclo($seccion,$idDepartamento,$idCiclo=null){
    //Obtiene todos los horarios de una seccion en donde el usuario asociado al horario sea de tipo profesor,
    //este caso deberia darse siempre, pero por si las dudas lo limito solo a usuarios de tipo profesor
    if($idCiclo!=null){
      $horarios = $this->obtenerHorarios($seccion,$idCiclo);
    }
    else{
      $horarios = $this->obtenerHorarios($seccion);
    }

    $horarios = $horarios->whereHas('usuario',function ($query) use ($idDepartamento){
      $query->whereHas('seccion',function($queryPrima) use ($idDepartamento){
        $queryPrima->where('idDepartamento',$idDepartamento);
      });
    });

    // $horarios = $horarios->whereHas('curso',function ($query) use ($idDepartamento){
    //   $query->whereHas('seccion',function($queryPrima) use ($idDepartamento){
    //     $queryPrima->where('idDepartamento',$idDepartamento);
    //   });
    // });
    return $horarios;
  }
}
