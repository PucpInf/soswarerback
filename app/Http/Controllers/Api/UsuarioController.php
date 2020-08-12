<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Services\UsuarioService;
use App\Http\Services\CicloService;
use App\Http\Services\HorarioService;
use App\Http\Models\Usuario;
use App\Http\Models\Archivo;
use App\Http\Models\ArchivoConvocatoria;
use App\Http\Models\Persona;
use App\Http\Models\Departamento;
use App\Http\Models\Horario;
use App\Http\Models\Seccion;
use App\Http\Models\TipoCategoria;
use App\Http\Models\ApoyoEconomico;
use App\Http\Models\MotivoViaje;
use App\Http\Models\GradoAcademico;
use App\Http\Models\Investigacion;
use App\Http\Models\ExpedienteProfesional;
use App\Http\Models\DesarrolloDocente;
use App\Http\Models\HorarioDetalle;
use App\Http\Models\Postulante;

use App\Http\Services\PersonaService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Http\Services\EncuestaService;

use Socialite;





class UsuarioController extends Controller {
  protected $usuarioService;
  protected $personaService;
  protected $encuestaService;
  protected $cicloService;
  protected $horarioService;

  public function __construct() {
    $this->usuarioService= new UsuarioService();
    $this->personaService= new PersonaService();
    $this->encuestaService= new EncuestaService();
    $this->cicloService= new CicloService();
    $this->horarioService= new HorarioService();
  }

  public function registro(Request $request) {

    try {
      $usuarioRegistroArray= $request->all();
      $registroUsuarioValidator = \Validator::make($usuarioRegistroArray,
                    [ 'nombres'=> 'required','apPaterno' => 'required','apMaterno' => 'required','correo' => 'required|unique:persona',
					            'fechaNacimiento' => 'required', 'contrasena' =>'required']);

      if ($registroUsuarioValidator->fails()) {
        Log::critical("Error en la validacion del registro del usuario: {$registroUsuarioValidator->errors()}\n\n");
        return response()->json(['status' => false, 'message'=> 'Error en la validación del registro deL usuario.', 'body' => $registroUsuarioValidator->errors()], 422);
      }

      DB::beginTransaction();
      $usuarioRegistroArray['fechaNacimiento'] = date('Y-m-d',strtotime($usuarioRegistroArray['fechaNacimiento']));
      $persona = $this->personaService->createAndRetrieve($usuarioRegistroArray);
      $usuarioRegistroArray['contrasena'] =  bcrypt(  $usuarioRegistroArray['contrasena']);
      $usuario = $this->usuarioService->createAndRetrieve($usuarioRegistroArray);
      $usuario = $this->usuarioService->registerUsuarioPersona($persona,$usuario);
      $usuario->persona;
      DB::commit();


      return response()->json(['status' => true, 'message'=> 'Usuario registrado', 'body'=> $usuario], 200);
    } catch(\Exception $e) {
      DB::rollback();
      Log::critical("No se pudo guardar el usuario: {$e->getCode()}, {$e->getLine()},{$e->getMessage()}" . PHP_EOL . "\n");
      return response()->json(['status' => false, 'message'=> 'El usuario no pudo ser registrado', 'body' => $e->getMessage()], 500);
    }
  }

