<?php

namespace App\Http\Services;

use App\Http\Models\Usuario;
use App\Http\Models\Horario;
use App\Http\Models\Persona;
use App\Http\Models\GradoAcademico;
use App\Http\Models\DesarrolloDocente;
use App\Http\Models\Departamento;
use App\Http\Models\TipoCategoria;
use App\Http\Models\Investigacion;
use App\Http\Models\ExpedienteProfesional;

class UsuarioService {
    public function retrieveAll() {
        return Usuario::all();
    }

    public function retrieveById($id) {
        return Usuario::find($id);
    }

    public function retrieveTeacherById($teacherid){

        $persona = Persona::whereHas('usuario', function ($query) use($teacherid) {
        $query->where('idTipo', 1)->where('id',$teacherid);
        })->first();
        return $persona;
    }

  public function getTeachers(){
        $personas = Persona::whereHas('usuario', function ($query) {
        $query->where('idTipo', 1);
        })->get();
        foreach ($personas as $persona ) {
            $persona->usuario;
        }
        return $personas;
  }

  public function retrieveByEmailAndGoogleId($email,$idGoogle) {

    $usuario = Usuario::where('correoPucp',$email)->where('idGoogle',$idGoogle)->first();
    if(!$usuario){
        return null;
    }

    if($usuario){
        $usuario->persona;

        $usuario->seccion;
        if($usuario->seccion){
            $usuario->seccion->departamento;
        }
        $usuario->categoria;
        $usuario->tipo;
        $gradosAcademicos = GradoAcademico::where('idUsuario',$usuario->id)->get();
        $grados = array();
        $bua = 1;
        foreach($gradosAcademicos as $grado){
            $data = array(
                'id' => $bua,
                'nombre' => $grado->nombre,
                'institucion' => $grado->institucion,
                'idUsuario' => $grado->idUsuario
            );
            array_push($grados,$data);
            $bua = $bua + 1;
        }
        $usuario->gradoAcad = $grados;
        $desarrolloDocente = DesarrolloDocente::where('idUsuario',$usuario->id)->get();
        $desaDocente = array();
        $n = 1;
        foreach($desarrolloDocente as $gg) {
            $depa = Departamento::where('id',$gg->idDepartamento)->first();
            $categoria = TipoCategoria::where('id',$gg->idCategoria)->first();
            $data = array(
                "id" => $n,
                "departamento" => $depa->nombre,
                "puesto_de_trabajo" => $gg->puesto_de_trabajo,
                "categoria" => $categoria->nombre_categoria,
                "fecha_inicio" => $gg->fecha_inicio,
                "fecha_fin" => $gg->fecha_fin
            );
            array_push($desaDocente,$data);
            $n = $n + 1;
        }
        $usuario->docenciaUniv = $desaDocente;

        $investigaciones = Investigacion::where('idUsuario',$usuario->id)->get();
        $investigas = array();
        $gg = 1;
        foreach($investigaciones as $inv){
            $data = array(
                'id' => $gg,
                'titulo' => $inv->titulo,
                'abstract' => $inv->abstract,
                'link' => $inv->link,
                'fecha_inicio' => $inv->fecha_inicio,
                'fecha_fin' => $inv->fecha_fin,
                'idUsuario' => $inv->idUsuario
            );
            array_push($investigas,$data);
            $gg = $gg + 1;
        }
        $usuario->investigacion = $investigas;

        $eProfesional = ExpedienteProfesional::where('idUsuario',$usuario->id)->get();
        $arreglo = array();
        $lol = 1;
        foreach($eProfesional as $e){
            $data = array(
                'id' => $lol,
                'puesto_de_trabajo' => $e->puesto_de_trabajo,
                'empresa' => $e->empresa,
                'fecha_inicio' => $e->fecha_inicio,
                'fecha_fin' => $e->fecha_fin,
                'idUsuario' => $e->idUsuario,
                'pais' => $e->pais
            );
            array_push($arreglo,$data);
            $lol = $lol + 1;
        }
        $usuario->expProfesional = $arreglo;

    }


    return $usuario;
}

