<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Http\Models\Departamento;

class AddIngenieriaDepaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $depa = new Departamento();
        $depa->nombre = "Ingeniería";
        $depa->presupuesto = 40000;
        $depa->anexo = 1234;
        $depa->correo = "ingenieria@pucp.pe";
        $depa->save();
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