  public function registroUsuarioContratado(Request $request) {
    $registroArray = $request->all();
    $registroValidator = \Validator::make($registroArray,
               [ 'dni' => 'required', 'nombres' => 'required', 'apPaterno' => 'required',
                 'apMaterno' => 'required', 'correo' => 'required', 'telefono' => 'required',
                 'fechaNacimiento' => 'required', 'sexo' => 'required', 'img' => 'required']);
    if($registroValidator->fails()){
      $array = array(
        'status' => false,
        'message' => 'Error en validación de datos',
        'body' => $registroValidator->errors()
      );
    }
    else{
      if(Persona::where('id',$registroArray['dni'])->exists()){
        //Se actualizan los datos de la persona, si ya está el usuario también se actualiza, sino se crea uno
        $persona = Persona::where('id',$registroArray['dni'])->first();

        $persona->id = $registroArray['dni'];
        if($registroArray['nombres'] != null) $persona->nombres = $registroArray['nombres'];
        if($registroArray['apPaterno'] != null) $persona->apPaterno = $registroArray['apPaterno'];
        if($registroArray['apMaterno'] != null) $persona->apMaterno = $registroArray['apMaterno'];
        if($registroArray['correo'] != null) $persona->correo = $registroArray['correo'];
        if($registroArray['telefono'] != null) $persona->telefono = $registroArray['telefono'];
        if($registroArray['fechaNacimiento'] != null) $persona->fechaNacimiento = $registroArray['fechaNacimiento'];
        if($registroArray['sexo'] != null) $persona->sexo = $registroArray['sexo'];
        $persona->save();

        if(Usuario::where('idPersona',$registroArray['dni'])->exists()){
          $usuario = Usuario::where('idPersona',$registroArray['dni'])->first();
          if($registroArray['img'] != '0'){
            $len = strlen($registroArray['img']);
            $str = substr($registroArray['img'],-($len-23));
            $img = base64_decode($str);
            $ruta1 = '200.16.7.152/img/Usuarios/';
            $ruta2 = '/var/www/html/dist/img/Usuarios/';
            $ruta1 .= $usuario->id.'.jpg';
            $ruta2 .= $usuario->id.'.jpg';
            file_put_contents($ruta2,$img);
          }
          else {
            $ruta1 = '200.16.7.152/img/Usuarios/default.jpg';
          }
          $usuario->fotoPerfil = $ruta1;
          if($registroArray['areaInteres'] != null) $usuario->areaInteres = $registroArray['areaInteres'];
          if($registroArray['especializacion'] != null) $usuario->especializacion = $registroArray['especializacion'];
          if($registroArray['idSeccion'] != null) $usuario->idSeccion = $registroArray['idSeccion'];
          if($registroArray['tipoUsuario'] != null) $usuario->idTipo = $registroArray['tipoUsuario'];
          $usuario->idPersona = $registroArray['dni'];
          $usuario->save();
        }
        else{
          //nuevo usuario
          $usuario = new Usuario();
          $condition = false;
          $codigo = 0;
          while(!$condition){
            $year = date("Y");
            $random = rand(0,9999);
            if($random < 10) {
              $random = $random * 1000;
            }
            else if ($random >=10 && $random < 100) {
              $random = $random * 100;
            }
            else if ($random >= 100 && $random < 1000) {
              $random = $random * 10;
            }
            $codigo = $year*10000+$random;
            $verificador = Usuario::where('id',$codigo)->exists();
            if(!$verificador){
              $condition = true;
            }
          }
          $usuario->id = $codigo;
          $pass = $this->generateRandomString();
          $usuario->contrasena = bcrypt($pass);
          if($registroArray['img'] != '0') {
            $len = strlen($registroArray['img']);
            $str = substr($registroArray['img'],-($len-23));
            $img = base64_decode($str);
            $ruta1 = '200.16.7.152/img/Usuarios/';
            $ruta2 = '/var/www/html/dist/img/Usuarios/';
            $ruta1 .= $codigo.'.jpg';
            $ruta2 .= $codigo.'.jpg';
            file_put_contents($ruta2,$img);
          }
          else{
            $ruta1 = '200.16.7.152/img/Usuarios/default.jpg';
          }
          $usuario->fotoPerfil = $ruta1;
          if($registroArray['areaInteres'] != null) $usuario->areaInteres = $registroArray['areaInteres'];
          if($registroArray['especializacion'] != null) $usuario->especializacion = $registroArray['especializacion'];
          if($registroArray['idSeccion'] != null) $usuario->idSeccion = $registroArray['idSeccion'];
          $usuario->idCategoria = 1;
          $usuario->idPersona = $registroArray['dni'];
          if($registroArray['tipoUsuario'] != null) $usuario->idTipo = $registroArray['tipoUsuario'];
          $correoPucp = 'a'.$codigo.'@pucp.pe';
          $usuario->correoPucp = $correoPucp;
          $usuario->nuevoUsuario = true;
          //Guarda usuario en la BD
          $usuario->save();
          
          $correo = $request->correo;
          $mensaje = "Hola!<br>Las credenciales generadas para ti son las siguientes: <br><b>Usuario: </b>".$usuario->correoPucp."<br><b>Password: </b>".$pass."<br><br><br>Saludos,<br>SOSware";
          $mail = new PHPMailer(true);
          $mail->IsSMTP();
          $mail->SMTPDebug = 0;
          $mail->SMTPAuth = true;
          $mail->SMTPSecure = 'ssl'; //ssl para gmail
          $mail->Host = 'smtp.gmail.com';
          $mail->Port = 465; //Puede ser 465 o 587
          $mail->IsHTML(true);
          $mail->Username = 'sgd.pucp@gmail.com';
          $mail->Password = 'sgdsoftware';
          $mail->SetFrom("sgd.pucp@gmail.com", "SGD PUCP");
          $mail->addAddress($correo);
          $mail->Subject = "Bienvenido al Sistema de Gestion Docente!";
          $mail->Body = $mensaje;
          $mail->send();
        }
      }
      else{
        //Se crea un nuevo usuario, first Persona y then Usuario
        $persona = new Persona();
        $persona->id = $registroArray['dni'];
        if($registroArray['nombres'] != null) $persona->nombres = $registroArray['nombres'];
        if($registroArray['apPaterno'] != null) $persona->apPaterno = $registroArray['apPaterno'];
        if($registroArray['apMaterno'] != null) $persona->apMaterno = $registroArray['apMaterno'];
        if($registroArray['correo'] != null) $persona->correo = $registroArray['correo'];
        if($registroArray['telefono'] != null) $persona->telefono = $registroArray['telefono'];
        if($registroArray['fechaNacimiento'] != null) $persona->fechaNacimiento = $registroArray['fechaNacimiento'];
        if($registroArray['sexo'] != null) $persona->sexo = $registroArray['sexo'];
        $persona->save();

        $usuario = new Usuario();
          $condition = false;
          $codigo = 0;
          while(!$condition){
            $year = date("Y");
            $random = rand(0,9999);
            if($random < 10) {
              $random = $random * 1000;
            }
            else if ($random >=10 && $random < 100) {
              $random = $random * 100;
            }
            else if ($random >= 100 && $random < 1000) {
              $random = $random * 10;
            }
            $codigo = $year*10000+$random;
            $verificador = Usuario::where('id',$codigo)->exists();
            if(!$verificador){
              $condition = true;
            }
          }
          $usuario->id = $codigo;
          $pass = $this->generateRandomString();
          $usuario->contrasena = bcrypt($pass);
          if($registroArray['img'] != '0') {
            $len = strlen($registroArray['img']);
            $str = substr($registroArray['img'],-($len-23));
            $img = base64_decode($str);
            $ruta1 = '200.16.7.152/img/Usuarios/';
            $ruta2 = '/var/www/html/dist/img/Usuarios/';
            $ruta1 .= $codigo.'.jpg';
            $ruta2 .= $codigo.'.jpg';
            file_put_contents($ruta2,$img);
          }
          else{
            $ruta1 = '200.16.7.152/img/Usuarios/default.jpg';
          }
          $usuario->fotoPerfil = $ruta1;
          if($registroArray['areaInteres'] != null) $usuario->areaInteres = $registroArray['areaInteres'];
          if($registroArray['especializacion'] != null) $usuario->especializacion = $registroArray['especializacion'];
          if($registroArray['idSeccion'] != null) $usuario->idSeccion = $registroArray['idSeccion'];
          $usuario->idCategoria = 1;
          $usuario->idPersona = $registroArray['dni'];
          if($registroArray['tipoUsuario'] != null) $usuario->idTipo = $registroArray['tipoUsuario'];;
          $correoPucp = 'a'.$codigo.'@pucp.pe';
          $usuario->correoPucp = $correoPucp;
          $usuario->nuevoUsuario = true;
          //Guarda usuario en la BD
          $usuario->save();

          $correo = $request->correo;
          $mensaje = "Hola!<br>Las credenciales generadas para ti son las siguientes: <br><b>Usuario: </b>".$usuario->correoPucp."<br><b>Password: </b>".$pass."<br><br><br>Saludos,<br>SOSware";
          $mail = new PHPMailer(true);
          $mail->IsSMTP();
          $mail->SMTPDebug = 0;
          $mail->SMTPAuth = true;
          $mail->SMTPSecure = 'ssl'; //ssl para gmail
          $mail->Host = 'smtp.gmail.com';
          $mail->Port = 465; //Puede ser 465 o 587
          $mail->IsHTML(true);
          $mail->Username = 'sgd.pucp@gmail.com';
          $mail->Password = 'sgdsoftware';
          $mail->SetFrom("sgd.pucp@gmail.com", "SGD PUCP");
          $mail->addAddress($correo);
          $mail->Subject = "Bienvenido al Sistema de Gestion Docente!";
          $mail->Body = $mensaje;
          $mail->send();

      }

      $gg = Persona::where('id',$request->dni)->first();
      $wp = Usuario::where('idPersona',$gg->id)->first();

      $user = $this->usuarioService->retrieveByEmail($wp->correoPucp);
      $array = array(
        'status' => true,
        'message' => 'Usuario registrado correctamente',
        'body' => $user
      );
    }
    return response()->json($array);
  }

