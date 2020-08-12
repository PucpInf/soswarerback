<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\Departamento;
use App\Http\Models\Seccion;
use App\Http\Models\Usuario;
use App\Http\Models\Persona;
use App\Http\Models\Curso;
use App\Http\Models\Horario;
use App\Http\Models\Dia;
use App\Http\Models\HorarioDetalle;
use App\Http\Models\TipoCategoria;
use Illuminate\Support\Facades\DB;
use App\Http\Services\DepartamentoService;
use App\Http\Models\ConcursoPorProfesor;

class DepartamentoController extends Controller
{
    protected $departamentoService;
    public function __construct(){
      $this->departamentoService =  new DepartamentoService;
    }
    public function GetDepartamentos ()
    {
    	try
    	{

    		$departamentos = Departamento::all();

	    	$secciones = Seccion::all();
	    	$var = array();
	    	foreach ($departamentos as $dep) {
                /*
	    		$var = array();
			    foreach ($secciones as $sec){
			    	if ($dep->id == $sec->idDepartamento)
			    		array_push($var,$sec);
			    }
			    $dep["secciones"] = $var;
                */
                $dep->secciones;
                foreach ($dep->secciones as $sec) {
                    $sec->profesores;
                    foreach ($sec->profesores as $prof) {
                        $prof->persona;
                    }
                }
			}

			return response()->json(['status' => true,
                'message'=> 'Departamento encontrados',
                'body'=> $departamentos],
                200);
    	}
    	catch(\Exception $e)
    	{
    		return response()->json(['status' => false,
                'message'=> 'Hubo un error',
                'body' => $e->getMessage()],
                500);
    	}

    }

    public function AddDepartamentos(Request $request){

      DB::beginTransaction();
      try {
        $departamentoData= $request->all();
        $departamento = $this->departamentoService->create($departamentoData);

        DB::commit();
        return response()->json(['status' => true, 'message' => "Departamento creado", 'body' => $departamento],200);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json(['status' => false, 'message'=> 'Hubo un error', 'body' => $e->getMessage()],  500);
      }
    }

    public function addDepa(Request $request) {
        //return $request;
        if($request['dptos']) {
            $bua = array();
            foreach($request['dptos'] as $depa){
                if($depa['nombre'] == "") break;
                    if(Departamento::where('id',$depa['id'])->exists()){
                        $dpto = Departamento::where('id',$depa['id'])->first();
                        $dpto->nombre = $depa['nombre'];
                        $dpto->anexo = $depa['anexo'];
                        $dpto->correo = $depa['correo'];
                        $dpto->save();        
                    }
                    else{
                        $newDepa = new Departamento();
                        $newDepa->nombre = $depa['nombre'];
                        $newDepa->anexo = $depa['anexo'];
                        $newDepa->correo = $depa['correo'];
                        $newDepa->save();
                    }
            }
            $depas = Departamento::all();
            $response = array(
                'status' => true,
                'message' => 'Departamentos actualizados',
                'body' => $depas
            );
        }
        else {
            $response = array(
                'status' => false,
                'message' => 'No se mandaron depas para actualizar',
                'body' => null
            );
        }
        return response()->json($response);
    }

