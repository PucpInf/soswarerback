<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Models\Convocatoria;
use App\Http\Models\Seccion;
use App\Http\Models\Usuario;
use App\Http\Models\Postulante;
use App\Http\Models\Persona;
use App\Http\Models\Departamento;
use App\Http\Controllers\Controller;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ConvocatoriaController extends Controller {

    public function registraConvocatoria(Request $request){
        $registroArray = $request->all();
        //Not sure el area
        $registroValidator = \Validator::make($registroArray,
               [ 'titulo' => 'required', 'requisitos' => 'required',
                 'documentacion' => 'required', 'puestoTrabajo' => 'required',
                 'responsabilidades' => 'required', 'beneficios' => 'required',
                 'fechaInicio' => 'required', 'fechaFin' => 'required',
                 'fechaResultado' => 'required', 'fechaPreSeleccion' => 'required',
                 'idSeccion' => 'required', 'cantidad' => 'required',
                 'evaluacion' => 'required', 'idUsuario' => 'required']);
        if($registroValidator->fails()){
            $array = array(
                'status' => false,
                'message' => 'Error en la validación de datos',
                'body' => $registroValidator->errors()
            );
        }
        else{
            $convocatoria = new Convocatoria();
            $convocatoria->titulo = strtoupper($registroArray['titulo']);
            $convocatoria->puestoTrabajo = $registroArray['puestoTrabajo'];
            $convocatoria->requisitos = $registroArray['requisitos'];
            $convocatoria->idSeccion = $registroArray['idSeccion'];
            $convocatoria->documentacion = $registroArray['documentacion'];
            $convocatoria->responsabilidades = $registroArray['responsabilidades'];
            $convocatoria->beneficios = $registroArray['beneficios'];
            $convocatoria->fecha_inicio_act = $registroArray['fechaInicio'];
            $convocatoria->fecha_fin_post = $registroArray['fechaFin'];
            $convocatoria->fechaResultado = $registroArray['fechaResultado'];
            $convocatoria->fechaPreSeleccion = $registroArray['fechaPreSeleccion'];
            $convocatoria->cantidad = $registroArray['cantidad'];
            $convocatoria->evaluacion = $registroArray['evaluacion'];
            $convocatoria->link = 'http://200.16.7.152/convocatoriaDocentes/index4.html';
            $convocatoria->estado = "Abierta";
            $convocatoria->save();

            $array = array(
                'status' => true,
                'message' => 'Convocatoria creada correctamente',
                'body' => $convocatoria                
            );

            $usuario = Usuario::where('id',$registroArray['idUsuario'])->first();
            $correo = $usuario->correoPucp;
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
            $mail->Subject = "Confirmacion de convocatoria docente";
            $mail->Body = "Hola!<br>La convocatoria ha sido creada correctamente. Podrás compartirla a través de este link: <br>http://200.16.7.152/convocatoriaDocentes/index4.html<br>";
            //$mail->send();
        }

        return response()->json($array);
    }

    public function getDatosConvocatoria() {
        $convo = Convocatoria::all()->last();
        if($convo != null) {
            $seccion = Seccion::where('id',$convo->idSeccion)->first();
            $departamento = Departamento::where('id',$seccion->idDepartamento)->first();
            $requisitos = $convo->requisitos;
            $documentacion = $convo->documentacion;
            $responsabilidades = $convo->responsabilidades;
            $beneficios = $convo->beneficios;
            $evaluacion = $convo->evaluacion;
            $requisitosLinea = array();
            $documentosLinea = array();
            $responsabilidadesLinea = array();
            $beneficiosLinea = array();
            $evaluacionLinea = array();
            foreach(preg_split("/((\r?\n)|(\r\n?))/",$requisitos) as $linea) {
                $requisito = array(
                    'requisito' => $linea
                );
                array_push($requisitosLinea,$requisito);
            }
            foreach(preg_split("/((\r?\n)|(\r\n?))/",$documentacion) as $linea) {
                $doc = array(
                    'documento' => $linea
                );
                array_push($documentosLinea,$doc);
            }
            foreach(preg_split("/((\r?\n)|(\r\n?))/",$responsabilidades) as $linea) {
                $resp = array(
                    'responsabilidad' => $linea
                );
                array_push($responsabilidadesLinea,$resp);
            }
            foreach(preg_split("/((\r?\n)|(\r\n?))/",$beneficios) as $linea) {
                $benef = array(
                    'beneficio' => $linea
                );
                array_push($beneficiosLinea,$benef);
            }
            foreach(preg_split("/((\r?\n)|(\r\n?))/",$evaluacion) as $linea) {
                $eval = array(
                    'evaluacion' => $linea
                );
                array_push($evaluacionLinea,$eval);
            }
            $data = array(
                'idConvocatoria' => $convo->id,
                'titulo' => $convo->titulo,
                'puestoTrabajo' => $convo->puestoTrabajo,
                'fechaInicio' => $convo->fecha_inicio_act,
                'fechaFin' => $convo->fecha_fin_post,
                'fechaResultado' => $convo->fechaResultado,
                'fechaPreSeleccion' => $convo->fechaPreSeleccion,
                'link' => $convo->link,
                'seccion' => $seccion->nombre,
                'departamento' => $departamento->nombre,
                'correo' => $seccion->correo,
                'anexo' => $seccion->anexo,
                'cantidad' => $convo->cantidad,
                'requisitos' => $requisitosLinea,
                'documentos' => $documentosLinea,
                'responsabilidades' => $responsabilidadesLinea,
                'beneficios' => $beneficiosLinea,
                'evaluacion' => $evaluacionLinea
            );
            $response = array(
                'status' => true,
                'message' => 'Convocatoria encontrada',
                'body' => $data
            );
        }
        else{
            $response = array(
                'status' => false,
                'message' => 'Convocatoria no encontrada',
                'body' => null
            );
        }
        return response()->json($response);
    }

    public function GetPostulantesDepartamento($dep)
    {
        try
        {
            $dep = Departamento::where('id',$dep)->first();
            $dep->secciones;
            $body =array();
            foreach ($dep->secciones as &$sec) {
            //estado pendiente se va a cambiar
            //$convocatorias = Convocatoria::where('idSeccion',$sec->id)->where('estado','pendiente')->get();
            $convocatorias = Convocatoria::where('idSeccion',$sec->id)->where('estado',false)->get();
            if (count($convocatorias))
            {
                foreach ($convocatorias as &$con) {
                    $postulantes = $con->postulantes;
                    if (count($postulantes))
                    {
                        foreach ($con->postulantes as &$pos) {
                            $pos->persona;
                            $pos->convocatoria;
                            array_push($body,$pos);
                        }
                    }
                }
            }
            }
            return response()->json(['status' => true,
                'message'=> 'Postulantes por Departamento',
                'body'=> $body],

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

    public function listaConvocatoriasSeccion($idSeccion){

        $convocatorias = array();
        $convos = Convocatoria::where('idSeccion',$idSeccion)->get();
        if($convos){
            foreach($convos as $gg){
                //gg es la convocatoria
                $postulantes = Postulante::where('idConvocatoria',$gg->id)->count();
                $seccion = Seccion::where('id',$gg->idSeccion)->first();
                $requisitosLinea = array();
                $documentosLinea = array();
                $responsabilidadesLinea = array();
                $beneficiosLinea = array();
                $evaluacionLinea = array();
                foreach(preg_split("/((\r?\n)|(\r\n?))/",$gg->requisitos) as $linea) {
                    $requisito = array(
                        'requisito' => $linea
                    );
                    array_push($requisitosLinea,$requisito);
                }
                foreach(preg_split("/((\r?\n)|(\r\n?))/",$gg->documentacion) as $linea) {
                    $doc = array(
                        'documento' => $linea
                    );
                    array_push($documentosLinea,$doc);
                }
                
                foreach(preg_split("/((\r?\n)|(\r\n?))/",$gg->responsabilidades) as $linea) {
                    $resp = array(
                        'responsabilidad' => $linea
                    );
                    array_push($responsabilidadesLinea,$resp);
                    
                }
                foreach(preg_split("/((\r?\n)|(\r\n?))/",$gg->beneficios) as $linea) {
                    $benef = array(
                        'beneficio' => $linea
                    );
                    array_push($beneficiosLinea,$benef);
                }
                foreach(preg_split("/((\r?\n)|(\r\n?))/",$gg->evaluacion) as $linea) {
                    $eval = array(
                        'evaluacion' => $linea
                    );
                    array_push($evaluacionLinea,$eval);
                }
                $data = array(
                    'id' => $gg->id,
                    'titulo' => $gg->titulo,
                    'requisitos' => $requisitosLinea,
                    'puestoTrabajo' => $gg->puestoTrabajo,
                    'fecha_inicio_act' => $gg->fecha_inicio_act,
                    'fecha_fin_post' => $gg->fecha_fin_post,
                    'link' => $gg->link,
                    'plazas' => $gg->cantidad,
                    'documentacion' => $documentosLinea,
                    'responsabilidades' => $responsabilidadesLinea,
                    'beneficios' => $beneficiosLinea,
                    'fechaResultado' => $gg->fechaResultado,
                    'fechaPreSeleccion' => $gg->fechaPreSeleccion,
                    'evaluaciones' => $evaluacionLinea,
                    'estado' => $gg->estado,
                    'seccion' => $seccion->nombre,
                    'nPostulantes' => $postulantes,
                    'fechaCreacion' => $gg->fechaCreacion
                );
                
                array_push($convocatorias,$data);
            }           
        }

        $response = array(
            'status' => true,
            'message' => 'Convocatorias encontradas en el departamento',
            'data' => $convocatorias
        );

        return response()->json($response);
    }

    public function listaConvocatoriasActivas($idDepartamento){
        //Sacar todos los ids de las secciones del departamento
        $secciones = Seccion::where('idDepartamento',$idDepartamento)->get();

        $convocatorias = array();
        foreach($secciones as $bua){
            $idSeccion = $bua->id;
            $convos = Convocatoria::where('idSeccion',$idSeccion)->get();
            if($convos){
                foreach($convos as $gg){
                    //gg es la convocatoria
                    $postulantes = Postulante::where('idConvocatoria',$gg->id)->count();
                    $seccion = Seccion::where('id',$gg->idSeccion)->first();
                    $requisitosLinea = array();
                    $documentosLinea = array();
                    $responsabilidadesLinea = array();
                    $beneficiosLinea = array();
                    $evaluacionLinea = array();
                    foreach(preg_split("/((\r?\n)|(\r\n?))/",$gg->requisitos) as $linea) {
                        $requisito = array(
                            'requisito' => $linea
                        );
                        array_push($requisitosLinea,$requisito);
                    }
                    foreach(preg_split("/((\r?\n)|(\r\n?))/",$gg->documentacion) as $linea) {
                        $doc = array(
                            'documento' => $linea
                        );
                        array_push($documentosLinea,$doc);
                    }
                    
                    foreach(preg_split("/((\r?\n)|(\r\n?))/",$gg->responsabilidades) as $linea) {
                        $resp = array(
                            'responsabilidad' => $linea
                        );
                        array_push($responsabilidadesLinea,$resp);
                        
                    }
                    foreach(preg_split("/((\r?\n)|(\r\n?))/",$gg->beneficios) as $linea) {
                        $benef = array(
                            'beneficio' => $linea
                        );
                        array_push($beneficiosLinea,$benef);
                    }
                    foreach(preg_split("/((\r?\n)|(\r\n?))/",$gg->evaluacion) as $linea) {
                        $eval = array(
                            'evaluacion' => $linea
                        );
                        array_push($evaluacionLinea,$eval);
                    }
                    $data = array(
                        'id' => $gg->id,
                        'titulo' => $gg->titulo,
                        'requisitos' => $requisitosLinea,
                        'puestoTrabajo' => $gg->puestoTrabajo,
                        'fecha_inicio_act' => $gg->fecha_inicio_act,
                        'fecha_fin_post' => $gg->fecha_fin_post,
                        'link' => $gg->link,
                        'plazas' => $gg->cantidad,
                        'documentacion' => $documentosLinea,
                        'responsabilidades' => $responsabilidadesLinea,
                        'beneficios' => $beneficiosLinea,
                        'fechaResultado' => $gg->fechaResultado,
                        'fechaPreSeleccion' => $gg->fechaPreSeleccion,
                        'evaluaciones' => $evaluacionLinea,
                        'estado' => $gg->estado,
                        'seccion' => $seccion->nombre,
                        'nPostulantes' => $postulantes,
                        'fechaCreacion' => $gg->fechaCreacion
                    );
                    
                    array_push($convocatorias,$data);
                }           
            } 
        }

        $response = array(
            'status' => true,
            'message' => 'Convocatorias encontradas en el departamento',
            'data' => $convocatorias
        );

        return response()->json($response);
    }

    public function getPostulantesPorConvocatoria($idConvocatoria){
        $postulantes = Postulante::where('idConvocatoria',$idConvocatoria)->get();
        if($postulantes){
            $data = array();
            foreach($postulantes as $gg){
                //gg es un registro de la tabla Postulante
                $persona = Persona::where('id',$gg->idPersona)->first();
                $nombre = $persona->nombres.' '.$persona->apPaterno.' '.$persona->apMaterno;
                $link = '200.16.7.152/archivos/Postulantes/'.$gg->idPersona.'.pdf';
                $persona = array(
                    'id' => $gg->idPersona,
                    'nombre' => $nombre,
                    'telefono' => $persona->telefono,
                    'cv' => $link,
                    'estado' => $gg->estado,
                    'idPostulante' => $gg->id
                );
                array_push($data,$persona);
            }
            $response = array(
                'status' => true,
                'message' => 'Postulantes encontrados para la convocatoria',
                'data' => $data
            );
        }
        else{
            $nombre = 'No se encontraron postulantes';
            $data = array(
                'id' => $nombre,
                'nombre' => $nombre,
                'telefono' => $nombre,
                'cv' => $nombre,
                'estado' => $nombre,
                'idPostulante' => $gg->id
            );
            $response = array(
                'status' => true,
                'message' => 'No se encontraron postulantes',
                'data' => $data
            );

        }
        return response()->json($response);
    }

    public function aprobarPostulantes(Request $request){
        /*idConvocatoria, idPersona, datos de la fila, cambiar estado */
                
    }

    public function seleccion(Request $request)
    {
        // return $request;
        try
        {
            $postulantes = $request->postulantes;
            //return $postulantes;
            foreach ($postulantes as &$post) {
                if($post['aprobar']){
                    // return $post['id'];
                    Postulante::where('id',$post['idPostulante'])->update(['estado' => 'Elegido']);
                }
                else{
                    // return $post['id'];
                    Postulante::where('id',$post['idPostulante'])->update(['estado' => 'Rechazado']);
                }
            }
            Convocatoria::where('id',$request->convocatoria)->update(['estado' => 'Finalizada']);
            return response()->json(['status' => true,
                'message'=> 'Seleccion Guardada'],
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
}