  public function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

  public function login(Request $request) {
    try {

      //$usuario = $this->usuarioService->retrieveByEmail($request['email']);

      $email=$request->email;
      $password=$request->password;
      $usuario = $this->usuarioService->retrieveByEmail($email);


      if($usuario != null && (Hash::check($password, $usuario->contrasena))) {
        Log::info('Usuario existe');

        return response()->json(['status' => true, 'message'=>'Sesión iniciada', 'body'=> $usuario], 200);
      }
      else {
        return response()->json(['status' => false, 'message'=> 'Credenciales incorrectos', 'body'=> null], 400);
      }

    }
    catch(\Exception $e) {
      Log::critical("No puede iniciar sesión: {$e->getCode()}, {$e->getLine()},{$e->getMessage()}" . PHP_EOL . "\n");
      return response()->json(['status' => false, 'message'=> 'Hubo un error', 'body' => $e->getMessage()], 500);
    }
  }

  public function listaProfesores(){
	  if(Usuario::where('idTipo',1)->orWhere('idTipo',3)->orWhere('idTipo',4)->exists()){

      $persons = Usuario::where('idTipo',1)->orWhere('idTipo',3)->orWhere('idTipo',4)->get();

      $usuarios = array();
      foreach($persons as $profesor){
        //El nombre se extra de la tabla Persona
        $nombreProfe = Persona::where('id',$profesor->idPersona)->first();
        //El departamento se obtiene a través del idSeccion del usuario
        $seccion = Seccion::where('id',$profesor->idSeccion)->first();
        $departamento = Departamento::where('id',$seccion['idDepartamento'])->first();
        //La categoria se obtiene a través del idCategoria
        $categoria = TipoCategoria::where('id',$profesor->idCategoria)->first();
        $data = array(
          "codigo" => $profesor->id,
          "nombre" => $nombreProfe->nombres.' '.$nombreProfe->apPaterno.' '.$nombreProfe->apMaterno,
          "departamento" => $departamento['nombre'],
          "seccion" => $seccion['nombre'],
          'categoria' => $categoria['nombre_categoria']
        );
        array_push($usuarios,$data);
      };
		  $response = array(
			  "status" => 200,
        "message" => "Lista de profesores",
			  "data" => $usuarios
		  );
	  }
	  else{
		  $response = array(
			  "status" => 404,
        "message" => "Lista de profesores vacía",
			  "data" => []
		  );
    }
	  return response()->json($response);
  }

  public function listaProfesoresEncuestas(){
    /*Codigo nuevo: despues del lab*/
    $profesores = $this->usuarioService->getTeachers();

    if (!$profesores || count($profesores)==0){
      return response()->json(array("status" => false,"message" => "Lista de profesores vacía", "body" => null),404);
    }
    foreach ($profesores as $profesor) {
      $encuestas = $profesor->usuario->encuestas;
      foreach($encuestas  as $encuesta){
        $encuesta->items;
      }
    }
    return response()->json(array("status" => true,"message" => "Lista de profesores", "data" => $profesores),200);

  }

  public function obtenerProfesorEncuestas($id){
    try {
      $profesor = $this->usuarioService->retrieveTeacherById($id);

      if (!$profesor){
        return response()->json(array("status" => false,"message" => "No se encontró el profesor", "data" => null),404);
      }
      $profesor->usuario;

      foreach ($profesor->usuario->encuestas as $encuesta ) {
        $encuesta->items;
      }
      return response()->json(array("status" => true,"message" => "Datos del profesor", "data" => $profesor),200);
    } catch (\Exception $e) {
        return response()->json(['status' => false, 'message' =>'Error de servidor', 'body' => $e->getMessage()],500);
    }


  }

  public function sacarDatosFila($id){

    $datosUsuario = Usuario::where('id',$id)->first();
    $datosPersona = Persona::where('id',$datosUsuario->idPersona)->first();

    $usuario = $this->usuarioService->retrieveByEmail($datosUsuario->correoPucp);
    if($usuario != null){
      return response()->json(['status' => true, 'message'=>'Usuario encontrado', 'body'=> $usuario], 200);
    }
    else{
      return response()->json(['status' => false, 'message' =>'Usuario no encontrado', 'body' => null],400);
    }
  }