    public function retrieveByEmail($email) {

        $usuario = Usuario::where('correoPucp',$email)->first();
        if(!$usuario){
            return null;
        }

        if($usuario){
            $usuario->persona;

            $usuario->seccion;
            if($usuario->seccion){
                $usuario->seccion->departamento;
            }
            $usuario->categoria;
            $usuario->tipo;
            $gradosAcademicos = GradoAcademico::where('idUsuario',$usuario->id)->get();
            $grados = array();
            $bua = 1;
            foreach($gradosAcademicos as $grado){
                $data = array(
                    'id' => $bua,
                    'nombre' => $grado->nombre,
                    'institucion' => $grado->institucion,
                    'idUsuario' => $grado->idUsuario
                );
                array_push($grados,$data);
                $bua = $bua + 1;
            }
            $usuario->gradoAcad = $grados;
            $desarrolloDocente = DesarrolloDocente::where('idUsuario',$usuario->id)->get();
            $desaDocente = array();
            $n = 1;
            foreach($desarrolloDocente as $gg) {
                $depa = Departamento::where('id',$gg->idDepartamento)->first();
                $categoria = TipoCategoria::where('id',$gg->idCategoria)->first();
                $data = array(
                    "id" => $n,
                    "departamento" => $depa->nombre,
                    "puesto_de_trabajo" => $gg->puesto_de_trabajo,
                    "categoria" => $categoria->nombre_categoria,
                    "fecha_inicio" => $gg->fecha_inicio,
                    "fecha_fin" => $gg->fecha_fin
                );
                array_push($desaDocente,$data);
                $n = $n + 1;
            }
            $usuario->docenciaUniv = $desaDocente;

            $investigaciones = Investigacion::where('idUsuario',$usuario->id)->get();
            $investigas = array();
            $gg = 1;
            foreach($investigaciones as $inv){
                $data = array(
                    'id' => $gg,
                    'titulo' => $inv->titulo,
                    'abstract' => $inv->abstract,
                    'link' => $inv->link,
                    'fecha_inicio' => $inv->fecha_inicio,
                    'fecha_fin' => $inv->fecha_fin,
                    'idUsuario' => $inv->idUsuario
                );
                array_push($investigas,$data);
                $gg = $gg + 1;
            }
            $usuario->investigacion = $investigas;

            $eProfesional = ExpedienteProfesional::where('idUsuario',$usuario->id)->get();
            $arreglo = array();
            $lol = 1;
            foreach($eProfesional as $e){
                $data = array(
                    'id' => $lol,
                    'puesto_de_trabajo' => $e->puesto_de_trabajo,
                    'empresa' => $e->empresa,
                    'fecha_inicio' => $e->fecha_inicio,
                    'fecha_fin' => $e->fecha_fin,
                    'idUsuario' => $e->idUsuario,
                    'pais' => $e->pais
                );
                array_push($arreglo,$data);
                $lol = $lol + 1;
            }
            $usuario->expProfesional = $arreglo;

        }


        return $usuario;
    }

    public function createAndRetrieve($usuarioData){
        $usuario = new Usuario($usuarioData);
        $usuario->id = $usuarioData['codigo']; //(Picho: ¿el id no era autoincrementable)
        if($usuarioData['img'] != "0"){
            $len = strlen($usuarioData['img']);
            $str = substr($usuarioData['img'],-($len-23));
            $img = base64_decode($str);
            $ruta1 = '200.16.7.152/img/Usuarios/';
            //$ruta2 = '/var/www/html/sos_back/public/img/Usuarios/';
            $ruta2 = 'D:/xampp/htdocs/Software/public/img/Usuarios/';
            $ruta1 .= $usuarioData['id'].'.jpg';
            $ruta2 .= $usuarioData['id'].'.jpg';
            file_put_contents($ruta2,$img);
        }
        else{
            $ruta1 = '200.16.7.152/img/Usuarios/default.jpg';
        }
        $usuario->fotoPerfil = $ruta1;
        return $usuario;
    }

    public function createAndRetrieveSimple($usuarioData){
        $usuario = new Usuario($usuarioData);
        $usuario->save();

        return $usuario;
    }

    public function registerUsuarioPersona($persona,$usuario){

        $usuario->idPersona=$persona->id;
        $usuario->save();
        return $usuario;
        //$usuario->persona()->save($persona);
    }

    public function retrieveProfesorById($profesorId){

        $usuario = Usuario::find($profesorId);
        if($usuario){
            if($usuario->idTipo == 1){
                return $usuario;
            }
        }
        return null;
        //$usuario->persona()->save($persona);
    }

    public function retrieveDedicacionesArray(){
        //deveuelve un arreglo de cadenas con los nombres de dedicacion distintos
        //de los profesores
        $dedicaciones = Usuario::where('idTipo',1)->select('especializacion')->distinct()->get();
        $plucked = $dedicaciones->pluck('especializacion');
        $dedicaciones = $plucked->all();
        return $dedicaciones;
    }

    public function obtenerHorariosPorEspecializacion($especializacion){
        //Obtiene todos los horarios de un curso en donde el usuario asociado al horario sea de tipo profesor,
        //este caso deberia darse siempre, pero por si las dudas lo limito solo a usuarios de tipo profesor

        $consulta= Horario::whereHas('usuario',function($query)use($especializacion){
            $query->where('idTipo',1)->where('especializacion',$especializacion);
        });
        return $consulta;

    }

    public function obtenerHorariosPorDedicacion($nombreDedicacion,$idDepartamento,$idCiclo=null){
        //Obtiene todos los horarios de un curso en donde el usuario asociado al horario sea de tipo profesor,
        //este caso deberia darse siempre, pero por si las dudas lo limito solo a usuarios de tipo profesor
        $horarios = $this->obtenerHorariosPorEspecializacion($nombreDedicacion);
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