    public function GetCursosHorarios ($depa)
    {
        set_time_limit(100);
        try
        {
            $departamentos = Departamento::where('id',$depa)->get();
            $var = array();
            foreach ($departamentos as $dep) {
                    $dep->secciones;
                    foreach ($dep->secciones as $sec) {
                            $sec->cursos;
                            foreach ($sec->cursos as $cur) {
                            $cur->seccion;
                            $cur->tipo;
                            $cur->horarios;
                            foreach ($cur->horarios as $hor) {
                                $hor->usuario;
                                //return $hor->usuario->persona;
                                $hor->usuario->persona;
                                $hor->usuario->persona["nombre_completo"]=$hor->usuario->persona->nombres . ' ' . $hor->usuario->persona->apPaterno . ' ' . $hor->usuario->persona->apMaterno;
                                $hor->curso;
                                array_push($var,$hor);
                            }

                            }
                    }
            }
            return response()->json(['status' => true,
                    'message'=> 'horarios encontrados',
                    'data'=> $var],
                    200);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => false,
                'message'=> 'Hubo un error',
                'body' => $e->getMessage()],
                500);
        }
    }

    public function getHorariosCurso($depa){
        $data = array();
        $secciones = Seccion::where('idDepartamento',$depa)->get();
        foreach($secciones as $seccion){
            $cursos = Curso::where('idSeccion',$seccion->id)->get();
            foreach($cursos as $curso){
                $horarios = Horario::where('idCurso',$curso->id)->get();
                foreach($horarios as $horario){
                    if(Usuario::where('id',$horario->idUsuario)->exists()){
                        $profe = Usuario::where('id',$horario->idUsuario)->first();
                        $persona = Persona::where('id',$profe->idPersona)->first();
                        $people = array(
                            'id' => $persona->id,
                            'nombres' => $persona->nombres,
                            'apPaterno' => $persona->apPaterno,
                            'apMaterno' => $persona->apMaterno,
                            'correo' => $persona->correo,
                            'telefono' => $persona->telefono,
                            'fechaNacimiento' => $persona->fechaNacimiento,
                            'sexo' => $persona->sexo,
                            'pais' => $persona->pais,
                            'nombre_completo' => $persona->nombres.' '.$persona->apPaterno.' '.$persona->apMaterno
                        );
                        $usuario = array(
                            'id' => $profe->id,
                            'contrasena' => $profe->contrasena,
                            'fotoPerfil' => $profe->fotoPerfil,
                            'areaInteres' => $profe->areaInteres,
                            'especializacion' => $profe->especializacion,
                            'idSeccion' => $profe->idSeccion,
                            'idCategoria' => $profe->idCategoria,
                            'idPersona' => $profe->idPersona,
                            'idTipo' => $profe->idTipo,
                            'correoPucp' => $profe->correoPucp,
                            'nuevoUsuario' => $profe->nuevoUsuario,
                            'idCarga' => $profe->idCarga,
                            'persona' => $people
                        );
                        $gg = array(
                            'id' => $horario->id,
                            'nombre' => $horario->nombre,
                            'idUsuario' => $horario->idUsuario,
                            'idCiclo' => $horario->idCiclo,
                            'idCurso' => $horario->idCurso,
                            'horas' => $horario->horas,
                            'usuario' => $usuario,
                            'curso' => $curso
                        );
                    } else{
                        $profe = null;
                        $persona = null;
                        $gg = array(
                            'id' => $horario->id,
                            'nombre' => $horario->nombre,
                            'idUsuario' => $horario->idUsuario,
                            'idCiclo' => $horario->idCiclo,
                            'idCurso' => $horario->idCurso,
                            'horas' => $horario->horas,
                            'usuario' => $profe,
                            'curso' => $curso
                        );
                    }
                    array_push($data,$gg);
                }
            }
        }
        $response = array(
            'status' => true,
            'message' => 'Horarios encontrados',
            'data' => $data 
        );
        return response()->json($response);
    }

    public function GetProfesores ($depa)
    {
        try
        {

            $departamentos = Departamento::where('id',$depa)->get();

            $var = array();
            foreach ($departamentos as $dep) {
                    $dep->secciones;
                    foreach ($dep->secciones as $sec) {
                             $sec->profesores;
                             $count =0;
                             foreach ($sec->profesores as $prof) {
                                 //$prof->profesor;
                                 $user = $prof;
                                 $persona = Persona::where('id',$user->idPersona)->first();
                                 $nombre = $persona->nombres.' '.$persona->apPaterno.' '.$persona->apMaterno;
                                 $prof->nombre = $nombre;
                                 $prof->seccion;
                                 $prof->persona;
                                 array_push($var,$prof);
                             }
                    }
            }
            return response()->json(['status' => true,
                    'message'=> 'Profesores encontrados',
                    'body'=> $var],
                    200);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => false,
                'message'=> 'Hubo un error',
                'body' => $e->getMessage()],
                500);
        }
    }

    public function GetConcursoProfesores ($depa)
    {
        //return "hola";
        try
        {
            // return ConcursoPorProfesor::all();
            $departamentos = Departamento::where('id',$depa)->get();
            $var = array();
            foreach ($departamentos as $dep) {
                    $dep->secciones;
                    foreach ($dep->secciones as $sec) {
                            $sec->profesores;
                            foreach ($sec->profesores as $prof) {
                            $prof->persona;
                            $prof->seccion;
                            $concursos = ConcursoPorProfesor::where('idUsuario',$prof["id"])->where('estado','Pendiente')->get();
                            if (count($concursos))
                            {
                            foreach ($concursos as $conc) {
                                $conc->concurso;
                            }
                            
                            $prof["concursos"]=$concursos;
                            array_push($var,$prof);
                            }
                            }
                    }
            }
            // return $var;
            return response()->json(['status' => true,
                    'message'=> 'profesores con concursos encontrados',
                    'body'=> $var],
                    200);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => false,
                'message'=> 'Hubo un error',
                'body' => $e->getMessage()],
                500);
        }
    }

    public function listaProfesores($idDepartamento){
        if(Usuario::where('idTipo',1)->orWhere('idTipo',3)->orWhere('idTipo',4)->exists()){        
        $profes = array();
        $secciones = Seccion::where('idDepartamento',$idDepartamento)->get();
        foreach($secciones as $seccion){
            if(Usuario::where('idSeccion',$seccion->id)->exists()){
                $profesores = Usuario::where('idTipo',1)->orWhere('idTipo',3)->orWhere('idTipo',4)->get();
                foreach($profesores as $profesor){
                    if($profesor->idSeccion == $seccion->id){
                        //El nombre se extra de la tabla Persona
                        $nombreProfe = Persona::where('id',$profesor->idPersona)->first();
                        //El departamento se obtiene a través del idSeccion del usuario
                        $departamento = Departamento::where('id',$idDepartamento)->first();
                        //La categoria se obtiene a través del idCategoria
                        $categoria = TipoCategoria::where('id',$profesor->idCategoria)->first();
                        $data = array(
                        "codigo" => $profesor->id,
                        "nombre" => $nombreProfe->nombres.' '.$nombreProfe->apPaterno.' '.$nombreProfe->apMaterno,
                        "departamento" => $departamento->nombre,
                        "seccion" => $seccion->nombre,
                        'categoria' => $categoria['nombre_categoria']
                        );
                        array_push($profes,$data);
                    }
                }
            }
        }
        $response = array(
            'status' => true,
            'message' => 'Lista de profesores',
            'data' => $profes
        );
        }
        else{
            $response = array(
                "status" => false,
                "message" => "Lista de profesores vacía",
                "data" => null
            );
      }
        return response()->json($response);
    }

    public function getCursos($idDepartamento){
        $secciones = Seccion::where('idDepartamento',$idDepartamento)->get();
        $courses = array();
        foreach($secciones as $seccion){
            $sec = Seccion::where('id',$seccion->id)->first();
            $cursos = Curso::where('idSeccion',$seccion->id)->get();
            $data = array();
            foreach($cursos as $curso){
              //Cada curso tiene uno o mas horarios
              //Cada horario tiene detalle
              $horarios = Horario::where('idCurso',$curso->id)->get();
              $gg = array();
              foreach($horarios as $horario){
                $nombreHorario = $horario->nombre;
                //De $horario solo necesito el nombre
                $clases = array();
                $detalle = HorarioDetalle::where('idHorario',$horario->id)->get();
                foreach($detalle as $detail){
                  $dia = Dia::where('id',$detail->idDia)->first();
                  $d = array(
                    'id' => $detail->id,
                    'dia' => $dia->dia,
                    'hini' => $detail->horaInicio,
                    'hfin' => $detail->horaFin
                  );
                  array_push($clases,$d);
                }
                $nuevo = array(
                  'horario' => $nombreHorario,
                  'clases' => $clases
                );
                array_push($gg,$nuevo);
              }
              $course = array(
                'nombre' => $curso->nombre,
                'idSeccion' => $curso->idSeccion,
                'creditosTot' => $curso->creditosTot,
                'credTeor' => $curso->credTeor,
                'credPrac' => $curso->credPrac,
                'idFacultad' => $curso->idFacultad,
                'idTipoCurso' => $curso->idTipoCurso,
                'id' => $curso->id,
                'codigo' => $curso->codigo,
                'horarios'=> $gg
              );
              //if($course != null) array_push($data,$course);
              if($course != null) array_push($courses,$course);
            }
            //if($course != null) array_push($courses,$course);
        }
        $response = array(
            'status' => true,
            'message' => 'Cursos encontrados',
            'data' => $courses
        );
        return response()->json($response);
    }

}