  public function listaApoyosEconomicosSeccion($idSeccion){
    $solicitudesTotal = ApoyoEconomico::all();
    if($solicitudesTotal != null){
      $solicitudesSeccion = array();
      $count = 0;
      foreach($solicitudesTotal as $soli){
        $user = Usuario::where('id',$soli->idUsuario)->first();
        if($user['idSeccion'] == $idSeccion){
          $persona = Persona::where('id',$user->idPersona)->first();
          $seccion = Seccion::where('id',$user->idSeccion)->first();
          $departamento = Departamento::where('id',$seccion->idDepartamento)->first();
          $motivo = MotivoViaje::where('id',$soli->idMotivo)->first();
          $json = array(
            'id' => $soli->id,
            'codigo' => $user->id,
            'unidad' => $departamento->nombre,
            'nombres' => $persona->nombres.' '.$persona->apPaterno.' '.$persona->apMaterno,
            'motivo' => $motivo->descripcion,
            'monto' => $soli->montoSolicitado,
            'estado' => $soli->estado,
            'fechaViaje' => $soli->fechaViaje,
            'fechaEvento' => $soli->fechaEvento,
            'observacion' => $soli->observacion,
            'tipoPersonal' => $soli->tipoPersonal,
            'moneda' => $soli->moneda,
            'boleto' => $soli->boleto,
            'inscripcion' => $soli->inscripcion,
            'hospedaje' => $soli->hospedaje,
            'assistCard' => $soli->assistCard,
            'alimentosMovilidad' => $soli->alimentosMovilidad,
            'impuestos' => $soli->impuestos
          );
        array_push($solicitudesSeccion,$json);
        $count++;
      }
    }
      if($count>0){
        return response()->json(['status' => true, 'message' => 'Solicitudes por aprobar encontradas en la sección', 'body' => $solicitudesSeccion],200);
      }
      else{
        return response()->json(['status' => false, 'message' => 'Solicitudes por aprobar no encontradas en la sección', 'body' => $solicitudesSeccion],200);
      }
    }
    else{
      return response()->json(['status' => false, 'message' => 'No se encontraron solicitudes por aprobar', 'body' => null],400);
    }
  }

  public function muestraSolicitudesProfesor($idUsuario) {
    $apoyos = ApoyoEconomico::where('idUsuario',$idUsuario)->get();
    if($apoyos != null){
      $data = array();
      foreach($apoyos as $apoyo){
        $motivo = MotivoViaje::where('id',$apoyo->idMotivo)->first();
        $json = array(
          'id' => $apoyo->id,
          'montoSolicitado' => $apoyo->montoSolicitado,
          'fechaViaje' => $apoyo->fechaViaje,
          'fechaEvento' => $apoyo->fechaEvento,
          'observacion' => $apoyo->observacion,
          'estado' => $apoyo->estado,
          'idUsuario' => $apoyo->idUsuario,
          'montoAprobado' => $apoyo->montoAprobado,
          'tipoPersonal' => $apoyo->tipoPersonal,
          'motivo' => $motivo->descripcion,
          'moneda' => $apoyo->moneda,
          'fechaRespuesta' => $apoyo->fechaRespuesta
        );
        array_push($data,$json);
      }
      return response()->json(['status' => true, 'message' => 'El usuario cuenta con historial de solicitudes', 'body' => $data],200);
    }
    else{
      return response()->json(['status' => false, 'message' => 'El usuario no tiene solicitudes', 'body' => null],400);
    }
  }

  public function getDatosPersona($dni) {
    $persona = Persona::where('id',$dni)->first();
    if($persona != NULL){
      $sexo = '';
      if($persona->sexo == 'M')
        $sexo = 'Masculino';
      else
        $sexo = 'Femenino';
      $data = array(
        'id' => $persona->id,
        'nombres' => $persona->nombres,
        'apPaterno' => $persona->apPaterno,
        'apMaterno' => $persona->apMaterno,
        'correo' => $persona->correo,
        'telefono' => $persona->telefono,
        'fechaNacimiento' => $persona->fechaNacimiento,
        'sexo' => $sexo
      );
      $array = array(
        'status' => 200,
        'message' => 'Datos encontrados',
        'body' => $data
      );
    }
    else{
      $array = array(
        'status' => 404,
        'message' => 'Datos no encontrados',
        'body' => null
      );
    }
    return response()->json($array);
  }

