<?php

namespace App\Http\Services;

use App\Http\Models\Departamento;

class DepartamentoService {

  public function create($departamentoArray){
    /*$departamentoCreado =  Departamento::create($departamentoArray);
    return $departamentoCreado;*/
    $depa = new Departamento();
    $depa->nombre = $departamentoArray['nombre'];
    $depa->anexo = $departamentoArray['anexo'];
    $depa->correo = $departamentoArray['correo'];
    $depa->save();
    return $depa;
  }

  public function retrieveById($id){
    return Departamento::find($id);
  }
}
