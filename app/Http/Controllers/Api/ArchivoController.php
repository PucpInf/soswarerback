<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Http\Models\Usuario;
use App\Http\Models\Investigacion;

use App\Http\Services\ArchivoService;
use App\Http\Services\EncuestaService;
use App\Http\Services\PersonaService;
use App\Http\Services\UsuarioService;
use App\Http\Services\HorarioService;
use App\Http\Services\CicloService;
use App\Http\Services\CursoService;
use App\Http\Services\FacultadService;
use App\Http\Services\TipoCategoriaService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;


class ArchivoController extends Controller {
  protected $archivoService;
  protected $encuestaService;
  protected $personaService;
  protected $usuarioService;
  protected $horarioService;
  protected $cicloService;
  protected $cursoService;
  protected $facultadService;
  protected $tipoCategoriaService;

  public function __construct() {
    $this->archivoService =  new ArchivoService();
    $this->encuestaService =  new EncuestaService();
    $this->personaService =  new PersonaService();
    $this->usuarioService =  new UsuarioService();
    $this->horarioService =  new HorarioService();
    $this->cicloService =  new CicloService();
    $this->cursoService =  new CursoService();
    $this->facultadService =  new FacultadService();
    $this->tipoCategoriaService =  new TipoCategoriaService();

  }

  public function cargaMasivaHandler(Request $request) {
    /*
    1 -> investigaciones
    2 -> profesores
    3 -> cursos
    */
    $registroArray = $request->all();
    $registroValidator = \Validator::make($registroArray,
               ['tipo' => 'required', 'data' => 'required']);

    try {
      switch ($registroArray['tipo']) {
        case 1:
          return $this->cargaMasivaInvestigaciones($request);
        case 2:
          return $this->cargaMasivaProfesores($request);
        case 3:
          return $this->cargaMasivaCursos($request);
      }

    } catch (\Exception $e) {
      return response()->json(['status' => false, 'message'=> 'Error de servidor', 'body'=> $e->getMessage()], 500);
    }
  }

  public function cargaMasivaInvestigaciones(Request $request) {

    try {

      $registroArray = $request->all();

      $response = array();
      $investigaciones = $registroArray['data'];
      foreach($investigaciones as $inv) {
        $registro = new Investigacion();
        $registro->titulo = $inv['titulo'];
        $registro->abstract = $inv['abstract'];
        $registro->indicador_calidad = $inv['indicadorCalidad'];
        $registro->codigo_validacion = $inv['codigoValidacion'];
        $registro->otros_autores = $inv['otrosAutores'];
        $registro->idUsuario = $inv['codigoPucp'];
        $registro->save();
        array_push($response,$registro);
      }
      return response()->json(['status' => true, 'message'=> 'Las investigaciones se cargaron exitosamente', 'body'=> $response], 200);

    } catch (\Exception $e) {
      return response()->json(['status' => false, 'message'=> 'Error de servidor', 'body'=> $e->getMessage()], 500);
    }

  }

  public function cargaMasivaProfesores(Request $request) {

  }

  public function cargaMasivaCursos(Request $request) {

  }