  public function GetProfesoresCurso($profesorId,$departamentoId=null,$seccionId=null){
    try {


      $profesor = $this->usuarioService->retrieveProfesorById($profesorId);

      if(!$profesor){
        return response()->json(['status'=>false,'message' => "No se encontró el profesor",'data'=>null ],200);
      }
      $message="Horarios por profesor";
      $consultaHorario = Horario::where('idUsuario',$profesor->id);

      if($departamentoId!=null){
        $message .= ", departamento";
        //Se debe asegurar que los horarios obtenidos y el profesor obtenido sean del departamento y seccion seteadas en los parametros
        $consultaHorario = $consultaHorario->whereHas('curso',function($query)use ($departamentoId){
                                  $query->whereHas('seccion',function($queryPrima) use ($departamentoId){
                                      $queryPrima->where('seccion.idDepartamento',$departamentoId);
                                   });
                              });
                            /*})->whereHas('usuario',function($query)use ($departamentoId){
                                            $query->whereHas('seccion',function($queryPrima) use ($departamentoId){
                                                $queryPrima->where('seccion.idDepartamento',$departamentoId);
                                            });
                                });*/

        if($seccionId!=null){
            $message .= ", y sección";
            $consultaHorario = $consultaHorario->whereHas('curso',function($query) use ($seccionId){
              $query->where('idSeccion',$seccionId);
            });/*->whereHas('usuario',function($query) use ($seccionId){
              $query->where('idSeccion',$seccionId);
            });*/
        }
      }
      $horarios =  $consultaHorario->get();
      $message .= "  - (Ruta: /api/curso/GetProfesoresCurso/{idUsuario}}/{idDepartamento?}/{idSeccion?})";

      if(count($horarios)==0){
        return response()->json(['status'=>false,'message' => "No se encontraron horarios del curso",'data'=>null ],404);
      }
      $body =array();
      foreach ($horarios as $horario) {

        $obj = array ('idHorario'=>null,'horario'=>null,'codigoCurso'=>null,'nombreCurso'=>null,'idProfesor'=>null,
                      'nombreProfesor'=>null,'apPaternoProfesor'=>null,
                      'apMaternoProfesor' => null,'idCiclo' => null,'ciclo' => null,'idDepartamento' => null,
                      'nombreDepartamento' => null,'idSeccion' => null,'nombreSeccion' => null,'numeroMatriculados'=>null
                      ,'numeroEncuestasValidas' => null,'puntajeFinal' =>null,'horasDictado'=>null);
        $obj['idHorario'] = $horario->id;
        $obj['horario'] = $horario->nombre;
        //$obj['horasDictado'] = $this->horarioService->getDictatedHourByHorarioObj($horario);
        $obj['horasDictado'] = $horario['horas'];

        if($horario->ciclo){
          $obj['ciclo'] = $horario->ciclo->ciclo;
          $obj['idCiclo'] = $horario->ciclo->id;
        }
        if($horario->usuario){
          $persona = $horario->usuario->persona;
          $obj['idProfesor']= $horario->usuario->id;
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
      return response()->json(['status'=>true,'message' => $message,'data'=>$body ],200);
    } catch (\Exception $e) {
      return response()->json(['status' => false,'message'=> 'Hubo un error', 'data' => $e->getMessage()], 500);
    }

  }


  public function GetProfesoresCicloCurso($idUsuario, $idCiclo){
    /*y devuelva un campo más: horas de dictado del horario
    las horas de dictado por cada día de la semana*/

    try {
      $profesor = $this->usuarioService->retrieveProfesorById($idUsuario);
      if(!$profesor){
        return response()->json(['status'=>false,'message' => "No se encontró el profesor",'body'=>null ],404);
      }
      $ciclo = $this->cicloService->retrieveById($idCiclo);
      if(!$ciclo){
        return response()->json(['status'=>false,'message' => "No se encontró el ciclo",'body'=>null ],404);
      }
      $message="Horarios por profesor por ciclo";
      $consultaHorario = Horario::where('idUsuario',$profesor->id)->where('idCiclo',$ciclo->id);

      // if($departamentoId!=null){
      //   $message .= ", departamento";
      //   //Se debe asegurar que los horarios obtenidos y el profesor obtenido sean del departamento y seccion seteadas en los parametros
      //   $consultaHorario = $consultaHorario->whereHas('curso',function($query)use ($departamentoId){
      //                             $query->whereHas('seccion',function($queryPrima) use ($departamentoId){
      //                                 $queryPrima->where('seccion.idDepartamento',$departamentoId);
      //                              });
      //                         });
      //                       /*})->whereHas('usuario',function($query)use ($departamentoId){
      //                                       $query->whereHas('seccion',function($queryPrima) use ($departamentoId){
      //                                           $queryPrima->where('seccion.idDepartamento',$departamentoId);
      //                                       });
      //                           });*/
      //
      //   if($seccionId!=null){
      //       $message .= ", y sección";
      //       $consultaHorario = $consultaHorario->whereHas('curso',function($query) use ($seccionId){
      //         $query->where('idSeccion',$seccionId);
      //       });/*->whereHas('usuario',function($query) use ($seccionId){
      //         $query->where('idSeccion',$seccionId);
      //       });*/
      //   }
      // }
      $horarios =  $consultaHorario->get();
      $message .= "  - (Ruta: /api/curso/GetProfesoresCicloCurso/{idUsuario}}/{idCiclo})";

      if(count($horarios)==0){
        return response()->json(['status'=>false,'message' => "No se encontraron horarios del curso",'body'=>null ],404);
      }
      $body =array();
      foreach ($horarios as $horario) {

        $obj = array ('idHorario'=>null,'horario'=>null,'codigoCurso'=>null,'nombreCurso'=>null,'idProfesor'=>null,
                      'nombreProfesor'=>null,'apPaternoProfesor'=>null,
                      'apMaternoProfesor' => null,'idCiclo' => null,'ciclo' => null,'numeroMatriculados'=>null
                      ,'numeroEncuestasValidas' => null,'puntajeFinal' =>null);
        $obj['idHorario'] = $horario->id;
        $obj['horario'] = $horario->nombre;
        if($horario->ciclo){
          $obj['ciclo'] = $horario->ciclo->ciclo;
          $obj['idCiclo'] = $horario->ciclo->id;
        }
        if($horario->usuario){
          $persona = $horario->usuario->persona;
          $obj['idProfesor']= $horario->usuario->id;
          $obj['nombreProfesor']=$persona->nombres;
          $obj['apPaternoProfesor']=$persona->apPaterno;
          $obj['apMaternoProfesor']=$persona->apMaterno;
        }
        if($horario->curso){
          $curso =  $horario->curso;
          $obj['nombreCurso'] = $curso->nombre;
          $obj['codigoCurso'] = $curso->codigo;
          $seccion = $curso->seccion;

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

  public function registraPostulante(Request $request){

    $registroArray = $request->all();
    //Dentro del request también estará el CV a guardar
    $registroValidator = \Validator::make($registroArray,
               [ 'dni' => 'required', 'correo' => 'required',
                 'apPaterno' => 'required', 'apMaterno' => 'required',
                 'nombres' => 'required', 'sexo' => 'required',
                 'fechaNacimiento' => 'required', 'telefono' => 'required',
                 'idConvocatoria' => 'required', 'cv' => 'required']);
    if($registroValidator->fails()){
      $array = array(
        'status' => false,
        'message' => 'Error en validación de datos',
        'body' => $registroValidator->errors()
      );
    }
    //Primero se crea a la persona
    $persona = new Persona();
    $persona->id = $registroArray['dni'];
    $persona->nombres = $registroArray['nombres'];
    $persona->apPaterno = $registroArray['apPaterno'];
    $persona->apMaterno = $registroArray['apMaterno'];
    $persona->correo = $registroArray['correo'];
    $persona->telefono = $registroArray['telefono'];
    $persona->fechaNacimiento = $registroArray['fechaNacimiento'];
    $persona->sexo = $registroArray['sexo'];
    $persona->save();

    //Ahora se registra al postulante
    $postulante = new Postulante();
    $postulante->idPersona = $registroArray['dni'];
    $postulante->idConvocatoria = $registroArray['idConvocatoria'];
    $postulante->estado = 'Pendiente';
    $postulante->save();

    $people = Postulante::where('idPersona',$registroArray['dni'])->where('idConvocatoria',$registroArray['idConvocatoria'])->first();
    $idPostulante = $people->id;

    //Se guarda el archivo en la tabla Archivo, luego se ingresa un registro
    //en la tabla archivoConvocatoria

    $file = base64_decode($registroArray['cv']);
    $len = strlen($registroArray['cv']);
    $str = substr($registroArray['cv'],-($len-28));
    $img = base64_decode($str);
    $ruta1 = '200.16.7.152/archivos/Postulantes/';
    $ruta2 = '/var/www/html/dist/archivos/Postulantes/';
    //$ruta2 = 'C:/xampp/htdocs/backend/public/archivos/Postulantes/';
    $ruta1 .= $registroArray['dni'].'.pdf';
    $ruta2 .= $registroArray['dni'].'.pdf';
    file_put_contents($ruta2,$img);
    $fileName = $registroArray['dni'];

    $archivo = new Archivo();
    $archivo->nombreArchivo = $fileName;
    $archivo->urlArchivo = $ruta1;
    $archivo->extension = 'PDF';
    $archivo->save();

    $arch = Archivo::where('nombreArchivo',$fileName)->first();
    $idArchivo = $arch->id;

    $archivoConvocatoria = new ArchivoConvocatoria();
    $archivoConvocatoria->idArchivo = $idArchivo;
    $archivoConvocatoria->idConvocatoria = $registroArray['idConvocatoria'];
    $archivoConvocatoria->idPostulante = $idPostulante;
    $archivoConvocatoria->save();

    $mensaje = "Hola!<br>Tu postulación fue registrada.<br>Para mayor información revisa el cronograma de evaluación o contáctate con nosotros!"."<br><br><br>Saludos,<br>SOSware";
    $mail = new PHPMailer(true);
    $mail->IsSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'ssl'; //ssl para gmail
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 465; //Puede ser 465 o 587
    $mail->IsHTML(true);
    $mail->Username = 'sgd.pucp@gmail.com';
    $mail->Password = 'sgdsoftware';
    $mail->SetFrom("sgd.pucp@gmail.com", "SGD PUCP");
    $mail->addAddress($registroArray['correo']);
    $mail->Subject = "Postulaste a la convocatoria en el SGD!";
    $mail->Body = $mensaje;
    $mail->send();

    $response = array(
      'status' => true,
      'message' => 'Postulante registrado. Se envió confirmación al correo ingresado en el formulario',
      'body' => $postulante
    );

    return response()->json($response);
  }

  public function editarPerfil(Request $request){

    $registroArray = $request->all();
    $registroValidator = \Validator::make($registroArray,
               ['idUsuario' => 'required']);

    $idUsuario = $registroArray['idUsuario'];

    if(Usuario::where('id',$idUsuario)->exists()){

      $user = Usuario::where('id',$idUsuario)->first();

      //Grados academicos del usuario
      if($registroArray['grados'] != NULL){
        foreach($registroArray['grados'] as $grade){
          if($grade['nombre'] == "") break;
          $grado = new GradoAcademico();
          $grado->nombre = $grade['nombre'];
          $grado->institucion = $grade['institucion'];
          $grado->idUsuario = $idUsuario;
          $grado->save();
        }
      }

      //Docencia del usuario
      if($registroArray['docencia']!=NULL){
        foreach($registroArray['docencia'] as $docencia){
          if($docencia['departamento'] == "") break;
          $nuevaDocencia = new DesarrolloDocente();
          $nuevaDocencia->puesto_de_trabajo = $docencia['puesto_de_trabajo'];
          $nuevaDocencia->fecha_inicio = $docencia['fechaIniDU'];
          $nuevaDocencia->fecha_fin = $docencia['fechaFinDU'];
          $nuevaDocencia->idDepartamento = $docencia['departamento']['id'];
          $nuevaDocencia->idCategoria = $docencia['categoria']['id'];
          $nuevaDocencia->idUsuario = $idUsuario;
          $nuevaDocencia->save();
        }
      }

      if($registroArray['experienciaLaboral']!=NULL){
        foreach($registroArray['experienciaLaboral'] as $exp){
          if($exp['puesto'] == "") break;
          $nuevaExp = new ExpedienteProfesional();
          $nuevaExp->puesto_de_trabajo = $exp['puesto'];
          $nuevaExp->empresa = $exp['empresa'];
          $nuevaExp->fecha_inicio = $exp['fechaIniEL'];
          $nuevaExp->fecha_fin = $exp['fechaFinEL'];
          $nuevaExp->pais = $exp['pais'];
          $nuevaExp->idUsuario = $idUsuario;
          $nuevaExp->save();
        }
      }

      if($registroArray['investigaciones']!=NULL){
        foreach($registroArray['investigaciones'] as $investiga){
          if($investiga['titulo'] == "") break;
          $nuevaInv = new Investigacion();
          $nuevaInv->titulo = $investiga['titulo'];
          $nuevaInv->abstract = $investiga['abstract'];
          $nuevaInv->link = $investiga['link'];
          $nuevaInv->fecha_inicio = $investiga['fechaIniINV'];
          $nuevaInv->fecha_fin = $investiga['fechaFinINV'];
          $nuevaInv->idUsuario = $idUsuario;
          $nuevaInv->save();
        }
      }

      $usuario = $this->usuarioService->retrieveByEmail($user->correoPucp);

      $response = array(
        'status' => true,
        'message' => 'Datos del usuario actualizados correctamente',
        'body' => $usuario
      );
    }
    else{
      $response = array(
        'status' => false,
        'message' => 'Usuario no encontrado',
        'body' => null
      );

    }

    return response()->json($response);
  }

  public function getNombreById($idUsuario){

    if(Usuario::where('id',$idUsuario)->exists()){
      $usuario = Usuario::where('id',$idUsuario)->first();
      $persona = Persona::where('id',$usuario->idPersona)->first();
      $nombre = $persona->nombres.' '.$persona->apPaterno.' '.$persona->apMaterno;

      $response = array(
        'status' => true,
        'message' => 'Usuario encontrado',
        'body' => $nombre
      );
    }
    else{
      $response = array(
        'status' => false,
        'message' => 'Usuario no encontrado',
        'body' => null
      );
    }
    return response()->json($response);
  }

  public function cambiarPassword(Request $request){
    $usuario = Usuario::where('id',$request->idUsuario)->first();
    if((Hash::check($request->password, $usuario->contrasena))){
      $usuario->contrasena = bcrypt($request->nuevoPass);
      $usuario->nuevoUsuario = false;
      $usuario->save();

      $response = array(
        'status' => true,
        'message' => 'Contraseña actualizada correctamente.'
      );
    }
    else{
      $response = array(
        'status' => false,
        'message' => 'La contraseña actual ingresada no es la correcta'
      );
    }
    return response()->json($response);
  }

  public function GetProfesoresCursoJP($profesorId,$departamentoId=null,$seccionId=null){
    try {
      $profesor = $this->usuarioService->retrieveProfesorById($profesorId);
      if(!$profesor){
        return response()->json(['status'=>false,'message' => "No se encontró el profesor",'data'=>null ],404);
      }
      $message="Horarios por profesor";
      $consultaHorario = Horario::where('idUsuario',$profesor->id);

      if($departamentoId!=null){
        $message .= ", departamento";
        //Se debe asegurar que los horarios obtenidos y el profesor obtenido sean del departamento y seccion seteadas en los parametros
        $consultaHorario = $consultaHorario->whereHas('curso',function($query)use ($departamentoId){
                                  $query->whereHas('seccion',function($queryPrima) use ($departamentoId){
                                      $queryPrima->where('seccion.idDepartamento',$departamentoId);
                                   });
                              });
                            /*})->whereHas('usuario',function($query)use ($departamentoId){
                                            $query->whereHas('seccion',function($queryPrima) use ($departamentoId){
                                                $queryPrima->where('seccion.idDepartamento',$departamentoId);
                                            });
                                });*/

        if($seccionId!=null){
            $message .= ", y sección";
            $consultaHorario = $consultaHorario->whereHas('curso',function($query) use ($seccionId){
              $query->where('idSeccion',$seccionId);
            });/*->whereHas('usuario',function($query) use ($seccionId){
              $query->where('idSeccion',$seccionId);
            });*/
        }
      }
      $horarios =  $consultaHorario->get();
      $message .= "  - (Ruta: /api/curso/GetProfesoresCurso/{idUsuario}}/{idDepartamento?}/{idSeccion?})";

      if(count($horarios)==0){
        return response()->json(['status'=>false,'message' => "No se encontraron horarios del curso",'data'=>null ],404);
      }
      $body =array();
      foreach ($horarios as $horario) {

        $obj = array ('idHorario'=>null,'horario'=>null,'codigoCurso'=>null,'nombreCurso'=>null,'creditosCurso'=>null,
                      'idProfesor'=>null,'nombreProfesor'=>null,'apPaternoProfesor'=>null,
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
          $obj['idProfesor']= $horario->usuario->id;
          $obj['nombreProfesor']=$persona->nombres;
          $obj['apPaternoProfesor']=$persona->apPaterno;
          $obj['apMaternoProfesor']=$persona->apMaterno;
        }
        if($horario->curso){
          $curso =  $horario->curso;
          $obj['nombreCurso'] = $curso->nombre;
          $obj['codigoCurso'] = $curso->codigo;
          $obj['creditosCurso'] = $curso->creditosTot;
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
      return response()->json(['status'=>true,'message' => $message,'data'=>$body ],200);
    } catch (\Exception $e) {
      return response()->json(['status' => false,'message'=> 'Hubo un error', 'data' => $e->getMessage()], 500);
    }

  }

  public function editarMiPerfil(Request $request){

    $registroArray = $request->all();
    $registroValidator = \Validator::make($registroArray,
               ['idUsuario' => 'required']);

    $idUsuario = $registroArray['idUsuario'];

    if(Usuario::where('id',$idUsuario)->exists()){

      $user = Usuario::where('id',$idUsuario)->first();

      $user->areaInteres = $registroArray['areaInteres'];
      $user->especializacion = $registroArray['especializacion'];
      $idPersona = $user->idPersona;
      $persona = Persona::where('id',$idPersona)->first();
      $persona->telefono = $registroArray['telefono'];
      $persona->save();
      $user->save();

      //Grados academicos del usuario
      if($registroArray['grados'] != NULL){
        foreach($registroArray['grados'] as $grade){
          if($grade['nombre'] == "") break;
          $grado = new GradoAcademico();
          $grado->nombre = $grade['nombre'];
          $grado->institucion = $grade['institucion'];
          $grado->idUsuario = $idUsuario;
          $grado->save();
        }
      }

      //Docencia del usuario
      if($registroArray['docencia']!=NULL){
        foreach($registroArray['docencia'] as $docencia){
          if($docencia['departamento'] == "") break;
          $nuevaDocencia = new DesarrolloDocente();
          $nuevaDocencia->puesto_de_trabajo = $docencia['puesto_de_trabajo'];
          $nuevaDocencia->fecha_inicio = $docencia['fechaIniDU'];
          $nuevaDocencia->fecha_fin = $docencia['fechaFinDU'];
          $nuevaDocencia->idDepartamento = $docencia['departamento']['id'];
          $nuevaDocencia->idCategoria = $docencia['categoria']['id'];
          $nuevaDocencia->idUsuario = $idUsuario;
          $nuevaDocencia->save();
        }
      }

      if($registroArray['experienciaLaboral']!=NULL){
        foreach($registroArray['experienciaLaboral'] as $exp){
          if($exp['puesto'] == "") break;
          $nuevaExp = new ExpedienteProfesional();
          $nuevaExp->puesto_de_trabajo = $exp['puesto'];
          $nuevaExp->empresa = $exp['empresa'];
          $nuevaExp->fecha_inicio = $exp['fechaIniEL'];
          $nuevaExp->fecha_fin = $exp['fechaFinEL'];
          $nuevaExp->pais = $exp['pais'];
          $nuevaExp->idUsuario = $idUsuario;
          $nuevaExp->save();
        }
      }

      if($registroArray['investigaciones']!=NULL){
        foreach($registroArray['investigaciones'] as $investiga){
          if($investiga['titulo'] == "") break;
          $nuevaInv = new Investigacion();
          $nuevaInv->titulo = $investiga['titulo'];
          $nuevaInv->abstract = $investiga['abstract'];
          $nuevaInv->link = $investiga['link'];
          $nuevaInv->fecha_inicio = $investiga['fechaIniINV'];
          $nuevaInv->fecha_fin = $investiga['fechaFinINV'];
          $nuevaInv->idUsuario = $idUsuario;
          $nuevaInv->save();
        }
      }

      $usuario = $this->usuarioService->retrieveByEmail($user->correoPucp);

      $response = array(
        'status' => true,
        'message' => 'Datos del usuario actualizados correctamente',
        'body' => $usuario
      );
    }
    else{
      $response = array(
        'status' => false,
        'message' => 'Usuario no encontrado',
        'body' => null
      );

    }

    return response()->json($response);
  }

  public function getInfoPerfil($idUsuario){

    $user = Usuario::where('id',$idUsuario)->first();

    $email = $user->correoPucp;

    $usuario = $this->usuarioService->retrieveByEmail($email);

    $response = array(
      'status' => true,
      'message' => 'Info del usuario',
      'body' => $usuario
    );
    return response()->json($response);
  }

  public function agregaPresupuesto(Request $request){
    /*idSeccion, idDepartamento, monto
    si idSeccion está vacío, se le asigna al departamento,
    si está con data se agrega a la seccion */
    if($request->idSeccion != null) {
      $seccion = Seccion::where('id',$request->idSeccion)->first();
      $seccion->presupuesto = $request->monto;
      $seccion->save();

      $response =  array(
        'status' => true,
        'message' => 'Monto asignado a la sección',
        'body' => $seccion
      );
    }
    else{
      $departamento = Departamento::where('id',$request->idDepartamento)->first();
      $departamento->presupuesto = $request->monto;
      $departamento->save();

      $response = array(
        'status' => true,
        'message' => 'Monto asignado al departamento',
        'body' => $departamento
      );
    }
    return response()->json($response);
  }

  public function recuperarPass(Request $request){
    /*Recibo el correo registrado en la tabla persona */
    $persona = Persona::where('correo',$request->correo)->first();
    $usuario = Usuario::where('idPersona',$persona->id)->first();
    $pass = $this->generateRandomString();
    $usuario->contrasena = bcrypt($pass);
    $usuario->nuevoUsuario = true;
    $usuario->save();
    $correo = $request->correo;

    $mensaje = "Hola!<br>Tu nueva contraseña es esta:<br>".$pass."<br>";
    $mail = new PHPMailer(true);
    $mail->IsSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'ssl'; //ssl para gmail
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 465; //Puede ser 465 o 587
    $mail->IsHTML(true);
    $mail->Username = 'sgd.pucp@gmail.com';
    $mail->Password = 'sgdsoftware';
    $mail->SetFrom("sgd.pucp@gmail.com", "SGD PUCP");
    $mail->addAddress($correo);
    $mail->Subject = "Restauracion de contraseña";
    $mail->Body = $mensaje;
    $mail->send();

    $response = array(
      'status' => true,
      'message' => 'Contraseña reestablecida',
      'body' => $usuario
    );

  }

  public function googleLoginCallback(Request $request) {

    $registroArray = $request->all();
    $registroValidator = \Validator::make($registroArray,
               ['email' => 'required', 'idGoogle' => 'required']);

    try {

      $email=$request->email;
      $idGoogle = $request->idGoogle;

      $usuario = $this->usuarioService->retrieveByEmailAndGoogleId($email,$idGoogle);

      if($usuario != null) {
        return response()->json(['status' => true, 'message'=>'Sesión iniciada', 'body'=> $usuario], 200);
      }
      else {
        return response()->json(['status' => false, 'message'=> 'Usuario no autorizado', 'body'=> null], 400);
      }

    }
    catch(\Exception $e) {
      Log::critical("No puede iniciar sesión: {$e->getCode()}, {$e->getLine()},{$e->getMessage()}" . PHP_EOL . "\n");
      return response()->json(['status' => false, 'message'=> 'Hubo un error', 'body' => $e->getMessage()], 500);
    }
  }
}
