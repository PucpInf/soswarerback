<?php

namespace App\Http\Services;

use App\Http\Models\Encuesta;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;

class EncuestaService {

  public function createAndRetrieve($encuestaData){

		$encuesta = new Encuesta($encuestaData);
		$encuesta->save();
		return $encuesta;
	}
  public function calculateMin($encuestas){
    $puntaje = $encuestas->min(function ($encuesta) {//vamos obtener el menor de los puntajes totales de cada encuesta
        return $encuesta->puntajeFinal;//con esta funcion obtenemos la suma de puntae los items de una determinada encuesta
    });
    return $puntaje;
  }
  public function calculateMax($encuestas){
    $puntaje = $encuestas->max(function ($encuesta) {//vamos obtener el menor de los puntajes totales de cada encuesta
        return $encuesta->puntajeFinal;//con esta funcion obtenemos la suma de puntae los items de una determinada encuesta
    });
    return $puntaje;
  }
  public function calculateAvg($encuestas){
    $puntaje = $encuestas->avg(function ($encuesta) {//vamos obtener el menor de los puntajes totales de cada encuesta
        return $encuesta->puntajeFinal;//con esta funcion obtenemos la suma de puntae los items de una determinada encuesta
    });
    return $puntaje;
  }

  public function retrieveEncuestasPorCadaHorario($horarios){
    $encuestas = $horarios->map(function ($horario, $key) {
        if($horario->encuesta){
          return $horario->encuesta;
        }


    });

    return $encuestas;
  }

  public function saveItemPuntajes($encuesta, $itemPuntajes){
    foreach ($itemPuntajes as $item) {
      $encuesta->items()->attach( $item['idItem'] , [ 'puntaje'=> $item['puntaje'] ]);
    }
  }
  public function storeEncuesta($contentFile){
    $encuestaData64Decoded  = base64_decode($contentFile);
    $encuestaData = array();
    foreach(explode("\n",$encuestaData64Decoded) as $line) {
        $encuestaData[] = str_getcsv($line, ";");
    }



    /*Campos por cada fila de encuestaData: correlacion con campos del csv*/
    /*

      [0]:Docente,
      [1]:Nombre,
      [3]:Facultad,
      [4]:Curso,
      [6]:Horario,
      [11]:Nro. Encu. Válidas,
      [12]:Nro. Matr.,
      [14]:Ptje. Final,
      [15]:CICLO,
      [16]:Nombre del curso final,
      [19]:Categoria,
      [20]:Dedicación (no se usará por ahora)

    */
    $datos= array();

    foreach ($encuestaData as $i => $filaEncuestaData) {
        //$i = 0 es para los encabezados
        if ($i==0 || $filaEncuestaData[0]==null) continue;

        $obj = array('orden'=>null,'dniPersona'=>null,'nombreCompletoPersona'=>null,'nombreFacultad'=>null,
                      'codigoCurso'=>null,'nombreHorario'=>null,'numeroContestadosEncuesta'=>null,'numeroAlumnosEncuesta'=>null,
                      'nombreCiclo'=>null,'nombreCurso'=>null,'nombreCategoria'=>null);

        $obj['orden'] = $i;
        $obj['dniPersona'] =  $filaEncuestaData[0];
        $obj['nombreCompletoPersona'] = $filaEncuestaData[1];
        $obj['nombreFacultad'] = $filaEncuestaData[3];
        $obj['codigoCurso'] = $filaEncuestaData[4];
        $obj['nombreHorario'] = $filaEncuestaData[6];
        $obj['numeroContestadosEncuesta'] = $filaEncuestaData[11];
        $obj['numeroAlumnosEncuesta'] = $filaEncuestaData[14];
        $obj['nombreCiclo'] = $filaEncuestaData[15];
        $obj['nombreCurso'] = $filaEncuestaData[16];
        $obj['nombreCategoria'] = $filaEncuestaData[19];



        array_push ($datos,$obj);

    }
    return $datos;


    $encuesta = Encuesta::create(['numeroAlumnos'=>$encuestaData[0],'numeroContestados' => $encuestaData[1],
                      'idHorario' => $encuestaData[2]]);

    $itemPuntajes= array();

    for ($i=3; $i < count($encuestaData) ; $i++) {
      $puntaje = array(['idItem'=>null,'puntaje'=>null]);
      $puntaje['idItem']= $encuestaData[$i];
      $i++;
      $puntaje['puntaje']= $encuestaData[$i];
      array_push($itemPuntajes, $puntaje);
    }


    $this->saveItemPuntajes($encuesta , $itemPuntajes);

  }
}
