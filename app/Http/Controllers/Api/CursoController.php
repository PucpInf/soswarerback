<?php

namespace App\Http\Controllers\Api;
use App\Http\Models\Curso;
use App\Http\Models\Horario;
use App\Http\Models\HorarioDetalle;
use App\Http\Models\Ciclo;
use App\Http\Models\Facultad;
use App\Http\Models\TipoCurso;
use App\Http\Models\Dia;
use App\Http\Models\Usuario;
use App\Http\Models\Persona;
use App\Http\Services\CursoService;
use App\Http\Services\CicloService;
use App\Http\Services\UsuarioService;
use App\Http\Services\EncuestaService;
use App\Http\Services\DepartamentoService;
use App\Http\Services\SeccionService;
use App\Http\Models\Encuesta;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class CursoController extends Controller
{
    protected $cursoService;
    protected $cicloService;
    protected $encuestaService;
    protected $usuarioService;
    protected $departamentoService;
    protected $seccionService;


    public function __construct(){
      $this->cursoService =  new CursoService();
      $this->cicloService =  new CicloService();
      $this->encuestaService =  new EncuestaService();
      $this->usuarioService =  new UsuarioService();
      $this->departamentoService =  new DepartamentoService();
      $this->seccionService =  new SeccionService();

    }
    public function GetCursosAno($ano)  {
    	try
    	{
	        $ciclo1 = $ano . '-1';
	        $ciclo2 = $ano . '-2';
	        $ciclos = Ciclo::where('ciclo', $ciclo1)->orwhere('ciclo',$ciclo2)->get();

	        $cursosano = Horario::where('idCiclo',$ciclos[0]->id)->orwhere('idCiclo',$ciclos[1]->id)->get();

          foreach ($cursosano as &$valor) {
            $valor->curso;
          }


          $cursos["anno"] = $ano;
          if ($ciclos[0]->ciclo == $ciclo1) {
            $cursos["ciclo1"] = $ciclos[0]->id;
            $cursos["ciclo2"] = $ciclos[1]->id;
          } else {
            $cursos["ciclo1"] = $ciclos[1]->id;
            $cursos["ciclo2"] = $ciclos[0]->id;
          }
          $cursos["cursos"]=$cursosano;

			return response()->json(['status' => true,
	            'message'=> 'Cursos por año encontrados',
	            'body'=> $cursos],

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

    public function GetCursosCiclo($idDepartamento=null, $idSeccion=null,$idCiclo=null){
      try {
        $message="Horarios obtenidos";
        $consultaHorario = Horario::whereHas('usuario',function($query){
          $query->where('idTipo',1); //1 es el id para el tipo profesor, si se permitieram regresar cursos asociados a usuarios de otros tipos, se puede borrar esta linea
        });
        if( $idDepartamento!=null   ){

          $message .= ", departamento";
          $consultaHorario = $consultaHorario->whereHas('curso',function($query)use ($idDepartamento){
                                    $query->whereHas('seccion',function($queryPrima) use ($idDepartamento){
                                        $queryPrima->where('seccion.idDepartamento',$idDepartamento);
                                     });
                              });
          if($idSeccion!=null){
            $message .= ", y sección";
            $consultaHorario = $consultaHorario->whereHas('curso',function($query) use ($idSeccion){
              $query->where('idSeccion',$idSeccion);
            });

            if($idCiclo!=null){
                $message .= ", por ciclo";
                $consultaHorario = $consultaHorario->where('idCiclo',$idCiclo);
            }
          }
        }

        $horarios =  $consultaHorario->get();
        $message .= "  - (Ruta: /api/curso/GetHorariosPorCiclo/{idDepartamento?}/{idSeccion?}/{idCiclo?})";
        if(count($horarios)==0){
          return response()->json(['status'=>false,'message' => "No se encontraron horarios",'body'=>null ],404);
        }
        $body =array();
        foreach ($horarios as $horario) {

          $obj = array ('idHorario'=>null,'horario'=>null,'codigoCurso'=>null,'idProfesor'=>null,'nombreCurso'=>null,
                        'nombreProfesor'=>null,'apPaternoProfesor'=>null,
                        'apMaternoProfesor' => null,'idCiclo' => null,'ciclo' => null,'idDepartamento' => null,
                        'nombreDepartamento' => null,'idSeccion' => null,'nombreSeccion' => null,'numeroMatriculados'=>null
                        ,'numeroEncuestasValidas' => null,'puntajeFinal' =>null);
          $obj['idHorario'] = $horario->id;
          $obj['horario'] = $horario->nombre;
          if($horario->ciclo){
            $obj['ciclo'] = $horario->ciclo->ciclo;
            $obj['idCiclo'] = $horario->ciclo->id;
          }
          if($horario->usuario){
            $persona = $horario->usuario->persona;
            $obj['idProfesor']=$horario->usuario->id;
            $obj['nombreProfesor']=$persona->nombres;
            $obj['apPaternoProfesor']=$persona->apPaterno;
            $obj['apMaternoProfesor']=$persona->apMaterno;
          }
          if($horario->curso){
            $curso =  $horario->curso;
            $obj['nombreCurso'] = $curso->nombre;
            $obj['codigoCurso'] = $curso->codigo;
            $seccion = $curso->seccion;
            if($seccion){
              $obj['idSeccion'] = $seccion->id;
              $obj['nombreSeccion'] = $seccion->nombre;
              if($seccion->departamento){
                $obj['nombreDepartamento'] = $seccion->departamento->nombre;
                $obj['idDepartamento'] = $seccion->departamento->id;
              }
            }
          }
          if($horario->encuesta){
            $obj['numeroMatriculados'] = $horario->encuesta->numeroAlumnos;
            $obj['numeroEncuestasValidas'] = $horario->encuesta->numeroContestados;
            $encuesta = $horario->encuesta;
            //$obj['puntajeFinal'] = $encuesta->items->sum('pivot.puntaje');
            $obj['puntajeFinal'] = $encuesta->puntajeFinal;

          }
          array_push($body,$obj);
        }
        return response()->json(['status'=>true,'message' => $message,'body'=>$body ],200);
      } catch (\Exception $e) {
        return response()->json(['status' => false,'message'=> 'Hubo un error', 'body' => $e->getMessage()], 500);
      }

    }

    public function GetCursosCursoCiclo($cursoId,$cicloId, $departamentoId=null, $seccionId=null){
      //CursoId en realidad es el codigo de curso

      try {
        $curso = $this->cursoService->retrieveCursoByCodigo($cursoId);
        if(!$curso){
          return response()->json(['status'=>false,'message' => "No se encontró el curso",'body'=>null ],404);
        }
        $message="Horarios de determinado curso obtenidos por ciclo";
        $consultaHorario = Horario::where('idCiclo',$cicloId)->where('idCurso',$curso->id)->whereHas('usuario',function($query){
          $query->where('idTipo',1); //1 es el id para el tipo profesor, si se permitieram regresar cursos asociados a usuarios de otros tipos, se puede borrar esta linea
        });
        if($departamentoId!=null){
          $message .= ", departamento";
          $consultaHorario = $consultaHorario->whereHas('curso',function($query)use ($departamentoId){
                                    $query->whereHas('seccion',function($queryPrima) use ($departamentoId){
                                        $queryPrima->where('seccion.idDepartamento',$departamentoId);
                                     });
                              });
          if($seccionId!=null){
              $message .= ", y sección";
              $consultaHorario = $consultaHorario->whereHas('curso',function($query) use ($seccionId){
                $query->where('idSeccion',$seccionId);
              });
          }
        }
        $horarios =  $consultaHorario->get();
        $message .= "  - (Ruta: /api/curso/GetHorariosPorCursoCiclo/{idCurso}/{idDepartamento?}/{idSeccion?})";

        if(count($horarios)==0){
          return response()->json(['status'=>false,'message' => "No se encontraron horarios del curso",'body'=>null ],404);
        }
        $body =array();
        foreach ($horarios as $horario) {

          $obj = array ('idHorario'=>null,'horario'=>null,'codigoCurso'=>null,'nombreCurso'=>null,'idProfesor'=>null,
                        'nombreProfesor'=>null,'apPaternoProfesor'=>null,
                        'apMaternoProfesor' => null,'idCiclo' => null,'ciclo' => null,'idDepartamento' => null,
                        'nombreDepartamento' => null,'idSeccion' => null,'nombreSeccion' => null,'numeroMatriculados'=>null
                        ,'numeroEncuestasValidas' => null,'puntajeFinal' =>null);
          $obj['idHorario'] = $horario->id;
          $obj['horario'] = $horario->nombre;
          if($horario->ciclo){
            $obj['ciclo'] = $horario->ciclo->ciclo;
            $obj['idCiclo'] = $horario->ciclo->id;
          }
          if($horario->usuario){
            $persona = $horario->usuario->persona;
            $obj['idProfesor']=$horario->usuario->id;
            $obj['nombreProfesor']=$persona->nombres;
            $obj['apPaternoProfesor']=$persona->apPaterno;
            $obj['apMaternoProfesor']=$persona->apMaterno;
          }
          if($horario->curso){
            $curso =  $horario->curso;
            $obj['nombreCurso'] = $curso->nombre;
            $obj['codigoCurso'] = $curso->codigo;
            $seccion = $curso->seccion;
            if($seccion){
              $obj['idSeccion'] = $seccion->id;
              $obj['nombreSeccion'] = $seccion->nombre;
              if($seccion->departamento){
                $obj['nombreDepartamento'] = $seccion->departamento->nombre;
                $obj['idDepartamento'] = $seccion->departamento->id;
              }
            }
          }
          if($horario->encuesta){
            $obj['numeroMatriculados'] = $horario->encuesta->numeroAlumnos;
            $obj['numeroEncuestasValidas'] = $horario->encuesta->numeroContestados;
            $encuesta = $horario->encuesta;
            //$obj['puntajeFinal'] = $encuesta->items->sum('pivot.puntaje');
            $obj['puntajeFinal'] = $encuesta->puntajeFinal;
          }
          array_push($body,$obj);
        }
        return response()->json(['status'=>true,'message' => $message,'body'=>$body ],200);
      } catch (\Exception $e) {
        return response()->json(['status' => false,'message'=> 'Hubo un error', 'body' => $e->getMessage()], 500);
      }
    }

    public function GetCursosGroupByCiclo($cursoId,$departamentoId=null,$seccionId=null){

      try {
        $curso = $this->cursoService->retrieveCursoByCodigo($cursoId);
        if(!$curso){
          return response()->json(['status'=>false,'message' => "No se encontró el curso",'body'=>null ],404);
        }
        $message="Puntaje de encuestas de determinado curso por ciclo";
        $consultaHorario = Horario::where('idCurso',$curso->id)->whereHas('usuario',function($query){
          $query->where('idTipo',1); //1 es el id para el tipo profesor, si se permitieram regresar cursos asociados a usuarios de otros tipos, se puede borrar esta linea
        });
        if($departamentoId!=null){
          $message .= ", departamento";
          $consultaHorario = $consultaHorario->whereHas('curso',function($query)use ($departamentoId){
                                    $query->whereHas('seccion',function($queryPrima) use ($departamentoId){
                                        $queryPrima->where('seccion.idDepartamento',$departamentoId);
                                     });
                              });
          if($seccionId!=null){
              $message .= ", y sección";
              $consultaHorario = $consultaHorario->whereHas('curso',function($query) use ($seccionId){
                $query->where('idSeccion',$seccionId);
              });
          }
        }


        $cursoCiclos =  $consultaHorario->groupBy('idCiclo')->groupBy('idCurso')->select('idCiclo','idCurso')->get();
        $message .= "  - (Ruta: /api/curso/GetCursosGroupByCiclo/{idCurso}/{idDepartamento?}/{idSeccion?})";
        if(count($cursoCiclos)==0){
          return response()->json(['status'=>false,'message' => "No se encontraron horarios del curso",'body'=>null ],404);
        }
        $body =array();
        foreach ($cursoCiclos as $cursoCiclo) {
          $curso = $this->cursoService->retrieveById($cursoCiclo->idCurso);
          $ciclo = $this->cicloService->retrieveById($cursoCiclo->idCiclo);

          $obj = array ('codigoCurso'=>null,'nombreCurso'=>null,'max'=>null,'min'=>null,
                        'media' => null,'idCiclo' => null,'ciclo' => null,'idDepartamento' => null,
                        'nombreDepartamento' => null,'idSeccion' => null,'nombreSeccion' => null);

          $obj['ciclo'] = $ciclo->ciclo;
          $obj['idCiclo'] = $ciclo->id;
          $obj['codigoCurso'] = $curso->codigo;
          $obj['nombreCurso'] = $curso->nombre;


          if($curso->seccion){
            $seccion = $curso->seccion;
            $obj['idSeccion'] = $seccion->id;
            $obj['nombreSeccion'] = $seccion->nombre;
            if($seccion->departamento){
              $obj['nombreDepartamento'] = $seccion->departamento->nombre;
              $obj['idDepartamento'] = $seccion->departamento->id;
            }
          }

          if($curso->encuestas){
                $encuestas = $curso->encuestas;
                $obj['min'] = $this->encuestaService->calculateMin($encuestas);
                $obj['max'] = $this->encuestaService->calculateMax($encuestas);
                $obj['media'] = $this->encuestaService->calculateAvg($encuestas);
          }
          array_push($body,$obj);
        }
        return response()->json(['status'=>true,'message' => $message,'body'=>$body ],200);
      } catch (\Exception $e) {
        return response()->json(['status' => false,'message'=> 'Hubo un error', 'body' => $e->getMessage()], 500);
      }

    }

    public function GetProfesoresGroupByCiclo($profesorId,$departamentoId=null,$seccionId=null){
      try {

          //         select id,nombre,"idCiclo","idSeccion" from curso
          // group by id,nombre,"idCiclo","idSeccion"
          $profesor = $this->usuarioService->retrieveProfesorById($profesorId);
          if(!$profesor){
            return response()->json(['status'=>false,'message' => "No se encontró el profesor",'body'=>null ],404);
          }
          $message="Profesores agrupados por ciclo";
          $consultaHorario = Horario::where('idUsuario',$profesor->id);

          if($departamentoId!=null){
            $message .= ", departamento";
            //Se debe asegurar que los horarios obtenidos y el profesor obtenido sean del departamento y seccion seteadas en los parametros
            $consultaHorario = $consultaHorario->whereHas('curso',function($query)use ($departamentoId){
                                      $query->whereHas('seccion',function($queryPrima) use ($departamentoId){
                                          $queryPrima->where('seccion.idDepartamento',$departamentoId);
                                       });
                                });//->whereHas('usuario',function($query)use ($departamentoId){
                                //                 $query->whereHas('seccion',function($queryPrima) use ($departamentoId){
                                //                     $queryPrima->where('seccion.idDepartamento',$departamentoId);
                                //                 });
                                //     });


            if($seccionId!=null){
                $message .= ", y sección";
                $consultaHorario = $consultaHorario->whereHas('curso',function($query) use ($seccionId){
                  $query->where('idSeccion',$seccionId);
                })->whereHas('usuario',function($query) use ($seccionId){
                  $query->where('idSeccion',$seccionId);
                });
            }
          }

          $cursoProfesores =  $consultaHorario->groupBy('idCiclo')->groupBy('idUsuario')->select('idCiclo','idUsuario')->get();
          $message .= "  - (Ruta: /api/curso/GetProfesoresGroupByCiclo/{idUsuario}}/{idDepartamento?}/{idSeccion?})";
          if(count($cursoProfesores)==0){
            return response()->json(['status'=>false,'message' => "No se encontraron horarios del profesor con estos parametros",'body'=>null ],404);
          }
          $body =array();

          if($departamentoId){
            $departamento = $this->departamentoService->retrieveById($departamentoId);
          }
          if($seccionId){
            $seccion = $this->seccionService->retrieveById($seccionId);
          }

          foreach ($cursoProfesores as $key=> $cursoProfesor) {

            $ciclo = $this->cicloService->retrieveById($cursoProfesor->idCiclo);



            $obj = array ('idProfesor'=>null,'nombreProfesor'=>null,'apPaternoProfesor'=>null,'apMaternoProfesor' => null,
                          'max'=>null,'min'=>null, 'media' => null,'idCiclo' => null,'ciclo' => null,
                          'idDepartamentoFiltro' => null, 'nombreDepartamentoFiltro' => null,'idSeccionFiltro' => null,
                          'nombreSeccionFiltro' => null);

            $obj['ciclo'] = $ciclo->ciclo;
            $obj['idCiclo'] = $ciclo->id;
            $obj['idProfesor'] = $profesor->id;
            $obj['nombreProfesor'] = $profesor->persona->nombres;
            $obj['apPaternoProfesor'] = $profesor->persona->apPaterno;
            $obj['apMaternoProfesor'] = $profesor->persona->apMaterno;
            $obj['nombreDepartamentoFiltro'] = 'Todos';
            $obj['idDepartamentoFiltro'] = 'Todos';
            $obj['nombreSeccionFiltro'] = 'Todos';
            $obj['idSeccionFiltro'] = 'Todos';

            $queryEncuestas = $profesor->encuestas()->whereHas('horario',function($query) use ($ciclo){
              $query->where("idCiclo", $ciclo->id);
            });



            if($departamentoId){
              $obj['nombreDepartamentoFiltro'] = $departamento->nombre;
              $obj['idDepartamentoFiltro'] = $departamento->id;

              $queryEncuestas = $queryEncuestas->whereHas('horario',function ($q1)use($departamentoId){
                $q1->whereHas('curso',function ($q2)use($departamentoId){
                  $q2->whereHas('seccion',function($q3)use($departamentoId){
                    $q3->where('idDepartamento', $departamentoId);
                  });
                });
              });
            }

            if($seccionId){
              $obj['nombreSeccionFiltro'] = $seccion->nombre;
              $obj['idSeccionFiltro'] = $seccion->id;

              $queryEncuestas = $queryEncuestas->whereHas('horario',function ($q1)use ($seccionId){
                $q1->whereHas('curso',function ($q2)use ($seccionId){
                  $q2->where('idSeccion',$seccionId);
                });
              });
            }

            $encuestas = $queryEncuestas->get();

            if($encuestas){
                  $obj['min'] = $this->encuestaService->calculateMin($encuestas);
                  $obj['max'] = $this->encuestaService->calculateMax($encuestas);
                  $obj['media'] = $this->encuestaService->calculateAvg($encuestas);
            }
            array_push($body,$obj);
          }
          return response()->json(['status'=>true,'message' => $message,'body'=>$body ],200);

      } catch (\Exception $e) {
          return response()->json(['status' => false,'message'=> 'Hubo un error', 'body' => $e->getLine().") ".$e->getMessage()], 500);
      }

    }

    public function GetCursos(){
      try
      {

          $cursos = Curso::select('nombre','id')->get();


      return response()->json(['status' => true,
              'message'=> 'Cursos encontrados',
              'body'=> $cursos],

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

    public function GetTipoCursosAno($ano, $tipoCurso, $dep){
      try
      {
          $ciclo1 = $ano . '-1';
          $ciclo2 = $ano . '-2';
          $ciclos = Ciclo::where('ciclo', $ciclo1)->orwhere('ciclo',$ciclo2)->get();

          $cursosano = Horario::where('idCiclo',$ciclos[0]->id)->orwhere('idCiclo',$ciclos[1]->id)->get();

          $miarray = array();

          foreach ($cursosano as &$valor) {
            $valor->curso;
            $valor->curso->seccion;
            if ($valor->curso->seccion->idDepartamento == $dep)
            {
              if ($valor->curso->idTipoCurso == $tipoCurso)
                {
                  $repite = false;
                  foreach ($miarray as &$arr) {
                    if($arr->nombre == $valor->curso->nombre)
                    {
                      $repite = true;
                      break;
                    }
                  }
                  if(!$repite)
                  {
                    //$valor->curso["idCurso"] = $valor->idCurso;
                    $miarray[] = $valor->curso;
                  }
              }
            }
          }
          // return $cursosano;
          $cursos["ano"] = $ano;
          if ($ciclos[0]->ciclo == $ciclo1) {
            $cursos["ciclo1"] = $ciclos[0]->id;
            $cursos["ciclo2"] = $ciclos[1]->id;
          } else {
            $cursos["ciclo1"] = $ciclos[1]->id;
            $cursos["ciclo2"] = $ciclos[0]->id;
          }
          $cursos["cursos"]=$miarray;

      return response()->json(['status' => true,
              'message'=> 'Cursos del tipo elegido por año encontrados',
              'body'=> $cursos],

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

    public function AddCurso(Request $request){
      set_time_limit(500);
      try
      {
        //Verificar si el curso ya está (modificacion de valores)
        if(Curso::where('codigo',$request->codigo)->exists()){
          $curso = Curso::where('codigo',$request->codigo)->first();
          $curso->nombre = $request->nombre;
          $curso->idSeccion = $request->idseccion;
          $curso->credTeor = $request->credteo;
          $curso->credPrac = $request->credprac;
          $curso->idFacultad = $request->facultad;
          $curso->idTipoCurso = $request->idtipo;
          $curso->codigo = $request->codigo;
          $curso->creditosTot = $request->credtot;
          $idCiclo = $request->idciclo;
          if($request->horarios != null) {
            foreach($request->horarios as $horario) {
              //Cada registro tiene un campo horario y otro clases
              $h = Horario::where('nombre',$horario['horario'])->first();
              if(Horario::where('nombre',$horario['horario'])->exists()){
                //Editar horario encontrado para el curso
                $h->nombre = $horario['horario'];
                foreach($horario['clases'] as $clase) {
                  //clase tiene dia, horainicio, hora fin
                  $id = $clase['id'];
                  $dia = $clase['dia']['dia'];
                  $d = Dia::where('dia',$dia)->first();
                  $horaIni = $clase['hini']['hora'];
                  $horaFin = $clase['hfin']['hora'];
                  $horas = intval($horaFin) - intval($horaIni);
                  if($id == 0){
                    //Nueva clase para el curso
                    $detalle = new HorarioDetalle();
                    $detalle->idHorario = $h['id'];
                    $detalle->idDia = $d->id;
                    $detalle->horaInicio = $horaIni;
                    $detalle->horaFin = $horaFin;
                    $detalle->horasDictado = $horas;
                    $detalle->save();
                  } else {
                    //Modifico el detalle
                    $detalle = HorarioDetalle::where('id',$id)->first();
                    $detalle->idDia = $d->id;
                    $detalle->horaInicio = $horaIni;
                    $detalle->horaFin = $horaFin;
                    $detalle->horasDictado = $horas;
                    $detalle->save();
                  }
                }
              }
              else{
                //Nuevo horario para el curso
                $nuevo = new Horario();
                $nuevo->nombre = $horario['horario'];
                $nuevo->idCiclo = $idCiclo;
                $nuevo->idCurso = $curso->id;
                $nuevo->save();
                foreach($horario['clases'] as $clase) {
                  //clase tiene dia, horainicio, hora fin
                  $id = $clase['id'];
                  $dia = $clase['dia']['dia'];
                  $d = Dia::where('dia',$dia)->first();
                  $horaIni = $clase['hini']['hora'];
                  $horaFin = $clase['hfin']['hora'];
                  $horas = intval($horaFin) - intval($horaIni);
                  if($id == 0){
                    //Nueva clase para el curso
                    $detalle = new HorarioDetalle();
                    $detalle->idHorario = $h['id'];
                    $detalle->idDia = $d->id;
                    $detalle->horaInicio = $horaIni;
                    $detalle->horaFin = $horaFin;
                    $detalle->horasDictado = $horas;
                    $detalle->save();
                  } else {
                    //Modifico el detalle
                    $detalle = HorarioDetalle::where('id',$id)->first();
                    $detalle->idDia = $d->id;
                    $detalle->horaInicio = $horaIni;
                    $detalle->horaFin = $horaFin;
                    $detalle->horasDictado = $horas;
                    $detalle->save();
                  }
                }
              }
            }             
          }
          $curso->save();
        }
        else{ 
          $curso = new Curso();
          $curso->nombre = $request->nombre;
          $curso->idSeccion = $request->idseccion;
          $curso->credTeor = $request->credteo;
          $curso->credPrac = $request->credprac;
          $curso->idFacultad = $request->facultad;
          $curso->idTipoCurso = $request->idtipo;
          $curso->codigo = $request->codigo;
          $curso->creditosTot = $request->credtot;
          $curso->save();
          $cursillo = Curso::where('nombre',$request->nombre)->first();
          $idCurso = $cursillo->id;
          $idCiclo = $request->idciclo;
          if($request->horarios != null) {
            foreach($request->horarios as $horario) {
              //Cada registro tiene un campo horario y otro clases
              $h = Horario::where('nombre',$horario['horario'])->first();
              if(Horario::where('nombre',$horario['horario'])->exists()){
                //Editar horario encontrado para el curso
                $h->nombre = $horario['horario'];
                foreach($horario['clases'] as $clase) {
                  //clase tiene dia, horainicio, hora fin
                  $id = $clase['id'];
                  $dia = $clase['dia']['dia'];
                  $d = Dia::where('dia',$dia)->first();
                  $horaIni = $clase['hini']['hora'];
                  $horaFin = $clase['hfin']['hora'];
                  $horas = intval($horaFin) - intval($horaIni);
                  if($id == 0){
                    //Nueva clase para el curso
                    $detalle = new HorarioDetalle();
                    $detalle->idHorario = $h['id'];
                    $detalle->idDia = $d->id;
                    $detalle->horaInicio = $horaIni;
                    $detalle->horaFin = $horaFin;
                    $detalle->horasDictado = $horas;
                    $detalle->save();
                  } else {
                    //Modifico el detalle
                    $detalle = HorarioDetalle::where('id',$id)->first();
                    $detalle->idDia = $d->id;
                    $detalle->horaInicio = $horaIni;
                    $detalle->horaFin = $horaFin;
                    $detalle->horasDictado = $horas;
                    $detalle->save();
                  }
                }
              }
              else{
                //Nuevo horario para el curso
                $nuevo = new Horario();
                $nuevo->nombre = $horario['horario'];
                $nuevo->idCiclo = $idCiclo;
                $nuevo->idCurso = $idCurso;
                $nuevo->save();
                $gg = Horario::where('nombre',$horario['horario'])->first();
                $idHorario = $gg->id;
                foreach($horario['clases'] as $clase) {
                  //clase tiene dia, horainicio, hora fin
                  $id = $clase['id'];
                  $dia = $clase['dia']['dia'];
                  $d = Dia::where('dia',$dia)->first();
                  $horaIni = $clase['hini']['hora'];
                  $horaFin = $clase['hfin']['hora'];
                  $horas = intval($horaFin) - intval($horaIni);
                  if($id == 0){
                    //Nueva clase para el curso
                    $detalle = new HorarioDetalle();
                    $detalle->idHorario = $idHorario;
                    $detalle->idDia = $d->id;
                    $detalle->horaInicio = $horaIni;
                    $detalle->horaFin = $horaFin;
                    $detalle->horasDictado = $horas;
                    $detalle->save();
                  } else {
                    //Modifico el detalle
                    $detalle = HorarioDetalle::where('id',$id)->first();
                    $detalle->idDia = $d->id;
                    $detalle->horaInicio = $horaIni;
                    $detalle->horaFin = $horaFin;
                    $detalle->horasDictado = $horas;
                    $detalle->save();
                  }
                }
              }
            }
          }
        }
        return response()->json(['status' => true,
              'message'=> 'Curso agregado',
              'body'=> $curso],

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
  

    public function getUserCourses (){
      $this->validate(request(), [
        'userId' => 'required'
      ]);
      // dd(request()->all());

      $term = app('App\Http\Controllers\Api\CicloController')->getCurrentTerm();
      
      $term = $term->getData()->body[0]->id;
      // // dd(request()->id);
      $response = app('App\Http\Controllers\Api\HorarioController')->GetCursosProfesorCiclo(request()->userId, $term)->getData()->body;
      // dd($response);
      $courses = array_map(function($data) {
        return $data->curso;
      }, $response);
      return response()->json(['status' => 'true', 'message' => 'Cursos que dicta el usuario', 'body' => $courses], 200);

    }

    public function getInfoCurso($idCurso){
      $curso = Curso::where('id',$idCurso)->first();
      //Horario con profe
      $facultad = Facultad::where('id',$curso->idFacultad)->first();
      $tipo = TipoCurso::where('id',$curso->idTipoCurso)->first();
      $horarios = Horario::where('idCurso',$idCurso)->get();
      $arrayHorarios = array();
      foreach($horarios as $horario){
        //return $horario['id'];
        $clases = HorarioDetalle::where('idHorario',$horario['id'])->get();
        //return $clases;
        $arrayClasesCurso = array();
        foreach($clases as $class){
          $dia = Dia::where('id',$class['idDia'])->first();
          $arrayClases = array(
            'dia' => $dia->dia,
            'hini' => $class->horaInicio,
            'hfin' => $class->horaFin
          );
          array_push($arrayClasesCurso,$arrayClases);
        }
        //return $arrayClasesCurso;
        $profe = Usuario::where('id',$horario['idUsuario'])->first();
        if($profe != null) {
          $infoProfe = Persona::where('id',$profe->idPersona)->first();
          $nombre = $infoProfe->nombres.' '.$infoProfe->apPaterno.' '.$infoProfe->apMaterno;
        }
        else{
          $nombre = null;
        }
        $gg = array(
          'nombre' => $curso->nombre,
          'yafue' => $horario['nombre'],
          'profesor' => $nombre,
          'clases' => $arrayClasesCurso
        );
        array_push($arrayHorarios,$gg);
      }
      $body = array(
        'facultad' => $facultad->nombreFacultad,
        'tipoCurso' => $tipo->nombre,
        'horarios' => $arrayHorarios
      );
      $response = array(
        'status' => true,
        'message' => 'Información del curso encontrada',
        'data' => $body
      );
      return response()->json($response);
    }

}
