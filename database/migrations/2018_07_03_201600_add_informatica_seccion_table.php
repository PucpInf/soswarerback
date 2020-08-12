<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Http\Models\Seccion;

class AddInformaticaSeccionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $seccion = new Seccion();
        $seccion->nombre = "Informática";
        $seccion->idDepartamento = 1;
        $seccion->presupuesto = 8000;
        $seccion->anexo = 4211;
        $seccion->correo = "informatica@pucp.pe";
        $seccion->save();
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