  public function guardar(Request $request){
    set_time_limit(1000);
    DB::beginTransaction();
    try {
      //return $request;

      $archivoData =  $request->all();
      /***Lo dejo comentado porque solo funciona para el formato de archivo csv de ejemplo que yo he
        creado para encuestas, si el formato es distinto, no funcionará***/

      //return $this->encuestaService->storeEncuesta($archivoData['file']); //servicio para guardar el contenido del csv de encuestas en la DB
      $contentFile= $archivoData['file'];
      $encuestaData64Decoded  = base64_decode($contentFile);
      $encuestaData = array();
      foreach(explode("\n",$encuestaData64Decoded) as $line) {
          $encuestaData[] = str_getcsv($line, ",");
      }
      $cicloCero=false;
      $encuestaGuardada = false;
      $datos= array();

      Log::info("=============================INICIO====================================");
      foreach ($encuestaData as $i => $filaEncuestaData) {
          Log::info("-------------------------------------------------------------------");
          Log::info("Iteracion {$i}");
          //$i = 0 es para los encabezados
          if ($i==0 || $filaEncuestaData[0]==null){
            Log::info("No procesaremos esta iteración por ser un encabezado o por no tener data");
            continue;
          }
          Log::info("Procesaremos la data...");
          $obj = array('orden'=>null,'dniPersona'=>null,'nombreCompletoPersona'=>null,'nombreFacultad'=>null,
                        'codigoCurso'=>null,'nombreHorario'=>null,'numeroContestadosEncuesta'=>null,'numeroAlumnosEncuesta'=>null,
                        'puntajeFinalEncuesta'=>null,'nombreCiclo'=>null,'nombreCurso'=>null,'nombreCategoria'=>null);

          $obj['orden'] = $i;
          $obj['dniPersona'] =  intval($filaEncuestaData[0]);
          $obj['nombreCompletoPersona'] = $filaEncuestaData[1];
          $obj['nombreFacultad'] = $filaEncuestaData[3];
          $obj['codigoCurso'] = $filaEncuestaData[4];
          $obj['nombreHorario'] = $filaEncuestaData[6];
          $obj['numeroContestadosEncuesta'] = intval($filaEncuestaData[11]);
          $obj['numeroAlumnosEncuesta'] = intval($filaEncuestaData[12]);
          $obj['puntajeFinalEncuesta'] = doubleval($filaEncuestaData[14]);
          $obj['nombreCiclo'] = $filaEncuestaData[15];
          $obj['nombreCurso'] = $filaEncuestaData[16];
          $obj['nombreCategoria'] = $filaEncuestaData[19];

          $nombreCiclo = str_replace(' ', '', $obj['nombreCiclo']);
          $listaCiclo =  explode("-", $obj['nombreCiclo']);
          if (intval($listaCiclo[1])==0){
            $cicloCero=true;
            Log::info("Ignoramos la iteración porque pertenecia a un ciclo 0");
            continue;
          }
          $obj['nombreCiclo']=$nombreCiclo;
          $nombreCategoria = $this->tipoCategoriaService->obtenerNombreCategoriaPorAbrev($obj['nombreCategoria']);
          if($nombreCategoria==null){
            $nombreCategoria = $obj['nombreCategoria'];
            Log::info("No existe un nombre equivalente para la abreviatura de nombre categoria");
          }
          else{
            Log::info("El nombre de la categoria fue una abreviatura valida");
          }
          $tipoCategoria = $this->tipoCategoriaService->retrieveByNombre($nombreCategoria);
          if(!$tipoCategoria){
            Log::info("No existe el tipo categoria asi que se inserta en la db");
            $tipoCategoria = $this->tipoCategoriaService->createAndRetrieve(['nombre_categoria' => $nombreCategoria]);
          }
          else{
            Log::info("Ya existe el tipo categoria en la db");
          }
          $persona = $this->personaService->retrieveById($obj['dniPersona']);
          if(!$persona){
            Log::info("No existe la persona, asi que se insertara en la tb con su respectivo registro usuario");
            $partes = explode(" ", $obj['nombreCompletoPersona']);
            $tam =  count ($partes);
            $nombres = $partes[0];
            $apPaterno = $partes[$tam-2];
            $apMaterno = $partes[$tam-1];
            if ($tam > 3){
              for ($i=1; $i < $tam-2 ; $i++) {
                if ($partes[$i]!=null && $partes[$i]!=''){
                  $nombres= $nombres. " ".$partes[$i];
                }
              }
            }
            //return "nombres: ". $nombres." apellido paterno: ".$apPaterno." apellido materno: ".$apMaterno;
            $persona = $this->personaService->createAndRetrieve( array( 'id' => $obj['dniPersona'], 'nombres' => $nombres,'apPaterno'=> $apPaterno, 'apMaterno'=>$apMaterno ));
            $usuario = $this->usuarioService->createAndRetrieveSimple( array( 'idPersona' => $persona->id,'idCategoria'=>$tipoCategoria->id, 'idTipo'=>1 ));
          }
          else{
            Log::info("Ya existe la persona con ese dni");
            //tenemos que verificar si el model persona tiene,efectivamente, asociado un usuario
            if($persona->usuario){
              $usuario= $persona->usuario;
            }
            else{
              $usuario = $this->usuarioService->createAndRetrieveSimple( array( 'idPersona' => $persona->id,'idCategoria'=>$tipoCategoria->id, 'idTipo'=>1 ));
            }

          }
          $facultad = $this->facultadService->retrieveByNombre($obj['nombreFacultad']);
           if(!$facultad){
             Log::info("No existe una facultad con ese nombre asi que lo ingresaremos a la db");
             $facultad = $this->facultadService->createAndRetrieve(['nombreFacultad' => $obj['nombreFacultad']]);
           }
           else{
             Log::info("Ya existe una facultad con ese nombre");
           }
            $ciclo = $this->cicloService->retrieveByNombre($obj['nombreCiclo']);
            if(!$ciclo){
              Log::info("No existe un ciclo con ese nombre asi que lo ingresaremos a la db");
              $ciclo = $this->cicloService->createAndRetrieve(['ciclo' => $obj['nombreCiclo']]);
            }
            else{
              Log::info("Ya existe un ciclo con ese nombre");
            }
            $curso = $this->cursoService->retrieveByCodigo($obj['codigoCurso']);
            if(!$curso){
              Log::info("No existe un curso con ese codigo asi que lo ingresaremos a la db");
              $curso = $this->cursoService->createAndRetrieve([
                                            'codigo' => $obj['codigoCurso'],
                                            'nombre' => $obj['nombreCurso'],
                                            'idFacultad' => $facultad->id
                                          ]);
            }
            else{
              Log::info("Ya existe un curso con ese nombre");
            }
            $horario = $this->horarioService->retrieveByNombreYCicloId( strval($obj['nombreHorario']), $ciclo->id);
            if(!$horario){
              Log::info("No existe un horario con ese nombre y en ese determinado ciclo asi que lo ingresaremos a la db");
              $horario = $this->horarioService->createAndRetrieve(['nombre' => $obj['nombreHorario'],'idUsuario'=>$usuario->id,'idCiclo'=> $ciclo->id,'idCurso'=>$curso->id]);
            }
            else{
              Log::info("Ya existe un horario con ese nombre en ese ciclo");
              if ($horario->encuesta){
                Log::info("El horario ya tiene una encuesta registrada y no se guardara la informacion de la tabla encuesta, pero igual si es que hay info nueva de las otras tablas, se han guardado");
                continue;
              }
            }
            $encuesta = $this->encuestaService->createAndRetrieve([
                                      'numeroAlumnos' => $obj['numeroAlumnosEncuesta'],
                                      'numeroContestados' => $obj['numeroContestadosEncuesta'],
                                      'puntajeFinal' => $obj['puntajeFinalEncuesta'],
                                      'idHorario' => $horario->id
                                    ]);
            $encuestaGuardada = true;
            $cicloCero =false;
            Log::info("Se guardó una nueva encuesta");
            DB::commit();
      }
      $archivo = null;
      if (!$cicloCero && $encuestaGuardada){
        $archivo =  $this->archivoService->storeFile($archivoData);
        $message = 'Archivo guardado';
        return response()->json(['status' => true, 'message'=> $message, 'body'=> $archivo], 200);
      }
      else if ($cicloCero){
        $message = 'Archivo ni data guardados ni en la BD ni en el servidor debido a que todos los registros pertenecen a un ciclo 0';
        return response()->json(['status' => false, 'message'=> $message, 'body'=> null], 400);
      }
      else if(!$encuestaGuardada){
        $message = 'Archivo guardado, pero no se creó ninguna nueva encuesta porque todos los horarios de todo el archivo ya tenian una encuesta asociada. La data de las otras tablas, sí pudo haber sido guardada en la DB dependiendo de si estas ya existían anteriormente o no en la base de datos.';
        return response()->json(['status' => true, 'message'=> $message, 'body'=> $archivo], 200);
      }
    } catch (\Exception $e) {
      DB::rollback();
      return response()->json(['status' => FALSE, 'message'=> 'Error de servidor', 'body'=> $e->getMessage()], 500);
    }
  }
}
