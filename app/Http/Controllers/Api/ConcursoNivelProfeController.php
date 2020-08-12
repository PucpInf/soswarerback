<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\ConcursoNivelProfe;
use App\Providers\ConcursoNivelProfeProvider;
use DateTime;


class ConcursoNivelProfeController extends Controller
{
    protected $concursoProvider;
    public function __construct(){
        $this->concursoProvider = New ConcursoNivelProfeProvider();
    }

    public function guardarPostulante(Request $request){
        
        $postulante = new ConcursoNivelProfe();
        $postulante->idUsuario = $request->idUsuario;
        $postulante->idConcurso = $request->idConcurso;
        $postulante->estado = 'Pendiente';
        $postulante->requestType = $request->requestType;
        date_default_timezone_set('America/Lima');
        $date = new DateTime();
        $postulante->fechaRegistro = $date->format('m/d/Y');
        $postulante->save();

        $response = array(
            'status' => true,
            'message' => 'Resultado correcto amigos',
            'body' => $postulante
        );
        return response()->json($response);
    }
    public function get(){
        $upgrades = $this->concursoProvider->getUpgrades();
        return $upgrades;
    }
}
