<?php

namespace App\Http\Services;

use App\Http\Models\Archivo;

class ArchivoService {

  public function storeFile($fileData){

    $fileStringBase64= $fileData['file'];
    $fileData['urlArchivo'] = ""; // creo la propiedad urlArchivo por defecto para la data

    $archivo = Archivo::create($fileData); // se guarda en la base de datos el archivo  con los datos del arreglo y se lo retorna en una variable


    /*Vamos a guardar la imagen en una url*/
    if(array_key_exists('file',$fileData) ){
      $len = strlen($fileStringBase64);
      $str = substr($fileStringBase64,-($len-23));
      $str = $fileStringBase64;
      $fileToSave = base64_decode($str);
      $rutaServidor = '/var/www/html/sos_back/public/archivos/Profesores/';

      //$rutaLocal = 'C:\Users\Alvaro\Documents\SGD - Back\SOSware-backend\public\archivos\Profesores\\';
      $rutaServidor .= $archivo->id.$archivo->extension;
      //$rutaLocal .= $archivo->id.$archivo->extension;
      file_put_contents($rutaServidor,$fileToSave);
    }
    else{
      //$rutaServidor.= 'default.csv';
      $rutaLocal = 'default.csv';
    }

    $archivo->urlArchivo = $rutaServidor;
    $archivo->save();
    return $archivo;


  }

}
