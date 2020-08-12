<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Http\Models\Persona;

class AddAdministradorPersonaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $persona = new Persona();
        $persona->id = 63816291;
        $persona->nombres = "Wen";
        $persona->apPaterno = "Lin";
        $persona->apMaterno = "Gao";
        $persona->correo = "xwen.15@gmail.com";
        $persona->telefono = 948251940;
        $persona->fechaNacimiento = "1994-08-15";
        $persona->sexo = "F";
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
