<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Http\Models\Persona;

class AddSgdPersonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $persona = new Persona();
        $persona->id = 70662737;
        $persona->nombres = "Alvaro";
        $persona->apPaterno = "Calvo";
        $persona->apMaterno = "Aguilar";
        $persona->correo = "alvaro_ca19@hotmail.com";
        $persona->telefono = 948744348;
        $persona->fechaNacimiento = "1995-08-19";
        $persona->sexo = "M";
        $persona->pais = "Peru";
        $persona->save();

        $persona = new Persona();
        $persona->id = 83728191;
        $persona->nombres = "Jose";
        $persona->apPaterno = "Bejarano";
        $persona->apMaterno = "Carranza";
        $persona->correo = "jbejarano@gmail.com";
        $persona->telefono = 947382719;
        $persona->fechaNacimiento = "1994-09-19";
        $persona->sexo = "M";
        $persona->pais = "Peru";
        $persona->save();

        $persona = new Persona();
        $persona->id = 93849381;
        $persona->nombres = "Jean Piere";
        $persona->apPaterno = "Sullon";
        $persona->apMaterno = "Monteza";
        $persona->correo = "piero94@hotmail.com";
        $persona->telefono = 948372819;
        $persona->fechaNacimiento = "1994-09-21";
        $persona->sexo = "M";
        $persona->pais = "Peru";
        $persona->save();

        $persona = new Persona();
        $persona->id = 72839482;
        $persona->nombres = "Gonzalo";
        $persona->apPaterno = "Campos";
        $persona->apMaterno = "Acosta";
        $persona->correo = "gonzalo.campos@gmail.com";
        $persona->telefono = 938493817;
        $persona->fechaNacimiento = "1995-09-19";
        $persona->sexo = "M";
        $persona->pais = "Peru";
        $persona->save();

        $persona = new Persona();
        $persona->id = 72839281;
        $persona->nombres = "Gonzalo";
        $persona->apPaterno = "Sanchez";
        $persona->apMaterno = "Sanchez";
        $persona->correo = "gsanchez@gmail.com";
        $persona->telefono = 938472819;
        $persona->fechaNacimiento = "1995-06-14";
        $persona->sexo = "M";
        $persona->pais = "Peru";
        $persona->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
